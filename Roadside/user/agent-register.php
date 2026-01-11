<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

$msg = "";

/* ================= AJAX HANDLER ================= */
if (isset($_POST['ajax_action'])) {

    if ($_POST['ajax_action'] === 'get_districts') {
        $province_id = intval($_POST['province_id']);
        $res = mysqli_query($con, "SELECT district_id,district_name FROM district WHERE province_id='$province_id' ORDER BY district_name");
        echo '<option value="">Select District</option>';
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<option value='{$row['district_id']}'>{$row['district_name']}</option>";
        }
        exit;
    }

    if ($_POST['ajax_action'] === 'get_cities') {
        $district_id = intval($_POST['district_id']);
        $res = mysqli_query($con, "SELECT city_id,city_name FROM city WHERE district_id='$district_id' ORDER BY city_name");
        echo '<option value="">Select City</option>';
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<option value='{$row['city_id']}'>{$row['city_name']}</option>";
        }
        exit;
    }
}

/* ================= AGENT REGISTRATION ================= */
if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $phone    = mysqli_real_escape_string($con, $_POST['phone']);
    $password = md5($_POST['password']); // keep md5 for compatibility
    $reg_date = date('Y-m-d');

    $province_id = intval($_POST['province']);
    $district_id = intval($_POST['district']);
    $city_id     = intval($_POST['city']);

    // Duplicate check
    $check = mysqli_query($con, "SELECT user_id FROM users WHERE email='$email' OR phone='$phone'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "Email or phone already registered!";
    } else {

        // Insert as AGENT (inactive by default)
        $insertUser = mysqli_query($con, "
            INSERT INTO users(username,email,password,phone,role,registration_date,status)
            VALUES('$username','$email','$password','$phone','agent','$reg_date','inactive')
        ");

        if ($insertUser) {

            $user_id = mysqli_insert_id($con);

            // User location
            mysqli_query($con, "
                INSERT INTO user_location(user_id,province_id,district_id,city_id,created_at)
                VALUES('$user_id','$province_id','$district_id','$city_id',NOW())
            ");

            // Upload certificate
            $cert_file = "";
            if (!empty($_FILES['cert_file']['name'])) {
                $dir = "../uploads/";
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $cert_file = time() . "_" . basename($_FILES['cert_file']['name']);
                move_uploaded_file($_FILES['cert_file']['tmp_name'], $dir . $cert_file);
            }

            // Agent table
            mysqli_query($con, "
                INSERT INTO agent(user_id,status,approved_by_admin,approved_date,disabled_remarks)
                VALUES('$user_id','inactive',NULL,NULL,'')
            ");

            $agent_id = mysqli_insert_id($con);

            // Agent location
            mysqli_query($con, "
                INSERT INTO agent_location(agent_id,city_id)
                VALUES('$agent_id','$city_id')
            ");

            $msg = "Agent registration submitted. Please wait for admin approval.";
        } else {
            $msg = "Something went wrong. Try again.";
        }
    }
}

/* ================= PROVINCES ================= */
$province_q = mysqli_query($con, "SELECT province_id,province_name FROM province ORDER BY province_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Agent Registration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="../../favicon.ico">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
:root{
    --primary:#0A924E;
    --secondary:#0b2e59;
    --dark:#071a2d;
    --white:#fff;
    --light-gray:#f4f7fb;
}

body{
    font-family:Segoe UI, sans-serif;
    background:var(--light-gray);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.container{
    max-width:650px;
    width:100%;
    background:var(--white);
    padding:30px;
    border-radius:14px;
    box-shadow:0 12px 30px rgba(7,26,45,.15);
}

h2{
    text-align:center;
    color:var(--secondary);
    margin-bottom:20px;
}

label{
    font-weight:600;
    margin-top:10px;
}

input,select{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1.5px solid #cfd9e6;
    margin-top:5px;
}

input:focus,select:focus{
    outline:none;
    border-color:var(--primary);
}

input[type=submit]{
    margin-top:20px;
    background:var(--primary);
    color:#fff;
    font-weight:700;
    border:none;
    cursor:pointer;
}

.msg{
    text-align:center;
    font-weight:600;
    margin-bottom:15px;
    color:#b42318;
}
</style>
</head>

<body>
<div class="container">

<h2>Agent Registration</h2>

<?php if($msg): ?>
<p class="msg"><?= $msg ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<label>Username</label>
<input type="text" name="username" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Phone</label>
<input type="tel" name="phone" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Province</label>
<select name="province" id="province" required>
<option value="">Select Province</option>
<?php while($p=mysqli_fetch_assoc($province_q)): ?>
<option value="<?= $p['province_id'] ?>"><?= $p['province_name'] ?></option>
<?php endwhile; ?>
</select>

<label>District</label>
<select name="district" id="district" required>
<option value="">Select District</option>
</select>

<label>City</label>
<select name="city" id="city" required>
<option value="">Select City</option>
</select>

<label>Upload Certification</label>
<input type="file" name="cert_file" accept=".pdf,.jpg,.png" required>

<input type="submit" name="register" value="Submit Registration">

</form>
</div>

<script>
$('#province').change(function(){
    let pid=$(this).val();
    $('#district').html('<option>Loading...</option>');
    $('#city').html('<option>Select City</option>');
    if(pid){
        $.post('',{ajax_action:'get_districts',province_id:pid},res=>{
            $('#district').html(res);
        });
    }
});

$('#district').change(function(){
    let did=$(this).val();
    $('#city').html('<option>Loading...</option>');
    if(did){
        $.post('',{ajax_action:'get_cities',district_id:did},res=>{
            $('#city').html(res);
        });
    }
});
</script>

</body>
</html>

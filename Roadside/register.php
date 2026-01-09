<?php
session_start();
include(__DIR__ . '/includes/dbconnection.php');

$msg = '';

// Fetch Provinces, Districts, Cities
$province_q = mysqli_query($con, "SELECT province_id, province_name FROM province ORDER BY province_name ASC");
$district_q = mysqli_query($con, "SELECT district_id, district_name, province_id FROM district ORDER BY district_name ASC");
$city_q     = mysqli_query($con, "SELECT city_id, city_name, district_id FROM city ORDER BY city_name ASC");

// Handle AJAX for district / city filtering
if(isset($_POST['ajax_action'])){
    if($_POST['ajax_action'] == 'get_districts'){
        $province_id = intval($_POST['province_id']);
        $res = mysqli_query($con, "SELECT * FROM district WHERE province_id='$province_id' ORDER BY district_name ASC");
        echo '<option value="">Select District</option>';
        while($row = mysqli_fetch_assoc($res)){
            echo "<option value='{$row['district_id']}'>{$row['district_name']}</option>";
        }
        exit;
    }
    if($_POST['ajax_action'] == 'get_cities'){
        $district_id = intval($_POST['district_id']);
        $res = mysqli_query($con, "SELECT * FROM city WHERE district_id='$district_id' ORDER BY city_name ASC");
        echo '<option value="">Select City</option>';
        while($row = mysqli_fetch_assoc($res)){
            echo "<option value='{$row['city_id']}'>{$row['city_name']}</option>";
        }
        exit;
    }
}

// Handle registration form submission
if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $phone    = mysqli_real_escape_string($con, $_POST['phone']);
    $password = md5($_POST['password']); // keep md5 as per your login system
    $role     = $_POST['role']; // user / agent
    $status   = ($role === 'agent') ? 'inactive' : 'active'; // agent inactive until admin approval
    $reg_date = date('Y-m-d');

    $province_id = intval($_POST['province']);
    $district_id = intval($_POST['district']);
    $city_id     = intval($_POST['city']);

    // Check duplicate
    $check = mysqli_query($con, "SELECT user_id FROM users WHERE email='$email' OR phone='$phone'");
    if(mysqli_num_rows($check) > 0){
        $msg = "Email or phone already registered!";
    } else {
        $insertUser = mysqli_query($con, "
            INSERT INTO users (username,email,password,phone,role,registration_date,status)
            VALUES ('$username','$email','$password','$phone','$role','$reg_date','$status')
        ");
        if($insertUser){
            $user_id = mysqli_insert_id($con);

            // Save user location
            mysqli_query($con, "
                INSERT INTO user_location(user_id, province_id, district_id, city_id, created_at)
                VALUES ('$user_id','$province_id','$district_id','$city_id', NOW())
            ");

            // If agent, save agent table & optional certificate
            if($role === 'agent'){
                $cert_file = null;
                if(isset($_FILES['cert_file']) && $_FILES['cert_file']['error'] == 0){
                    $target_dir = "uploads/";
                    if(!is_dir($target_dir)) mkdir($target_dir,0777,true);
                    $cert_file = time().'_'.basename($_FILES['cert_file']['name']);
                    move_uploaded_file($_FILES['cert_file']['tmp_name'], $target_dir.$cert_file);
                }

                mysqli_query($con, "
                    INSERT INTO agent(user_id,status,approved_by_admin,approved_date,disabled_remarks)
                    VALUES ('$user_id','inactive',NULL,NULL,'')
                ");

                $agent_id = mysqli_insert_id($con);

                // Save agent location
                mysqli_query($con, "
                    INSERT INTO agent_location(agent_id, city_id)
                    VALUES ('$agent_id','$city_id')
                ");

                $msg = "Agent registration submitted! Waiting for admin approval.";
            } else {
                $msg = "Registration successful! <a href='login.php'>Login here</a>";
            }
        } else {
            $msg = "Error inserting user data!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RSA Registration</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0;padding:0;}
.container{width:90%;max-width:650px;margin:50px auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.1);}
h2{text-align:center;color:#007bff;margin-bottom:20px;}
form{display:flex;flex-direction:column;}
label{margin-bottom:5px;font-weight:bold;}
input, select{padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:5px;font-size:16px;}
input[type="submit"]{padding:12px;background:#007bff;color:#fff;border:none;border-radius:5px;cursor:pointer;transition:background 0.3s;}
input[type="submit"]:hover{background:#0056b3;}
.msg{margin-bottom:15px;font-weight:bold;color:red;text-align:center;}
.success{color:green;}
.agent-fields{display:none;padding-top:10px;border-top:1px solid #ccc;margin-top:10px;}
</style>
</head>
<body>
<div class="container">
<h2>Register</h2>

<?php if($msg != ''): ?>
<p class="msg <?php echo (strpos($msg,'successful')!==false || strpos($msg,'submitted')!==false)?'success':'';?>"><?php echo $msg; ?></p>
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

<label>Register As</label>
<select name="role" id="roleSelect" required>
<option value="">Select Role</option>
<option value="user">User</option>
<option value="agent">Agent</option>
</select>

<label>Province</label>
<select name="province" id="province" required>
<option value="">Select Province</option>
<?php
mysqli_data_seek($province_q,0);
while($p = mysqli_fetch_assoc($province_q)){
    echo "<option value='{$p['province_id']}'>{$p['province_name']}</option>";
}
?>
</select>

<label>District</label>
<select name="district" id="district" required>
<option value="">Select District</option>
</select>

<label>City</label>
<select name="city" id="city" required>
<option value="">Select City</option>
</select>

<div class="agent-fields" id="agentFields">
<label>Upload Certification (PDF/JPG/PNG)</label>
<input type="file" name="cert_file" accept=".pdf,.jpg,.png">
</div>

<input type="submit" name="register" value="Register">
</form>
</div>

<script>
// Show agent fields if role is agent
$('#roleSelect').change(function(){
    if($(this).val() === 'agent') $('#agentFields').slideDown();
    else $('#agentFields').slideUp();
});

// Filter districts based on province
$('#province').change(function(){
    var province_id = $(this).val();
    $('#district').html('<option>Loading...</option>');
    $('#city').html('<option value="">Select City</option>');
    if(province_id != ''){
        $.post('', {ajax_action:'get_districts', province_id:province_id}, function(data){
            $('#district').html(data);
        });
    }
});

// Filter cities based on district
$('#district').change(function(){
    var district_id = $(this).val();
    $('#city').html('<option>Loading...</option>');
    if(district_id != ''){
        $.post('', {ajax_action:'get_cities', district_id:district_id}, function(data){
            $('#city').html(data);
        });
    }
});
</script>
</body>
</html>

<?php
session_start();
include(__DIR__ . '/includes/dbconnection.php');

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

/* ================= REGISTRATION ================= */
if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $phone    = mysqli_real_escape_string($con, $_POST['phone']);
    $password = md5($_POST['password']); // keep as requested
    $role     = $_POST['role'];
    $status   = ($role === 'agent') ? 'inactive' : 'active';
    $reg_date = date('Y-m-d');

    // Duplicate check
    $check = mysqli_query($con, "SELECT user_id FROM users WHERE email='$email' OR phone='$phone'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "Email or phone already registered!";
    } else {

        $insertUser = mysqli_query($con, "
            INSERT INTO users(username,email,password,phone,role,registration_date,status)
            VALUES('$username','$email','$password','$phone','$role','$reg_date','$status')
        ");

        if ($insertUser) {

            $user_id = mysqli_insert_id($con);

            /* ===== AGENT ONLY DATA ===== */
            if ($role === 'agent') {

                $province_id = intval($_POST['province']);
                $district_id = intval($_POST['district']);
                $city_id     = intval($_POST['city']);

                // user location
                mysqli_query($con, "
                    INSERT INTO user_location(user_id,province_id,district_id,city_id,created_at)
                    VALUES('$user_id','$province_id','$district_id','$city_id',NOW())
                ");

                // certificate upload
                $cert_file = "";
                if (!empty($_FILES['cert_file']['name'])) {
                    $dir = "uploads/";
                    if (!is_dir($dir)) mkdir($dir, 0777, true);
                    $cert_file = time() . "_" . basename($_FILES['cert_file']['name']);
                    move_uploaded_file($_FILES['cert_file']['tmp_name'], $dir . $cert_file);
                }

                // agent table
                mysqli_query($con, "
                    INSERT INTO agent(user_id,status,approved_by_admin,approved_date,disabled_remarks)
                    VALUES('$user_id','inactive',NULL,NULL,'')
                ");

                $agent_id = mysqli_insert_id($con);

                // agent location
                mysqli_query($con, "
                    INSERT INTO agent_location(agent_id,city_id)
                    VALUES('$agent_id','$city_id')
                ");

                $msg = "Agent registration submitted! Waiting for admin approval.";

            } else {
                $msg = "Registration successful! <a href='login.php'>Login here</a>";
            }

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
<title>RSA Registration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
/* ================= ROOT THEME ================= */
:root{
    --primary: #0A924E;
    --secondary: #0b2e59;
    --dark: #071a2d;
    --white: #ffffff;
    --light-gray: #f4f7fb;
    --gray-text: #64748b;
}

/* ================= RESET ================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

/* ================= BODY ================= */
body{
    font-family: "Segoe UI", system-ui, sans-serif;
    background: linear-gradient(135deg, #0f172a, #020617);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--dark);
}

/* ================= CONTAINER ================= */
.container{
    width:100%;
    max-width:650px;
    background:var(--white);
    padding:32px 36px;
    border-radius:16px;
    box-shadow:0 15px 35px rgba(7,26,45,0.15);
    animation: fadeUp 0.4s ease;
}

/* ================= HEADING ================= */
h2{
    text-align:center;
    font-size:28px;
    color:var(--secondary);
    margin-bottom:25px;
}

/* ================= MESSAGE ================= */
.msg{
    padding:12px 15px;
    border-radius:8px;
    font-weight:600;
    text-align:center;
    margin-bottom:20px;
    background:#fdecec;
    color:#b42318;
}

.msg.success{
    background:#e7f7ef;
    color:var(--primary);
}

/* ================= FORM ================= */
form{
    display:flex;
    flex-direction:column;
    gap:14px;
}

label{
    font-size:14px;
    font-weight:600;
    color:var(--secondary);
}

/* ================= INPUTS ================= */
input,
select{
    width:100%;
    padding:12px 14px;
    border-radius:10px;
    border:1.8px solid #d0d9e6;
    background:var(--white);
    font-size:15px;
    transition:all .25s ease;
}

input::placeholder{
    color:var(--gray-text);
}

input:focus,
select:focus{
    outline:none;
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(10,146,78,.15);
}

/* ================= SELECT ARROW ================= */
select{
    cursor:pointer;
}

/* ================= FILE INPUT ================= */
input[type="file"]{
    padding:10px;
    background:var(--light-gray);
    border-style:dashed;
}

/* ================= SUBMIT BUTTON ================= */
input[type="submit"]{
    margin-top:10px;
    background:linear-gradient(135deg, var(--secondary));
    color:var(--white);
    font-size:16px;
    font-weight:700;
    border:none;
    padding:14px;
    border-radius:12px;
    cursor:pointer;
    transition:transform .2s ease, box-shadow .2s ease;
}

input[type="submit"]:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(10,146,78,.35);
}

input[type="submit"]:active{
    transform:translateY(0);
}

/* ================= AGENT SECTION ================= */
.agent-section{
    display:none;
    margin-top:20px;
    padding:18px;
    background:var(--light-gray);
    border-radius:14px;
    border-left:5px solid var(--primary);
    animation: slideDown .35s ease;
}

.agent-section label{
    margin-top:10px;
}

/* ================= ANIMATIONS ================= */
@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(15px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes slideDown{
    from{
        opacity:0;
        transform:translateY(-8px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* ================= RESPONSIVE ================= */
@media(max-width:576px){
    .container{
        padding:25px 20px;
        border-radius:14px;
    }
    h2{
        font-size:24px;
    }
}

</style>
</head>

<body>

<div class="container">
<h2>Register</h2>

<?php if($msg): ?>
<p class="msg <?php echo (strpos($msg,'successful')!==false || strpos($msg,'submitted')!==false)?'success':'';?>">
    <?php echo $msg; ?>
</p>
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

<!-- AGENT ONLY -->
<div class="agent-section" id="agentSection">

    <label>Province</label>
    <select name="province" id="province">
        <option value="">Select Province</option>
        <?php while($p=mysqli_fetch_assoc($province_q)): ?>
            <option value="<?= $p['province_id'] ?>"><?= $p['province_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>District</label>
    <select name="district" id="district">
        <option value="">Select District</option>
    </select>

    <label>City</label>
    <select name="city" id="city">
        <option value="">Select City</option>
    </select>

    <label>Upload Certification</label>
    <input type="file" name="cert_file" accept=".pdf,.jpg,.png">

</div>

<input type="submit" name="register" value="Register">

</form>
</div>

<script>
$('#roleSelect').change(function(){
    if($(this).val()==='agent'){
        $('#agentSection').slideDown();
        $('#province,#district,#city').attr('required',true);
    }else{
        $('#agentSection').slideUp();
        $('#province,#district,#city').removeAttr('required');
    }
});

$('#province').change(function(){
    let pid=$(this).val();
    $('#district').html('<option>Loading...</option>');
    $('#city').html('<option value="">Select City</option>');
    if(pid){
        $.post('',{ajax_action:'get_districts',province_id:pid},function(res){
            $('#district').html(res);
        });
    }
});

$('#district').change(function(){
    let did=$(this).val();
    $('#city').html('<option>Loading...</option>');
    if(did){
        $.post('',{ajax_action:'get_cities',district_id:did},function(res){
            $('#city').html(res);
        });
    }
});
</script>

</body>
</html>

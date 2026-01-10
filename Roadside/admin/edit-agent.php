<?php
session_start();
include('../includes/dbconnection.php');
if(empty($_SESSION['admin_id'])) header('location:../index.php');

$id = $_GET['id'];
$agent = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM users WHERE user_id='$id' AND role='agent'"));

if(isset($_POST['submit'])){
    $username = mysqli_real_escape_string($con,$_POST['username']);
    $email = mysqli_real_escape_string($con,$_POST['email']);
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $pass_sql = $password ? ", password='".md5($password)."'" : "";
    mysqli_query($con,"UPDATE users SET username='$username', email='$email', phone='$phone' $pass_sql WHERE user_id='$id'");
    header('location:approved-agents.php');
}
?>

<form method="post">
<input type="text" name="username" value="<?php echo $agent['username'];?>" required>
<input type="email" name="email" value="<?php echo $agent['email'];?>" required>
<input type="text" name="phone" value="<?php echo $agent['phone'];?>">
<input type="password" name="password" placeholder="Leave blank to keep current password">
<input type="submit" name="submit" value="Update Agent">
</form>

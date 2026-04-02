<?php
session_start();
include 'components/connect.php';

if(isset($_POST['login'])){

$email = $_POST['email'];
$password = $_POST['pass'];

$sql = $conn->prepare("SELECT * FROM students WHERE email = ?");
$sql->execute([$email]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

if($row){

if(password_verify($password,$row['password'])){

$_SESSION['student_id'] = $row['id'];
$_SESSION['student_name'] = $row['firstname'];
$_SESSION['success'] = "Login Successful!";

header("Location: home.php");
exit;

}else{
$error = "Incorrect password";
}

}else{
$error = "Email not found";
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Login</title>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<link rel="stylesheet" href="css/style.css">

<style>

body{
background:url("img/backgroundd.jpg") no-repeat center center;
background-size:cover;
}

.form-container{
display:flex;
justify-content:center;
align-items:center;
min-height:80vh;
}

.form-container form{
background:#b6a3a3fa;
padding:35px 30px;
width:100%;
max-width:380px;
border-radius:18px;
box-shadow:0 15px 35px rgba(0,0,0,0.15);
text-align:center;
}

.form-container form h3{
font-size:26px;
margin-bottom:25px;
color:#333;
}

.boxx{
width:100%;
padding:14px 15px;
margin:12px 0;
border-radius:30px;
background:#fffffff7;
border:1px solid #ccc;
outline:none;
font-size:15px;
}

.btn{
font-weight:600;
color: #fff;
background: linear-gradient(135deg, #667eea, #764ba2);
cursor:pointer;
padding:12px;
border-radius:30px;
border:none;
width:100%;
}

.text-danger{
color:red;
font-size:15px;
}

</style>

</head>

<body>

<?php include 'components/header.php'; ?>

<section class="form-container">

<form method="POST" id="loginform">

<h3>Student Login</h3>

<?php if(isset($error)) { ?>
<script>
Swal.fire({
icon:'error',
title:'Login Failed',
text:'<?php echo $error; ?>',
confirmButtonColor:'#764ba2'
});
</script>
<?php } ?>

<input type="text" id="email" name="email" placeholder="Enter Email" class="boxx">
<small id="email_error"></small>

<input type="password" id="password" name="pass" placeholder="Enter Password" class="boxx">
<small id="password_error"></small>

<input type="submit" name="login" value="Login Now" class="btn">

</form>

</section>

<?php include 'components/footer.php'; ?>

<script>

$(document).ready(function(){

$('#loginform').submit(function(e){

var email = $('#email').val().trim();
var password = $('#password').val().trim();

var email_regex = /^[\w-\.]+@([\w-]+\.)+[\w]{2,4}$/;

var validate = true;

// EMAIL VALIDATION
if(email==""){
$('#email_error').text("Email is required").addClass("text-danger");
validate=false;
}
else if(!email_regex.test(email)){
$('#email_error').text("Enter valid email").addClass("text-danger");
validate=false;
}
else{
$('#email_error').text("");
}

// PASSWORD VALIDATION
if(password==""){
$('#password_error').text("Password is required").addClass("text-danger");
validate=false;
}
else{
$('#password_error').text("");
}

// STOP FORM IF INVALID
if(!validate){
e.preventDefault();
}

});

});

</script>

</body>
</html>
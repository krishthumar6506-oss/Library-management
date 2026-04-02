<?php
session_start();
include '../components/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

if (!isset($_COOKIE['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['add'])) {

    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $lastname  = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $email     = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mobile    = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
    $gender    = $_POST['gender'];

    $plainPassword = $_POST['password'];
    $password = password_hash($plainPassword, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $_SESSION['error'] = 'Email already exists';
        header('Location: student.php');
        exit;
    }

    $insert = $conn->prepare(
        "INSERT INTO students 
        (firstname, lastname, email, password, mobile, gender, created_on)
        VALUES (?, ?, ?, ?, ?, ?, NOW())"
    );

    if ($insert->execute([$firstname, $lastname, $email, $password, $mobile, $gender])) {

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'krishthumar6506@gmail.com';
            $mail->Password   = 'gdwjmxsytojtcpwx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('krishthumar6506@gmail.com', 'Student Management');
            $mail->addAddress($email, "$firstname $lastname");

            $mail->isHTML(true);
            $mail->Subject = 'Registration Successful';

$mail->Body = "
<html>
<head>
<style>
body{
font-family: Arial, sans-serif;
background:#f4f6f9;
padding:20px;
}

.email-container{
max-width:600px;
margin:auto;
background:#ffffff;
border-radius:8px;
overflow:hidden;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.header{
background:#007bff;
color:white;
text-align:center;
padding:20px;
font-size:22px;
font-weight:bold;
}

.content{
padding:25px;
color:#333;
line-height:1.6;
}

.details{
background:#f8f9fa;
padding:15px;
border-radius:5px;
margin:15px 0;
}

.details p{
margin:5px 0;
font-weight:bold;
}

.footer{
text-align:center;
padding:15px;
font-size:14px;
color:#777;
background:#f1f1f1;
}
</style>
</head>

<body>

<div class='email-container'>

<div class='header'>
Page Turner 
</div>

<div class='content'>

<h3>Hello $firstname $lastname,</h3>

<p>Your <b>Online Library account</b> has been created successfully.</p>

<div class='details'>
<p>Email : $email</p>
<p>Password : $plainPassword</p>
</div>

<p>Please keep your login details confidential.</p>

<p>Thank you,<br><b>Online Library Team</b></p>

</div>

<div class='footer'>
© ".date('Y')." Online Library | All Rights Reserved
</div>

</div>

</body>
</html>
";

            $mail->send();

            $_SESSION['success'] = 'Student added and email sent successfully';

        } catch (Exception $e) {

            $_SESSION['success'] = 'Student added (email not sent)';

        }

    } else {

        $_SESSION['error'] = 'Failed to add student';

    }

    header('Location: student.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Student</title>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<link rel="stylesheet" href="../components/admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<style>

body{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.container{
    width:100%;
    max-width:500px;
}

.box{
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,0.15);
}

.box-footer{
    margin-top:10px;
}

::placeholder{
   color:#999;
   font-size:14px;
}

.text-danger{
    color:red;
    font-size:12px;
    font-weight:700;
}

</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<?php
if(isset($_SESSION['success'])){
    echo "<script>swal('Success!', '".$_SESSION['success']."', 'success');</script>";
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    echo "<script>swal('Error!', '".$_SESSION['error']."', 'error');</script>";
    unset($_SESSION['error']);
}
?>

<div class="container">

<div class="box box-primary">

<div class="box-header with-border">
<h3 class="box-title">Add New Student</h3>
</div>

<form method="POST" id="studentForm">

<div class="form-group">
<label>Firstname</label>
<input type="text" id="firstName" name="firstname" class="form-control" placeholder="Enter your first name">
<small id="fname_error"></small>
</div>

<div class="form-group">
<label>Lastname</label>
<input type="text" id="lastName" name="lastname" class="form-control" placeholder="Enter your last name">
<small id="lname_error"></small>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address">
<small id="email_error"></small>
</div>

<div class="form-group">
<label>Mobile</label>
<input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter 10 digit mobile number">
<small id="mobile_error"></small>
</div>

<div class="form-group">
<label>Gender</label><br>

<label><input type="radio" name="gender" value="Male"> Male</label>
<label><input type="radio" name="gender" value="Female"> Female</label>
<label><input type="radio" name="gender" value="Other"> Other</label>

<br>
<small id="gender_error"></small>

</div>

<div class="form-group">

<label>Password</label>
<input type="password" id="password" name="password" class="form-control" placeholder="Enter strong password">
<small id="password_error"></small>

</div>

<div class="box-footer">

<button type="submit" name="add" class="btn btn-primary btn-block">
<i class="fa fa-save"></i> Save
</button>

</div>

</form>

</div>

</div>

<script>
$(document).ready(function () {

$('#studentForm').submit(function (e) {

var validate = true;

// FIRST NAME
var fname = $('#firstName').val().trim();
var fname_regex = /^[a-zA-Z]{3,}$/;

if(fname == ""){
$('#fname_error').text("Firstname is required").addClass("text-danger");
validate = false;
}
else if(!fname_regex.test(fname)){
$('#fname_error').text("Only letters allowed (min 3 characters)").addClass("text-danger");
validate = false;
}
else{
$('#fname_error').text("");
}

// LAST NAME
var lname = $('#lastName').val().trim();
var lname_regex = /^[a-zA-Z]{3,}$/;

if(lname == ""){
$('#lname_error').text("Lastname is required").addClass("text-danger");
validate = false;
}
else if(!lname_regex.test(lname)){
$('#lname_error').text("Only letters allowed (min 3 characters)").addClass("text-danger");
validate = false;
}
else{
$('#lname_error').text("");
}

// EMAIL
var email = $('#email').val().trim();
var email_regex = /^[\w-\.]+@([\w-]+\.)+[\w]{2,4}$/;

if(email == ""){
$('#email_error').text("Email is required").addClass("text-danger");
validate = false;
}
else if(!email_regex.test(email)){
$('#email_error').text("Enter valid email").addClass("text-danger");
validate = false;
}
else{
$('#email_error').text("");
}

// MOBILE
var mobile = $('#mobile').val().trim();
var mobile_regex = /^[0-9]{10}$/;

if(mobile == ""){
$('#mobile_error').text("Mobile number required").addClass("text-danger");
validate = false;
}
else if(!mobile_regex.test(mobile)){
$('#mobile_error').text("Enter valid 10 digit number").addClass("text-danger");
validate = false;
}
else{
$('#mobile_error').text("");
}

// GENDER
if($('input[name="gender"]:checked').length == 0){
$('#gender_error').text("Please select gender").addClass("text-danger");
validate = false;
}
else{
$('#gender_error').text("");
}

// PASSWORD
var password = $('#password').val();
var password_regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/;

if(password == ""){
$('#password_error').text("Password is required").addClass("text-danger");
validate = false;
}
else if(!password_regex.test(password)){
$('#password_error').text("Password must contain uppercase, lowercase, number & symbol").addClass("text-danger");
validate = false;
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
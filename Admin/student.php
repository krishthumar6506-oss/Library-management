<?php
session_start();
include '../components/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

/* ===== AUTH CHECK ===== */
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

    // PASSWORD
    $plainPassword = $_POST['password']; // for email
    $password = password_hash($plainPassword, PASSWORD_DEFAULT);

    /* ===== CHECK EMAIL ===== */
    $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $_SESSION['error'] = 'Email already exists';
        header('Location: student.php');
        exit;
    }

    /* ===== INSERT STUDENT ===== */
    $insert = $conn->prepare(
        "INSERT INTO students 
        (firstname, lastname, email, password, mobile, gender, created_on)
        VALUES (?, ?, ?, ?, ?, ?, NOW())"
    );

    if ($insert->execute([$firstname, $lastname, $email, $password, $mobile, $gender])) {

        /* ===== SEND EMAIL ===== */
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'krishthumar6506@gmail.com';   // YOUR EMAIL
            $mail->Password   = 'gdwjmxsytojtcpwx';            // APP PASSWORD
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('krishthumar6506@gmail.com', 'Student Management');
            $mail->addAddress($email, "$firstname $lastname");

            $mail->isHTML(false);
            $mail->Subject = 'Registration Successful';


$mail->Body = "
Hello $firstname $lastname,

Your Online Library account has been created successfully.

Login Details:
Email    : $email
Password : $plainPassword

Please keep your login details confidential.

Thank you,
Online Library
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
</head>
<style>
/* Background */
body{
    min-height: 100vh;
    margin: 0;
    background: url("../img/back.jpg") no-repeat center center/cover;
    font-family: Arial, sans-serif;
}

/* Center everything */
.container{
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Glass form box */
.box{
    width: 100%;
    max-width: 520px;
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 18px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    padding: 30px 28px;
    border: 1px solid rgba(255,255,255,0.3);
}

/* Header */
.box-header{
    text-align: center;
    margin-bottom: 20px;
}

.box-title{
    font-size: 24px;
    font-weight: 600;
    color: #222;
}

/* Form groups */
.form-group{
    margin-bottom: 14px;
}

.form-group label{
    font-size: 14px;
    font-weight: 600;
    color: #333;
}

/* Inputs */
.form-control{
    width: 100%;
    padding: 13px 16px;
    border-radius: 30px;
    border: 1px solid #ccc;
    font-size: 14px;
    outline: none;
    transition: 0.3s;
}

.form-control:focus{
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.35);
}

/* Radio buttons */
.form-group input[type="radio"]{
    margin-right: 6px;
}

/* Button */
.btn{
    width: 100%;
    padding: 14px;
    border-radius: 30px;
    border: none;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.3);
}

/* Footer spacing */
.box-footer{
    margin-top: 10px;
}
</style>

<body>
<?php include '../components/admin_header.php'; ?>
<?php
if(isset($_SESSION['success'])){
    $success_msg = $_SESSION['success'];
    echo "<script>
        swal('Success!', '$success_msg', 'success');
    </script>";
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    $warning_msg = $_SESSION['error'];
    echo "<script>
        swal('Error!', '$warning_msg', 'error');
    </script>";
    unset($_SESSION['error']);
}
?>



<div class="container">
<div class="container">
<div class="box box-primary">
<div class="box-header with-border">
<h3 class="box-title">Add New Student</h3>
</div>
<form method="POST" id="studentForm">

<div class="form-group">
<label>Firstname</label>
<input type="text" id="firstName" name="firstname" class="form-control">
<small id="fname_error"></small>
</div>

<div class="form-group">
<label>Lastname</label>
<input type="text" id="lastName" name="lastname" class="form-control">
<small id="lname_error"></small>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" id="email" name="email" class="form-control">
<small id="email_error"></small>
</div>

<div class="form-group">
<label>Mobile</label>
<input type="text" id="mobile" name="mobile" class="form-control">
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
<input type="password" id="password" name="password" class="form-control">
<small id="password_error"></small>
</div>

<div class="box-footer">
<button type="submit" name="add" class="btn btn-primary btn-block">
<i class="fa fa-save"></i> Save
</div>
</form>
</div>
</div>
</div>
</body>
<script>
$(document).ready(function () {

    $('#studentForm').submit(function (e) {

        // ================= FIRST NAME =================
        var fname = $('#firstName').val().trim();
        var fname_regex = /^[a-zA-Z]+$/;

        if (fname == "") {
            $('#fname_error').text("Firstname is required").addClass('text-danger');
            $('#firstName').addClass("is-invalid");
            var validate_fname = false;
        }
        else if (fname.length < 3) {
            $('#fname_error').text("Minimum length is 3 characters").addClass('text-danger');
            $('#firstName').addClass("is-invalid");
            validate_fname = false;
        }
        else if (!fname_regex.test(fname)) {
            $('#fname_error').text("Name must contain only letters").addClass('text-danger');
            $('#firstName').addClass("is-invalid");
            validate_fname = false;
        }
        else {
            $('#fname_error').text("");
            $('#firstName').removeClass("is-invalid").addClass("is-valid");
            validate_fname = true;
        }

        // ================= LAST NAME =================
        var lname = $('#lastName').val().trim();
        var lname_regex = /^[a-zA-Z]+$/;

        if (lname == "") {
            $('#lname_error').text("Lastname is required").addClass('text-danger');
            $('#lastName').addClass("is-invalid");
            var validate_lname = false;
        }
        else if (lname.length < 3) {
            $('#lname_error').text("Minimum length is 3 characters").addClass('text-danger');
            $('#lastName').addClass("is-invalid");
            validate_lname = false;
        }
        else if (!lname_regex.test(lname)) {
            $('#lname_error').text("Name must contain only letters").addClass('text-danger');
            $('#lastName').addClass("is-invalid");
            validate_lname = false;
        }
        else {
            $('#lname_error').text("");
            $('#lastName').removeClass("is-invalid").addClass("is-valid");
            validate_lname = true;
        }

        // ================= EMAIL =================
        var email = $('#email').val().trim();
        var email_regex = /^[\w-\.]+@([\w-]+\.)+[\w]{2,4}$/;

        if (email == "") {
            $('#email_error').text("Email is required").addClass('text-danger');
            $('#email').addClass("is-invalid");
            var validate_email = false;
        }
        else if (!email_regex.test(email)) {
            $('#email_error').text("Enter valid email address").addClass('text-danger');
            $('#email').addClass("is-invalid");
            validate_email = false;
        }
        else {
            $('#email_error').text("");
            $('#email').removeClass("is-invalid").addClass("is-valid");
            validate_email = true;
        }

        // ================= MOBILE =================
        var mobile = $('#mobile').val().trim();
        var mobile_regex = /^[0-9]{10}$/;

        if (mobile == "") {
            $('#mobile_error').text("Mobile number is required").addClass('text-danger');
            $('#mobile').addClass("is-invalid");
            var validate_mobile = false;
        }
        else if (!mobile_regex.test(mobile)) {
            $('#mobile_error').text("Enter valid 10 digit number").addClass('text-danger');
            $('#mobile').addClass("is-invalid");
            validate_mobile = false;
        }
        else {
            $('#mobile_error').text("");
            $('#mobile').removeClass("is-invalid").addClass("is-valid");
            validate_mobile = true;
        }

        // ================= GENDER =================
        if ($('input[name="gender"]:checked').length == 0) {
            $('#gender_error').text("Please select gender").addClass('text-danger');
            var validate_gender = false;
        } else {
            $('#gender_error').text("");
            validate_gender = true;
        }

        // ================= PASSWORD =================
        var password = $('#password').val();
        var password_regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/;

        if (password == "") {
            $('#password_error').text("Password is required").addClass('text-danger');
            $('#password').addClass("is-invalid");
            var validate_password = false;
        }
        else if (!password_regex.test(password)) {
            $('#password_error').text("Password must contain 8 characters, uppercase, lowercase, number & special character").addClass('text-danger');
            $('#password').addClass("is-invalid");
            validate_password = false;
        }
        else {
            $('#password_error').text("");
            $('#password').removeClass("is-invalid").addClass("is-valid");
            validate_password = true;
        }

        // ================= FINAL CHECK =================
        if (
            validate_fname == false ||
            validate_lname == false ||
            validate_email == false ||
            validate_mobile == false ||
            validate_gender == false ||
            validate_password == false
        ) {
            e.preventDefault();
        }

    });

});
</script>

</html>

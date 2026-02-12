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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<link rel="stylesheet" href="../components/admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<style>
/* Background */
body{
    min-height: 100vh;
    margin: 0;
    background: url("../img/backgroud.jpg") no-repeat center center/cover;
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

<form method="POST">
<div class="box-body">

<div class="form-group">
<label>Firstname</label>
<input type="text" name="firstname" class="form-control" required>
</div>

<div class="form-group">
<label>Lastname</label>
<input type="text" name="lastname" class="form-control" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="form-group">
<label>Mobile</label>
<input type="text" name="mobile" class="form-control" required>
</div>

<div class="form-group">
<label>Gender</label><br>
<label><input type="radio" name="gender" value="Male" required> Male</label>
<label><input type="radio" name="gender" value="Female"> Female</label>
<label><input type="radio" name="gender" value="Other"> Other</label>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

</div>

<div class="box-footer">
<button type="submit" name="add" class="btn btn-primary btn-block">
<i class="fa fa-save"></i> Save
</button>
</div>
</form>
</div>
</div>

</body>

</html>

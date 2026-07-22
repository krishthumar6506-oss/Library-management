<?php
session_start();
include 'components/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if(isset($_POST['send_otp'])){
    $email = $_POST['email'];
    
    // Check if email exists in students table
    $sql = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $sql->execute([$email]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    
    if($row){
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        
        // Delete any existing OTP for this email
        $delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $delete->execute([$email]);
        
        // Insert new OTP
        $insert = $conn->prepare("INSERT INTO password_resets (email, otp, expires_at, created_at) VALUES (?, ?, ?, NOW())");
        $insert->execute([$email, $otp, $expires_at]);
        
        // Send OTP via email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pageturner899@gmail.com'; // Replace with your Gmail
            $mail->Password = 'cntxxylgvqnsgjiw'; // Replace with your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Recipients
            $mail->setFrom('pageturner899@gmail.com', 'Library Management System');
            $mail->addAddress($email);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP - Library Management System';
            $mail->Body = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
                    <h2 style="color: #764ba2; text-align: center;">Password Reset Request</h2>
                    <p>Hello,</p>
                    <p>You have requested to reset your password. Please use the following OTP to verify your identity:</p>
                    <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; text-align: center; border-radius: 10px; margin: 20px 0;">
                        <h1 style="margin: 0; font-size: 36px; letter-spacing: 5px;">' . $otp . '</h1>
                    </div>
                    <p>This OTP will expire in <strong>10 minutes</strong>.</p>
                    <p>If you did not request this password reset, please ignore this email.</p>
                    <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
                    <p style="color: #666; font-size: 12px; text-align: center;">Library Management System</p>
                </div>
            ';
            
            $mail->send();
            
            // Store email in session for verification
            $_SESSION['reset_email'] = $email;
            $_SESSION['success'] = "OTP has been sent to your email address!";
            
            header("Location: verify_otp.php");
            exit;
            
        } catch (Exception $e) {
            $error = "Failed to send OTP. Please try again later. Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Email not found in our records!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<style>
body{
    background:url("img/backgroundd.jpg") no-repeat center center;
    background-size:cover;
    font-family: Arial, sans-serif;
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
    margin-bottom:10px;
    color:#333;
}

.form-container form p{
    color:#555;
    margin-bottom:20px;
    font-size:14px;
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
    margin-top:10px;
}

.btn:hover{
    opacity:0.9;
}

.text-danger{
    color:red;
    font-size:13px;
    display:block;
    text-align:left;
    padding-left:15px;
}

.back-link{
    display:block;
    margin-top:20px;
    color:#764ba2;
    text-decoration:none;
    font-size:14px;
}

.back-link:hover{
    text-decoration:underline;
}

.icon-lock{
    font-size:50px;
    color:#764ba2;
    margin-bottom:15px;
}
</style>
</head>

<body>
<?php include 'components/header.php'; ?>

<section class="form-container">
<form method="POST" id="forgotForm">
    <i class="fas fa-lock icon-lock"></i>
    <h3>Forgot Password?</h3>
    <p>Enter your email address and we'll send you an OTP to reset your password.</p>
    
    <?php if(isset($error)) { ?>
    <script>
    Swal.fire({
        icon:'error',
        title:'Error',
        text:'<?php echo $error; ?>',
        confirmButtonColor:'#764ba2'
    });
    </script>
    <?php } ?>
    
    <input type="email" id="email" name="email" placeholder="Enter your email" class="boxx">
    <small id="email_error"></small>
    
    <input type="submit" name="send_otp" value="Send OTP" class="btn">
    
    <a href="login.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>
</form>
</section>

<?php include 'components/footer.php'; ?>

<script>
$(document).ready(function(){
    $('#forgotForm').submit(function(e){
        var email = $('#email').val().trim();
        var email_regex = /^[\w-\.]+@([\w-]+\.)+[\w]{2,4}$/;
        var validate = true;
        
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
        
        if(!validate){
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>

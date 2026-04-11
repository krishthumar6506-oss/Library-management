<?php
session_start();
include 'components/connect.php';

// Check if user verified OTP
if(!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified'])){
    header("Location: forgot_password.php");
    exit;
}

$email = $_SESSION['reset_email'];

if(isset($_POST['reset_password'])){
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($password === $confirm_password){
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password in database
        $update = $conn->prepare("UPDATE students SET password = ? WHERE email = ?");
        $update->execute([$hashed_password, $email]);
        
        // Delete the used OTP
        $delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $delete->execute([$email]);
        
        // Clear session variables
        unset($_SESSION['reset_email']);
        unset($_SESSION['otp_verified']);
        
        $_SESSION['success'] = "Password reset successfully! Please login with your new password.";
        header("Location: login.php");
        exit;
    } else {
        $error = "Passwords do not match!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
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

.password-strength{
    text-align:left;
    padding-left:15px;
    font-size:12px;
    margin-top:5px;
}

.strength-weak{
    color:red;
}

.strength-medium{
    color:orange;
}

.strength-strong{
    color:green;
}

.icon-key{
    font-size:50px;
    color:#764ba2;
    margin-bottom:15px;
}
</style>
</head>

<body>
<?php include 'components/header.php'; ?>

<section class="form-container">
<form method="POST" id="resetForm">
    <i class="fas fa-key icon-key"></i>
    <h3>Reset Password</h3>
    <p>Create a new password for your account</p>
    
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
    
    <input type="password" id="password" name="password" placeholder="Enter new password" class="boxx">
    <small id="password_error"></small>
    <div id="password_strength" class="password-strength"></div>
    
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" class="boxx">
    <small id="confirm_password_error"></small>
    
    <input type="submit" name="reset_password" value="Reset Password" class="btn">
</form>
</section>

<?php include 'components/footer.php'; ?>

<script>
$(document).ready(function(){
    // Password strength checker
    $('#password').on('input', function(){
        var password = $(this).val();
        var strength = 0;
        
        if(password.length >= 8) strength++;
        if(password.match(/[a-z]+/)) strength++;
        if(password.match(/[A-Z]+/)) strength++;
        if(password.match(/[0-9]+/)) strength++;
        if(password.match(/[$@#&!]+/)) strength++;
        
        var strengthText = '';
        var strengthClass = '';
        
        if(password.length == 0){
            $('#password_strength').text('');
            return;
        }
        
        if(strength < 2){
            strengthText = 'Weak password';
            strengthClass = 'strength-weak';
        } else if(strength < 4){
            strengthText = 'Medium strength';
            strengthClass = 'strength-medium';
        } else {
            strengthText = 'Strong password';
            strengthClass = 'strength-strong';
        }
        
        $('#password_strength').text(strengthText).removeClass().addClass('password-strength ' + strengthClass);
    });
    
    $('#resetForm').submit(function(e){
        var password = $('#password').val().trim();
        var confirm_password = $('#confirm_password').val().trim();
        var validate = true;
        
        // Password validation
        if(password == ""){
            $('#password_error').text("Password is required").addClass("text-danger");
            validate = false;
        }
        else if(password.length < 8){
            $('#password_error').text("Password must be at least 8 characters").addClass("text-danger");
            validate = false;
        }
        else{
            $('#password_error').text("");
        }
        
        // Confirm password validation
        if(confirm_password == ""){
            $('#confirm_password_error').text("Please confirm your password").addClass("text-danger");
            validate = false;
        }
        else if(password !== confirm_password){
            $('#confirm_password_error').text("Passwords do not match").addClass("text-danger");
            validate = false;
        }
        else{
            $('#confirm_password_error').text("");
        }
        
        if(!validate){
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>

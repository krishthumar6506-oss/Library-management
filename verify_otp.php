<?php
session_start();
include 'components/connect.php';

// Check if user came from forgot password page
if(!isset($_SESSION['reset_email'])){
    header("Location: forgot_password.php");
    exit;
}

$email = $_SESSION['reset_email'];

if(isset($_POST['verify_otp'])){
    $otp = trim($_POST['otp']);

    // ✅ Get latest OTP
    $sql = $conn->prepare("
        SELECT otp 
        FROM password_resets 
        WHERE email = ? 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $sql->execute([$email]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if($row){

        // ✅ ONLY check OTP (NO TIME LIMIT)
        if($otp === $row['otp']){

            $_SESSION['otp_verified'] = true;

            // ✅ Delete OTP after success (important)
            $delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
            $delete->execute([$email]);

            $_SESSION['success'] = "OTP verified successfully!";
            header("Location: reset_password.php");
            exit;

        } else {
            $error = "Invalid OTP!";
        }

    } else {
        $error = "No OTP found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify OTP</title>

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
    text-align:center;
    letter-spacing:5px;
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

.icon-envelope{
    font-size:50px;
    color:#764ba2;
    margin-bottom:15px;
}
</style>
</head>

<body>

<?php include 'components/header.php'; ?>

<section class="form-container">
<form method="POST" id="otpForm">
    
    <i class="fas fa-envelope-open-text icon-envelope"></i>
    <h3>Verify OTP</h3>
    <p>Enter the 6-digit OTP sent to<br><strong><?php echo htmlspecialchars($email); ?></strong></p>
    
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
    
    <input type="text" id="otp" name="otp" placeholder="Enter 6-digit OTP" class="boxx" maxlength="6">
    <small id="otp_error"></small>
    
    <input type="submit" name="verify_otp" value="Verify OTP" class="btn">
    
    <a href="forgot_password.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    
</form>
</section>

<?php include 'components/footer.php'; ?>

<script>
$(document).ready(function(){
    $('#otpForm').submit(function(e){
        var otp = $('#otp').val().trim();
        var validate = true;
        
        if(otp == ""){
            $('#otp_error').text("OTP is required").addClass("text-danger");
            validate = false;
        }
        else if(!/^\d{6}$/.test(otp)){
            $('#otp_error').text("Enter valid 6-digit OTP").addClass("text-danger");
            validate = false;
        }
        else{
            $('#otp_error').text("");
        }
        
        if(!validate){
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
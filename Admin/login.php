<?php
include '../components/connect.php';

$success_msg = [];
$warning_msg = [];

if(isset($_POST['submit'])){

   $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
   $password = $_POST['password'];

   $select_admin = $conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
   $select_admin->execute([$username]);

   if($select_admin->rowCount() > 0){
      $row = $select_admin->fetch(PDO::FETCH_ASSOC);

      if(password_verify($password, $row['password'])){
         setcookie('admin_id', $row['id'], time() + 60*60, '/');
         $success_msg[] = 'Admin login successful!';
         header("refresh:1;url=dashboard.php");
      }else{
         $warning_msg[] = 'Incorrect username or password!';
      }

   }else{
      $warning_msg[] = 'Incorrect username or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<style>

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Manrope', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body{
    min-height: 100vh;
    background:
        radial-gradient(circle at top left, rgba(249,115,22,0.22), transparent 26%),
        radial-gradient(circle at top right, rgba(14,165,233,0.18), transparent 24%),
        linear-gradient(135deg, #0f172a 0%, #1e293b 48%, #334155 100%);
}

.form-container{
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
}

.form-container form{
    width: 100%;
    max-width: 430px;
    padding: 38px 34px;
    background: rgba(255,255,255,0.92);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 28px;
    box-shadow: 0 28px 50px rgba(15,23,42,0.34);
    text-align: left;
    backdrop-filter: blur(12px);
}

.form-container form h3{
    font-size: 32px;
    margin-bottom: 10px;
    font-weight: 800;
    color: #142033;
    letter-spacing: -0.03em;
}

.form-container form p{
    margin-bottom: 24px;
    color: #64748b;
    font-size: 15px;
    line-height: 1.6;
}

.form-container .box{
    width: 100%;
    padding: 15px 18px;
    margin: 12px 0;
    border-radius: 18px;
    border: 1px solid rgba(148,163,184,0.45);
    font-size: 15px;
    outline: none;
    color: #142033;
    background: rgba(255,255,255,0.95);
    transition: 0.25s ease;
}

.form-container .box:focus{
    border-color: rgba(249,115,22,0.7);
    box-shadow: 0 0 0 5px rgba(249,115,22,0.13);
}

.form-container .btn{
    width: 100%;
    padding: 15px;
    margin-top: 20px;
    border: none;
    border-radius: 18px;
    background: linear-gradient(135deg, #f97316, #fb923c);
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    transition: 0.25s ease;
    color: white;
    box-shadow: 0 18px 30px rgba(249,115,22,0.28);
}

.form-container .btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 20px 34px rgba(249,115,22,0.34);
}

.text-danger{
    color: #dc2626;
    font-size: 13px;
    display: block;
    text-align: left;
    margin-left: 10px;
}

.is-invalid{
    border: 2px solid red !important;
}

.is-valid{
    border: 2px solid green !important;
}

</style>
</head>

<body>

<section class="form-container">

<form action="" method="POST" id="adminLogin" novalidate>

   <h3>Welcome Admin</h3>
   <p>Sign in to manage books, students, returns, and library requests from one place.</p>

   <input type="text" id="username" name="username" placeholder="Enter Username" class="box">
   <small id="user_error"></small>

   <input type="password" id="password" name="password" placeholder="Enter Password" class="box">
   <small id="pass_error"></small>

   <input type="submit" name="submit" value="Login Now" class="btn">
</form>

</section>
<script>
$(document).ready(function () {

    $('#adminLogin').submit(function (e) {

        var username = $('#username').val().trim();
        var password = $('#password').val().trim();

        var validate_user = true;
        var validate_pass = true;

        // USERNAME
        if (username == "") {
            $('#user_error').text("Username is required").addClass("text-danger");
            $('#username').removeClass("is-valid").addClass("is-invalid");
            validate_user = false;
        }
        else if (username.length < 3) {
            $('#user_error').text("Username must be at least 3 characters").addClass("text-danger");
            $('#username').removeClass("is-valid").addClass("is-invalid");
            validate_user = false;
        }
        else {
            $('#user_error').text("");
            $('#username').removeClass("is-invalid").addClass("is-valid");
        }

        // PASSWORD
        if (password == "") {
            $('#pass_error').text("Password is required").addClass("text-danger");
            $('#password').removeClass("is-valid").addClass("is-invalid");
            validate_pass = false;
        }
        else {
            $('#pass_error').text("");
            $('#password').removeClass("is-invalid").addClass("is-valid");
        }

        // STOP SUBMIT ONLY IF INVALID
        if(validate_user == false || validate_pass == false){
            e.preventDefault();
        }

    });

});
</script>
</body>

<?php
if(isset($success_msg)){
   foreach($success_msg as $msg){
      echo "<script>swal('Success!', '$msg', 'success');</script>";
   }
}

if(isset($warning_msg)){
   foreach($warning_msg as $msg){
      echo "<script>swal('Error!', '$msg', 'error');</script>";
   }
}
?>

</html>

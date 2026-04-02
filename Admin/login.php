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
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body{
    min-height: 100vh;
    background: url("../img/back.jpg") no-repeat center center/cover;
}

.form-container{
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.form-container form{
    width: 100%;
    max-width: 380px;
    padding: 35px 30px;
    background: #f2efeff5;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    text-align: center;
}

.form-container form h3{
    font-size: 26px;
    margin-bottom: 25px;
    font-weight: 600;
}

.form-container .box{
    width: 100%;
    padding: 14px 18px;
    margin: 12px 0;
    border-radius: 30px;
    border: 1px solid #ccc;
    font-size: 15px;
    outline: none;
    transition: 0.3s ease;
}

.form-container .btn{
    width: 100%;
    padding: 14px;
    margin-top: 20px;
    border: none;
    border-radius: 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s ease;
    color: white;
}

.form-container .btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}

.text-danger{
    color: red;
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
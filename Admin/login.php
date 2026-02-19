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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

</head>
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
    background: #f2efef7d;
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

.form-container .box:focus{
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
}

.form-container .btn{
    width: 100%;
    padding: 14px;
    margin-top: 20px;
    border: none;
    border-radius: 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #38f047;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s ease;
}

/* BUTTON HOVER */
.form-container .btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}


</style>
<body style="padding-left: 0;">

<section class="form-container" style="min-height: 100vh;">

<form action="" method="POST">
   <h3>Welcome Admin</h3>
   <input type="text" name="username" placeholder="Enter Username" class="box" required>
   <input type="password" name="password" placeholder="Enter Password" class="box" required>
   <input type="submit" value="Login Now" name="submit" class="btn">
</form>


</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


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
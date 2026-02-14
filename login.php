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

        if(password_verify($password, $row['password'])){

            $_SESSION['student_id'] = $row['id'];
            $_SESSION['student_name'] = $row['firstname'];
            $_SESSION['success'] = 'Admin login successful!';
            header('Location: home.php');
            exit;


        } else {
            $error = "Incorrect password";
        }

    } else {
        $error = "Email not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<style>
body {
    background: url("img/backgroundd.jpg") no-repeat center center;
    background-size: cover;
}

.form-container{
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
}

.form-container form{
    background: #b6a3a3b2;
    padding: 35px 30px;
    width: 100%;
    max-width: 380px;
    border-radius: 18px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    text-align: center;
}

.form-container form h3{
    font-size: 26px;
    margin-bottom: 25px;
    color: #333;
}

.form-container .box{
    width: 100%;
    padding: 14px 15px;
    margin: 12px 0;
    border-radius: 30px;
    background: #ffffffb3;
    border: 1px solid #ccc;
    outline: none;
    font-size: 15px;
    transition: 0.3s;
}

.form-container .btn{
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #667eea, #764ba2);
    cursor: pointer;
    transition: 0.3s ease;
}

.boxx{
    width: 100%;
    padding: 14px 15px;
    margin: 12px 0;
    border-radius: 30px;
    background: #ffffffb3;
    border: 1px solid #ccc;
    outline: none;
    font-size: 15px;
}

.btn{
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #667eea, #764ba2);
    cursor: pointer;
    padding: 12px;
    border-radius: 30px;
    border: none;
    width: 100%;
}
</style>
<body>

<?php include 'components/header.php'; ?>

<section class="form-container">
   <form action="" method="POST">
      <h3>Student Login</h3>

<?php if(isset($error)) { ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Failed',
    text: '<?php echo $error; ?>',
    confirmButtonColor: '#764ba2'
});
</script>
<?php } ?>


      <input type="email" name="email" required placeholder="Enter Email" class="boxx">
      <input type="password" name="pass" required placeholder="Enter Password" class="boxx">
      <input type="submit" name="login" value="Login Now" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
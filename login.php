
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
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
    backdrop-filter: blur(8px);
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
    border: 1px solid #ccc;
    outline: none;
    font-size: 15px;
    transition: 0.3s;
}

.form-container .box:focus{
    border-color: #5a67d8;
    box-shadow: 0 0 0 3px rgba(90,103,216,0.2);
}

.form-container .btn{
    width: 100%;
    margin-top: 20px;
    padding: 14px;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #667eea, #764ba2);
    cursor: pointer;
    transition: 0.3s ease;
}



</style>
<?php include 'components/hearder.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Welcome Back!</h3>
      <input type="email" name="email" required maxlength="50" placeholder="Enter your Email" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="Enter your Password" class="box"><br>
      
      <input type="submit" value="login now" name="submit" class="btn">
   </form>


   
</section>


<?php include 'components/footer.php'; ?>


</body>
</html>

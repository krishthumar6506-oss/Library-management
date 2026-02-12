<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="../components/admin_style.css">
<style>
   
body{
    min-height: 100vh;
    background: url("../img/back.jpg") no-repeat center center/cover;
}

.dashboard .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, 35rem);
    align-items: flex-start;
    justify-content: center;
    gap: 1.5rem;
}

.dashboard .box-container .box {
    text-align: center;
    border-radius: .5rem;
    border: var(--border);
    padding: 2rem;
    box-shadow: var(--box-shadow);
    background: #d8cfcf93;
   }

.dashboard .box-container h3 {
    font-size: 2.5rem;
    padding-bottom: .5rem;
}

.dashboard .box-container p {
    border: var(--border);
    border-radius: .5rem;
    padding: 1.5rem;
    font-size: 1.8rem;
    color: var (--light-color);
    background-color: var(--light-bg);
    margin: 1rem 0;
}
</style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

   <div class="box">
   <h3>Welcome!</h3>
   <p>Admin</p>
</div>


   <div class="box">
      <?php
         $select_listings = $conn->prepare("SELECT * FROM `students`");
         $select_listings->execute();
         $count_listings = $select_listings->rowCount();
      ?>
      <h3><?= $count_listings; ?></h3>
      <p>Students</p>
      <a href="listings.php" class="btn">View Students</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `borrow`");
         $select_users->execute();
         $count_users = $select_users->rowCount();
      ?>
      <h3><?= $count_users; ?></h3>
      <p>Total Borrow</p>
      <a href="users.php" class="btn">View Borrow</a>
   </div>



   <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `returns`");
         $select_messages->execute();
         $count_messages = $select_messages->rowCount();
      ?>
      <h3><?= $count_messages; ?></h3>
      <p>Total Returns</p>
      <a href="messages.php" class="btn">View Returns</a>
   </div>

   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

</body>
</html>
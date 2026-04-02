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
</head>
<style>
   /* Large desktops */
@media (min-width: 1200px){
    body{
        padding-left: 30rem;
    }
    .header{
        width: 300px;
        padding: 30px 20px;
    }
    .dashboard .box-container{
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
}

/* Tablets / Medium devices */
@media (min-width: 768px) and (max-width: 1199px){
    body{
        padding-left: 25rem;
    }
    .header{
        width: 250px;
        padding: 25px 15px;
    }
    .dashboard .box-container{
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .dashboard .box h3{
        font-size: 24px;
    }
    .dashboard .box p{
        font-size: 15px;
    }
}

/* Mobile / Small devices */
@media (max-width: 767px){
    body{
        padding-left: 0;
        background-size: cover;
    }
    .header{
        position: relative;
        width: 100%;
        padding: 15px;
        border-right: none;
    }
    .dashboard .box-container{
        grid-template-columns: 1fr;
        gap: 15px;
    }
    .dashboard .box h3{
        font-size: 22px;
    }
    .dashboard .box p{
        font-size: 14px;
    }
    .btn{
        font-size: 14px;
        padding: 8px 12px;
    }
}
</style>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

   <div class="box">
   <h3>Welcome.</h3>
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
      <a href="view_student.php" class="btn">View Students</a>
    </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `books`");
         $select_users->execute();
         $count_users = $select_users->rowCount();
      ?>
      <h3><?= $count_users; ?></h3>
      <p>Total Books</p>
      <a href="view_books.php" class="btn">View Books</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `borrow`");
         $select_users->execute();
         $count_users = $select_users->rowCount();
      ?>
      <h3><?= $count_users; ?></h3>
      <p>Total Borrow</p>
      <a href="borrowed.php" class="btn">View Borrow Books</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `returns`");
         $select_users->execute();
         $count_users = $select_users->rowCount();
      ?>
      <h3><?= $count_users; ?></h3>
      <p>Total Return Books</p>
      <a href="returned.php" class="btn">View Return Books</a>
   </div>


   </div>

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

</body>
</html>
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
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>
   <p class="admin-page-subtitle">A quick overview of students, books, borrows, returns, and request activity.</p>

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

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `books`");
         $select_users->execute();
         $count_users = $select_users->rowCount();
      ?>
      <h3><?= $count_users; ?></h3>
      <p>Books Status</p>
      <a href="book_status.php" class="btn">All Books Status</a>
   </div>

   <div class="box">
      <?php
         try{
            $conn->exec("
               CREATE TABLE IF NOT EXISTS book_requests (
                  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  student_id INT(11) NOT NULL,
                  book_id INT(11) NOT NULL,
                  request_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  status VARCHAR(20) NOT NULL DEFAULT 'Pending'
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
            ");
            $select_requests = $conn->prepare("SELECT * FROM `book_requests`");
            $select_requests->execute();
            $count_requests = $select_requests->rowCount();
         } catch(PDOException $e){
            $count_requests = 0;
         }
      ?>
      <h3><?= $count_requests; ?></h3>
      <p>Book Requests</p>
      <a href="book_requests.php" class="btn">View Requests</a>
   </div>


   </div>

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

</body>
</html>

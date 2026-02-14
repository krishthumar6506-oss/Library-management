<?php
session_start();
if(!isset($_SESSION['student_id'])){
   header("Location: login.php");
   exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
body {
    background: url("img/backgroundd.jpg") no-repeat center center;
    background-size: cover;
}

.services{
    padding: 60px 20px;
}

.services .heading{
    text-align: center;
    font-size: 32px;
    margin-bottom: 45px;
    color: #333;
    text-transform: capitalize;
}

.services .box-container{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1100px;
    margin: auto;
}

 h2 {
    background: #b6a3a3d3;

 }
 
.services .box{
    background: #b6a3a3d3;
    padding: 30px 25px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.services .box:hover{
    transform: translateY(-8px);
    box-shadow: 0 18px 35px rgba(0,0,0,0.15);
}

.services .box img{
    width: 90px;
    margin-bottom: 20px;
}

.services .box h3{
    font-size: 20px;
    margin-bottom: 10px;
    text-transform: capitalize;
    color: #000000;
}

.services .box p{
    font-size: 15px;
    line-height: 1.6;
}

.carousel-item img {
    height: 850px;        /* change this height as you want */
    object-fit: cover;    /* crop image nicely */
}


</style>
<body>
<?php include 'components/hearder.php'; ?>

<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="1400">
      <img src="img/1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="1400">
      <img src="img/2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="1400">
      <img src="img/3.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="1400">
      <img src="img/4.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="1400">
      <img src="img/5.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="1400">
      <img src="img/6.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="1400">
      <img src="img/7.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="1400">
      <img src="img/8.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<section class="services">

   <h2 class="heading">our services</h2>

   <div class="box-container">

      <div class="box">
         <img src="img/img1.png" alt="">
         <h3>Book Search</h3>
         <p>Easily search books by title, author, or category in the library.</p>
      </div>

      <div class="box">
         <img src="img/img2.png" alt="">
         <h3>Book Issue</h3>
         <p>Quickly issue books to students with proper tracking and records.</p>
      </div>

      <div class="box">
         <img src="img/img3.png" alt="">
         <h3>Book Return</h3>
         <p>Return borrowed books easily with updated return history.</p>
      </div>

      <div class="box">
         <img src="img/img4.png" alt="">
         <h3>Digital Library</h3>
         <p>Access e-books and digital resources anytime, anywhere.</p>
      </div>

      <div class="box">
         <img src="img/img5.png" alt="">
         <h3>User Management</h3>
         <p>Manage student and member accounts efficiently.</p>
      </div>

      <div class="box">
         <img src="img/img6.png" alt="">
         <h3>24/7 Support</h3>
         <p>Get assistance and library access support at any time.</p>
      </div>

   </div>

</section>


<?php include 'components/footer.php'; ?>

</body>
</html>
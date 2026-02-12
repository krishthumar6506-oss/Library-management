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

    /* ===== SERVICES SECTION ===== */
.services{
    padding: 60px 20px;
}

/* Heading */
.services .heading{
    text-align: center;
    font-size: 32px;
    margin-bottom: 45px;
    color: #333;
    text-transform: capitalize;
}

/* Container */
.services .box-container{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1100px;
    margin: auto;
}

/* Service box */
.services .box{
    background: #fff;
    padding: 30px 25px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

/* Hover effect */
.services .box:hover{
    transform: translateY(-8px);
    box-shadow: 0 18px 35px rgba(0,0,0,0.15);
}

/* Image */
.services .box img{
    width: 90px;
    margin-bottom: 20px;
}

/* Title */
.services .box h3{
    font-size: 20px;
    margin-bottom: 10px;
    text-transform: capitalize;
    color: #222;
}

/* Text */
.services .box p{
    font-size: 15px;
    line-height: 1.6;
    color: #666;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px){
    .services .box-container{
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px){
    .services .box-container{
        grid-template-columns: 1fr;
    }
}

</style>
<body>
<?php include 'components/hearder.php'; ?>

<section class="services">

   <h2 class="heading">our services</h2>

   <div class="box-container">

      <div class="box">
         <img src="images/icon-1.png" alt="">
         <h3>Book Search</h3>
         <p>Easily search books by title, author, or category in the library.</p>
      </div>

      <div class="box">
         <img src="images/icon-2.png" alt="">
         <h3>Book Issue</h3>
         <p>Quickly issue books to students with proper tracking and records.</p>
      </div>

      <div class="box">
         <img src="images/icon-3.png" alt="">
         <h3>Book Return</h3>
         <p>Return borrowed books easily with updated return history.</p>
      </div>

      <div class="box">
         <img src="images/icon-4.png" alt="">
         <h3>Digital Library</h3>
         <p>Access e-books and digital resources anytime, anywhere.</p>
      </div>

      <div class="box">
         <img src="images/icon-5.png" alt="">
         <h3>User Management</h3>
         <p>Manage student and member accounts efficiently.</p>
      </div>

      <div class="box">
         <img src="images/icon-6.png" alt="">
         <h3>24/7 Support</h3>
         <p>Get assistance and library access support at any time.</p>
      </div>

   </div>

</section>


<?php include 'components/footer.php'; ?>

</body>
</html>
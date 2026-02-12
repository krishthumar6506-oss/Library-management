<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<style>
body {
    background: url("img/backgroundd.jpg") no-repeat center center;
    background-size: cover;
}

.about .row{
   display: flex;
   flex-wrap: wrap;
   gap: 1.5rem;
   align-items: center;
}

.about .row .image{
   flex: 1 1 40rem;
}

.about .row .image img{
   width: 100%;
}

.about .row .content{
   flex: 1 1 40rem;
   text-align: center;
}

.about .row .content h3{
   font-size: 2.5rem;
   color: var(--black);
   margin-bottom: .5rem;
   text-transform: capitalize;
}

.about .row .content p{
   line-height: 2;
   padding: 1rem 0;
   font-size: 20px;
   color: var(--light-color);
}


/* ===== STEPS SECTION ===== */
.steps{
    padding: 60px 20px;
}

/* Heading */
.steps .heading{
    text-align: center;
    font-size: 32px;
    margin-bottom: 60px;
    color: #000000;
    backdrop-filter: blur(8px);
    padding: 3px 5px;
    border-radius: 16px;
}

/* Container */
.steps .box-container{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1100px;
    margin: auto;
}

/* Step box */
.services .box{
    background: #fff;
    padding: 30px 25px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

/* Hover effect */
.steps .box:hover{
    transform: translateY(-8px);
    box-shadow: 0 18px 35px rgba(0,0,0,0.15);
}

/* Image */
.steps .box img{
    width: 90px;
    margin-bottom: 20px;
}

/* Title */
.steps .box h3{
    font-size: 20px;
    margin-bottom: 10px;
    text-transform: capitalize;
    color: #222;
}

/* Text */
.steps .box p{
    font-size: 15px;
    line-height: 1.6;
    color: #666;
}

</style>
<body>
<?php include 'components/hearder.php'; ?>


<section class="about">

   <div class="row">
      <div class="image">
         <img src="img/About.png" alt="">
      </div>
      <div class="content">
         <p>A library is a treasure house of knowledge where books open the door to learning, imagination, and wisdom. 
            Libraries provide a peaceful environment that encourages reading and helps people explore new ideas through books on various subjects. 
            Together, libraries and books play a vital role in educating minds and preserving knowledge for future generations.</p>
      </div>
   </div>

</section>

<section class="steps">

   <h1 class="heading">3 Simple Steps</h1>

   <div class="box-container">

      <div class="box">
         <img src="img/step1.png" alt="">
         <h3>search books</h3>
         <p>Users can easily search books by title, author, or category in the library.</p>
      </div>

      <div class="box">
         <img src="img/step2.png" alt="">
         <h3>borrow books</h3>
         <p>Select available books and issue them quickly using the library system.</p>
      </div>

      <div class="box">
         <img src="img/step3.png" alt="">
         <h3>read & return</h3>
         <p>Enjoy reading your books and return them on time to keep records updated.</p>
      </div>

   </div>

</section>


<?php include 'components/footer.php'; ?>
</body>
</html>
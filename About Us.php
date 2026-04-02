<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<style>
    body {
    background: url("img/backgroundd.jpg") no-repeat center center;
    background-size: cover;
}

/* LARGE DEVICES (Desktop ≥992px) */
@media (min-width: 992px) {

    .about .row{
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: center;
    }

    .about .row .image,
    .about .row .content{
        flex: 1 1 40rem;
        text-align: center;
    }

    .about .row .image img{
        width: 100%;
    }

    .about .row .content p{
        line-height: 2;
        padding: 1rem 0;
        font-size: 22px;
        background: #f5e3e3f9;
    }

    .steps{
        padding: 60px 20px;
    }

    .steps .heading{
        text-align: center;
        font-size: 36px;
        margin-bottom: 60px;
        background: #b6a3a3f9;
        padding: 5px;
        border-radius: 16px;
    }

    .steps .box-container{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1100px;
        margin: auto;
    }

    .steps .box{
        padding: 30px 25px;
        border-radius: 16px;
        text-align: center;
        background: #b6a3a3f9;
        box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        transition: 0.3s;
    }

    .steps .box img{
        width: 100px;
        margin-bottom: 20px;
    }
}


/* MEDIUM DEVICES (Tablet 768px–991px) */
@media (min-width: 768px) and (max-width: 991px) {

    .about .row{
        display: flex;
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }

    .about .row .image img{
        width: 100%;
    }

    .about .row .content p{
        font-size: 18px;
        line-height: 1.8;
        background: #f5e3e3f9;
        padding: 1rem;
    }

    .steps{
        padding: 40px 15px;
    }

    .steps .heading{
        font-size: 30px;
        margin-bottom: 40px;
        background: #b6a3a3f9;
        padding: 5px;
        border-radius: 16px;
        text-align: center;
    }

    .steps .box-container{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .steps .box{
        padding: 25px;
        text-align: center;
        background: #b6a3a3f9;
        border-radius: 16px;
    }

    .steps .box img{
        width: 85px;
        margin-bottom: 15px;
    }
}


/* SMALL DEVICES (Mobile ≤767px) */
@media (max-width: 767px) {

    .about .row{
        display: flex;
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .about .row .image img{
        width: 100%;
    }

    .about .row .content p{
        font-size: 16px;
        line-height: 1.6;
        padding: 0.8rem;
        background: #f5e3e3f9;
    }

    .steps{
        padding: 30px 10px;
    }

    .steps .heading{
        font-size: 24px;
        margin-bottom: 30px;
        background: #b6a3a3f9;
        padding: 4px;
        border-radius: 12px;
        text-align: center;
    }

    .steps .box-container{
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .steps .box{
        padding: 20px;
        text-align: center;
        background: #b6a3a3f9;
        border-radius: 12px;
    }

    .steps .box img{
        width: 70px;
        margin-bottom: 10px;
    }
}
</style>
</head>

<body>

<?php include 'components/hearder.php'; ?>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="img/About.png" alt="">
      </div>
      <div class="content">
         <p>
            A library is a treasure house of knowledge where books open the door to learning,
            imagination, and wisdom. Libraries provide a peaceful environment that encourages
            reading and helps people explore new ideas.
         </p>
      </div>
   </div>
</section>

<section class="steps">
   <h1 class="heading">3 Simple Steps</h1>

   <div class="box-container">

      <div class="box">
         <img src="img/step1.png" alt="">
         <h3>Search Books</h3>
         <p>Users can search books by title, author, or category.</p>
      </div>

      <div class="box">
         <img src="img/step2.png" alt="">
         <h3>Borrow Books</h3>
         <p>Select available books and issue them quickly.</p>
      </div>

      <div class="box">
         <img src="img/step3.png" alt="">
         <h3>Read & Return</h3>
         <p>Read your books and return them on time.</p>
      </div>

   </div>
</section>

<?php include 'components/footer.php'; ?>

<script>
$(document).ready(function(){
    $(".steps .box").css({
        "background": "#b6a3a3af",
        "transition": "0.3s",
        "cursor": "pointer"
    });

    $(".steps .box").mouseenter(function(){
        $(this).css({
            "transform": "translateY(-10px)",
            "box-shadow": "0 18px 35px rgba(0,0,0,0.15)"
        });
    });

    $(".steps .box").mouseleave(function(){
        $(this).css({
            "transform": "translateY(0px)",
            "box-shadow": "0 12px 25px rgba(0,0,0,0.08)"
        });
    });

    $(".about .image img").css({
        "transition": "0.4s",
        "cursor": "pointer"
    });

    $(".about .image img").mouseenter(function(){
        $(this).css({
            "transform": "scale(1.08)",
            "box-shadow": "0 15px 30px rgba(0,0,0,0.3)"
        });
    });

    $(".about .image img").mouseleave(function(){
        $(this).css({
            "transform": "scale(1)",
            "box-shadow": "none"
        });
    });

});
</script>

</body>
</html>
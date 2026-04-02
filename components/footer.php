<style>

.footer {
   background: #222;
   color: #eee;
   padding: 2rem 1rem;
   font-size: 16px;
}

.footer .flex {
   display: flex;
   justify-content: space-between;
   gap: 1.5rem;
   flex-wrap: wrap;
   max-width: 1200px;
   margin: auto;
}

.footer .box {
   flex: 1 1 200px;
}

.footer .box a {
   display: flex;
   align-items: center;
   gap: 0.5rem;
   color: #ccc;
   text-decoration: none;
   margin-bottom: 0.6rem;
   transition: color 0.5s;
}

.footer .box a i {
   color: #4caf50;
   font-size: 14px;
}

.footer .box a:hover {
   color: #6fe86d;
}

.footer .credit {
   text-align: center;
   margin-top: 1.5rem;
   font-size: 18px;
   color: #aaa;
}

.footer .credit span {
   color: #4caf50;
}
@media (min-width: 992px) {

   .footer{
      font-size: 17px;
   }

   .footer .box{
      flex: 1 1 250px;
   }

   .footer .credit{
      font-size: 18px;
   }
}

@media (min-width: 768px) and (max-width: 991px) {

   .footer .flex{
      justify-content: center;
      text-align: center;
   }

   .footer .box{
      flex: 1 1 300px;
   }

   .footer .box a{
      justify-content: center;
   }

   .footer .credit{
      font-size: 16px;
   }
}

@media (max-width: 767px) {

   .footer{
      padding: 1.5rem 1rem;
      font-size: 14px;
   }

   .footer .flex{
      flex-direction: column;
      align-items: center;
      text-align: center;
   }

   .footer .box{
      width: 100%;
   }

   .footer .box a{
      justify-content: center;
      font-size: 14px;
   }

   .footer .credit{
      font-size: 14px;
   }
}
</style>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<footer class="footer">

   <section class="flex">

      <div class="box">
         <a href="tel:8160324334"><i class="fas fa-phone"></i><span>8160324334</span></a>
         <a href="tel:9512409981"><i class="fas fa-phone"></i><span>9512409981</span></a>
         <a href="tel:9924882954"><i class="fas fa-phone"></i><span>9924882954</span></a>
         <a href="mailto:pageturner899@gmail.com"><i class="fas fa-envelope"></i><span>pageturner899@gmail.com</span></a>
         <a href="#"><i class="fas fa-map-marker-alt"></i><span>Rajkot, india - 360003</span></a>
      </div>

      <div class="box">
         <a href="home.php"><span>Home</span></a>
         <a href="About Us.php"><span>About us</span></a>
         <a href="search.php"><span>Search</span></a>
         <a href="notes.php"><span>Notes</span></a>
      </div>

      <div class="box">
         <a href="#"><span>Facebook</span><i class="fab fa-facebook-f"></i></a>
         <a href="#"><span>Twitter</span><i class="fab fa-twitter"></i></a>
         <a href="#"><span>Instagram</span><i class="fab fa-instagram"></i></a>

      </div>

   </section>

   <div class="credit">&copy; copyright by <span>Page Turner</span> | all rights reserved!</div>

</footer>
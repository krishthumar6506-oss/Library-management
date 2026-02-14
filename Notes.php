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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Notes</title>

<style>

body {
    background: url("img/backgroundd.jpg") no-repeat center center;
    background-size: cover;
}

.container{
    width: 90%;
    max-width: 800px;
    background: #b7af95bb;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

h2{
    text-align: center;
    margin-bottom: 20px;
}

.note{
    background: #f0f3fa93;
    padding: 15px;
    margin: 15px 0;
    border-left: 6px solid #4e73df;
    border-radius: 5px;
    transition: 0.3s;
}

.note:hover{
    transform: scale(1.03);
    background: #e2e6f5;
}

h3{
    text-align: center;
    font-size: 28px;
    margin-bottom: 25px;
    background : #a48f8f9f ;
    color: #333;
}

.holiday-table{
    width: 100%;
    max-width: 900px;
    margin: auto;
    border-collapse: collapse;
    background: #c0aeaecd;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.holiday-table th{
    background: linear-gradient(to right, #fc694c97, #c1477e94);
    color: white;
    padding: 14px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.holiday-table td{
    padding: 12px;
    text-align: center;
    font-size: 15px;
    color: #000000f1;
}

</style>

</head>
<body>
<?php include 'components/hearder.php'; ?>

        <div class="container">
            <h2>📚 Library Notes</h2>

            <div class="note">
                A library is a place where books and study materials are available for reading and learning.
            </div>

            <div class="note">
                Libraries provide books, newspapers, magazines, and digital resources.
            </div>

            <div class="note">
                It helps students gain knowledge and improve research skills.
            </div>

            <div class="note">
                A librarian manages and organizes the books.
            </div>

            <div class="note">
                Libraries maintain silence for a peaceful study environment.
            </div>

        </div>

<br>
<br>
<h3>🗓️ Indian Holidays 2026</h3>

<table class="holiday-table">
<tr>
    <th>Date</th>
    <th>Festival Name</th>
</tr>

<tr><td>January 14</td><td>Makar Sankranti</td></tr>
<tr><td>January 26</td><td>Vasant Panchami</td></tr>
<tr><td>February 15</td><td>Maha Shivaratri</td></tr>
<tr><td>March 3</td><td>Holika Dahan</td></tr>
<tr><td>March 4</td><td>Holi</td></tr>
<tr><td>March 26</td><td>Ram Navami</td></tr>
<tr><td>April 14</td><td>Baisakhi</td></tr>
<tr><td>April 29</td><td>Akshaya Tritiya</td></tr>
<tr><td>August 4</td><td>Raksha Bandhan</td></tr>
<tr><td>August 26</td><td>Krishna Janmashtami</td></tr>
<tr><td>September 17</td><td>Ganesh Chaturthi</td></tr>
<tr><td>October 20</td><td>Dussehra (Vijayadashami)</td></tr>
<tr><td>November 8</td><td>Diwali (Deepavali)</td></tr>
<tr><td>November 9</td><td>Govardhan Puja</td></tr>
<tr><td>November 10</td><td>Bhai Dooj</td></tr>
<tr><td>November 24</td><td>Kartik Purnima</td></tr>

</table>
<br>

<?php include 'components/footer.php'; ?>

</body>
</html>

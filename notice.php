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
<title>Library Notice</title>

<style>

body {
    background: url("img/backgroundd.jpg") no-repeat center center;
    background-size: cover;
}

.container{
    width: 90%;
    max-width: 800px;
    background: #b7af95ef;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

h2{
    text-align: center;
    margin-bottom: 20px;
}

/* Notice Style */
.notice{
    background: #fff3cd;
    padding: 15px;
    margin: 15px 0;
    border-left: 6px solid #ffc107;
    border-radius: 5px;
    transition: 0.3s;
}

.notice:hover{
    transform: scale(1.03);
    background: #ffe8a1;
}

h3{
    text-align: center;
    font-size: 28px;
    margin-bottom: 25px;
    background : #a48f8ff0 ;
    color: #333;
}

.holiday-table{
    width: 100%;
    max-width: 900px;
    margin: auto;
    border-collapse: collapse;
    background: #c0aeaef9;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.holiday-table th{
    background: linear-gradient(to right, #fc694cc9, #c1477ed6);
    color: white;
    padding: 14px;
    font-size: 16px;
}

.holiday-table td{
    padding: 12px;
    text-align: center;
    font-size: 15px;
    color: #000;
}

</style>

</head>
<body>

<?php include 'components/headerr.php'; ?>

<div class="container">
    <h2>📢 Library Notice Board</h2>

    <div class="notice">
        📌 Library will remain closed on public holidays.
    </div>

    <div class="notice">
        📌 Maintain silence inside the library.
    </div>

    <div class="notice">
        📌 Return books before the due date to avoid fines.
    </div>

    <div class="notice">
        📌 Carry your student ID while issuing books.
    </div>

<div class="notice">
    📌 Students can borrow a maximum of 3 books at a time.
</div>

<div class="notice">
    📌 Lost library cards must be reported immediately.
</div>

<div class="notice">
    📌 Reference books are not allowed to be issued outside the library.
</div>

<div class="notice">
    📌 Follow the librarian's instructions at all times.
</div>

<div class="notice">
    📌 Library timing: 9:00 AM to 6:00 PM (Monday to Saturday).
</div>

</div>

<br><br>

<h3>🗓️ Indian Holidays 2026</h3>

<table class="holiday-table">
<tr>
    <th>Date</th>
    <th>Festival Name</th>
</tr>

<tr><td>January 13</td><td>Lohri</td></tr>
<tr><td>January 14</td><td>Makar Sankranti</td></tr>
<tr><td>March 4</td><td>Holi</td></tr>
<tr><td>March 26</td><td>Ram Navami</td></tr>
<tr><td>March 29</td><td>Good Friday</td></tr>
<tr><td>March 31</td><td>Eid al-Fitr</td></tr>
<tr><td>March 31</td><td>Easter</td></tr>
<tr><td>April 14</td><td>Vaisakhi</td></tr>
<tr><td>June 7</td><td>Eid al-Adha</td></tr>
<tr><td>July 6</td><td>Muharram</td></tr>
<tr><td>August 26</td><td>Krishna Janmashtami</td></tr>
<tr><td>September 16</td><td>Milad-un-Nabi</td></tr>
<tr><td>September 17</td><td>Ganesh Chaturthi</td></tr>
<tr><td>October 20</td><td>Dussehra</td></tr>
<tr><td>November 8</td><td>Diwali</td></tr>
<tr><td>November 15</td><td>Guru Nanak Jayanti</td></tr>
<tr><td>December 25</td><td>Christmas</td></tr>
</table>

<br>

<?php include 'components/footer.php'; ?>

</body>
</html>
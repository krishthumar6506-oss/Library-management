<?php
session_start();
include '../components/connect.php';

$msg = "";

if (!isset($_COOKIE['admin_id'])) {
    header('Location: login.php');
    exit;
}

/* ================= RETURN BOOK ================= */

if(isset($_POST['ret'])){

$email = trim($_POST['email']);
$isbn  = trim($_POST['isbn']);

if($email != '' && $isbn != ''){

try{

// ✅ CHECK STUDENT
$student = $conn->prepare("SELECT id FROM students WHERE email=?");
$student->execute([$email]);
$stu = $student->fetch(PDO::FETCH_ASSOC);

if(!$stu){
$msg = "student";
}else{

$student_id = $stu['id'];

// ✅ CHECK BOOK
$book = $conn->prepare("SELECT id FROM books WHERE isbn=?");
$book->execute([$isbn]);
$b = $book->fetch(PDO::FETCH_ASSOC);

if(!$b){
$msg = "book";
}else{

$book_id = $b['id'];

// ✅ CHECK ACTIVE BORROW
$borrow = $conn->prepare("
SELECT id FROM borrow 
WHERE student_id=? AND book_id=? AND status=1
");
$borrow->execute([$student_id,$book_id]);

if($borrow->rowCount()==0){
$msg = "notborrowed";
}else{

$row = $borrow->fetch(PDO::FETCH_ASSOC);
$borrow_id = $row['id'];

$date = date("Y-m-d");

// ✅ INSERT RETURN
$conn->prepare("
INSERT INTO returns (student_id, book_id, date_return)
VALUES (?,?,?)
")->execute([$student_id,$book_id,$date]);

// ✅ UPDATE BORROW STATUS → CLOSED
$conn->prepare("UPDATE borrow SET status=0 WHERE id=?")
->execute([$borrow_id]);

// ✅ UPDATE BOOK → AVAILABLE
$conn->prepare("UPDATE books SET status=1 WHERE id=?")
->execute([$book_id]);

$msg = "success";

}
}
}

}catch(PDOException $e){
$msg = "error";
}
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Return</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<link rel="stylesheet" href="../components/admin_style.css">

<style>

.container{
width:100%;
max-width:500px;
}

.box{
padding:25px;
margin-left:330px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,0.15);
}

::placeholder{
color:#999;
font-size:14px;
}

.error{
color:red;
font-size:15px;
font-weight:700;
display:block;
margin-top:4px;
}

.form-control{
transition:0.3s ease;
}

.form-control:focus{
outline:none;
border:2px solid #9c6130;
box-shadow:0 0 5px rgba(156,97,48,0.3);
}

.col-md-8{
width:98%;
padding:20px;
}

.box1{
background:#dbccccf7;
border-radius:6px;
box-shadow:0 2px 8px rgba(0,0,0,0.08);
}

.box1-body{
padding:15px;
}

table{
width:100%;
border-collapse:collapse;
}

.table thead th{
background:#bfb0b0fd;
font-weight:600;
font-size:14px;
padding:12px;
text-align:left;
border-bottom:1px solid #ddd;
}

.table tbody td{
padding:12px;
border-bottom:1px solid #eee;
font-size:14px;
}

.btn{
border:none;
padding:7px 10px;
border-radius:4px;
cursor:pointer;
}

</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="container">
<div class="box">

<h1 style="text-align:center; font-size:32px;">Book Return</h1>
<br><br>

<form id="bookForm" method="POST">

<div class="form-group">
<label>Email</label>
<input type="email" name="email" id="email" class="form-control" placeholder="Enter Student Email">
<small class="error" id="email_error"></small>
</div>

<div class="form-group">
<label>ISBN</label>
<input type="text" name="isbn" id="isbn" class="form-control" placeholder="Enter ISBN Number">
<small class="error" id="isbn_error"></small>
</div>

<button type="submit" name="ret" class="btn">
<i class="fa fa-save"></i> Book Return
</button>

</form>

</div>
</div>

<div class="col-md-8">


<div class="box1">
<div class="box1-body">

<table class="table">
<h1 style="text-align:center; font-size:32px;">All Return Status</h1>
<br>

<table class="table">

<thead>
<tr>
<th>Email</th>
<th>ISBN</th>
<th>First Name</th>
<th>Last Name</th>
<th>Borrow Date</th>
</tr>
</thead>

<tbody>
<?php
$stmt = $conn->prepare("
SELECT 
students.email,
students.firstname,
students.lastname,
books.isbn,
returns.date_return

FROM returns
JOIN students ON returns.student_id = students.id
JOIN books ON returns.book_id = books.id

");

$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

echo "
<tr>
<td>{$row['email']}</td>
<td>{$row['isbn']}</td>
<td>{$row['firstname']}</td>
<td>{$row['lastname']}</td>
<td>{$row['date_return']}</td>

</tr>";
}
?>


</tbody>
</table>

</div>
</div>
</div>

<script>

$(document).ready(function(){

$("#bookForm").submit(function(e){

var valid=true;

var student_id=$("#student_id").val().trim();
var isbn=$("#isbn").val().trim();

var number_pattern=/^[0-9]+$/;

$(".error").text("");
$(".form-control").css("border","1px solid #ccc");

if(student_id==""){
$("#student_error").text("Student ID required");
$("#student_id").css("border","2px solid red");
valid=false;
}
else if(!number_pattern.test(student_id)){
$("#student_error").text("Numbers only");
$("#student_id").css("border","2px solid red");
valid=false;
}
else{
$("#student_id").css("border","2px solid green");
}

if(isbn==""){
$("#isbn_error").text("ISBN required");
$("#isbn").css("border","2px solid red");
valid=false;
}
else if(!number_pattern.test(isbn)){
$("#isbn_error").text("Numbers only");
$("#isbn").css("border","2px solid red");
valid=false;
}
else{
$("#isbn").css("border","2px solid green");
}

if(!valid){
e.preventDefault();
}

});

});

</script>

<script>

<?php if($msg=="student"){ ?>
swal("Error","Student ID not found","error");
<?php } ?>

<?php if($msg=="book"){ ?>
swal("Error","Book not found","error");
<?php } ?>

<?php if($msg=="notborrowed"){ ?>
swal("Warning","This book was not borrowed","warning");
<?php } ?>

<?php if($msg=="success"){ ?>
swal("Success","Book Returned Successfully","success");
<?php } ?>

<?php if($msg=="error"){ ?>
swal("Error","Database Error","error");
<?php } ?>

</script>

</body>
</html>
<?php
session_start();
include '../components/connect.php';
if (!isset($_COOKIE['admin_id'])) {
    header('Location: login.php');
    exit;
}


if(isset($_POST['add'])){

$isbn      = $_POST['isbn'];
$title     = $_POST['title'];
$author    = $_POST['author'];
$publisher = $_POST['publisher'];
$pub_date  = $_POST['publisher_date'];

$check = $conn->prepare("SELECT id FROM books WHERE isbn=?");
$check->execute([$isbn]);

if($check->rowCount() > 0){

$_SESSION['error'] = "ISBN already exists";

}else{

$stmt = $conn->prepare("INSERT INTO books 
(isbn,title,author,publisher,publish_date,status)
VALUES (?,?,?,?,?,1)");

if($stmt->execute([$isbn,$title,$author,$publisher,$pub_date])){

$_SESSION['success'] = "Book added successfully";

}else{

$_SESSION['error'] = "Failed to add book";

}

}

header("Location: book.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Book</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<link rel="stylesheet" href="../components/admin_style.css">

<style>

body{
display:flex;
justify-content:center;
align-items:center;
}

.container{
width:100%;
max-width:500px;
}

.box{
padding:25px;
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

</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="container">
<div class="box">

<h1 style="text-align:center; margin-top:20px;">Add New Book</h1>
<br><br>

<form id="bookForm" method="POST">

<div class="form-group">
<label>ISBN</label>
<input type="text" name="isbn" id="isbn" class="form-control" placeholder="Enter ISBN Number">
<small class="error" id="isbn_error"></small>
</div>

<div class="form-group">
<label>Title</label>
<input type="text" name="title" id="title" class="form-control" placeholder="Enter Book Title">
<small class="error" id="title_error"></small>
</div>

<div class="form-group">
<label>Author</label>
<input type="text" name="author" id="author" class="form-control" placeholder="Enter Author Name">
<small class="error" id="author_error"></small>
</div>

<div class="form-group">
<label>Publisher</label>
<input type="text" name="publisher" id="publisher" class="form-control" placeholder="Enter Publisher Name">
<small class="error" id="publisher_error"></small>
</div>

<div class="form-group">
<label>Publish Date</label>
<input type="date" name="publisher_date" id="publisher_date" class="form-control">
<small class="error" id="date_error"></small>
</div>

<button type="submit" name="add" class="btn">
<i class="fa fa-save"></i> Save Book
</button>

</form>

</div>
</div>

<?php
if(isset($_SESSION['success'])){
echo "<script>
swal('Success','".$_SESSION['success']."','success');
</script>";
unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
echo "<script>
swal('Error','".$_SESSION['error']."','error');
</script>";
unset($_SESSION['error']);
}
?>

<script>

$(document).ready(function(){

$("#bookForm").submit(function(e){

var valid=true;

var isbn=$("#isbn").val().trim();
var title=$("#title").val().trim();
var author=$("#author").val().trim();
var publisher=$("#publisher").val().trim();
var date=$("#publisher_date").val().trim();

var isbn_pattern=/^[0-9]+$/;
var name_pattern=/^[a-zA-Z\s]+$/;

$(".error").text("");
$(".form-control").css("border","1px solid #ccc");

if(isbn==""){
$("#isbn_error").text("ISBN required");
$("#isbn").css("border","2px solid red");
valid=false;
}
else if(!isbn_pattern.test(isbn)){
$("#isbn_error").text("Numbers only");
$("#isbn").css("border","2px solid red");
valid=false;
}
else{
$("#isbn").css("border","2px solid green");
}

if(title.length<3){
$("#title_error").text("Minimum 3 characters");
$("#title").css("border","2px solid red");
valid=false;
}
else{
$("#title").css("border","2px solid green");
}

if(author==""){
$("#author_error").text("Author required");
$("#author").css("border","2px solid red");
valid=false;
}
else if(!name_pattern.test(author)){
$("#author_error").text("Letters only");
$("#author").css("border","2px solid red");
valid=false;
}
else{
$("#author").css("border","2px solid green");
}

if(publisher.length<3){
$("#publisher_error").text("Minimum 3 characters");
$("#publisher").css("border","2px solid red");
valid=false;
}
else{
$("#publisher").css("border","2px solid green");
}

if(date==""){
$("#date_error").text("Date required");
$("#publisher_date").css("border","2px solid red");
valid=false;
}
else{
$("#publisher_date").css("border","2px solid green");
}

if(!valid){
e.preventDefault();
}

});

});

</script>

</body>
</html>
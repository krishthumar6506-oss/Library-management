<?php
session_start();
include '../components/connect.php';

$msg = "";

if (!isset($_COOKIE['admin_id'])) {
    header('Location: login.php');
    exit;
}

/* UPDATE BOOK */
if(isset($_POST['update'])){

$id = $_POST['id'];
$isbn = trim($_POST['isbn']);
$title = trim($_POST['title']);
$author = trim($_POST['author']);
$publisher = trim($_POST['publisher']);
$date = $_POST['publish_date'];

try{

$stmt = $conn->prepare("UPDATE books SET isbn=?, title=?, author=?, publisher=?, publish_date=? WHERE id=?");
$stmt->execute([$isbn,$title,$author,$publisher,$date,$id]);

$msg = "success";

}catch(PDOException $e){

$msg = "error";

}

}

/* FETCH BOOK DATA */
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Book</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<link rel="stylesheet" href="../components/admin_style.css">

<style>
.container{
max-width:68rem;
}

.box{
padding:2.8rem;
}

.error{
font-size:1.25rem;
}

.form-control{
min-height:5rem;
}

</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="container admin-page-shell">
<div class="box">

<h1 class="admin-page-title">Edit Book</h1>
<p class="admin-page-subtitle">Update the core details for this catalog entry.</p>

<form id="bookForm" method="POST">

<input type="hidden" name="id" value="<?= $row['id']; ?>">

<div class="form-group">
<label>ISBN</label>
<input type="text" name="isbn" id="isbn" class="form-control" value="<?= $row['isbn']; ?>" placeholder="Enter ISBN">
<small class="error" id="isbn_error"></small>
</div>

<div class="form-group">
<label>Title</label>
<input type="text" name="title" id="title" class="form-control" value="<?= $row['title']; ?>" placeholder="Enter Book Title">
<small class="error" id="title_error"></small>
</div>

<div class="form-group">
<label>Author</label>
<input type="text" name="author" id="author" class="form-control" value="<?= $row['author']; ?>" placeholder="Enter Author Name">
<small class="error" id="author_error"></small>
</div>

<div class="form-group">
<label>Publisher</label>
<input type="text" name="publisher" id="publisher" class="form-control" value="<?= $row['publisher']; ?>" placeholder="Enter Publisher">
<small class="error" id="publisher_error"></small>
</div>

<div class="form-group">
<label>Publish Date</label>
<input type="date" name="publish_date" id="publish_date" class="form-control" value="<?= $row['publish_date']; ?>">
</div>

<br>

<button type="submit" name="update" class="btn">
<i class="fa fa-save"></i> Update Book
</button>

</form>

</div>
</div>

<script>
$(document).ready(function(){

$("#bookForm").submit(function(e){

var valid = true;

var isbn = $("#isbn").val().trim();
var title = $("#title").val().trim();

$(".error").text("");
$(".form-control").css("border","1px solid #ccc");

if(isbn==""){
$("#isbn_error").text("ISBN required");
$("#isbn").css("border","2px solid red");
valid=false;
}

if(title==""){
$("#title_error").text("Title required");
$("#title").css("border","2px solid red");
valid=false;
}

if(!valid){
e.preventDefault();
}

});

});
</script>

<script>

<?php if($msg=="success"){ ?>

swal({
title:"Success",
text:"Book Updated Successfully",
icon:"success"
}).then(()=>{
window.location="view_books.php";
});

<?php } ?>

<?php if($msg=="error"){ ?>

swal("Error","Database Error","error");

<?php } ?>

</script>

</body>
</html>

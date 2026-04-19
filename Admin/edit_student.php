<?php
session_start();
include '../components/connect.php';

$msg = "";

if (!isset($_COOKIE['admin_id'])) {
    header('Location: login.php');
    exit;
}

/* UPDATE STUDENT */
if(isset($_POST['update'])){

$id = $_POST['id'];
$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$mobile = trim($_POST['mobile']);
$gender = trim($_POST['gender']);

try{

$stmt = $conn->prepare("UPDATE students SET firstname=?, lastname=?,mobile=?, gender=? WHERE id=?");
$stmt->execute([$firstname,$lastname,$mobile,$gender,$id]);

$msg = "success";

}catch(PDOException $e){

$msg = "error";

}

}

/* FETCH STUDENT DATA */
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student</title>

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
.gender-group{
    font-size:1.4rem;
}
</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="container admin-page-shell">
<div class="box">

<h1 class="admin-page-title">Edit Student</h1>
<p class="admin-page-subtitle">Update the student profile details used across library operations.</p>

<form id="studentForm" method="POST">

<input type="hidden" name="id" value="<?= $row['id']; ?>">

<div class="form-group">
<label>First Name</label>
<input type="text" name="firstname" id="firstname" class="form-control" value="<?= $row['firstname']; ?>" placeholder="Enter First Name">
<small class="error" id="firstname_error"></small>
</div>

<div class="form-group">
<label>Last Name</label>
<input type="text" name="lastname" id="lastname" class="form-control" value="<?= $row['lastname']; ?>" placeholder="Enter Last Name">
<small class="error" id="lastname_error"></small>
</div>

<div class="form-group">
<label>Mobile</label>
<input type="text" name="mobile" id="mobile" class="form-control" value="<?= $row['mobile']; ?>" placeholder="Enter Mobile Number">
<small class="error" id="mobile_error"></small>
</div>

<div class="form-group gender-group">
<label>Gender</label>
<div class="admin-radio-row">
<label><input type="radio" name="gender" value="Male"
<?php if($row['gender']=="Male"){ echo "checked"; } ?>> Male</label>

<label><input type="radio" name="gender" value="Female"
<?php if($row['gender']=="Female"){ echo "checked"; } ?>> Female</label>

<label><input type="radio" name="gender" value="Other"
<?php if($row['gender']=="Other"){ echo "checked"; } ?>> Other</label>
</div>
</div>

<br>

<button type="submit" name="update" class="btn">
<i class="fa fa-save"></i> Update Student
</button>

</form>

</div>
</div>

<script>
$(document).ready(function () {

$('#studentForm').submit(function (e) {

var validate = true;

// FIRST NAME
var fname = $('#firstname').val().trim();
var fname_regex = /^[a-zA-Z]{3,}$/;

if(fname == ""){
$('#firstname_error').text("Firstname is required").addClass("text-danger");
validate = false;
}
else if(!fname_regex.test(fname)){
$('#firstname_error').text("Only letters allowed (min 3 characters)").addClass("text-danger");
validate = false;
}
else{
$('#firstname_error').text("");
}

// LAST NAME
var lname = $('#lastname').val().trim();
var lname_regex = /^[a-zA-Z]{3,}$/;

if(lname == ""){
$('#lastname_error').text("Lastname is required").addClass("text-danger");
validate = false;
}
else if(!lname_regex.test(lname)){
$('#lastname_error').text("Only letters allowed (min 3 characters)").addClass("text-danger");
validate = false;
}
else{
$('#lastname_error').text("");
}

// MOBILE
var mobile = $('#mobile').val().trim();
var mobile_regex = /^[0-9]{10}$/;

if(mobile == ""){
$('#mobile_error').text("Mobile number required").addClass("text-danger");
validate = false;
}
else if(!mobile_regex.test(mobile)){
$('#mobile_error').text("Enter valid 10 digit number").addClass("text-danger");
validate = false;
}
else{
$('#mobile_error').text("");
}

// STOP FORM IF INVALID
if(!validate){
e.preventDefault();
}

});

});
</script>

<script>
<?php if($msg=="success"){ ?>

swal({
title:"Success",
text:"Student Updated Successfully",
icon:"success"
}).then(()=>{
window.location="view_student.php";
});

<?php } ?>

<?php if($msg=="error"){ ?>

swal("Error","Database Error","error");

<?php } ?>

</script>

</body>
</html>

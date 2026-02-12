<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Book</title>

<link rel="stylesheet" href="../components/admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

</head>

<style>
body{
    min-height: 100vh;
    background: url("../img/back.jpg") no-repeat center center/cover;
    font-family: Arial, sans-serif;
}

.container{
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 40px;
}

.box{
    background: #ddd5d5af;
    width: 100%;
    max-width: 500px;
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.box-header{
    text-align: center;
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

.box-title{
    font-size: 20px;
    font-weight: bold;
}

.box-body{
    padding: 20px;
}

.form-group{
    margin-bottom: 15px;
}

.form-group label{
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
}

.form-control{
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.btn{
    width: 100%;
    padding: 14px;
    border: none;
    background: #805100;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
}

.box-footer{
    padding: 15px 20px 20px;
}
</style>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="container">
<div class="box">
<div class="box-header">
    <h3 class="box-title">Add New Book</h3>
</div>

<form method="POST" action="Add_Books.php">
<div class="box-body">

<div class="form-group">
    <label>ISBN</label>
    <input type="text" name="isbn" class="form-control" required>
</div>

<div class="form-group">
    <label>Title</label>
    <input type="text" name="title" class="form-control" required>
</div>

<div class="form-group">
    <label>Author</label>
    <input type="text" name="author" class="form-control" required>
</div>

<div class="form-group">
    <label>Publisher</label>
    <input type="text" name="publisher" class="form-control" required>
</div>

<div class="form-group">
    <label>Publish Date</label>
    <input type="date" name="publisher_date" class="form-control" required>
</div>

</div>

<div class="box-footer">
<button type="submit" name="add" class="btn">
    <i class="fa fa-save"></i> Save Book
</button>
</div>
</form>

</div>
</div>

<?php
if(isset($_SESSION['success'])){
    echo "<script>
        swal('Success', '".$_SESSION['success']."', 'success');
    </script>";
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    echo "<script>
        swal('Error', '".$_SESSION['error']."', 'error');
    </script>";
    unset($_SESSION['error']);
}
?>


</body>
</html>

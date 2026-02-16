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
    <input type="text" name="isbn" placeholder="Enter International Standard Book Number" class="form-control" required>
</div>

<div class="form-group">
    <label>Title</label>
    <input type="text" name="title" placeholder="Enter Book Title" class="form-control" required>
</div>

<div class="form-group">
    <label>Author</label>
    <input type="text" name="author" placeholder="Enter author name" class="form-control" required>
</div>

<div class="form-group">
    <label>Publisher</label>
    <input type="text" name="publisher" placeholder="Enter publisher name" class="form-control" required>
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

<?php 
session_start(); 
include '../components/connect.php';

$msg = "";

if (!isset($_COOKIE['admin_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_POST['add'])){

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
                $book = $conn->prepare("SELECT id, status FROM books WHERE isbn=?");
                $book->execute([$isbn]);
                $b = $book->fetch(PDO::FETCH_ASSOC);

                if(!$b){
                    $msg = "book";
                }else{

                    $book_id = $b['id'];

                    // ✅ CHECK BOOK STATUS
                    if($b['status'] == 0){
                        $msg = "borrowed";
                    }else{

                        $date = date("Y-m-d");

                        // ✅ INSERT BORROW
                        $conn->prepare("
                            INSERT INTO borrow (student_id, book_id, date_borrow, status)
                            VALUES (?,?,?,1)
                        ")->execute([$student_id,$book_id,$date]);

                        // ✅ UPDATE BOOK → BORROWED
                        $conn->prepare("UPDATE books SET status=0 WHERE id=?")
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
<title>Book Borrow</title>

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
.col-md-8{
    padding:0;
}
</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="container admin-page-shell">
<div class="box">

<h1 class="admin-page-title">Book Borrow</h1>
<p class="admin-page-subtitle">Issue a book to a student using their email and the book ISBN.</p>

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

<button type="submit" name="add" class="btn">
<i class="fa fa-save"></i> Book Borrow
</button>

</form>
</div>
</div>

<!-- TABLE -->
<div class="col-md-8 admin-table-shell">
<div class="box1">
<div class="box1-body">

<h1 class="admin-page-title">All Borrow Records</h1>
<p class="admin-page-subtitle">Recent borrow activity across the library.</p>

<div class="admin-table-wrap">
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
        $stmt = $conn->prepare("SELECT 
students.email,
students.firstname,
students.lastname,
books.isbn,
borrow.date_borrow

FROM borrow
JOIN students ON borrow.student_id = students.id
JOIN books ON borrow.book_id = books.id
");
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          echo "
          <tr>
            <td>{$row['email']}</td>
            <td>{$row['isbn']}</td>
            <td>{$row['firstname']}</td>
            <td>{$row['lastname']}</td>
            <td>{$row['date_borrow']}</td>
          </tr>";
        }
        ?>


</tbody>
</table>
</div>

</div>
</div>
</div>

<script>
$(document).ready(function(){

$("#bookForm").submit(function(e){

    var valid = true;
    var email = $("#email").val().trim();
    var isbn  = $("#isbn").val().trim();

    var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var number_pattern = /^[0-9]+$/;

    $(".error").text("");
    $(".form-control").css("border","1px solid #ccc");

    // ✅ Email Validation
    if(email == ""){
        $("#email_error").text("Email required");
        $("#email").css("border","2px solid red");
        valid = false;
    } else if(!email_pattern.test(email)){
        $("#email_error").text("Invalid email format");
        $("#email").css("border","2px solid red");
        valid = false;
    } else{
        $("#email").css("border","2px solid green");
    }

    // ✅ ISBN Validation
    if(isbn == ""){
        $("#isbn_error").text("ISBN required");
        $("#isbn").css("border","2px solid red");
        valid = false;
    } else if(!number_pattern.test(isbn)){
        $("#isbn_error").text("Numbers only");
        $("#isbn").css("border","2px solid red");
        valid = false;
    } else{
        $("#isbn").css("border","2px solid green");
    }

    if(!valid){
        e.preventDefault();
    }

});
});
</script>

<!-- SWEET ALERT -->
<script>

<?php if($msg=="student"){ ?>
swal("Error","Student email not found","error");
<?php } ?>

<?php if($msg=="book"){ ?>
swal("Error","Book not found with this ISBN","error");
<?php } ?>

<?php if($msg=="borrowed"){ ?>
swal("Warning","This book is already borrowed","warning");
<?php } ?>

<?php if($msg=="success"){ ?>
swal("Success","Book Borrowed Successfully","success");
<?php } ?>

<?php if($msg=="error"){ ?>
swal("Error","Database Error","error");
<?php } ?>

</script>

</body>
</html>

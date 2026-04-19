<?php
session_start();
include '../components/connect.php';

$msg = "";
$penalty_notice = "";
$penalty_rate = 10;

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

$conn->exec("
CREATE TABLE IF NOT EXISTS penalties (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
borrow_id INT(11) NOT NULL,
student_id INT(11) NOT NULL,
book_id INT(11) NOT NULL,
due_date DATE NOT NULL,
return_date DATE NOT NULL,
late_days INT(11) NOT NULL DEFAULT 0,
amount DECIMAL(10,2) NOT NULL DEFAULT 0,
status VARCHAR(20) NOT NULL DEFAULT 'Unpaid',
paid_at DATETIME DEFAULT NULL,
created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
");

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
$borrow_info = $conn->prepare("SELECT date_borrow FROM borrow WHERE id=?");
$borrow_info->execute([$borrow_id]);
$borrow_data = $borrow_info->fetch(PDO::FETCH_ASSOC);

$date_borrow = $borrow_data['date_borrow'];
$due_date = date("Y-m-d", strtotime($date_borrow . " +7 days"));
$late_days = 0;
$penalty_amount = 0;

if (strtotime($date) > strtotime($due_date)) {
$late_days = (int) floor((strtotime($date) - strtotime($due_date)) / 86400);
$penalty_amount = $late_days * $penalty_rate;
}

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

if ($penalty_amount > 0) {
$penalty_check = $conn->prepare("SELECT id FROM penalties WHERE borrow_id=? LIMIT 1");
$penalty_check->execute([$borrow_id]);

if ($penalty_check->rowCount() > 0) {
$existing_penalty = $penalty_check->fetch(PDO::FETCH_ASSOC);
$conn->prepare("
UPDATE penalties
SET due_date=?, return_date=?, late_days=?, amount=?, status='Unpaid', paid_at=NULL
WHERE id=?
")->execute([$due_date, $date, $late_days, $penalty_amount, $existing_penalty['id']]);
} else {
$conn->prepare("
INSERT INTO penalties (borrow_id, student_id, book_id, due_date, return_date, late_days, amount, status)
VALUES (?,?,?,?,?,?,?,'Unpaid')
")->execute([$borrow_id, $student_id, $book_id, $due_date, $date, $late_days, $penalty_amount]);
}

$penalty_notice = " Late penalty generated: Rs. " . number_format($penalty_amount, 2) . " for " . $late_days . " late day(s).";
}

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

<h1 class="admin-page-title">Book Return</h1>
<p class="admin-page-subtitle">Close an active borrow record and make the book available again.</p>

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

<div class="col-md-8 admin-table-shell">


<div class="box1">
<div class="box1-body">

<h1 class="admin-page-title">All Return Records</h1>
<p class="admin-page-subtitle">Track books that have already been returned.</p>
<div class="admin-table-wrap">
<table class="table">

<thead>
<tr>
<th>Email</th>
<th>ISBN</th>
<th>First Name</th>
<th>Last Name</th>
<th>Return Date</th>
<th>Late Days</th>
<th>Penalty</th>
<th>Penalty Status</th>
</tr>
</thead>

<tbody>
<?php
$conn->exec("
CREATE TABLE IF NOT EXISTS penalties (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
borrow_id INT(11) NOT NULL,
student_id INT(11) NOT NULL,
book_id INT(11) NOT NULL,
due_date DATE NOT NULL,
return_date DATE NOT NULL,
late_days INT(11) NOT NULL DEFAULT 0,
amount DECIMAL(10,2) NOT NULL DEFAULT 0,
status VARCHAR(20) NOT NULL DEFAULT 'Unpaid',
paid_at DATETIME DEFAULT NULL,
created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
");

$stmt = $conn->prepare("
SELECT 
students.email,
students.firstname,
students.lastname,
books.isbn,
returns.date_return,
COALESCE(penalties.late_days, 0) AS late_days,
COALESCE(penalties.amount, 0) AS penalty_amount,
COALESCE(penalties.status, 'No Penalty') AS penalty_status

FROM returns
JOIN students ON returns.student_id = students.id
JOIN books ON returns.book_id = books.id
LEFT JOIN penalties
ON penalties.student_id = returns.student_id
AND penalties.book_id = returns.book_id
AND penalties.return_date = returns.date_return

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
<td>{$row['late_days']}</td>
<td>Rs. ".number_format((float) $row['penalty_amount'], 2)."</td>
<td>{$row['penalty_status']}</td>
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

var valid=true;

var email=$("#email").val().trim();
var isbn=$("#isbn").val().trim();

var email_pattern=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
var number_pattern=/^[0-9]+$/;

$(".error").text("");
$(".form-control").css("border","1px solid #ccc");

if(email==""){
$("#email_error").text("Email required");
$("#email").css("border","2px solid red");
valid=false;
}
else if(!email_pattern.test(email)){
$("#email_error").text("Invalid email format");
$("#email").css("border","2px solid red");
valid=false;
}
else{
$("#email").css("border","2px solid green");
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
swal("Error","Student email not found","error");
<?php } ?>

<?php if($msg=="book"){ ?>
swal("Error","Book not found","error");
<?php } ?>

<?php if($msg=="notborrowed"){ ?>
swal("Warning","This book was not borrowed","warning");
<?php } ?>

<?php if($msg=="success"){ ?>
swal("Success","Book Returned Successfully<?php echo addslashes($penalty_notice); ?>","success");
<?php } ?>

<?php if($msg=="error"){ ?>
swal("Error","Database Error","error");
<?php } ?>

</script>

</body>
</html>

<?php
session_start();
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Status</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<link rel="stylesheet" href="../components/admin_style.css">

<style>
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

th, td{
padding:12px;
font-size: 14px;
border-bottom:1px solid #ddd;
}

th{
background:#bfb0b0fd;
}
</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="col-md-8">
<div class="box1">
<div class="box1-body">

<h1 style="text-align:center; font-size:32px;">Borrow & Return Records</h1>
<br>

<table>

<thead>
<tr>
<th>Email</th>
<th>ISBN</th>
<th>Name</th>
<th>Borrow Date</th>
<th>Return Date</th>
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
borrow.date_borrow,
returns.date_return

FROM borrow
JOIN students ON borrow.student_id = students.id
JOIN books ON borrow.book_id = books.id
LEFT JOIN returns 
ON borrow.student_id = returns.student_id 
AND borrow.book_id = returns.book_id

ORDER BY borrow.date_borrow DESC
");

$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

$returnDate = $row['date_return'] 
    ? $row['date_return'] 
    : "<span style='color:gray;'>Not Returned</span>";

echo "
<tr>
<td>{$row['email']}</td>
<td>{$row['isbn']}</td>
<td>{$row['firstname']} {$row['lastname']}</td>
<td>{$row['date_borrow']}</td>
<td>$returnDate</td>
</tr>";
}
?>

</tbody>
</table>

</div>
</div>
</div>

</body>
</html>
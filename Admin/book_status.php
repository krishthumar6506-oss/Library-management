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
padding:0;
}
</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="col-md-8 admin-table-shell">
<div class="box1">
<div class="box1-body">

<h1 class="admin-page-title">Borrow & Return Records</h1>
<p class="admin-page-subtitle">A full history of borrowed books and whether they have been returned.</p>
<div class="admin-table-wrap">
<table class="table">

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
    : "<span class='admin-status-muted'>Not Returned</span>";

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
</div>

</body>
</html>

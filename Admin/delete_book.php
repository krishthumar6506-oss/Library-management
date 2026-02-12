<?php
include '../components/connect.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->execute([$id]);

header("Location: books.php");
exit;

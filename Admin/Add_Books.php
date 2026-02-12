<?php
session_start();
include '../components/connect.php';

if(isset($_POST['add'])){

    $isbn      = $_POST['isbn'];
    $title     = $_POST['title'];
    $author    = $_POST['author'];
    $publisher = $_POST['publisher'];
    $pub_date  = $_POST['publisher_date'];

    $check = $conn->prepare("SELECT id FROM books WHERE isbn = ?");
    $check->execute([$isbn]);

    if($check->rowCount() > 0){
        $_SESSION['error'] = 'ISBN already exists';
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO books (isbn, title, author, publisher, publish_date, status)
             VALUES (?, ?, ?, ?, ?, 1)"
        );

        if($stmt->execute([$isbn, $title, $author, $publisher, $pub_date])){
            $_SESSION['success'] = 'Book added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add book';
        }
    }
}

header("Location: book.php");
exit;

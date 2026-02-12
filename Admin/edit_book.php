<?php
include '../components/connect.php';

/* CHECK ADMIN LOGIN */
if(!isset($_COOKIE['admin_id'])){
  header('location:login.php');
  exit;
}

/* CHECK BOOK ID */
if(!isset($_GET['id'])){
  header("Location: books.php");
  exit;
}

$id = $_GET['id'];

/* FETCH BOOK DATA */
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$book){
  header("Location: books.php");
  exit;
}

/* UPDATE BOOK */
if(isset($_POST['update'])){

  $stmt = $conn->prepare(
    "UPDATE books 
     SET isbn=?, title=?, author=?, publisher=?
     WHERE id=?"
  );

  $stmt->execute([
    $_POST['isbn'],
    $_POST['title'],
    $_POST['author'],
    $_POST['publisher'],
    $id
  ]);

  header("Location: books.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Book</title>

<link rel="stylesheet" href="../components/admin_style.css">

<style>
/* PAGE BACKGROUND */
body{
  min-height: 100vh;
  background: url("../img/backgroud.jpg") no-repeat center center / cover;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: Arial, Helvetica, sans-serif;
}

/* FORM CONTAINER */
form{
  background: rgba(255,255,255,0.95);
  padding: 25px 30px;
  border-radius: 8px;
  width: 380px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* INPUTS */
form input{
  width: 100%;
  padding: 10px 12px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

/* FOCUS */
form input:focus{
  outline: none;
  border-color: #00a65a;
  box-shadow: 0 0 0 2px rgba(0,166,90,0.2);
}

/* BUTTON */
form button{
  width: 100%;
  padding: 10px;
  background: #00a65a;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 15px;
  cursor: pointer;
  font-weight: 600;
}

form button:hover{
  background: #008d4c;
}
</style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<form method="POST">
  <input type="text" name="isbn" value="<?= $book['isbn']; ?>" required>

  <input type="text" name="title" value="<?= $book['title']; ?>" required>

  <input type="text" name="author" value="<?= $book['author']; ?>" required>

  <input type="text" name="publisher" value="<?= $book['publisher']; ?>" required>

  <button type="submit" name="update">Update Book</button>
</form>

</body>
</html>

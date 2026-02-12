<?php
include '../components/connect.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
?>
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

/* INPUTS & SELECT */
form input,
form select{
  width: 100%;
  padding: 10px 12px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

/* FOCUS */
form input:focus,
form select:focus{
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

<?php include '../components/admin_header.php'; ?>

<form method="POST">
  <input type="text" name="firstname" value="<?= $student['firstname']; ?>" required>
  <input type="text" name="lastname" value="<?= $student['lastname']; ?>" required>
  <input type="text" name="mobile" value="<?= $student['mobile']; ?>">
  <select name="gender">
    <option <?= $student['gender']=='Male'?'selected':''; ?>>Male</option>
    <option <?= $student['gender']=='Female'?'selected':''; ?>>Female</option>
    <option <?= $student['gender']=='Other'?'selected':''; ?>>Other</option>
  </select>
  <button type="submit" name="update">Update</button>
</form>

<?php
if(isset($_POST['update'])){
  $stmt = $conn->prepare(
    "UPDATE students SET firstname=?, lastname=?, mobile=?, gender=? WHERE id=?"
  );
  $stmt->execute([
    $_POST['firstname'],
    $_POST['lastname'],
    $_POST['mobile'],
    $_POST['gender'],
    $id
  ]);

  header("Location: view_student.php");
}
?>

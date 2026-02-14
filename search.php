<?php
session_start();
if(!isset($_SESSION['student_id'])){
   header("Location: login.php");
   exit();
}
?>


include 'components/connect.php'; // PDO connection

$search = '';

if(isset($_GET['search']) && $_GET['search'] !== ''){
  $search = trim($_GET['search']);

  $stmt = $conn->prepare("
    SELECT * FROM books
    WHERE 
      id LIKE :search OR
      isbn LIKE :search OR
      title LIKE :search OR
      author LIKE :search
  ");

  $like = "%$search%";
  $stmt->bindParam(':search', $like, PDO::PARAM_STR);
  $stmt->execute();

} else {
  $stmt = $conn->prepare("SELECT * FROM books");
  $stmt->execute();
}

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Search</title>
<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>
<style>
  body {
    background: url("img/backgroundd.jpg") no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
  }

  .container {
    background: #ffffff;
    padding: 20px;
    margin-top: 50px;
    border-radius: 5px;
  }

  .input-group input {
    border-radius: 4px 0 0 4px;
  }

  .input-group button {
    border-radius: 0 4px 4px 0;
  }

  table {
    background: #ffffff;
  }

  th {
    background-color: #007bff;
    color: white;
    text-align: center;
  }

  td {
    text-align: center;
  }

  tr:hover {
    background-color: #f2f2f2;
  }
  
</style>

<body>
<?php include 'components/hearder.php'; ?>
<div class="container" style="margin-top:50px;">

  <!-- SEARCH BOX -->
  <form method="GET">
    <div class="input-group input-group-lg">
      <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search by ID, ISBN, Title or Author"
        value="<?= htmlspecialchars($search); ?>"
      >
      <span class="input-group-btn">
        <button class="btn btn-primary" type="submit">Search</button>
      </span>
    </div>
  </form>

  <br>

  <!-- RESULTS TABLE -->
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>ISBN</th>
        <th>Title</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>

    <?php if(count($books) > 0): ?>
      <?php foreach($books as $row): ?>
        <?php
          $status = ($row['status'] == 0)
            ? '<span class="label label-success">Available</span>'
            : '<span class="label label-danger">Not Available</span>';
        ?>
        <tr>
          <td><?= $row['id']; ?></td>
          <td><?= $row['isbn']; ?></td>
          <td><?= $row['title']; ?></td>
          <td><?= $row['author']; ?></td>
          <td><?= $row['publisher']; ?></td>
          <td><?= $row['publish_date']; ?></td>
          <td><?= $status; ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" class="text-center">No results found</td>
      </tr>
    <?php endif; ?>

    </tbody>
  </table>

</div>
<?php include 'components/footer.php'; ?>
</body>
</html>

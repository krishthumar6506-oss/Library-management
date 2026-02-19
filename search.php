<?php
session_start();

if(!isset($_SESSION['student_id'])){
   header("Location: login.php");
   exit();
}

include 'components/connect.php';

$search = '';
$books = [];

try {

    if(isset($_GET['search']) && $_GET['search'] !== ''){

        $search = trim($_GET['search']);

        $stmt = $conn->prepare("
            SELECT * FROM books
            WHERE 
                publisher LIKE :search OR
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

} catch(PDOException $e){
    echo "Database Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Search</title>
<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">

<style>
body {
    background: url("img/backgroundd.jpg") no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}

.container {
    background: #cbc0c0d0;
    padding: 20px;
    margin-top: 50px;
    border-radius: 5px;
}

.input-group input {
    border-radius: 4px 0 0 4px;
     background-color: #f0eaead0;
}

.input-group button {
    padding: 15px 19px;
    border-radius: 0 4px 4px 0;
}

table {
    background: #d8cacace;
}

th {
    background-color: #007bff;
    color: white;
    text-align: center;
}

td {
    text-align: center;
}

</style>
</head>

<body>

<?php include 'components/hearder.php'; ?>

<div class="container">

    <form method="GET">
        <div class="input-group input-group-lg">
            <input type="text" name="search" class="form-control" placeholder="Search by ISBN, Title, publisher or Author" value="<?= htmlspecialchars($search); ?>">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Search</button>
            </span>
        </div>
    </form>

    <br>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>

        <?php if(!empty($books)): ?>

            <?php foreach($books as $row): ?>

                <?php
                    $status = ($row['status'] == 0)
                        ? '<span class="label label-success">Available</span>'
                        : '<span class="label label-danger">Not Available</span>';
                ?>

                <tr>
                    <td><?= htmlspecialchars($row['isbn']); ?></td>
                    <td><?= htmlspecialchars($row['title']); ?></td>
                    <td><?= htmlspecialchars($row['author']); ?></td>
                    <td><?= htmlspecialchars($row['publisher']); ?></td>
                    <td><?= htmlspecialchars($row['publish_date']); ?></td>
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
<br>
<br>
<br>
<br>
<?php include 'components/footer.php'; ?>

</body>
</html>

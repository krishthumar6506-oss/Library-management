<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

include 'components/connect.php';

$student_id = (int) $_SESSION['student_id'];
$search = '';
$books = [];
$success = '';
$error = '';

try {
    $conn->exec("
        CREATE TABLE IF NOT EXISTS book_requests (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            student_id INT(11) NOT NULL,
            book_id INT(11) NOT NULL,
            request_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            status VARCHAR(20) NOT NULL DEFAULT 'Pending'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");

    if (isset($_POST['request_book'])) {
        $book_id = (int) ($_POST['book_id'] ?? 0);

        if ($book_id <= 0) {
            $error = "Invalid book request.";
        } else {
            $book_stmt = $conn->prepare("SELECT id, title, status FROM books WHERE id = ?");
            $book_stmt->execute([$book_id]);
            $book = $book_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$book) {
                $error = "Book not found.";
            } elseif ((int) $book['status'] === 1) {
                $error = "This book is available now, so request is not needed.";
            } else {
                $check_stmt = $conn->prepare("
                    SELECT id FROM book_requests
                    WHERE student_id = ? AND book_id = ? AND status = 'Pending'
                ");
                $check_stmt->execute([$student_id, $book_id]);

                if ($check_stmt->fetch()) {
                    $error = "You already requested this book.";
                } else {
                    $insert_stmt = $conn->prepare("
                        INSERT INTO book_requests (student_id, book_id, status)
                        VALUES (?, ?, 'Pending')
                    ");
                    $insert_stmt->execute([$student_id, $book_id]);
                    $success = "Request sent for \"" . $book['title'] . "\".";
                }
            }
        }
    }

    if(isset($_GET['search']) && $_GET['search'] !== ''){
        $search = trim($_GET['search']);

        $stmt = $conn->prepare("
            SELECT books.*,
                   EXISTS(
                       SELECT 1
                       FROM book_requests
                       WHERE book_requests.book_id = books.id
                         AND book_requests.student_id = :student_id
                         AND book_requests.status = 'Pending'
                   ) AS already_requested
            FROM books
            WHERE publisher LIKE :search
               OR isbn LIKE :search
               OR title LIKE :search
               OR author LIKE :search
            ORDER BY title ASC
        ");

        $like = "%$search%";
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':search', $like, PDO::PARAM_STR);
        $stmt->execute();

    } else {
        $stmt = $conn->prepare("
            SELECT books.*,
                   EXISTS(
                       SELECT 1
                       FROM book_requests
                       WHERE book_requests.book_id = books.id
                         AND book_requests.student_id = :student_id
                         AND book_requests.status = 'Pending'
                   ) AS already_requested
            FROM books
            ORDER BY title ASC
        ");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    $error = "Database Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Search</title>

<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
body{
    background:url("img/backgroundd.jpg") no-repeat center center fixed;
    background-size:cover;
    font-family:Arial,sans-serif;
}

.container{
    background:#cbc0c0fa;
    padding:20px;
    margin-top:50px;
    border-radius:5px;
}

.input-group input{
    border-radius:4px 0 0 4px;
    background-color:#f0eaeaf9;
}

.input-group button{
    padding:15px 19px;
    border-radius:0 4px 4px 0;
}

table{
    background:#d8cacaf5;
}

th{
    background-color:#007bff;
    color:white;
    text-align:center;
}

td{
    text-align:center;
}

.text-danger{
    color:red;
    font-size:14px;
}

.is-invalid{
    border:2px solid red!important;
}

.is-valid{
    border:2px solid green!important;
}

.alert-wrap{
    margin-bottom:20px;
}

.request-btn{
    border:none;
    padding:8px 14px;
    border-radius:6px;
    background:#dc3545;
    color:#fff;
    font-weight:600;
}

.request-btn:disabled{
    background:#6c757d;
    cursor:not-allowed;
}

.status-cell{
    min-width:180px;
}
</style>
</head>

<body>

<?php include 'components/headerr.php'; ?>

<div class="container">

<?php if($success !== ''): ?>
    <div class="alert-wrap">
        <div class="alert alert-success mb-0"><?= htmlspecialchars($success); ?></div>
    </div>
<?php endif; ?>

<?php if($error !== ''): ?>
    <div class="alert-wrap">
        <div class="alert alert-danger mb-0"><?= htmlspecialchars($error); ?></div>
    </div>
<?php endif; ?>

<!-- SEARCH FORM -->
<form method="GET" id="searchForm" novalidate>
    <div class="input-group input-group-lg">
        <input type="text" id="search" name="search" class="form-control"
        placeholder="Search by ISBN, Title, Publisher or Author"
        value="<?= htmlspecialchars($search); ?>">

        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Search</button>
        </span>
    </div>
    <small id="search_error"></small>
</form>

<br>

<!-- BOOK TABLE -->
<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>ISBN</th>
    <th>Title</th>
    <th>Author</th>
    <th>Publisher</th>
    <th>Date</th>
    <th>Status</th>
    <th>Request</th>
</tr>
</thead>

<tbody>

<?php if(!empty($books)): ?>
    <?php foreach($books as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['isbn']); ?></td>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= htmlspecialchars($row['author']); ?></td>
        <td><?= htmlspecialchars($row['publisher']); ?></td>
        <td><?= htmlspecialchars($row['publish_date']); ?></td>

        <!-- STATUS COLUMN -->
        <td class="status-cell">
            <?php if($row['status'] == 1): ?>
                <span class="badge bg-success">Available</span>
            <?php else: ?>
                <span class="badge bg-danger">Not Available</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if($row['status'] == 0): ?>
                <?php if((int) $row['already_requested'] === 1): ?>
                    <button type="button" class="request-btn" disabled>Requested</button>
                <?php else: ?>
                    <form method="POST" class="m-0">
                        <input type="hidden" name="book_id" value="<?= (int) $row['id']; ?>">
                        <button type="submit" name="request_book" class="request-btn">Request Book</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <span class="text-success fw-semibold">No need</span>
            <?php endif; ?>
        </td>
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

<br><br><br><br>

<?php include 'components/footer.php'; ?>

<script>
$(document).ready(function(){

    $('#searchForm').submit(function(e){

        var search = $('#search').val().trim();
        var validate_search = true;

        if(search==""){
            $('#search_error')
            .text("Search field cannot be empty")
            .addClass('text-danger');

            $('#search')
            .removeClass("is-valid")
            .addClass("is-invalid");

            validate_search=false;
        }
        else{
            $('#search_error').text("");

            $('#search')
            .removeClass("is-invalid")
            .addClass("is-valid");

            validate_search=true;
        }

        if(!validate_search){
            e.preventDefault();
        }

    });

});
</script>

</body>
</html>

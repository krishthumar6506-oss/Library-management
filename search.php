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
            WHERE publisher LIKE :search 
            OR isbn LIKE :search 
            OR title LIKE :search 
            OR author LIKE :search
        ");

        $like = "%$search%";
        $stmt->bindParam(':search',$like,PDO::PARAM_STR);
        $stmt->execute();

    } else {
        $stmt = $conn->prepare("SELECT * FROM books");
        $stmt->execute();
    }

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    echo "Database Error: ".$e->getMessage();
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
</style>
</head>

<body>

<?php include 'components/headerr.php'; ?>

<div class="container">

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
        <td>
            <?php if($row['status'] == 1): ?>
                <span class="badge bg-success">Available</span>
            <?php else: ?>
                <span class="badge bg-danger">Not Available</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>

<?php else: ?>
    <tr>
        <td colspan="6" class="text-center">No results found</td>
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
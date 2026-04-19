<?php
include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
   exit;
}

$requests = [];
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

    $stmt = $conn->prepare("
        SELECT
            book_requests.id,
            book_requests.request_date,
            book_requests.status,
            students.firstname,
            students.lastname,
            students.email,
            books.title,
            books.author,
            books.isbn,
            books.status AS book_status
        FROM book_requests
        JOIN students ON students.id = book_requests.student_id
        JOIN books ON books.id = book_requests.book_id
        ORDER BY
            CASE WHEN book_requests.status = 'Pending' THEN 0 ELSE 1 END,
            book_requests.request_date DESC
    ");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Unable to load book requests.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Requests</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<link rel="stylesheet" href="../components/admin_style.css">
<style>
.requests-wrap{
    padding:0;
}

.requests-box table{
    background:#fff;
}

.badge-pill{
    min-width:11rem;
}

.pending{
    background:#fff3cd;
    color:#856404;
}

.available{
    background:#d1e7dd;
    color:#0f5132;
}

.borrowed{
    background:#f8d7da;
    color:#842029;
}

@media (max-width: 767px){
    .requests-wrap{
        padding:1rem;
    }

    .requests-box{
        overflow-x:auto;
    }
}
</style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="requests-wrap">
    <div class="requests-box">
        <h1 class="heading">Book Requests</h1>

        <?php if($error !== ''): ?>
            <p><?= htmlspecialchars($error); ?></p>
        <?php elseif(empty($requests)): ?>
            <p class="admin-empty">No book requests found.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Book</th>
                        <th>ISBN</th>
                        <th>Author</th>
                        <th>Request Date</th>
                        <th>Request Status</th>
                        <th>Book Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['firstname'] . ' ' . $request['lastname']); ?></td>
                            <td><?= htmlspecialchars($request['email']); ?></td>
                            <td><?= htmlspecialchars($request['title']); ?></td>
                            <td><?= htmlspecialchars($request['isbn']); ?></td>
                            <td><?= htmlspecialchars($request['author']); ?></td>
                            <td><?= htmlspecialchars(date('d M Y, h:i A', strtotime($request['request_date']))); ?></td>
                            <td>
                                <span class="badge-pill <?= strtolower($request['status']) === 'pending' ? 'pending' : 'available'; ?>">
                                    <?= htmlspecialchars($request['status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-pill <?= (int) $request['book_status'] === 1 ? 'available' : 'borrowed'; ?>">
                                    <?= (int) $request['book_status'] === 1 ? 'Available' : 'Not Available'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>

<?php
include '../components/connect.php';

if(!isset($_COOKIE['admin_id'])){
  header('location:login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Books List</title>

<link rel="stylesheet" href="../components/admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<style>
  .col-md-8{
    padding: 0;
  }

  .btn-sm{
    font-size:13px;
  }

  .btn-success{
    background: #00a65a;
    color: #fff;
  }

  .btn-success:hover{
    background: #008d4c;
  }

  .btn-danger{
    background: #dd4b39;
    color: #fff;
  }

  .btn-danger:hover{
    background: #c23321;
  }

  .btn i{
    font-size:14px;
  }
  /* Responsive Media Queries */

/* Large screens >= 1200px */
@media (min-width: 1200px){
    .col-md-8{
        width: 80%;
        margin: 0 auto;
    }
}

/* Medium screens 768px - 1199px */
@media (min-width: 768px) and (max-width: 1199px){
    .col-md-8{
        width: 90%;
    }
}

/* Small screens <= 767px */
@media (max-width: 767px){
    .col-md-8{
        width: 100%;
    }

    .box1-body{
        overflow-x: auto; /* allow horizontal scroll if table too wide */
    }

    .table{
        display: block;
        min-width: 500px; /* keeps table readable */
    }

    .table thead{
        display: none; /* hide headers for stacked view */
    }

    .table tbody tr{
        display: block;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
    }

    .table tbody td{
        display: flex;
        justify-content: space-between;
        padding: 8px;
        text-align: right;
        position: relative;
    }

    .table tbody td::before{
        content: attr(data-label); /* show column name */
        position: absolute;
        left: 10px;
        width: 45%;
        font-weight: 600;
        text-align: left;
    }

    /* Optional: Buttons smaller on mobile */
    .btn, .btn-sm{
        padding: 6px 10px;
        font-size: 12px;
    }
}
  </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="col-md-8">
  <div class="box1">
    <div class="box1-body">

        <h1 class="admin-page-title">All Books</h1>
        <p class="admin-page-subtitle">Review, edit, and remove catalog items from the library collection.</p>
        <table class="table">
        <thead>
          <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Publisher</th>
            <th>Date</th>
            <th>Tools</th>
          </tr>
        </thead>
        <tbody>

        <?php
        $stmt = $conn->prepare("SELECT * FROM books");
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          echo "
          <tr>
            <td>{$row['isbn']}</td>
            <td>{$row['title']}</td>
            <td>{$row['author']}</td>
            <td>{$row['publisher']}</td>
            <td>{$row['publish_date']}</td>
            <td>
              <button class='btn btn-success edit' data-id='{$row['id']}'>
   <i class='fa fa-edit'></i>
</button>
              <button class='btn btn-danger delete' data-id='{$row['id']}'>
                <i class='fa fa-trash'></i>
              </button>
            </td>
          </tr>";
        }
        ?>

        </tbody>
      </table>

    </div>
  </div>
</div>

</body>
<script>
document.querySelectorAll('.edit').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    window.location.href = "edit_book.php?id=" + id;
  });
});
</script><script>
document.querySelectorAll('.edit').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    window.location.href = "edit_book.php?id=" + id;
  });
});

document.querySelectorAll('.delete').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.getAttribute('data-id');

    if (confirm("Are you sure you want to delete this book?")) {
      window.location.href = "delete_book.php?id=" + id;
    }
  });
});
</script>


</html>

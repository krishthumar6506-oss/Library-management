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
    width: 98%;
    padding: 20px;
  }

  .box1{
    background: #dbccccdc;
    border-radius:6px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
  }

  .box1-body{
    padding:15px;
  }

  table{
    width:100%;
    border-collapse:collapse;
  }

  .table thead th{
    background: #bfb0b0e4;
    font-weight:600;
    font-size: 14px;
    padding:12px;
    text-align:left;
    border-bottom:1px solid #ddd;
    position:relative;
  }

  .table tbody td{
    padding:12px;
    border-bottom:1px solid #eee;
    font-size:14px;
  }

  .btn{
    border:none;
    padding:7px 10px;
    border-radius:4px;
    cursor:pointer;
  }

  .btn-sm{
    font-size:13px;
  }

  .btn-success{
    background:#00a65a;
    color:#fff;
  }

  .btn-success:hover{
    background:#008d4c;
  }

  .btn-danger{
    background:#dd4b39;
    color:#fff;
  }

  .btn-danger:hover{
    background:#c23321;
  }

  .btn i{
    font-size:14px;
  }
  </style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<div class="col-md-8">
  <div class="box1">
    <div class="box1-body">

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

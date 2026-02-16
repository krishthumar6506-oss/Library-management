  <?php

  include '../components/connect.php';

  if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];
  }else{
    $admin_id = '';
    header('location:login.php');
  }

  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
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

        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Firstname</th>
              <th>Lastname</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Gender</th>
              <th>Tools</th>
            </tr>
          </thead>
          <tbody>

          <?php
          $sql = "SELECT * FROM students";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "
        <tr>
                <td>".$row['firstname']."</td>
                <td>".$row['lastname']."</td>
                <td>".$row['email']."</td>
                <td>".$row['mobile']."</td>
                <td>".$row['gender']."</td>
                <td>
                  <button class='btn btn-success btn-sm edit' data-id='".$row['id']."'>
                    <i class='fa fa-edit'></i>
                  </button>
                  <button class='btn btn-danger btn-sm delete' data-id='".$row['id']."'>
                    <i class='fa fa-trash'></i>
                  </button>
                </td>
              </tr>
            ";
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

      if(confirm("Are you sure you want to delete this student?")){
        window.location.href = "delete_student.php?id=" + id;
      }
    });
  });
  </script>

  </html>
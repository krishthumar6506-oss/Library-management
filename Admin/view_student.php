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
/* Base Table Styling */
.col-md-8{
  padding: 0;
  margin: 0 auto;
}

.box1-body{
  overflow-x: auto; /* allow horizontal scroll on small screens */
}

table{
  width:100%;
  border-collapse:collapse;
  min-width: 600px; /* prevents columns from collapsing too much */
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

/* Responsive Media Queries */

/* Large screens >= 1200px */
@media (min-width: 1200px){
  .col-md-8{
    width: 80%;
  }
  .table thead th,
  .table tbody td{
    font-size: 14px;
    padding: 12px 15px;
  }
}

/* Medium screens 768px - 1199px */
@media (min-width: 768px) and (max-width: 1199px){
  .col-md-8{
    width: 90%;
  }
  .table thead th,
  .table tbody td{
    font-size: 13px;
    padding: 10px 12px;
  }
}

/* Small screens <= 767px */
@media (max-width: 767px){
  .col-md-8{
    width: 100%;
  }
  .table{
    display: block;
    overflow-x: auto;
  }
  .table thead,
  .table tbody,
  .table tr,
  .table th,
  .table td{
    display: block; /* stack table rows on mobile */
  }
  .table thead tr{
    display: none; /* hide table header on very small screens */
  }
  .table tbody tr{
    margin-bottom: 15px;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 6px;
  }
  .table tbody td{
    text-align: right;
    padding-left: 50%;
    position: relative;
  }
  .table tbody td::before{
    content: attr(data-label);
    position: absolute;
    left: 10px;
    width: 45%;
    padding-right: 10px;
    font-weight: 600;
    text-align: center;
  }
}

  </style>
  </head>
  <body>
      <?php include '../components/admin_header.php'; ?>

      <div class="col-md-8">
    <div class="box1">
      <div class="box1-body">

        <h1 class="admin-page-title">All Students</h1>
        <p class="admin-page-subtitle">Manage student records, contact details, and profile updates.</p>
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
    document.querySelectorAll('.edit').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    window.location.href = "edit_student.php?id=" + id;
    });
  });

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

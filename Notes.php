
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Notes</title>

<style>

body {
    background: url("img/backgroundd.jpg") no-repeat center center;
    background-size: cover;
}

.container{
    width: 90%;
    max-width: 800px;
    background: #b7af95ef;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

h2{
    text-align: center;
    margin-bottom: 20px;
}

.note{
    background: #f0f3faed;
    padding: 15px;
    margin: 15px 0;
    border-left: 6px solid #4e73df;
    border-radius: 5px;
    transition: 0.3s;
}

.note:hover{
    transform: scale(1.03);
    background: #e2e6f5;
}

h3{
    text-align: center;
    font-size: 28px;
    margin-bottom: 25px;
    background : #a48f8ff0 ;
    color: #333;
}

.holiday-table{
    width: 100%;
    max-width: 900px;
    margin: auto;
    border-collapse: collapse;
    background: #c0aeaef9;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.holiday-table th{
    background: linear-gradient(to right, #fc694cc9, #c1477ed6);
    color: white;
    padding: 14px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.holiday-table td{
    padding: 12px;
    text-align: center;
    font-size: 15px;
    color: #000000f1;
}
@media (min-width: 992px) {

    .container{
        max-width: 900px;
        padding: 40px;
    }

    h2{
        font-size: 32px;
    }

    h3{
        font-size: 30px;
    }

    .note{
        font-size: 18px;
    }

    .holiday-table th,
    .holiday-table td{
        font-size: 16px;
        padding: 14px;
    }
}

@media (min-width: 768px) and (max-width: 991px) {

    .container{
        width: 95%;
        padding: 25px;
    }

    h2{
        font-size: 26px;
    }

    h3{
        font-size: 24px;
    }

    .note{
        font-size: 16px;
    }

    .holiday-table th,
    .holiday-table td{
        font-size: 14px;
        padding: 10px;
    }
}

@media (max-width: 767px) {

    body{
        background-position: top;
    }

    .container{
        width: 100%;
        padding: 15px;
        border-radius: 0;
    }

    h2{
        font-size: 20px;
    }

    h3{
        font-size: 18px;
    }

    .note{
        font-size: 14px;
        margin: 10px 0;
    }

    .holiday-table{
        font-size: 13px;
    }

    .holiday-table th,
    .holiday-table td{
        padding: 8px;
    }
}

</style>

</head>
<body>
<?php include 'components/hearder.php'; ?>

        <div class="container">
            <h2>📚 Library Notes</h2>

            <div class="note">
                A library is a place where books and study materials are available for reading and learning.
            </div>

            <div class="note">
                Libraries provide books, newspapers, magazines, and digital resources.
            </div>

            <div class="note">
                It helps students gain knowledge and improve research skills.
            </div>

            <div class="note">
                A librarian manages and organizes the books.
            </div>

            <div class="note">
                Libraries maintain silence for a peaceful study environment.
            </div>

        </div>

<br>
<br>
<h3>🗓️ Indian Holidays 2026</h3>

<table class="holiday-table">
<tr>
    <th>Date</th>
    <th>Festival Name</th>
</tr>
<!-- January -->
<tr><td>January 13</td><td>Lohri</td></tr>
<tr><td>January 14</td><td>Makar Sankranti</td></tr>

<!-- March -->
<tr><td>March 4</td><td>Holi</td></tr>
<tr><td>March 26</td><td>Ram Navami</td></tr>
<tr><td>March 29</td><td>Good Friday</td></tr>
<tr><td>March 31</td><td>Eid al-Fitr</td></tr>
<tr><td>March 31</td><td>Easter</td></tr>

<!-- April -->
<tr><td>April 14</td><td>Vaisakhi</td></tr>

<!-- June -->
<tr><td>June 7</td><td>Eid al-Adha</td></tr>

<!-- July -->
<tr><td>July 6</td><td>Muharram</td></tr>

<!-- August -->
<tr><td>August 26</td><td>Krishna Janmashtami</td></tr>

<!-- September -->
<tr><td>September 16</td><td>Milad-un-Nabi</td></tr>
<tr><td>September 17</td><td>Ganesh Chaturthi</td></tr>

<!-- October -->
<tr><td>October 20</td><td>Dussehra</td></tr>

<!-- November -->
<tr><td>November 8</td><td>Diwali</td></tr>
<tr><td>November 15</td><td>Guru Nanak Jayanti</td></tr>

<!-- December -->
<tr><td>December 25</td><td>Christmas</td></tr>
</table>
<br>

<?php include 'components/footer.php'; ?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Header Design</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

header {
     padding-top: 130px;
}

.navbar-custom {
    position: fixed;
    top: 5px;
    left: 50%;
    transform: translateX(-50%);
    width: 95%;
    z-index: 1000;

    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);

    border-radius: 25px;
    padding: 20px 40px;

    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
}

.logo-img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.8s ease-in-out;
}

.logo-img:hover {
    transform: rotate(360deg);
}

.navbar-brand {
    font-size: 24px;
    font-weight: bold;
    color: #333 !important;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.but {
    text-decoration: none;
    padding: 10px 25px;
    border-radius: 30px;
    font-weight: 500;
    color: #333;
    background: transparent;
    border: 2px solid #333;
    transition: all 0.3s ease;
}

.but:hover {
    transform: translateY(-5px);
}


</style>
</head>

<body>

<header>
<nav class="navbar-custom">

    <a href="home.php" class="navbar-brand">
        <img src="img/LOGO.jpeg" alt="Logo" class="logo-img">
        Page Turner
    </a>

    <a href="home.php" class="but">Home</a>
    <a href="search.php" class="but" >Search <i class="fa-brands fa-searchengin"></i></a>
    <a href="About Us.php" class="but">About Us</a>
    <a href="login.php" class="but">Login</a>

</nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

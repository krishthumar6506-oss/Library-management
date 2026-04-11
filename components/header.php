<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Responsive Header</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ================= BODY ================= */

body {
    margin: 0;
    padding: 0;
    padding-top: 110px;   /* Space for fixed navbar */
    font-family: Arial, sans-serif;
}


/* ================= NAVBAR ================= */

.navbar-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;

    display: flex;
    justify-content: space-between;
    align-items: center;

    background: rgba(223, 216, 216, 0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);

    padding: 15px 40px;
}


/* ================= LOGO ================= */

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


/* ================= MENU ITEMS ================= */

.menu-items {
    display: flex;
    gap: 25px;
    align-items: center;
}

.but {
    text-decoration: none;
    padding: 8px 20px;
    border-radius: 30px;
    font-weight: 500;
    color: #333;
    background: transparent;
    border: 2px solid #333;
    transition: all 0.3s ease;
}

.but:hover {
    transform: translateY(-4px);
    background: #333;
    color: hsl(0, 26%, 90%);
}


/* ================= MEDIUM DEVICES ================= */

@media (max-width: 991px) {

    body {
        padding-top: 95px;
    }

    .navbar-custom {
        padding: 12px 25px;
    }

    .logo-img {
        width: 60px;
        height: 60px;
    }

    .navbar-brand {
        font-size: 20px;
    }

    .but {
        padding: 6px 16px;
        font-size: 14px;
    }
}


/* ================= SMALL DEVICES ================= */

@media (max-width: 767px) {

    body {
        padding-top: 80px;
    }

    .navbar-custom {
        padding: 10px 15px;
        flex-wrap: wrap;
    }

    .logo-img {
        width: 50px;
        height: 50px;
    }

    .navbar-brand {
        font-size: 18px;
    }

    .menu-items {
        gap: 10px;
    }

    .but {
        padding: 5px 12px;
        font-size: 13px;
    }
}
</style>
</head>

<body>

<header>
<nav class="navbar-custom">

    <!-- Logo -->
    <a href="home.php" class="navbar-brand">
        <img src="img/LOGO.jpeg" alt="Logo" class="logo-img">
        Page Turner
    </a>

    <!-- Menu Items -->
    <div class="menu-items">
        <a href="home.php" class="but">
            <i class="fa-solid fa-house"></i> <span class="d-none d-md-inline">Home</span>
        </a>

        <a href="search.php" class="but">
            <i class="fa-solid fa-magnifying-glass"></i> <span class="d-none d-md-inline">Search</span>
        </a>

        <a href="About Us.php" class="but">
            <i class="fa-solid fa-circle-info"></i> <span class="d-none d-md-inline">About</span>
        </a>

        <a href="notice.php" class="but">
            <i class="fa-solid fa-book"></i> <span class="d-none d-md-inline">Notices</span>
        </a>

        <a href="login.php" class="but">
            <i class="fa-solid fa-user"></i> <span class="d-none d-md-inline">Login</span>
        </a>
    </div>

</nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
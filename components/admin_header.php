<style>
   .header {
       position: fixed;
       top: 0;
       left: 0;
       bottom: 0;
       width: 30rem;
       padding: 2rem;
       z-index: 1100;
       background: linear-gradient(to bottom, rgba(255, 255, 255, 0.266) 0%, rgba(245, 245, 245, 0.292) 100%);
       border-right: var(--border);
       box-shadow: 5px 0 15px rgba(0, 0, 0, 0.05);
       transition: all 0.3s ease;
       backdrop-filter: blur(5px);
   }
   
   .header:hover {
       box-shadow: 5px 0 25px rgba(0, 0, 0, 0.1);
   }
   
   .header .logo {
       display: block;
       text-align: center;
       font-size: 2.5rem;
       color: var(--black);
       padding: 1.5rem 0;
       margin-bottom: 2rem;
       position: relative;
       font-weight: 600;
       letter-spacing: 1px;
   }
   
   .header .logo::after {
       content: '';
       position: absolute;
       bottom: 0;
       left: 25%;
       width: 50%;
       height: 2px;
       background: linear-gradient(to right, transparent 0%, var(--main-color) 50%, transparent 100%);
   }
   
   .header .navbar {
       padding: 1rem 0;
       background-color: transparent;
   }
   
   .header .navbar a {
       display: flex;
       align-items: center;
       border-radius: 8px;
       margin: 1.5rem 0;
       font-size: 1.7rem;
       color: var(--black);
       padding: 1.5rem 2rem;
       background-color: rgba(255, 255, 255, 0.29);
       border: 1px solid rgba(0, 0, 0, 0.08);
       transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
       box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
   }
   
   .header .navbar a:hover {
       background-color: var(--main-color);
       color: white;
       transform: translateX(5px);
       box-shadow: 0 5px 15px rgba(156, 97, 48, 0.3);
       border-color: transparent;
   }
   
   .header .navbar a i {
       margin-right: 1.5rem;
       font-size: 1.8rem;
       transition: all 0.3s ease;
   }
   
   .header .navbar a:hover i {
       transform: scale(1.1);
       color: white;
   }
   
   .header .delete-btn {
       bottom: 3rem;
       left: 2rem;
       right: 2rem;
   }
   
   .header .delete-btn i {
       transition: transform 0.3s ease;
   }
   
   .header .delete-btn:hover i {
       transform: rotate(90deg);
   }
   
   .header .navbar a.active {
       background-color: var(--main-color);
       color: rgba(255, 255, 255, 0.315);
       font-weight: 500;
       border-left: 4px solid var(--black);
   }
   /* Scrollable Nav Area */
   
   .header .nav-scroll {
       height: calc(100vh - 15rem);
       overflow-y: auto;
       padding-right: 0.5rem;
   }
   /* Custom Scrollbar */
   
   .header .nav-scroll::-webkit-scrollbar {
       width: 5px;
   }
   
   .header .nav-scroll::-webkit-scrollbar-thumb {
       background-color: var(--main-color);
       border-radius: 10px;
   }
   
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<header class="header">

   <div id="close-btn"><i class="fas fa-times"></i></div>

   <a href="dashboard.php" class="logo">AdminPanel.</a>

   <nav class="navbar">
      <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="listings.php"><i class="fas fa-building"></i><span>Listings</span></a>
      <a href="users.php"><i class="fas fa-user"></i><span>Users</span></a>
      <a href="messages.php"><i class="fas fa-message"></i><span>Messages</span></a>
   </nav>
   <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn"><i class="fas fa-right-from-bracket"></i><span>Logout</span></a>

</header>

<div id="menu-btn" class="fas fa-bars"></div>
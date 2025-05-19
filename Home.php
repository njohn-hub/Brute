<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Welcome</title>
  <link rel="stylesheet" href="style.css" />
 
</head>
<body>

  <div class="header">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <div class="main-content">
    <h2>Dashboard</h2>
    <p>You have successfully logged in to the system. Explore the features or manage your account.</p>
    <!-- You can add more dashboard cards, links, or sections here -->
  </div>

</body>
</html>

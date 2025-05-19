<?php
include 'config.php';

session_start();
$message = [];

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $select = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];

            // Redirect to welcome/home page
            header('Location: home.php');
            exit();
        } else {
            $message[] = "Incorrect password!";
        }
    } else {
        $message[] = "User not found!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- css file link   -->
    <link rel="stylesheet" href="style.css">
    <!-- css file link   -->

</head>

<body>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message">' . $msg . '</div>';
    }
}
?>

    <div class="form-container">

        <form action="" method="post">
            <h3>Welcome back</h3>
            <input type="email" name="email" required placeholder="enter email" class="box">
            <input type="password" name="password" required placeholder="enter password" class="box">
            <input type="submit" name="submit" class="btn" value="Login">
            <p>Don't have an account? <a href="register.php">Register</a></p>

        </form>

    </div>


</body>

</html>
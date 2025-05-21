<?php
include 'config.php';
session_start();
$message = [];

// Initialize login attempt tracking
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Check for lockout
$waitTime = 300; // 5 minutes
$remaining = 0;
$locked = false;

if ($_SESSION['login_attempts'] >= 3) {
    $timeSinceLastAttempt = time() - $_SESSION['last_attempt_time'];
    if ($timeSinceLastAttempt < $waitTime) {
        $remaining = $waitTime - $timeSinceLastAttempt;
        $locked = true;
        $message[] = "Too many failed attempts. Try again later.";
    } else {
        // Reset lockout
        $_SESSION['login_attempts'] = 0;
        $locked = false;
    }
}

// Handle login
if (isset($_POST['submit']) && !$locked) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $select = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['login_attempts'] = 0;
            header('Location: home.php');
            exit();
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $message[] = "Incorrect password!";
        }
    } else {
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
        $message[] = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message">' . $msg . '</div>';
    }
    if ($locked) {
        echo '<div class="message">Please wait <span id="timer">' . $remaining . '</span> seconds…</div>';
    }
}
?>

<div class="form-container">
    <form action="" method="post">
        <h3>Welcome back</h3>
        <input type="email" name="email" required placeholder="enter email" class="box" <?php if ($locked) echo 'disabled'; ?>>
        <input type="password" name="password" required placeholder="enter password" class="box" <?php if ($locked) echo 'disabled'; ?>>
        <input type="submit" name="submit" class="btn" value="Login" <?php if ($locked) echo 'disabled'; ?>>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>

<script>
const timerElement = document.getElementById("timer");
if (timerElement) {
    let timeLeft = parseInt(timerElement.textContent, 10);
    const countdown = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(countdown);
            location.reload();
        } else {
            timerElement.textContent = timeLeft;
        }
    }, 1000);
}
</script>

</body>
</html>

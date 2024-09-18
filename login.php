<?php
$is_invalid = false;
$error_message = "";

$mysqli = require __DIR__ . "/database.php";
$loginLink = "login.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") { //Process the login form
        $sql = sprintf("SELECT * FROM user WHERE email = '%s'",
                       $mysqli->real_escape_string($_POST["email"])); // Prevent SQL injection attacks

        $result = $mysqli->query($sql);
        $user = $result->fetch_assoc(); // Return data from result method

        if ($user) {
            if (password_verify($_POST["pass"], $user["password_hash"])) { // Verify hash = plain text password
                session_start();
                session_regenerate_id();
                $_SESSION["user_id"] = $user["id"];
                header("Location: captcha.php");
                exit;
            }
        }

        $is_invalid = true; // Login invalid
        $error_message = "Invalid email or password. Please try again.";
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Login to order.">
  <meta name="keywords" content="Login, authentication, email, password, register, food menu">
  <title>Login Form</title>
  <link rel="stylesheet" href="loginstyle.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <div class="wrapper">
    <div class="formbox-login">
      <form method="post">
        <h1>Login</h1>
        <!-- Error Message -->
        <?php if ($is_invalid && !empty($error_message)): ?>
          <h2 style="text-align: center; padding-top: 20px; color: red;"><?= $error_message ?></h2>
        <?php endif; ?>

        <div class="input-box">
          <input type="email" name="email" id="email" placeholder="Email"
          value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required> <!--More user friendly to redisplay email -->
          <i class='bx bxs-envelope'></i>
        </div>

        <div class="input-box">
          <input type="password" name="pass" id="pass" placeholder="Password" required>
          <i class='bx bxs-lock-alt' ></i>
        </div>

        <div class="">
          <button type="submit" class="btn">Login</button>
        </div>

        <div class="register-link">
          <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>

        <div class="food">
          <p>Not interested? <a href="menu.php">Browse our food selection here!</a> </p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

<?php
$is_invalid = false;
$error_message = "";
$mysqli = require __DIR__ . "/database.php";

$result = $mysqli->query("SELECT * FROM captcha ORDER BY RAND() LIMIT 1");
$captcha = $result->fetch_assoc();
$captchaImagePath = $captcha['captcha_link'];
$storedCaptchaAnswer = $captcha["captcha_ans"];

if ($_SERVER["REQUEST_METHOD"] === "POST") { // Process the login form
// CAPTCHA verification
$userCaptchaInput = $_POST["captchaInput"];

if (strcasecmp($captcha["captcha_ans"], $userCaptchaInput)) {
  header("Location: menu.php");
} else {
  $is_invalid = true;
  $error_message = "Invalid CAPTCHA. The provided CAPTCHA ('$userCaptchaInput') does not match the expected CAPTCHA. Please try again.";
}
}
?>

<!DOCTYPE html>
<html lang="" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Complete the CAPTCHA to proceed with the login process.">
    <meta name="keywords" content="CAPTCHA, login, form validation">
    
    <title>Captcha</title>
    <link rel="stylesheet" href="captchastyle.css">
  </head>
  <body>
    <div class="wrapper">
      <div class="formbox-login">
        <form method="post">
          <h1>You must complete the Captcha</h1>
          <?php if ($is_invalid && !empty($error_message)): ?>
            <h2 style="text-align: center; padding-top: 20px; color: red;"><?= $error_message ?></h2>
          <?php endif; ?>

          <!-- CAPTCHA Section -->
          <img src="<?= $captchaImagePath ?>" alt="CAPTCHA" />

          <div class="input-box">
            <input type="text" name="captchaInput" placeholder="Enter CAPTCHA" required>
          </div>

          <div class="">
            <button type="submit" class="btn">Login</button>
          </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>

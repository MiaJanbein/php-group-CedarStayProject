<?php

require('projectFunctions.php');
session_start(); 
$conn=connect();

//incase of logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    header("Location: loginPage.php"); // Redirect to the login page
    exit();
}


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['useremail']) && isset($_POST['password'])) {
    $useremail = $conn->real_escape_string($_POST['useremail']); // Escape special characters
    $password = $conn->real_escape_string($_POST['password']); // Escape special characters

    // Query to check if the user exists
    $sql = "SELECT * FROM users WHERE email= '$useremail' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successful login
        $_SESSION['useremail'] = $useremail; // Store username in session
        header("Location: homePage.php"); // Redirect to home page
        exit();
    } else {
        // Invalid login
        $error_message = "Invalid username or password!";
    }
}

// Close connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form  </title>
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <div class="wrapper">
    <form action="loginPage.php" method="post">
      <h1>Login</h1>

      <!-- Display error message -->
      <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
      <?php endif; ?>

      <div class="input-box">
        <input type="text" placeholder="Email" name="useremail" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" placeholder="Password" name="password" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      <div class="remember-forgot">
        <label><input type="checkbox">Remember Me</label>
        <a href="#">Forgot Password </a>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register-link">
        <p>Don't have an account? <a href="registrationPage.php">Register</a></p>
      </div>
	  
    </form>
  </div>
</body>
</html>
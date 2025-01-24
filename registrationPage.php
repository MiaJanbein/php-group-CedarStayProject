<?php
session_start();
require('projectFunctions.php');

// Connect to the database
$stmt =Connect();

// Check connection
if ($stmt->connect_error) {
    die("Connection failed: " . $stmt->connect_error);
}

// Initialize error message
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $birthday = $_POST['birthday'];
    $password = $_POST['password'];
	 
    // Calculate the user's age
    $birth_date = new DateTime($birthday);
    $current_date = new DateTime();
    $age = $current_date->diff($birth_date)->y;
    
	
	if ($age < 18) {
        $error_message = "You must be at least 18 years old to register.";
    } elseif(!preg_match('/^[a-zA-Z]{3,8}$/', $name)) {
        $error_message = "Name must be 3–8 alphabetic characters (no numbers or special characters).";
    }else{
        // Securely hash the password
        $hashed_password = $password;

	 
        // Insert user into the database
        $insert_query = "INSERT INTO users (name, email, password) VALUES (?,?,?)";
        $stmt_insert = $stmt->prepare($insert_query);
        $stmt_insert->bind_param('sss', $name, $email, $hashed_password);

        if ($stmt_insert->execute()) {
            $_SESSION['useremail'] = $email;
			$_SESSION['userName']=$name;
             header("Location: loginPage.php"); // Redirect to login page
            exit();
        } else {
            $error_message = "Error: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }

}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <div class="wrapper">
    <form action="registrationPage.php" method="post">
      <h1>Register</h1>

      <!-- Display error message if registration fails -->
      <?php if (!empty($error_message)): ?>
        <div class="error-message" style="color: red; font-weight: bold;">
          <?= htmlspecialchars($error_message) ?>
        </div>
      <?php endif; ?>

      <div class="input-box">
        <input type="text" placeholder="Full Name" name="name" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="email" placeholder="Email" name="email" required>
        <i class='bx bxs-envelope'></i>
      </div>
      <div class="input-box">
        <input type="date" placeholder="Birthday" name="birthday" required>
        <i class='bx bxs-calendar'></i>
      </div>
      <div class="input-box">
        <input type="password" placeholder="Password" name="password" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      <button type="submit" class="btn">Register</button>
      <div class="register-link">
        <p>Already have an account? <a href="loginPage.php">Login</a></p>
      </div>
    </form>
  </div>
</body>
</html>
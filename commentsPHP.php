<?php
require('projectFunctions.php');

session_start();
$connect=connect();
$message = "";
// Check if the user is logged in
if (!isset($_SESSION['useremail'])) {
    header("Location: loginPage.php");
    exit();
}

// Get user email and name from the session
$email = $_SESSION['useremail'];


// Get the place ID from the GET request
if (!isset($_GET['p'])) {
    echo "Invalid request. Place ID is missing.";
    exit();
}
$placeId = intval($_GET['p']);

// Handle the POST request for submitting a comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitComment'])) {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

    if (empty($comment) || $rating <= 0 || $rating > 5) {
        $message = "Please provide a valid comment and rating.";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userId = $row['id'];

            $insertSql = "INSERT INTO reviews (userId, placeId, content, rating) VALUES (?, ?, ?, ?)";
            $insertStmt = $connect->prepare($insertSql);
            $insertStmt->bind_param("iisi", $userId, $placeId, $comment, $rating);

            if ($insertStmt->execute()) {
                header("Location: secondPage.php?p=$placeId");
				exit();
            } else {
                $message = "Error submitting your review: " . $connect->error;
            }

            $insertStmt->close();
        } else {
            session_destroy();
            header("Location: loginPage.php");
            exit();
        }

        $stmt->close();
    }
}

?>

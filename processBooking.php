<?php
require "projectFunctions.php";
session_start();

if (!isset($_SESSION['useremail'])) {
    // Redirect to login page if the user is not logged in
    header("Location: loginPage.php");
    exit();
}

$connect = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placeId = intval($_POST['placeId']);
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $userEmail = $_SESSION['useremail'];

    // Get the userId using the userEmail from the session
    $user_query = "SELECT id FROM users WHERE email = ?";
    $stmt_user = $connect->prepare($user_query);
    $stmt_user->bind_param("s", $userEmail);
    $stmt_user->execute();
    $stmt_user->bind_result($userId);
    $stmt_user->fetch();
    $stmt_user->close();

    if (!$userId) {
        echo "Error: User ID not found for the given email.";
        exit();
    }

    // Insert booking into the database
    $sql = "INSERT INTO bookings (userId, placeId, checkIn, checkOut) VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("iiss", $userId, $placeId, $checkIn, $checkOut);

    if ($stmt->execute()) {
        // Redirect to a success page or confirmation
        header("Location: secondPage.php?p=$placeId");
        exit();
    } else {
        // Handle errors
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$connect->close();
?>

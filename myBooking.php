<?php
session_start();
$server = "localhost";
$username = "root";
$pass = "your_new_password";
$db = "hotelmanagement";

$mysqli = new mysqli($server, $username, $pass, $db);
if ($mysqli->connect_error) {
    die('Error in connection: ' . $mysqli->connect_error);
}

// Check if the session user_id is set
if (!isset($_SESSION['user_id'])) {
    $bookings = [];
} else {
    $userId = $_SESSION['user_id'];
    $bookings = [];

    // Fetch bookings for the logged-in user with one image per booking
    $sql = "
        SELECT 
            b.id AS booking_id, 
            p.title, 
            p.address, 
            COALESCE(MIN(ph.url), 'placeholder.jpg') AS image_url, 
            b.checkIn, 
            b.checkOut, 
            b.totalPrice
        FROM bookings b
        JOIN places p ON b.placeId = p.id
        LEFT JOIN photos ph ON p.id = ph.placeId
        WHERE b.userId = ?
        GROUP BY b.id
    ";

    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch rows if query is successful
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        $stmt->close();
    } else {
        die('Error preparing SQL: ' . $mysqli->error);
    }

    if (empty($bookings)) {
        $message = "You haven't made any bookings yet.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
    body {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f0f0f0;
        font-family: Arial, sans-serif;
    }
    .container {
        width: 90%;
        max-width: 800px;
        background-color:  rgba(0, 77, 64, 0.7);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .booking-row {
        display: flex;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 8px;
        background-color: rgba(193, 225, 193, 0.5);
        border: 1px solid #ddd;
    }
    .booking-row img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }
    .booking-details {
        margin-left: 20px;
    }
    .booking-details h3 {
        margin: 0;
    }
    .booking-details p {
        margin: 5px 0;
    }
    .no-bookings-message {
        text-align: center;
        font-size: 1.5em;
        color: white;
       
    }
    </style>
</head>
<body>

    <div class="container">
        <?php if (isset($message)): ?>
            <p class="no-bookings-message"><?= $message ?></p>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-row">
                    <img src="<?= htmlspecialchars($booking['image_url']) ?>" alt="Place Image">
                    <div class="booking-details">
                        <h3><?= htmlspecialchars($booking['title']) ?></h3>
                        <p>Address: <?= htmlspecialchars($booking['address']) ?></p>
                        <p>Check-in: <?= htmlspecialchars($booking['checkIn']) ?></p>
                        <p>Check-out: <?= htmlspecialchars($booking['checkOut']) ?></p>
                        <p>Total Price: $<?= htmlspecialchars($booking['totalPrice']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>

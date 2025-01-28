<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, just exit the page and show nothing
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "your_new_password";
$dbname = "hotelmanagement";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$userId = $_SESSION['user_id']; // Use the userId from session

// Fetch user accommodations
$sql = "
    SELECT 
        a.title, 
        a.description, 
        p.url AS photo_url
    FROM 
        places a
    LEFT JOIN 
    photos p 
    ON 
        a.id = p.placeId
    WHERE 
        a.userId = ?
    GROUP BY 
        a.id
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Error preparing SQL query: ' . $conn->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Accommodations</title>
    <style>
        /* Existing styles unchanged */
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
            background-color: rgba(0, 77, 64, 0.7) ;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            
        }

        .accommodation-item {
            display: flex;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            background-color: rgba(193, 225, 193, 0.5) ;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .accommodation-item img {
            width: 150px;
            height: 150px;
            object-fit: cover; /* Ensures the image fills the box, cropping if necessary */
            border-radius: 8px;
            background-color:rgba(193, 225, 193, 0.5)  ; /* Add a background color for empty spaces if the image fails to load */
        }

        .accommodation-info {
            margin-left: 20px;
        }

        .accommodation-info h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .accommodation-info p {
            margin: 5px 0;
            color: #666;
        }

        .center-text {
            text-align: center;
            font-size: 1.5em;
            color: white;
            margin-top: 20px;
        }

        .add-new-container button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 1em;
            color: white;
            background-color: #004d40;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        .add-new-container button:hover {
            background-color: #004d40;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="add-new-container">
            <button onclick="window.location.href='addPlace.php'">+ Add New Place</button>
        </div>

        <div class="accommodation-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Prepend the path for image display
                    $photoUrl =  htmlspecialchars($row['photo_url'] ?? 'fallback-image.jpg');
                    echo '<div class="accommodation-item">';
                    echo '<img src="' . $photoUrl . '" alt="' . htmlspecialchars($row['title']) . '">';
                    echo '<div class="accommodation-info">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '</div>';
                    echo '</div>';
                  
                }
            } else {
                echo '<p class="center-text">No accommodations have been added yet.</p>';
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>

</body>
</html>



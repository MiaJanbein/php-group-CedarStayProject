
<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "your_new_password";
$dbname = "hotelmanagement";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a database connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $address = $_POST['address'];
    $checkIn = $_POST['check-in-time'];
    $checkOut = $_POST['check-out-time'];
    $maxGuests = $_POST['max-guests'];
    $description = $_POST['description'];
    $extraInfo = $_POST['extra-info'];
    $rating = 0; // Default rating, you can handle it dynamically later
    $pricePerNight = $_POST['price-per-night'];

    // Validate form data
    if (empty($title) || empty($address) || empty($description) || empty($maxGuests) || empty($pricePerNight)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Insert the place details into the places table
        $query = "INSERT INTO places (title, address, maxGuests, description, extraInfo, rating, pricePerNight) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)"; 
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('ssissid', $title, $address, $maxGuests, $description, $extraInfo, $rating, $pricePerNight);

            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                // Get the ID of the last inserted place
                $placeId = $stmt->insert_id;

                // Handle photo uploads
                if (!empty($_FILES['photos']['name'][0])) {
                    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                        // Read the file content as binary
                        $imageData = file_get_contents($tmp_name);

                        // Insert the binary content into the tempPhotos database
                        $photoQuery = "INSERT INTO tempPhotos (placeId, url) VALUES (?, ?)";
                        if ($photoStmt = $conn->prepare($photoQuery)) {
                            $photoStmt->bind_param('ib', $placeId, $imageData);
                            $photoStmt->send_long_data(1, $imageData);
                            $photoStmt->execute();
                            $photoStmt->close();
                        } else {
                            $error_message = "Error inserting photo: " . $conn->error;
                        }
                    }
                }

                // Handle photo URLs
                if (!empty($_POST['photo-url'])) {
                    $photoUrl = $_POST['photo-url'];
                    $photoQuery = "INSERT INTO tempPhotos (placeId, url) VALUES (?, ?)";
                    if ($photoStmt = $conn->prepare($photoQuery)) {
                        $photoStmt->bind_param('is', $placeId, $photoUrl);
                        $photoStmt->execute();
                        $photoStmt->close();
                    } else {
                        $error_message = "Error inserting photo URL: " . $conn->error;
                    }
                }

                // Success message
                $success_message = "Accommodation added successfully with photos!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Accommodation - Airbnb</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .action-bar {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #004d40;
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .action-bar .back-button {
            margin-right: 10px;
        }

        .action-bar .back-button a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #00796b;
            transition: background-color 0.3s;
        }

        .action-bar .back-button a:hover {
            background-color: #005a4c;
        }

        .action-bar .logo {
            font-size: 1.5em;
            font-weight: bold;
            flex-grow: 1; /* Ensures logo stays centered when other elements shift */
            text-align: center;
        }

        .action-bar .login-container {
            margin-left: 20px;
        }

        .action-bar .login-button {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .action-bar .login-button:hover {
            background-color: #005a4c;
        }

        main {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bolder;
            font-size: 14px;
            color: black;
            margin-bottom: 5px;
        }

        p {
            color: gray;
            font-size: 14px;
            margin-top: 5px;
        }

        input, select, textarea, button.submit-btn {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .input-container {
            flex: 1;
            min-width: 180px;
            margin-right: 20px;
        }

        .input-container:last-child {
            margin-right: 0;
        }

        .upload-icon-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .uploaded-images-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        .upload-icon-box {
            width: 120px;
            height: 120px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .upload-icon-box input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .uploaded-image-box {
            width: 120px;
            height: 120px;
            background-size: cover;
            background-position: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            position: relative;
        }

        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 255, 255, 0.7);
            border: none;
            padding: 5px;
            border-radius: 50%;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: rgba(255, 255, 255, 1);
        }

        input[type='submit'] {
            width: auto;
            height: 45px;
            padding: 10px 15px;
            background-color: #005a4c;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button.submit-btn {
            margin-top: 20px;
            background-color: #005a4c;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button.submit-btn:hover {
            background-color: #005a4c;
        }

        .url-input-container {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .url-input-container input[type="url"] {
            flex: 3;
            padding: 10px;
            font-size: 16px;
        }

        .url-input-container input[type="button"] {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            background-color: #005a4c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .url-input-container input[type="button"]:hover {
            background-color: #005a4c;
        }
    </style>
</head>
<body>

<div class="action-bar">
    <div class="back-button">
        <a href="main.php">&larr; Back</a>
    </div>
    <div class="logo">CedarStay</div>
    <div class="login-container">
        <?php
        if (isset($_SESSION['useremail'])) {
            $username = $_SESSION['useremail'];
            $button_text = "Logout";
            $button_action = "loginPage.php?logout=true"; // Add query parameter for logout
        } else {
            $button_text = "Login";
            $button_action = "loginPage.php"; // Redirect to login page
        }
        ?>
        <a href="<?= $button_action ?>" class="login-button"><?= $button_text ?></a>
    </div>
</div>

<main>
    <form method="POST" enctype="multipart/form-data">
        <!-- Success/Error Messages -->
        <?php if (!empty($success_message)) { echo "<p>$success_message</p>"; } ?>
        <?php if (!empty($error_message)) { echo "<p>$error_message</p>"; } ?>

        <label for="title">Title</label>
        <p>Title for your place, should be short and catchy as in advertisement</p>
        <input type="text" id="title" name="title" placeholder="E.g., Cozy Apartment in the City Center" required>

        <label for="address">Address</label>
        <p>Address to this place</p>
        <input type="text" id="address" name="address" placeholder="Enter the address" required>

        <label for="photos">Photos</label>
        <p>More = better</p>

        <!-- URL Input Section -->
        <div class="url-input-container">
            <input type="url" id="photo-url" name="photo-url" placeholder="Add using a link...jpg">
            <input type="button" value="Add Photo" onclick="addUrlImage(); return false;">
        </div>

        <!-- Box to display upload icon -->
        <div class="upload-icon-container">
            <div class="upload-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="size-6">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                </svg>
                <input type="file" name="photos[]" multiple onchange="handleImageUpload(event, document.querySelector('.uploaded-images-container'))">
            </div>
        </div>

        <!-- Container to show uploaded images horizontally -->
        <div class="uploaded-images-container"></div>

        <label for="description">Description</label>
        <p>Description of the place</p>
        <textarea id="description" name="description" rows="5" placeholder="Describe your place in detail..." required></textarea>

        <label for="extra-info">Extra Info</label>
        <p>House rules, etc</p>
        <textarea id="extra-info" name="extra-info" rows="4" placeholder="Add any additional information..."></textarea>

        <label for="check-times">Check-in & out times</label>
        <p>Add check-in and check-out times. Remember to have some time window for cleaning the room between guests.</p>
        <div class="row">
            <div class="input-container">
                <label for="check-in-time">Check-in Time</label>
                <input type="time" id="check-in-time" name="check-in-time" maxlength="5">
            </div>

            <div class="input-container">
                <label for="check-out-time">Check-out Time</label>
                <input type="time" id="check-out-time" name="check-out-time" maxlength="5">
            </div>
        </div>

        <label for="max-guests">Max Guests</label>
        <p>The number of people this place can host</p>
        <input type="number" id="max-guests" name="max-guests" min="1" placeholder="Max Guests" required>

        <label for="price-per-night">Price per Night</label>
        <p>Set the price for one night in your accommodation</p>
        <input type="number" id="price-per-night" name="price-per-night" min="0" placeholder="Price per night" required>

        <button type="submit" class="submit-btn">Submit</button>
    </form>
</main>
<script>
        // Ensure at least 3 photos are uploaded
        document.querySelector('form').addEventListener('submit', function(event) {
            const photoCount = document.querySelectorAll('.uploaded-images-container .uploaded-image-box').length;
            if (photoCount < 3) {
                alert("You must upload at least 3 photos.");
                event.preventDefault();
            }
        });

    // Handle uploaded image previews
    // Handle uploaded image previews
function handleImageUpload(event, container) {
    const files = event.target.files;
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.className = 'uploaded-image-box';
            preview.style.backgroundImage = `url(${e.target.result})`;

            const deleteButton = document.createElement('button');
            deleteButton.className = 'delete-button';
            deleteButton.innerHTML = 'X';
            deleteButton.onclick = function() {
                container.removeChild(preview);
            };

            preview.appendChild(deleteButton);
            container.appendChild(preview);
        };
        reader.readAsDataURL(file);
    });
}

// Add photo by URL
function addUrlImage() {
    const urlInput = document.getElementById('photo-url');
    const url = urlInput.value;
    if (url) {
        const container = document.querySelector('.uploaded-images-container');
        const preview = document.createElement('div');
        preview.className = 'uploaded-image-box';
        preview.style.backgroundImage = `url(${url})`;

        const deleteButton = document.createElement('button');
        deleteButton.className = 'delete-button';
        deleteButton.innerHTML = 'X';
        deleteButton.onclick = function() {
            container.removeChild(preview);
        };

        preview.appendChild(deleteButton);
        container.appendChild(preview);
        urlInput.value = '';
    }
}

</script>
</body>

</html>

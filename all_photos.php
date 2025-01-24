<?php
require "projectFunctions.php"; // Include database connection or helper functions

$connect = connect(); // Establish the database connection

// Get the place ID from the URL
$placeId = intval($_GET['p']);

// SQL Query to select all photos for the given placeId
$sql = "SELECT url FROM photos WHERE placeId = $placeId";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Start rendering the layout
    echo '<div style="background-color: #f0f0f0; padding: 20px; font-family: Arial, sans-serif;">';
    echo '<div style="max-width: 900px; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 10px;">';

    // Title
    echo '<h1 style="font-size: 2em; margin-bottom: 20px;">All Photos</h1>';

    // Go Back Button
    echo '<div style="text-align: center; margin-bottom: 20px;">';
    echo '<button onclick="window.history.back()" style="background: rgba(0, 0, 0, 0.5); color: white; border: none; padding: 10px 20px; cursor: pointer; font-size: 1em; border-radius: 5px;">Go Back</button>';
    echo '</div>';

    // Photo Display Container
    echo '<div style="position: relative; overflow: hidden; width: 100%;">';
    echo '<img id="currentImage" src="' . htmlspecialchars($result->fetch_assoc()['url']) . '" alt="Photo" style="width: 500px; height: 350px; object-fit: cover; border-radius: 5px; display: block; margin: 0 auto;">';
    echo '</div>'; // Close photo display container

    // Navigation Buttons
    echo '<div style="text-align: center; margin-top: 20px;">';
    echo '<button id="prevBtn" style="background: rgba(0, 0, 0, 0.5); color: white; border: none; padding: 10px; cursor: pointer; font-size: 1.5em;">&#8249;</button>';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    echo '<button id="nextBtn" style="background: rgba(0, 0, 0, 0.5); color: white; border: none; padding: 10px; cursor: pointer; font-size: 1.5em;">&#8250;</button>';
    echo '</div>';

    echo '</div>'; // Close content container
    echo '</div>'; // Close background container
} else {
    // No photos found for this place
    echo '<div style="text-align: center; font-size: 1.2em; color: #555;">';
    echo 'No photos available for this place.';
    echo '</div>';
}
?>

<script>
// JavaScript to handle the navigation between images
let currentIndex = 0;
let photos = <?php 
    $result->data_seek(0); // Reset pointer to the beginning of the result set
    $photoUrls = [];
    while ($row = $result->fetch_assoc()) {
        $photoUrls[] = htmlspecialchars($row['url']);
    }
    echo json_encode($photoUrls);
?>;

document.getElementById("nextBtn").addEventListener("click", function() {
    if (currentIndex < photos.length - 1) {
        currentIndex++;
        document.getElementById("currentImage").src = photos[currentIndex];
    }
});

document.getElementById("prevBtn").addEventListener("click", function() {
    if (currentIndex > 0) {
        currentIndex--;
        document.getElementById("currentImage").src = photos[currentIndex];
    }
});
</script>

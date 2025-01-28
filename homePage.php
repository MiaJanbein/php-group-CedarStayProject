<?php
require "projectFunctions.php";
$connect = connect();


session_start();

// Handle the search query
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = '';
if (!empty($search_query)) {
    $search_sql = "WHERE LOWER(places.title) LIKE LOWER('%$search_query%')";
}
// Append the search condition if it exists
if (!empty($search_sql)) {
    $sql .= " $search_sql";
}
// Handle the price filter (from modal form)
$price_filter = isset($_POST['price']) ? $_POST['price'] : null;
$guests_filter = isset($_POST['guests']) ? $_POST['guests'] : null;

$filter_sql = "";

// Apply the price filter if set
if ($price_filter) {
    $filter_sql .= " AND places.pricePerNight <= " . $price_filter;
}

// Apply the guests filter if set
if ($guests_filter) {
    // Assuming that there's a guests column in the 'places' table to filter by guest capacity
    $filter_sql .= " AND places.maxGuests >= " . $guests_filter;
}

// Fetch filtered data with the additional filters
$sql = "SELECT 
            places.id AS place_id,
            places.title,
            places.address,
            places.pricePerNight,
            places.maxGuests,
            photos.url AS photo_url
        FROM 
            places
        LEFT JOIN photos ON places.id = photos.placeId";

// Append the search condition if it exists
if (!empty($search_sql)) {
    $sql .= " $search_sql";
}

// Add the filter condition for price and guests if it exists
if (!empty($filter_sql)) {
    $sql .= " WHERE 1=1 $filter_sql";
}

// Add GROUP BY clause
$sql .= " GROUP BY places.id";

// Append the order by clause if the filter is set
if (!empty($order_by)) {
    $sql .= " $order_by";
}

// Execute the query
$result = $connect->query($sql);

if (isset($_SESSION['useremail'])) { 
    $username = $_SESSION['useremail'];
    $button_text = "Logout";
    $button_action = "loginPage.php?logout=true"; // Add query parameter for logout
} else {
    $button_text = "Login";
    $button_action = "loginPage.php"; // Redirect to login page
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CedarStay</title>
    <style>
        /* Existing styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        /* Add your styles for action-bar, modal-overlay, and other components here */
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
        .action-bar .logo {
            font-size: 1.5em;
            font-weight: bold;
        }
        .action-bar .search-filter-container {
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .action-bar .search-filter-container form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .action-bar .search-filter-container input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .action-bar .filter-button ,.search-button{
            background-color: #00796b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
		.menu-button-container button {
            display: flex; /* Ensures proper alignment for SVG inside button */
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .menu-button-container svg {
            width: 24px; /* Set explicit width */
            height: 24px; /* Set explicit height */
            fill: none;
            stroke: white; /* Ensure icon is visible on dark background */
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
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }
        .modal-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
        }
        .modal-container h1 {
            margin-bottom: 20px;
            text-align: center;
        }
        .modal-container .slider-container {
            margin: 20px 0;
        }
        .modal-container .slider-container p {
            margin-bottom: 5px;
        }
        .modal-container .btn-close {
            background: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-container .btn-apply {
            background: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .places-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px;
        }
        .place-card {
            width: 30%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .place-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .place-card div {
            text-align: left;
            margin-top: 10px;
            width: 100%;
        }
        .place-card h2 {
            margin: 5px 0;
            font-size: 1.2em;
        }
        .place-card p {
            margin: 5px 0;
        }
        .place-card p.bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="action-bar">
        <div class="logo">CedarStay</div>
        <div class="search-filter-container">
			<form action="" method="GET">
                <input type="text" name="search" placeholder="Search by place name" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="search-button">Search </button>
            </form>
            <button class="filter-button">Filter</button>
        </div>
		<div class="menu-button-container">
			<button 
				onclick="window.location.href='main.php'" 
				style="background: none; border: none; padding: 0; cursor: pointer;">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="size-6">
					<path strokeLinecap="round" strokeLinejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
				</svg>
			</button>
		</div>
        <div class="login-container">
            <a href="<?php echo $button_action; ?>" class="login-button"><?php  echo $button_text; ?></a>
        </div>
    </div>

   <div class="modal-overlay">
		<div class="modal-container">
			<h1>Filter by Price and Guests</h1>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<div class="slider-container">
					<p>Max Price ($):</p>
					<input type="range" name="price" id="price" min="100" max="500" value="<?php echo isset($_POST['price']) ? $_POST['price'] : 100; ?>">
					<span id="price-value"><?php echo isset($_POST['price']) ? $_POST['price'] : 100; ?></span>
				</div>
				<div class="slider-container">
					<p>Min Guests:</p>
					<input type="range" name="guests" id="guests" min="1" max="7" value="<?php echo isset($_POST['guests']) ? $_POST['guests'] : 1; ?>">
					<span id="guests-value"><?php echo isset($_POST['guests']) ? $_POST['guests'] : 1; ?></span>
				</div>
				<button type="submit" class="btn-apply">Apply Filters</button>
				<button type="button" class="btn-close">Close</button>
			</form>
		</div>
	</div>


    <div class="places-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="place-card">
                    <a href="secondPage.php?p=<?php echo $row['place_id']; ?>" style="width: 100%; text-decoration: none;">
                        <img src="<?php echo $row['photo_url']; ?>" alt="<?php echo $row['title']; ?>">
                    </a>
                    <div>
                        <h2><?php echo $row['title']; ?></h2>
                        <p><?php echo $row['address']; ?></p>
                        <p class="bold">Price per night: $<?php echo $row['pricePerNight']; ?></p>
                    </div>
                </div>

                <?php 
                // Set session variables for the place data
                $_SESSION['place_id'] = $row['place_id'];
                $_SESSION['address'] = $row['address'];
                $_SESSION['title'] = $row['title'];
                $_SESSION['price'] = $row['pricePerNight'];
                $_SESSION['maxGuests'] = $row['maxGuests'];
                ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No places found.</p>
        <?php endif; ?>
    </div>

    <script>
    // JavaScript for the modal and slider functionality
    const filterButton = document.querySelector('.filter-button');
    const modalOverlay = document.querySelector('.modal-overlay');
    const closeButton = document.querySelector('.btn-close');

    // Open modal
    filterButton.addEventListener('click', () => {
        modalOverlay.style.display = 'flex';
    });

    // Close modal
    closeButton.addEventListener('click', () => {
        modalOverlay.style.display = 'none';
    });

    // Update sliders dynamically
    const priceSlider = document.getElementById('price');
    const priceValue = document.getElementById('price-value');
    const guestsSlider = document.getElementById('guests');
    const guestsValue = document.getElementById('guests-value');

    // Update price value dynamically
    priceSlider.addEventListener('input', () => {
        priceValue.textContent = priceSlider.value;
    });

    // Update guests value dynamically
    guestsSlider.addEventListener('input', () => {
        guestsValue.textContent = guestsSlider.value;
    });
    </script>
</body>
</html>
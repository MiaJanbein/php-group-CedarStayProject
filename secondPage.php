<?php session_start(); ?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-pUA-Compatible" content="ie=edge" />
    <title>CedarStay</title>
    <style>
		
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css' rel='stylesheet'>
	
	
	<link rel="stylesheet" href="rating.css" />

	<!-- Flatpickr JS -->
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="comment_section.js"></script>
<style>	
	
body {
        background-image: linear-gradient(to right, #7B1FA2, #E91E63);
    }   
	/* Container for the booking form and extra info */
	.booking-container {
		display: flex;
		justify-content: space-between;  /* Space between the form and extra info */
		align-items: flex-start;
		padding: 0 20px;
		margin-top: 20px; /* Adjust if needed */
	}

	/* Extra Info Style */
	.extra-info {
		flex: 1; /* Makes the extra info take the remaining space */
		padding: 65px;
		
		border-radius: 5px;
		max-width: 300px; /* Set a maximum width to avoid too wide of a layout */
		color: #555;
		margin-right: 20px; /* Space between the extra info and booking form */
	}

	/* Booking Form Container */
	.section .section-center {
		position: relative;
		top: 35%;
		left: 0;
		right: 0;
		transform: translateY(-35%);
		display: flex;
		justify-content: center; /* Center the form */
		align-items: center;
	}

	/* Booking Form Style */
	.booking-form {
		position: relative;
		max-width: 300px;
		width: 100%;
		padding: 40px;
		background-image: url('cedarImage3.jpg');
		background-size: cover;
		border-radius: 5px;
		z-index: 20;
		margin: 0;
	}

	/* Optional: Add padding or margin to prevent elements from being too close together */
	.booking-form,
	.extra-info {
		margin: 0 20px;
	}

	/* Media Query for smaller screens */
	@media (max-width: 768px) {
		.booking-container {
			flex-direction: column;  /* Stack them vertically on smaller screens */
			justify-content: center;
			align-items: center;
		}

		.extra-info {
			margin-right: 0; /* Remove right margin on smaller screens */
			margin-bottom: 5px; /* Add margin at the bottom for spacing */
		}

		.booking-form {
			max-width: 100%;  /* Allow the booking form to take full width on smaller screens */
		}
	}

    .section {
        position: relative;
        height: 100vh;
    }

    
	 #booking {
		font-family: 'Raleway', sans-serif;
	}

	

	.booking-form::before {
		content: '';
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
		top: 0;
		background: rgba(0, 0, 0, 0.7);
		z-index: -1;
	}

	.booking-form .form-header {
		text-align: center;
		position: relative;
		margin-bottom: 55px;
	}

	.booking-form .form-header h1 {
		font-weight: 700;
		text-transform: capitalize;
		font-size: 42px;
		margin: 0px;
		
		color: #fff;
	}

	.booking-form .form-group {
		position: relative;
		margin-bottom: 50px;
	}

	.booking-form .form-control {
		background-color: rgba(255, 255, 255, 0.2);
		height: 30px;
		padding: 0px 25px;
		border: none;
		border-radius: 4px;
		color: #fff;
		-webkit-box-shadow: 0px 0px 0px 2px transparent;
		box-shadow: 0px 0px 0px 2px transparent;
		-webkit-transition: 0.2s;
		transition: 0.2s;
		text-align: center;
		font-size: 16px;
		font-family: 'Arial', sans-serif;
	}

	.booking-form .form-control::placeholder {
		color: rgba(255, 255, 255, 0.5);
	}

	.booking-form .form-control:focus {
		-webkit-box-shadow: 0px 0px 0px 2px #ff8846;
		box-shadow: 0px 0px 0px 2px #ff8846;
	}

	.booking-form .form-label {
		position: absolute;
		top: -25px; /* Adjusted to position above the input field */
		left: 7px;
		opacity: 5;
		color: #fff; /* Set color to white */
		font-size: 15px;
		font-weight: bold;
		text-transform: uppercase;
		letter-spacing: 1px;
		height: 10px;
		line-height: 15px;
		-webkit-transition: 0.2s all;
		transition: 0.2s all;
	}

	.booking-form .form-group.input-not-empty .form-control {
		padding-top: 16px;
	}

	.booking-form .form-group.input-not-empty .form-label {
		opacity: 1;
		top: 10px; /* Adjusted to make it stick closer to the input */
	}

	.booking-form .btn-guest {
		height: 40px;
		width: 40px;
		color: #fff;
		background-color: #e35e0a;
		border: none;
		border-radius: 50%;
		font-size: 20px;
		font-weight: bold;
		cursor: pointer;
		margin: 0 10px;
		text-align: center;
		line-height: 40px;
	}

	.booking-form .btn-guest:hover {
		opacity: 0.9;
	}

	.booking-form .submit-btn {
		color: #fff;
		background-color: #005a4c;
		font-weight: 700;
		height: 60px;
		padding: 10px 30px;
		width: 100%;
		border-radius: 5px;
		border: none;
		text-transform: uppercase;
		font-size: 16px;
		letter-spacing: 1.3px;
		-webkit-transition: 0.2s all;
		transition: 0.2s all;
	}

	.booking-form .submit-btn:hover,
	.booking-form .submit-btn:focus {
		opacity: 0.9;
	}

	.form-label {
		color: #ff8846;
		font-size: 18px;
		font-weight: bold;
		margin-bottom: 10px;
		display: block;
		text-align: center;
	}

	.booking-form p {
		color: #ff8846;
		font-size: 15px;
		font-weight: bold;
		margin-bottom: 10px;
		text-align: center;
		text-transform: uppercase;
	}

	/* Modern styling for date inputs */
	.booking-form input[type="date"] {
		padding: 10px;
		font-size: 16px;
		border-radius: 5px;
		border: 2px solid #ddd;
		color: #333;
	}

	.booking-form input[type="date"]:focus {
		border-color: #ff8846;
	}

</style>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="javascript.js"></script>
</head></html>
<?php
require "projectFunctions.php";

$connect = connect();

$placeId = intval($_GET['p']); // Get the place ID from the URL
$sql = "SELECT 
            places.title, 
            places.address, 
            places.description, 
            places.extraInfo, 
            photos.url AS photo_url
        FROM 
            places
        LEFT JOIN 
            photos ON places.id = photos.placeId
        WHERE 
            places.id = $placeId";

	$result = $connect->query($sql);



	if ($result->num_rows > 0) {
		$place = [];
		while ($row = $result->fetch_assoc()) {
			$place['title'] = $row['title'];
			$place['address'] = $row['address'];
			$place['description'] = $row['description'];
			$place['extraInfo'] = $row['extraInfo'];
			$place['photos'][] = $row['photo_url'];
		}

	require 'action_bar.php';
    
    // Start rendering the layout
    echo '<div style="background-color: #f0f0f0; padding: 0px; font-family: Arial, sans-serif;">';
    echo '<div style="max-width: 60%; margin: 10px auto; background-color: #fff; padding: 0 20%; border-radius: 10px;">';

    // Title
    echo '<h1 style="font-size: 2em; margin-bottom: 10px;">' . htmlspecialchars($place['title']) . '</h1>';

    // Photo Grid
    echo '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px; margin-bottom: 20px;">';

    // First column (1 picture)
    if (!empty($place['photos'][0])) {
        echo '<div style="grid-row: span 2;">';
        echo '<img src="' . htmlspecialchars($place['photos'][0]) . '" alt="Photo 1" style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;">';
        echo '</div>';
    }

    // Second column (2 pictures)
    for ($i = 1; $i <= 2; $i++) {
        if (!empty($place['photos'][$i])) {
            echo '<div>';
            echo '<img src="' . htmlspecialchars($place['photos'][$i]) . '" alt="Photo ' . ($i + 1) . '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;">';
            echo '</div>';
        }
    }

    echo '</div>';

    // Show All Photos Button
    echo '<button style="padding: 10px 20px; background-color:#004d40; color: #fff; border: none; border-radius: 5px; cursor: pointer;" onclick="window.location.href=\'all_photos.php?p=' . $placeId . '\'">Show All Photos</button>';


    // Description
    echo '<div style="margin-top: 30px;">';
    echo '<h2 style="font-size: 1.5em; margin-bottom: 10px;">Description</h2>';
    echo '<p style="font-size: 1.1em; line-height: 1.6; color: #555;">' . nl2br(htmlspecialchars($place['description'])) . '</p>';
    echo '</div>';

    
	$placeId = intval($_GET['p']); // Get the place ID from the URL

	// Fetch booked dates for the specific placeId
	$bookedDates = [];
	$sql = "SELECT checkIn, checkOut FROM bookings WHERE placeId = ?";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param("i", $placeId);
	$stmt->execute();
	$result = $stmt->get_result();

	while ($row = $result->fetch_assoc()) {
		$checkIn = $row['checkIn'];
		$checkOut = $row['checkOut'];
		$bookedDates[] = ['checkIn' => $checkIn, 'checkOut' => $checkOut];
	}
	$stmt->close();


	// Format booked dates for use in JavaScript
	$bookedDatesJson = json_encode($bookedDates);

	// Calendar HTML with disabled dates
	echo '
	<div class="booking-container">
    <!-- Extra Info -->
    <div class="extra-info">
        <h2 style="color: black; font-size: 1.5em; margin-bottom: 10px;">Extra Info</h2>
        <p style="font-size: 1.1em; line-height: 1.6; color: #555;">'.nl2br(htmlspecialchars($place["extraInfo"])).'</p>
    </div>
	<div id="booking" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row">
					<div class="booking-form">
						<div class="form-header">
							<h1>Make your reservation</h1>
						</div>
						<form action="processBooking.php" method="POST">
							<input type="hidden" name="placeId" value="' . htmlspecialchars($placeId) . '">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="date" id="checkIn" name="checkIn" required>
										<span class="form-label">Check In</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="date" id="checkOut" name="checkOut" required>
										<span class="form-label">Check Out</span>
									</div>
								</div>
							</div>
							<div class="form-btn">
								<button class="submit-btn" type="submit" name="bookNow">Book Now</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div></div>';
		
	
	require('comment_section.html');
	require('diplayComments.html');
	
	echo '<div style="color: #333; font-size: 2em; margin-top: 70px; font-weight: bold;">Plan your route with the map</div>';
    // Embed Google Maps (iframe) - Moved below "Extra Info"
    $mapAddress = urlencode($place['address']);
    echo '<div style="margin-top: 30px;">';
    echo '<iframe src="https://www.google.com/maps/embed/v1/place?q=' . $mapAddress . '&key=AIzaSyD5LRPcjd_HwzyW5OHnpug7Yuc8U27mrqs" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
    echo '</div>';

    // Address (Google Maps link)
    echo '<p style="font-size: 1.1em; color: #555; margin-top: 10px;">';
    echo '<a href="https://www.google.com/maps/search/?api=1&query=' . urlencode($place['address']) . '" target="_blank" style="color: #007BFF; text-decoration: none;">' . htmlspecialchars($place['address']) . '</a>';
    echo '</p>';

    echo '</div>';
    echo '</div>';
		
	
	
} else {
    echo '<p style="text-align: center; font-size: 1.2em; color: #555;">Place not found.</p>';
}
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
   

    // Parse PHP array into JavaScript object
    const bookedDates = <?php echo $bookedDatesJson; ?>;
    // Function to check if the selected booking period overlaps with any booked period
    function isDateRangeBooked(checkInDate, checkOutDate) {
        const selectedCheckIn = new Date(checkInDate);
        const selectedCheckOut = new Date(checkOutDate);
        
        for (const booking of bookedDates) {
            const bookingStart = new Date(booking.checkIn);
            const bookingEnd = new Date(booking.checkOut);
            
            // Check if the selected range overlaps with any existing booking range
            if ((selectedCheckIn >= bookingStart && selectedCheckIn <= bookingEnd) || 
                (selectedCheckOut >= bookingStart && selectedCheckOut <= bookingEnd) || 
                (selectedCheckIn <= bookingStart && selectedCheckOut >= bookingEnd)) {
                return true;
            }
        }
        return false;
    }

    // Attach validation to Check-In and Check-Out inputs
    function disableInvalidDates(inputElement) {
        inputElement.addEventListener("input", () => {
            const checkInDate = document.getElementById("checkIn").value;
            const checkOutDate = document.getElementById("checkOut").value;

            if (checkInDate && checkOutDate) {
                if (isDateRangeBooked(checkInDate, checkOutDate)) {
                    inputElement.setCustomValidity("The selected date range overlaps with an existing booking. Please choose another date.");
                    inputElement.reportValidity(); // Show the validation error immediately
                } else {
                    inputElement.setCustomValidity("");
                }
            }
        });
    }

    // Restrict Check-Out's min date based on Check-In selection
    const checkInInput = document.getElementById("checkIn");
    const checkOutInput = document.getElementById("checkOut");

    checkInInput.addEventListener("input", () => {
        const selectedDate = checkInInput.value;
        checkOutInput.min = selectedDate; // Set Check-Out's min date dynamically
    });

    // Restrict Check-In date based on Check-Out selection
    checkOutInput.addEventListener("input", () => {
        const selectedDate = checkOutInput.value;
        checkInInput.max = selectedDate; // Set Check-In's max date dynamically
    });

    // Add validation for booked dates
    disableInvalidDates(checkInInput);
    disableInvalidDates(checkOutInput);
});


</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <style>
        /* Your CSS styles here */
        body {
            font-family: 'Raleway', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Container for the booking form and extra info */
        .booking-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0 20px;
            margin-top: 20px;
        }

        /* Extra Info Style */
        .extra-info {
            flex: 1;
            padding: 65px;
            border-radius: 5px;
            max-width: 300px;
            color: #555;
            margin-right: 20px;
        }

        /* Booking Form Container */
        .section .section-center {
            position: relative;
            top: 35%;
            left: 0;
            right: 0;
            transform: translateY(-35%);
            display: flex;
            justify-content: center;
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

        .booking-form .form-header h1 {
            font-weight: 700;
            text-transform: capitalize;
            font-size: 42px;
            margin: 0;
            color: #fff;
        }

        .booking-form .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            height: 30px;
            padding: 0px 25px;
            border: none;
            border-radius: 4px;
            color: #fff;
        }

        .booking-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
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
        }

        @media (max-width: 768px) {
            .booking-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<?php
$placeId = intval($_GET['p']);
$bookedDates = [];
$sql = "SELECT checkIn, checkOut FROM bookings WHERE placeId = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $placeId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $bookedDates[] = ['checkIn' => $row['checkIn'], 'checkOut' => $row['checkOut']];
}
$stmt->close();
$bookedDatesJson = json_encode($bookedDates);
?>

<div class="booking-container">
    <div class="extra-info">
        <h2 style="color: black; font-size: 1.5em; margin-bottom: 10px;">Extra Info</h2>
        <p style="font-size: 1.1em; line-height: 1.6; color: #555;">
            <?= nl2br(htmlspecialchars($place["extraInfo"])) ?>
        </p>
    </div>
    <div id="booking" class="section">
        <div class="section-center">
            <div class="booking-form">
                <div class="form-header">
                    <h1>Make your reservation</h1>
                </div>
                <form action="processBooking.php" method="POST">
                    <input type="hidden" name="placeId" value="<?= htmlspecialchars($placeId) ?>">
                    <div class="form-group">
                        <input class="form-control" type="date" id="checkIn" name="checkIn" required>
                        <label>Check In</label>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="date" id="checkOut" name="checkOut" required>
                        <label>Check Out</label>
                    </div>
                    <button class="submit-btn" type="submit" name="bookNow">Book Now</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const bookedDates = <?= $bookedDatesJson ?>;

        function isDateRangeBooked(checkInDate, checkOutDate) {
            const selectedCheckIn = new Date(checkInDate);
            const selectedCheckOut = new Date(checkOutDate);

            for (const booking of bookedDates) {
                const bookingStart = new Date(booking.checkIn);
                const bookingEnd = new Date(booking.checkOut);

                if ((selectedCheckIn >= bookingStart && selectedCheckIn <= bookingEnd) || 
                    (selectedCheckOut >= bookingStart && selectedCheckOut <= bookingEnd) || 
                    (selectedCheckIn <= bookingStart && selectedCheckOut >= bookingEnd)) {
                    return true;
                }
            }
            return false;
        }

        const checkInInput = document.getElementById("checkIn");
        const checkOutInput = document.getElementById("checkOut");

        checkInInput.addEventListener("input", () => {
            const selectedDate = checkInInput.value;
            checkOutInput.min = selectedDate;
        });

        checkOutInput.addEventListener("input", () => {
            const selectedDate = checkOutInput.value;
            checkInInput.max = selectedDate;
        });
    });
</script>

</body>
</html>

<?php
session_start(); // Ensure session is started

if (isset($_SESSION['useremail'])) {
    $username = $_SESSION['useremail'];
    $button_text = "Logout";
    $button_action = "loginPage.php?logout=true"; // Add query parameter for logout
    $logged_in = true; // User is logged in
} else {
    $button_text = "Login";
    $button_action = "loginPage.php"; // Redirect to login page
    $logged_in = false; // User is not logged in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CedarStay</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            width: 100%;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            background-image: url('https://kaleela.com/Content/BlogImages/9b386574-275d-4f47-a82e-ca3d0cbad532.png');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;

        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #004d40 ;
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
         
        }
        .action-bar .back-button {
            margin-right: 10px;
        }

        .back-button a,
        .login-button {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #00796b;
            transition: background-color 0.3s;
        }
        .back-button a:hover,
        .login-button:hover {
            background-color: #005a4c;
        }

        .action-bar .logo {
            font-size: 1.5em;
            font-weight: bold;
            flex-grow: 1;
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

        .button-container {
            display: <?= $logged_in ? "flex" : "block" ?>;
            justify-content: <?= $logged_in ? "center" : "flex-start" ?>;
            align-items: center;
            margin-bottom: 10px;
            position: fixed;
            top:  <?= $logged_in ? "60px" : "260px" ?>;
            width: <?= $logged_in ? "700px" : "900px" ?>;
            background:  : transparent;
            z-index: 999;
            
          
          
        }

        .glow-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: <?= $logged_in ? "700px" : "900px" ?>;
            height: <?= $logged_in ? "auto" : "70px" ?>;
            border: none;
            outline: none;
            position: relative;
            z-index: 0;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            flex-grow: 1;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            background-color: dodgerblue;
            color: white;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
            margin-right: 40px;
            
        }

        .glow-button:hover {
            transform: <?= $logged_in ? "scale(1.05)" : "translateY(-5px)" ?>;
            transition: transform 0.2s ease-in-out;
        }

        .glow-button:before {
            content: "";
            background: linear-gradient(
                150deg,
                #ff0000,
                #ff0044,
                #ff0075,
                #ff35a2,
                #ff59ca,
                #e877ea,
                #ca90ff,
                #aba5ff,
                #8fb7ff,
                #7cc5ff,
                #79d1ff,
                #85daff,
                #79d1ff,
                #7cc5ff,
                #8fb7ff,
                #aba5ff,
                #ca90ff,
                #e877ea,
                #ff59ca,
                #ff35a2,
                #ff0075,
                #ff0044,
                #ff0000
            );
            position: absolute;
            top: -6px;
            left: -6px;
            background-size: 400%;
            z-index: -1;
            filter: blur(15px);
            width: calc(100% + 12px);
            height: calc(100% + 12px);
            animation: glowing 20s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            border-radius: 5px;
        }

        .glow-button:active {
            transform: scale(0.9);
            transition: transform 0.1s;
        }

        .glow-button:hover:before {
            opacity: 1;
        }

        .glow-button:after {
            z-index: -1;
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: #005a4c;
            border: 0.5px solid white;
            left: 0;
            top: 0;
            border-radius: 5px;
            transition: all 0.2s ease-in-out;
        }

        .glow-button:hover:after {
            background: white;
            transition: all 0.2s ease-in-out;
        }

        .glow-button:hover {
            color: #005a4c;
            transition: all 0.2s ease-in-out;
        }

        @keyframes glowing {
            0% {
                background-position: 0 0;
            }
            50% {
                background-position: 400% 0;
            }
            100% {
                background-position: 0 0;
            }
        }

        #content {
    display: flex;
    justify-content: flex-start; /* Align content to the top */
    align-items: flex-start; /* Align items to the top */
    flex-direction: column;
    padding: 20px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin-top: 120px; /* Push content below buttons */
    
    height: auto; /* Allow content to grow based on its content */
    max-height: calc(100vh - 200px); /* Prevent content from growing beyond screen height */
    overflow-y: auto; /* Enable vertical scrolling if content overflows */
    z-index: 0;
    align-items:center;
    align-items:center;
}





    </style>
</head>
<body>
    <div class="action-bar">
        <div class="back-button">
            <a href="homePage.php">&larr; Back</a>
        </div>
        <div class="logo">CedarStay</div>
        <div class="login-container">
            <a href="<?= $button_action ?>" class="login-button"><?= $button_text ?></a>
        </div>
    </div>
    <div class="button-container">
    <div class="glow-button" id="myBookingButton">My Booking</div>
    <div class="glow-button" id="myAccommodationButton">My Accommodations</div>
</div>

<div id="content">
    <?php if (!$logged_in): ?>
        <p>Welcome! Please log in to view your bookings or accommodations.</p>
    <?php endif; ?>
</div>


<script>
  document.getElementById('myBookingButton')?.addEventListener('click', () => {
    // Check if user is logged in
    if (!<?= json_encode($logged_in) ?>) {
        window.location.href = "loginPage.php"; // Redirect to login if not logged in
    } else {
        loadContent('myBooking.php'); // Load content if logged in
    }
});

document.getElementById('myAccommodationButton')?.addEventListener('click', () => {
    // Check if user is logged in
    if (!<?= json_encode($logged_in) ?>) {
        window.location.href = "loginPage.php"; // Redirect to login if not logged in
    } else {
        loadContent('myAccommodation.php'); // Load content if logged in
    }
});

// If the user is logged in, trigger a click on the 'My Accommodations' button by default
if (<?= json_encode($logged_in) ?>) {
    document.getElementById('myAccommodationButton').click();
}

function loadContent(page) {
    const contentDiv = document.getElementById('content');
    
    fetch(page)
        .then(response => response.text())
        .then(data => {
            contentDiv.innerHTML = data;
        })
        .catch(error => {
            contentDiv.innerHTML = 'Error loading content.';
            console.error(error);
        });
}

</script>

</body>
</html>

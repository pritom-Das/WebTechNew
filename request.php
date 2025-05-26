<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['login_error'] = "You must be logged in to access this page.";
    header("Location: error.html"); 
    exit();
}

$userName = $_SESSION['user_name'] ?? 'Guest'; 

$cities = [
    "New York", "London", "Paris", "Tokyo", "Dubai", "Singapore",
    "Rome", "Berlin", "Sydney", "Rio de Janeiro", "Cairo", "Moscow",
    "Beijing", "Mumbai", "Istanbul", "Los Angeles", "Madrid",
    "Amsterdam", "Seoul", "Toronto"
];

$selectedCities = [];
$errorMessage = '';
if (isset($_SESSION['temp_selected_cities']) && is_array($_SESSION['temp_selected_cities'])) {
    $selectedCities = $_SESSION['temp_selected_cities'];
    unset($_SESSION['temp_selected_cities']);
}


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_cities'])) {
    if (isset($_POST['cities']) && is_array($_POST['cities'])) {
        $submittedCities = $_POST['cities']; 
        if (count($submittedCities) < 10) {
            $errorMessage = "Please select at least 10 cities.";
            $_SESSION['temp_selected_cities'] = $submittedCities;
        } else {
            $_SESSION['final_selected_cities'] = $submittedCities;
            header("Location: cities.php");
            exit();
        }
    } else {
        $errorMessage = "Please select at least 10 cities."; // No cities selected at all
    }
}

// If there's an error in city selection 
if (isset($_SESSION['city_selection_error'])) {
    $errorMessage = $_SESSION['city_selection_error'];
    unset($_SESSION['city_selection_error']); 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to the top for longer content */
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .welcome-message {
            font-size: 1.1em;
            color: #007bff;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .city-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
            text-align: left; /* Align checkboxes to the left */
        }
        .city-item {
            display: flex;
            align-items: center;
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .city-item:hover {
            background-color: #d1d9e0;
        }
        .city-item input[type="checkbox"] {
            margin-right: 10px;
            accent-color: #007bff; 
            transform: scale(1.2); /* Slightly larger checkbox */
        }
        .city-item label {
            flex-grow: 1;
            cursor: pointer;
        }
        .submit-button {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }
        .submit-button:hover {
            background-color: #218838;
        }
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success-message { /* This will not be used here now as we redirect */
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .footer-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="welcome-message">Welcome <?php echo htmlspecialchars($userName); ?>!</p>
        <h1>Select Your Preferred Cities</h1>
        <p>Please select at least 10 cities from the list below:</p>

        <?php if ($errorMessage): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="request.php" method="post">
            <div class="city-grid">
                <?php foreach ($cities as $city): ?>
                    <div class="city-item">
                        <input type="checkbox"
                               id="city_<?php echo str_replace(' ', '_', $city); ?>"
                               name="cities[]"
                               value="<?php echo htmlspecialchars($city); ?>"
                               <?php echo in_array($city, $selectedCities) ? 'checked' : ''; ?>>
                        <label for="city_<?php echo str_replace(' ', '_', $city); ?>">
                            <?php echo htmlspecialchars($city); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" name="submit_cities" class="submit-button">Submit </button>
        </form>

        <div class="footer-links">
            <a href="dashboard.php">Go to Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
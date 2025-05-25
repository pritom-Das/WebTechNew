<?php
session_start();

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['login_error'] = "You must be logged in to access this page.";
    header("Location: error.html");
    exit();
}

$userName = $_SESSION['user_name'] ?? 'Guest';

// Retrieve the selected cities from the session
$selectedCities = [];
if (isset($_SESSION['final_selected_cities']) && is_array($_SESSION['final_selected_cities'])) {
    $selectedCities = $_SESSION['final_selected_cities'];
    // Clear the session variable after retrieving, so it's not shown again if user navigates away and back
    unset($_SESSION['final_selected_cities']);
} else {
    // If no cities were found in the session (e.g., direct access or session expired/cleared)
    $_SESSION['login_error'] = "No cities selected. Please go back and make your selection.";
    header("Location: request.php"); // Redirect back to the selection page
    exit();
}

// --- Mock AQI Data ---
// In a real application, you would fetch real AQI data from an API (e.g., OpenWeatherMap, IQAir)
// For demonstration, we'll generate random but consistent-ish AQI values.
function getAqiValue(string $city): int {
    // A very simple hash to make AQI somewhat consistent for a given city
    $seed = crc32($city); // Generates a consistent number from the city name
    srand($seed);         // Seed the random number generator
    $aqi = rand(20, 150); // Generate a random AQI between 20 and 150
    srand();              // Reset the random seed for other rand() calls if needed
    return $aqi;
}

// Removed the getAqiStatus function as it's no longer needed

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Cities & AQI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .welcome-message {
            font-size: 1.1em;
            color: #007bff;
            margin-bottom: 30px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        /* Removed .aqi-status styling */

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
        <p class="welcome-message">Hello, <?php echo htmlspecialchars($userName); ?>!</p>
        <h1>Your Selected Cities & Current AQI</h1>
        <p>Below are the cities you selected, along with their mock Air Quality Index (AQI) values.</p>

        <?php if (empty($selectedCities)): ?>
            <div class="error-message">
                It seems no cities were selected. Please go back to the selection page.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>City</th>
                        <th>AQI Value</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($selectedCities as $city):
                        $aqi = getAqiValue($city); // Get the AQI value
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($city); ?></td>
                            <td>
                                <?php echo $aqi; ?> </td>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="footer-links">
            <a href="request.php">Change City Selection</a>
            <a href="dashboard.php">Go to Dashboard (if you have one)</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
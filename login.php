<?php



session_start(); // Always start the session at the very top of your script

// Database connection details
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "register"; // Make sure this matches your database name

// Establish database connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the login form was submitted
if (isset($_POST['login_submit'])) {
    $email = $_POST['e_mail'] ?? '';
    $entered_password = $_POST['logpass'] ?? '';

    if (empty($email) || empty($entered_password)) {
        $_SESSION['login_error'] = "Please enter both email and password.";
        header("Location: error.html");
        exit();
    }

    // --- Prepare and execute SQL query to fetch user data ---
    // Using prepared statements is crucial to prevent SQL injection.
    $stmt = $conn->prepare("SELECT name, password FROM userinformation WHERE email = ?");

    if ($stmt) {
        // Bind the email parameter
        $stmt->bind_param("s", $email);
        $stmt->execute();
        // Store the result to check the number of rows
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($name, $password);

            // Fetch the results
            $stmt->fetch();

            // --- Password Verification ---
            // Verify the entered password against the hashed password from the database
            if (password_verify($entered_password, $password)) {
                // --- Login Successful ---
                // Store user information in session
                $_SESSION['user_name'] = $name;
                $_SESSION['logged_in'] = true; // A flag to indicate login status

                // Regenerate session ID for security (prevents session fixation attacks)
                session_regenerate_id(true);

                // Redirect to request.php
                header("Location: request.php");
                exit(); // Always exit after a header redirect
            } else {
                // Invalid password
                $_SESSION['login_error'] = "Invalid email or password."; // Generic error for security
                header("Location: error.html");
                exit();
            }
        } else {
            // No user found with that email
            $_SESSION['login_error'] = "Invalid email or password."; // Generic error for security
            header("Location: error.html");
            exit();
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error in preparing the SQL statement
        // In a real application, you'd log this error.
        $_SESSION['login_error'] = "An unexpected error occurred. Please try again.";
        header("Location: error.html");
        exit();
    }
} else {
    // If the script is accessed directly without a POST submission (e.g., typing login.php in browser)
    $_SESSION['login_error'] = "Please submit the login form.";
    header("Location: error.html");
    exit();
}

// Close the database connection
$conn->close();
?>
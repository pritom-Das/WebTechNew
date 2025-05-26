
<?php



session_start(); 

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

  
    $stmt = $conn->prepare("SELECT name, password FROM userinformation WHERE email = ?");
    if ($stmt) {
        // Bind the email parameter
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($name, $password);

            // Fetch the results
            $stmt->fetch();

            if ($entered_password === $password) {
                $_SESSION['user_name'] = $name;
                $_SESSION['logged_in'] = true; // A flag to indicate login status
                session_regenerate_id(true);
                header("Location: request.php");
                exit(); 
            } else {
                // Invalid password
                $_SESSION['login_error'] = "Invalid email or password."; 
                header("Location: newerror.html");
                exit();
            }
        } else {
            $_SESSION['login_error'] = "Invalid email or password."; 
            header("Location: error.html");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION['login_error'] = "An unexpected error occurred. Please try again.";
        header("Location: error.html");
        exit();
    }
} else {
    
    $_SESSION['login_error'] = "Please submit the login form.";
    header("Location: error.html");
    exit();
}

// Close the database connection
$conn->close();
?>

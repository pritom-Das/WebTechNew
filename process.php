
 <?php<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm User Information</title>
    <link rel="stylesheet" href="stylingProcess.css">
</head>
<body>
    <h2>User Information</h2>
    <div class="container">
        <?php
        session_start(); // Start the session to store user data temporarily

        if (isset($_POST['submit'])) {
            $uname = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $dob = htmlspecialchars($_POST['dob']);
            $city = htmlspecialchars($_POST['city']);
            $pass = htmlspecialchars($_POST['password']);
            $confipass = htmlspecialchars($_POST['confipassword']);
            $terms = isset($_POST['terms']) ? $_POST['terms'] : '';

            if ($uname && $email && $dob && $pass && $confipass && $city && $terms !== '') {
                echo "<p> User name: $uname</p> ";
                echo "<p> Email: $email</p>";
                echo "<p> Dob: $dob </p>";
                echo "<p> City: $city </p>";
                echo "<p> Password: $pass</p>"; 
                echo "<p> Confirm Password: $confipass </p>";
                echo "<p> Terms: $terms </p>";
                echo "<p>Please confirm your information before submitting.</p>";

                // Store user data in session temporarily
                $_SESSION['temp_uname'] = $uname;
                $_SESSION['temp_email'] = $email;
                $_SESSION['temp_dob'] = $dob;
                $_SESSION['temp_city'] = $city;
                $_SESSION['temp_pass'] = $pass;
                $_SESSION['temp_terms'] = $terms;

                // Form for the confirm button
                echo '<form method="post">';
                echo '<button type="submit" name="confirm">Confirm</button>';
                echo '<button type="button" onclick="window.history.back();">Cancel</button>';
                echo '</form>';

            } else {
                echo "<p>Some required fields are missing.</p>";
                echo '<button type="button" onclick="window.history.back();">Go Back</button>';
            }
        } elseif (isset($_POST['confirm'])) {

            // Retrieve user data from the session
            $uname = $_SESSION['temp_uname'];
            $email = $_SESSION['temp_email'];
            $dob = $_SESSION['temp_dob'];
            $city = $_SESSION['temp_city'];
            $pass = $_SESSION['temp_pass'];
            $terms = $_SESSION['temp_terms'];

            // Database connection
            $host = "localhost";
            $user = "root";
            $password = "";
            $db = "register";
            $conn = mysqli_connect($host, $user, $password, $db);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Use prepared statements to prevent SQL injection
            $sql = "INSERT INTO userinformation (name, email, password, country, dob) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssss", $uname, $email, $pass, $city, $dob);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<p>User information inserted successfully.</p>";
                    unset($_SESSION['temp_uname']);
                    unset($_SESSION['temp_email']);
                    unset($_SESSION['temp_dob']);
                    unset($_SESSION['temp_city']);
                    unset($_SESSION['temp_pass']);
                    unset($_SESSION['temp_terms']);
                } else {
                    echo "<p>Error inserting record: " . mysqli_stmt_error($stmt) . "</p>";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<p>Error preparing SQL statement: " . mysqli_error($conn) . "</p>";
            }
            mysqli_close($conn);

            echo '<button type="button"><a href="index.html">Go back to form</a></button>';
        } else {
            echo "<p>No user data to confirm.</p>";
            echo '<button type="button"><a href="index.html">Go to Registration Form</a></button>';
        }
        ?>
    </div>
</body>
</html>
       
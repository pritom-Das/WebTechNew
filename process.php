<?php

if (isset($_POST['submit'])) {
    $uname = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $dob = htmlspecialchars($_POST['dob']);
    $city = htmlspecialchars($_POST['city']);
    $pass = htmlspecialchars($_POST['password']);
    $confipass = htmlspecialchars($_POST['confipassword']);
    
    // For checkbox, use isset() since it may not be set at all if not checked
    $terms = isset($_POST['terms']) ? $_POST['terms'] : '';

    if ($uname && $email && $dob && $pass && $confipass && $city && $terms !== '') {
        echo "User name: $uname<br>";
        echo "Email : $email<br>";
        echo "Dob : $dob <br>";
        echo "City : $city <br>";
        echo "Password : $pass <br>";
        echo "Confirm Password : $confipass <br>";
        echo "Terms : $terms <br>";
    } else {
        echo "Some fields are missing or invalid 1";
    }
} else {
    echo "Some fields are missing or invalid 2";
}


?>
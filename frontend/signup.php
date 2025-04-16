#!/usr/local/bin/php
<?php
// signup.php
session_start(); // If you want to maintain a session for logged-in users

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Retrieve form data from POST
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // 2. Basic server-side validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        die("Error: All fields are required.");
    }
    if ($password !== $confirm) {
        die("Error: Passwords do not match.");
    }

    // 3. Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 4. Connect to your database
    $servername = "mysql.cise.ufl.edu";  
    $dbUsername = "ngleason";
    $dbPassword = "Bigtime12";
    $dbname     = "Budget_Buddy";

    try {
        // Create a PDO connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // 5. Insert user data
        $sql = "INSERT INTO users (Username, Password) VALUES (:username, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        //$stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        $stmt->execute();

        //establish session before redirect
        $_SESSION['UserID']   = $user['UserID'];
        $_SESSION['Username'] = $user['Username'];

        // 6. Redirect or show success message
        header("Location: home/index.php");
        exit; 
        // Optionally redirect:
        // header("Location: login.html");
        // exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>

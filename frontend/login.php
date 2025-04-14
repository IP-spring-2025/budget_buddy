#!/usr/local/bin/php
<?php
// login.php
session_start(); // If you want to maintain a session for logged-in users
print($PHPSESSID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die("Error: Username and password are required.");
    }

    // 1. Connect to the database
    $servername = "mysql.cise.ufl.edu";  
    $dbUsername = "ngleason";
    $dbPassword = "Bigtime12";
    $dbname     = "Budget_Buddy";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. Fetch the user record
        $sql = "SELECT UserID, Username, Password FROM users WHERE Username = :username LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. If user exists, verify hashed password
        if ($user && password_verify($password, $user['Password'])) {
            // Password is correct
            // Optional: store session variables
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['Username'] = $user['Username'];

            echo "Login successful! Welcome, " . $user['Username'];
            header("Location: home/index.php");
            exit; 

        } else {
            // user not found or password mismatch
            echo "Invalid username or password.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>

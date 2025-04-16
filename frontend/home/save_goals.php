#!/usr/local/bin/php
<?php
session_start();

if(isset($_SESSION['UserID']) && isset($_SESSION['Username'])) {
    $userID = $_SESSION['UserID'];

    //goal values
    $living = $_POST['living'];
    $saving = $_POST['saving'];
    $investing = $_POST['investing'];
    $other = $_POST['other'];

    // DB credentials
    $servername = "mysql.cise.ufl.edu";  
    $dbUsername = "ngleason";
    $dbPassword = "Bigtime12";
    $dbname     = "Budget_Buddy";

    //create connection
    $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
    if (!$conn) {
        http_response_code(500);
        die("Connection failed: " . mysqli_connect_error());
    }

    //update the goal vals
    $sql = "UPDATE budget SET LivingGoal = ?, SavingGoal = ?, InvestingGoal = ?, OtherGoal = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ddddi", $living, $saving, $investing, $other, $userID);

        if (mysqli_stmt_execute($stmt)) {
            echo "Success: Living = $living, Saving = $saving, Investing = $investing, Other = $other";
        } else {
            http_response_code(500);
            echo "Execution failed: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        http_response_code(500);
        echo "Prepare failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    http_response_code(403);
    echo "Session for userid and username not set!";
}
?>

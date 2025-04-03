#!/usr/local/bin/php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>
<body>

<h2>User Table</h2>

<?php
$servername = "mysql.cise.ufl.edu"; 
$username = "";        //CHANGE TO YOUR SQL username (UFID)
$password = "";  // CHANGE TO YOUR SQL password (Not Gatorlink password)
$dbname = "Budget_Buddy"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT UserID, Username, Balance FROM users");
    $stmy = $conn->query("SELECT UserID, TotalIncome, TotalExpenses, BudgetType FROM budget");
    
    echo "<table border='1'>
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>Balance</th>
            </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['UserID']}</td>
                <td>{$row['Username']}</td>
                <td>\${$row['Balance']}</td>
              </tr>";
    }
    echo "</table>";

    echo "<h2>Budget Table</h2>";
    echo "<table border='1'>
            <tr>
                <th>UserID</th>
                <th>TotalIncome</th>
                <th>TotalExpenses</th>
                <th>BudgetType</th>
            </tr>";

    while ($row = $stmy->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['UserID']}</td>
                <td>\${$row['TotalIncome']}</td>
                <td>\${$row['TotalExpenses']}</td>
                <td>{$row['BudgetType']}</td>
              </tr>";
    }
    echo "</table>";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;
?>

</body>
</html>

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

    $stmt = $conn->query("SELECT UserID, Username, Money FROM users");
    
    echo "<table border='1'>
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>Money</th>
            </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['UserID']}</td>
                <td>{$row['Username']}</td>
                <td>\${$row['Money']}</td>
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

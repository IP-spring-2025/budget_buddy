<?php
session_start();

//file to update pie and bar chart in real tine 

if (isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];

    // DB connection
    $servername = "mysql.cise.ufl.edu";  
    $dbUsername = "ngleason";
    $dbPassword = "Bigtime12";
    $dbname     = "Budget_Buddy";

    $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
    if (!$conn) {
        http_response_code(500);
        echo json_encode(["error" => "Connection failed"]);
        exit();
    }

    // categories and orgin totals
    $categories = ['Living', 'Saving', 'Investing', 'Other'];
    $totals = array_fill_keys($categories, 0);

    $sql = "SELECT Category, Amount FROM transactions WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $sql);

    $totalAmount = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        if (in_array($row['Category'], $categories)) {
            $totals[$row['Category']] += $row['Amount'];
            $totalAmount += $row['Amount'];
        }
    }

    // calc percentages
    $percentages = [];
    foreach ($categories as $cat) {
        $percent = $totalAmount > 0 ? ($totals[$cat] / $totalAmount) * 100 : 0;
        $percentages[] = round($percent, 2);
    }

    echo json_encode($percentages); 
} else {
    http_response_code(403);
    echo json_encode(["error" => "Not authorized"]);
}
?>

#!/usr/local/bin/php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];
    $category = $_POST['category'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $description = $_POST['description'] ?? null;
    $date = $_POST['date'] ?? null;

    $servername = "mysql.cise.ufl.edu";  
    $dbUsername = "ngleason";
    $dbPassword = "Bigtime12";
    $dbname     = "Budget_Buddy";

    $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
    if (!$conn) {
        http_response_code(500);
        die("Connection failed: " . mysqli_connect_error());
    }

    // update balance if posted
    if (isset($_POST['totalBalance']) && is_numeric($_POST['totalBalance'])) {
        $newBalance = $_POST['totalBalance'];

        $sql = "UPDATE users SET Balance = ? WHERE UserID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            http_response_code(500);
            die("Prepare failed (update balance): " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "di", $newBalance, $userID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo "Balance updated";
        exit();
    }

    // check for existing categories
    $categories = ['Living', 'Saving', 'Investing', 'Other'];
    $existing = [];

    $check = "SELECT Category FROM budget WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $check);
    if (!$stmt) {
        http_response_code(500);
        die("Prepare failed (check budget): " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cat);
    while (mysqli_stmt_fetch($stmt)) {
        $existing[] = strtolower(trim($cat));
    }
    mysqli_stmt_close($stmt);

    // insert missing categories with defaults
    $insertDefault = "INSERT INTO budget (UserID, Category, TotalIncome, TotalExpense, BudgetType, Percentage) VALUES (?, ?, 0, 0, 'yearly', 0)";
    foreach ($categories as $cat) {
        if (!in_array(strtolower($cat), $existing)) {
            $stmt = mysqli_prepare($conn, $insertDefault);
            if (!$stmt) {
                http_response_code(500);
                die("Prepare failed (insert default): " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "is", $userID, $cat);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // handle transaction insert
    if ($category && $amount && $description && $date) {
        $sql = "SELECT BudgetID FROM budget WHERE UserID = ? AND TRIM(LOWER(Category)) = TRIM(LOWER(?)) LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            http_response_code(500);
            die("Prepare failed (select budgetID): " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "is", $userID, $category);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $budgetID);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($budgetID) {
            $insert = "INSERT INTO transactions (UserID, BudgetID, Amount, TransactionDate, Category, Description) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert);
            if (!$stmt) {
                http_response_code(500);
                die("Prepare failed (insert transaction): " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "iidsss", $userID, $budgetID, $amount, $date, $category, $description);

            if (mysqli_stmt_execute($stmt)) {
                echo "Success";
            } else {
                http_response_code(500);
                echo "Insert failed: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            http_response_code(400);
            echo "No matching budget ID found for category: $category";
        }

        mysqli_close($conn);
    }
} else {
    http_response_code(403);
    echo "Unauthorized";
}
?>

#!/usr/local/bin/php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

//file to insert transactions from modal form when filled otu

    if (isset($_SESSION['UserID'])) {
        $userID = $_SESSION['UserID'];
        $category = $_POST['category'];
        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $date = $_POST['date'];

        $servername = "mysql.cise.ufl.edu";  
        $dbUsername = "ngleason";
        $dbPassword = "Bigtime12";
        $dbname     = "Budget_Buddy";

        $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
        if (!$conn) {
            http_response_code(500);
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    //updates total balance 
    if (isset($_POST['totalBalance']) && is_numeric($_POST['totalBalance'])) {
        $newBalance = $_POST['totalBalance'];
    
        $sql = "UPDATE budget SET TotalIncome = ? WHERE UserID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "di", $newBalance, $userID);
    
        if (mysqli_stmt_execute($stmt)) {
            echo "Balance updated";
        } else {
            http_response_code(500);
            echo "Update failed: " . mysqli_stmt_error($stmt);
        }
    
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit();
    }
    

    if (isset($_POST['category'], $_POST['amount'], $_POST['description'], $_POST['date'])) {
        $category = $_POST['category'];
        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $date = $_POST['date'];
    

        //matches budet id to catergories 
        $sql = "SELECT BudgetID FROM budget WHERE UserID = ? AND TRIM(LOWER(Category)) = TRIM(LOWER(?)) LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $userID, $category);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $budgetID);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    
        if ($budgetID) {
            $insert = "INSERT INTO transactions (UserID, BudgetID, Amount, TransactionDate, Category, Description)  VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert);
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

?>
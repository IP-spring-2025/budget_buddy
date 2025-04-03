CREATE Table transactions(
    TransactionID int AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    BudgetID INT NOT NULL,
    Amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    TransactionDate Date,
    Category VARCHAR(50),
    FOREIGN KEY(BudgetID) REFERENCES budget(BudgetID) ON DELETE CASCADE,
    FOREIGN KEY(UserID) REFERENCES users(UserID) ON DELETE CASCADE 
);

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (1, 1, 50.00, '2025-04-01', 'Groceries');
 
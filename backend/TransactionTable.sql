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


INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (2, 2, 100.00, '2025-04-02', 'Rent');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (3, 3, 25.00, '2025-04-03', 'Utilities');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (4, 4, 75.00, '2025-04-04', 'Transportation');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (5, 5, 150.00, '2025-04-05', 'Entertainment');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (1, 1, 200.00, '2025-04-06', 'Shopping');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (2, 2, 50.00, '2025-04-07', 'Dining Out');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (3, 3, 300.00, '2025-04-08', 'Medical');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (4, 4, 400.00, '2025-04-09', 'Travel');

INSERT INTO transactions (BudgetID, UserID, Amount, TransactionDate, Category)
VALUES (5, 5, 500.00, '2025-04-10', 'Education');
=======
 


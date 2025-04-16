CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(15),
    Password VARCHAR(30),
    Money DECIMAL(10,2) DEFAULT 0.00 
);

CREATE TABLE budget (
    BudgetID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    TotalIncome DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    TotalExpense DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    BudgetType ENUM('monthly', 'yearly', 'biweekly') NOT NULL,
    FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE
);


    
INSERT INTO users (Username, Password) VALUES ('admin', 'admin123');
INSERT INTO users (Username, Password, Money) VALUES ('user1', 'password1', '100.00');
INSERT INTO users (Username, Password, Money) VALUES ('user2', 'password2', '50.00');

INSERT INTO budget (UserID, TotalIncome, TotalExpense, BudgetType)
VALUES (1, 5000.00, 2000.00, 'monthly');

INSERT INTO users (Username, Password, Money) VALUES ('user3', 'password3', '200.00');
INSERT INTO users (Username, Password, Money) VALUES ('user4', 'password4', '300.00');
INSERT INTO users (Username, Password, Money) VALUES ('user5', 'password5', '400.00');

INSERT INTO budget (UserID, TotalIncome, TotalExpense, BudgetType)
VALUES (2, 3000.00, 1500.00, 'yearly');
INSERT INTO budget (UserID, TotalIncome, TotalExpense, BudgetType)
VALUES (3, 4000.00, 1000.00, 'biweekly');
INSERT INTO budget (UserID, TotalIncome, TotalExpense, BudgetType)
VALUES (4, 2500.00, 1200.00, 'monthly');
INSERT INTO budget (UserID, TotalIncome, TotalExpense, BudgetType)
VALUES (5, 6000.00, 3000.00, 'yearly');
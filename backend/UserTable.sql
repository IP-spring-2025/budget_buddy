CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(15),
    Password VARCHAR(30),
    Money DECIMAL(10,2) DEFAULT 0.00 
);

CREATE TABLE budget (
    UserID INT PRIMARY KEY,
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

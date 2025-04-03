CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(15),
    Password VARCHAR(30),
    Money DECIMAL(10,2) DEFAULT 0.00 
);

    
INSERT INTO users (Username, Password) VALUES ('admin', 'admin123');
INSERT INTO users (Username, Password, Money) VALUES ('user1', 'password1', '100.00');
INSERT INTO users (Username, Password, Money) VALUES ('user2', 'password2', '50.00');

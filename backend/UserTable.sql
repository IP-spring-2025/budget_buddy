CREATE TABLE users (
	Username varchar(15),
    Password varchar(30),
    PRIMARY KEY (Username)
    );
    
INSERT INTO users (Username, Password)
VALUES ("TestUser", "Password");

SELECT Username, Password
FROM users;
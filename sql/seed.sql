
DROP DATABASE IF EXISTS shoetaku;
CREATE DATABASE shoetaku;
USE shoetaku;

GRANT ALL PRIVILEGES ON shoetaku.* TO 'shoetakusa'@`localhost` IDENTIFIED BY 'shoetaku';
FLUSH PRIVILEGES;



CREATE TABLE User (
userId INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
email VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(64) NOT NULL,
firstName VARCHAR(50) NOT NULL,
lastName VARCHAR(50) NOT NULL,
city VARCHAR(10) NOT NULL,
countryCode VARCHAR(10) NOT NULL,
isFacebook TINYINT NOT NULL DEFAULT 0,
isMerged TINYINT NOT NULL DEFAULT 0 
);



INSERT INTO User (email, password, firstName, lastName, city, countryCode) VALUES ("edwardchoi90@gmail.com","test","FN","LN","Vancouver","CA");
INSERT INTO User (email, password, firstName, lastName, city, countryCode) VALUES ("test@test.com","test","FN","LN","Vancouver","CA");

CREATE TABLE Shoe (
shoeId INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
brand VARCHAR(20) NOT NULL,
model VARCHAR(25) NOT NULL,
size DECIMAL(3,1) NOT NULL,
itemCondition ENUM('1','2','3','4','5'),
description TEXT,
imageUrl VARCHAR(50),
ownerId INT(11) NOT NULL,
likeCount INT(11) NOT NULL DEFAULT 0,
isWanted TINYINT NOT NULL DEFAULT 0,
FOREIGN KEY (ownerId) REFERENCES USER (userId) ON DELETE CASCADE
);

INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike", "NikeShoes", 9, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike2", "NikeShoes", 10, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike3", "NikeShoes", 8, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike4", "NikeShoes", 11, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike5", "NikeShoes", 12, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike6", "NikeShoes", 7, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike7", "NikeShoes", 9, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike8", "NikeShoes", 10, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike9", "NikeShoes", 8, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike10", "NikeShoes", 11, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike11", "NikeShoes", 12, 1,"Mint", "example.com", 1, 0, 1);
INSERT INTO Shoe(brand, model, size, itemCondition, description, imageUrl, ownerId, likeCount, isWanted) VALUES ("Nike12", "NikeShoes", 7, 1,"Mint", "example.com", 1, 0, 1);

CREATE TABLE Wanted
(
postId INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(30) NOT NULL,
price DECIMAL(7,2) NOT NULL,
shoeId INT(11) NOT NULL,
created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
isFound TINYINT NOT NULL DEFAULT 0,
FOREIGN KEY (shoeId) REFERENCES Shoe (shoeId) ON DELETE CASCADE
);

CREATE TABLE Sell
(
postId INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(30) NOT NULL,
price DECIMAL(7,2) NOT NULL,
shoeId INT(11) NOT NULL,
created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
isSold TINYINT NOT NULL DEFAULT 0,
FOREIGN KEY (shoeId) REFERENCES Shoe (shoeId) ON DELETE CASCADE
);

CREATE TABLE Like_Shoe
(
userId INT(11) NOT NULL,
shoeId INT(11) NOT NULL,
PRIMARY KEY (userId, shoeId),
FOREIGN KEY (userId) REFERENCES User (userId),
FOREIGN KEY (shoeId) REFERENCES Shoe (shoeId) ON DELETE CASCADE
);

CREATE TABLE Message
(
messageId INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
sender INT(11) NOT NULL,
receiver INT(11) NOT NULL,
message TEXT,
sendDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (sender) REFERENCES User (userId),
FOREIGN KEY (receiver) REFERENCES User (userId)
);

CREATE TABLE Invalid_Token
(
dbId INT(11)  NOT NULL PRIMARY KEY AUTO_INCREMENT,
tokenId VARCHAR(30) NOT NULL,
token VARCHAR(60) NOT NULL,
expiryTime INT(30) NOT NULL
);

CREATE TRIGGER After_Add_Like AFTER INSERT ON Like_Shoe 
FOR EACH ROW
	UPDATE Shoe
    SET likeCount = likeCount + 1 
    where shoeId = NEW.shoeId;


CREATE TRIGGER After_Delete_Like AFTER DELETE ON Like_Shoe
FOR EACH ROW
    UPDATE CLASS
    SET likeCount = likeCount - 1 
    WHERE shoeId = OLD.shoeId;

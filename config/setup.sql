flush privileges;

CREATE DATABASE if not exists looneylimericks;
GRANT ALL PRIVILEGES ON looneylimericks.* To 'pat'@'localhost' IDENTIFIED BY 'secret';

use looneylimericks;

DROP TABLE IF EXISTS poems;

CREATE TABLE poems (
 id INT(15) PRIMARY KEY AUTO_INCREMENT,
 title VARCHAR(30),
 author VARCHAR(30),
 timestamp int(15),
 content VARCHAR(155),
 featured TINYINT(1),
 votes INT(5),
 value float(5));


INSERT INTO poems VALUES (null, 'Help', 'David', '1382760077', 'can\'t get a good a poem topic\r\nthis is becoming catastrophic\r\ncausing some frustration\r\ngoing to take a vacation\r\nits causing me to be myopic', 0, 15, 23.21);
INSERT INTO poems VALUES (null, 'Punishment for a Crime', 'David and Joshua', '1382752124', 'King said I committed treason\r\nCouldn\'t give a good reason\r\nhe sentenced me to die\r\nI left without a goodbye\r\nand proceeded to tease him', 0, 5, 12.5);
INSERT INTO poems VALUES (null, 'Short Poem', 'David', '1382743523', 'poem short\r\nlook I play sport\r\nI cry\r\nI spy\r\nI contort', 0, 1, 4);
INSERT INTO poems VALUES (null, 'Sabbath', 'David', '1382783480', 'king of every nation\r\nLord of all creation\r\nhe made it in seven\r\ndays, sits in heaven\r\neleven ways, just for elation', 1, 7, 14.22);
INSERT INTO poems VALUES (null, 'A decision', 'David', '1382788654', 'I made a new decision\r\nIts a most grand vision\r\nI\'m going to stay meek\r\nAll throughout the week\r\nSo I can avoid car collision', 1, 2, 3.5);
INSERT INTO poems VALUES (null, 'Getting fat', 'David', '1382794243', 'he gained some size\r\nafter eating some fries\r\nnow he\'s going to combat\r\nfor a new weight to arrive at\r\nso he wont be despised', 1, 0, 0);
INSERT INTO poems VALUES (null, 'Rate this', 'David', '1382764316', 'give me an update\r\nwhat is the new poem rate\r\ndon\'t laugh\r\nwhen you look at the graph\r\nits about a fourth of eight', 1, 1, 2);

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DELIMITER |
CREATE EVENT select_featured_poem 
    ON SCHEDULE EVERY 10 MINUTE 
    DO
      BEGIN
		UPDATE poems SET featured = 0;
		UPDATE poems SET featured = 1 WHERE rand() limit 1;
      END |
 
DELIMITER ;

SET GLOBAL event_scheduler =  ON;
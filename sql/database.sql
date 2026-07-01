CREATE DATABASE IF NOT EXISTS tuneflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tuneflow;

DROP TABLE IF EXISTS songs;
DROP TABLE IF EXISTS singers;
DROP TABLE IF EXISTS genres;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    role ENUM('user','premium','admin') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT 'default.png',
    status ENUM('active','locked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE singers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    image VARCHAR(255) DEFAULT 'default-avatar.png',
    followers VARCHAR(50) DEFAULT '0',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    songs_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE songs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    singer VARCHAR(100) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    duration VARCHAR(10) DEFAULT '00:00',
    plays INT DEFAULT 0,
    cover VARCHAR(255) DEFAULT 'default-song.png',
    audio VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users(fullname,email,role,avatar,status) VALUES
('Nguyen Van A','nguyenvana@gmail.com','user','default.png','active'),
('Tran Thi B','tranthib@gmail.com','premium','default.png','active'),
('Le Van C','levanc@gmail.com','admin','default.png','active'),
('Pham Thi D','phamthid@gmail.com','user','default.png','active'),
('Hoang Van E','hoangvane@gmail.com','premium','default.png','active'),
('Bui Minh Khang','buiminhkhang@gmail.com','user','default.png','active'),
('Do Thanh Long','dothanhlong@gmail.com','user','default.png','active'),
('Vo Ngoc Mai','vongocmai@gmail.com','premium','default.png','active'),
('Dang Anh Quan','danganhquan@gmail.com','user','default.png','locked'),
('Mai Phuong Linh','maiphuonglinh@gmail.com','admin','default.png','active');

INSERT INTO singers(name,country,image,followers) VALUES
('Luna Sky','USA','default-avatar.png','2.3M'),
('DJ Nova','Canada','default-avatar.png','1.8M'),
('Ariana Lee','USA','default-avatar.png','3.1M'),
('Minh Anh','Vietnam','default-avatar.png','950K'),
('Kenji Yamato','Japan','default-avatar.png','1.2M'),
('Sofia Rose','UK','default-avatar.png','2.0M'),
('Leo Max','Australia','default-avatar.png','780K'),
('Hana Kim','Korea','default-avatar.png','1.5M'),
('Tommy Ray','USA','default-avatar.png','890K'),
('Mia Chen','China','default-avatar.png','1.1M');

INSERT INTO genres(name,description,songs_count) VALUES
('Pop','Popular music with catchy melodies and modern sound.',12),
('EDM','Electronic dance music for clubs and festivals.',10),
('Rock','Strong guitar sound and energetic rhythm.',8),
('Rap','Hip hop music with rhythm and lyrical flow.',9),
('Ballad','Slow romantic songs with emotional melodies.',11),
('R&B','Smooth vocals with soul and rhythm influences.',7),
('Jazz','Improvisational music with rich harmony.',5),
('Indie','Independent music with unique creative style.',6);

INSERT INTO songs(title,singer,genre,duration,plays,cover,audio) VALUES
('Summer Vibes','Luna Sky','Pop','03:25',300000,'default-song.png','summer-vibes.mp3'),
('Neon Lights','DJ Nova','EDM','04:10',250000,'default-song.png','neon-lights.mp3'),
('Forever Young','Ariana Lee','Ballad','03:48',200000,'default-song.png','forever-young.mp3'),
('Midnight Dream','Sofia Rose','Pop','03:32',180000,'default-song.png','midnight-dream.mp3'),
('Firestorm','Tommy Ray','Rock','04:05',165000,'default-song.png','firestorm.mp3'),
('Lost in Tokyo','Kenji Yamato','Indie','03:55',140000,'default-song.png','lost-in-tokyo.mp3'),
('Rainy Night','Hana Kim','R&B','03:44',132000,'default-song.png','rainy-night.mp3'),
('City Lights','Leo Max','EDM','03:58',125000,'default-song.png','city-lights.mp3'),
('Golden Hour','Mia Chen','Pop','03:29',118000,'default-song.png','golden-hour.mp3'),
('Broken Heart','Minh Anh','Ballad','04:12',110000,'default-song.png','broken-heart.mp3'),
('Skyline','DJ Nova','EDM','03:50',98000,'default-song.png','skyline.mp3'),
('One More Chance','Ariana Lee','Ballad','04:20',92000,'default-song.png','one-more-chance.mp3'),
('Dream Again','Luna Sky','Pop','03:36',87000,'default-song.png','dream-again.mp3'),
('Street Flow','Tommy Ray','Rap','03:12',82000,'default-song.png','street-flow.mp3'),
('Ocean Eyes','Sofia Rose','R&B','03:41',76000,'default-song.png','ocean-eyes.mp3');
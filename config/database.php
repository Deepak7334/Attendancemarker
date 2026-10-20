CREATE DATABASE village_db;
USE village_db;

-- Admin Table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    password VARCHAR(255)
);

-- Citizens Table
CREATE TABLE citizens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    password VARCHAR(255),
    status VARCHAR(20) DEFAULT 'pending'
);

-- Schemes Table
CREATE TABLE schemes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scheme_name VARCHAR(150),
    description TEXT,
    eligibility TEXT,
    last_date DATE
);

-- Complaints Table
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    citizen_id INT,
    subject VARCHAR(150),
    message TEXT,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Announcements
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin (password = admin123)
INSERT INTO admin (username, password)
VALUES ('admin', '$2y$10$wH6dZkJ7v0kJg0mQbE8r3u7wD1Gk9T5XoK6x0XcQ2QnQeQWlF8Oa2');
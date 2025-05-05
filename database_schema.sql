-- Create Database
CREATE DATABASE library_system;

-- Use the Database
USE library_system;

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'librarian', 'student') NOT NULL,
    phone_number VARCHAR(15),
    address TEXT
);

-- BOOKS TABLE
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    published_year YEAR,
    isbn VARCHAR(13) UNIQUE,
    quantity INT NOT NULL,
    shelf_location VARCHAR(255),
    status ENUM('available', 'damaged', 'reserved') DEFAULT 'available'
);

-- ISSUE BOOKS TABLE
CREATE TABLE issue_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    issue_date DATE NOT NULL,
    return_date DATE,
    fine DECIMAL(10, 2),
    status ENUM('issued', 'returned', 'overdue', 'lost') DEFAULT 'issued',
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- FINES TABLE
CREATE TABLE fines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    reason TEXT NOT NULL,
    paid_status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- DAMAGED BOOKS TABLE
CREATE TABLE damaged_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    reported_by INT NOT NULL,
    date_reported DATE NOT NULL,
    remarks TEXT,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (reported_by) REFERENCES users(id)
);

-- SEARCH HISTORY TABLE
CREATE TABLE search_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    search_term VARCHAR(255) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ACTIVITY LOGS TABLE
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- Insert Admin User
INSERT INTO users (name, email, password, role) 
VALUES ('Admin User', 'admin@example.com', PASSWORD('admin123'), 'admin');

-- Insert Librarian User
INSERT INTO users (name, email, password, role) 
VALUES ('Librarian User', 'librarian@example.com', PASSWORD('lib123'), 'librarian');

-- Insert Student User
INSERT INTO users (name, email, password, role) 
VALUES ('Student User', 'student@example.com', PASSWORD('stud123'), 'student');
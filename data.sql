CREATE DATABASE mdm_db;

USE mdm_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE master_brand (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50),
    name VARCHAR(100),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE master_category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50),
    name VARCHAR(100),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE master_item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand_id INT,
    category_id INT,
    code VARCHAR(50),
    name VARCHAR(100),
    attachment VARCHAR(255),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES master_brand(id),
    FOREIGN KEY (category_id) REFERENCES master_category(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

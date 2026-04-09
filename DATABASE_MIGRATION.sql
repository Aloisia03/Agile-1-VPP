-- Database Migration Guide

-- Add new columns to users table for password reset functionality
ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER password;
ALTER TABLE users ADD COLUMN address TEXT NULL AFTER phone;
ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL AFTER address;
ALTER TABLE users ADD COLUMN reset_token_expires DATETIME NULL AFTER reset_token;

-- Alternative: If the users table doesn't exist yet, create it with all columns
-- CREATE TABLE users (
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     name VARCHAR(255) NOT NULL,
--     email VARCHAR(255) NOT NULL UNIQUE,
--     password VARCHAR(255) NOT NULL,
--     phone VARCHAR(20),
--     address TEXT,
--     reset_token VARCHAR(255),
--     reset_token_expires DATETIME,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

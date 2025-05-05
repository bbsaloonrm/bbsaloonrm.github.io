CREATE DATABASE IF NOT EXISTS barbershop;
USE barbershop;

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create database user with necessary privileges
CREATE USER IF NOT EXISTS 'barber_user'@'localhost' IDENTIFIED BY 'securepassword';
GRANT ALL PRIVILEGES ON barbershop.* TO 'barber_user'@'localhost';
FLUSH PRIVILEGES;

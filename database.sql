-- CREATE DATABASE plans_app;

-- USE plans_app;

CREATE TABLE
  users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    plan_id INT NOT NULL
  );

CREATE TABLE
  plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    max_entries INT NOT NULL
  );
INSERT INTO plans (name, max_entries) VALUES 
('Basic Plan', 5),
('Premium Plan', 50),
('Platinum Plan', 0);


CREATE TABLE
  entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id)
  );

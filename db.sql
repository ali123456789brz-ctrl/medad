
-- Create database and tables for WAMP (MySQL)
CREATE DATABASE IF NOT EXISTS medad CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE medad;

-- Consultation requests = tickets
CREATE TABLE IF NOT EXISTS consultation_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(32) NOT NULL,
  case_type VARCHAR(64) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('pending','active','closed') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (phone)
) ENGINE=InnoDB;

-- Messages under each ticket
CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ticket_id INT NOT NULL,
  sender ENUM('client','admin','system') NOT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_msg_ticket FOREIGN KEY (ticket_id) REFERENCES consultation_requests(id) ON DELETE CASCADE,
  INDEX(ticket_id)
) ENGINE=InnoDB;

-- Admin users (no default user here; use seed_admin.php once)
CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Admin tokens (simple bearer token auth)
CREATE TABLE IF NOT EXISTS admin_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NOT NULL,
  token VARCHAR(128) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_tok_admin FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE,
  INDEX (token),
  INDEX (expires_at)
) ENGINE=InnoDB;

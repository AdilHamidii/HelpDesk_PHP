CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'tutor', 'admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS tickets ( 
    id INT AUTO8INCREMENT PRIMARY KEY, 
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'closed') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    student_id INT NOT NULL,
    tutor_id INT DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (tutor_id) REFERENCES users(id)
    
)

INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@example.com', 'admin', 'admin');


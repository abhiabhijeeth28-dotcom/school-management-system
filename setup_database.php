<?php
include 'config/db.php';

// SQL queries to create tables
$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'teacher', 'student') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "students_details" => "CREATE TABLE IF NOT EXISTS students_details (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        class VARCHAR(50) NOT NULL,
        roll_no VARCHAR(50) NOT NULL,
        dob DATE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",
    "subjects" => "CREATE TABLE IF NOT EXISTS subjects (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        code VARCHAR(50) NOT NULL,
        teacher_id INT(11),
        FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL
    )",
    "attendance" => "CREATE TABLE IF NOT EXISTS attendance (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        student_id INT(11) NOT NULL,
        date DATE NOT NULL,
        status ENUM('Present', 'Absent', 'Late') NOT NULL,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
    )",
    "marks" => "CREATE TABLE IF NOT EXISTS marks (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        student_id INT(11) NOT NULL,
        subject_id INT(11) NOT NULL,
        marks_obtained DECIMAL(5,2) NOT NULL,
        total_marks INT(11) NOT NULL,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table $name created successfully.<br>";
    } else {
        echo "Error creating table $name: " . $conn->error . "<br>";
    }
}

// Check if admin exists, if not create default admin
$sql = "SELECT * FROM users WHERE role='admin' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO users (name, email, password, role) VALUES ('Administrator', 'admin@school.com', '$password', 'admin')";
    if ($conn->query($insert_admin) === TRUE) {
        echo "Default admin user created (admin@school.com / admin123).<br>";
    } else {
        echo "Error creating admin user: " . $conn->error . "<br>";
    }
} else {
    echo "Admin user already exists.<br>";
}

$conn->close();
?>

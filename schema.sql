-- ForeSITE Learning System Database Schema
-- Based on Adaptive Learning System Architecture

-- Drop database if exists
DROP DATABASE IF EXISTS foresite_db;
CREATE DATABASE foresite_db;
USE foresite_db;

-- User roles table
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name ENUM('student', 'faculty', 'admin') NOT NULL,
    description VARCHAR(255)
);

-- Users table (shared by students, faculty, and admins)
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Learning styles table
CREATE TABLE learning_styles (
    style_id INT PRIMARY KEY AUTO_INCREMENT,
    style_name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Student profiles table
CREATE TABLE student_profiles (
    profile_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    dominant_learning_style_id INT,
    profile_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (dominant_learning_style_id) REFERENCES learning_styles(style_id)
);

-- VARK questionnaire responses
CREATE TABLE questionnaire_responses (
    response_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    answer TEXT NOT NULL,
    response_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Courses table
CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (created_by) REFERENCES users(user_id)
);

-- Modules table
CREATE TABLE modules (
    module_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    sequence_order INT NOT NULL,
    prerequisite_module_id INT NULL,
    passing_score INT DEFAULT 75,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (prerequisite_module_id) REFERENCES modules(module_id)
);

-- Module content table
CREATE TABLE module_contents (
    content_id INT PRIMARY KEY AUTO_INCREMENT,
    module_id INT NOT NULL,
    content_type ENUM('text', 'video', 'quiz', 'assignment', 'discussion') NOT NULL,
    content_title VARCHAR(100) NOT NULL,
    content_data TEXT NOT NULL,
    learning_style_id INT NULL,
    sequence_order INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(module_id),
    FOREIGN KEY (learning_style_id) REFERENCES learning_styles(style_id)
);

-- Student enrollment in courses
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completion_status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    completion_date TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY unique_enrollment (user_id, course_id)
);

-- Student progress in modules
CREATE TABLE module_progress (
    progress_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    module_id INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (module_id) REFERENCES modules(module_id),
    UNIQUE KEY unique_progress (user_id, module_id)
);

-- Assessments table
CREATE TABLE assessments (
    assessment_id INT PRIMARY KEY AUTO_INCREMENT,
    module_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    passing_score INT DEFAULT 75,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(module_id)
);

-- Assessment questions
CREATE TABLE assessment_questions (
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    assessment_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer', 'essay') NOT NULL,
    points INT DEFAULT 1,
    FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id)
);

-- Student assessment attempts
CREATE TABLE assessment_attempts (
    attempt_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    assessment_id INT NOT NULL,
    score INT NOT NULL,
    passed BOOLEAN DEFAULT FALSE,
    started_at TIMESTAMP NOT NULL,
    submitted_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id)
);

-- Certificates table
CREATE TABLE certificates (
    certificate_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    certificate_url VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY unique_certificate (user_id, course_id)
);

-- Faculty feedback table
CREATE TABLE feedback (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    student_id INT NOT NULL,
    module_id INT NOT NULL,
    feedback_text TEXT NOT NULL,
    rating INT CHECK (rating BETWEEN 0 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES users(user_id),
    FOREIGN KEY (student_id) REFERENCES users(user_id),
    FOREIGN KEY (module_id) REFERENCES modules(module_id)
);

-- System log for monitoring and auditing
CREATE TABLE system_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    action_type VARCHAR(50) NOT NULL,
    action_details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert default roles
INSERT INTO roles (role_name, description) VALUES
('student', 'Regular student user'),
('faculty', 'Faculty member with teaching privileges'),
('admin', 'System administrator with full access');

-- Insert default learning styles based on VARK model
INSERT INTO learning_styles (style_name, description) VALUES
('visual', 'Preference for visual information like charts, graphs, and diagrams'),
('auditory', 'Preference for spoken or heard information'),
('reading/writing', 'Preference for information displayed as words'),
('kinesthetic', 'Preference for learning through experience and practice');

-- Insert admin user (password should be properly hashed in production)
INSERT INTO users (role_id, email, password, first_name, last_name) VALUES
(3, 'admin@foresite.com', 'admin_password_hash', 'System', 'Administrator'); 
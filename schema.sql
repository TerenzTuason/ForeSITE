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

-- Laravel sessions table for database session driver
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
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

-- Courses table
CREATE TABLE courses (
	course_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    objectives JSON NOT NULL,
    structure JSON NOT NULL,
    learning_style_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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

-- Student assessment results
CREATE TABLE assessment_results (
    result_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(200),
    last_name VARCHAR(200),
    department VARCHAR(200),
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    answers JSON NOT NULL,
    result JSON NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
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
INSERT INTO learning_styles (style_name) VALUES
('activist'),
('reflector'),
('theorist'),
('pragmatist');

-- Insert default courses
INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Applied Strategic Foresight: Practical Tools for Future-Ready Decision Making',
  'This course provides a comprehensive overview of strategic foresight tools and techniques, including scenario planning, driver mapping, and SWOT analysis. It equips learners with the skills to effectively navigate complex decision-making environments and prepare for future challenges.',
  '[
    "Apply futures thinking tools to real policy and business contexts using scenario planning and drivers analysis.",
    "Design and implement future-oriented project frameworks grounded in practical foresight pathways.",
    "Gather, synthesize, and apply intelligence from horizon scanning, expert input, and systems mapping.",
    "Develop, test, and refine strategies using scenario building, policy stress-testing, backcasting, and SWOT analysis.",
    "Communicate foresight insights through roadmaps, visioning sessions, and stakeholder-focused outputs.",
    "Evaluate the relevance and impact of foresight tools for improving decision quality and organizational resilience."
  ]',
  '[
    {
      "module": 1,
      "title": "Introduction to Futures Thinking",
      "focus": "Use of drivers, scenarios, and the Three Horizons model for identifying emerging challenges and shaping policy responses"
    },
    {
      "module": 2,
      "title": "Futures Process Design",
      "focus": "Building projects with clear aims, stakeholder engagement, and right-sized scope for effective foresight"
    },
    {
      "module": 3,
      "title": "Pathways for Business and Policy Needs",
      "focus": "Selecting and sequencing tools for policy and business goals using structured foresight pathways"
    },
    {
      "module": 4,
      "title": "Gathering Strategic Intelligence",
      "focus": "Horizon Scanning, 7 Questions, and expert interviews to collect actionable future insights"
    },
    {
      "module": 5,
      "title": "Exploring the Dynamics of Change",
      "focus": "Applying PESTLE-based driver mapping and Axes of Uncertainty for systems-focused foresight"
    },
    {
      "module": 6,
      "title": "Scenarios, Visioning, and Future Descriptions",
      "focus": "Scenario matrix construction, visioning exercises, and strategic use of SWOT for future contexts"
    },
    {
      "module": 7,
      "title": "Developing & Testing Policy and Strategy",
      "focus": "Policy Stress-Testing, Backcasting, and Roadmapping for robust, testable decision paths"
    },
    {
      "module": 8,
      "title": "Strategic Planning with Narrative Thinking",
      "focus": "Integrating future-focused storytelling with design thinking and stakeholder co-creation"
    },
    {
      "module": 9,
      "title": "Evaluating Tools for Impact and Adaptability",
      "focus": "SWOT analysis and iterative review of foresight tools for continuous improvement and relevance"
    },
    {
      "module": 10,
      "title": "Capstone: Real-World Foresight Application",
      "focus": "Final synthesis project applying course tools to a live issue, such as climate policy, AI regulation, or smart cities"
    }
  ]',
  1
);
INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Strategic Futures Thinking: Systems, Models, and Policy Design',
  'This course provides a comprehensive overview of strategic foresight tools and techniques, including scenario planning, driver mapping, and SWOT analysis. It equips learners with the skills to effectively navigate complex decision-making environments and prepare for future challenges.',
  '[
    "Analyze complex systems using formal models (e.g., Three Horizons Framework, PESTLE, Axes of Uncertainty).",
    "Evaluate and generalize key principles from Futures Thinking applicable across various policy and strategic domains.",
    "Design structured, evidence-informed futures processes suited to diverse contexts and stakeholders.",
    "Apply critical comparison and theoretical reasoning to assess Futures Thinking tools (e.g., Scenario Planning, SWOT, Delphi Method).",
    "Develop robust, adaptable policy strategies using backcasting, roadmapping, and policy stress-testing.",
    "Critically assess the effectiveness and impact of futures tools across process, output, and influence dimensions."
  ]',
  '[
    {
      "module": 1,
      "title": "Introduction to Futures Thinking",
      "focus": "Formal models (e.g., Three Horizons), strategic preparedness, and analytical comparison"
    },
    {
      "module": 2,
      "title": "Futures Process Design",
      "focus": "Structuring foresight using purpose-driven design, stakeholder mapping, and tool selection"
    },
    {
      "module": 3,
      "title": "Pathways to Meet Business Needs",
      "focus": "Matching foresight pathways to strategic goals through comparative analysis"
    },
    {
      "module": 4,
      "title": "Gathering Intelligence About the Future",
      "focus": "Horizon Scanning, 7 Questions, Delphi Method — methods for rigorous evidence collection"
    },
    {
      "module": 5,
      "title": "Exploring the Dynamics of Change",
      "focus": "Driver Mapping and Axes of Uncertainty using systems thinking frameworks (e.g., PESTLE)"
    },
    {
      "module": 6,
      "title": "Describing Future Scenarios",
      "focus": "Scenario building, Visioning, and SWOT for structured foresight narratives"
    },
    {
      "module": 7,
      "title": "Developing & Testing Strategy",
      "focus": "Strategy tools: Policy Stress-Testing, Backcasting, and Roadmapping for robust planning"
    },
    {
      "module": 8,
      "title": "Evaluating Futures Tools",
      "focus": "Post-analysis of tool effectiveness across process, outcomes, and policy impact"
    }
  ]',
  2
);
INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Strategic Futures Thinking: Systems, Models, and Policy Design',
  'This course provides a comprehensive overview of strategic foresight tools and techniques, including scenario planning, driver mapping, and SWOT analysis. It equips learners with the skills to effectively navigate complex decision-making environments and prepare for future challenges.',
  '[
    "Analyze complex systems using formal models (e.g., Three Horizons Framework, PESTLE, Axes of Uncertainty).",
    "Evaluate and generalize key principles from Futures Thinking applicable across various policy and strategic domains.",
    "Design structured, evidence-informed futures processes suited to diverse contexts and stakeholders.",
    "Apply critical comparison and theoretical reasoning to assess Futures Thinking tools (e.g., Scenario Planning, SWOT, Delphi Method).",
    "Develop robust, adaptable policy strategies using backcasting, roadmapping, and policy stress-testing.",
    "Critically assess the effectiveness and impact of futures tools across process, output, and influence dimensions."
  ]',
  '[
    {
      "module": 1,
      "title": "Introduction to Futures Thinking",
      "focus": "Formal models (e.g., Three Horizons), strategic preparedness, and analytical comparison"
    },
    {
      "module": 2,
      "title": "Futures Process Design",
      "focus": "Structuring foresight using purpose-driven design, stakeholder mapping, and tool selection"
    },
    {
      "module": 3,
      "title": "Pathways to Meet Business Needs",
      "focus": "Matching foresight pathways to strategic goals through comparative analysis"
    },
    {
      "module": 4,
      "title": "Gathering Intelligence About the Future",
      "focus": "Horizon Scanning, 7 Questions, Delphi Method — methods for rigorous evidence collection"
    },
    {
      "module": 5,
      "title": "Exploring the Dynamics of Change",
      "focus": "Driver Mapping and Axes of Uncertainty using systems thinking frameworks (e.g., PESTLE)"
    },
    {
      "module": 6,
      "title": "Describing Future Scenarios",
      "focus": "Scenario building, Visioning, and SWOT for structured foresight narratives"
    },
    {
      "module": 7,
      "title": "Developing & Testing Strategy",
      "focus": "Strategy tools: Policy Stress-Testing, Backcasting, and Roadmapping for robust planning"
    },
    {
      "module": 8,
      "title": "Evaluating Futures Tools",
      "focus": "Post-analysis of tool effectiveness across process, outcomes, and policy impact"
    }
  ]',
  3
);
INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Futures in Reflection: Designing Thoughtful Strategies for Uncertain Times',
  'This course provides a comprehensive overview of strategic foresight tools and techniques, including scenario planning, driver mapping, and SWOT analysis. It equips learners with the skills to effectively navigate complex decision-making environments and prepare for future challenges.',
  '[
    "Define the principles of Futures Thinking and explain its relevance in policy and strategic planning.",
    "Reflect on trends, changes, and drivers influencing the future across sectors.",
    "Design thoughtful foresight processes tailored to specific business or policy needs.",
    "Apply intelligence-gathering and systems analysis tools like Horizon Scanning, Driver Mapping, and Delphi.",
    "Construct plausible future scenarios and vision statements using structured, reflective techniques.",
    "Test strategies through methods such as stress-testing, wind-tunneling, backcasting, and SWOT analysis.",
    "Evaluate foresight tools by analyzing their process, outcomes, and influence with depth and nuance."
  ]',
  '[
    {
      "module": 1,
      "title": "Introduction to Futures Thinking",
      "focus": "Foundations of foresight, reflective observation, scenario warm-up"
    },
    {
      "module": 2,
      "title": "Futures Process Design",
      "focus": "Planning foresight activities through purpose-driven and inclusive design"
    },
    {
      "module": 3,
      "title": "Pathways for Business Needs",
      "focus": "Understanding and comparing seven strategic foresight pathways"
    },
    {
      "module": 4,
      "title": "Tools for Gathering Intelligence",
      "focus": "Horizon Scanning, Delphi, 7 Questions, and Issues Paper use and alignment"
    },
    {
      "module": 5,
      "title": "Exploring the Dynamics of Change",
      "focus": "Driver Mapping and Axes of Uncertainty for systems-focused foresight"
    },
    {
      "module": 6,
      "title": "Describing Future Possibilities",
      "focus": "Scenario creation, Visioning preferred futures, and comparing narrative paths"
    },
    {
      "module": 7,
      "title": "Developing & Testing Strategy",
      "focus": "Backcasting, Policy Stress-Testing, and Wind-Tunneling"
    },
    {
      "module": 8,
      "title": "Evaluating Foresight Tools",
      "focus": "Post-use reflection on effectiveness, engagement, and strategic alignment"
    }
  ]',
  4
);

-- Insert admin user (password should be properly hashed in production)
INSERT INTO users (role_id, email, password, first_name, last_name) VALUES
(3, 'admin@foresite.com', '$2y$10$nHipa0I9/v/SEy4HrI6mxOnBJ.a4kXnhgxyMC.2WaJnELzpikNiUi', 'System', 'Administrator'); 
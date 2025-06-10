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
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE
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

-- Insert default learning styles based on VARK model
INSERT INTO learning_styles (style_name) VALUES
('activist'),
('reflector'),
('theorist'),
('pragmatist');

-- Student profiles table
CREATE TABLE student_profiles (
    profile_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    dominant_learning_style_id INT,
    profile_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (dominant_learning_style_id) REFERENCES learning_styles(style_id) ON DELETE CASCADE
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
    FOREIGN KEY (learning_style_id) REFERENCES learning_styles(style_id) ON DELETE CASCADE
);

-- Insert default courses
INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Applied Strategic Foresight: Practical Tools for Future-Ready Decision Making',
  'This course introduces practical foresight methods for shaping long-term strategies. It emphasizes anticipating and responding to change through scenario planning, systems thinking, and futures analysis. Participants will learn how to apply foresight to real-world issues, exploring trends, drivers, and emerging signals to strengthen organizational readiness and decision-making.',
  '[
    "Recognize and analyze change drivers, signals, and long-term trends shaping strategic environments.",
    "Apply structured frameworks like the Three Horizons and Futures Wheel to explore evolving futures.",
    "Design foresight-informed strategies aligned with emerging challenges and stakeholder goals.",
    "Use driver mapping, horizon scanning, and scenario development to build future-ready plans.",
    "Incorporate deeper narrative techniques like CLA to uncover assumptions and shift perspectives.",
    "Integrate foresight tools into the policy cycle and business planning to improve adaptive capacity."
  ]',
  '[
    {
      "module": 1,
      "title": "Foundations of Futures Thinking",
      "focus": "Develop an understanding of futures literacy through drivers analysis, horizon scanning, and strategic framing across time horizons."
    },
    {
      "module": 2,
      "title": "Mapping Change and Building Insight",
      "focus": "Apply tools such as PESTLE and Futures Wheel to identify critical forces of change and visualize their impacts."
    },
    {
      "module": 3,
      "title": "Creating Strategic Pathways",
      "focus": "Explore alternative futures using scenario planning, the Futures Triangle, and Causal Layered Analysis to inform resilient strategies."
    }
  ]',
  1
);

INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Applied Strategic Foresight: Practical Tools for Future-Ready Decision Making',
  'This course introduces practical foresight methods for shaping long-term strategies. It emphasizes anticipating and responding to change through scenario planning, systems thinking, and futures analysis. Participants will learn how to apply foresight to real-world issues, exploring trends, drivers, and emerging signals to strengthen organizational readiness and decision-making.',
  '[
    "Recognize and analyze change drivers, signals, and long-term trends shaping strategic environments.",
    "Apply structured frameworks like the Three Horizons and Futures Wheel to explore evolving futures.",
    "Design foresight-informed strategies aligned with emerging challenges and stakeholder goals.",
    "Use driver mapping, horizon scanning, and scenario development to build future-ready plans.",
    "Incorporate deeper narrative techniques like CLA to uncover assumptions and shift perspectives.",
    "Integrate foresight tools into the policy cycle and business planning to improve adaptive capacity."
  ]',
  '[
    {
      "module": 1,
      "title": "Foundations of Futures Thinking",
      "focus": "Develop an understanding of futures literacy through drivers analysis, horizon scanning, and strategic framing across time horizons."
    },
    {
      "module": 2,
      "title": "Mapping Change and Building Insight",
      "focus": "Apply tools such as PESTLE and Futures Wheel to identify critical forces of change and visualize their impacts."
    },
    {
      "module": 3,
      "title": "Creating Strategic Pathways",
      "focus": "Explore alternative futures using scenario planning, the Futures Triangle, and Causal Layered Analysis to inform resilient strategies."
    }
  ]',
  2
);

INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Applied Strategic Foresight: Practical Tools for Future-Ready Decision Making',
  'This course introduces practical foresight methods for shaping long-term strategies. It emphasizes anticipating and responding to change through scenario planning, systems thinking, and futures analysis. Participants will learn how to apply foresight to real-world issues, exploring trends, drivers, and emerging signals to strengthen organizational readiness and decision-making.',
  '[
    "Recognize and analyze change drivers, signals, and long-term trends shaping strategic environments.",
    "Apply structured frameworks like the Three Horizons and Futures Wheel to explore evolving futures.",
    "Design foresight-informed strategies aligned with emerging challenges and stakeholder goals.",
    "Use driver mapping, horizon scanning, and scenario development to build future-ready plans.",
    "Incorporate deeper narrative techniques like CLA to uncover assumptions and shift perspectives.",
    "Integrate foresight tools into the policy cycle and business planning to improve adaptive capacity."
  ]',
  '[
    {
      "module": 1,
      "title": "Foundations of Futures Thinking",
      "focus": "Develop an understanding of futures literacy through drivers analysis, horizon scanning, and strategic framing across time horizons."
    },
    {
      "module": 2,
      "title": "Mapping Change and Building Insight",
      "focus": "Apply tools such as PESTLE and Futures Wheel to identify critical forces of change and visualize their impacts."
    },
    {
      "module": 3,
      "title": "Creating Strategic Pathways",
      "focus": "Explore alternative futures using scenario planning, the Futures Triangle, and Causal Layered Analysis to inform resilient strategies."
    }
  ]',
  3
);

INSERT INTO courses (name, description, objectives, structure, learning_style_id) VALUES (
  'Applied Strategic Foresight: Practical Tools for Future-Ready Decision Making',
  'This course introduces practical foresight methods for shaping long-term strategies. It emphasizes anticipating and responding to change through scenario planning, systems thinking, and futures analysis. Participants will learn how to apply foresight to real-world issues, exploring trends, drivers, and emerging signals to strengthen organizational readiness and decision-making.',
  '[
    "Recognize and analyze change drivers, signals, and long-term trends shaping strategic environments.",
    "Apply structured frameworks like the Three Horizons and Futures Wheel to explore evolving futures.",
    "Design foresight-informed strategies aligned with emerging challenges and stakeholder goals.",
    "Use driver mapping, horizon scanning, and scenario development to build future-ready plans.",
    "Incorporate deeper narrative techniques like CLA to uncover assumptions and shift perspectives.",
    "Integrate foresight tools into the policy cycle and business planning to improve adaptive capacity."
  ]',
  '[
    {
      "module": 1,
      "title": "Foundations of Futures Thinking",
      "focus": "Develop an understanding of futures literacy through drivers analysis, horizon scanning, and strategic framing across time horizons."
    },
    {
      "module": 2,
      "title": "Mapping Change and Building Insight",
      "focus": "Apply tools such as PESTLE and Futures Wheel to identify critical forces of change and visualize their impacts."
    },
    {
      "module": 3,
      "title": "Creating Strategic Pathways",
      "focus": "Explore alternative futures using scenario planning, the Futures Triangle, and Causal Layered Analysis to inform resilient strategies."
    }
  ]',
  4
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
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

-- Student enrollment in courses
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    assessment_result_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completion_status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    completion_date TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (assessment_result_id) REFERENCES assessment_results(result_id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id)
);

-- Lesson screen for each course module
CREATE TABLE lesson_screens (
    lesson_screen_id INT PRIMARY KEY AUTO_INCREMENT,
    screen_number VARCHAR(50) NOT NULL,
    screen_title TEXT NULL,
    screen_description TEXT NULL,
    screen_content JSON NULL,
    screen_url VARCHAR(255) NULL,
    screen_duration TEXT NULL
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1-video',
  'Introduction to Futures Thinking',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1748185804/1_Introduction_to_Futures_Thinking_egkgxg.mp4',
  '3 minutes 27 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.1',
  'What is Futures Thinking?',
  'A strategic mindset to explore change and build resilient policy',
  '[
        "Futures Thinking helps identify long-term issues and uncertainties in a policy area.",
        "It does not predict the future — it helps prepare for different possibilities.",
        "Supports resilient policy design using tools like:",
        [
            "Scenario planning",
            "Change driver analysis",
            "Strategic workshops"
        ],
        "It is flexible — it can be adapted for quick brainstorming or in-depth strategy development.",
        "Helps teams:",
        [
            "Understand trends",
            "Make better-informed decisions",
            "Mobilize stakeholder action"
        ]
    ]',
    null,
    '15 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.2-video',
  'Looking Ahead: The Three Horizons Model',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1749389838/1.2_Looking_Ahead_The_Three_Horizons_Model_aemjrp.mp4',
  '6 minutes 4 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.2',
  'Looking Ahead: The Three Horizons Model',
  'A tool for thinking across short, medium, and long-term futures.',
  '[
        "The Three Horizons Framework breaks change into three phases:",
        [
            "Horizon 1 (H1): Present systems and issues we manage today",
            "Horizon 2 (H2): Emerging changes and innovations disrupting the current system",
            "Horizon 3 (H3): Long-term transformations and future possibilities"
        ],
        "Policymakers must:",
        [
            "Respond to current needs (H1)",
            "Prepare for near-term shifts (H2)",
            "Explore long-term visions (H3)"
        ],
        "Futures tools focus most on H2 and H3, helping policymakers stay ahead of change."
    ]',
    'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186077/1.2_The_Three_Horizons_Model_b7t0si.png',
    '12 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.3-video',
  'Identifying Change Drivers',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1749389385/1.3_Identifying_Change_Drivers_dvzt5f.mp4',
  '2 minutes 44 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.3',
  'Identifying Change Drivers',
  'Understanding what shapes the future.',
  '[
        "Change drivers are trends or forces influencing how the future develops.",
        "Often categorized using PESTLE:",
        [
            "Political",
            "Economic",
            "Social",
            "Technological",
            "Legal",
            "Environmental"
        ],
        "Look beyond current events—important drivers often emerge outside the usual policy space.",
        "Tip: It’s better to collect too many drivers than miss a critical one.",
        "Driver identification is the foundation of most futures work."
    ]',
    null,
    '14 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.3.1-video',
  'What is PESTLE Analysis?',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1749389594/1.3.1_What_is_PESTLE_Analysis_iij5o2.mp4',
  '4 minutes 4 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.4-video',
  'Identifying Weak Signals',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1749389236/1.4_Identifying_Weak_Signals_lo6qbb.mp4',
  '3 minutes 5 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.4',
  'Identifying Weak Signals',
  'Spotting early signs of future change.',
  '[
        "Weak signals are early hints of significant future changes.",
        "They are often unclear, rare, or not well understood yet.",
        "Horizon Scanning helps identify these subtle trends.",
        "Even if there’s no solid data, trust your intuition — a weak signal might grow into a major shift.",
        "Example: An unusual news article today could indicate a big future trend tomorrow."
    ]',
    null,
    '12 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.5-video',
  'Linking Futures Thinking to the Policy Cycle',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1749389050/1.5_Linking_Futures_Thinking_to_the_Policy_Cycle_pqfozn.mp4',
  '1 minute 54 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '1.5',
  'Linking Futures Thinking to the Policy Cycle',
  'Where foresight fits in policymaking.',
  '[
        "The policy cycle typically includes:",
        [
            "Formulation (where Futures Thinking is most valuable)",
            "Implementation",
            "Monitoring",
            "Evaluation",
            "Modification"
        ],
        "Futures Thinking supports early-stage strategy by:",
        [
            "Providing fresh insight and long-term perspective",
            "Highlighting uncertainties and trade-offs"
        ],
        "Key to impact: Involve policy teams and decision-makers early in the futures process.",
        "Use futures work to bridge long-term visioning with real policy goals."
    ]',
    'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186077/1.5_Illustrative_Policy_Cycle_dgw7i8.png',
    '15 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '2.1-video',
  'Driver Mapping',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1748185724/2.1_Driver_Mapping_lmspxt.mp4',
  '56 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '2.1',
  'Driver Mapping',
  'Drivers are influential forces of changes that are currently shaping or have the capacity to shape or transform a system. Driver mapping is one of the most important tools in foresight. It helps to identify the most influential forces of change in a system.',
  '[
        "To find these drivers, we often use tools like the STEEP or PESTLE frameworks. Think of these frameworks as ways to get a \'big picture\' view of everything influencing a situation. They help us explore many different types of drivers by sorting them into categories:",
        [
            "STEEP looks at Social, Technological, Economic, Ecological (or Environmental), and Political drivers.",
            "PESTLE includes all of those, plus an extra category for Legal drivers."
        ]
    ]',
    'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186077/2.1_PESTLE_i4quij.png',
    '18 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '2.1.1',
  'Why Use Driver Mapping?',
  'Understanding the core reasons for identifying and analyzing key forces of change.',
  '[
        "When we use Drive Mapping, we\'re aiming to achieve a few important things:",
        [
            "Our first goal is to pinpoint all the major forces (we call them \'drivers\') that are truly shaping the system or topic we\'re exploring. This helps us see the full picture of what\'s influencing the future.",
            "Next, from all those identified drivers, we figure out which ones are the most critical or influential. This helps us focus our efforts on the \'game-changers\' that will have the biggest impact.",
            "Finally, this core set of important drivers becomes the essential starting point for all our deeper futures analysis. They are the key ingredients we use to explore different possible futures and build scenarios."
        ]
    ]',
    null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '2.1.2',
  'How to do Driver Mapping?',
  'Drive Mapping is a practical exercise to figure out what forces are really shaping your topic of interest. Think of it like a detective`s checklist to understand the big picture!',
  '[
        "How to Drive Mapping:",
        [
            "Define Your Focus",
            ["Clearly state your topic, boundaries, and time frame."],
            "Choose Your Framework",
            ["Pick a lens like STEEP or PESTLE to guide your thinking."],
            "Brainstorm Drivers",
            ["List all possible influences you can think of."],
            "Organize & Refine",
            ["Group similar drivers, remove duplicates, and tidy up your list."],
            "Filter External Drivers",
            ["Separate out drivers you can\'t influence, focusing on what you can act on."],
            "Finalize Your List",
            ["Ensure you have a balanced number of critical drivers – not too many, not too few."]
        ]   
    ]',
    null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '2.2-video',
  'Horizon Scanning',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1748185779/2.2_Horizon_Scanning_yhuel4.mp4',
  '2 minutes 4 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '2.2',
  'Horizon scanning',
  'Horizon scanning is a foresight process focused on identifying and collating early warning signs of change, or emerging signals that may have significant impacts when they develop.',
  '[
        "Scanning for signals of change",
        ["Actively looking for early clues about what might happen in the future. We\'re searching for potential problems, new chances, and things that are likely to develop."],
        "What is a \'Signal\'?",
        [
            "It\'s the very first hint of a new event, a local trend, or an innovative idea from an organization.",
            "These signals have the power to cause big changes or even completely transform things.",
            "They can grow and spread, affecting larger areas or even entire regions."
        ],
        "What is a \'Weak Signal\'?",
        [
            "This is a clue that\'s really hard to notice or very subtle.",
            "The information we have about it is often incomplete or messy.",
            "We\'re not sure how it will develop in the future.",
            "Even though they are faint and uncertain, weak signals are important to notice and keep an eye on because they can turn into strong, clear trends later on."
        ]
  ]',
  'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186078/2.2_Emerging_Issue_Analysis_blsa8e.png',
  '15 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '2.2.1',
  'Why Use Horizon Scanning?',
  'Discover how Horizon Scanning helps you spot future risks and opportunities early, preparing you for what`s ahead.',
  '[
        "Horizon Scanning is a vital tool because it helps us to:",
        [
            "It allows us to systematically look for potential problems, exciting new chances, and uncertainties that could affect our current plans or future direction.",
            "It serves as a baseline analysis for our planning and program design. By continuously watching for identified risks and signals, it acts like an \'early warning system\' to alert us to changes.",
            "It helps us find specific ways to strengthen our risk analysis and weave future insights from scanning into our existing processes, making them more robust."
        ]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '2.2.2',
  'How to do Horizon Scanning?',
  'Horizon Scanning helps you spot early signs of future changes so you can prepare. Think of it as looking ahead for clues!',
  '[
        "The 5 Steps:",
        [
            "Collect Signals",
            ["Look for tiny hints of change in news, social media, research, etc. (using categories like Social, Tech, Economic, Environment, Political, Values). Note down what it is, where it\'s from, and its possible impact."],
            "Collate Signals",
            ["Gather all your collected signals into one organized place, like a simple list or spreadsheet."],
            "Interpret Signals",
            ["Figure out what your signals mean. Use simple ratings (like 1-5) for how likely they are and how big their impact could be, then visualize them to see patterns."],
            "Make Sense of Signals",
            ["Discuss how these signals might change things in the future. This helps you make better decisions today, without trying to predict exactly what will happen."],
            "Integrate Insights",
            ["Use what you\'ve learned from the signals to improve your current plans and strategies, making them more ready for the future."]
        ]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '2.3-video',
  'Understanding Weak Signals and Trends',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1749388239/2.3_Understanding_Weak_Signals_and_Trends_wjvz6k.mp4',
  '9 minutes 42 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '2.4-video',
  'Trends Identification and Analysis',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1748185774/2.3_Trends_Identification_and_Analysis_zfvxos.mp4',
  '9 minutes 34 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '2.3',
  'Trends Identification and Analysis',
  'Understanding the cascading impacts of change using structured brainstorming and visualization.',
  '[
        "The Futures Wheel (also called the Implications Wheel) is a simple but powerful way to think about the future.",
        "It helps us map out all the possible effects that a major change or event could have.",
        "Think of it like dropping a pebble in a pond:",
        [
            "The first ripple is the direct impact.",
            "Then, those ripples create new, smaller ripples, which are the indirect impacts (or \'second and third-order effects\')."
        ],
        "This tool helps us see how one initial change “seed of change” can set off a chain reaction of consequences, both good and bad, that keep going and going."
  ]',
  'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186078/2.3_Implications_Analysis_Considerations_fzxlyt.png',
  '18 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '2.3.1',
  'How to do Futures Wheel Analysis?',
  'Explore the ripple effects of a change! The Futures Wheel helps you map out all the possible consequences of a trend or event.',
  '[
        "Choose Your Change",
        ["Pick the main trend, issue, or event you want to explore (this goes in the middle)."],
        "First Impacts",
        ["What are the direct consequences? Write these in a circle around your change."],
        "Second Impacts",
        ["For each direct impact, what else happens because of it? Add these to an outer circle"],
        "Keep Expanding",
        ["Continue thinking about impacts of impacts for as long as it\'s useful."],
        "Think Strategy",
        ["What do all these potential ripples mean for your plans or decisions?"],
        "Share & Learn",
        ["Discuss your findings with others to see different perspectives."]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '3.1-video',
  'Causal Layered Analysis',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1748185859/3.1_Causal_Layered_Analysis_u1pfwq.mp4',
  '19 minutes 11 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '3.1',
  'Causal Layered Analysis',
  'It is a powerful tool that helps us understand complex issues by looking beyond the surface. It`s used to reveal hidden beliefs and perspectives that shape our current reality, and then to help create new stories that can lead to a desired future.',
  '[
        "The Four Levels of CLA:",
        [
            "Litany (The Surface Story):",
            ["This is what you see and hear every day – the headlines, the obvious facts, the immediate problems."]
        ],
        [
            "Systemic Causes (The Structure Behind It):",
            ["Just below the surface, this level looks at the organized systems, structures, and historical reasons that cause the surface problem. It\'s about how things are set up."]
        ],
        [
            "Worldviews (The Beliefs That Shape It):",
            ["Going deeper, this level explores the mindsets, cultural beliefs, values, and assumptions that influence the systems and the problem. It\'s about what people believe and how they think about the issue."]
        ],
        [
            "Metaphors and Myths (The Deepest Stories):",
            ["This is the very deepest level, uncovering the powerful, often unspoken, stories and fundamental ideas that shape our worldviews. These are the unconscious narratives that truly influence how we see reality."]
        ]
  ]',
  'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186078/3.1_CLA_Levels_of_Analysis_ooqitq.png',
  '15 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '3.1.1',
  'Why Use Causal Layered Analysis?',
  'Discover how Causal Layered Analysis helps you dig deeper into a problem, finding hidden beliefs and creating clearer paths forward.',
  '[
      "Causal Layered Analysis (CLA) is a powerful tool used for several key reasons:",
      [
          "It helps you unpack any issue or topic to understand it at a much profounder level than just the surface.",
          "It lets you map out and understand the various, sometimes conflicting, ideas people have about the future.",
          "It helps you break down a desired future (like a vision or a big goal) to understand its core parts and how to build it.",
          "It allows you to grasp the different stories or narratives that people tell about a problem or the future.",
          "It helps you develop solid, comprehensive strategies that address issues from their roots.",
          "It assists in clearly defining and working towards a preferred vision of the future you want to create."
      ]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '3.1.2',
  'How to do Causal Layered Analysis?',
  'Learn to dig deep into any issue by exploring its surface, systems, beliefs, and hidden stories.',
  '[
    "Causal Layered Analysis (CLA) helps you understand a problem or topic by looking at it through four different levels, like peeling an onion. First, pick an issue you want to explore (like a new plan, a problem, or a topic of interest):",
    "The 4 Levels of CLA:",
    [
      "The Litany (What You See & Hear)",
      [
        "Ask: What are the most obvious facts, news headlines, or everyday discussions about this issue? How would a reporter describe it simply?",
        "Think: This is the surface-level story."
      ],
      "The System (How Things Are Organized)",
      [
        "Ask: What structures, policies, or organized ways of doing things support this issue? Why does it exist this way? What kind of new systems would be needed to change it?",
        "Think: This is about the facts, data, and structures underneath the surface story."
      ],
      "The Worldview (What People Believe)",
      [
        "Ask: What are the deeper beliefs, values, or mindsets that shape how this issue is understood or how the system works? Are these beliefs shared, or are they different among various groups? If your plan succeeds, what new ways of thinking or new cultural values would emerge?",
        "Think: This explores the assumptions and core beliefs that drive behavior."
      ],
      "The Myth/Metaphor (The Deepest Stories)",
      [
        "Ask: What are the unspoken, powerful stories, narratives, or images (metaphors) that influence peoples worldviews about this issue? Is there a new story or a memorable metaphor that could help people understand or change things? What kind of mental shift would that new story require?",
        "Think: This is about the unconscious stories and archetypes that deeply shape our reality."
      ]
    ]
  ]',
  'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186079/3.1.2_Using_CLA_sfpy9p.png'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '3.2-video',
  'Futures Triangle',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1748185745/3.2_Futures_Triangle_l837ah.mp4',
  '2 minutes 59 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '3.2',
  'Futures Triangle',
  'Futures triangle is a futures method that helps us to map the present views of the future through three dimensions that are shaping it. The three dimensions are: the images (pull) of the future, the megatrends and drivers (push) of the present and the barriers (weight) of past/history.',
  JSON_ARRAY(
    'The Futures Triangle is a quick and easy way to understand how the future might play out. It`s built on three main forces constantly pushing and pulling on things:',
    JSON_ARRAY(
      'The "Pull" of the Future (Your Visions):',
      JSON_ARRAY(
        'This is about the images we have in our minds of what the future could or should be like.',
        'Think of it as the dreams, hopes, and even fears that push us forward.',
        'These visions can come from anywhere: books, movies, political goals, religious ideas, or even advertisements. They`re basically what people or groups strongly desire or dread for the future.'
      )
    ),
    'The "Push" of the Present (Current Trends):',
    JSON_ARRAY(
      'These are the ongoing trends and major forces happening right now that are actively shaping what`s coming next.',
      'Things like new technologies, economic changes, or shifts in how people live and interact.'
    ),
    'The "Weight" of the Past (Historical Barriers):',
    JSON_ARRAY(
      'This represents the old ways of doing things, traditions, established systems, and historical events that can slow down or block changes we want to see.',
      'It`s like a heavy anchor from history that makes it harder to move in a new direction.'
    ),
    'Putting It All Together:',
    JSON_ARRAY(
      'The Futures Triangle shows that these three forces are always interacting and changing.',
      'If one force gets stronger, it affects the others. For example, if the "Weight of the Past" gets heavier (meaning old ways become more stubborn), it might weaken the "Pull of the Future" or slow down the "Push of the Present."',
      'This push and pull changes where the future is likely to go.'
    )
  ),
  'https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186111/3.2_Futures_Triangle_kzo8sy.png',
  '18 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '3.2.1',
  'Why Use the Futures Triangle?',
  'Learn how this tool helps you understand the forces shaping the future – from the past, present, and your hopes for tomorrow.',
  JSON_ARRAY(
    'The Futures Triangle is a handy tool that helps us:',
    JSON_ARRAY(
      'Understand how the past, what`s happening now, and future possibilities all connect and influence each other.',
      'Discover various believable futures by looking at how the forces from the past, present, and your future visions interact.',
      'Use it as a foundation for creating detailed "what if" stories about the future.',
      'Develop more effective actions and strategies that consider these powerful forces.'
    )
  ),
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '3.2.2',
  'How to Use the Futures Triangle?',
  'Understand the forces shaping your future by exploring your visions, current trends, and past influences.',
  JSON_ARRAY(
    'The Futures Triangle helps you map out how different forces — your hopes for the future, what`s happening now, and lessons from the past — are all pushing and pulling on each other. It`s about understanding the big picture to see where things might go.',
    'Here`s how to use it, by exploring three key areas:',
    JSON_ARRAY(
      'The "Pull" of the Future (Your Hopes)',
      JSON_ARRAY(
        'What it is: Your vision for what you want the future to be.',
        'Ask yourself:',
        JSON_ARRAY('What`s your ideal future?'),
        'What resources help you get there?'
      ),
      'The "Push" of the Present (Today`s Trends)',
      JSON_ARRAY(
        'What it is: The forces and changes happening right now that are shaping tomorrow.',
        'Ask yourself:',
        JSON_ARRAY(
          'What big trends are happening?',
          'How are they moving us forward?'
        ),
        'What resources help you get there?'
      ),
      'The "Weight" of the Past (Old Influences)',
      JSON_ARRAY(
        'What it is: History, traditions, and old ways of doing things that might slow change.',
        'Ask yourself:',
        JSON_ARRAY(
          'What`s holding things back?',
          'Who benefits if nothing changes?'
        )
      )
    ),
    'By thinking through these questions for each of the three points of the triangle, you`ll start to see how they interact. This helps you understand the likely direction things are headed, imagine different possible futures, and develop clearer actions.'
  ),
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '3.3-video',
  'Scenario Planning',
  null,
  null,
  'https://res.cloudinary.com/dwn5t3o4j/video/upload/v1748185751/3.3_Scenario_Planning_viem5x.mp4',
  '2 minutes 15 seconds'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url, screen_duration) VALUES(
  '3.3',
  'Scenario Planning',
  'A scenario is a description of how the future may unfold based on an explicit, coherent and internally consistent set of plausible assumptions about key relationships among drivers of change.',
  JSON_ARRAY(
    'Scenario planning is a special tool that helps us imagine and prepare for different possible futures. It`s not about predicting one exact future, but rather creating several "what if" stories based on big trends and uncertainties we see today.',
    'A scenario is simply a detailed story about how the future could unfold. Each story is realistic, makes sense internally, and is based on clear ideas about how different changes might connect.',
    'Types of scenarios methods',
    JSON_ARRAY(
      '2x2 scenarios',
      'Organizational scenarios',
      'Integrated scenarios',
      'Manoa scenarios',
      'Transformative scenarios',
      'Change progression scenarios method'
    ),
    '1. 2x2 Scenarios Method',
    JSON_ARRAY(
        'Step 1: Identify the two most uncertain and impactful drivers (variables) relevant to your policy or issue. These are the axes of the 2x2 matrix.',
        'Step 2: Define the two critical uncertainties for each axis (e.g., technological advancement vs. regulatory constraints).',
        'Step 3: Create four scenarios by considering all possible combinations of the two variables. This will form your 2x2 matrix.',
        JSON_ARRAY(
            'Example: If your axes are "regulation" and "technology," your four scenarios could be:',
            JSON_ARRAY(
              '1. High regulation, High technology',
              '2. High regulation, Low technology',
              '3. Low regulation, High technology',
              '4. Low regulation, Low technology.'
            )
        ),
        'Step 4: Develop detailed narratives for each of the four scenarios and explore their impacts on your strategy or policy.'
    ),
    '2. Organizational Scenarios Method',
    JSON_ARRAY(
        'Step 1: Focus on specific organizational challenges, such as leadership, workforce trends, or market competition.',
        'Step 2: Identify key drivers within the organization that could affect its future, such as employee engagement or technological adoption.',
        'Step 3: Develop scenarios around how these organizational drivers might evolve, considering both internal and external factors.',
        JSON_ARRAY('Example: Scenario 1 could be "rapid technological innovation leads to restructuring," while Scenario 2 could be "slow technology adoption leads to stagnation in growth."'),
        'Step 4: Map out each scenario with a clear story and visual aids to highlight the impact on the organization`s future.'
    ),
    '3. Integrated Scenarios Method',
    JSON_ARRAY(
        'Step 1: Identify multiple systems or sectors that interact within your scenario planning (e.g., economy, environment, social systems).',
        'Step 2: Map out how these different systems might evolve based on key drivers and uncertainties.',
        'Step 3: Explore how changes in one system might impact others. The integrated scenario method helps you understand interdependencies between systems.',
        JSON_ARRAY('Example: Consider how a technological breakthrough in AI might affect the economy, labor markets, and ethical considerations.'),
        'Step 4: Create scenarios that reflect these integrated changes and their consequences across multiple systems.'
    ),
    '4. Manoa Scenarios Method',
    JSON_ARRAY(
        'Step 1: Select two key drivers that have high uncertainty but significant impact on the issue you are studying.',
        'Step 2: Explore the potential interactions between these drivers, including how they might collide in unexpected ways.',
        'Step 3: Develop scenarios based on how these drivers might influence each other.',
        JSON_ARRAY('Example: If the two drivers are "climate change" and "policy response," consider how changes in one could intensify or mitigate the effects of the other.'),
        'Step 4: Focus on surprises and unexpected outcomes to challenge conventional thinking and explore alternative futures.'
    ),
    '5. Transformative Scenarios Method',
    JSON_ARRAY(
        'Step 1: Identify the forces that drive transformational change in your field or sector.',
        'Step 2: Examine both the driving forces (e.g., technological advancements, cultural shifts) and the resisting forces (e.g., regulatory barriers, existing power structures).',
        'Step 3: Develop scenarios that show how transformational changes could unfold in different directions, considering the clash between driving and resisting forces.',
        JSON_ARRAY('Example: Scenario 1 could be "The rise of automation leads to mass job displacement," while Scenario 2 could be "Automation is embraced and creates new industries."'),
        'Step 4: Explore what actions would need to be taken to either harness or mitigate the impacts of transformational changes.'
    ),
    '6. Change Progression Scenarios Method',
    JSON_ARRAY(
        'Step 1: Identify a trend or phenomenon that is currently unfolding in your area of interest.',
        'Step 2: Track the potential progression of this trend over time, from its current state to various potential future states.',
        'Step 3: Develop multiple scenarios based on how the trend could evolve, considering both slow and rapid progressions.',
        JSON_ARRAY('Example: If the trend is "increased use of renewable energy," scenarios could range from "gradual adoption with government support" to "rapid transition due to technological breakthroughs."'),
        'Step 4: Define what milestones or signals could indicate whether the trend is progressing slowly or quickly, and adjust your strategies accordingly.'
    )
  ),
  null,
  '18 minutes'
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '3.3.1',
  'Why Use Scenario Planning?',
  'Learn how scenarios help you explore different possible futures, challenge your thinking, and prepare for anything.',
  JSON_ARRAY(
    'Using Scenarios is super helpful because it allows us to:',
    JSON_ARRAY(
      'It makes us think beyond what we expect to happen, by exploring many different ways a plan, policy, or strategy could unfold in the future.',
      'It helps us imagine how key issues, partners, and other people might act in various future situations.',
      'It`s a great way to create "what if" plans (contingency plans) for our programs, policies, and strategies, making us ready for different possible futures.'
    )
  ),
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  '3.3.2',
  'How to Build Scenarios?',
  'Learn to create different "what if" stories about the future to better understand and prepare for change.',
  JSON_ARRAY(
    'Define Your Focus & Time',
    JSON_ARRAY(
      'What issue are you exploring? (e.g., "The future of digital access.")',
      'How far into the future? Pick a specific time (e.g., 5, 10, or 20 years from now).'
    ),
    'Pick Your Key Drivers',
    JSON_ARRAY(
      'Identify the most important forces of change (drivers) that will shape your future.',
      'These drivers become your "critical uncertainties" (for a 2x2 scenario) or help explore "colliding impacts" (for a Manoa scenario).'
    ),
    'Build Your Scenarios',
    JSON_ARRAY(
      'For 2x2 Scenarios: Take your two most uncertain and impactful drivers. Imagine them as axes on a graph (like X and Y). This creates four unique future "corners" or stories.',
      'For Manoa Scenarios: Explore how your chosen drivers might collide or interact in surprising ways. Think about how their impacts would combine.'
    ),
    'Develop Stories & Images',
    JSON_ARRAY(
      'For each scenario you`ve built, create a clear story. What does this future look like?',
      'Think about creating a catchy title and even a visual image for each one to make it memorable.'
    ),
    'Review & Discuss',
    JSON_ARRAY(
      'Share your scenarios with others.',
      'Ask: Does this scenario make sense? What are the biggest surprises? How would this future affect different people or groups?'
    ),
    'Find Your Message',
    JSON_ARRAY(
      'What key insights or messages do you want people to take away from these scenarios?',
      'What actions should be considered today based on these possible futures?'
    )
  ),
  null
);

-- Student progress in modules
CREATE TABLE module_progress (
    progress_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    module_number INT NOT NULL,
    module_title TEXT NOT NULL,
    module_focus TEXT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    time_spent_minutes INT DEFAULT 0,
    score INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

-- Module content table (for each module)
CREATE TABLE lesson_screen_progress (  
    screen_progress_id INT PRIMARY KEY AUTO_INCREMENT,
    module_progress_id INT NOT NULL,
    lesson_screen_id INT NOT NULL,
    course_module_number INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    FOREIGN KEY (module_progress_id) REFERENCES module_progress(progress_id) ON DELETE CASCADE
);

CREATE TABLE module_assessment (
    assessment_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    module_number INT NOT NULL,
    assessment_title TEXT NOT NULL,
    assessment_objective TEXT NULL,
    assessment_scenario TEXT NULL,
    assessment_instructions JSON NULL,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  1, 1, 
  'Future Shapers: Mapping Change Drivers', 
  'Work within your group. Identify and analyze change drivers and weak signals using the PESTLE framework.', 
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Spot the Current Change Driver',
      'step_description', 'Find a current event (news, blog, tweet) that highlights a shift or trend influencing the future. Write a brief description of the change.'
    ),
    JSON_OBJECT(
      'step_title', 'Categorize the PESTLE',
      'step_description', 'Classify the change using PESTLE framework.'
    ),
    JSON_OBJECT(
      'step_title', 'Identify a Weak Signal',
      'step_description', 'Find a subtle early sign within your example that could lead to future change.'
    ),
    JSON_OBJECT(
      'step_title', 'Reflect',
      'step_description', 'How might the change driver or signal impact the future? Who could be affected if it grows?'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  1, 2, 
  'Future Force Field: Solving Tomorrow`s Challenges', 
  'Collaborate with your team to apply Futures Thinking tools to solve a shared future challenge.', 
  'Your team is a group of policy advisors preparing your city for a major shift in AI automation. The goal is to develop a strategy that helps the community adapt without negatively impacting jobs or social systems.', 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Identify Key Drivers',
      'step_description', 'As a team, use the PESTLE framework to collectively identify three key drivers (e.g., technological, political, social) influencing the shift. Discuss and assign each driver to a team member to research and explain its impact.'
    ),
    JSON_OBJECT(
      'step_title', 'Horizon Scanning',
      'step_description', 'Each team member will choose one weak signal (early hint) related to their assigned driver. The team will discuss how these weak signals could evolve into major trends.'
    ),
    JSON_OBJECT(
      'step_title', 'Trend Identification',
      'step_description', 'Use the Futures Wheel as a team to map out the first, second, and third-order impacts of the technological shift on sectors like employment, economy, and society.'
    ),
    JSON_OBJECT(
      'step_title', 'Brainstorm Solutions',
      'step_description', 'As a group, develop three policy solutions that address the challenges of the shift. Focus on: Mitigating negative impacts, supporting vulnerable groups, encouraging public-private collaboration.'
    ),
    JSON_OBJECT(
      'step_title', 'Reflection',
      'step_description', 'Write a 150-300 word reflection on what you learned about identifying future drivers and solving problems based on future trends.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  1, 3, 
  'Futures Quest: Scenario Building Game', 
  'Engage students in creating alternative futures using Causal Layered Analysis (CLA) and Scenario Planning to explore future challenges.', 
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Select an issue',
      'step_description', 'As a group, choose an issue to explore. Each member can suggest one issue, and you`ll vote on the final choice.'
    ),
    JSON_OBJECT(
      'step_title', 'CLA Exploration',
      'step_description', 'Use Causal Layered Analysis as a group to explore: • Litany: The visible facts (e.g., trends, headlines) • Systemic Causes: The deeper causes (e.g., policies, economic systems) • Worldviews: The cultural beliefs influencing the issue • Myths/Metaphors: The deep, often unspoken narratives'
    ),
    JSON_OBJECT(
      'step_title', 'Build Scenarios',
      'step_description', 'As a group, create four future scenarios for your issue: • Best Case: Ideal future • Worst Case: Worst possible future • Middle Ground: Plausible future • Wildcard: Unlikely but possible future'
    ),
    JSON_OBJECT(
      'step_title', 'Group Discussion',
      'step_description', 'Discuss your scenarios and consolidate your ideas into one cohesive group submission.'
    ),
    JSON_OBJECT(
      'step_title', 'Reflection',
      'step_description', 'Write a 150–300-word group reflection on what you learned from this activity.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  4, 1, 
  'Futures Thinking Challenge: Testing Your Understanding', 
  null,
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Short Answer Questions',
      'step_description', 'As a group, discuss the following questions and create a collective response. One group member will write down the answers.
      1. What is Futures Thinking?
      2. What is the Three Horizons Model?
      3. What are Change Drivers?
      4. What are Weak Signals?'
    ),
    JSON_OBJECT(
      'step_title', 'Multiple-Choice Questions',
      'step_description', 'As a group, answer the following questions. Discuss each one and come to a collective agreement on the answers.
      1. Which of the following is NOT a category in the PESTLE framework?
        a. Technological
        b. Ethical
        c. Economic
        d. Legal
      2. Which part of the Three Horizons Model focuses on long-term transformations?
        a. Horizon 1
        b. Horizon 2
        c. Horizon 3
        d. None of the Above
      3. What is the primary benefit of scanning for weak signals?
        a. It helps predict exact future outcomes
        b. It allows us to respond to immediate problems
        c. It provides early warning of changes that may impact long-term strategies
        d. It focuses on solving current challenges'
    ),
    JSON_OBJECT(
      'step_title', 'Scenario Application',
      'step_description', 'Given the global pandemic scenario, work together to:
      • Identify at least three drivers influencing the future of global supply chains. Categorize them using the PESTLE framework.
      • Identify two weak signals in the context of supply chain disruptions and discuss how these signals might grow into major trends.'
    ),
    JSON_OBJECT(
      'step_title', 'Reflection',
      'step_description', 'After completing the task, each group member will write a 150-300-word individual reflection on the activity. Focus on:
      • How you worked together to identify key trends and signals.
      • The role of Futures Thinking in planning for long-term challenges in your field of interest.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  4, 2, 
  'Futures Exploration: Case Study on Technological Disruption', 
  null,
  'Imagine a major global retailer has implemented a new AI-driven automation system in its supply chain, which has led to significant changes in the workforce and job opportunities. Some employees have lost jobs due to automation, while new job categories are emerging, demanding new skills and expertise. As a policy advisor, your goal is to assess how this change will affect the company, employees, and the broader labor market.', 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Identify Key Drivers',
      'step_description', 'As a group, use the PESTLE framework to identify three key drivers affecting workforce automation. Discuss their potential impacts on the workforce and society.'
    ),
    JSON_OBJECT(
      'step_title', 'Horizon Scanning',
      'step_description', 'Each team member will identify one weak signal related to workforce changes and automation. Discuss how these weak signals might evolve into major trends.'
    ),
    JSON_OBJECT(
      'step_title', 'Trends Identification and Analysis',
      'step_description', 'As a group, use the Futures Wheel to identify first, second, and third-order impacts of automation on the workforce, economy, and society. Discuss how these trends might shape future outcomes.'
    ),
    JSON_OBJECT(
      'step_title', 'Brainstorm Solutions',
      'step_description', 'As a group, propose three strategic solutions to address workforce challenges created by automation. Focus on: Upskilling and reskilling strategies, Policy solutions for displaced workers, Strategies to adapt to a more automated workforce.'
    ),
    JSON_OBJECT(
      'step_title', 'Reflect',
      'step_description', 'Write a 150-300-word reflection on how you applied Futures Thinking tools to assess the impacts of technological disruption.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  4, 3, 
  'Solving Future Challenges: A Pragmatist Approach', 
  'To enhance problem-solving skills by applying Futures Thinking concepts, particularly Causal Layered Analysis and Futures Triangle, in crafting potential solutions to future challenges.',
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Choose a Current Issue or Trend',
      'step_description', 'Select one significant issue or trend that may affect the future (e.g., climate change, technology advancement, geopolitical tensions). Write a brief description of the issue (3-5 sentences).'
    ),
    JSON_OBJECT(
      'step_title', 'Divide the Task',
      'step_description', 'Each member of the group will work on a different part of the task:
      • CLA (Litany, Systemic Causes, Worldviews, Myths/Metaphors): One member will handle the Litany and Systemic Causes, while another will explore Worldviews and Myths/Metaphors.
      • Futures Triangle: One member will focus on defining the Pull of the Future, another on the Push of the Present, and another on the Weight of the Past.'
    ),
    JSON_OBJECT(
      'step_title', 'Collaborative Problem-Solving',
      'step_description', 'Once the individual parts are completed, come together as a group to discuss and integrate the findings. Then, collectively propose one or more solutions based on the CLA and Futures Triangle analysis.'
    ),
    JSON_OBJECT(
      'step_title', 'Problem-Solving',
      'step_description', 'Based on the insights from CLA and the Futures Triangle, propose one concrete solution to address the issue and move closer to your envisioned future. Outline the steps involved in implementing this solution.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  3, 1, 
  'Futures Thinking Framework Creation', 
  'To formalize the understanding of Futures Thinking by developing a structured framework that identifies key components from the module and applies them in a scenario planning context.',
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Group Framework Development',
      'step_description', 'In your group, work together to create a detailed Futures Thinking framework using a shared digital tool (like Google Docs or Miro). Each member should contribute to the sections: Overview, Tools, Horizon Model, and Change Drivers.'
    ),
    JSON_OBJECT(
      'step_title', 'Group Discussion',
      'step_description', 'After creating the framework, each group member should share their thoughts on how Futures Thinking tools could influence a policy area of their choice. Have a discussion on which tools are most beneficial for anticipating change and guiding policy.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  3, 2, 
  'Generalizing Futures Thinking Tools Using a Venn Diagram', 
  'To help students generalize and visualize the connections between Horizon Scanning, Trends Identification and Analysis, and Key Drivers using a Venn Diagram, showing how they collectively contribute to understanding and preparing for future changes.',
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Review and identify components of each Futures Thinking Tool',
      'step_description', '• For Key Drivers, identify three significant drivers shaping the future (e.g., climate change, AI advancements).
      • For Horizon Scanning, identify two early signals that might indicate future changes (e.g., social media trends, shifts in consumer behavior).
      • For Trends Identification and Analysis, identify two emerging trends (e.g., remote work, renewable energy).'
    ),
    JSON_OBJECT(
      'step_title', 'Create a Venn Diagram',
      'step_description', 'Draw a three-circle Venn Diagram, with each circle representing one of the three tools: Key Drivers, Horizon Scanning, and Trends Identification and Analysis.'
    ),
    JSON_OBJECT(
      'step_title', 'In the non-overlapping parts of each circle, list the unique elements for each tool',
      'step_description', '(e.g., for Horizon Scanning, list the weak signals you identified). In the overlapping sections, identify and list how elements from each tool might interact or contribute to one another. For example, how a Key Driver (e.g., climate change) might influence Trends (e.g., increased use of renewable energy) or how Horizon Scanning signals (e.g., early signs of climate policy changes) align with identified Key Drivers.'
    ),
    JSON_OBJECT(
      'step_title', 'Generalization',
      'step_description', 'After completing the diagram, write a brief explanation (150-200 words) generalizing how these three tools work together to shape future possibilities. Focus on how identifying drivers, scanning for early signals, and analyzing trends create a comprehensive understanding of future changes.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  3, 3, 
  'Comparative Analysis of Future Scenarios', 
  'To understand and compare different future scenarios based on key drivers of change, exploring how variations in these drivers could shape the future through teamwork and collective analysis. As a team, you will compare two future scenarios using the Futures Triangle  or Causal Layered Analysis methods. Together, you will analyze how different trends and  drivers can shape the future, focusing on the collective impact of these drivers on society, technology, and the environment.',
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Form Teams & Select Two Scenarios',
      'step_description', 'Work together to choose two distinct scenarios based on a key issue. One scenario could focus on technological advancement, and the other could address environmental or policy constraints.'
    ),
    JSON_OBJECT(
      'step_title', 'Assign Drivers to Team Members',
      'step_description', 'Assign each team member to research and analyze specific key drivers (e.g., technology, economy, climate). Ensure that everyone has a driver to explore in detail.'
    ),
    JSON_OBJECT(
      'step_title', 'Create a Comparison Chart',
      'step_description', 'Using the table below, collaborate to compare how each driver influences the future in both scenarios. Each member should contribute their findings for the chart.
      | Key Drivers | Scenario 1: Tech-Driven Future | Scenario 2: Environmentally Constrained Future |
      |-------------|-------------------------------|--------------------------------------------|
      | Technology Advancement | Positive Impact | Neutral Impact |
      | Economic Growth | Strong Growth | Slower Growth |
      | Climate Change | Neutral Impact | Significant Negative Impact |
      | Global Policy Shifts | Positive Impact | Mixed Impact |'
    ),
    JSON_OBJECT(
      'step_title', 'Group Discussion',
      'step_description', 'After completing the comparison chart, hold a discussion to analyze how these drivers interact in each scenario. How does each driver influence the future? What insights did you gain from comparing the scenarios? Each member should share their perspective on the drivers they researched.'
    ),
    JSON_OBJECT(
      'step_title', 'Write a Collaborative Reflection',
      'step_description', 'After the discussion, write a joint reflection summarizing the group`s findings. Address how the interaction between drivers influences the scenarios and discuss the broader implications for future planning.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  2, 1, 
  'Classifying Futures Thinking Concepts', 
  'Reflect on and classify the core elements of Futures Thinking based on the introduction. This activity will help you categorize and organize your understanding of the concepts discussed.',
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Classify key concepts',
      'step_description', 'As a group, classify the key concepts into three categories (as defined above). Discuss each concept in the categories. Consider:
      • How do these concepts intersect with your community or professional experiences?
      • Which concepts might be most challenging to apply, and why?'
    ),
    JSON_OBJECT(
      'step_title', 'Create a shared list',
      'step_description', 'Create a shared list of questions or points for further exploration.'
    ),
    JSON_OBJECT(
      'step_title', 'Individual reflection',
      'step_description', 'After discussion, each participant should individually reflect on the classification and note their personal insights.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  2, 2, 
  'Reflective Futures Exploration', 
  'To reflect on the key concepts of Futures Thinking, identify change drivers, understand horizon scanning, and analyze trends through a reflective approach.',
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Futures Thinking Reflection (Group Discussion)',
      'step_description', 'As a group, discuss your personal understandings of Futures Thinking. Share how you each interpret the concept and its importance. Afterward, summarize your group`s collective thoughts on: What does Futures Thinking mean for your community or area of interest?'
    ),
    JSON_OBJECT(
      'step_title', 'Driver Mapping (Group PESTLE Analysis)',
      'step_description', 'In your group, choose a current global issue (e.g., COVID-19 recovery, technological advancements, or climate change). Use the PESTLE Framework to collaboratively identify potential drivers affecting this issue. Afterward, collectively discuss:
      • Which drivers do you think will shape the future most significantly, and why?'
    ),
    JSON_OBJECT(
      'step_title', 'Horizon Scanning (Group Signal Exploration)',
      'step_description', 'Each member of the group finds a weak signal in a news article, blog post, or tweet related to a global issue. Share your findings and discuss:
      • What signals did you find, and why might they be important?'
    ),
    JSON_OBJECT(
      'step_title', 'Trends Identification and Futures Wheel (Group Mapping)',
      'step_description', 'Together, select a trend affecting your group (e.g., social media`s influence, remote work, or environmental sustainability). As a group, create a Futures Wheel to analyze the trend`s impact. Reflect on:
      • Which ripple effect was most unexpected, and how might it impact future developments?'
    ),
    JSON_OBJECT(
      'step_title', 'Final Reflection (Group Reflection and Discussion)',
      'step_description', 'As a group, discuss how Futures Thinking can influence your chosen issue. Reflect on how you might incorporate Futures Thinking into your professional or academic practices. Summarize your group`s final thoughts.'
    )
  )
);

INSERT INTO module_assessment (course_id, module_number, assessment_title, assessment_objective, assessment_scenario, assessment_instructions) VALUES (
  2, 3, 
  'Mapping the Future: A Reflective Exploration of Change and Possibility', 
  'The objective of the activity is to engage learners in reflecting on current issues through Futures Thinking techniques, including Causal Layered Analysis, Horizon Scanning, the Futures Triangle, and Scenario Planning. Learners will analyze drivers of change, identify emerging trends, and explore potential future outcomes while reflecting on how past, present, and future forces shape alternative futures.',
  null, 
  JSON_ARRAY(
    JSON_OBJECT(
      'step_title', 'Group Selection',
      'step_description', 'As a group, choose a common issue that interests everyone (e.g., global health, urban development, digital privacy).'
    ),
    JSON_OBJECT(
      'step_title', 'Causal Layered Analysis (CLA)',
      'step_description', 'Each group member will take one of the CLA layers and analyze the chosen issue from that perspective:
      • Litany: What are the visible symptoms or headlines surrounding this issue?
      • Systemic Causes: What are the systemic or structural factors that contribute to this issue?
      • Worldview: What beliefs or ideologies are shaping this issue? 
      • Myth/Metaphor: What deeper narratives or collective metaphors influence this issue?'
    ),
    JSON_OBJECT(
      'step_title', 'Futures Triangle',
      'step_description', 'Discuss and agree on the Pulls of the Future, Pushes of the Present, and Weights of the Past as a group. Identify how these elements interact with each other.'
    ),
    JSON_OBJECT(
      'step_title', 'Scenario Planning',
      'step_description', 'As a team, develop 3 different scenarios for the future:
      • Exploratory Scenario: Based on current trends.
      • Normative Scenario: A future you wish to see and how to achieve it.
      • Wildcard Scenario: An unlikely but impactful event that could change the course.'
    ),
    JSON_OBJECT(
      'step_title', 'Group Reflection',
      'step_description', 'Write a 300-word reflection on:
      • Discuss the most plausible and impactful scenario.
      • Share insights on how the past and present have shaped the future possibilities.
      • Reflect on the insights gained from each member`s contribution and how collaboration shaped the final scenarios.'
    )
  )
);

-- Module assessment progress table
CREATE TABLE module_assessment_progress (
    assessment_progress_id INT PRIMARY KEY AUTO_INCREMENT,
    module_assessment_id INT NOT NULL,
    user_id INT NOT NULL,
    module_progress_id INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    file_url VARCHAR(255),  
    FOREIGN KEY (module_assessment_id) REFERENCES module_assessment(assessment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (module_progress_id) REFERENCES module_progress(progress_id) ON DELETE CASCADE
);

-- Certificates table
CREATE TABLE certificates (
    certificate_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    certificate_url VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

-- Scores table
CREATE TABLE scores (
    score_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    score INT NOT NULL CHECK (score BETWEEN 1 AND 5),
    module_assessment_progress_id INT NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (module_assessment_progress_id) REFERENCES module_assessment_progress(assessment_progress_id) ON DELETE CASCADE
);

-- Faculty feedback table
CREATE TABLE feedback (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT NOT NULL,
    feedback TEXT NOT NULL,
    module_assessment_progress_id INT NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (module_assessment_progress_id) REFERENCES module_assessment_progress(assessment_progress_id) ON DELETE CASCADE
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
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert default roles
INSERT INTO roles (role_name, description) VALUES
('student', 'Regular student user'),
('faculty', 'Faculty member with teaching privileges'),
('admin', 'System administrator with full access');

-- Insert admin user (password should be properly hashed in production)
INSERT INTO users (role_id, email, password, first_name, last_name) VALUES
(3, 'admin@foresite.com', '$2y$10$nHipa0I9/v/SEy4HrI6mxOnBJ.a4kXnhgxyMC.2WaJnELzpikNiUi', 'System', 'Administrator');

-- Chat functionality tables
-- Chat rooms table for learning style specific chat rooms
CREATE TABLE chat_rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    learning_style_id INT NOT NULL,
    room_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (learning_style_id) REFERENCES learning_styles(style_id) ON DELETE CASCADE
);

-- Initialize default chat rooms for each learning style
INSERT INTO chat_rooms (learning_style_id, room_name) VALUES
(1, 'Activist Learning Chat'),
(2, 'Reflector Learning Chat'),
(3, 'Theorist Learning Chat'),
(4, 'Pragmatist Learning Chat');

-- Table for groups of students in a course
CREATE TABLE `groups` (
  group_id int PRIMARY KEY AUTO_INCREMENT,
  course_id int NOT NULL,
  group_name varchar(100) NOT NULL,
  learning_style_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (course_id) REFERENCES courses(course_id),
  FOREIGN KEY (learning_style_id) REFERENCES learning_styles(style_id)
);

-- Table to associate users with groups
CREATE TABLE group_members (
  group_member_id int PRIMARY KEY AUTO_INCREMENT,
  group_id int NOT NULL,
  user_id int NOT NULL,
  FOREIGN KEY (group_id) REFERENCES `groups`(group_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_group (user_id, group_id)
);

-- Table to assign a faculty member to a group for supervision/gradingAdd commentMore actions
CREATE TABLE faculty_assigned_groups (
    faculty_id INT NOT NULL,
    group_id INT NOT NULL,
    PRIMARY KEY (faculty_id, group_id),
    FOREIGN KEY (faculty_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES `groups`(group_id) ON DELETE CASCADE
);

-- Chat messages table for storing chat messages
CREATE TABLE chat_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES `groups`(group_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE notifications (
  notification_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  sender_id INT NULL,
  message TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE
);

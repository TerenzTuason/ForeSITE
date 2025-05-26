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
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
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
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (assessment_result_id) REFERENCES assessment_results(result_id),
    UNIQUE KEY unique_enrollment (user_id, course_id)
);

-- Lesson screen for each course module
CREATE TABLE lesson_screens (
    lesson_screen_id INT PRIMARY KEY AUTO_INCREMENT,
    screen_number VARCHAR(50) NOT NULL,
    screen_title TEXT NULL,
    screen_description TEXT NULL,
    screen_content JSON NULL,
    screen_url VARCHAR(255) NULL
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "1.1",
  "What is Futures Thinking?",
  "A strategic mindset to explore change and build resilient policy",
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
    null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "1.2",
  "Looking Ahead: The Three Horizons Model",
  "A tool for thinking across short, medium, and long-term futures.",
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
    "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186077/1.2_The_Three_Horizons_Model_b7t0si.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "1.3",
  "Identifying Change Drivers",
  "Understanding what shapes the future.",
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
    null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "1.4",
  "Identifying Weak Signals",
  "Spotting early signs of future change.",
  '[
        "Weak signals are early hints of significant future changes.",
        "They are often unclear, rare, or not well understood yet.",
        "Horizon Scanning helps identify these subtle trends.",
        "Even if there’s no solid data, trust your intuition — a weak signal might grow into a major shift.",
        "Example: An unusual news article today could indicate a big future trend tomorrow."
    ]',
    null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "1.5",
  "Linking Futures Thinking to the Policy Cycle",
  "Where foresight fits in policymaking.",
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
    "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186077/1.5_Illustrative_Policy_Cycle_dgw7i8.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.1",
  "Driver Mapping",
  "Drivers are influential forces of changes that are currently shaping or have the capacity to shape or transform a system. Driver mapping is one of the most important tools in foresight. It helps to identify the most influential forces of change in a system.",
  '[
        "To find these drivers, we often use tools like the STEEP or PESTLE frameworks. Think of these frameworks as ways to get a \'big picture\' view of everything influencing a situation. They help us explore many different types of drivers by sorting them into categories:",
        [
            "STEEP looks at Social, Technological, Economic, Ecological (or Environmental), and Political drivers.",
            "PESTLE includes all of those, plus an extra category for Legal drivers."
        ]
    ]',
    "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186077/2.1_PESTLE_i4quij.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.1.1",
  "Why Use Driver Mapping?",
  "Understanding the core reasons for identifying and analyzing key forces of change.",
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
  "2.1.2",
  "How to do Driver Mapping?",
  "Drive Mapping is a practical exercise to figure out what forces are really shaping your topic of interest. Think of it like a detective's checklist to understand the big picture!",
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

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.1.3",
  null,
  null,
  null,
  "https://www.youtube.com/watch?v=1Pd0xSsdCAU"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.2",
  "Horizon scanning",
  "Horizon scanning is a foresight process focused on identifying and collating early warning signs of change, or emerging signals that may have significant impacts when they develop.",
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
  "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186078/2.2_Emerging_Issue_Analysis_blsa8e.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.2.1",
  "Why Use Horizon Scanning?",
  "Discover how Horizon Scanning helps you spot future risks and opportunities early, preparing you for what's ahead.",
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
  "2.2.2",
  "How to do Horizon Scanning?",
  "Horizon Scanning helps you spot early signs of future changes so you can prepare. Think of it as looking ahead for clues!",
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

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.2.3",
  null,
  null,
  null,
  "https://www.youtube.com/watch?v=QeILzDCS_6U"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.3",
  "Trends Identification and Analysis",
  "Understanding the cascading impacts of change using structured brainstorming and visualization.",
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
  "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186078/2.3_Implications_Analysis_Considerations_fzxlyt.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "2.3.1",
  "How to do Futures Wheel Analysis?",
  "Explore the ripple effects of a change! The Futures Wheel helps you map out all the possible consequences of a trend or event.",
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

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3",
  null,
  null,
  null,
  "https://www.youtube.com/watch?v=ImWDmFPfifI"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.1",
  "Causal Layered Analysis",
  "It is a powerful tool that helps us understand complex issues by looking beyond the surface. It's used to reveal hidden beliefs and perspectives that shape our current reality, and then to help create new stories that can lead to a desired future.",
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
  "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186078/3.1_CLA_Levels_of_Analysis_ooqitq.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.1.1",
  "Why Use Causal Layered Analysis?",
  "Discover how Causal Layered Analysis helps you dig deeper into a problem, finding hidden beliefs and creating clearer paths forward.",
  '[
        "Causal Layered Analysis (CLA) is a powerful tool used for several key reasons:",
        [
          "It helps you unpack any issue or topic to understand it at a much profounder level than just the surface.",
          "It lets you map out and understand the various, sometimes conflicting, ideas people have about the future.",
          "It helps you break down a desired future (like a vision or a big goal) to understand its core parts and how to build it.",
          "It allows you to grasp the different \"stories\" or narratives that people tell about a problem or the future.",
          "It helps you develop solid, comprehensive strategies that address issues from their roots.",
          "It assists in clearly defining and working towards a preferred vision of the future you want to create."
        ]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.1.2",
  "How to do Causal Layered Analysis?",
  "Learn to dig deep into any issue by exploring its surface, systems, beliefs, and hidden stories.",
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
            "Ask: What are the unspoken, powerful stories, narratives, or images (metaphors) that influence people\'s worldviews about this issue? Is there a new story or a memorable metaphor that could help people understand or change things? What kind of mental shift would that new story require?",
            "Think: This is about the unconscious stories and archetypes that deeply shape our reality."
          ]
        ],
  ]',
  "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186079/3.1.2_Using_CLA_sfpy9p.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.1.3",
  null,
  null,
  null,
  "https://www.youtube.com/watch?v=KuSDCu6aTgM"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.2",
  "Futures Triangle",
  "Futures triangle is a futures method that helps us to map the present views of the future through three dimensions that are shaping it. The three dimensions are: the images (pull) of the future, the megatrends and drivers (push) of the present and the barriers (weight) of past/history.",
  '[
        "The Futures Triangle is a quick and easy way to understand how the future might play out. It\'s built on three main forces constantly pushing and pulling on things:",
        [
          "The "Pull" of the Future (Your Visions):",
          [
            "This is about the images we have in our minds of what the future could or should be like.",
            "Think of it as the dreams, hopes, and even fears that push us forward.",
            "These visions can come from anywhere: books, movies, political goals, religious ideas, or even advertisements. They\'re basically what people or groups strongly desire or dread for the future."
          ],
        ],
        "The "Push" of the Present (Current Trends):",
        [
          "These are the ongoing trends and major forces happening right now that are actively shaping what\'s coming next.",
          "Things like new technologies, economic changes, or shifts in how people live and interact."
        ],
        "The "Weight" of the Past (Historical Barriers):",
        [
          "This represents the old ways of doing things, traditions, established systems, and historical events that can slow down or block changes we want to see.",
          "It\'s like a heavy anchor from history that makes it harder to move in a new direction."
        ],
        "Putting It All Together:",
        [
          "The Futures Triangle shows that these three forces are always interacting and changing.",
          "If one force gets stronger, it affects the others. For example, if the "Weight of the Past" gets heavier (meaning old ways become more stubborn), it might weaken the "Pull of the Future" or slow down the "Push of the Present.",
          "This push and pull changes where the future is likely to go."
        ]
  ]',
  "https://res.cloudinary.com/dwn5t3o4j/image/upload/v1748186111/3.2_Futures_Triangle_kzo8sy.png"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.2.1",
  "Why Use the Futures Triangle?",
  "Learn how this tool helps you understand the forces shaping the future – from the past, present, and your hopes for tomorrow.",
  '[
        "The Futures Triangle is a handy tool that helps us:",
        [
          "Understand how the past, what\'s happening now, and future possibilities all connect and influence each other.",
          "Discover various believable futures by looking at how the forces from the past, present, and your future visions interact.",
          "Use it as a foundation for creating detailed "what if" stories about the future.",
          "Develop more effective actions and strategies that consider these powerful forces."
        ]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.2.2",
  "How to Use the Futures Triangle?",
  "Understand the forces shaping your future by exploring your visions, current trends, and past influences.",
  '[
        "The Futures Triangle helps you map out how different forces — your hopes for the future, what\'s happening now, and lessons from the past — are all pushing and pulling on each other. It\'s about understanding the big picture to see where things might go.",
        "Here\'s how to use it, by exploring three key areas:",
        [
          "The "Pull" of the Future (Your Hopes)",
          [
            "What it is: Your vision for what you want the future to be.",
            "Ask yourself:",
            ["What\'s your ideal future?"],
            "What resources help you get there?"
          ],
          "The "Push" of the Present (Today\'s Trends)",
          [
            "What it is: The forces and changes happening right now that are shaping tomorrow.",
            "Ask yourself:",
            [
              "What big trends are happening?",
              "How are they moving us forward?"
            ],
              "What resources help you get there?"
          ],
          "The "Weight" of the Past (Old Influences)",
          [
            "What it is: History, traditions, and old ways of doing things that might slow change.",
            "Ask yourself:",
            [
              "What\'s holding things back?",
              "Who benefits if nothing changes?"
            ],
          ]
        ],
        "By thinking through these questions for each of the three points of the triangle, you\'ll start to see how they interact. This helps you understand the likely direction things are headed, imagine different possible futures, and develop clearer actions."
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.2.3",
  null,
  null,
  null,
  "https://www.youtube.com/watch?v=ukr9ItEQX2Y"
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.3",
  "Scenario",
  "A scenario is a description of how the future may unfold based on an explicit, coherent and internally consistent set of plausible assumptions about key relationships among drivers of change.",
  '[
        "Scenario planning is a special tool that helps us imagine and prepare for different possible futures. It\'s not about predicting one exact future, but rather creating several "what if" stories based on big trends and uncertainties we see today.",
        "A scenario is simply a detailed story about how the future could unfold. Each story is realistic, makes sense internally, and is based on clear ideas about how different changes might connect.",
        "Types of scenarios methods",
        [
          "2x2 scenarios",
          "Organizational scenarios",
          "Integrated scenarios",
          "Manoa scenarios",
          "Transformative scenarios",
          "Change progression scenarios method"
        ]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.3.1",
  "Why Use Scenario Planning?",
  "Learn how scenarios help you explore different possible futures, challenge your thinking, and prepare for anything.",
  '[
        "Using Scenarios is super helpful because it allows us to:",
        [
          "It makes us think beyond what we expect to happen, by exploring many different ways a plan, policy, or strategy could unfold in the future.",
          "It helps us imagine how key issues, partners, and other people might act in various future situations.",
          "It\'s a great way to create "what if" plans (contingency plans) for our programs, policies, and strategies, making us ready for different possible futures."
        ]
  ]',
  null
);

INSERT INTO lesson_screens (screen_number, screen_title, screen_description, screen_content, screen_url) VALUES(
  "3.3.2",
  "How to Build Scenarios?",
  "Learn to create different 'what if' stories about the future to better understand and prepare for change.",
  '[
        "Define Your Focus & Time",
        [
          "What issue are you exploring? (e.g., "The future of digital access.")",
          "How far into the future? Pick a specific time (e.g., 5, 10, or 20 years from now)."
        ],
        "Pick Your Key Drivers",
        [
          "Identify the most important forces of change (drivers) that will shape your future.",
          "These drivers become your "critical uncertainties" (for a 2x2 scenario) or help explore "colliding impacts" (for a Manoa scenario)."
        ],
        "Build Your Scenarios",
        [
          "For 2x2 Scenarios: Take your two most uncertain and impactful drivers. Imagine them as axes on a graph (like X and Y). This creates four unique future "corners" or stories."
          "For Manoa Scenarios: Explore how your chosen drivers might collide or interact in surprising ways. Think about how their impacts would combine."
        ],
        "Develop Stories & Images",
        [
          "For each scenario you\'ve built, create a clear story. What does this future look like?",
          "Think about creating a catchy title and even a visual image for each one to make it memorable."
        ],
        "Review & Discuss",
        [
          "Share your scenarios with others.",
          "Ask: Does this scenario make sense? What are the biggest surprises? How would this future affect different people or groups?"
        ],
        "Find Your Message",
        [
          "What key insights or messages do you want people to take away from these scenarios?",
          "What actions should be considered today based on these possible futures?"
        ]
  ]',
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
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- Module content table (for each module)
CREATE TABLE lesson_screen_progress (  
    screen_progress_id INT PRIMARY KEY AUTO_INCREMENT,
    module_progress_id INT NOT NULL,
    lesson_screen_id INT NOT NULL,
    course_module_number INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    FOREIGN KEY (module_progress_id) REFERENCES module_progress(progress_id)
);

-- Certificates table
CREATE TABLE certificates (
    certificate_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    certificate_url VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
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
    FOREIGN KEY (module_id) REFERENCES module_progress(progress_id)
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

-- Insert admin user (password should be properly hashed in production)
INSERT INTO users (role_id, email, password, first_name, last_name) VALUES
(3, 'admin@foresite.com', '$2y$10$nHipa0I9/v/SEy4HrI6mxOnBJ.a4kXnhgxyMC.2WaJnELzpikNiUi', 'System', 'Administrator'); 
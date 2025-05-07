# ForeSITE Learning System

## Frontend
*Frontend setup instructions will be added once the frontend development begins.*

## Backend Setup

### Prerequisites
1. XAMPP (Download from [https://www.apachefriends.org/](https://www.apachefriends.org/))
2. Composer (Download from [https://getcomposer.org/download/](https://getcomposer.org/download/))
3. PHP 8.1 or higher
4. MySQL 5.7 or higher
5. Laravel CLI (Install using `composer global require laravel/installer`)

### Database Setup
1. Install XAMPP and start Apache and MySQL services from the XAMPP Control Panel
2. Create a new database named `foresite_db` in phpMyAdmin (http://localhost/phpmyadmin)
3. Import the schema.sql file directly into the database using phpMyAdmin:
   - In phpMyAdmin, select the `foresite_db` database
   - Click on the "Import" tab
   - Click "Choose File" and select the schema.sql file from the project root
   - Click "Go" to import the schema

### Backend Installation
1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd ForeSITE
   ```

2. Navigate to the backend directory:
   ```bash
   cd backend
   ```

3. Install PHP dependencies using Composer:
   ```bash
   composer install
   ```

4. Create a copy of the environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database connection in `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=foresite_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Create Laravel required tables (sessions, etc.):
   ```bash
   php artisan migrate
   ```

8. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

The backend API will be available at `http://localhost:8000/api/v1`

## API Flow and Database Interaction

The API provides CRUD (Create, Read, Update, Delete) operations for the main entities in the database schema. Here's how the API routes interact with the database tables:

### 1. Roles (`roles` table)
- **GET /api/v1/roles**: Fetches all records from the `roles` table
- **GET /api/v1/roles/{id}**: Fetches a single record from the `roles` table where `role_id = {id}`
- **POST /api/v1/roles**: Inserts a new record into the `roles` table
- **PUT /api/v1/roles/{id}**: Updates a record in the `roles` table where `role_id = {id}`
- **DELETE /api/v1/roles/{id}**: Deletes a record from the `roles` table where `role_id = {id}` (only if not used by any users)

### 2. Users (`users` table)
- **GET /api/v1/users**: Fetches all records from the `users` table with their associated `roles`
- **GET /api/v1/users/{id}**: Fetches a single record from the `users` table with associated `role` and `student_profile`
- **POST /api/v1/users**: Inserts a new record into the `users` table (requires valid `role_id`)
- **PUT /api/v1/users/{id}**: Updates a record in the `users` table where `user_id = {id}`
- **DELETE /api/v1/users/{id}**: Deletes a record from the `users` table where `user_id = {id}` (also deletes associated `student_profile`)
- **GET /api/v1/users/{user}/role**: Gets the role of a specific user
- **PUT /api/v1/users/{user}/role**: Updates the role of a specific user
- **GET /api/v1/users/{user}/enrollments**: Gets all enrollments for a user
- **GET /api/v1/users/{user}/assessment-attempts**: Gets all assessment attempts for a user
- **GET /api/v1/users/{user}/certificates**: Gets all certificates for a user
- **GET /api/v1/users/{user}/received-feedback**: Gets feedback received by the user
- **GET /api/v1/users/{user}/given-feedback**: Gets feedback given by the user (for faculty)

### 3. Learning Styles (`learning_styles` table)
- **GET /api/v1/learning-styles**: Fetches all records from the `learning_styles` table
- **GET /api/v1/learning-styles/{id}**: Fetches a single record from the `learning_styles` table where `style_id = {id}`
- **POST /api/v1/learning-styles**: Inserts a new record into the `learning_styles` table
- **PUT /api/v1/learning-styles/{id}**: Updates a record in the `learning_styles` table where `style_id = {id}`
- **DELETE /api/v1/learning-styles/{id}**: Deletes a record from the `learning_styles` table where `style_id = {id}` (only if not used by any student profiles)

### 4. Student Profiles (`student_profiles` table)
- **GET /api/v1/student-profiles**: Fetches all records from the `student_profiles` table with their associated `users` and `learning_styles`
- **GET /api/v1/student-profiles/{id}**: Fetches a single record from the `student_profiles` table with associated `user` and `learning_style`
- **POST /api/v1/student-profiles**: Inserts a new record into the `student_profiles` table (requires valid `user_id` and optional `dominant_learning_style_id`)
- **PUT /api/v1/student-profiles/{id}**: Updates a record in the `student_profiles` table where `profile_id = {id}`
- **DELETE /api/v1/student-profiles/{id}**: Deletes a record from the `student_profiles` table where `profile_id = {id}`

### 5. Questionnaire Responses
- **GET /api/v1/questionnaire-responses**: Fetches all questionnaire responses
- **GET /api/v1/questionnaire-responses/{id}**: Fetches a single questionnaire response
- **POST /api/v1/questionnaire-responses**: Creates a new questionnaire response
- **PUT /api/v1/questionnaire-responses/{id}**: Updates a questionnaire response
- **DELETE /api/v1/questionnaire-responses/{id}**: Deletes a questionnaire response

### 6. Courses
- **GET /api/v1/courses**: Fetches all courses
- **GET /api/v1/courses/{id}**: Fetches a single course with its modules
- **POST /api/v1/courses**: Creates a new course
- **PUT /api/v1/courses/{id}**: Updates a course
- **DELETE /api/v1/courses/{id}**: Deletes a course (only if it has no enrollments, modules, or certificates)
- **GET /api/v1/courses/{course}/enrollments**: Gets all enrollments for a course
- **POST /api/v1/courses/{course}/enrollments**: Adds a new enrollment to a course
- **DELETE /api/v1/courses/{course}/enrollments/{enrollment}**: Removes an enrollment from a course
- **GET /api/v1/courses/{course}/certificates**: Gets all certificates for a course

### 7. Modules
- **GET /api/v1/courses/{course}/modules**: Fetches all modules for a course
- **GET /api/v1/modules/{id}**: Fetches a single module
- **POST /api/v1/courses/{course}/modules**: Creates a new module in a course
- **PUT /api/v1/modules/{id}**: Updates a module
- **DELETE /api/v1/modules/{id}**: Deletes a module

### 8. Module Contents
- **GET /api/v1/modules/{module}/contents**: Fetches all content for a module
- **GET /api/v1/contents/{id}**: Fetches a single content item
- **POST /api/v1/modules/{module}/contents**: Creates new content for a module
- **PUT /api/v1/contents/{id}**: Updates a content item
- **DELETE /api/v1/contents/{id}**: Deletes a content item

### 9. Assessments
- **GET /api/v1/modules/{module}/assessments**: Fetches all assessments for a module
- **GET /api/v1/assessments/{id}**: Fetches a single assessment
- **POST /api/v1/modules/{module}/assessments**: Creates a new assessment for a module
- **PUT /api/v1/assessments/{id}**: Updates an assessment
- **DELETE /api/v1/assessments/{id}**: Deletes an assessment

### 10. Assessment Questions
- **GET /api/v1/assessments/{assessment}/questions**: Fetches all questions for an assessment
- **GET /api/v1/questions/{id}**: Fetches a single question
- **POST /api/v1/assessments/{assessment}/questions**: Creates a new question for an assessment
- **PUT /api/v1/questions/{id}**: Updates a question
- **DELETE /api/v1/questions/{id}**: Deletes a question

### 11. Assessment Attempts
- **GET /api/v1/assessments/{assessment}/attempts**: Fetches all attempts for an assessment
- **GET /api/v1/attempts/{id}**: Fetches a single attempt
- **POST /api/v1/assessments/{assessment}/attempts**: Creates a new attempt for an assessment
- **PUT /api/v1/attempts/{id}**: Updates an attempt
- **DELETE /api/v1/attempts/{id}**: Deletes an attempt

### 12. Certificates
- **GET /api/v1/certificates**: Fetches all certificates
- **GET /api/v1/certificates/{id}**: Fetches a single certificate
- **POST /api/v1/certificates**: Creates a new certificate
- **PUT /api/v1/certificates/{id}**: Updates a certificate
- **DELETE /api/v1/certificates/{id}**: Deletes a certificate

### 13. Feedback
- **GET /api/v1/feedback**: Fetches all feedback
- **GET /api/v1/feedback/{id}**: Fetches a single feedback
- **POST /api/v1/feedback**: Creates a new feedback
- **PUT /api/v1/feedback/{id}**: Updates a feedback
- **DELETE /api/v1/feedback/{id}**: Deletes a feedback

### Database Relationships
- A `User` belongs to a `Role` (`role_id` foreign key)
- A `User` can have one `StudentProfile` (one-to-one)
- A `StudentProfile` belongs to a `User` (`user_id` foreign key)
- A `StudentProfile` belongs to a `LearningStyle` (`dominant_learning_style_id` foreign key, optional)
- A `LearningStyle` can have many `StudentProfiles` (one-to-many)
- A `Role` can have many `Users` (one-to-many)
- A `Course` belongs to a `User` (creator)
- A `Course` has many `Modules`
- A `Module` belongs to a `Course`
- A `Module` has many `ModuleContents` and `Assessments`
- An `Assessment` belongs to a `Module`
- An `Assessment` has many `AssessmentQuestions` and `AssessmentAttempts`
- A `User` has many `Enrollments`, `AssessmentAttempts`, and `Certificates`

## API Documentation

For detailed API documentation, including request and response formats, please see the [API Documentation](backend/API_DOCUMENTATION.md) file.

### Available Endpoints Overview

All API endpoints are prefixed with `/api/v1`

1. **Roles**
   - `GET /roles` - List all roles
   - `GET /roles/{id}` - Get specific role
   - `POST /roles` - Create a new role
     ```json
     {
       "role_name": "student",
       "description": "Regular student user"
     }
     ```
   - `PUT /roles/{id}` - Update a role
     ```json
     {
       "description": "Updated description"
     }
     ```
   - `DELETE /roles/{id}` - Delete a role

2. **Users**
   - `GET /users` - List all users
   - `GET /users/{id}` - Get specific user
   - `POST /users` - Create a new user
     ```json
     {
       "role_id": 1,
       "email": "student@example.com",
       "password": "password123",
       "first_name": "John",
       "last_name": "Doe",
       "is_active": true
     }
     ```
   - `PUT /users/{id}` - Update a user
     ```json
     {
       "first_name": "Updated Name",
       "last_name": "Updated Surname"
     }
     ```
   - `DELETE /users/{id}` - Delete a user
   - `GET /users/{user}/role` - Get user's role
   - `PUT /users/{user}/role` - Update user's role
     ```json
     {
       "role_id": 2
     }
     ```
   - `GET /users/{user}/enrollments` - Get user's enrollments
   - `GET /users/{user}/assessment-attempts` - Get user's assessment attempts
   - `GET /users/{user}/certificates` - Get user's certificates
   - `GET /users/{user}/received-feedback` - Get feedback received by user
   - `GET /users/{user}/given-feedback` - Get feedback given by user

3. **Learning Styles**
   - `GET /learning-styles` - List all learning styles
   - `GET /learning-styles/{id}` - Get specific learning style
   - `POST /learning-styles` - Create a new learning style
     ```json
     {
       "style_name": "visual",
       "description": "Preference for visual information like charts, graphs, and diagrams"
     }
     ```
   - `PUT /learning-styles/{id}` - Update a learning style
     ```json
     {
       "description": "Updated learning style description"
     }
     ```
   - `DELETE /learning-styles/{id}` - Delete a learning style

4. **Student Profiles**
   - `GET /student-profiles` - List all student profiles
   - `GET /student-profiles/{id}` - Get specific student profile
   - `POST /student-profiles` - Create a new student profile
     ```json
     {
       "user_id": 2,
       "dominant_learning_style_id": 1
     }
     ```
   - `PUT /student-profiles/{id}` - Update a student profile
     ```json
     {
       "dominant_learning_style_id": 2
     }
     ```
   - `DELETE /student-profiles/{id}` - Delete a student profile

5. **Questionnaire Responses**
   - `GET /questionnaire-responses` - List all responses
   - `GET /questionnaire-responses/{id}` - Get specific response
   - `POST /questionnaire-responses` - Create a new response
     ```json
     {
       "user_id": 2,
       "question_id": 1,
       "response_text": "Sample response"
     }
     ```
   - `PUT /questionnaire-responses/{id}` - Update a response
     ```json
     {
       "response_text": "Updated response"
     }
     ```
   - `DELETE /questionnaire-responses/{id}` - Delete a response

6. **Courses**
   - `GET /courses` - List all courses
   - `GET /courses/{id}` - Get specific course
   - `POST /courses` - Create a new course
     ```json
     {
       "title": "Introduction to Programming",
       "description": "Learn the basics of programming",
       "created_by": 1,
       "is_active": true
     }
     ```
   - `PUT /courses/{id}` - Update a course
     ```json
     {
       "title": "Updated Course Title",
       "is_active": false
     }
     ```
   - `DELETE /courses/{id}` - Delete a course
   - `GET /courses/{course}/enrollments` - Get course enrollments
   - `POST /courses/{course}/enrollments` - Add new enrollment
     ```json
     {
       "user_id": 2
     }
     ```
   - `DELETE /courses/{course}/enrollments/{enrollment}` - Remove enrollment
   - `GET /courses/{course}/certificates` - Get course certificates

7. **Modules**
   - `GET /courses/{course}/modules` - List course modules
   - `GET /modules/{id}` - Get specific module
   - `POST /courses/{course}/modules` - Create a new module
     ```json
     {
       "title": "Module 1: Variables and Data Types",
       "description": "Introduction to variables and data types",
       "sequence_number": 1
     }
     ```
   - `PUT /modules/{id}` - Update a module
     ```json
     {
       "title": "Updated Module Title",
       "sequence_number": 2
     }
     ```
   - `DELETE /modules/{id}` - Delete a module

8. **Module Contents**
   - `GET /modules/{module}/contents` - Fetch all content for a module
   - `GET /contents/{id}` - Fetch a single content item
   - `POST /modules/{module}/contents` - Create new content for a module
     ```json
     {
       "content_type": "video",
       "title": "Introduction Video",
       "content_url": "https://example.com/video.mp4",
       "sequence_number": 1
     }
     ```
   - `PUT /contents/{id}` - Update a content item
     ```json
     {
       "title": "Updated Content Title",
       "content_url": "https://example.com/updated-video.mp4"
     }
     ```
   - `DELETE /contents/{id}` - Delete a content item

9. **Assessments**
   - `GET /modules/{module}/assessments` - Fetch all assessments for a module
   - `GET /assessments/{id}` - Fetch a single assessment
   - `POST /modules/{module}/assessments` - Create a new assessment for a module
     ```json
     {
       "title": "Module 1 Quiz",
       "description": "Test your knowledge of variables and data types",
       "time_limit_minutes": 30,
       "passing_score": 70
     }
     ```
   - `PUT /assessments/{id}` - Update an assessment
     ```json
     {
       "title": "Updated Assessment Title",
       "passing_score": 75
     }
     ```
   - `DELETE /assessments/{id}` - Delete an assessment

10. **Assessment Questions**
    - `GET /assessments/{assessment}/questions` - Fetch all questions for an assessment
    - `GET /questions/{id}` - Fetch a single question
    - `POST /assessments/{assessment}/questions` - Create a new question for an assessment
      ```json
      {
        "question_text": "What is a variable?",
        "question_type": "multiple_choice",
        "options": ["A memory location", "A data type", "A function", "A class"],
        "correct_answer": "A memory location",
        "points": 10
      }
      ```
    - `PUT /questions/{id}` - Update a question
      ```json
      {
        "question_text": "Updated question text",
        "points": 15
      }
      ```
    - `DELETE /questions/{id}` - Delete a question

11. **Certificates**
    - `GET /certificates` - Fetch all certificates
    - `GET /certificates/{id}` - Fetch a single certificate
    - `POST /certificates` - Create a new certificate
      ```json
      {
        "user_id": 2,
        "course_id": 1,
        "issue_date": "2023-06-15",
        "certificate_title": "Certificate of Completion",
        "certificate_url": "https://example.com/certificates/12345.pdf"
      }
      ```
    - `PUT /certificates/{id}` - Update a certificate
      ```json
      {
        "certificate_url": "https://example.com/certificates/updated.pdf"
      }
      ```
    - `DELETE /certificates/{id}` - Delete a certificate

12. **Feedback**
    - `GET /feedback` - Fetch all feedback
    - `GET /feedback/{id}` - Fetch a single feedback
    - `POST /feedback` - Create a new feedback
      ```json
      {
        "student_id": 2,
        "faculty_id": 3,
        "module_id": 1,
        "feedback_text": "Great work on your assignment!",
        "rating": 5
      }
      ```
    - `PUT /feedback/{id}` - Update a feedback
      ```json
      {
        "feedback_text": "Updated feedback text",
        "rating": 4
      }
      ```
    - `DELETE /feedback/{id}` - Delete a feedback

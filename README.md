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
- **GET /api/v1/users/{user}/assessment-results**: Gets all assessment results for a user
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

### 5. Assessment Results (`assessment_results` table)
- **GET /api/v1/assessment-results**: Fetches all assessment results
- **GET /api/v1/assessment-results/{id}**: Fetches a single assessment result
- **POST /api/v1/assessment-results**: Creates a new assessment result
- **PUT /api/v1/assessment-results/{id}**: Updates an assessment result
- **DELETE /api/v1/assessment-results/{id}**: Deletes an assessment result

### 6. Courses (`courses` table)
- **GET /api/v1/courses**: Fetches all courses
- **GET /api/v1/courses/{id}**: Fetches a single course with its modules
- **POST /api/v1/courses**: Creates a new course
- **PUT /api/v1/courses/{id}**: Updates a course
- **DELETE /api/v1/courses/{id}**: Deletes a course (only if it has no enrollments, modules, or certificates)
- **GET /api/v1/courses/{course}/enrollments**: Gets all enrollments for a course
- **POST /api/v1/courses/{course}/enrollments**: Adds a new enrollment to a course
- **DELETE /api/v1/courses/{course}/enrollments/{enrollment}**: Removes an enrollment from a course
- **GET /api/v1/courses/{course}/certificates**: Gets all certificates for a course

### 7. Modules (`modules` table)
- **GET /api/v1/courses/{course}/modules**: Fetches all modules for a course
- **GET /api/v1/modules/{id}**: Fetches a single module
- **POST /api/v1/courses/{course}/modules**: Creates a new module in a course
- **PUT /api/v1/modules/{id}**: Updates a module
- **DELETE /api/v1/modules/{id}**: Deletes a module

### 8. Module Contents (`module_contents` table)
- **GET /api/v1/modules/{module}/contents**: Fetches all content for a module
- **GET /api/v1/contents/{id}**: Fetches a single content item
- **POST /api/v1/modules/{module}/contents**: Creates new content for a module
- **PUT /api/v1/contents/{id}**: Updates a content item
- **DELETE /api/v1/contents/{id}**: Deletes a content item

### 9. Certificates (`certificates` table)
- **GET /api/v1/certificates**: Fetches all certificates
- **GET /api/v1/certificates/{id}**: Fetches a single certificate
- **POST /api/v1/certificates**: Creates a new certificate
- **PUT /api/v1/certificates/{id}**: Updates a certificate
- **DELETE /api/v1/certificates/{id}**: Deletes a certificate

### 10. Feedback (`feedback` table)
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
- A `Course` belongs to a `LearningStyle` (`learning_style_id` foreign key)
- A `Course` has many `Modules`
- A `Module` belongs to a `Course`
- A `Module` has many `ModuleContents`
- A `ModuleContent` belongs to a `Module` and optionally to a `LearningStyle`
- A `User` has many `Enrollments`, `AssessmentResults`, and `Certificates`
- A `User` can give and receive `Feedback`

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
   - `GET /users/{user}/assessment-results` - Get user's assessment results
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

5. **Assessment Results**
   - `GET /assessment-results` - List all assessment results
   - `GET /assessment-results/{id}` - Get specific assessment result
   - `POST /assessment-results` - Create a new assessment result
     ```json
     {
       "user_id": 2,
       "module_id": 1,
       "score": 85,
       "completed_date": "2023-06-15"
     }
     ```
   - `PUT /assessment-results/{id}` - Update an assessment result
     ```json
     {
       "score": 90
     }
     ```
   - `DELETE /assessment-results/{id}` - Delete an assessment result

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

9. **Certificates**
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

10. **Feedback**
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

## Learning Style Classifier API

The project includes a machine learning-based API that classifies users into Honey-Mumford learning styles based on their questionnaire responses.

### Setup and Running

1. Install the required dependencies:
```bash
cd algorithm
pip install -r requirements.txt
pip3 install -U scikit-learn
```

2. Run the Flask application:
```bash
python app.py
```

The server will start on `http://localhost:5000` or `http://127.0.0.1:5000`.

### API Usage

#### Predict Learning Style

**Endpoint:** `POST /predict`

**Request Body:**
```json
{
    "answers": [0, 1, 0, 1, ...] // Array of 80 binary values (0 or 1)
}
```

**Example Request for Pragmatist Style:**
```json
{
    "answers": [
        0, 0, 0, 0, 1, 0, 0, 0, 1, 0,  // 1-10
        1, 0, 0, 0, 0, 0, 0, 0, 1, 0,  // 11-20
        1, 0, 0, 0, 0, 0, 1, 0, 0, 0,  // 21-30
        0, 0, 0, 0, 1, 0, 1, 0, 0, 0,  // 31-40
        0, 0, 0, 1, 0, 0, 0, 0, 1, 1,  // 41-50
        0, 0, 1, 1, 0, 1, 0, 0, 1, 0,  // 51-60
        0, 0, 0, 0, 1, 0, 0, 1, 1, 1,  // 61-70
        0, 0, 1, 1, 0, 0, 0, 0, 0, 1   // 71-80
    ]
}
```

**Example Request for Reflector Style:**
```json
{
    "answers": [
        0, 0, 0, 0, 0, 0, 1, 0, 0, 0,  // 1-10: Yes to Q7 (likes thorough preparation)
        0, 0, 1, 0, 1, 1, 0, 0, 0, 0,  // 11-20: Yes to Q13,15,16 (thorough job, careful interpretation, weighing alternatives)
        0, 0, 0, 0, 1, 1, 0, 1, 1, 0,  // 21-30: Yes to Q25,26,28,29 (meticulous, careful, gathering information)
        1, 0, 1, 0, 0, 1, 0, 0, 1, 0,  // 31-40: Yes to Q31,33,36,39 (listens first, observes others, worries about rushing)
        0, 1, 0, 0, 0, 1, 0, 0, 0, 0,  // 41-50: Yes to Q41,46 (thorough analysis, standing back)
        0, 1, 0, 0, 1, 0, 0, 0, 0, 1,  // 51-60: Yes to Q52,55,60 (specific discussions, multiple drafts, many alternatives)
        0, 1, 0, 0, 0, 1, 1, 0, 0, 0,  // 61-70: Yes to Q62,66,67 (low profile, careful thinking, listening)
        0, 0, 0, 0, 0, 1, 1, 0, 0, 0   // 71-80: Yes to Q76,77 (interested in others' thoughts, methodical)
    ]
}
```

This example emphasizes Reflector characteristics by setting positive responses (1) to questions that indicate:
- Preference for thorough preparation and implementation
- Careful observation and analysis before action
- Interest in gathering multiple perspectives
- Tendency to listen more than speak
- Methodical and detailed approach to work
- Preference for standing back and observing

**Response:**
```
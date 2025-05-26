# ForeSITE Learning System API Documentation

This document provides detailed information about the API endpoints available in the ForeSITE Learning System.

## Base URL

All API endpoints are prefixed with: `/api/v1`

## Authentication

Currently, the API endpoints are publicly accessible for testing purposes. Authentication will be implemented in future versions.

## Response Format

All responses are in JSON format. Successful responses include a `data` key containing the requested information:

```json
{
    "data": {
        // Response data
    }
}
```

Error responses include an `error` key containing the error message:

```json
{
    "error": "Error message"
}
```

## API Flow and Database Interaction

The API provides CRUD (Create, Read, Update, Delete) operations for the main entities in the database schema. Here's how the API routes interact with the database tables:

### 1. Authentication
- **POST /api/v1/auth/register**: Register a new user
- **POST /api/v1/auth/login**: Login a user

### 2. Users (`users` table)
- **GET /api/v1/users**: Fetches all users with their associated roles
- **GET /api/v1/users/{id}**: Fetches a single user with associated role and student profile
- **POST /api/v1/users**: Creates a new user
- **PUT /api/v1/users/{id}**: Updates a user
- **DELETE /api/v1/users/{id}**: Deletes a user
- **GET /api/v1/users/{user}/role**: Gets the role of a specific user
- **PUT /api/v1/users/{user}/role**: Updates the role of a specific user
- **GET /api/v1/users/{user}/enrollments**: Gets all enrollments for a user
- **GET /api/v1/users/{user}/assessment-results**: Gets all assessment results for a user
- **GET /api/v1/users/{user}/certificates**: Gets all certificates for a user
- **GET /api/v1/users/{user}/received-feedback**: Gets feedback received by the user
- **GET /api/v1/users/{user}/given-feedback**: Gets feedback given by the user

### 3. Roles (`roles` table)
- **GET /api/v1/roles**: List all roles
- **GET /api/v1/roles/{id}**: Get specific role
- **POST /api/v1/roles**: Create a new role
- **PUT /api/v1/roles/{id}**: Update a role
- **DELETE /api/v1/roles/{id}**: Delete a role

### 4. Learning Styles (`learning_styles` table)
- **GET /api/v1/learning-styles**: List all learning styles
- **GET /api/v1/learning-styles/{id}**: Get specific learning style
- **POST /api/v1/learning-styles**: Create a new learning style
- **PUT /api/v1/learning-styles/{id}**: Update a learning style
- **DELETE /api/v1/learning-styles/{id}**: Delete a learning style

### 5. Student Profiles (`student_profiles` table)
- **GET /api/v1/student-profiles**: List all student profiles
- **GET /api/v1/student-profiles/{id}**: Get specific student profile
- **POST /api/v1/student-profiles**: Create a new student profile
- **PUT /api/v1/student-profiles/{id}**: Update a student profile
- **DELETE /api/v1/student-profiles/{id}**: Delete a student profile

### 6. Assessment Results (`assessment_results` table)
- **GET /api/v1/assessment-results**: List all assessment results
- **GET /api/v1/assessment-results/{id}**: Get specific assessment result
- **POST /api/v1/assessment-results**: Create a new assessment result
- **PUT /api/v1/assessment-results/{id}**: Update an assessment result
- **DELETE /api/v1/assessment-results/{id}**: Delete an assessment result

### 7. Courses (`courses` table)
- **GET /api/v1/courses**: List all courses
- **GET /api/v1/courses/{id}**: Get specific course
- **POST /api/v1/courses**: Create a new course
- **PUT /api/v1/courses/{id}**: Update a course
- **DELETE /api/v1/courses/{id}**: Delete a course
- **GET /api/v1/courses/{course}/enrollments**: Get course enrollments
- **POST /api/v1/courses/{course}/enrollments**: Add new enrollment
- **DELETE /api/v1/courses/{course}/enrollments/{enrollment}**: Remove enrollment
- **GET /api/v1/courses/{course}/certificates**: Get course certificates

### 8. Modules (`modules` table)
- **GET /api/v1/courses/{course}/modules**: List course modules
- **GET /api/v1/modules/{id}**: Get specific module
- **POST /api/v1/courses/{course}/modules**: Create a new module
- **PUT /api/v1/modules/{id}**: Update a module
- **DELETE /api/v1/modules/{id}**: Delete a module

### 9. Module Progress (`module_progress` table)
- **GET /api/v1/module-progress**: List all module progress entries
- **GET /api/v1/module-progress/{id}**: Get specific module progress
- **POST /api/v1/module-progress**: Create a new module progress (supports single or bulk insert)
- **PUT /api/v1/module-progress/{id}**: Update a module progress
- **DELETE /api/v1/module-progress/{id}**: Delete a module progress
- **GET /api/v1/users/{user}/module-progress**: Get all module progress entries for a specific user
- **GET /api/v1/courses/{course}/module-progress**: Get all module progress entries for a specific course

### 10. Module Contents (`module_contents` table)
- **GET /api/v1/modules/{module}/contents**: List all content for a module
- **GET /api/v1/module-progress/{moduleId}/contents**: List all content for a module progress
- **GET /api/v1/contents/{id}**: Get specific content
- **POST /api/v1/modules/{module}/contents**: Create new content for a module
- **POST /api/v1/module-progress/{moduleId}/contents**: Create new content for a module progress
- **PUT /api/v1/contents/{id}**: Update content
- **DELETE /api/v1/contents/{id}**: Delete content

### 11. Certificates (`certificates` table)
- **GET /api/v1/certificates**: List all certificates
- **GET /api/v1/certificates/{id}**: Get specific certificate
- **POST /api/v1/certificates**: Create a new certificate
- **PUT /api/v1/certificates/{id}**: Update a certificate
- **DELETE /api/v1/certificates/{id}**: Delete a certificate

### 12. Feedback (`feedback` table)
- **GET /api/v1/feedback**: List all feedback
- **GET /api/v1/feedback/{id}**: Get specific feedback
- **POST /api/v1/feedback**: Create a new feedback
- **PUT /api/v1/feedback/{id}**: Update a feedback
- **DELETE /api/v1/feedback/{id}**: Delete a feedback

### 13. Lesson Screens (`lesson_screens` table)
- **GET /api/v1/lesson-screens**: List all lesson screens
- **GET /api/v1/lesson-screens/{id}**: Get specific lesson screen
- **POST /api/v1/lesson-screens**: Create a new lesson screen
- **PUT /api/v1/lesson-screens/{id}**: Update a lesson screen
- **DELETE /api/v1/lesson-screens/{id}**: Delete a lesson screen
- **GET /api/v1/courses/{course}/lesson-screens**: Get all lesson screens for a specific course
- **GET /api/v1/courses/{course}/modules/{module}/lesson-screens**: Get all lesson screens for a specific course module

### 14. Enrollments (`enrollments` table)
- **POST /api/v1/enrollments**: Create a new enrollment
  ```json
  {
    "user_id": 1,
    "course_id": 1,
    "assessment_result_id": 1
  }
  ```
  **Response (201 Created)**
  ```json
  {
    "status": "success",
    "message": "Enrollment created successfully",
    "data": {
      "enrollment_id": 1,
      "user_id": 1,
      "course_id": 1,
      "assessment_result_id": 1,
      "enrollment_date": "2024-03-20T12:00:00Z",
      "completion_status": "not_started",
      "completion_date": null
    }
  }
  ```
  **Error Responses**
  - 409 Conflict: User already enrolled in the course
  - 422 Unprocessable Entity: Validation failed (invalid/missing fields)
  - 500 Internal Server Error: Something went wrong during enrollment creation

## Testing with Postman or APIdog

1. Import the following cURL commands into your API client to test the API:

```bash
# Get all roles
curl -X GET "http://localhost:8000/api/v1/roles" -H "Accept: application/json"

# Create a new enrollment
curl -X POST "http://localhost:8000/api/v1/enrollments" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_id": 1,
    "course_id": 1,
    "assessment_result_id": 1
}'
```

2. Create a collection for all the endpoints to easily test them.

## Database Setup

Before using the API, make sure you:

1. Create a MySQL database named `foresite_db`
2. Import the schema.sql file using phpMyAdmin
3. The database tables will be created based on the schema.sql file

## Future Enhancements

1. Authentication and Authorization
2. Rate Limiting
3. Pagination for large result sets
4. Advanced filtering and searching

## Module Progress API Examples

### Create a Single Module Progress Entry
```json
// POST /api/v1/module-progress
{
  "user_id": 1,
  "course_id": 1,
  "module_number": 1,
  "module_title": "Introduction to Futures Thinking",
  "module_focus": "Use of drivers, scenarios, and the Three Horizons model",
  "status": "not_started",
  "progress_percentage": 0,
  "started_at": null,
  "completed_at": null,
  "time_spent_minutes": 0,
  "score": null
}
```

### Create Multiple Module Progress Entries
```json
// POST /api/v1/module-progress
{
  "data": [
    {
      "user_id": 1,
      "course_id": 1,
      "module_number": 1,
      "module_title": "Introduction to Futures Thinking",
      "module_focus": "Use of drivers, scenarios, and the Three Horizons model",
      "status": "not_started",
      "progress_percentage": 0,
      "time_spent_minutes": 0
    },
    {
      "user_id": 1,
      "course_id": 1,
      "module_number": 2,
      "module_title": "Futures Process Design",
      "module_focus": "Building projects with clear aims",
      "status": "not_started",
      "progress_percentage": 0,
      "time_spent_minutes": 0
    }
  ]
}
```

## Module Content API Examples

### Create a Module Content Entry
```json
// POST /api/v1/module-progress/{moduleId}/contents
{
  "content_type": "text",
  "content_title": "Introduction to the Module",
  "content_data": "This is the content text for the introduction...",
  "sequence_order": 1
}
```

## Lesson Screens API Examples

### Create a Lesson Screen
```json
// POST /api/v1/lesson-screens
{
  "course_id": 1,
  "course_module_number": 1,
  "screen_number": 1,
  "screen_title": "Introduction to Futures Thinking",
  "screen_description": "Overview of the key concepts",
  "screen_content": {
    "content": "This is the introduction content...",
    "type": "text"
  },
  "screen_url": null
}
```

### Response Format for Fetching Lesson Screens
```json
// GET /api/v1/lesson-screens or GET /api/v1/lesson-screens/{id}
{
  "data": {
    "lesson_screen_id": 1,
    "course_id": 1,
    "course_module_number": 1,
    "screen_number": 1,
    "screen_title": "Introduction to Futures Thinking",
    "screen_description": "Overview of the key concepts",
    "screen_content": {
      "content": "This is the introduction content...",
      "type": "text"
    },
    "screen_url": null,
    "course": {
      "course_id": 1,
      "name": "Applied Strategic Foresight: Practical Tools for Future-Ready Decision Making",
      "description": "This course provides a comprehensive overview of strategic foresight tools and techniques...",
      "objectives": [...],
      "structure": [...],
      "learning_style_id": 1,
      "created_at": "2024-05-25T10:00:00Z"
    }
  }
}
```

### Get Lesson Screens for a Course Module
```json
// GET /api/v1/courses/{courseId}/modules/{moduleNumber}/lesson-screens
{
  "data": [
    {
      "lesson_screen_id": 1,
      "course_id": 1,
      "course_module_number": 1,
      "screen_number": 1,
      "screen_title": "Introduction to Futures Thinking",
      "screen_description": "Overview of the key concepts",
      "screen_content": {
        "content": "This is the introduction content...",
        "type": "text"
      },
      "screen_url": null
    },
    {
      "lesson_screen_id": 2,
      "course_id": 1,
      "course_module_number": 1,
      "screen_number": 2,
      "screen_title": "Key Principles of Strategic Foresight",
      "screen_description": "Understanding the fundamental principles",
      "screen_content": {
        "content": "This is the content about key principles...",
        "type": "text"
      },
      "screen_url": null
    }
  ]
}
```

### Get All Lesson Screens for a Course
```json
// GET /api/v1/courses/{courseId}/lesson-screens
{
  "data": [
    {
      "lesson_screen_id": 1,
      "course_id": 1,
      "course_module_number": 1,
      "screen_number": 1,
      "screen_title": "Introduction to Futures Thinking",
      "screen_description": "Overview of the key concepts",
      "screen_content": {
        "content": "This is the introduction content...",
        "type": "text"
      },
      "screen_url": null
    },
    {
      "lesson_screen_id": 2,
      "course_id": 1,
      "course_module_number": 1,
      "screen_number": 2,
      "screen_title": "Key Principles of Strategic Foresight",
      "screen_description": "Understanding the fundamental principles",
      "screen_content": {
        "content": "This is the content about key principles...",
        "type": "text"
      },
      "screen_url": null
    },
    {
      "lesson_screen_id": 3,
      "course_id": 1,
      "course_module_number": 2,
      "screen_number": 1,
      "screen_title": "Futures Process Design",
      "screen_description": "Building projects with clear aims",
      "screen_content": {
        "content": "This is the content about process design...",
        "type": "text"
      },
      "screen_url": null
    }
  ]
}
```
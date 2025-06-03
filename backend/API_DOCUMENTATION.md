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
- **GET /api/v1/users/{user}/all-progress**: Get all module progress and lesson screen progress for a user
- **PUT /api/v1/module-progress/{id}**: Update a module progress
- **DELETE /api/v1/module-progress/{id}**: Delete a module progress
- **GET /api/v1/users/{user}/module-progress**: Get all module progress entries for a specific user
- **GET /api/v1/courses/{course}/module-progress**: Get all module progress entries for a specific course

### 10. Lesson Screen Progress (`lesson_screen_progress` table)
- **GET /api/v1/module-progress/{moduleId}/screen-progress**: List all screen progress entries for a module progress
- **GET /api/v1/screen-progress/{id}**: Get specific screen progress entry
- **POST /api/v1/module-progress/{moduleId}/screen-progress**: Create new screen progress entry
- **PUT /api/v1/screen-progress/{id}**: Update screen progress entry
- **DELETE /api/v1/screen-progress/{id}**: Delete screen progress entry
- **GET /api/v1/lesson-screens/{lessonScreenId}/progress**: Get progress entries for a specific lesson screen for a module progress

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
- **GET /api/v1/courses/{course}/lesson-screens**: Get all lesson screens associated with a specific course through module progress
- **GET /api/v1/courses/{course}/modules/{module}/lesson-screens**: Get all lesson screens for a specific course module through module progress

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

### 15. Module Assessments (`module_assessment` table)
- **GET /api/v1/module-assessments**: List all module assessments
- **GET /api/v1/module-assessments/{id}**: Get specific module assessment
- **POST /api/v1/module-assessments**: Create a new module assessment
- **PUT /api/v1/module-assessments/{id}**: Update a module assessment
- **DELETE /api/v1/module-assessments/{id}**: Delete a module assessment
- **GET /api/v1/courses/{course}/module-assessments**: Get all assessments for a specific course
- **GET /api/v1/courses/{course}/modules/{module}/assessment**: Get assessment for a specific course module

### Module Assessment API Examples

### Get Single Module Assessment
```json
// GET /api/v1/module-assessments/{id}
{
  "data": {
    "assessment_id": 1,
    "course_id": 1,
    "module_number": 1,
    "assessment_title": "Future Shapers: Mapping Change Drivers",
    "assessment_objective": "Work within your group. Identify and analyze change drivers and weak signals using the PESTLE framework.",
    "assessment_scenario": null,
    "assessment_instructions": [
      {
        "step_title": "Spot the Current Change Driver",
        "step_description": "Find a current event (news, blog, tweet) that highlights a shift or trend influencing the future. Write a brief description of the change."
      },
      {
        "step_title": "Categorize the PESTLE",
        "step_description": "Classify the change using PESTLE framework."
      }
    ]
  }
}
```

### Create Module Assessment
```json
// POST /api/v1/module-assessments
{
  "course_id": 1,
  "module_number": 1,
  "assessment_title": "New Assessment",
  "assessment_objective": "Assessment objective here",
  "assessment_scenario": "Optional scenario description",
  "assessment_instructions": [
    {
      "step_title": "Step 1",
      "step_description": "Description of step 1"
    }
  ]
}
```

### 16. Module Assessment Progress (`module_assessment_progress` table)
- **GET /api/v1/module-assessment-progress**: List all assessment progress entries
- **GET /api/v1/module-assessment-progress/{id}**: Get specific assessment progress
- **POST /api/v1/module-assessment-progress**: Create a new assessment progress entry
- **PUT /api/v1/module-assessment-progress/{id}**: Update assessment progress
- **DELETE /api/v1/module-assessment-progress/{id}**: Delete assessment progress
- **GET /api/v1/users/{user}/assessment-progress**: Get all assessment progress for a specific user
- **GET /api/v1/module-assessments/{assessment}/progress**: Get all progress entries for a specific assessment

### Module Assessment Progress API Examples

### Get User's Assessment Progress
```json
// GET /api/v1/users/{userId}/assessment-progress
{
  "data": [
    {
      "assessment_progress_id": 1,
      "module_assessment_id": 1,
      "user_id": 1,
      "status": "completed",
      "module_assessment": {
        "assessment_id": 1,
        "course_id": 1,
        "module_number": 1,
        "assessment_title": "Future Shapers: Mapping Change Drivers",
        "assessment_objective": "Work within your group. Identify and analyze change drivers and weak signals using the PESTLE framework."
      }
    }
  ]
}
```

### Create Assessment Progress Entry
```json
// POST /api/v1/module-assessment-progress
{
  "module_assessment_id": 1,
  "user_id": 1,
  "status": "in_progress"
}
```

### 17. Scores (`scores` table)
- **GET /api/v1/scores**: List all scores
- **GET /api/v1/scores/{id}**: Get specific score
- **POST /api/v1/scores**: Create a new score
- **PUT /api/v1/scores/{id}**: Update a score
- **DELETE /api/v1/scores/{id}**: Delete a score
- **GET /api/v1/scores/faculty/{facultyId}**: Get all scores given by a specific faculty member
- **GET /api/v1/scores/assessment-progress/{progressId}**: Get all scores for a specific assessment progress

### Score API Examples

### Get Single Score
```json
// GET /api/v1/scores/{id}
{
  "data": {
    "score_id": 1,
    "faculty_id": 1,
    "score": 4,
    "module_assessment_progress_id": 1,
    "faculty": {
      "user_id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com"
    },
    "module_assessment_progress": {
      "assessment_progress_id": 1,
      "module_assessment_id": 1,
      "user_id": 2,
      "status": "completed"
    }
  }
}
```

### Create Score
```json
// POST /api/v1/scores
{
  "faculty_id": 1,
  "score": 4,
  "module_assessment_progress_id": 1
}
```

### 18. Feedback (`feedback` table)
- **GET /api/v1/feedback**: List all feedback
- **GET /api/v1/feedback/{id}**: Get specific feedback
- **POST /api/v1/feedback**: Create a new feedback
- **PUT /api/v1/feedback/{id}**: Update a feedback
- **DELETE /api/v1/feedback/{id}**: Delete a feedback
- **GET /api/v1/feedback/faculty/{facultyId}**: Get all feedback given by a specific faculty member
- **GET /api/v1/feedback/assessment-progress/{progressId}**: Get all feedback for a specific assessment progress

### Feedback API Examples

### Get Single Feedback
```json
// GET /api/v1/feedback/{id}
{
  "data": {
    "feedback_id": 1,
    "faculty_id": 1,
    "feedback": "Great work on understanding the concepts. Consider exploring more practical applications.",
    "module_assessment_progress_id": 1,
    "faculty": {
      "user_id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com"
    },
    "module_assessment_progress": {
      "assessment_progress_id": 1,
      "module_assessment_id": 1,
      "user_id": 2,
      "status": "completed"
    }
  }
}
```

### Create Feedback
```json
// POST /api/v1/feedback
{
  "faculty_id": 1,
  "feedback": "Great work on understanding the concepts. Consider exploring more practical applications.",
  "module_assessment_progress_id": 1
}
```

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

## Lesson Screen Progress API Examples

### Get Single Screen Progress Entry
```json
// GET /api/v1/screen-progress/{screenProgressId}
{
  "data": {
    "screen_progress_id": 1,
    "module_progress_id": 1,
    "lesson_screen_id": 1,
    "status": "completed",
    "progress_percentage": 100,
    "module_progress": {
      "progress_id": 1,
      "user_id": 1,
      "course_id": 1,
      "module_number": 1,
      "module_title": "Introduction to Futures Thinking",
      "module_focus": "Use of drivers, scenarios, and the Three Horizons model",
      "status": "in_progress",
      "progress_percentage": 50,
      "started_at": "2024-05-25T10:00:00Z",
      "completed_at": null,
      "time_spent_minutes": 30,
      "score": null
    },
    "lesson_screen": {
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
    }
  }
}
```

### Create Multiple Screen Progress Entries
```json
// POST /api/v1/module-progress/{moduleId}/screen-progress
{
  "data": [
    {
      "lesson_screen_id": 1,
      "status": "completed",
      "progress_percentage": 100
    },
    {
      "lesson_screen_id": 2,
      "status": "in_progress",
      "progress_percentage": 50
    }
  ]
}
```

### Update Screen Progress
```json
// PUT /api/v1/screen-progress/{screenProgressId}
{
  "status": "completed",
  "progress_percentage": 100
}
```

### Get Module Progress Screen Entries
```json
// GET /api/v1/module-progress/{moduleId}/screen-progress
{
  "data": [
    {
      "screen_progress_id": 1,
      "module_progress_id": 1,
      "lesson_screen_id": 1,
      "status": "completed",
      "progress_percentage": 100,
      "lesson_screen": {
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
      }
    },
    {
      "screen_progress_id": 2,
      "module_progress_id": 1,
      "lesson_screen_id": 2,
      "status": "in_progress",
      "progress_percentage": 50,
      "lesson_screen": {
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
    }
  ]
}
```

### Get All User Progress
```json
// GET /api/v1/users/{userId}/all-progress
{
  "data": {
    "courses": [
      {
        "course_id": 1,
        "modules": [
          {
            "module_progress_id": 1,
            "course_id": 1,
            "module_number": 1,
            "module_title": "Introduction to Futures Thinking",
            "status": "in_progress",
            "completion_percentage": 50,
            "lesson_screen_progress": [
              {
                "screen_progress_id": 2,
                "module_progress_id": 1,
                "lesson_screen_id": 2,
                "status": "in_progress",
                "progress_percentage": 50,
                "lesson_screen": {
                  "lesson_screen_id": 2,
                  "course_id": 1,
                  "course_module_number": 1,
                  "screen_number": "1.2",
                  "screen_title": "Key Principles of Strategic Foresight",
                  "screen_description": "Understanding the fundamental principles",
                  "screen_content": {
                    "content": "This is the content about key principles...",
                    "type": "text"
                  },
                  "screen_url": null
                }
              },
              {
                "screen_progress_id": 1,
                "module_progress_id": 1,
                "lesson_screen_id": 1,
                "status": "not_started",
                "progress_percentage": 0,
                "lesson_screen": {
                  "lesson_screen_id": 1,
                  "course_id": 1,
                  "course_module_number": 1,
                  "screen_number": "1.1",
                  "screen_title": "Introduction to Futures Thinking",
                  "screen_description": "Overview of the key concepts",
                  "screen_content": {
                    "content": "This is the introduction content...",
                    "type": "text"
                  },
                  "screen_url": null
                }
              }
            ]
          },
          {
            "module_progress_id": 2,
            "course_id": 1,
            "module_number": 2,
            "module_title": "Futures Process Design",
            "status": "not_started",
            "completion_percentage": 0,
            "lesson_screen_progress": []
          }
        ]
      }
    ]
  }
}
```

### Lesson Screens API Examples

### Create a Lesson Screen
```json
// POST /api/v1/lesson-screens
{
  "screen_number": "1.1", // Now accepts alphanumeric values like "1", "1.1", "2a", etc.
  "screen_title": "Introduction to Futures Thinking", // Optional, can be null
  "screen_description": "Overview of the key concepts", // Optional, can be null
  "screen_content": { // Optional, can be null
    "content": "This is the introduction content...",
    "type": "text"
  },
  "screen_url": null, // Optional
  "screen_duration": "15 minutes" // Optional, can be null
}
```

### Response Format for Fetching Lesson Screens
```json
// GET /api/v1/lesson-screens or GET /api/v1/lesson-screens/{id}
{
  "data": {
    "lesson_screen_id": 1,
    "screen_number": "1.1", // Now returned as a string value
    "screen_title": "Introduction to Futures Thinking",
    "screen_description": "Overview of the key concepts",
    "screen_content": {
      "content": "This is the introduction content...",
      "type": "text"
    },
    "screen_url": null,
    "screen_duration": "15 minutes"
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
      "screen_number": "1.1", // Now uses string format for alphanumeric screen numbers
      "screen_title": "Introduction to Futures Thinking",
      "screen_description": "Overview of the key concepts",
      "screen_content": {
        "content": "This is the introduction content...",
        "type": "text"
      },
      "screen_url": null,
      "screen_duration": "15 minutes"
    },
    {
      "lesson_screen_id": 2,
      "screen_number": "1.2", // Now uses string format for alphanumeric screen numbers
      "screen_title": "Key Principles of Strategic Foresight",
      "screen_description": "Understanding the fundamental principles",
      "screen_content": {
        "content": "This is the content about key principles...",
        "type": "text"
      },
      "screen_url": null,
      "screen_duration": "12 minutes"
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
      "screen_url": null,
      "screen_duration": "15 minutes"
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
      "screen_url": null,
      "screen_duration": "12 minutes"
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
      "screen_url": null,
      "screen_duration": "18 minutes"
    }
  ]
}
```
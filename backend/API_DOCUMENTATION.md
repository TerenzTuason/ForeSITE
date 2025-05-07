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

## API Endpoints

### Roles

#### Get All Roles

- **URL:** `/api/v1/roles`
- **Method:** `GET`
- **Response:** List of all roles

Example Response:
```json
{
    "data": [
        {
            "role_id": 1,
            "role_name": "student",
            "description": "Regular student user"
        },
        {
            "role_id": 2,
            "role_name": "faculty",
            "description": "Faculty member with teaching privileges"
        },
        {
            "role_id": 3,
            "role_name": "admin",
            "description": "System administrator with full access"
        }
    ]
}
```

#### Get Role by ID

- **URL:** `/api/v1/roles/{id}`
- **Method:** `GET`
- **Response:** Single role

Example Response:
```json
{
    "data": {
        "role_id": 1,
        "role_name": "student",
        "description": "Regular student user"
    }
}
```

#### Create Role

- **URL:** `/api/v1/roles`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
      "role_name": "student",
      "description": "Regular student user"
  }
  ```
- **Response:** Created role

#### Update Role

- **URL:** `/api/v1/roles/{id}`
- **Method:** `PUT`
- **Request Body:**
  ```json
  {
      "description": "Updated description"
  }
  ```
- **Response:** Updated role

#### Delete Role

- **URL:** `/api/v1/roles/{id}`
- **Method:** `DELETE`
- **Response:** No content (204)

### Users

#### Get All Users

- **URL:** `/api/v1/users`
- **Method:** `GET`
- **Response:** List of all users with their roles

Example Response:
```json
{
    "data": [
        {
            "user_id": 1,
            "role_id": 3,
            "email": "admin@example.com",
            "first_name": "John",
            "last_name": "Doe",
            "created_at": "2023-05-07T04:00:00.000000Z",
            "last_login": null,
            "is_active": true,
            "role": {
                "role_id": 3,
                "role_name": "admin",
                "description": "System administrator with full access"
            }
        }
    ]
}
```

#### Get User by ID

- **URL:** `/api/v1/users/{id}`
- **Method:** `GET`
- **Response:** Single user with role and student profile if exists

#### Create User

- **URL:** `/api/v1/users`
- **Method:** `POST`
- **Request Body:**
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
- **Response:** Created user

#### Update User

- **URL:** `/api/v1/users/{id}`
- **Method:** `PUT`
- **Request Body:**
  ```json
  {
      "first_name": "Updated Name"
  }
  ```
- **Response:** Updated user

#### Delete User

- **URL:** `/api/v1/users/{id}`
- **Method:** `DELETE`
- **Response:** No content (204)

### Learning Styles

#### Get All Learning Styles

- **URL:** `/api/v1/learning-styles`
- **Method:** `GET`
- **Response:** List of all learning styles

Example Response:
```json
{
    "data": [
        {
            "style_id": 1,
            "style_name": "visual",
            "description": "Preference for visual information like charts, graphs, and diagrams"
        },
        {
            "style_id": 2,
            "style_name": "auditory",
            "description": "Preference for spoken or heard information"
        }
    ]
}
```

#### Get Learning Style by ID

- **URL:** `/api/v1/learning-styles/{id}`
- **Method:** `GET`
- **Response:** Single learning style

#### Create Learning Style

- **URL:** `/api/v1/learning-styles`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
      "style_name": "tactile",
      "description": "Preference for hands-on learning"
  }
  ```
- **Response:** Created learning style

#### Update Learning Style

- **URL:** `/api/v1/learning-styles/{id}`
- **Method:** `PUT`
- **Request Body:**
  ```json
  {
      "description": "Updated description"
  }
  ```
- **Response:** Updated learning style

#### Delete Learning Style

- **URL:** `/api/v1/learning-styles/{id}`
- **Method:** `DELETE`
- **Response:** No content (204)

### Student Profiles

#### Get All Student Profiles

- **URL:** `/api/v1/student-profiles`
- **Method:** `GET`
- **Response:** List of all student profiles with their users and learning styles

#### Get Student Profile by ID

- **URL:** `/api/v1/student-profiles/{id}`
- **Method:** `GET`
- **Response:** Single student profile with user and learning style

#### Create Student Profile

- **URL:** `/api/v1/student-profiles`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
      "user_id": 2,
      "dominant_learning_style_id": 1
  }
  ```
- **Response:** Created student profile

#### Update Student Profile

- **URL:** `/api/v1/student-profiles/{id}`
- **Method:** `PUT`
- **Request Body:**
  ```json
  {
      "dominant_learning_style_id": 2
  }
  ```
- **Response:** Updated student profile

#### Delete Student Profile

- **URL:** `/api/v1/student-profiles/{id}`
- **Method:** `DELETE`
- **Response:** No content (204)

## Testing with Postman or APIdog

1. Import the following cURL commands into your API client to test the API:

```
curl -X GET "http://localhost:8000/api/v1/roles" -H "Accept: application/json"
```

```
curl -X POST "http://localhost:8000/api/v1/users" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "role_id": 1,
    "email": "student@example.com",
    "password": "password123",
    "first_name": "John",
    "last_name": "Doe",
    "is_active": true
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
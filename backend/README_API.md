# ForeSITE Learning System API

This is the backend API for the ForeSITE Learning System, built with Laravel.

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- XAMPP or similar stack (recommended for local development)

## Setup

### 1. Clone the Repository

```bash
git clone <repository_url>
cd foresite/backend
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Update the `.env` file with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foresite_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Setup

Make sure your MySQL server is running, then run migrations:

```bash
php artisan migrate
```

If you want to populate the database with the schema directly from schema.sql:

```bash
mysql -u root < /path/to/schema.sql
```

### 5. Start the Development Server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000/api`.

## API Endpoints

### Authentication

- **POST /api/register** - Register a new user
- **POST /api/login** - Login and get authentication token
- **GET /api/user** - Get authenticated user information
- **POST /api/logout** - Logout user (revoke token)

### Users

- **GET /api/users** - List all users (paginated)
- **GET /api/users/{id}** - Get a specific user
- **POST /api/users** - Create a new user
- **PUT /api/users/{id}** - Update a user
- **DELETE /api/users/{id}** - Delete a user

### Roles

- **GET /api/roles** - List all roles
- **GET /api/roles/{id}** - Get a specific role
- **POST /api/roles** - Create a new role
- **PUT /api/roles/{id}** - Update a role
- **DELETE /api/roles/{id}** - Delete a role

### Learning Styles

- **GET /api/learning-styles** - List all learning styles
- **GET /api/learning-styles/{id}** - Get a specific learning style
- **POST /api/learning-styles** - Create a new learning style
- **PUT /api/learning-styles/{id}** - Update a learning style
- **DELETE /api/learning-styles/{id}** - Delete a learning style

### Courses, Modules, and Content

- **GET /api/courses** - List all courses
- **GET /api/courses/{id}** - Get a specific course with its modules
- **POST /api/courses** - Create a new course
- **PUT /api/courses/{id}** - Update a course
- **DELETE /api/courses/{id}** - Delete a course

Similar endpoints are available for modules, module contents, assessments, etc.

## Authentication

The API uses Laravel Sanctum for token-based authentication. To authenticate:

1. Make a POST request to `/api/login` with email and password
2. Include the returned token in subsequent requests in the Authorization header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

## Error Handling

The API returns appropriate HTTP status codes:

- 200 - Success
- 201 - Created
- 400 - Bad Request
- 401 - Unauthorized
- 403 - Forbidden
- 404 - Not Found
- 422 - Validation Error
- 500 - Server Error

All error responses include a message explaining the error. 
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

## API Documentation

### Available Endpoints

The backend provides RESTful API endpoints for the following resources:

1. **Roles**
   - `GET /api/v1/roles` - List all roles
   - `GET /api/v1/roles/{id}` - Get specific role
   - `POST /api/v1/roles` - Create a new role
   - `PUT /api/v1/roles/{id}` - Update a role
   - `DELETE /api/v1/roles/{id}` - Delete a role

2. **Users**
   - `GET /api/v1/users` - List all users
   - `GET /api/v1/users/{id}` - Get specific user
   - `POST /api/v1/users` - Create a new user
   - `PUT /api/v1/users/{id}` - Update a user
   - `DELETE /api/v1/users/{id}` - Delete a user

3. **Learning Styles**
   - `GET /api/v1/learning-styles` - List all learning styles
   - `GET /api/v1/learning-styles/{id}` - Get specific learning style
   - `POST /api/v1/learning-styles` - Create a new learning style
   - `PUT /api/v1/learning-styles/{id}` - Update a learning style
   - `DELETE /api/v1/learning-styles/{id}` - Delete a learning style

4. **Student Profiles**
   - `GET /api/v1/student-profiles` - List all student profiles
   - `GET /api/v1/student-profiles/{id}` - Get specific student profile
   - `POST /api/v1/student-profiles` - Create a new student profile
   - `PUT /api/v1/student-profiles/{id}` - Update a student profile
   - `DELETE /api/v1/student-profiles/{id}` - Delete a student profile

These endpoints can be accessed using a tool like Postman, APIdog, or any other API client.

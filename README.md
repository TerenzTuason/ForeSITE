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

### Database Relationships
- A `User` belongs to a `Role` (`role_id` foreign key)
- A `User` can have one `StudentProfile` (one-to-one)
- A `StudentProfile` belongs to a `User` (`user_id` foreign key)
- A `StudentProfile` belongs to a `LearningStyle` (`dominant_learning_style_id` foreign key, optional)
- A `LearningStyle` can have many `StudentProfiles` (one-to-many)
- A `Role` can have many `Users` (one-to-many)

### Example API Flow
1. First, create a role (`POST /api/v1/roles`)
2. Create a user with the role ID (`POST /api/v1/users`)
3. Create a learning style (`POST /api/v1/learning-styles`)
4. Create a student profile with the user ID and learning style ID (`POST /api/v1/student-profiles`)

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

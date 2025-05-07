# ForeSITE

## Frontend
*Frontend setup instructions will be added once the frontend development begins.*

## Backend Setup

### Prerequisites
1. XAMPP (Download from [https://www.apachefriends.org/](https://www.apachefriends.org/))
2. Composer (Download from [https://getcomposer.org/download/](https://getcomposer.org/download/))
3. Git (Download from [https://git-scm.com/downloads](https://git-scm.com/downloads))

### XAMPP Setup
1. Install XAMPP on your system
2. Start Apache and MySQL services from XAMPP Control Panel
3. Create a new database named `foresite` in phpMyAdmin (http://localhost/phpmyadmin)

### Backend Installation
1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd foresite
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
   DB_DATABASE=foresite
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Run database migrations:
   ```bash
   php artisan migrate
   ```

8. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

The backend API will be available at `http://localhost:8000`

### API Documentation
API documentation will be added once the endpoints are implemented.

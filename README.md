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
2. Import the schema.sql file directly into the database using phpMyAdmin:
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

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database connection in `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=foresite_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Create Laravel required tables (sessions, etc.):
   ```bash
   php artisan migrate
   ```

7. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

The backend API will be available at `http://127.0.0.1:8000/api/v1`

## Learning Style Classifier API

The project includes a machine learning-based API that classifies users into Honey-Mumford learning styles based on their questionnaire responses.

### Prerequisites

1. Python 3.11 (Download from [https://www.python.org/downloads/release/python-3110/](https://www.python.org/downloads/release/python-3110/))

### Setup and Running

1. Install the required dependencies:
```bash
cd algorithm
pip install -r requirements.txt
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
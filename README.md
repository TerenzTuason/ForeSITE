# ForeSITE Learning System

An adaptive learning platform that personalizes Futures Thinking education using Honey-Mumford learning styles. Classifies learners (Activist, Reflector, Theorist, Pragmatist) and adapts content accordingly.

---

## Project Structure

```
ForeSITE/
├── frontend/          # Static HTML/CSS web app
├── backend/           # Laravel PHP API
├── algorithm/         # ML learning-style classifier (Python/Flask)
├── schema.sql         # Database schema (import via phpMyAdmin)
├── vercel.json        # Deployment config (Vercel)
└── KeyElementsOftheSystem.md
```

---

## Directory Guide

### `frontend/`
Static web pages. Open `index.html` in a browser or serve with any static server.

| Path | Purpose |
|------|---------|
| `index.html` | Landing page |
| `login.html`, `register.html` | Auth pages |
| `admin/` | Admin dashboard, users, courses, progress |
| `faculty/` | Faculty dashboard, learner management |
| `students/` | Student dashboard, courses, modules, assessments, profile |
| `components/` | Reusable navbars |
| `assets/` | Images, videos, logos |
| `styles/main.css` | Global styles |

### `backend/`
Laravel API. Handles auth, courses, enrollments, progress, chat, assessments.

| Path | Purpose |
|------|---------|
| `routes/api.php` | All API routes |
| `app/Http/Controllers/API/` | API controllers |
| `app/Models/` | Eloquent models |
| `database/migrations/` | DB migrations |
| `config/` | App config |

### `algorithm/`
Flask API that predicts learning style from 80-question responses.

| File | Purpose |
|------|---------|
| `app.py` | Flask app, `/predict` endpoint |
| `learning_style_classifier.py` | ML ensemble (Decision Tree, RF, SVM, XGBoost, CNN) |
| `classifier_config.py` | Model params, question mappings |
| `requirements.txt` | Python dependencies |

---

## Quick Setup

### 1. Database
- Install XAMPP, start Apache + MySQL
- phpMyAdmin → Import → select `schema.sql` from project root

### 2. Backend
```bash
cd backend
composer install
php artisan key:generate
# Edit .env: DB_DATABASE=foresite_db, DB_USERNAME=root, DB_PASSWORD=
php artisan serve
```
API: `http://localhost:8000/api/v1`

### 3. Algorithm (Learning Style API)
```bash
cd algorithm
pip install -r requirements.txt
python app.py
```
API: `http://localhost:5000`

### 4. Frontend
Open `frontend/index.html` in a browser, or use a simple server:
```bash
cd frontend && python -m http.server 3000
```

---

## API Quick Reference

### Learning Style Prediction
`POST /predict` (algorithm, port 5000)

```json
{ "answers": [0, 1, 0, ...] }  // 80 binary values
```

Response: `{ "learning_style": "reflector", "confidence": 0.85, ... }`

### Backend API
See `backend/API_DOCUMENTATION.md` for full endpoint list. Main routes under `/api/v1/`:
- `auth/login`, `auth/register`
- `courses`, `users`, `enrollments`
- `module-progress`, `module-assessments`
- `chat/`, `groups/`, `scores/`, `feedback/`

---

## Deployment
`vercel.json` routes:
- `/algorithm/*` → Python Flask app
- `/api/*` → PHP Laravel backend

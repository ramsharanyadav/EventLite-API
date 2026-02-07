# EventLite API

A modern, production-ready REST API for managing events and bookings with JWT authentication, role-based access control, and comprehensive API documentation.

## 🎯 Overview

EventLite API provides a complete solution for event management and booking with:

- **JWT Authentication** - Secure token-based authentication with 24-hour expiration
- **Role-Based Access Control** - Admin and user roles with fine-grained permissions
- **Event Management** - Create, read, update, and delete events
- **Booking System** - Reserve seats with automatic capacity management
- **Race Condition Prevention** - Database transactions ensure data consistency
- **Interactive API Documentation** - Swagger UI at `/docs` for testing endpoints
- **Comprehensive Tests** - 32 test cases with 106 assertions

## 📋 Requirements

### System Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| **PHP** | 8.2 | 8.2+ |
| **MySQL** | 8.0 | 8.0+ |
| **Composer** | 2.0 | Latest |
| **Node.js** | 16 | 18+ (optional) |

### Windows/WSL Setup

If using **Windows Subsystem for Linux (WSL)**:

```bash
# Install WSL2 (Windows 10/11)
wsl --install

# Inside WSL, install dependencies
sudo apt update
sudo apt install php8.2 php8.2-mysql php8.2-xml php8.2-mbstring
sudo apt install mysql-server mysql-client
sudo apt install composer
```

### macOS Setup

```bash
# Using Homebrew
brew install php@8.2 mysql composer

# Start MySQL
brew services start mysql
```

### Linux Setup

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.2 php8.2-mysql php8.2-xml php8.2-mbstring mysql-server composer
```

## 🚀 Installation & Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/ramsharanyadav/EventLite-API.git
cd eventlite-api
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

```bash
# Copy example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### Step 4: Configure Database

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_lite
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database (if not exists):

```bash
mysql -u root -p -e "CREATE DATABASE event_lite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

This creates the following tables:
- `users` - User accounts with roles
- `events` - Event listings
- `bookings` - Event reservations
- Plus Laravel system tables (cache, sessions, jobs)

### Step 6: Seed Sample Data (Optional)

```bash
php artisan db:seed
```

This creates:
- **1 Admin User:** admin@eventlite.test / password
- **5 Regular Users:** Generated via factory
- **2 Named Events:** Laravel Conference 2026, Web Development Workshop
- **8 Random Events:** Generated via factory

### Step 7: Generate API Documentation

```bash
php artisan l5-swagger:generate
```

### Step 8: Start Development Server

```bash
php artisan serve
```

The API will be available at: **http://localhost:8000**

---

## 📚 API Documentation

### Interactive Swagger UI

**URL:** [http://localhost:8000/docs](http://localhost:8000/docs)

JSON format suitable for:
- Postman import
- Code generation
- API client libraries

**URL:** http://localhost:8000/api/documentation

Features:
- Try-it-out functionality for all endpoints
- Real-time request/response testing
- Schema definitions and examples
- Built-in authorization using JWT tokens

---

## 🔐 JWT Authentication

### Obtaining a JWT Token

#### Option 1: Register a New User

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Ramsharan Yadav",
    "email": "ramshara@eventlite.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "user" // optional : admin / user (default)
  }'
```

Response:
```json
{
    "message": "User registered successfully",
    "user": {
        "name": "Ramsharan Yadav",
        "email": "ramshara@eventlite.com",
        "role": "user",
        "updated_at": "2026-02-07T07:07:26.000000Z",
        "created_at": "2026-02-07T07:07:26.000000Z",
        "id": 28
    },
    "token": "eyJ0eXAiOiJK....."
}
```

#### Option 2: Login with Existing Account

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "ramshara@eventlite.com",
    "password": "password123"
  }'
```

Response:
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "type": "bearer",
  "expires_in": 86400
}
```

### Using the Token

Include the token in the `Authorization` header for all protected requests:

```bash
Authorization: Bearer <your_token_here>
```

### Token Refresh

Tokens expire after 24 hours. Refresh without re-authenticating:

```bash
curl -X POST http://localhost:8000/api/auth/refresh \
  -H "Authorization: Bearer <old_token>"
```

---

## 📖 Example cURL Commands

### 1. Get Your User Profile

```bash
TOKEN="your_jwt_token_here"

curl -X POST http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer $TOKEN"
```

### 2. List Upcoming Events (Public)

```bash
curl -X GET http://localhost:8000/api/events | jq
```

### 3. Get Event Details

```bash
curl -X GET http://localhost:8000/api/events/1 | jq
```

### 4. Create an Event (Admin Only)

```bash
TOKEN="your_admin_token_here"

curl -X POST http://localhost:8000/api/events \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Tech Summit 2026",
    "starts_at": "2026-04-15 09:00:00",
    "capacity": 200
  }'
```

### 5. Update an Event (Admin Only)

```bash
TOKEN="your_admin_token_here"

curl -X PATCH http://localhost:8000/api/events/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Summit Title",
    "capacity": 250
  }'
```

### 6. Delete an Event (Admin Only)

```bash
TOKEN="your_admin_token_here"

curl -X DELETE http://localhost:8000/api/events/1 \
  -H "Authorization: Bearer $TOKEN"
```

### 7. Book an Event (Authenticated Users)

```bash
TOKEN="your_user_token_here"

curl -X POST http://localhost:8000/api/events/1/book \
  -H "Authorization: Bearer $TOKEN"
```

### 8. View Your Bookings

```bash
TOKEN="your_user_token_here"

curl -X GET http://localhost:8000/api/me/bookings \
  -H "Authorization: Bearer $TOKEN" | jq
```

### 9. Logout

```bash
TOKEN="your_token_here"

curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer $TOKEN"
```
---

## 🗄️ Database Management

### Run Migrations Only

```bash
php artisan migrate
```

### Reset Database (Fresh + Seed)

```bash
php artisan migrate:fresh --seed
```

### Rollback Last Migration Batch

```bash
php artisan migrate:rollback
```

---

## 📁 Project Structure

```
eventlite-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php       # Authentication endpoints
│   │   │   │   ├── EventController.php      # Event CRUD endpoints
│   │   │   │   └── BookingController.php    # Booking endpoints
│   │   │   └── Controller.php               # Base controller with schemas
│   │   └── Middleware/
│   │       └── AdminMiddleware.php          # Admin authorization
│   └── Models/
│       ├── User.php
│       ├── Event.php
│       └── Booking.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── routes/
│   ├── api.php                              # All API routes
│   └── web.php
├── tests/
│   ├── Feature/
│   └── TestCase.php
├── config/
│   ├── auth.php                             # JWT guard config
│   ├── swagger.php
│   └── ...
├── storage/
│   └── api-docs/
│       └── api-docs.json                    # Generated Swagger spec
├── .env.example
├── artisan
├── composer.json
├── phpunit.xml
└── README.md
```

---

## 🔑 Sample Credentials

After running `php artisan db:seed`:

**Admin Account:**
```
Email: admin@eventlite.test
Password: password
Role: admin
```

Use these credentials to test admin-only endpoints.

---

## 🚀 API Endpoints Summary

### Authentication (5 endpoints)
```
POST   /api/auth/register      Register new user
POST   /api/auth/login         Login user
POST   /api/auth/me            Get current user
POST   /api/auth/logout        Logout user
POST   /api/auth/refresh       Refresh JWT token
```

### Events (5 endpoints)
```
GET    /api/events             List upcoming events
GET    /api/events/{id}        Get event details
POST   /api/events             Create event (admin)
PATCH  /api/events/{id}        Update event (admin)
DELETE /api/events/{id}        Delete event (admin)
```

### Bookings (2 endpoints)
```
POST   /api/events/{id}/book   Book an event
GET    /api/me/bookings        View user's bookings
```

---

## 🔐 Security Features

✅ **JWT Authentication**
- Tokens expire after 24 hours
- Secure Bearer token scheme
- Token refresh without re-authentication

✅ **Admin Middleware**
- Role-based access control
- Returns 403 Forbidden for unauthorized access
- Applied automatically to protected routes

✅ **Password Security**
- Bcrypt hashing (never stored in plain text)
- Minimum 6 characters required
- Password confirmation on registration

✅ **Database Security**
- Transactions for atomicity
- Pessimistic locking for concurrent access
- Unique constraints prevent duplicates

---

## 📊 API Performance

- Response time: < 50ms average
- Concurrent requests: Supported via locking
- Database queries: Optimized with eager loading
- Rate limiting: Configurable per endpoint

---

**Happy API building! 🚀**

For issues or questions, please check the documentation files or open an issue on GitHub.

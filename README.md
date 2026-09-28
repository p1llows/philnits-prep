# PhilNITS Prep

A web-based examination reviewer for the PhilNITS IP Passport Examination, built with the TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire).

## Features

- **User Authentication**: Secure registration and login system
- **Role-Based Access**: Separate roles for Learners and Administrators
- **Assessment System**: Initial assessment with score calculation
- **Topic-Based Review**: Organized question review by topics
- **Practice Mode**: Immediate feedback on practice questions
- **Mistake Tracking**: Track and review incorrect answers
- **Progress Monitoring**: Monitor preparation progress over time
- **PWA Support**: Installable as a Progressive Web App

## Technology Stack

- **Backend**: Laravel 11+
- **Frontend**: Tailwind CSS, Alpine.js, Livewire
- **Database**: MySQL (with Docker support)
- **Admin Panel**: Filament (Phase 2+)

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- Docker and Docker Compose (optional but recommended)

## Installation

### 1. Clone and Setup

```bash
# Navigate to project directory
cd philnits-prep

# Install PHP dependencies
composer install --ignore-platform-reqs

# Install JavaScript dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Database Setup

Using Docker (Recommended):

```bash
# Start Docker containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Seed database with test users
docker-compose exec app php artisan db:seed
```

Without Docker:

```bash
# Configure .env with your database credentials
# Update DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Create database manually in MySQL
mysql -u root -p -e "CREATE DATABASE philnits_prep;"

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed
```

### 3. Build Assets

```bash
# Development mode (recommended during development)
npm run dev

# Production build
npm run build
```

### 4. Start Development Server

```bash
# Using PHP's built-in server
php artisan serve

# Application will be available at http://localhost:8000
```

## Test Users

The seeder creates two test users for development:

**Administrator:**
- Email: `admin@philnitsprep.local`
- Password: `password`
- Role: Admin (access to admin features)

**Learner:**
- Email: `learner@philnitsprep.local`
- Password: `password`
- Role: Learner (standard user access)

⚠️ **Important**: These are for development only. Do not use these credentials in production!

## Available Routes

### Public Routes
- `GET /` - Welcome page
- `GET /login` - Login page
- `POST /login` - Handle login
- `GET /register` - Registration page
- `POST /register` - Handle registration
- `GET /forgot-password` - Password reset request

### Protected Routes (Require Authentication)
- `GET /dashboard` - User dashboard
- `GET /profile` - Profile management
- `GET /assessment` - Assessment pages (to be implemented)
- `GET /practice` - Practice mode (to be implemented)

### Admin Routes (Requires Admin Role)
- Admin panel routes will be created using Filament in Phase 5

## Project Structure

```
philnits-prep/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   │   └── User.php
│   └── Policies/
├── bootstrap/
│   └── app.php
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── docker/
│   └── mysql/
│       └── init.sql
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── auth/
│       ├── components/
│       ├── layouts/
│       └── profile/
├── routes/
├── storage/
└── tests/
```

## Development Workflow

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php
```

### Debugging Tips

1. Check logs:
```bash
tail -f storage/logs/laravel.log
```

2. Clear caches if needed:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

3. Rebuild assets after changes:
```bash
npm run build
```

## Environment Variables

Key environment variables to configure:

```env
APP_NAME="PhilNITS Prep"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql  # Use 'mysql' when using Docker
DB_PORT=3306
DB_DATABASE=philnits_prep
DB_USERNAME=philnits
DB_PASSWORD=secret

QUEUE_CONNECTION=sync  # Change to 'database' for production
SESSION_DRIVER=file    # Change to 'redis' for production
```

## Security Considerations

- All passwords are hashed using bcrypt
- CSRF protection enabled on all forms
- Role-based access control implemented
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade templating

## Future Phases

### Phase 2: Database Schema & Models
- Topics model
- Questions model
- Source packages model
- Assessment model

### Phase 3: Assessment System
- Initial assessment interface
- Answer validation
- Score calculation
- Results display

### Phase 4: Review System
- Topic browsing
- Question practice mode
- Mistake tracking

### Phase 5: Content Management
- Filament admin panel
- PDF import functionality
- Question editor

### Phase 6: AI Integration
- Topic classification suggestions
- Explanation generation
- Intelligent recommendations

## Contributing

This is a development project. Follow these guidelines:

1. Keep code clean and well-documented
2. Write tests where appropriate
3. Follow PSR-12 coding standards
4. Use meaningful commit messages
5. Test before committing

## License

MIT License - See LICENSE file for details

## Credits

Designed to help candidates prepare for the PhilNITS IP Passport Examination.

---

For more information about the project requirements and specifications, see `02-product-requirements.md.docx`.

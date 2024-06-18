# Laravel API with Job Queue

An API endpoint built with Laravel that utilizes job queues, event handling, and database migrations.

## Setup

1. Clone the repository to your local environment.
2. Navigate to the project directory.
3. Install composer dependencies: `composer install`
4. Copy the `.env.example` to `.env`: `cp .env.example .env`
5. Generate an app encryption key: `php artisan key:generate`
6. Create an empty database and update `.env` file with database credentials.
7. Run migrations: `php artisan migrate`

## Database Migrations

The migrations file for creating `submissions` table can be found in `database/migrations` directory. The migration file creates a table `submissions` with the following columns:

- `id`
- `name`
- `email`
- `message`
- `timestamps` (`created_at` and `updated_at`)

## API Endpoints

### /api/submit

The API endpoint accepts a `POST` request with the following JSON payload:

```
{ 
    "name":"Sergey", 
    "email": "email@example.net", 
    "message": "test message" 
} 
```



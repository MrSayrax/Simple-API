# Simple API with Job Queue
![PHP](https://img.shields.io/badge/PHP-8.3-blue)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![Version](https://img.shields.io/badge/Version-1.0.0-green)

An API endpoint built with Laravel that utilizes job queues, event handling, and database migrations.

## Setup

1. Clone the repository to your local environment.
2. Navigate to the project directory.
3. Install composer dependencies: `composer install`
4. Copy the `.env.example` to `.env`: `cp .env.example .env`
5. Generate an app encryption key: `php artisan key:generate`
6. Create an empty database and update `.env` file with database credentials.
7. Run migrations: `php artisan migrate`

## Laravel Sail Setup
Laravel sail, a lightweight command-line interface for interacting with Laravel's default Docker configuration, is supported.
1. You may need to install Docker Desktop before getting started.
2. Start Laravel Sail's Docker environment using the command: `./vendor/bin/sail up`
3. If you want to make `sail` a global command on your system, you can add the following to your shell configuration file: `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'`

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
## Running Tests
Laravel is built with testing in mind. You can run the tests in the application using PHP's built-in command:
1. Navigate to the root directory of your project.
2. Run the PHPUnit tests via `sail artisan test`.

Note: Before running your tests, set the `DB_CONNECTION` and `DB_DATABASE` in your `.env.testing` (create one if doesn't exist from `.env.example`) to `sqlite` and `:memory:` respectively, to use in-memory database.

All done! Laravel API with Job Queue is now set up and ready for use.


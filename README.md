# Login

## Features

-   Laravel 7
-   Vue
-   Login, register, email verification and password reset
-   Bootstrap 4

## Installation

-   `composer install`
-   copy `.env.example` and rename it to `.env`
-   Edit `.env` and set your database connection and nexmo details
-   `php artisan passport:install`
-   `php artisan migrate --seed` to generate admin and test account
-   `npm install`

## Usage

#### Development

```bash
npm run dev
```

```bash
php artisan serve
```

You can access your application using the provided host.

### Accounts for logging in

Admin Account

-   username: `admin@blinc.ph`
-   password: `admin`

Test Account

-   username: `test@blinc.ph`
-   password: `test`

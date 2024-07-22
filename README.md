<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

# Laravel Project

This project is built using the Laravel framework, providing a robust and scalable foundation for web and mobile applications. It includes a variety of packages to extend its functionality.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Included Packages](#included-packages)
- [Development Packages](#development-packages)
- [Contributing](#contributing)
- [License](#license)

## Requirements

- Laravel ^10
- Composer

## Installation

1. **Clone the repository:**
    ```sh
    git clone https://github.com/RanaGaballah/Blog-Application.git
    cd Blog-Application
    ```

2. **Install dependencies:**
    ```sh
    composer install
   
    ```
    or

   ```sh
    composer update
   
    ```

3. **Copy `.env.example` to `.env`:**
    ```sh
    cp .env.example .env
    ```

4. **Generate application key:**
    ```sh
    php artisan key:generate
    ```

5. **Run database migrations:**
    ```sh
    php artisan migrate
    ```

## Running the Application

After completing the installation steps, you can start the application using the built-in Laravel development server:

```sh

php artisan serve

```

Navigate to `http://localhost:8000` in your web browser to view the application.

## Included Packages

This project utilizes several packages to enhance its functionality:

| Package | Description |
|---------|-------------|
| [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) | HTTP Client |
| [laravel/framework](https://github.com/laravel/framework) | The Laravel Framework |
| [laravel/sanctum](https://github.com/laravel/sanctum) | Simple API token authentication |

## Development Packages

| Package | Description |
|---------|-------------|
| [fakerphp/faker](https://github.com/FakerPHP/Faker) | Faker library for PHP |
| [mockery/mockery](https://github.com/mockery/mockery) | Mocking framework for PHP |
| [nunomaduro/collision](https://github.com/nunomaduro/collision) | Error handling for command-line applications |
| [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit) | Testing framework for PHP |
| [spatie/laravel-ignition](https://github.com/spatie/laravel-ignition) | Debugging and error handling tool for Laravel |

## Contributing

Contributions are welcome! Please submit a pull request or create an issue to help improve the project.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).















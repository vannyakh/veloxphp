<!-- veloxPHP -->
# veloxPHP

## Clone the repository

```bash
git clone https://github.com/veloxphp/veloxphp.git
```

## Install dependencies

```bash
composer install
```

## Copy environment file

```bash
cp .env.example .env
```

## Generate application key
php velox key:generate
```bash

## Configuration

Edit `.env` file to configure your application:

```

## env

```bash
APP_NAME=Velox
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=velox
DB_USERNAME=root
DB_PASSWORD=
```
## Basic Usage

### Starting the Development Server

```bash
php velox serve
```

### Running Migrations

```bash
php velox migrate
```

## php velox serve

```bash
The application will be available at `http://localhost:8000`

### Creating a New Controller
```

php velox make:controller UserController
```

This will create a new controller in the `app/Controllers` directory.

php velox make:model User

This will create a new model in the `app/Models` directory.

php velox make:middleware AuthMiddleware

This will create a new middleware in the `app/Middleware` directory.
```
<!-- Project Structure -->

## Project Structure

```bash
velox/
├── app/
│ ├── Controllers/
│ ├── Models/
│ ├── Middleware/
│ └── Resources/
├── bootstrap/
├── config/
├── core/
├── database/
│ ├── migrations/
│ └── seeds/
├── public/
├── resources/
│ └── views/
├── routes/
├── storage/
├── tests/
└── vendor/
```
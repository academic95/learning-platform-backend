# Learning Platform Backend

Laravel API for internal employee learning platform.

## Requirements

- Docker
- Docker Compose

## Services

- Laravel API: http://localhost:8000
- phpMyAdmin: http://localhost:8080
- Filament Admin: http://localhost:8000/admin
- API Docs: http://localhost:8000/request-docs

## Setup

```bash
docker-compose up -d --build
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed


Test users
- Admin:
  - Email: admin@example.com
  - Password: password

- User:
  - Email: user@example.com
  - Password: password

Authorization: Bearer {token}

# PHP Developer Test Assignment

## Описание
Laravel-проект с решениями тестовых задач:
1. **DI контейнер** — [Container.php](app/Support/Container.php)
2. **CPA БД** — [Миграции](database/migrations) и [Сидеры](database/seeders/DatabaseSeeder.php)
3. **Clicks сервис** — [ClickController.php](app/Http/Controllers/ClickController.php), [Click.php](app/Models/Click.php), [API маршруты](routes/api.php)
4. **URL парсер** — [UrlParser.php](app/Services/UrlParser.php)

## Установка
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve

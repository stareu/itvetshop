# IT Vet Shop

Интернет-магазин на Laravel 12 с React/Inertia frontend, админ-панелью Filament, корзиной, заказами, оплатой через T-Bank и входом через Telegram WebApp.

## Возможности

- Каталог активных товаров.
- Корзина и оформление заказа.
- История заказов пользователя.
- Страницы успешной и неуспешной оплаты.
- Callback для обработки уведомлений T-Bank.
- Telegram WebApp авторизация.
- Админ-панель Filament для управления товарами, заказами, пользователями, ролями, покупателями и виртуальными активами.

## Стек

- PHP 8.2+
- Laravel 12
- Inertia.js 2
- React 19
- TypeScript
- Vite 7
- Tailwind CSS 4
- Filament 4
- SQLite по умолчанию, можно переключить на другую БД через `.env`

## Требования

- PHP 8.2 или новее
- Composer
- Node.js и npm
- SQLite или другая поддерживаемая Laravel база данных

Для Windows/XAMPP убедитесь, что PHP из XAMPP доступен в `PATH`, либо запускайте команды через полный путь к `php.exe`.

## Установка

1. Установите PHP-зависимости:

```bash
composer install
```

2. Создайте файл окружения на основе `.env.example`.

3. Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

4. Подготовьте базу данных.

По умолчанию проект настроен на SQLite. Если файла базы еще нет, создайте его:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

Затем выполните миграции:

```bash
php artisan migrate
```

При необходимости заполните базу начальными данными:

```bash
php artisan db:seed
```

Seeder создает администратора и несколько тестовых товаров. После seeding вход в админку доступен по адресу `/admin`.

5. Установите frontend-зависимости:

```bash
npm install
```

## Быстрый старт

В проекте есть Composer-скрипт, который устанавливает зависимости, создает `.env`, генерирует ключ, запускает миграции и собирает frontend:

```bash
composer run setup
```

Если используется SQLite и файла `database/database.sqlite` еще нет, создайте его перед запуском скрипта.

## Запуск для разработки

Запустите backend, очередь и Vite одной командой:

```bash
composer run dev
```

По умолчанию Laravel будет доступен на:

```text
http://localhost:8000
```

Можно запускать процессы отдельно:

```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1
```

## Сборка frontend

```bash
npm run build
```

SSR-сборка:

```bash
npm run build:ssr
```

## Проверки

Backend-тесты:

```bash
composer run test
```

Проверка типов:

```bash
npm run types
```

Форматирование:

```bash
npm run format
```

Проверка форматирования:

```bash
npm run format:check
```

Lint:

```bash
npm run lint
```

## Переменные окружения

Базовые настройки находятся в `.env.example`. Для локального запуска обычно достаточно:

```env
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
```

Для интеграций дополнительно используются:

```env
TG_BOT_TOKEN=
TBANK_TERMINAL=
TBANK_TERMINAL_PASSWORD=
```

`TG_BOT_TOKEN` нужен для проверки данных Telegram WebApp. `TBANK_TERMINAL` и `TBANK_TERMINAL_PASSWORD` нужны для создания платежей и проверки уведомлений T-Bank.

## Основные маршруты

- `/` - каталог товаров.
- `/cart` - корзина.
- `/orders` - заказы авторизованного пользователя.
- `/offer` - оферта.
- `/privacy` - политика конфиденциальности.
- `/tg` - вход через Telegram WebApp.
- `/payment-success` и `/payment-fail` - результат оплаты.
- `/tbank` - callback от T-Bank.
- `/admin` - админ-панель Filament.

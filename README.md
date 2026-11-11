# icpo-backend

Веб-система на Laravel 13 для ведения и управления картотекой лиц, находящихся в розыске или представляющих оперативный интерес для Международной организации уголовной полиции (Интерпол).

Система предназначена для регистрации и систематизации сведений о лицах, поиска по произвольному набору признаков, отслеживания изменений их статусов и ведения архива.

Для каждой записи предусмотрен один из следующих статусов:

- `wanted` - разыскивается;
- `serving` - отбывает наказание;
- `archived` - архивная запись;
- `deceased` - умер;
- `detained` - задержан;
- `acquitted` - оправдан.

Административная панель реализована на Filament 5.

## Запуск

### Требования

- Docker
- Docker Compose

PHP, Composer, Node.js и PostgreSQL устанавливать на хостовую машину не требуется.

### Запуск контейнеров

```bash
docker compose up -d --build
```

### Установка зависимостей

```bash
docker compose exec app composer install
```

### Генерация ключа приложения

```bash
docker compose exec app php artisan key:generate
```

### Установка Filament

```bash
docker compose exec app composer require filament/filament:"^5.0" -W
docker compose exec app php artisan filament:install --panels
```

### Выполнение миграций

```bash
docker compose exec app php artisan migrate
```

### Остановка приложения

```bash
docker compose down
```

## Адреса сервисов

| Сервис | Адрес |
|---|---|
| Приложение | http://localhost:8080 |
| Filament | http://localhost:8080/admin |
| Vite | http://localhost:5173 |
| Mailpit | http://localhost:8025 |
| pgAdmin | http://localhost:5050 |

Mailpit и pgAdmin запускаются отдельно:

```bash
docker compose --profile tools up -d
```

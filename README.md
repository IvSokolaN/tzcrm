## Тестовое задание по заказу питания

Приложение доступно по адресу http://localhost:8088

Если не хочется создавать .env, то [.env.example](src/.env.example) содержит все необходимые переменные.
Можно его скопировать:

```bash
    cp src/.env.example .env
```

### Порядок запуска приложения:

1) Установка зависимостей

```bash
    docker-compose run --rm composer install
```

2) Запуск контейнеров

```bash
    docker-compose up nginx -d
```

3) Запуск миграций

```bash
    docker-compose run --rm artisan migrate
```

Приложение доступно по адресу http://localhost:8088

4) Остановка контейнеров

```bash
    docker-compose down
```


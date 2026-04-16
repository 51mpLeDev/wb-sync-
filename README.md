# WB API Sync Service

## 📌 Описание

Сервис для синхронизации данных с тестового API (orders, sales, stocks, incomes) с поддержкой мульти-аккаунтов и сохранением в MySQL.

Проект реализован на Laravel с использованием очередей, постраничной загрузки и обработки ошибок API.

---

## ⚙️ Стек

* PHP 8.4
* Laravel 13
* MySQL 8
* Docker + Docker Compose
* Laravel Queue (database)
* Scheduler

---

## 🚀 Быстрый запуск (Docker)

```bash
git clone https://github.com/51mpLeDev/wb-sync-
cd wb-sync-

docker compose up -d --build
```

После запуска:

* API: http://localhost:8000
* MySQL: localhost:3307

---

## 🗄 Миграции

Миграции выполняются автоматически при старте контейнера.

Если нужно вручную:

```bash
docker compose exec app php artisan migrate
```

---

## 🧠 Архитектура

* **Services** — работа с API
* **Jobs** — синхронизация данных
* **Models** — работа с БД
* **Console Commands** — управление через CLI
* **Queue** — обработка в фоне

---

## 🔑 Работа с токенами

### 📌 Правило

У одного аккаунта может быть несколько токенов, но:

```text
account_id + api_service_id + token_type_id = UNIQUE
```

---

## ▶️ Создание сущностей через CLI

### Компания

```bash
php artisan company:create
```

### Аккаунт

```bash
php artisan account:create
```

### API сервис

```bash
php artisan api-service:create
```

### Тип токена

```bash
php artisan token-type:create
```

### Токен

```bash
php artisan token:create
```

---

## ▶️ Запуск синхронизации

```bash
docker compose exec app php artisan wb:sync
```

---

## ⚙️ Очередь

Очередь запускается автоматически в контейнере:

```bash
wb_queue
```

Проверка:

```bash
docker logs -f wb_queue
```

---

## ⏱ Планировщик

```php
Schedule::command('wb:sync')->twiceDaily(9, 18);
```

---

## 📡 API

Host:

```
http://109.73.206.144:6969
```

Авторизация:

```
?key=API_KEY
```

---

## 🔄 Обработка данных

* Пагинация (`page`, `limit=500`)
* Загрузка до конца данных
* Обработка больших объёмов

---

## ⚠️ Обработка ошибок

### Реализовано:

* Retry при ошибках
* Обработка `429 Too Many Requests`
* Повторные попытки с задержкой
* Логирование ошибок

---

## 🧾 Логирование

* Файл логов (storage/logs)
* Вывод в консоль
* Статусы выполнения
* Ошибки API
* Ошибки сохранения

---

## 📊 Поддерживаемые сущности

* Orders
* Sales
* Stocks
* Incomes

---

## 📌 Особенности

* Мульти-аккаунты
* Поддержка разных API сервисов
* Поддержка разных типов токенов
* Уникальность данных (без дублей)
* Масштабируемая архитектура

---

## 👨‍💻 Автор

Shukurov Firdavs
Full-Stack Developer

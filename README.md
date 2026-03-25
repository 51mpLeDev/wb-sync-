# WB API Sync Service

## 📌 Описание

Сервис для синхронизации данных с тестового API (orders, sales, stocks, incomes) и сохранения их в базу данных MySQL.

Проект реализован на Laravel с использованием очередей и постраничной загрузки данных.

---

## ⚙️ Стек

* PHP 8+
* Laravel 13
* MySQL
* Queue (database)

---

## 🚀 Установка

```bash
git clone https://github.com/51mpLeDev/wb-sync-
cd wb-sync-

composer install
cp .env.example .env
php artisan key:generate
```

---

## 🗄 Настройка БД

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wb_sync
DB_USERNAME=wb_user
DB_PASSWORD=
```

---

## ▶️ Миграции

```bash
php artisan migrate
```

---

## 🔑 API доступ

* Host: http://109.73.206.144:6969
* Key: E6kUTYrYwZq2tN4QEtyzsbEBk3ie

Авторизация через query параметр:

```
?key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

---

## ▶️ Запуск синхронизации

```bash
php artisan queue:work
php artisan wb:sync
```

---

## 🧠 Реализовано

### ✔ Сущности

* Orders
* Sales
* Stocks
* Incomes

---

### ✔ Пагинация

* Используется `page` и `limit=500`
* Данные загружаются циклом до конца

---

### ✔ Уникальные ключи

| Entity  | Поле                   |
| ------- | ---------------------- |
| Orders  | g_number               |
| Sales   | sale_id                |
| Stocks  | warehouse_name + nm_id |
| Incomes | income_id              |

---

### ✔ Очереди

* Jobs выполняются через Laravel Queue
* Независимые задачи выполняются параллельно

---

### ✔ Логирование

* Старт/конец синхронизации
* Количество обработанных записей
* Ошибки API
* Ошибки сохранения данных

---

## 📊 Архитектура

* Services → работа с API
* Jobs → синхронизация данных
* Models → работа с БД
* Console Command → запуск процесса

---

## 🗄 Доступ к базе данных

Host: crossover.proxy.rlwy.net  
Port: 45055  
Database: railway  
User: root  
Password: eSPcPyIcjWJzGagLLUmpGORSdiNFOSqO

Таблицы:
- orders
- sales
- stocks
- incomes

---

## 💬 Команда

```bash
php artisan wb:sync
```

Запускает:

* SyncOrdersJob
* SyncSalesJob
* SyncStocksJob
* SyncIncomesJob

---

## 📈 Результат

После выполнения:

* База данных заполнена актуальными данными
* Дубли исключены
* Ошибки логируются

---

## 👨‍💻 Автор

Shukurov Firdavs | Full-Stack Developer

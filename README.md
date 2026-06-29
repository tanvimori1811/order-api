# Laravel Order Status Management API

## Project Overview

This project is a REST API built with Laravel 13 for managing order statuses and maintaining the history of status changes.

## Features

* Update an order status
* Store every status change in the history table
* Retrieve the complete status history of an order
* RESTful API endpoints
* MySQL database integration

## Technologies Used

* Laravel 13
* PHP 8.4
* MySQL
* Postman
* Git & GitHub

## Project Structure

```
app/
 ├── Http/
 │    └── Controllers/
 │         └── Api/
 │              └── OrderController.php
 ├── Models/
 │    ├── Order.php
 │    └── OrderStatusHistory.php

database/
 └── migrations/

routes/
 └── api.php
```

## Database Tables

### orders

| Column     | Type      |
| ---------- | --------- |
| id         | bigint    |
| status     | string    |
| created_at | timestamp |
| updated_at | timestamp |

### order_status_histories

| Column     | Type      |
| ---------- | --------- |
| id         | bigint    |
| order_id   | bigint    |
| status     | string    |
| note       | text      |
| created_at | timestamp |
| updated_at | timestamp |

## API Endpoints

### Update Order Status

**PUT**

```
/api/orders/{id}/status
```

Request Body

```json
{
    "status": "confirmed",
    "note": "Payment received"
}
```

Success Response

```json
{
    "message": "Order status updated successfully.",
    "order": {
        "id": 1,
        "status": "confirmed"
    }
}
```

---

### Get Order Status History

**GET**

```
/api/orders/{id}/history
```

Success Response

```json
[
    {
        "status": "confirmed",
        "note": "Payment received"
    }
]
```

## Installation

```bash
git clone https://github.com/tanvimori1811/order-api.git

cd order-api

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

## Testing

The API was tested using Postman.

## Author

**Tanvi Mori**

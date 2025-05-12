# Gold Trading Platform (Laravel Modular)

A modular **Laravel 12** application implementing a simple gold trading exchange with:
- **User Authentication** via Laravel Sanctum
- **Wallets**: track user gold and amount balances + reserved amounts
- **Order Module**: buy/sell orders with partial fills
- **Matching Engine**: asynchronous order matching via Redis queue
- **Commission Rules**: tiered commissions with min/max caps
- **Order History & Cancellation**
- **API Documentation** with Dedoc/Scramble (/docs/api)
- **Dockerized** development environment (PHP-FPM, Nginx, Pgsql, Redis)

---

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Environment Setup](#environment-setup)
3. [Docker Setup](#docker-setup)
4. [Running the Application](#running-the-application)
5. [Match buy sell orders](#match-buy-sell-orders)
6. [API Documentation (Scramble)](#api-documentation-scramble)
7. [API Endpoints Summary](#api-endpoints-summary)

---

## Prerequisites
- **Docker** (Desktop or Engine)
- **Docker Compose**

---


## Environment Setup
Copy example env:
   ```bash
   cp .env.example .env
   ```
---

## Docker Setup
1. Build and start containers:
   ```bash
   docker-compose up --build -d
   ```
2. install composer.json:
   ```bash
   docker-compose exec -u root app composer install
   ```   


3. Provided containers:
    - **app** (PHP-FPM, your Laravel code)
    - **web** (Nginx)
    - **db**  (MySQL)
    - **redis** (for queues)

---

## Running the Application
1. Generate application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```
2. Run migrations and seeders:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

3. Access the app in your browser at **http://localhost:8000**.

---
## Match buy sell orders
for running the MatchOrderJob that match sell and buy orders, run bellow command

   ```bash
   
   docker-compose exec app php artisan queue:work
   
   ```
---
## API Documentation (Scramble)
View:
- Swagger UI:  `http://localhost:8000/docs/api`
- OpenAPI JSON: `http://localhost:8000/docs/api.json`
---

## API Endpoints Summary
| Path                         | Method | Description                  | Auth Required |
|------------------------------|--------|------------------------------|---------------|
| `/api/v1/auth/register`      | POST   | Register new user            | No            |
| `/api/v1/auth/login`         | POST   | Obtain API token             | No            |
| `/api/v1/orders/buy`         | POST   | Place a buy order            | Yes           |
| `/api/v1/orders/sell`        | POST   | Place a sell order           | Yes           |
| `/api/v1/orders/history`     | GET    | Get user order history       | Yes           |
| `/api/v1/orders/cancel`      | POST   | Cancel an open/partial order | Yes           |

**Enjoy trading!**

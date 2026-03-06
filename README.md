# 📦 Product & Inventory Management API

REST API untuk mengelola produk dan kategori, termasuk fitur pencarian, update stok setelah transaksi, serta perhitungan total nilai inventory.

Project ini dibangun menggunakan Laravel 12 dan MySQL.

---

## 🚀 Tech Stack

- PHP 8.2
- Laravel 12
- MySQL
- Eloquent ORM

Dependency dan system requirements proyek ini dikelola menggunakan Composer dan dapat dilihat pada file [composer.json](composer.json)

---

## 📂 Fitur Utama

- CRUD Produk
- Menambah dan Membaca semua Kategori
- Pencarian produk berdasarkan nama atau kategori
- Update stok produk setelah transaksi
- Perhitungan total nilai inventory (price × stock)
- Global error handling untuk endpoint tidak ditemukan

---

## ⚙️ Installation Guide

### 1. Clone Repository

```bash
git clone https://github.com/cryzepy/ecommerce-inventory-api.git
cd ecommerce-inventory-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Copy Environment File

```bash
cp .env.example .env
```

### 4. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migration

```bash
php artisan migrate
```

### 7. Jalankan Server

```bash
php artisan serve
```

Base URL API:

```
http://127.0.0.1:8000/api
```

---

# 🗄️ Database Structure

## Categories Table

| Field      | Type      |
|------------|----------|
| id         | bigint   |
| name       | string (unique) |
| created_at | timestamp |
| updated_at | timestamp |

## Products Table

| Field          | Type |
|----------------|------|
| id             | bigint |
| name           | string |
| price          | decimal(11,2) |
| stock_quantity | integer unsigned |
| category_id    | foreign key (nullable) |
| created_at     | timestamp |
| updated_at     | timestamp |

### Relasi
- 1 kategori dapat memiliki banyak produk
- 1 produk hanya memiliki 1 kategori (nullable)

---

# 📌 API Endpoints

---

## 🔹 Category Endpoints

### Create Category

**POST** `/api/categories`

Request Body:

```json
{
  "name": "Elektronik"
}
```

Response 201:

```json
{
  "data": {
    "id": 1,
    "name": "Elektronik",
    "created_at": "2026-01-01T00:00:00.000000Z",
    "updated_at": "2026-01-01T00:00:00.000000Z"
  }
}
```

---

### Get All Categories

**GET** `/api/categories`

Response 200:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Elektronik"
    }
  ]
}
```

---

## 🔹 Product Endpoints

### Create Product

**POST** `/api/products`

```json
{
  "name": "Laptop",
  "price": 15000000,
  "stock_quantity": 10,
  "category_id": 1
}
```

Response 201:

```json
{
  "data": {
    "id": 1,
    "name": "Laptop",
    "price": "15000000.00",
    "stock_quantity": 10,
    "category_id": 1,
    "created_at": "2026-01-01T00:00:00.000000Z",
    "updated_at": "2026-01-01T00:00:00.000000Z"
  }
}
```

---

### Get All Products

**GET** `/api/products`

---

### Get Product By ID

**GET** `/api/products/{id}`

Response 404 jika tidak ditemukan:

```json
{
  "message": "Produk Tidak ditemukan."
}
```

---

### Update Product

**PUT** `/api/products/{id}`

```json
{
  "price": 12000000
}
```

---

### Delete Product

**DELETE** `/api/products/{id}`

Response:

```json
{
  "message": "Produk berhasil dihapus."
}
```

---

## 🔎 Search Product

Mencari berdasarkan nama atau kategori.

**GET**
```
/api/products/search?name=laptop
```

atau

```
/api/products/search?category_id=1
```

---

## 📦 Update Stock After Transaction

Mengurangi stok setelah terjadi transaksi.

**POST** `/api/products/update-stock`

```json
{
  "product_id": 1,
  "quantity_sold": 2
}
```

Validasi:
- Produk harus ada
- Stok tidak boleh menjadi negatif

Response:

```json
{
  "data": {
    "id": 1,
    "name": "Laptop",
    "price": "15000000.00",
    "stock_quantity": 8
  }
}
```

---

## 💰 Get Total Inventory Value

Menghitung total nilai inventory berdasarkan:

```
SUM(price * stock_quantity)
```

**GET** `/api/inventory/value`

Response:

```json
{
  "data": {
    "total_inventaris": 150000000
  }
}
```

---

# ❗ Error Handling

### Endpoint Tidak Ditemukan

```json
{
  "message": "Endpoint tidak ditemukan"
}
```

### Error Sistem

```json
{
  "message": "Terjadi kesalahan pada sistem"
}
```
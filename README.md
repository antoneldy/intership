# Talenavi Backend Technical Test – Todo API (Laravel)

Project ini dibuat sebagai pengerjaan *Technical Test Backend Internship – Talenavi*.  
Aplikasi menyediakan API untuk **Create Todo** dan **Generate Excel Report** dengan fitur filtering lengkap.

---

## Tech Stack
- Laravel 10
- MySQL
- Laravel Excel (maatwebsite/excel)
- Postman (API Testing)
- GitHub (Code Repository)

---

## Fitur Utama

### 1. Create Todo (POST /api/todos)
Membuat todo baru dengan field:
- `title` (required)
- `assignee` (optional)
- `due_date` (required, tidak boleh di masa lalu)
- `time_tracked` (default: 0)
- `status` (enum: pending, open, in_progress, completed — default pending)
- `priority` (enum: low, medium, high)

Terdapat validasi lengkap  
Response berupa object Todo

---

### 2. Export Todo to Excel (GET /api/todos/export)
Menghasilkan file Excel dengan kolom:
- title, assignee, due_date, time_tracked, status, priority

### **Fitur Filtering:**
- title → partial match  
- assignee → multi-value (contoh: `Andi,Budi`)  
- due_date → range (`start=YYYY-MM-DD&end=YYYY-MM-DD`)  
- time_tracked → range (`min=X&max=Y`)  
- status → multi-value  
- priority → multi-value  

Excel menyertakan **summary row** berisi:
- total todos  
- total time_tracked  

---

## Postman Collection
Repository ini menyertakan:
- **todo-api.json** → koleksi Postman untuk testing Create & Export

POST --- http://localhost:8000/api/todos
GET --- http://localhost:8000/api/todos/export?title=prep&assignee=Antonius&start=2025-01-01&end=2025-12-31&min=0&max=100&status=pending,in_progress&priority=low,high 

## ▶ Cara Menjalankan Project

```bash
composer install
cp .env.example .env
php artisan key:generate

# Sesuaikan database
php artisan migrate

php artisan serve

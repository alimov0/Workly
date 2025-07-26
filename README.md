 <p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<h1 align="center">Workly - Job Board REST API</h1>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 📌 Workly haqida

**Workly** — bu ish beruvchilar va ish qidiruvchilarni bog‘lovchi RESTful API asosidagi platforma. Laravel 12 orqali ishlab chiqilgan.

### 👥 Rollar

| Rol | Vazifa |
|-----|--------|
| Admin | Hammasini boshqaradi |
| Employer | E’lon yaratadi, arizalarni ko‘radi |
| Job Seeker | Ish qidiradi, ariza yuboradi |

### 🧩 Asosiy modellari

- **User** — `role`, `email_verified_at`, `hasMany` `vacancies`, `applications`
- **Vacancy** — `belongsTo` `user`, `category`, `hasMany` `applications`
- **Application** — `user_id`, `vacancy_id`, `cover_letter`, `resume_file`
- **Category** — `parent_id`, `hasMany` `vacancies`

### 🔐 Auth

Laravel Sanctum orqali:
- `POST /register`
- `POST /login`
- `POST /logout`

---

## 🚀 API Endpoints

### 🎯 Job Seeker

| Endpoint | Maqsad |
|----------|--------|
| `GET /jobs` | E’lonlar |
| `POST /jobs/{id}/apply` | Ariza yuborish |

### 🧑‍💼 Employer

| Endpoint | Maqsad |
|----------|--------|
| `POST /employer/jobs` | E’lon joylash |
| `GET /employer/jobs/{id}/applications` | Arizalar |

### 🛠️ Admin

| Endpoint | Maqsad |
|----------|--------|
| `GET /admin/users` | Barcha userlar |
| `GET /admin/jobs` | Barcha ishlar |

---

## 📦 Qo‘shimcha imkoniyatlar

- 🔎 Search va Filter
- 📤 Queue orqali email
- 🧮 Observer (slug generate)
- 📁 CV yuklash (storage link)
- 🔔 Real-time Notification
- 💬 Advanced JSON Response

---

## 📘 Postman Collection

📎 *[Postman Link joylash kerak]*

---

## 🛠 Ishga tushirish

```bash
git clone https://github.com/your-username/workly.git
cd workly
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan queue:work


# Workly API

This is a Laravel 12 RESTful API project for job vacancy management, built using Service-Repository architecture with advanced response handling and admin/user authentication.

## Features

- Sanctum-based authentication
- Admin and Employer roles
- Advanced response formats 
- Notifications system
- Vacancy management
- Category CRUD
- Validation via Form Requests

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- Simple, fast routing engine
- Powerful dependency injection container
- Multiple backends for session and cache storage
- Expressive database abstraction layer
- Robust background job processing
- Real-time event broadcasting

Laravel is accessible, powerful, and provides tools required for large, robust applications.

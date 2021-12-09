## System Description
Merupakan proyek untuk mengelola Surat Pemberitahuan Pajak Terhutang (SPPT), project ini tidak mengandung view dari sistem, project ini merupakan web service yang menyediakan API untuk sistem frontend

## URL API List
Daftar dan spesifikasi dari endpoint API bisa dilihat pada [Dokumentasi API](https://app.swaggerhub.com/apis-docs/ganiadiw/SPPT_Sumberjo/1.0.0)

## Installation Guide

```
composer install
```
```
Windows (CMD) : copy .env.example .env | Linux (Bash) : cp .env.example .env
```
```
php artisan key:generate
```
Configure your database in .env file
```
php artisan migrate
```
```
php artisan db:seed
```
```
php artisan storage:link
```
```
php artisan serve
```

## Documentation

### Routes
Route dapat di lihat pada direktori routes/api yang berisi endpoint API

### Controllers
| Controller Name | Description |
| --------------- | ----------- |
| AdministratorController | untuk mengelola adminstrator dari sistem
| AuntheticationController | untuk mengelola authentikasi
| GuardianController | untuk mengelola pamong blok
| ProfileController | untuk mengelola profile
| SpptController | untuk mengelola SPPT
| TaxHistoryController | untuk mengelola riwayat pembayaran pajak

### Middleware
Untuk pengelolaan middleware, project ini menggunakan package dari Laravel Sanctum dan Spatie laravel-permission

### Database Seeder
Berisi data dummy yang dapat digunakan sebagai contoh data. Dapat diakses di direktori database/seeders

### Custom Helpers
Berisi file yang digunakan sebagai formatter dari response json yang dihasilkan oleh sistem. Dapat diakses pada direktor Helpers
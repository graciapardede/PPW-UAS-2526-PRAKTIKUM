# 📚 REST API Perpustakaan Digital

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-44%20Passed-brightgreen.svg)](tests/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

REST API untuk sistem manajemen Perpustakaan Digital dengan fitur CRUD lengkap, authentication, dan comprehensive testing.

---

## 📑 Daftar Output

Sesuai dengan requirement tugas, berikut adalah output yang disediakan:

### 1. ✅ Source Code REST API
**Lokasi**: Seluruh source code ada di folder project ini
- **Controllers**: `app/Http/Controllers/Api/`
  - `AuthController.php` - Authentication (Register, Login, Logout)
  - `BookController.php` - CRUD Books
  - `MemberController.php` - CRUD Members
  - `BorrowingController.php` - CRUD Borrowings & Return Book
- **Models**: `app/Models/`
  - `User.php`, `Book.php`, `Member.php`, `Borrowing.php`
- **Migrations**: `database/migrations/`
  - Create tables untuk books, members, borrowings
- **Routes**: `routes/api.php`
  - Semua API endpoints terdefinisi dengan baik

### 2. ✅ Koleksi Testing
**Lokasi**: 
- **PHPUnit Tests**: `tests/Feature/`
  - `AuthTest.php` (7 test cases)
  - `BookTest.php` (11 test cases)
  - `MemberTest.php` (11 test cases)
  - `BorrowingTest.php` (13 test cases)
- **Postman Collection**: `Perpustakaan_Digital_API.postman_collection.json`
  - Import file ini ke Postman untuk manual testing
  - Sudah include auto-save token setelah login

### 3. ✅ Laporan Hasil Pengujian dan Analisis
**Lokasi**: `API_DOCUMENTATION.md`
- Dokumentasi API lengkap dengan semua endpoints
- Laporan hasil pengujian (44 tests, 182 assertions - All Passed ✅)
- Analisis kritis implementasi
- Saran perbaikan dan future enhancements

---

## 🎯 Fitur Utama

### ✅ Designing & Implementing REST API
1. **Prinsip RESTful**
   - Resource-based URLs (`/api/books`, `/api/members`, `/api/borrowings`)
   - HTTP methods yang tepat (GET, POST, PUT, DELETE)
   - Stateless authentication
   - Standardized response format

2. **Framework Backend**
   - Laravel 12 (Framework PHP modern)
   - Laravel Sanctum (Token-based authentication)
   - Eloquent ORM (Database management)

3. **CRUD Service**
   - ✅ Books: Create, Read, Update, Delete
   - ✅ Members: Create, Read, Update, Delete
   - ✅ Borrowings: Create, Read, Update, Delete + Return Book

4. **Validasi Input & Error Handling**
   - ✅ Input validation untuk semua request
   - ✅ HTTP status code yang tepat:
     - `200 OK` - Request berhasil
     - `201 Created` - Resource baru dibuat
     - `400 Bad Request` - Business logic error
     - `401 Unauthorized` - Authentication required
     - `404 Not Found` - Resource tidak ditemukan
     - `422 Unprocessable Entity` - Validation error
     - `500 Internal Server Error` - Server error

### ✅ Testing API
1. **API Testing Tools**
   - PHPUnit (Automated testing)
   - Postman Collection (Manual testing)

2. **Test Cases**
   - ✅ Positive test cases: Semua fitur normal berfungsi
   - ✅ Negative test cases: Validation, error handling, edge cases

3. **Testing Coverage**
   - ✅ Autentikasi (Register, Login, Logout, Unauthorized access)
   - ✅ Konsistensi data (Stock management, Foreign key validation)
   - ✅ Error handling (Invalid input, Not found, Duplicate data)

4. **Laporan & Analisis**
   - ✅ Test results: 44 tests passed (100% success rate)
   - ✅ 182 assertions verified
   - ✅ Analisis kritis dengan skor 8.5/10
   - ✅ Recommendations untuk improvement

---

## 🚀 Quick Start

### Prerequisites
- PHP >= 8.2
- Composer
- Laravel 12

### Installation

1. **Install Dependencies**
```bash
composer install
```

2. **Setup Environment**
```bash
# File .env sudah ada, cek konfigurasi database
```

3. **Run Migrations**
```bash
php artisan migrate:fresh
```

4. **Start Server**
```bash
php artisan serve
```

API berjalan di: `http://localhost:8000`

---

## 📖 Dokumentasi Lengkap

Baca dokumentasi lengkap di: **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)**

Dokumentasi mencakup:
- Database Schema
- Semua API Endpoints dengan contoh request/response
- Authentication guide
- Error handling
- Testing results
- Analisis kritis

---

## 🧪 Running Tests

### Run All Tests
```bash
php artisan test
```

**Expected Output:**
```
Tests:    44 passed (182 assertions)
Duration: 2.24s
```

### Test Breakdown
- Authentication: 7 tests ✅
- Books CRUD: 11 tests ✅
- Members CRUD: 11 tests ✅
- Borrowings CRUD: 13 tests ✅

---

## 🔌 API Usage Examples

### 1. Register & Login

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login - Response akan include access_token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 2. Create Book

```bash
curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "title": "Laravel Guide",
    "author": "John Doe",
    "isbn": "978-1234567890",
    "year": 2024,
    "stock": 10
  }'
```

---

## 📊 Test Results Summary

```
✓ Authentication Tests       (7/7 passed)
✓ Books CRUD Tests          (11/11 passed)
✓ Members CRUD Tests        (11/11 passed)
✓ Borrowings Tests         (13/13 passed)

Total: 44/44 tests passed ✅
Success Rate: 100%
```

---

## 🎯 Assignment Requirements Checklist

### ✅ Designing & Implementing REST API
- [x] Mendesain REST API sesuai prinsip RESTful
- [x] Mengimplementasikan API menggunakan framework backend (Laravel 12)
- [x] Menyediakan fitur CRUD Service (Books, Members, Borrowings)
- [x] Menerapkan validasi input dan error handling
- [x] HTTP status code yang tepat

### ✅ Testing API
- [x] Membuat API testing (PHPUnit + Postman)
- [x] Menyusun test case positif dan negatif
- [x] Menguji autentikasi, konsistensi data, dan error handling

### ✅ Deliverables
- [x] Source code REST API
- [x] Koleksi testing
- [x] Laporan hasil pengujian dan analisis

---

**🎓 Tugas UAS - REST API Perpustakaan Digital**

**Status**: ✅ Complete & Tested | **Test Coverage**: 44 tests - All Passed ✅

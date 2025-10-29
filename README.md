# 🚀 Aneramedia Laravel Starter Kit

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Status-Production%20Ready-brightgreen?style=flat-square" alt="Status">
  <img src="https://img.shields.io/badge/API%20Tests-100%25%20Passing-brightgreen?style=flat-square" alt="Tests">
  <img src="https://img.shields.io/badge/Code%20Style-Laravel%20Pint-blue?style=flat-square" alt="Code Style">
</p>

---

## 📋 Deskripsi Proyek

**Aneramedia Laravel Starter Kit** adalah template aplikasi Laravel 12 yang siap produksi dengan sistem autentikasi, manajemen role & permission, dan menu dinamis. Proyek ini dirancang untuk mempercepat pengembangan aplikasi web dengan fitur-fitur enterprise yang sudah terintegrasi.

### ✨ Fitur Unggulan

- 🔐 **Autentikasi API dengan Laravel Sanctum**
  - Registration & Login dengan token-based authentication
  - Password hashing dengan bcrypt
  - Automatic role assignment untuk user baru

- 👥 **Role-Based Access Control (RBAC)**
  - Implementasi Laratrust untuk manajemen role & permission
  - Hierarchical permission system
  - Dynamic middleware untuk access control

- 🎯 **Menu Dinamis Berdasarkan Role**
  - Menu system yang dapat dikonfigurasi
  - Hierarchical menu structure
  - Role-based menu visibility

- 🧪 **Testing Suite Lengkap**
  - 100% API test coverage
  - Feature & Unit tests dengan PHPUnit
  - Automated testing untuk semua endpoints

- 🏗️ **Arsitektur Modular**
  - Organized controller structure
  - Form Request validation
  - API Resources untuk response formatting
  - Service layer untuk business logic

### 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi | Versi |
|----------|-----------|-------|
| **Backend Framework** | Laravel | 12.35.1 |
| **PHP Version** | PHP | 8.4.10 |
| **Database** | SQLite | Default |
| **Authentication** | Laravel Sanctum | 4.2.0 |
| **Authorization** | Laratrust | 8.5+ |
| **Testing** | PHPUnit | 11.5.42 |
| **Code Style** | Laravel Pint | 1.25.1 |
| **Development** | Laravel Sail | 1.46.0 |

---

## 📋 Persyaratan Sistem

### Minimum Requirements

- **PHP**: 8.2 atau lebih tinggi
- **Composer**: 2.0+
- **Node.js**: 18+ (untuk asset compilation)
- **NPM**: 8+
- **SQLite**: 3.8+ (default) atau MySQL 8.0+

### Recommended Specifications

- **RAM**: 2GB minimum, 4GB recommended
- **Storage**: 1GB free space
- **OS**: Windows 10+, macOS 10.15+, Ubuntu 20.04+

### PHP Extensions Required

```
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
```

---

## 🚀 Panduan Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/your-username/aneramedia-starter-kit.git
cd aneramedia-starter-kit
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (jika diperlukan)
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database
touch database/database.sqlite
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed database dengan data awal
php artisan db:seed
```

### 5. Quick Setup (Alternative)

```bash
# Gunakan composer script untuk setup otomatis
composer run setup
```

---

## 🎯 Cara Penggunaan

### Menjalankan Aplikasi

#### Development Server

```bash
# Menjalankan server development
php artisan serve

# Atau gunakan composer script untuk development lengkap
composer run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

#### Production Deployment

```bash
# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run dengan web server (Apache/Nginx)
```

### 🔑 API Endpoints

#### Authentication

```bash
# Register user baru
POST /api/v1/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}

# Login
POST /api/v1/login
{
    "email": "john@example.com",
    "password": "password123"
}

# Get user profile (requires authentication)
GET /api/v1/me
Authorization: Bearer {token}

# Logout
POST /api/v1/logout
Authorization: Bearer {token}
```

#### Role & Permission Management

```bash
# Get all roles (admin only)
GET /api/v1/roles
Authorization: Bearer {admin_token}

# Get all permissions (admin only)
GET /api/v1/permissions
Authorization: Bearer {admin_token}

# Get all users (admin only)
GET /api/v1/users
Authorization: Bearer {admin_token}
```

#### Menu System

```bash
# Get user menu berdasarkan role
GET /api/v1/user-menu
Authorization: Bearer {token}

# Get all menus (admin only)
GET /api/v1/menus
Authorization: Bearer {admin_token}
```

### 🧪 Testing

```bash
# Run semua tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run dengan coverage
php artisan test --coverage

# Run tests dengan filter
php artisan test --filter=testUserCanLogin
```

### 🎨 Code Formatting

```bash
# Format code dengan Laravel Pint
vendor/bin/pint

# Check formatting tanpa fix
vendor/bin/pint --test
```

---

## 📁 Struktur Proyek

```
aneramedia-starter-kit/
├── app/
│   ├── Console/Commands/          # Artisan commands
│   ├── Http/
│   │   ├── Controllers/Api/       # API Controllers (modular)
│   │   │   ├── auth/             # Authentication controllers
│   │   │   ├── users/            # User management
│   │   │   ├── roles/            # Role management
│   │   │   ├── permissions/      # Permission management
│   │   │   └── menus/            # Menu management
│   │   ├── Middleware/           # Custom middleware
│   │   ├── Requests/             # Form request validation
│   │   ├── Resources/            # API resources
│   │   └── Responses/            # API response helpers
│   ├── Models/                   # Eloquent models
│   ├── Services/                 # Business logic services
│   └── Traits/                   # Reusable traits
├── database/
│   ├── factories/                # Model factories
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── routes/
│   ├── v1/                       # API v1 routes (modular)
│   ├── api.php                   # Main API routes
│   └── web.php                   # Web routes
├── tests/
│   ├── Feature/                  # Feature tests
│   └── Unit/                     # Unit tests
└── storage/                      # Storage files
```

### 🏗️ Arsitektur Komponen

#### Controllers
- **Modular Structure**: Controllers diorganisir berdasarkan modul (auth, users, roles, etc.)
- **BaseController**: Shared functionality untuk semua API controllers
- **Form Requests**: Validation terpisah untuk setiap endpoint

#### Models & Relationships
- **User Model**: Dengan Laratrust traits untuk role management
- **Role & Permission Models**: Hierarchical permission system
- **Menu Model**: Dynamic menu dengan role-based visibility

#### Middleware
- **CheckAcl**: Dynamic permission checking
- **ForceJsonResponse**: Ensure JSON responses untuk API
- **Sanctum Auth**: Token-based authentication

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari komunitas! Berikut panduan untuk berkontribusi:

### 📝 Panduan Kontribusi

1. **Fork** repository ini
2. **Create** feature branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** perubahan (`git commit -m 'Add some AmazingFeature'`)
4. **Push** ke branch (`git push origin feature/AmazingFeature`)
5. **Open** Pull Request

### 🔍 Standar Kontribusi

- **Code Style**: Ikuti Laravel coding standards
- **Testing**: Pastikan semua tests passing
- **Documentation**: Update dokumentasi jika diperlukan
- **Commit Messages**: Gunakan conventional commit format

### 🧪 Before Submitting

```bash
# Run tests
php artisan test

# Format code
vendor/bin/pint

# Check for any issues
php artisan route:list
```

### 📋 Development Tasks

Lihat <mcfile name="DEVELOPMENT_TASKS.md" path="d:\aneramedia-starter-kit\DEVELOPMENT_TASKS.md"></mcfile> untuk daftar task yang sedang dikerjakan dan yang akan datang.

---

## 📊 Status Proyek

### ✅ Completed Features

- [x] User Authentication dengan Sanctum
- [x] Role & Permission Management dengan Laratrust
- [x] Dynamic Menu System
- [x] Comprehensive API Testing (100% coverage)
- [x] Modular Architecture
- [x] Code Formatting dengan Pint

### 🔄 In Progress

- [ ] Frontend Integration
- [ ] Advanced Menu Features
- [ ] API Documentation (Swagger)

### 📋 Planned Features

- [ ] Email Verification
- [ ] Password Reset
- [ ] Rate Limiting
- [ ] API Versioning
- [ ] Caching Implementation

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

```
MIT License

Copyright (c) 2025 Aneramedia

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 📞 Kontak

### 👨‍💻 Developer

- **Name**: Aneramedia Development Team
- **Email**: dev@aneramedia.com
- **Website**: [https://aneramedia.com](https://aneramedia.com)

### 🐛 Bug Reports & Feature Requests

- **Issues**: [GitHub Issues](https://github.com/your-username/aneramedia-starter-kit/issues)
- **Discussions**: [GitHub Discussions](https://github.com/your-username/aneramedia-starter-kit/discussions)

### 📚 Documentation

- **API Documentation**: <mcfile name="rest-api-testing.md" path="d:\aneramedia-starter-kit\rest-api-testing.md"></mcfile>
- **Development Guide**: <mcfile name="DEVELOPMENT_TASKS.md" path="d:\aneramedia-starter-kit\DEVELOPMENT_TASKS.md"></mcfile>
- **Project Structure**: <mcfile name="structure.md" path="d:\aneramedia-starter-kit\structure.md"></mcfile>

---

## 🙏 Acknowledgments

- **Laravel Team** - Untuk framework yang luar biasa
- **Sanctum Team** - Untuk authentication system yang powerful
- **Laratrust** - Untuk role & permission management
- **Community Contributors** - Untuk feedback dan kontribusi

---

<p align="center">
  <strong>⭐ Jika proyek ini membantu Anda, berikan star di GitHub! ⭐</strong>
</p>

<p align="center">
  Made with ❤️ by <a href="https://aneramedia.com">Aneramedia</a>
</p>

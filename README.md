<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo">
</p>

<h1 align="center">🏋️ Be-FITLiFE — Backend API</h1>

<p align="center">
  RESTful API backend cho hệ thống quản lý phòng gym <strong>FITLiFE</strong>, xây dựng bằng Laravel 12 + Sanctum
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-8892BF?logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Sanctum-4.x-FF6B35?logo=laravel&logoColor=white" alt="Sanctum">
  <img src="https://img.shields.io/badge/License-MIT-green" alt="MIT License">
</p>

---

## 📋 Mục lục

- [Mô tả dự án](#-mô-tả-dự-án)
- [Kiến trúc hệ thống](#-kiến-trúc-hệ-thống)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cài đặt & Chạy dự án](#-cài-đặt--chạy-dự-án)
- [Tài liệu API](#-tài-liệu-api)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Đóng góp](#-đóng-góp)

---

## 📖 Mô tả dự án

**Be-FITLiFE** là backend API của hệ thống quản lý phòng gym **FITLiFE** — một nền tảng toàn diện phục vụ hội viên, huấn luyện viên và quản trị viên.

### Các tính năng chính

| Nhóm | Tính năng |
|------|-----------|
| 🔐 **Xác thực** | Đăng nhập / Đăng ký cho Member, Trainer, Admin; Google OAuth; Token-based auth (Sanctum) |
| 👤 **Hội viên (Member)** | Quản lý hồ sơ, đăng ký gói tập, xem lịch tập, điểm danh, yêu cầu đổi lịch, thanh toán online |
| 🏃 **Huấn luyện viên (Trainer)** | Quản lý lịch dạy, điểm danh học viên, ghi chú sức khỏe, xem thu nhập & lương |
| 🛠️ **Quản trị viên (Admin)** | Dashboard thống kê, quản lý hội viên / HLV / gói tập / chi nhánh / lịch tập / lương / hóa đơn |
| 📦 **Gói tập (Package)** | CRUD gói tập, gắn HLV theo gói, khuyến mãi |
| 🏢 **Chi nhánh (Branch)** | Quản lý đa chi nhánh |
| 📅 **Lịch tập** | Tạo lịch, duyệt lịch, đổi lịch, hủy lịch |
| 💰 **Thanh toán** | Tích hợp cổng thanh toán (PayOS), kiểm tra mã khuyến mãi |
| 📧 **Email** | Gửi thông báo qua mail (MasterMail) |

---

## 🏗️ Kiến trúc hệ thống

Sơ đồ dưới đây mô tả luồng xử lý từ HTTP Layer → Controllers → Data Layer của toàn bộ hệ thống backend:

![Sơ đồ kiến trúc hệ thống FITLiFE](docs/images/architecture.png)

> **Giải thích sơ đồ:**
> - **HTTP Layer**: Request đi vào qua `index.php`, phân phối sang `api.php` (API routes) hoặc `web.php` (Web routes).
> - **Middleware**: `memberMiddleware`, `trainerMiddleware`, `AdminMiddleware` bảo vệ các route tương ứng. Xác thực token qua **Laravel Sanctum**.
> - **Core Domain (Controllers)**: Xử lý logic nghiệp vụ — Packages, Members, Admin, Enrollments, Trainers, Schedules, Branches, Reschedules, TrainerNotes, Attendance, TrainerSalary.
> - **Data Layer (Eloquent Models)**: Tương tác CSDL qua Eloquent ORM. Seeder cung cấp dữ liệu mẫu. Tất cả schema được quản lý bởi **Migrations**.

---

## 🛠️ Công nghệ sử dụng

| Thành phần | Phiên bản |
|-----------|-----------|
| **PHP** | ^8.2 |
| **Laravel Framework** | ^12.0 |
| **Laravel Sanctum** | ^4.3 (Token Auth) |
| **MySQL** | 8.0+ |
| **Composer** | 2.x |
| **Node.js** | 18+ |

---

## 💻 Yêu cầu hệ thống

Trước khi cài đặt, đảm bảo máy đã có:

- **PHP** >= 8.2 (với các extension: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- **Composer** >= 2.0
- **MySQL** >= 8.0 (hoặc XAMPP/WAMP)
- **Node.js** >= 18.x và **npm**
- **Git**

---

## 🚀 Cài đặt & Chạy dự án

### Bước 1 — Clone repository

```bash
git clone https://github.com/xuantruong5/Be-FITLIFE.git
cd Be-FITLIFE
```

### Bước 2 — Cài đặt PHP dependencies

```bash
composer install
```

### Bước 3 — Cấu hình môi trường

Sao chép file cấu hình mẫu:

```bash
cp .env.example .env
```

Chỉnh sửa file `.env` với thông tin phù hợp:

```env
APP_NAME=FITLiFE
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fitlife_db
DB_USERNAME=root
DB_PASSWORD=

# Mail (tuỳ chọn)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS="noreply@fitlife.vn"
MAIL_FROM_NAME="FITLiFE"
```

### Bước 4 — Tạo Application Key

```bash
php artisan key:generate
```

### Bước 5 — Tạo cơ sở dữ liệu

Tạo database `fitlife_db` trong MySQL, sau đó chạy migrations và seeder:

```bash
# Chỉ chạy migration
php artisan migrate

# Migration + seed dữ liệu mẫu
php artisan migrate --seed

# Reset toàn bộ và seed lại (dùng khi dev)
php artisan migrate:fresh --seed
```

### Bước 6 — Cài đặt Node.js dependencies (tuỳ chọn)

```bash
npm install
```

### Bước 7 — Khởi động server

```bash
# Chạy Laravel development server
php artisan serve
```

Server sẽ chạy tại: **http://localhost:8000**

#### Chạy toàn bộ stack cùng lúc (server + queue + logs + vite):

```bash
composer dev
```

---

## 📚 Tài liệu API

**Base URL:** `http://localhost:8000/api`

Tất cả các request trả về JSON. Các route được bảo vệ yêu cầu header:

```
Authorization: Bearer {token}
```

---

### 🔐 Authentication

#### Auth — Member

| Method | Endpoint | Mô tả | Auth |
|--------|----------|-------|------|
| `POST` | `/api/login` | Đăng nhập hội viên | ❌ |
| `POST` | `/api/login-google` | Đăng nhập bằng Google | ❌ |
| `POST` | `/api/register` | Đăng ký tài khoản hội viên | ❌ |
| `POST` | `/api/member/logout` | Đăng xuất (thiết bị hiện tại) | ✅ Member |
| `POST` | `/api/member/logout-all` | Đăng xuất tất cả thiết bị | ✅ Member |

#### Auth — Trainer

| Method | Endpoint | Mô tả | Auth |
|--------|----------|-------|------|
| `POST` | `/api/trainer/login` | Đăng nhập huấn luyện viên | ❌ |
| `POST` | `/api/trainer/logout` | Đăng xuất HLV | ✅ Trainer |
| `POST` | `/api/trainer/logout-all` | Đăng xuất tất cả thiết bị | ✅ Trainer |

#### Auth — Admin

| Method | Endpoint | Mô tả | Auth |
|--------|----------|-------|------|
| `POST` | `/api/admin/login` | Đăng nhập admin | ❌ |
| `POST` | `/api/admin/logout` | Đăng xuất admin | ✅ Admin |
| `GET` | `/api/admin/check-token` | Kiểm tra token admin | ✅ Admin |

---

### 👤 Member Routes

> **Middleware:** `memberMiddleware` — Yêu cầu Sanctum token của hội viên.

#### Hồ sơ

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/member/profile` | Lấy thông tin hồ sơ hội viên |
| `POST` | `/api/member/change-profile` | Cập nhật hồ sơ cá nhân |
| `GET` | `/api/member/my-package` | Xem gói tập hiện tại |

#### Gói tập & Huấn luyện viên

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/packages` | Danh sách gói tập (public) |
| `GET` | `/api/member/packages` | Xem gói tập (member) |
| `GET` | `/api/member/package/{id_package}/trainers` | Danh sách HLV theo gói |
| `GET` | `/api/member/my-trainer` | Xem HLV của mình |
| `GET` | `/api/member/my-trainer/{id}` | Chi tiết HLV |

#### Đăng ký & Quản lý lịch tập

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `POST` | `/api/member/register-schedule` | Đăng ký lịch tập |
| `GET` | `/api/member/my-schedules` | Danh sách lịch đã đăng ký |
| `GET` | `/api/member/my-schedule` | Xem lịch tập (format khác) |
| `GET` | `/api/member/my-schedule/{id}` | Chi tiết lịch tập |
| `GET` | `/api/member/schedule/{id}` | Chi tiết trước khi thanh toán |
| `GET` | `/api/member/title` | Lấy tiêu đề lịch tập |
| `DELETE` | `/api/member/schedule-members/{id}` | Hủy đăng ký lịch |
| `POST` | `/api/member/cancel-schedule` | Hủy lịch tập |
| `POST` | `/api/member/change-schedule` | Đổi lịch tập |
| `GET` | `/api/member/get-schedule/{id}` | Lấy thông tin để đổi lịch |

#### Điểm danh

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/member/my-attendances` | Lịch sử điểm danh |

#### Đổi lịch (Reschedule)

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/member/reschedules` | Danh sách yêu cầu đổi lịch |
| `POST` | `/api/member/reschedules` | Tạo yêu cầu đổi lịch |

#### Ghi chú sức khỏe

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/member/my-notes` | Xem ghi chú sức khỏe từ HLV |
| `GET` | `/api/member/my-trainer-note` | Xem ghi chú HLV (format khác) |

#### Thanh toán

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `POST` | `/api/member/create-order` | Tạo đơn hàng / thanh toán |
| `POST` | `/api/member/check-promotion` | Kiểm tra mã khuyến mãi |
| `GET` | `/api/member/orders/check-payment/{orderCode}` | Kiểm tra trạng thái thanh toán |

---

### 🏃 Trainer Routes

> **Middleware:** `trainerMiddleware` — Yêu cầu Sanctum token của huấn luyện viên.

#### Lịch dạy

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/trainer/schedules` | Danh sách lịch dạy |
| `POST` | `/api/trainer/schedules` | Tạo lịch dạy mới |
| `GET` | `/api/trainer/schedules/{id}` | Chi tiết lịch dạy |
| `PUT` | `/api/trainer/schedules/{id}` | Cập nhật lịch dạy |
| `DELETE` | `/api/trainer/schedules/{id}` | Xóa lịch dạy |
| `POST` | `/api/trainer/create-schedule` | Tạo lịch dạy (extended) |
| `POST` | `/api/trainer/change-schedule` | Đổi lịch dạy |
| `GET` | `/api/trainer/today-schedules` | Lịch dạy hôm nay |
| `GET` | `/api/trainer/member-schedules` | Lịch của từng học viên |

#### Học viên & Gói

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/trainer/schedule-members` | Danh sách hội viên trong ca học |
| `GET` | `/api/trainer/member-packages` | Hội viên theo gói |
| `GET` | `/api/trainer/goi/chi-nhanh` | Gói tập theo chi nhánh |

#### Điểm danh

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/trainer/attendances` | Danh sách điểm danh |
| `POST` | `/api/trainer/attendances` | Tạo bản ghi điểm danh |
| `GET` | `/api/trainer/attendances/{id}` | Chi tiết điểm danh |
| `PUT` | `/api/trainer/attendances/{id}` | Cập nhật điểm danh |
| `POST` | `/api/trainer/change/attendances` | Cập nhật điểm danh hàng loạt |

#### Yêu cầu đổi lịch

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/trainer/reschedules` | Danh sách yêu cầu đổi lịch |
| `GET` | `/api/trainer/reschedules/{id}` | Chi tiết yêu cầu |
| `POST` | `/api/trainer/reschedules/{id}/approve` | Phê duyệt yêu cầu |
| `POST` | `/api/trainer/reschedules/{id}/reject` | Từ chối yêu cầu |

#### Ghi chú hội viên

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/trainer/notes` | Danh sách ghi chú |
| `POST` | `/api/trainer/notes` | Tạo ghi chú mới |
| `GET` | `/api/trainer/notes/{id}` | Chi tiết ghi chú |
| `PUT` | `/api/trainer/notes/{id}` | Cập nhật ghi chú |
| `DELETE` | `/api/trainer/notes/{id}` | Xóa ghi chú |

#### Lương & Thu nhập

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/trainer/my-salary` | Xem lương cá nhân |
| `GET` | `/api/trainer/income` | Xem thu nhập |

---

### 🛠️ Admin Routes

> **Middleware:** `AdminMiddleware` — Yêu cầu Sanctum token của quản trị viên.

#### Dashboard & Tổng quan

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/admin/dashboard` | Thống kê tổng quan |
| `GET` | `/api/admin/member` | Danh sách hội viên |
| `GET` | `/api/admin/trainner` | Danh sách huấn luyện viên |
| `GET` | `/api/admin/package` | Danh sách gói tập |
| `GET` | `/api/admin/reschedule` | Danh sách yêu cầu đổi lịch |
| `POST` | `/api/admin/change/reschedule` | Xử lý đổi lịch |
| `GET` | `/api/admin/invoices` | Danh sách hóa đơn |
| `GET` | `/api/admin/sum/invoices` | Thống kê doanh thu |

#### Chi nhánh (Branch)

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/admin/branches` | Danh sách chi nhánh |
| `POST` | `/api/admin/branches` | Tạo chi nhánh mới |
| `GET` | `/api/admin/branches/{id}` | Chi tiết chi nhánh |
| `PUT` | `/api/admin/branches/{id}` | Cập nhật chi nhánh |
| `DELETE` | `/api/admin/branches/{id}` | Xóa chi nhánh |

#### Gói tập (Package)

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/admin/packages` | Danh sách gói tập |
| `POST` | `/api/admin/packages` | Tạo gói tập mới |
| `GET` | `/api/admin/packages/{id}` | Chi tiết gói tập |
| `PUT` | `/api/admin/packages/{id}` | Cập nhật gói tập |
| `DELETE` | `/api/admin/packages/{id}` | Xóa gói tập |
| `POST` | `/api/admin/store/packages` | Tạo gói tập (extended) |

#### Lịch tập (Schedule)

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/admin/schedules` | Danh sách lịch tập |
| `GET` | `/api/admin/schedules/{id}` | Chi tiết lịch tập |
| `POST` | `/api/admin/schedules/{id}/approve` | Phê duyệt lịch |
| `POST` | `/api/admin/schedules/{id}/reject` | Từ chối lịch |

#### Lương HLV (Salary)

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/admin/salaries` | Danh sách bảng lương |
| `POST` | `/api/admin/salaries` | Tạo bảng lương |
| `GET` | `/api/admin/salaries/{id}` | Chi tiết bảng lương |
| `PUT` | `/api/admin/salaries/{id}` | Cập nhật bảng lương |
| `POST` | `/api/admin/salaries/{id}/pay` | Thanh toán lương |

---

### 📦 Response Format

Tất cả API trả về JSON theo định dạng thống nhất:

```json
// Thành công
{
  "status": true,
  "message": "Thao tác thành công",
  "data": { ... }
}

// Lỗi
{
  "status": false,
  "message": "Mô tả lỗi",
  "errors": { ... }
}
```

#### HTTP Status Codes

| Code | Ý nghĩa |
|------|---------|
| `200` | OK — Thành công |
| `201` | Created — Tạo mới thành công |
| `400` | Bad Request — Dữ liệu không hợp lệ |
| `401` | Unauthorized — Chưa xác thực |
| `403` | Forbidden — Không có quyền |
| `404` | Not Found — Không tìm thấy |
| `422` | Unprocessable Entity — Validation thất bại |
| `500` | Internal Server Error — Lỗi server |

---

## 📁 Cấu trúc dự án

```
Be-FITLIFE/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Các controller xử lý request
│   │   │   ├── AdminController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── BranchController.php
│   │   │   ├── MembersController.php
│   │   │   ├── PackageController.php
│   │   │   ├── RescheduleController.php
│   │   │   ├── ScheduleMemberController.php
│   │   │   ├── TrainerController.php
│   │   │   ├── TrainerNoteController.php
│   │   │   ├── TrainerSalaryController.php
│   │   │   └── TrainerScheduleController.php
│   │   ├── Middleware/         # Custom middleware (auth guards)
│   │   └── Requests/           # Form request validation
│   ├── Mail/                   # Mail notifications (MasterMail)
│   └── Models/                 # Eloquent models
│       ├── Admin.php
│       ├── Attendance.php
│       ├── Branch.php
│       ├── Member.php
│       ├── Package.php
│       ├── ScheduleMember.php
│       ├── Trainer.php
│       ├── TrainerNote.php
│       ├── TrainerSalary.php
│       ├── TrainerSchedule.php
│       └── reschedule.php
├── database/
│   ├── migrations/             # Schema migrations
│   └── seeders/                # Seed dữ liệu mẫu
├── docs/
│   └── images/                 # Tài liệu & ảnh kiến trúc
├── routes/
│   ├── api.php                 # API routes
│   └── web.php                 # Web routes
├── .env.example                # Cấu hình môi trường mẫu
├── composer.json               # PHP dependencies
└── README.md
```

---

## 🤝 Đóng góp

1. Fork repository
2. Tạo branch mới: `git checkout -b feature/ten-tinh-nang`
3. Commit thay đổi: `git commit -m "feat: mô tả thay đổi"`
4. Push lên branch: `git push origin feature/ten-tinh-nang`
5. Tạo Pull Request

---

## 📄 License

Dự án được phân phối theo giấy phép **MIT License**.

---

<p align="center">
  Made with ❤️ by <strong>FITLiFE Team</strong>
</p>

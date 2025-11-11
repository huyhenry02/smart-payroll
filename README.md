# 🧭 HỆ THỐNG QUẢN LÝ NHÂN SỰ & TIỀN LƯƠNG (HRM PAYROLL SYSTEM)

## 📘 GIỚI THIỆU CHUNG

**Hệ thống Quản lý Nhân sự & Tiền lương** được xây dựng bằng **Laravel 12**,  
phục vụ công tác quản lý tổng thể thông tin nhân sự, chấm công, tính lương, phụ cấp, trích nộp, thưởng – phạt, và các nghiệp vụ hành chính nội bộ.

Dự án được thiết kế theo kiến trúc **Module hóa (Modular Architecture)**, bao gồm các module chức năng chính:

---

### 🧩 1. MODULE HỆ THỐNG (SYSTEM)
- Quản lý người dùng (User)
- Quản lý nhóm quyền (Role) và phân quyền chi tiết (Permission)
- Cấp quyền thao tác theo từng route của hệ thống
- Chức năng:
    - Thêm / Sửa / Xóa / Khóa tài khoản người dùng
    - Gán nhóm quyền cho tài khoản
    - Phân quyền chi tiết theo module – hành động
- Tích hợp kiểm tra quyền:
    - Ẩn / hiện các nút trong giao diện theo quyền
    - Middleware bảo vệ route
- Đổi mật khẩu với xác thực mạnh:
    - Tối thiểu 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt
    - Có xác nhận trùng khớp và toggle ẩn/hiện mật khẩu

---

### 🏢 2. MODULE DANH MỤC CHUNG (GENERAL CATALOG)
Quản lý toàn bộ danh mục nền tảng của hệ thống:
- Phòng ban (`Department`)
- Chức vụ (`Position`)
- Nhân viên (`Employee`)
- Ca làm việc (`WorkingShift`)
- Khoản khấu trừ (`Deduction`)
- Phụ cấp (`Allowance`)
- Khoản thưởng (`Bonus`)

Chức năng:
- Thêm, sửa, xóa danh mục trực tiếp từ giao diện bảng
- Hỗ trợ tìm kiếm nhân viên, cập nhật thông tin nhanh
- Hiển thị dữ liệu động theo quyền được cấp

---

### 🕒 3. MODULE CHẤM CÔNG (ATTENDANCE)
- Quản lý chi tiết bảng chấm công theo tháng và nhân viên
- Theo dõi ngày đi làm, nghỉ phép, làm thêm (Overtime)
- Ghi nhận & xác nhận ca làm thêm, khóa công tháng
- Tự động tổng hợp công phục vụ tính lương

Ký hiệu trong bảng công:
- ✅ Đi làm
- ❌ Nghỉ
- 🅽 Chủ nhật

---

### 💰 4. MODULE KẾ TOÁN - TIỀN LƯƠNG (ACCOUNTING)
- Tính toán lương thực lĩnh cho từng nhân viên theo công thức:
    - **Lương cơ bản (hệ số × ngày công)**
    - **Phụ cấp (hệ số × hệ số chuẩn)**
    - **Trích nộp (tỷ lệ × lương cơ bản)**
    - **Tiền thưởng & Làm thêm**
- Cho phép xem, in và xuất file PDF:
    - Bảng lương
    - Thuế TNCN
    - Bảng thanh toán
- Báo cáo theo tháng, quý, năm

---

### 📊 5. MODULE BÁO CÁO (REPORT & JOURNAL)
- Báo cáo nhật ký kế toán, lương, thưởng, phụ cấp
- Thống kê bằng biểu đồ Chart.js:
    - Hợp đồng / đơn hàng / nhân sự theo tháng – quý – năm
- Xuất báo cáo PDF / Excel chuẩn A4

---

### 👥 6. PHÂN QUYỀN HỆ THỐNG
Cấu trúc quyền gồm 3 tầng:
1. **Role** – nhóm quyền (VD: Giám đốc, Kế toán, Nhân sự)
2. **Permission** – hành động cụ thể (VD: `general_catalog.postDepartment`)
3. **User** – được gán 1 role duy nhất, kế thừa toàn bộ quyền

Tính năng:
- Gán quyền theo từng module (system, accounting, attendance, ...)
- Hỗ trợ middleware kiểm tra quyền truy cập backend
- Ẩn / hiện nút và menu ở frontend dựa vào quyền

---

## ⚙️ YÊU CẦU MÔI TRƯỜNG

| Thành phần | Phiên bản khuyến nghị |
|-------------|------------------------|
| **PHP** | >= 8.2 |
| **Laravel** | 12.x |
| **Composer** | >= 2.7 |
| **MySQL** | >= 8.0 |
| **Node.js** | >= 18.x |
| **NPM** | >= 9.x |
| **Extension PHP cần có** | `pdo`, `mbstring`, `bcmath`, `openssl`, `tokenizer`, `curl`, `intl`, `fileinfo` |

---

## HƯỚNG DẪN CÀI ĐẶT & CHẠY DỰ ÁN

### 🔹 Bước 1: Clone project
```bash
git clone https://github.com/huyhenry02/smart-payroll.git
cd smart-payroll
git checkout develop
```
### 🔹 Bước 2: Cài thư viện PHP
```bash
composer install
```
### 🔹 Bước 3: Tạo file cấu hình .env
```bash
cp .env.example .env
```
### ✏️ Cập nhật thông số ENV kết nối DB:
```yaml
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_payroll
DB_USERNAME=root
DB_PASSWORD=
```
### 🔹 Bước 4: Tạo bảng trong phần mềm quản trị csdl (MySQL Workbench, phpMyAdmin, Adminer, ...)
Tạo database với tên trùng với `DB_DATABASE` trong file `.env`, ví dụ:
```sql
CREATE DATABASE smart_payroll CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
### 🔹 Bước 5: Tạo key và migrate database
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```
### 🔹 Bước 6: Chạy server Laravel
```bash
php artisan serve
```
🌐 Truy cập ứng dụng tại:
👉 http://127.0.0.1:8000
### 🔹 Bước 7: Đăng nhập hệ thống
Tài khoản admin mặc định:
- Email: director1@tech.com
- Mật khẩu: 1

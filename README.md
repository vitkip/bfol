
# BFOL Content Management System

ລະບົບນີ້ເປັນ Laravel Web Application ສໍາລັບບໍລິຫານຂ່າວສານ ກິດຈະກໍາ ແລະເນື້ອຫາອື່ນໆ ພ້ອມຫຼາຍພາສາ (Lao, English, Chinese) ແລະລະບົບ CRUD ສໍາລັບຫົວຂໍ້ຕ່າງໆ.

## System Overview

This system is a Laravel-based CMS for managing news, events, categories, tags, and event tags. It supports multilingual content (Lao, English, Chinese) and provides a modern admin panel for CRUD operations.

### Main Features
- News management (CRUD, categories, tags, featured, urgent, status)
- Event management (CRUD, categories, event tags, featured, international, registration, etc.)
- Category management (multi-level, color, icon, type)
- Tag & Event Tag management (multi-language)
- User management, permissions, and more

## Database Structure (Main Tables)

### news
- id, title_lo, title_en, title_zh, slug, excerpt_lo/en/zh, content_lo/en/zh, thumbnail, category_id, author_id, status, is_featured, is_urgent, view_count, published_at, timestamps
- Pivot: news_tags (news_id, tag_id)

### events
- id, title_lo, title_en, title_zh, slug, description_lo/en/zh, thumbnail, location_lo/en/zh, country, start_date, end_date, start_time, end_time, category_id, organizer_lo/en/zh, registration_url, registration_deadline, max_participants, status, is_featured, is_international, author_id, view_count, timestamps

### categories
- id, name_lo, name_en, name_zh, slug, type, parent_id, color, icon, sort_order, is_active, created_at

### tags
- id, name_lo, name_en, name_zh, slug

### event_tags
- id, name_lo, name_en, name_zh

## ວິທີການຕິດຕັ້ງ (Installation)

### 1. Clone Project & Install Dependencies
```bash
git clone <repo-url>
cd bfol
composer install
npm install && npm run build
```

### 2. Copy .env & Configure
```bash
cp .env.example .env
# ແກ້ໄຂ DB_DATABASE, DB_USERNAME, DB_PASSWORD ໃຫ້ຕົວເອງ
php artisan key:generate
```

### 3. Run Migrations & Seeders
```bash
php artisan migrate
php artisan db:seed # (optional, if seeders exist)
```

### 4. Storage & Permissions
```bash
php artisan storage:link
# ໃຫ້ສິດທິຂຽນໃຫ້ storage, bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 5. Start Local Server
```bash
php artisan serve
# ຫຼືໃຊ້ XAMPP/Valet/Apache
```

## Admin Login
- URL: `/admin/login`
- Default roles: (set up via seeder or admin)

---
ເພີ່ມເຕີມ: ລະບົບນີ້ສາມາດຂະຫຍາຍຟີຈເຈີເພີ່ມເຕີມໄດ້ຕາມຄວາມຕ້ອງການ

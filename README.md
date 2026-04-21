# BFOL — Buddhist Foreign Affairs of Laos CMS

ລະບົບ Content Management System (CMS) ສຳລັບ **ກົມກັມມາທິການຕ່າງປະເທດຂອງພຸດທະສາສະໜາລາວ** ພັດທະນາດ້ວຍ Laravel 13 ຮອງຮັບ 3 ພາສາ (ລາວ, ອັງກິດ, ຈີນ) ພ້ອມໜ້າຄຸ້ມຄອງ (Admin Panel) ສຳລັບຈັດການເນື້ອຫາທຸກໝວດໝູ່.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13.5, PHP 8.4 |
| Database | MySQL 8 |
| Frontend (Admin) | Tailwind CSS (CDN), Alpine.js 3, Font Awesome 6 |
| File Storage | Laravel Storage (`public` disk → `storage/app/public/`) |
| Auth | Laravel Breeze (session-based) |
| Server | XAMPP / Apache |

---

## Admin CRUD Status

| ໝວດ | ຕາລາງ | Admin CRUD | ໝາຍເຫດ |
|---|---|---|---|
| ເນື້ອຫາ | `news` | ✅ ພັດທະນາແລ້ວ | ຂ່າວສານ: ບັນທຶກຂໍ້ມູນຂ່າວສານ ພ້ອມຫົວຂໍ້, ສະຫຼຸບ, ເນື້ອຫາ 3 ພາສາ, ຮູບປົກ, ສະຖານະ, ຈຳນວນຜູ້ເຂົ້າຊົມ, ໝວດໝູ່, ຜູ້ຂຽນ |
| ເນື້ອຫາ | `events` | ✅ ພັດທະນາແລ້ວ | ກິດຈະກຳ/ອີເວັນ: ບັນທຶກກິດຈະກຳ ເຊັ່ນ ງານສົນທະນາ, ສົນທະກຳ, ງານບຸນ |
| ເນື້ອຫາ | `pages` | ✅ ພັດທະນາແລ້ວ | ໜ້າເນື້ອຫາທົ່ວໄປ: ເຊັ່ນ ໜ້າກ່ຽວກັບ, ໜ້າຕິດຕໍ່ |
| ເນື້ອຫາ | `media_items` | ✅ ພັດທະນາແລ້ວ | ສື່ມວນຊົນ: ຮູບພາບ, ວິດີໂອ, ເອກະສານສຳລັບເວັບໄຊ |
| ເນື້ອຫາ | `documents` | ✅ ພັດທະນາແລ້ວ | ເອກະສານ: ເອກະສານທີ່ສາມາດດາວໂຫຼດໄດ້ |
| ເນື້ອຫາ | `categories` | ✅ ພັດທະນາແລ້ວ | ໝວດໝູ່: ຈັດປະເພດເນື້ອຫາ (ຂ່າວ, ກິດຈະກຳ, ສື່, ເອກະສານ) ແບບຊັບຊ້ອນໄດ້ |
| ເນື້ອຫາ | `tags` | ✅ ພັດທະນາແລ້ວ | ແທັກ: ຄຳຄົ້ນຫາ/ປ້າຍກຳກັບເນື້ອຫາ |
| ເນື້ອຫາ | `event_tags` | ✅ ພັດທະນາແລ້ວ | ແທັກກິດຈະກຳ: ປ້າຍກຳກັບສຳລັບກິດຈະກຳ |
| ພາລະກິດ | `partner_organizations` | ✅ ພັດທະນາແລ້ວ | ອົງການຮ່ວມມື: ບັນທຶກອົງການພາລະກິດຮ່ວມມື |
| ພາລະກິດ | `mou_agreements` | ✅ ພັດທະນາແລ້ວ | ເອກະສານ MOU: ບັນທຶກຂໍ້ຕົກລົງຮ່ວມມື |
| ພາລະກິດ | `monk_exchange_programs` | ✅ ພັດທະນາແລ້ວ | ໂຄງການແລກປ່ຽນພຣະ: ລາຍການໂຄງການແລກປ່ຽນພຣະ |
| ພາລະກິດ | `aid_projects` | ✅ ພັດທະນາແລ້ວ | ໂຄງການຊ່ວຍເຫຼືອ: ບັນທຶກໂຄງການຊ່ວຍເຫຼືອຕ່າງໆ |
| ໜ້າຫຼັກ | `hero_slides` | ✅ ພັດທະນາແລ້ວ | ຮູບສະແດງໜ້າຫຼັກ: ຮູບສະແດງໃນ Slider ໜ້າຫຼັກ |
| ໜ້າຫຼັກ | `banners` | ✅ ພັດທະນາແລ້ວ | ແບນເນີ: ຮູບແບນເນີໃນເວັບໄຊ |
| ໜ້າຫຼັກ | `committee_members` |  ❌ ຍັງບໍ່ທັນ  | ຄະນະກຳມາທິການ: ລາຍຊື່ຄະນະກຳມາທິການ |
| ລະບົບ | `users` | ✅ ພັດທະນາແລ້ວ | ຜູ້ໃຊ້ລະບົບ: ບັນທຶກຂໍ້ມູນ Admin ແລະສິດທິ |
| ລະບົບ | `site_settings` | ✅ ພັດທະນາແລ້ວ | ຕັ້ງຄ່າເວັບໄຊ: ການກຳນົດຄ່າພື້ນຖານເວັບໄຊ |
| ລະບົບ | `contact_messages` | ✅ ພັດທະນາແລ້ວ | ຂໍ້ຄວາມຕິດຕໍ່: ບັນທຶກຂໍ້ຄວາມຈາກແບບຟອມຕິດຕໍ່ (ອ່ານ-ລຶບ) |
| ລະບົບ | `contact_messages` | ✅ ພັດທະນາແລ້ວ | ອ່ານ-ລຶບ (ບໍ່ມີ create/edit) |
| ຍັງບໍ່ພັດທະນາ | `monk_exchange_applications` | ❌ ຍັງບໍ່ທັນ | ການສະໝັກແລກປ່ຽນພຣະ |
| ຍັງບໍ່ພັດທະນາ | `translation_projects` | ❌ ຍັງບໍ່ທັນ | ໂຄງການແປພາສາ |
| ຍັງບໍ່ພັດທະນາ | `photo_albums` | ❌ ຍັງບໍ່ທັນ | ອາລ໌ບໍ້ຮູບ |
| ຍັງບໍ່ພັດທະນາ | `photo_album_images` | ❌ ຍັງບໍ່ທັນ | ຮູບພາຍໃນອາລ໌ບໍ້ |
| ຍັງບໍ່ພັດທະນາ | `site_statistics` | ❌ ຍັງບໍ່ທັນ | ສະຖິຕິໜ້າຫຼັກ |

---

## ໂຄງສ້າງຖານຂໍ້ມູນ (Database Schema)

### 🔵 ຕາລາງລະບົບ (System Tables)

---

#### `users` — ຜູ້ໃຊ້ລະບົບ
ເກັບຂໍ້ມູນ Admin ທີ່ສາມາດເຂົ້າໃຊ້ລະບົບ ຈັດການເນື້ອຫາໄດ້ຕາມ Role.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `username` | varchar(60) UNIQUE | ຊື່ຜູ້ໃຊ້ (unique) |
| `email` | varchar(120) UNIQUE | ອີເມລ |
| `email_verified_at` | timestamp NULL | ວັນທີຢືນຢັນອີເມລ |
| `password` | varchar | ລະຫັດຜ່ານ (bcrypt) |
| `full_name_lo` | varchar(120) | ຊື່ເຕັມ (ລາວ) |
| `full_name_en` | varchar(120) NULL | ຊື່ເຕັມ (EN) |
| `full_name_zh` | varchar(120) NULL | ຊື່ເຕັມ (ZH) |
| `role` | enum | `superadmin` / `admin` / `editor` / `viewer` |
| `avatar_url` | varchar(500) NULL | URL ຮູບໂປຣໄຟລ |
| `is_active` | boolean | ເປີດ/ປິດບັນຊີ |
| `last_login` | datetime NULL | ເວລາ login ລ່າສຸດ |
| `remember_token` | varchar NULL | Token ຈຳລະຫັດຜ່ານ |
| `created_at` / `updated_at` | timestamp | ສ້າງ / ອັບເດດ |

ຕາລາງທີ່ກ່ຽວຂ້ອງ: `sessions`, `password_reset_tokens`

---

#### `site_settings` — ການຕັ້ງຄ່າເວັບໄຊ
ເກັບຂໍ້ມູນການຕັ້ງຄ່າທົ່ວໄປໃນຮູບແບບ key-value ຈັດກຸ່ມຕາມໝວດ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `key` | varchar(100) UNIQUE | ຊື່ Key ເຊັ່ນ `site_name`, `contact_email` |
| `value` | longtext NULL | ຄ່າ (text, URL ຮູບ, boolean, ຕົວເລກ) |
| `type` | enum | `text` / `textarea` / `image` / `json` / `boolean` / `number` / `color` |
| `group` | varchar(80) | ໝວດ: `general` / `contact` / `social` / `display` / `system` |
| `label_lo` | varchar(200) NULL | ຊື່ສະແດງ (ລາວ) |
| `label_en` | varchar(200) NULL | ຊື່ສະແດງ (EN) |
| `label_zh` | varchar(200) NULL | ຊື່ສະແດງ (ZH) |
| `updated_by` | FK → users NULL | ຜູ້ອັບເດດລ່າສຸດ |
| `updated_at` | timestamp auto | ອັບເດດອັດຕະໂນມັດ |

---

#### `contact_messages` — ຂໍ້ຄວາມຕິດຕໍ່
ຮັບຂໍ້ຄວາມຈາກ Form ຕິດຕໍ່ໃນໜ້າເວັບ. Admin ສາມາດອ່ານ, ໝາຍສະຖານະ, ແລະລຶບໄດ້.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `name` | varchar(150) | ຊື່ຜູ້ສົ່ງ |
| `email` | varchar(120) | ອີເມລ |
| `phone` | varchar(50) NULL | ເບີໂທ |
| `subject` | varchar(300) NULL | ຫົວຂໍ້ |
| `message` | text | ເນື້ອຫາຂໍ້ຄວາມ |
| `language` | enum | `lo` / `en` / `zh` (ພາສາທີ່ຜູ້ໃຊ້ຕິດຕໍ່) |
| `is_read` | boolean | ອ່ານແລ້ວ/ຍັງບໍ່ |
| `replied_by` | FK → users NULL | Admin ທີ່ຕອບ |
| `replied_at` | datetime NULL | ເວລາຕອບ |
| `ip_address` | varchar(45) NULL | IP ຜູ້ສົ່ງ |
| `created_at` | timestamp auto | ເວລາສົ່ງ |

> ⚠️ ຕາລາງນີ້ `timestamps = false` — ມີພຽງ `created_at` (ບໍ່ມີ `updated_at`)

---

### 🟡 ຕາລາງເນື້ອຫາ (Content Tables)

---

#### `categories` — ໝວດໝູ່
ໝວດໝູ່ສຳລັບ News, Events, Media, Documents ແລະ Mission ຮອງຮັບ parent-child hierarchy.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `name_lo` | varchar(120) | ຊື່ (ລາວ) |
| `name_en` | varchar(120) NULL | ຊື່ (EN) |
| `name_zh` | varchar(120) NULL | ຊື່ (ZH) |
| `slug` | varchar(120) UNIQUE | URL slug |
| `type` | enum | `news` / `event` / `media` / `document` / `mission` |
| `parent_id` | FK → categories NULL | ໝວດແມ່ (ຮອງຮັບ sub-category) |
| `color` | varchar(10) | ສີ hex เช่น `#1a3a6b` |
| `icon` | varchar(80) NULL | Font Awesome class |
| `sort_order` | smallint | ລຳດັບ (ຕ່ຳ = ກ່ອນ) |
| `is_active` | boolean | ເປີດ/ປິດ |
| `created_at` | timestamp auto | |

---

#### `tags` — Tag ຂ່າວ/ສື່
Tag ທົ່ວໄປໃຊ້ສຳລັບ News (ຜ່ານ pivot `news_tags`).

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `name_lo` | varchar(80) | ຊື່ (ລາວ) |
| `name_en` | varchar(80) NULL | ຊື່ (EN) |
| `name_zh` | varchar(80) NULL | ຊື່ (ZH) |
| `slug` | varchar(80) UNIQUE | URL slug |

Pivot: **`news_tags`** (`news_id`, `tag_id`) — ຄວາມສຳພັນ News ↔ Tag

---

#### `event_tags` — Tag ກິດຈະກໍາ
Tag ສະເພາະກິດຈະກໍາ (ແຍກຕ່າງຫາກຈາກ `tags` ທົ່ວໄປ).

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `name_lo` | varchar | ຊື່ (ລາວ) |
| `name_en` | varchar | ຊື່ (EN) |
| `name_zh` | varchar | ຊື່ (ZH) |
| `created_at` / `updated_at` | timestamp | |

---

#### `news` — ຂ່າວສານ
ຂ່າວສານທົ່ວໄປຂອງ BFOL ຮອງຮັບ 3 ພາສາ, ໝວດໝູ່, tag, featured ແລະ urgent.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo` | varchar(300) | ຫົວຂໍ້ (ລາວ) |
| `title_en` | varchar(300) NULL | ຫົວຂໍ້ (EN) |
| `title_zh` | varchar(300) NULL | ຫົວຂໍ້ (ZH) |
| `slug` | varchar(300) UNIQUE | URL slug |
| `excerpt_lo/en/zh` | text NULL | ຄຳຫຍໍ້ 3 ພາສາ |
| `content_lo` | longtext | ເນື້ອຫາ (ລາວ) — ຕ້ອງມີ |
| `content_en` / `content_zh` | longtext NULL | ເນື້ອຫາ EN / ZH |
| `thumbnail` | varchar(500) NULL | URL ຮູບໜ້າຫຼັກ |
| `category_id` | FK → categories NULL | ໝວດໝູ່ |
| `author_id` | FK → users NULL | ຜູ້ຂຽນ |
| `status` | enum | `draft` / `published` / `archived` |
| `is_featured` | boolean | ສະແດງໃນ Featured section |
| `is_urgent` | boolean | ຂ່າວດ່ວນ (highlight) |
| `view_count` | int unsigned | ຈຳນວນຄົນເບິ່ງ |
| `published_at` | datetime NULL | ເວລາເຜີຍແຜ່ |
| `created_at` / `updated_at` | timestamp | |

Pivot: **`news_tags`** (`news_id`, `tag_id`)

---

#### `events` — ກິດຈະກໍາ
ກິດຈະກໍາ ແລະ ງານໄຫວ້ຕ່າງໆ ຮອງຮັບ 3 ພາສາ, ສະຖານທີ, ຂໍ້ມູນລົງທະບຽນ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar | ຫົວຂໍ້ 3 ພາສາ |
| `slug` | varchar UNIQUE | URL slug |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `thumbnail` | varchar NULL | URL ຮູບ |
| `location_lo/en/zh` | varchar NULL | ສະຖານທີ 3 ພາສາ |
| `country` | varchar NULL | ປະເທດ |
| `start_date` / `end_date` | date NULL | ວັນເລີ່ມ / ສຸດ |
| `start_time` / `end_time` | time NULL | ເວລາ |
| `category_id` | FK → categories NULL | ໝວດ |
| `organizer_lo/en/zh` | varchar NULL | ຜູ້ຈັດ 3 ພາສາ |
| `registration_url` | varchar NULL | URL ລົງທະບຽນ |
| `registration_deadline` | date NULL | ກຳນົດລົງທະບຽນ |
| `max_participants` | int NULL | ຈຳນວນຮັບສູງສຸດ |
| `status` | varchar | `upcoming` / `ongoing` / `completed` / `cancelled` |
| `is_featured` | boolean | Featured |
| `is_international` | boolean | ງານລະດັບສາກົນ |
| `author_id` | FK → users NULL | ຜູ້ສ້າງ |
| `view_count` | bigint unsigned | ຈຳນວນເບິ່ງ |
| `created_at` / `updated_at` | timestamp | |

---

#### `pages` — ໜ້າຂໍ້ມູນ Static
ໜ້າ static ເຊັ່ນ ກ່ຽວກັບເຮົາ, ນະໂຍບາຍ, ຕິດຕໍ່ ຮອງຮັບ SEO meta ແລະ 3 ພາສາ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `slug` | varchar(150) UNIQUE | URL slug (ເຊັ່ນ `about`, `contact`) |
| `title_lo/en/zh` | varchar(300) | ຫົວຂໍ້ 3 ພາສາ |
| `content_lo/en/zh` | longtext NULL | ເນື້ອຫາ 3 ພາສາ (HTML) |
| `meta_title_lo/en/zh` | varchar(200) NULL | Meta title SEO |
| `meta_description` | varchar(500) NULL | Meta description |
| `thumbnail` | varchar(500) NULL | ຮູບ OG Image |
| `parent_slug` | varchar(150) NULL | slug ໜ້າແມ່ |
| `sort_order` | smallint | ລຳດັບ |
| `is_published` | boolean | ເຜີຍແຜ່/ຊ່ອນ |
| `author_id` | FK → users NULL | ຜູ້ສ້າງ |
| `created_at` / `updated_at` | timestamp | |

---

#### `media_items` — ສື່ (ຮູບ / ວີດີໂອ / ສຽງ)
ຮູບ, ວີດີໂອ, ສຽງ ທີ່ upload ເອງ ຫຼື embed ຈາກ YouTube/Facebook/SoundCloud.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(300) | ຊື່ 3 ພາສາ |
| `type` | enum | `image` / `video` / `audio` / `document` |
| `file_url` | varchar(500) NULL | URL ໄຟລ໌ (storage) |
| `thumbnail_url` | varchar(500) NULL | URL thumbnail |
| `description_lo/en/zh` | text NULL | ຄຳອະທິບາຍ 3 ພາສາ |
| `category_id` | FK → categories NULL | ໝວດ |
| `event_id` | FK → events NULL | ກິດຈະກໍາທີ່ກ່ຽວຂ້ອງ |
| `platform` | enum | `local` / `youtube` / `facebook` / `soundcloud` / `other` |
| `external_url` | varchar(500) NULL | URL ຈາກ platform ພາຍນອກ |
| `duration_sec` | int NULL | ຄວາມຍາວ (ວິນາທີ) |
| `file_size_kb` | int NULL | ຂະໜາດໄຟລ໌ |
| `mime_type` | varchar(100) NULL | MIME type |
| `is_featured` | boolean | Featured |
| `view_count` | int | ຈຳນວນເບິ່ງ |
| `download_count` | int | ຈຳນວນດາວໂຫຼດ |
| `published_at` | datetime NULL | ເວລາເຜີຍ |
| `author_id` | FK → users NULL | ຜູ້ອັບໂຫຼດ |
| `created_at` / `updated_at` | timestamp | |

---

#### `documents` — ເອກະສານ
ໄຟລ໌ PDF, Word ຫຼື ເອກະສານອື່ນໆ ທີ່ admin upload ເພື່ອດາວໂຫຼດ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(300) | ຊື່ 3 ພາສາ |
| `file_url` | varchar(500) | URL ໄຟລ໌ (ຕ້ອງມີ) |
| `file_type` | varchar(20) NULL | ນາມສະກຸນ: `pdf`, `docx`, ... |
| `file_size_kb` | int NULL | ຂະໜາດໄຟລ໌ (KB) |
| `category_id` | FK → categories NULL | ໝວດ |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `is_public` | boolean | ສາທາລະນະ / private |
| `download_count` | int | ຈຳນວນດາວໂຫຼດ |
| `published_at` | datetime NULL | ເວລາເຜີຍ |
| `author_id` | FK → users NULL | ຜູ້ upload |
| `created_at` / `updated_at` | timestamp | |

---

### 🟠 ຕາລາງໜ້າຫຼັກ (Homepage Tables)

---

#### `hero_slides` — Slides ໜ້າຫຼັກ
Slides ສຳລັບ Hero Banner ໃນໜ້າຫຼັກຂອງເວັບ ຮອງຮັບ 2 ປຸ່ມ CTA ຕໍ່ Slide.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `tag_lo/en/zh` | varchar(100) NULL | ປ້າຍຂະໜາດນ້ອຍ (เช่น "ຂ່າວ") |
| `title_lo/en/zh` | varchar(300) | ຫົວຂໍ້ Slide 3 ພາສາ |
| `subtitle_lo/en/zh` | text NULL | ຄຳບັນຍາຍ 3 ພາສາ |
| `image_url` | varchar(500) | URL ຮູບ Background (ຕ້ອງມີ) |
| `btn1_text_lo/en/zh` | varchar(80) NULL | ຊື່ປຸ່ມ 1 ສາມພາສາ |
| `btn1_url` | varchar(500) NULL | URL ປຸ່ມ 1 |
| `btn2_text_lo/en/zh` | varchar(80) NULL | ຊື່ປຸ່ມ 2 ສາມພາສາ |
| `btn2_url` | varchar(500) NULL | URL ປຸ່ມ 2 |
| `sort_order` | smallint | ລຳດັບ (ຕ່ຳ = ກ່ອນ) |
| `is_active` | boolean | ເປີດ/ປິດ |
| `created_at` / `updated_at` | timestamp | |

---

#### `banners` — Banner ໂຄສະນາ
Banner ສ່ວນຕ່າງໆຂອງເວັບ (Sidebar, Top, Bottom, Popup) ກຳນົດສີ ແລະ ຕຳແໜ່ງໄດ້.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(200) | ຫົວຂໍ້ Banner 3 ພາສາ |
| `subtitle_lo/en/zh` | text NULL | ຄຳບັນຍາຍ 3 ພາສາ |
| `image_url` | varchar(500) NULL | URL ຮູບ Banner |
| `btn_text_lo/en/zh` | varchar(80) NULL | ຊື່ປຸ່ມ CTA 3 ພາສາ |
| `btn_url` | varchar(500) NULL | URL ປຸ່ມ CTA |
| `style` | varchar(50) | ສີ: `banner-blue` / `banner-green` / `banner-gold` / `banner-dark` / `banner-light` / `banner-red` |
| `position` | varchar(80) | ຕຳແໜ່ງ: `sidebar` / `top` / `bottom` / `popup` / `inline` |
| `sort_order` | smallint | ລຳດັບ |
| `is_active` | boolean | ເປີດ/ປິດ |
| `created_at` / `updated_at` | timestamp | |

Index: `(is_active, position, sort_order)`

---

#### `committee_members` — ຄະນະກຳມະການ
ສະມາຊິກຄະນະກຳມະການຕ່າງປະເທດ ຮອງຮັບ 3 ພາສາ, ຮູບ, ໄລຍະດຳລົງຕຳແໜ່ງ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `name_lo/en/zh` | varchar(200) | ຊື່ 3 ພາສາ |
| `title_lo/en/zh` | varchar(100) NULL | ຄຳນຳໜ້າ ເຊັ່ນ ທ່ານ, ດຣ. |
| `position_lo/en/zh` | varchar(200) | ຕຳແໜ່ງ 3 ພາສາ (ຕ້ອງມີ ລາວ) |
| `department` | varchar(200) NULL | ພະແນກ / ໜ່ວຍງານ |
| `photo_url` | varchar(500) NULL | URL ຮູບໂປຣໄຟລ |
| `bio_lo/en/zh` | text NULL | ປະຫວັດຫຍໍ້ 3 ພາສາ |
| `email` | varchar(120) NULL | ອີເມລ |
| `phone` | varchar(50) NULL | ເບີໂທ |
| `term_start` | year NULL | ປີເລີ່ມດຳລົງ |
| `term_end` | year NULL | ປີສຸດດຳລົງ |
| `sort_order` | smallint | ລຳດັບ |
| `is_active` | boolean | ດຳລົງຕຳແໜ່ງຢູ່/ອອກແລ້ວ |
| `created_at` / `updated_at` | timestamp | |

---

#### `site_statistics` — ສະຖິຕິໜ້າຫຼັກ ❌ ຍັງບໍ່ທັນພັດທະນາ
ຕົວເລກສະຖິຕິ ສະແດງໃນ Counter Section ຂອງໜ້າຫຼັກ ເຊັ່ນ "500+ ໂຄງການ".

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `label_lo/en/zh` | varchar(100) | ຊື່ສະຖິຕິ 3 ພາສາ |
| `value` | int unsigned | ຕົວເລກ |
| `icon` | varchar(100) NULL | Font Awesome class |
| `suffix` | varchar(20) NULL | ຕໍ່ທ້າຍ ເຊັ່ນ `+`, `%` |
| `sort_order` | smallint | ລຳດັບ |
| `is_active` | boolean | ສະແດງ/ຊ່ອນ |
| `updated_at` | timestamp auto | ອັບເດດອັດຕະໂນມັດ |

---

#### `photo_albums` — ອາລ໌ບໍ້ຮູບ ❌ ຍັງບໍ່ທັນພັດທະນາ
ອາລ໌ບໍ້ຮູບຕ່າງໆ ເຊື່ອມກັບກິດຈະກໍາ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(200) | ຊື່ 3 ພາສາ |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `cover_image` | varchar(500) NULL | URL ຮູບໜ້າຫຼັກ Album |
| `event_id` | FK → events NULL | ກິດຈະກໍາທີ່ກ່ຽວຂ້ອງ |
| `is_public` | boolean | ສາທາລະນະ |
| `created_at` / `updated_at` | timestamp | |

ຕາລາງລູກ: **`photo_album_images`** (`id`, `album_id`, `image_url`, `caption_lo/en/zh`, `sort_order`, `created_at`)

---

### 🟢 ຕາລາງພາລະກິດ (Mission Tables)

---

#### `partner_organizations` — ອົງກອນຄູ່ຮ່ວມ
ອົງກອນທາງດ້ານສາດສະໜາ, ລັດຖະບານ ຫຼື NGO ທີ່ BFOL ຮ່ວມມື.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `name_lo/en/zh` | varchar(200) | ຊື່ 3 ພາສາ |
| `acronym` | varchar(30) NULL | ຊື່ຫຍໍ້ ເຊັ່ນ `UNESCO`, `WFB` |
| `country_code` | char(2) | ລະຫັດປະເທດ ISO ເຊັ່ນ `CN`, `TH` |
| `country_name_lo/en/zh` | varchar(100) | ຊື່ປະເທດ 3 ພາສາ |
| `logo_url` | varchar(500) NULL | URL Logo |
| `website_url` | varchar(500) NULL | URL ເວັບໄຊ |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `contact_person` | varchar(200) NULL | ຊື່ຜູ້ຕິດຕໍ່ |
| `contact_email` | varchar(120) NULL | ອີເມລຕິດຕໍ່ |
| `contact_phone` | varchar(50) NULL | ເບີໂທ |
| `type` | enum | `buddhist_org` / `government` / `ngo` / `academic` / `media` / `un_agency` / `other` |
| `partnership_since` | year NULL | ປີທີ່ເລີ່ມຮ່ວມມື |
| `status` | enum | `active` / `inactive` / `pending` |
| `sort_order` | smallint | ລຳດັບ |
| `created_at` / `updated_at` | timestamp | |

---

#### `mou_agreements` — ບົດບັນທຶກຄວາມເຂົ້າໃຈ (MOU)
ສັນຍາ MOU ລະຫວ່າງ BFOL ແລະ ອົງກອນຄູ່ຮ່ວມ ຮອງຮັບໄຟລ໌ເອກະສານ ແລະ ການແຈ້ງເຕືອນໝົດອາຍຸ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(300) | ຊື່ MOU 3 ພາສາ |
| `partner_org_id` | FK → partner_organizations | ອົງກອນຄູ່ຮ່ວມ (ຕ້ອງມີ) |
| `signed_date` | date | ວັນທີເຊັນ (ຕ້ອງມີ) |
| `expiry_date` | date NULL | ວັນໝົດອາຍຸ |
| `document_url` | varchar(500) NULL | URL ໄຟລ໌ MOU (PDF) |
| `status` | enum | `active` / `expired` / `pending` / `renewed` / `terminated` |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `signers_lo/en/zh` | text NULL | ລາຍຊື່ຜູ້ເຊັນ 3 ພາສາ |
| `scope_lo/en/zh` | text NULL | ຂອບເຂດ MOU 3 ພາສາ |
| `created_at` / `updated_at` | timestamp | |

> ⚠️ `partner_org_id` ໃຊ້ `RESTRICT` — ລຶບ Partner ທີ່ມີ MOU ຜູກຢູ່ບໍ່ໄດ້

---

#### `monk_exchange_programs` — ໂຄງການແລກປ່ຽນພຣະ
ໂຄງການສົ່ງພຣະໄປຕ່າງປະເທດ ຮອງຮັບ quota, deadline ສະໝັກ ແລະ ສະຖານະ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(300) | ຊື່ 3 ພາສາ |
| `destination_country` | varchar(100) | ປະເທດປາຍທາງ |
| `partner_org_id` | FK → partner_organizations NULL | ອົງກອນຄູ່ຮ່ວມ |
| `year` | year | ປີໂຄງການ |
| `application_open` | date NULL | ວັນເປີດຮັບສະໝັກ |
| `application_deadline` | date NULL | ວັນປິດຮັບສະໝັກ |
| `program_start` | date NULL | ວັນເລີ່ມໂຄງການ |
| `program_end` | date NULL | ວັນສຸດໂຄງການ |
| `monks_quota` | smallint NULL | ຈຳນວນຮັບ |
| `monks_selected` | smallint | ຈຳນວນໄດ້ຮັບເລືອກແລ້ວ |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `requirements_lo/en/zh` | text NULL | ເງື່ອນໄຂ 3 ພາສາ |
| `application_url` | varchar(500) NULL | URL Form ສະໝັກ |
| `contact_email` | varchar(120) NULL | ອີເມລຕິດຕໍ່ |
| `status` | enum | `draft` / `open` / `closed` / `ongoing` / `completed` / `cancelled` |
| `is_featured` | boolean | Featured |
| `author_id` | FK → users NULL | ຜູ້ສ້າງ |
| `created_at` / `updated_at` | timestamp | |

---

#### `monk_exchange_applications` — ໃບສະໝັກແລກປ່ຽນພຣະ ❌ ຍັງບໍ່ທັນພັດທະນາ
ໃບສະໝັກຈາກພຣະໄປ Admin ສຳລັບ Review ແລະ ອະນຸມັດ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `program_id` | FK → monk_exchange_programs | ໂຄງການທີ່ສະໝັກ |
| `monk_name_lo` | varchar(200) | ຊື່ພຣະ (ລາວ) |
| `monk_name_en` | varchar(200) NULL | ຊື່ (EN) |
| `temple_name_lo` | varchar(200) NULL | ຊື່ວັດ |
| `province` | varchar(100) NULL | ແຂວງ |
| `phone` | varchar(50) NULL | ເບີໂທ |
| `years_ordained` | smallint NULL | ຈຳນວນວັສສາ |
| `languages` | varchar(200) NULL | ພາສາທີ່ໃຊ້ໄດ້ |
| `documents_url` | varchar(500) NULL | URL ໄຟລ໌ເອກະສານ |
| `status` | enum | `pending` / `reviewing` / `approved` / `rejected` / `withdrawn` |
| `notes` | text NULL | ໝາຍເຫດ Admin |
| `reviewed_by` | FK → users NULL | Admin ທີ່ Review |
| `reviewed_at` | datetime NULL | ເວລາ Review |
| `submitted_at` | timestamp auto | ເວລາສົ່ງ |
| `updated_at` | timestamp auto | |

---

#### `aid_projects` — ໂຄງການຊ່ວຍເຫຼືອ
ໂຄງການຊ່ວຍເຫຼືອ (ສາດສະໜາ, ມະນຸດສະທຳ, ການສຶກສາ, ວັດທະນະທຳ) ທີ່ BFOL ດຳເນີນຢູ່ຕ່າງປະເທດ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(300) | ຊື່ 3 ພາສາ |
| `country` | varchar(100) | ປະເທດ (ຕ້ອງມີ) |
| `partner_org_id` | FK → partner_organizations NULL | ອົງກອນຄູ່ຮ່ວມ |
| `type` | enum | `religious` / `humanitarian` / `educational` / `cultural` / `other` |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `budget_usd` | decimal(15,2) NULL | ງົບປະມານ (USD) |
| `start_date` | date NULL | ວັນເລີ່ມ |
| `end_date` | date NULL | ວັນສຸດ |
| `status` | enum | `planning` / `active` / `completed` / `suspended` / `cancelled` |
| `report_url` | varchar(500) NULL | URL ລາຍງານ (Google Drive...) |
| `author_id` | FK → users NULL | ຜູ້ສ້າງ |
| `created_at` / `updated_at` | timestamp | |

---

#### `translation_projects` — ໂຄງການແປພາສາ ❌ ຍັງບໍ່ທັນພັດທະນາ
ໂຄງການແປຄຳພີ, ເອກະສານ ພຸດທະສາດ ລະຫວ່າງພາສາຕ່າງໆ.

| ຄໍລຳ | ປະເພດ | ລາຍລະອຽດ |
|---|---|---|
| `id` | bigint PK | Primary key |
| `title_lo/en/zh` | varchar(300) | ຊື່ 3 ພາສາ |
| `source_language` | varchar(60) | ພາສາຕົ້ນທາງ ເຊັ່ນ `ປາລີ`, `ສັນສະກຣິດ` |
| `target_language` | varchar(60) | ພາສາປາຍທາງ ເຊັ່ນ `ລາວ`, `ອັງກິດ` |
| `description_lo/en/zh` | text NULL | ລາຍລະອຽດ 3 ພາສາ |
| `document_url` | varchar(500) NULL | URL ໄຟລ໌ |
| `translator` | varchar(200) NULL | ຊື່ຜູ້ແປ |
| `year` | year NULL | ປີ |
| `status` | enum | `in_progress` / `reviewing` / `completed` / `published` |
| `created_at` / `updated_at` | timestamp | |

---

### ⚙️ ຕາລາງ Laravel Framework

| ຕາລາງ | ຈຸດປະສົງ |
|---|---|
| `sessions` | Session ຜູ້ໃຊ້ (database driver) |
| `password_reset_tokens` | Token Reset ລະຫັດຜ່ານ |
| `cache` / `cache_locks` | Laravel Cache |
| `jobs` / `job_batches` / `failed_jobs` | Queue Jobs |

---

## Admin Panel Routes

```
GET    /admin                          Dashboard
GET    /admin/news                     ລາຍການຂ່າວ
GET    /admin/events                   ລາຍການກິດຈະກໍາ
GET    /admin/pages                    ໜ້າ Static
GET    /admin/media                    ສື່ (ຮູບ/ວີດີໂອ)
GET    /admin/documents                ເອກະສານ
GET    /admin/categories               ໝວດໝູ່
GET    /admin/tags                     Tags
GET    /admin/event_tags               Event Tags
GET    /admin/partners                 ອົງກອນຄູ່ຮ່ວມ
GET    /admin/mou                      MOU
GET    /admin/monk-programs            ແລກປ່ຽນພຣະ
GET    /admin/aid-projects             ຊ່ວຍເຫຼືອ
GET    /admin/committee                ຄະນະກຳມະການ
GET    /admin/slides                   Hero Slides
GET    /admin/banners                  Banners
GET    /admin/contacts                 ຂໍ້ຄວາມຕິດຕໍ່
GET    /admin/users                    ຜູ້ໃຊ້
GET    /admin/settings                 ການຕັ້ງຄ່າ
```

---

## ການຕິດຕັ້ງ (Installation)

### 1. Clone & Dependencies
```bash
git clone <repo-url>
cd bfol
composer install
```

### 2. ຕັ້ງຄ່າ Environment
```bash
cp .env.example .env
# ແກ້ໄຂ DB_DATABASE, DB_USERNAME, DB_PASSWORD
php artisan key:generate
```

### 3. Migrate & Storage
```bash
php artisan migrate
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

### 4. ເລີ່ມ Server
```bash
# XAMPP: ວາງໂຄງການໃນ htdocs/ ແລ້ວ Start Apache
# ຫຼື
php artisan serve
```

### 5. Admin Login
```
URL:  http://localhost/bfol/public/admin/login
```

---

## ໂຄງສ້າງໄຟລ໌ (Key Directories)

```
app/
  Http/Controllers/Admin/   ← Controllers ທຸກ module
  Models/                   ← Eloquent Models
  Traits/HasTranslations.php ← Helper ຫຼາຍພາສາ

resources/views/admin/
  layouts/app.blade.php     ← Layout ຫຼັກ (Sidebar + Topbar)
  news/                     ← Views ຂ່າວ
  events/                   ← Views ກິດຈະກໍາ
  partners/                 ← Views ຄູ່ຮ່ວມ
  mou/                      ← Views MOU
  monk-programs/            ← Views ແລກປ່ຽນພຣະ
  aid-projects/             ← Views ໂຄງການຊ່ວຍເຫຼືອ
  banners/                  ← Views Banner
  contacts/                 ← Views ຂໍ້ຄວາມ
  settings/                 ← Views ຕັ້ງຄ່າ
  ...

storage/app/public/
  news/                     ← ຮູບຂ່າວ
  slides/                   ← ຮູບ Slides
  banners/                  ← ຮູບ Banner
  mou-documents/            ← ໄຟລ໌ MOU
  partners/                 ← Logo ຄູ່ຮ່ວມ
  documents/                ← ເອກະສານດາວໂຫຼດ
```

---

## ໝາຍເຫດ ການພັດທະນາ

- **Multilingual**: ທຸກ model ທີ່ມີ `_lo/_en/_zh` fields ໃຊ້ `HasTranslations` trait
- **File Upload**: ໃຊ້ `Storage::disk('public')` — ຕ້ອງ run `php artisan storage:link` ກ່ອນ
- **Boolean Checkbox**: ໃຊ້ hidden `value="0"` + checkbox `value="1"` ເພື່ອຮອງຮັບ unchecked state
- **Route Model Binding**: ທຸກ Controller ໃຊ້ type-hinted Model (ເຊັ່ນ `AidProject $aidProject`)
- **Pagination**: ທຸກ index ໃຊ້ `->paginate()->withQueryString()` ເພື່ອຮັກສາ filter

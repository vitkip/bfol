# BFOL — Buddhist Foreign Affairs of Laos CMS

ລະບົບ Content Management System (CMS) ສຳລັບ **ກັມມາທິການການຕ່າງປະເທດ ສູນກາງ ອພສ** ພັດທະນາດ້ວຍ Laravel 11 + React/Vite ຮອງຮັບ 3 ພາສາ (ລາວ, ອັງກິດ, ຈີນ) ພ້ອມ Admin Panel ສຳລັບຈັດການເນື້ອຫາທຸກໝວດໝູ່, ລະບົບ API, ກາຟ ແລະ ສົ່ງອອກ PDF.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11, PHP 8.4 |
| Database | MySQL 8 |
| API Auth | Laravel Sanctum (token-based) |
| Frontend Admin (Blade) | Tailwind CSS CDN, Alpine.js 3, Font Awesome 6 |
| Frontend SPA (React) | React + Vite, Tailwind CSS 4, Highcharts, Axios |
| PDF Export | mPDF (UTF-8 / ພາສາລາວ) |
| File Storage | Laravel Storage (`public` disk → `storage/app/public/`) |
| Media | Spatie Media Library |
| Logging | Spatie Activity Log |
| Server | XAMPP / Apache |

---

## Admin CRUD Status

| ໝວດ | ຕາລາງ | Admin CRUD | ໝາຍເຫດ |
|---|---|---|---|
| ເນື້ອຫາ | `news` | ✅ ພັດທະນາແລ້ວ | ຂ່າວສານ 3 ພາສາ, ຮູບປົກ, ສະຖານະ, ຂ່າວດ່ວນ, Featured, ໝວດ, Tag |
| ເນື້ອຫາ | `events` | ✅ ພັດທະນາແລ້ວ | ກິດຈະກຳ 3 ພາສາ, ສະຖານທີ, ລົງທະບຽນ, ງານສາກົນ |
| ເນື້ອຫາ | `pages` | ✅ ພັດທະນາແລ້ວ | ໜ້າ Static 3 ພາສາ, SEO meta, parent-child |
| ເນື້ອຫາ | `media_items` | ✅ ພັດທະນາແລ້ວ | ຮູບ, ວີດີໂອ, ສຽງ — upload ຫຼື embed YouTube/Facebook |
| ເນື້ອຫາ | `documents` | ✅ ພັດທະນາແລ້ວ | ເອກະສານດາວໂຫຼດ, ນັບຈຳນວນດາວໂຫຼດ |
| ເນື້ອຫາ | `categories` | ✅ ພັດທະນາແລ້ວ | ໝວດໝູ່ parent-child, ຮອງຮັບ news/event/media/document/mission |
| ເນື້ອຫາ | `tags` | ✅ ພັດທະນາແລ້ວ | Tag ຂ່າວ 3 ພາສາ |
| ເນື້ອຫາ | `event_tags` | ✅ ພັດທະນາແລ້ວ | Tag ກິດຈະກຳ 3 ພາສາ |
| ເນື້ອຫາ | `photo_albums` | ✅ ພັດທະນາແລ້ວ | ອາລ໌ບໍ້ຮູບ, ຈັດລຳດັບ, ຜູກກັບ Event |
| ເນື້ອຫາ | `translation_projects` | ✅ ພັດທະນາແລ້ວ | ໂຄງການແປຄຳພີ/ເອກະສານ ລາວ↔ EN↔ ZH |
| ອົງກອນ | `committee_members` | ✅ ພັດທະນາແລ້ວ | ຄະນະກຳມາທິການ, ຮູບ, ຕຳແໜ່ງ, ປະຫວັດ, ຂໍ້ມູນລາຍລະອຽດ |
| ອົງກອນ | `departments` | ✅ ພັດທະນາແລ້ວ | ພະແນກ/ໜ່ວຍງານ, ຮອງຮັບ parent-child, D3.js Org Chart |
| ພາລະກິດ | `partner_organizations` | ✅ ພັດທະນາແລ້ວ | ອົງກອນຮ່ວມມື, Logo, ປະເທດ, ຂໍ້ມູນຕິດຕໍ່ |
| ພາລະກິດ | `mou_agreements` | ✅ ພັດທະນາແລ້ວ | ສັນຍາ MOU, ເອກະສານ PDF, ວັນໝົດອາຍຸ |
| ພາລະກິດ | `monk_exchange_programs` | ✅ ພັດທະນາແລ້ວ | ໂຄງການແລກປ່ຽນພຣະ, quota, deadline |
| ພາລະກິດ | `monk_exchange_applications` | ✅ ພັດທະນາແລ້ວ | ໃບສະໝັກ, ສະຖານະ Review/ອະນຸມັດ |
| ພາລະກິດ | `aid_projects` | ✅ ພັດທະນາແລ້ວ | ໂຄງການຊ່ວຍເຫຼືອ, ງົບ USD, ລາຍງານ |
| ໜ້າຫຼັກ | `hero_slides` | ✅ ພັດທະນາແລ້ວ | Hero Slider ໜ້າຫຼັກ, 2 ປຸ່ມ CTA, 3 ພາສາ |
| ໜ້າຫຼັກ | `banners` | ✅ ພັດທະນາແລ້ວ | Banner sidebar/top/bottom/popup, ສີ, ລຳດັບ |
| ໜ້າຫຼັກ | `site_statistics` | ✅ ພັດທະນາແລ້ວ | ຕົວເລກສະຖິຕິ Counter Section |
| ລະບົບ | `navigation_menus` | ✅ ພັດທະນາແລ້ວ | ຈັດການ Menu ນຳທາງ 3 ພາສາ, parent-child |
| ລະບົບ | `users` | ✅ ພັດທະນາແລ້ວ | ຜູ້ໃຊ້ Role: viewer/editor/admin/superadmin |
| ລະບົບ | `site_settings` | ✅ ພັດທະນາແລ້ວ | ຕັ້ງຄ່າ key-value, cache |
| ລະບົບ | `contact_messages` | ✅ ພັດທະນາແລ້ວ | Inbox ຂໍ້ຄວາມຕິດຕໍ່, ໝາຍ Read, ລຶບ |

---

## Admin Panel Routes

```
GET /admin                     Dashboard (ສະຖິຕິ, ກາຟ, ຂ່າວລ່າສຸດ)
GET /admin/news                ລາຍການຂ່າວ (CRUD)
GET /admin/events              ລາຍການກິດຈະກຳ (CRUD)
GET /admin/pages               ໜ້າ Static (CRUD)
GET /admin/media               ສື່ ຮູບ/ວີດີໂອ (CRUD)
GET /admin/documents           ເອກະສານ (CRUD + ດາວໂຫຼດ)
GET /admin/albums              ອາລ໌ບໍ້ຮູບ (CRUD + ຈັດການຮູບ)
GET /admin/translations        ໂຄງການແປ (CRUD)
GET /admin/categories          ໝວດໝູ່ (CRUD)
GET /admin/tags                Tags (CRUD)
GET /admin/event_tags          Event Tags (CRUD)
GET /admin/partners            ອົງກອນຄູ່ຮ່ວມ (CRUD)
GET /admin/mou                 MOU (CRUD)
GET /admin/monk-programs       ໂຄງການແລກປ່ຽນພຣະ (CRUD)
GET /admin/monk-applications   ໃບສະໝັກແລກປ່ຽນ (ລາຍການ)
GET /admin/aid-projects        ໂຄງການຊ່ວຍເຫຼືອ (CRUD)
GET /admin/committee           ຄະນະກຳມາທິການ (CRUD — admin+)
GET /admin/departments         ໂຄງສ້າງພະແນກ (CRUD — admin+)
GET /admin/slides              Hero Slides (admin+)
GET /admin/banners             Banners (admin+)
GET /admin/navigation          Navigation Menu (admin+)
GET /admin/contacts            Inbox ຂໍ້ຄວາມ (ອ່ານ/ລຶບ)
GET /admin/users               ຜູ້ໃຊ້ລະບົບ (superadmin)
GET /admin/settings            ການຕັ້ງຄ່າ (superadmin)
GET /admin/activity-log        ປະຫວັດການໃຊ້ງານ
```

---

## API Routes (Laravel Sanctum)

### Public Endpoints (ບໍ່ຕ້ອງ Auth)

```
POST /api/login                      Login — ຮັບ Bearer Token
POST /api/public/contact             ສົ່ງຂໍ້ຄວາມຕິດຕໍ່
GET  /api/public/home                ຂໍ້ມູນໜ້າຫຼັກ (slides, stats, news, partners)
GET  /api/public/slides              Hero Carousel
GET  /api/public/stats               ສະຖິຕິ Counter Section
GET  /api/public/news                ຂ່າວ (pagination + filter)
GET  /api/public/news/{slug}         ລາຍລະອຽດຂ່າວ
GET  /api/public/events              ກິດຈະກຳ
GET  /api/public/partners            ອົງກອນຄູ່ຮ່ວມ
GET  /api/public/settings            ການຕັ້ງຄ່າເວັບ
GET  /api/public/menu                Navigation Menu
GET  /api/public/pages/{slug}        ໜ້າ Static
GET  /api/public/documents           ເອກະສານດາວໂຫຼດ
```

### Protected Endpoints (ຕ້ອງໃຊ້ Bearer Token)

```
GET    /api/user                     ຂໍ້ມູນ User ທີ Login
POST   /api/logout                   Logout
GET    /api/dashboard                Dashboard (ສະຖິຕິ, ຂ່າວລ່າສຸດ, ຂໍ້ຄວາມ)
GET    /api/dashboard/chart          ກາຟ Monthly (news/events 12 ເດືອນ)
GET    /api/statistics               ສະຖິຕິລວມ + ທ່າອ່ຽງ + Breakdown

GET|POST        /api/news            ລາຍການ / ສ້າງຂ່າວ
GET|PUT|DELETE  /api/news/{id}       ລາຍລະອຽດ / ແກ້ໄຂ / ລຶບ
PATCH           /api/news/{id}/status  ອັບເດດສະຖານະ

GET|POST        /api/events          ລາຍການ / ສ້າງ
GET|PUT|DELETE  /api/events/{id}     ລາຍລະອຽດ / ແກ້ໄຂ / ລຶບ
PATCH           /api/events/{id}/status

GET|POST        /api/partners        ອົງກອນຄູ່ຮ່ວມ
GET|PUT|DELETE  /api/partners/{id}
GET|POST        /api/mou             MOU
GET|PUT|DELETE  /api/mou/{id}
GET|POST        /api/committee       ຄະນະກຳມາທິການ (admin+)
GET|PUT|DELETE  /api/committee/{id}
GET|POST        /api/monk-programs   ໂຄງການແລກປ່ຽນ
GET|PUT|DELETE  /api/monk-programs/{id}
GET|POST        /api/aid-projects    ໂຄງການຊ່ວຍເຫຼືອ
GET|PUT|DELETE  /api/aid-projects/{id}

GET    /api/categories               ໝວດໝູ່ (read-only)
GET    /api/tags                     Tags (read-only)
GET    /api/contacts                 Inbox ຂໍ້ຄວາມ
PATCH  /api/contacts/{id}/read       ໝາຍ Read
DELETE /api/contacts/{id}            ລຶບ
```

### PDF Export Endpoints

```
GET /api/pdf/news         ລາຍງານຂ່າວ PDF
GET /api/pdf/events       ລາຍງານກິດຈະກຳ PDF
GET /api/pdf/partners     ລາຍງານອົງກອນຄູ່ຮ່ວມ PDF
GET /api/pdf/mou          ລາຍງານ MOU PDF
GET /api/pdf/committee    ລາຍງານຄະນະກຳມາທິການ PDF
```

---

## Frontend Routes (ໜ້າສາທາລະນະ)

```
GET /                      ໜ້າຫຼັກ — Hero Slider, ຂ່າວ, ຄູ່ຮ່ວມ, ສະຖິຕິ, Work Areas
GET /news                  ລາຍການຂ່າວ (Filter, Pagination)
GET /news/{slug}           ລາຍລະອຽດຂ່າວ
GET /events                ລາຍການກິດຈະກຳ
GET /events/{slug}         ລາຍລະອຽດກິດຈະກຳ
GET /media                 Gallery ສື່ (ຮູບ/ວີດີໂອ)
GET /documents             ຫ້ອງສະໝຸດເອກະສານ
GET /partners              ອົງກອນຄູ່ຮ່ວມ
GET /partners/{id}         ລາຍລະອຽດ Partner
GET /mou                   ລາຍການ MOU
GET /aid-projects          ໂຄງການຊ່ວຍເຫຼືອ
GET /monk-programs         ໂຄງການແລກປ່ຽນພຣະ
GET /committee             ຄະນະກຳມາທິການ
GET /structure             ໂຄງສ້າງອົງກອນ
GET /structure/d3          D3.js Interactive Org Chart
GET /gallery               ອາລ໌ບໍ້ຮູບ
GET /gallery/{album}       ລາຍລະອຽດ Album
GET /translations          ໂຄງການແປ
GET /page/{slug}           ໜ້າ Dynamic (about, contact, ...)
GET /search                ຄົ້ນຫາ Full-Text
GET /contact               ແບບຟອມຕິດຕໍ່
GET /lang/{locale}         ສ່ຽງພາສາ: lo / en / zh
```

---

## React SPA Frontend (`/frontend`)

```
Tech:    React (Vite), Tailwind CSS 4, Highcharts, Axios
Entry:   frontend/src/main.jsx
Config:  frontend/vite.config.js
API URL: http://localhost/bfol/public/api (axios baseURL)
```

| ໜ້າ | Component | ຄຳອະທິບາຍ |
|---|---|---|
| Login | `LoginPage` | Form login → Sanctum Token → localStorage |
| Dashboard | `DashboardPage` | ສະຖິຕິ counts, Recent News, Recent Messages |
| News | `NewsPage`, `NewsForm` | CRUD ຂ່າວ ພ້ອມ Filter ສະຖານະ |
| Events | `EventsPage`, `EventForm` | CRUD ກິດຈະກຳ |
| Partners | `PartnersPage`, `PartnerForm` | CRUD ອົງກອນ |
| MOU | `MouPage`, `MouForm` | CRUD MOU |
| Committee | `CommitteePage`, `CommitteeForm` | CRUD ຄະນະ |
| Monk Programs | `MonkProgramsPage`, `MonkProgramForm` | CRUD ໂຄງການ |
| Aid Projects | `AidProjectsPage`, `AidProjectForm` | CRUD ໂຄງການຊ່ວຍ |
| Charts | `ChartsPage` | Highcharts: ກາຟ Monthly News/Events (Bar+Line) |
| Statistics | `StatisticsPage` | ກາຟ Breakdown Pie/Bar ສຳລັບ Partners, MOU, Projects |
| PDF Export | ປຸ່ມ Download PDF | ດາວໂຫຼດ PDF ຈາກ `/api/pdf/*` |
| Contact Inbox | `ContactsPage` | ລາຍການຂໍ້ຄວາມ, ໝາຍ Read |

---

## ຟີຈເຈີ້ພິເສດ (Special Features)

### ຮອງຮັບ 3 ພາສາ (Multilingual)
- ທຸກ Model ທີ່ມີ content ໃຊ້ `HasTranslations` trait (`_lo` / `_en` / `_zh`)
- Language Switcher: `/lang/{locale}` — ບັນທຶກໃນ Session
- ທຸກ View ໃຊ້ `locale()` helper ເລືອກ field ໂດຍອັດຕະໂນມັດ

### PDF Export ດ້ວຍ mPDF
- ຮອງຮັບ Unicode / ອັກສອນລາວ ຄົບຖ້ວນ
- ຕາຕະລາງຂໍ້ມູນ + Status Badge ສີ
- 5 ລາຍງານ: ຂ່າວ, ກິດຈະກຳ, ຄູ່ຮ່ວມ, MOU, ຄະນະ

### ກາຟ (Highcharts ໃນ React)
- Monthly Bar Chart: ຈຳນວນຂ່າວ/ກິດຈະກຳ 12 ເດືອນ
- Pie/Bar Breakdown: ສະຖານະ MOU, ປະເພດ Partner, ສະຖານະ Projects
- ດຶງຂໍ້ມູນຈາກ API `/api/dashboard/chart` ແລະ `/api/statistics`

### ໂຄງສ້າງອົງກອນ D3.js
- Tree Layout ຕາມ Department Hierarchy
- Interactive Pan/Zoom
- Route `/structure/d3` (ໃຊ້ D3.js CDN)

### ລະບົບ Role-Based Access Control
| Role | ສິດ |
|---|---|
| `viewer` | ອ່ານ-ດຽວ (ທຸກ module) |
| `editor` | CRUD ເນື້ອຫາ (ຂ່າວ, ກິດຈະກຳ, ສື່, ເອກະສານ, Partners, MOU, ...)  |
| `admin` | Editor + ຈັດການ Committee, Departments, Slides, Banners, Navigation |
| `superadmin` | ທຸກ + ຈັດການ Users ແລະ Settings |

### Activity Log (Spatie)
- ບັນທຶກ Create/Update/Delete ທຸກ action
- ສະແດງໃນ `/admin/activity-log`

### ຄົ້ນຫາ Full-Text
- ຄົ້ນຫາຂ້າມ: ຂ່າວ, ກິດຈະກຳ, ໂຄງການ, ໜ້າ Static, ອົງກອນ
- Case-insensitive LIKE query, ຮອງຮັບ 3 ພາສາ

---

## ໂຄງສ້າງຖານຂໍ້ມູນ (Database Schema)

### ຕາລາງລະບົບ (System)

| ຕາລາງ | ຈຸດປະສົງ |
|---|---|
| `users` | ຜູ້ໃຊ້: role, avatar, is_active, last_login |
| `site_settings` | ຕັ້ງຄ່າ key-value (text/image/json/boolean/color) ຈັດກຸ່ມ |
| `contact_messages` | ຂໍ້ຄວາມຈາກ Form ຕິດຕໍ່ (read-only + delete) |
| `navigation_menus` | Menu ນຳທາງ parent-child 3 ພາສາ |
| `sessions` / `cache` / `jobs` | Laravel Framework |

### ຕາລາງເນື້ອຫາ (Content)

| ຕາລາງ | ສຳຄັນ |
|---|---|
| `categories` | parent_id hierarchy, type enum (news/event/media/document/mission) |
| `tags` | 3 ພາສາ, Pivot: `news_tags` |
| `event_tags` | Tag ສະເພາະກິດຈະກຳ |
| `news` | title/excerpt/content 3 ພາສາ, status, is_featured, is_urgent, view_count |
| `events` | title/description/location 3 ພາສາ, registration_url, max_participants, status, is_international |
| `pages` | slug, content HTML 3 ພາສາ, SEO meta, parent_slug |
| `media_items` | type (image/video/audio/document), platform (local/youtube/facebook/soundcloud) |
| `documents` | file_url, file_type, download_count |
| `photo_albums` | cover_image, event_id; child: `photo_album_images` (sort_order) |
| `translation_projects` | source/target_language, translator, year, status |

### ຕາລາງໜ້າຫຼັກ (Homepage)

| ຕາລາງ | ສຳຄັນ |
|---|---|
| `hero_slides` | title/subtitle/tag 3 ພາສາ, btn1/btn2 (text+url), sort_order |
| `banners` | style (6 ສີ), position (sidebar/top/bottom/popup/inline) |
| `site_statistics` | label 3 ພາສາ, value, icon, suffix (`+`/`%`) |

### ຕາລາງອົງກອນ (Organization)

| ຕາລາງ | ສຳຄັນ |
|---|---|
| `departments` | parent_id hierarchy, sort_order |
| `committee_members` | department_id, photo, bio, education, date_of_ordination, pansa, temple, province |

### ຕາລາງພາລະກິດ (Mission)

| ຕາລາງ | ສຳຄັນ |
|---|---|
| `partner_organizations` | acronym, country_code, logo_url, type (7 ປະເພດ), partnership_since |
| `mou_agreements` | partner_org_id, signed_date, expiry_date, document_url, status (5 ສະຖານະ) |
| `monk_exchange_programs` | monks_quota, monks_selected, application_deadline, status (6 ສະຖານະ) |
| `monk_exchange_applications` | program_id, status (pending/reviewing/approved/rejected/withdrawn) |
| `aid_projects` | type (religious/humanitarian/educational/cultural), budget_usd, report_url |
| `translation_projects` | source_language, target_language, translator, status |

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

### 3. Migrate & Seed & Storage
```bash
php artisan migrate
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

### 4. React Frontend (SPA)
```bash
cd frontend
npm install
npm run dev      # development
npm run build    # production build → dist/
```

### 5. ເລີ່ມ Server
```bash
# XAMPP: ວາງໂຄງການໃນ htdocs/ ແລ້ວ Start Apache + MySQL
# ຫຼື
php artisan serve
```

### 6. Admin Login
```
URL:  http://localhost/bfol/public/admin/login
API:  http://localhost/bfol/public/api
SPA:  http://localhost:5173  (Vite dev server)
```

---

## ໂຄງສ້າງໄຟລ໌ (Key Directories)

```
bfol/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          ← 24 Controllers ສຳລັບ Blade Admin Panel
│   │   ├── Api/            ← 16 Controllers ສຳລັບ REST API
│   │   └── Front/          ← 16 Controllers ສຳລັບ Public Frontend
│   ├── Models/             ← 25+ Eloquent Models
│   └── Traits/
│       └── HasTranslations.php  ← Helper ຫຼາຍພາສາ
│
├── frontend/               ← React SPA (Vite)
│   ├── src/
│   │   ├── pages/          ← Page Components
│   │   ├── components/     ← Reusable Components
│   │   └── api/            ← Axios API calls
│   └── vite.config.js
│
├── resources/views/
│   ├── admin/              ← Blade Admin Views (111+ files)
│   │   ├── layouts/app.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── news/ events/ pages/ media/ documents/
│   │   ├── albums/ translations/ categories/ tags/
│   │   ├── partners/ mou/ monk-programs/ aid-projects/
│   │   ├── committee/ departments/ slides/ banners/
│   │   ├── navigation/ contacts/ users/ settings/
│   │   └── activity-log/ monk-applications/
│   └── front/              ← Blade Public Views
│       ├── layouts/app.blade.php
│       ├── home/ news/ events/ media/ documents/
│       ├── partners/ mou/ aid-projects/ monk-programs/
│       ├── committee/ structure/ gallery/ translations/
│       ├── pages/ contact/ search/
│       └── _partials/ (navbar, footer, banner, topbar)
│
├── routes/
│   ├── api.php             ← API Routes (Sanctum)
│   └── web.php             ← Web Routes (Admin + Frontend)
│
└── storage/app/public/
    ├── news/               ← ຮູບຂ່າວ
    ├── slides/             ← ຮູບ Hero Slides
    ├── banners/            ← ຮູບ Banner
    ├── mou-documents/      ← ໄຟລ໌ MOU PDF
    ├── partners/           ← Logo ຄູ່ຮ່ວມ
    ├── documents/          ← ເອກະສານດາວໂຫຼດ
    ├── committee/          ← ຮູບຄະນະ
    └── albums/             ← ຮູບ Album
```

---

## ໝາຍເຫດ ການພັດທະນາ

- **Multilingual**: ທຸກ Model ທີ່ຮອງຮັບ `_lo/_en/_zh` fields ໃຊ້ `HasTranslations` trait
- **File Upload**: ໃຊ້ `Storage::disk('public')` — ຕ້ອງ run `php artisan storage:link` ກ່ອນ
- **Boolean Checkbox (Blade)**: ໃຊ້ `hidden value="0"` + `checkbox value="1"` ຮອງຮັບ unchecked state
- **Route Model Binding**: ທຸກ Controller ໃຊ້ type-hinted Model (`AidProject $aidProject`)
- **Pagination**: ທຸກ index ໃຊ້ `->paginate()->withQueryString()` ຮັກສາ filter state
- **API Auth**: ໃຊ້ Sanctum Bearer Token — `Authorization: Bearer {token}` header
- **PDF**: `mPDF` ທີ່ backend — frontend ດາວໂຫຼດ blob response
- **contact_messages**: `timestamps = false` — ມີພຽງ `created_at` (ບໍ່ມີ `updated_at`)
- **MOU constraint**: `partner_org_id` ໃຊ້ `RESTRICT` — ລຶບ Partner ທີ່ຜູກ MOU ຢູ່ບໍ່ໄດ້

---

## ຜູ້ພັດທະນາລະບົບ (Developer)

| ຊ່ອງທາງ | ລາຍລະອຽດ |
|---|---|
| Facebook | [facebook.com/phathasira](https://www.facebook.com/phathasira) |
| ໂທລະສັບ | [020 7777 2338](tel:02077772338) |
| WhatsApp | [020 9121 3388](https://wa.me/85620091213388) |

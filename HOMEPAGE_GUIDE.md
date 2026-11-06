# Homepage Management Guide

## Overview
Sistem homepage AMTPM ini memiliki fitur lengkap untuk mengelola konten landing page dengan animasi parallax, carousel, dan scroll-driven effects. Semua konten dapat dikelola oleh admin melalui panel admin.

## Features

### 1. Carousel Banner
- **Parallax Effect**: Gambar background bergerak dengan efek parallax saat scroll
- **Auto-play**: Slide berganti otomatis setiap 5 detik
- **Indicators**: Dot indicators untuk navigasi manual
- **Captions**: Teks caption yang dapat dikustomisasi posisinya (top, bottom, left, right)
- **Responsive**: Menyesuaikan dengan berbagai ukuran layar

### 2. Slider Rotate (4 Pillars AMTPM)
Menampilkan 4 pilar utama AMTPM dengan animasi rotate:
- **CLEANING** (Pembersihan)
- **TIGHTENING** (Pengencangan)
- **LUBRICATION** (Pelumasan)
- **INSPECTION** (Inspeksi)

Setiap pilar memiliki:
- Icon/gambar representatif
- Title dalam bahasa Inggris
- Subtitle dalam bahasa Indonesia
- Deskripsi detail

### 3. Flow Process Section
Menampilkan alur proses AMTPM dengan gambar dan deskripsi. Contoh flow:
- Perencanaan
- Pelaksanaan
- Pemantauan
- Evaluasi

### 4. Maintenance Data Section
Menampilkan data maintenance dengan filter:
- **Red Tag**: Masalah yang perlu perhatian segera
- **White Tag**: Masalah yang sedang dalam proses penyelesaian
- Filter dapat di-switch antara "Semua", "Red Tag", atau "White Tag"

### 5. Sections
Section dinamis untuk konten seperti:
- AMTPM Background (penjelasan tentang AMTPM)
- Maintenance Data (data maintenance)
- Dapat ditambah section lain sesuai kebutuhan

## Admin Panel Access

### Menu Navigation
Setelah login sebagai admin, menu "Homepage" akan muncul di navigation bar dengan sub-menu:
- **Carousel**: Kelola banner carousel
- **4 Pillars**: Kelola slider rotate (4 pilar AMTPM)
- **Flow Process**: Kelola flow process
- **Sections**: Kelola section konten

### Direct URLs
- Carousel: `/admin/homepage/carousel`
- Slider Rotate: `/admin/homepage/slider-rotate`
- Flow Process: `/admin/homepage/flow-process`
- Sections: `/admin/homepage/sections`

## Permissions

Pastikan user memiliki permission berikut untuk mengakses admin panel:
- `homepage.view` - Melihat homepage
- `homepage.carousel.manage` - Kelola carousel
- `homepage.slider.manage` - Kelola slider rotate
- `homepage.flow.manage` - Kelola flow process
- `homepage.sections.manage` - Kelola sections

Default admin role memiliki semua permissions ini.

## Image Upload Guidelines

### Carousel Images
- **Format**: JPEG, PNG, JPG, GIF
- **Max Size**: 2MB
- **Recommended Size**: 1920x1080px (16:9 aspect ratio)
- **Location**: `public/storage/homepage/carousel/`

### Slider Rotate Icons
- **Format**: JPEG, PNG, JPG, GIF, SVG
- **Max Size**: 2MB
- **Recommended Size**: 128x128px (square)
- **Location**: `public/storage/homepage/slider-rotate/`

### Flow Process Images
- **Format**: JPEG, PNG, JPG, GIF
- **Max Size**: 2MB
- **Recommended Size**: 800x600px (4:3 aspect ratio)
- **Location**: `public/storage/homepage/flow-process/`

### Section Background Images
- **Format**: JPEG, PNG, JPG, GIF
- **Max Size**: 2MB
- **Recommended Size**: 1920x1080px
- **Location**: `public/storage/homepage/sections/`

## How to Use

### 1. Managing Carousel
1. Buka menu: Homepage → Carousel
2. Klik "Add New" untuk menambah carousel item
3. Isi form:
   - **Title**: Judul banner (opsional)
   - **Description**: Deskripsi banner (opsional)
   - **Image**: Upload gambar banner (wajib)
   - **Order**: Urutan tampil (0, 1, 2, ...)
   - **Caption Position**: Posisi caption (bottom/top/left/right)
   - **Active**: Checkbox untuk mengaktifkan/menonaktifkan
4. Klik "Create Carousel Item"
5. Edit atau delete item menggunakan tombol di tabel

### 2. Managing 4 Pillars (Slider Rotate)
1. Buka menu: Homepage → 4 Pillars
2. Klik "Add New" untuk menambah pilar
3. Isi form:
   - **Title**: Judul dalam bahasa Inggris (wajib) - e.g., "CLEANING"
   - **Subtitle**: Judul dalam bahasa Indonesia (opsional) - e.g., "PEMBERSIHAN"
   - **Description**: Deskripsi detail (wajib)
   - **Icon**: Upload icon/gambar (opsional)
   - **Position**: Posisi (1-4) untuk menentukan urutan rotate
   - **Active**: Checkbox untuk mengaktifkan/menonaktifkan
4. Klik "Create Slider Rotate Item"
5. Pastikan position 1-4 terisi semua untuk tampilan optimal

### 3. Managing Flow Process
1. Buka menu: Homepage → Flow Process
2. Klik "Add New" untuk menambah flow process
3. Isi form:
   - **Title**: Judul step (wajib)
   - **Description**: Deskripsi step (opsional)
   - **Image**: Upload gambar (wajib)
   - **Order**: Urutan tampil (0, 1, 2, ...)
   - **Active**: Checkbox untuk mengaktifkan/menonaktifkan
4. Klik "Create Flow Process Item"

### 4. Managing Sections
1. Buka menu: Homepage → Sections
2. Klik "Add New" untuk menambah section
3. Isi form:
   - **Key**: Unique identifier (wajib) - e.g., "amtpm_background"
   - **Title**: Judul section (wajib)
   - **Content**: Konten HTML (opsional)
   - **Background Image**: Gambar background (opsional)
   - **Background Color**: Warna background (opsional) - e.g., "#f5f5f5"
   - **Text Color**: Warna teks (opsional) - e.g., "#333333"
   - **Order**: Urutan tampil (0, 1, 2, ...)
   - **Active**: Checkbox untuk mengaktifkan/menonaktifkan
4. Klik "Create Section"

## Default Data

Database sudah di-seed dengan data default:
- 3 carousel items
- 4 slider rotate items (4 pilar AMTPM)
- 4 flow process items
- 2 sections (amtpm_background, maintenance_data)

Silakan edit atau replace dengan data dan gambar yang sesuai.

## Customization Tips

### 1. Colors
Warna utama yang digunakan:
- Primary Red: `#ed2132`
- Background: `#f5f5f5` dan `#ffffff`
- Text: `#333333` dan `#666666`

Untuk mengubah warna, edit CSS di `welcome.blade.php`

### 2. Animation Speed
Default animation speeds:
- Carousel auto-play: 5000ms (5 detik)
- Slider rotate: 4000ms (4 detik)

Untuk mengubah, edit JavaScript di `welcome.blade.php`:
```javascript
setInterval(nextSlide, 5000); // carousel
setInterval(() => { ... }, 4000); // slider rotate
```

### 3. Responsive Breakpoints
Default breakpoints:
- Mobile: < 768px
- Desktop: ≥ 768px

Untuk mengubah, edit media queries di CSS.

## Troubleshooting

### Images not displaying
1. Pastikan storage link sudah aktif: `php artisan storage:link`
2. Cek permission folder `public/storage/homepage/`
3. Pastikan path gambar benar di database

### Carousel not rotating
1. Pastikan JavaScript tidak error (buka browser console)
2. Cek apakah ada carousel items yang active
3. Pastikan tidak ada conflict dengan script lain

### Permissions denied
1. Pastikan user memiliki role yang tepat
2. Cek permissions di database (spatie_permission tables)
3. Run seeder ulang jika perlu: `php artisan db:seed --force`

## Support

Untuk bantuan lebih lanjut, hubungi developer atau cek dokumentasi Laravel.

---

## Technical Details

### File Structure
```
resources/views/
├── welcome.blade.php              # Homepage template
├── admin/homepage/
│   ├── carousel/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── slider-rotate/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── flow-process/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── sections/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php

app/
├── Http/Controllers/
│   ├── HomepageController.php
│   └── Admin/HomepageAdminController.php
└── Models/
    ├── HomepageCarouselItem.php
    ├── HomepageSliderRotateItem.php
    ├── HomepageFlowProcessItem.php
    └── HomepageSection.php

database/
├── migrations/
│   ├── 2026_10_12_000001_create_homepage_carousel_items_table.php
│   ├── 2026_10_12_000002_create_homepage_slider_rotate_items_table.php
│   ├── 2026_10_12_000003_create_homepage_flow_process_items_table.php
│   └── 2026_10_12_000004_create_homepage_sections_table.php
└── seeders/
    └── HomepageSeeder.php
```

### Database Tables
- `homepage_carousel_items`
- `homepage_slider_rotate_items`
- `homepage_flow_process_items`
- `homepage_sections`

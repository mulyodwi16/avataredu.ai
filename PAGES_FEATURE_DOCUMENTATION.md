# Fitur Pages - Admin Panel

## Ringkasan
Telah berhasil menambahkan fitur **Pages** ke admin panel, di bawah menu **Manage Users**. Fitur ini memungkinkan Super Admin untuk membuat, mengedit, dan menghapus halaman/catatan dengan konten HTML/Markdown.

## File yang Dibuat/Dimodifikasi

### 1. **Migration** 
- **File**: `src/database/migrations/2025_10_23_000000_create_pages_table.php`
- **Tabel**: `pages` dengan kolom:
  - `id` - Primary key
  - `title` - Judul halaman
  - `slug` - URL slug (auto-generated dari title)
  - `content` - Konten halaman (support HTML/Markdown)
  - `status` - Status draft/published
  - `created_at`, `updated_at` - Timestamps

### 2. **Model**
- **File**: `src/app/Models/Page.php`
- **Features**:
  - Auto slug generation
  - Scopes: `published()`, `draft()`
  - Method: `isPublished()`

### 3. **Controller**
- **File**: `src/app/Http/Controllers/Admin/AdminPagesController.php`
- **Methods**: 
  - `index()` - List semua pages
  - `create()` - Form create
  - `store()` - Simpan page baru
  - `edit()` - Form edit
  - `update()` - Update page
  - `destroy()` - Hapus page

### 4. **Routes**
- **File**: `src/routes/web.php`
- **Routes ditambahkan**:
  ```
  GET    /admin/pages              - List pages
  POST   /admin/pages              - Simpan page baru
  GET    /admin/pages/create       - Form create
  GET    /admin/pages/{page}/edit  - Form edit
  PUT    /admin/pages/{page}       - Update page
  DELETE /admin/pages/{page}       - Hapus page
  ```

### 5. **Views**
- **File 1**: `src/resources/views/admin/partials/pages-content.blade.php`
  - Menampilkan list semua pages dalam tabel
  - Tombol Edit dan Delete untuk setiap page
  
- **File 2**: `src/resources/views/admin/pages/create-page.blade.php`
  - Form untuk membuat page baru
  - Fields: Title, Content (textarea), Status

- **File 3**: `src/resources/views/admin/pages/edit-page.blade.php`
  - Form untuk edit page
  - Menampilkan metadata (created_at, updated_at)

### 6. **API Methods** (di `AdminDashboardApiController`)
- `getPagesContent()` - Render list pages
- `getCreatePageContent()` - Render form create
- `getEditPageContent($pageId)` - Render form edit

### 7. **Layout Update**
- **File**: `src/resources/views/layouts/admin-dashboard.blade.php`
- **Changes**:
  - Tambah menu button "Pages" di sidebar
  - Update page titles object untuk 'pages'

## Cara Menggunakan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Akses Admin Panel
- Login sebagai Super Admin
- Klik menu **"Pages"** di sidebar (di bawah Manage Users)

### 3. Kelola Pages
- **Create**: Klik tombol "Create New Page"
  - Isi Title, Content, pilih Status (Draft/Published)
  - Klik "Create Page"
  
- **Edit**: Klik tombol "Edit" di baris yang ingin diubah
  - Ubah isi sesuai kebutuhan
  - Klik "Update Page"
  
- **Delete**: Klik tombol "Delete" 
  - Confirm deletion

## Fitur

✅ **Create** - Buat page baru dengan auto-generated slug dari title
✅ **Read** - Lihat list semua pages dengan status
✅ **Update** - Edit page dengan auto slug update jika title berubah
✅ **Delete** - Hapus page dengan konfirmasi
✅ **Status** - Draft/Published untuk setiap page
✅ **UI Integration** - Seamless integration dengan admin panel
✅ **Validation** - Validasi input menggunakan Laravel Validator
✅ **Error Handling** - Logging dan error messages yang informatif

## Catatan
- Menu "Pages" hanya visible untuk Super Admin (dilindungi oleh middleware `@if(auth()->user()->isSuperAdmin())`)
- Slug otomatis di-generate dari title, dengan fallback timestamp jika slug sudah ada
- Content field support HTML dan Markdown
- Semua operasi menggunakan AJAX untuk seamless single-page navigation

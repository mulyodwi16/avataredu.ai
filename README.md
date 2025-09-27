# 🚀 Avataredu.ai – Deployment Guide

Panduan ini untuk setup project **Avataredu.ai** menggunakan Docker (Laravel, Nginx, MySQL, Node, phpMyAdmin).

---

## 1. Clone Repository
Clone repo dari GitHub ke lokal/VPS:
```bash
git clone git@github.com:mulyodwi16/avataredu.ai.git
cd avataredu.ai

2. Tambahkan File Environment & Compose

Karena .env dan docker-compose.yml tidak disertakan dalam repo, buat file tersebut secara manual:

.env → isi konfigurasi Laravel, database, dan OAuth (gunakan credential masing-masing).

docker-compose.yml → definisikan service untuk app (PHP-FPM), web (Nginx), mysql, phpmyadmin, dan node.

⚠️ Catatan: jangan commit file .env dan docker-compose.yml ke GitHub, karena berisi informasi sensitif.

docker compose up -d --build

docker compose exec app composer install

docker compose exec app php artisan key:generate

docker compose exec app php artisan migrate

docker compose exec node npm run build

docker compose run --rm app sh -lc "rm -f public/hot && php artisan optimize:clear"

9. Selesai ✅

Akses aplikasi:

Dev/local: http://localhost:8080

Server/VPS: https://avataredu.ai

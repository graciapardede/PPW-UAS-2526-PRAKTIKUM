# Online Course Enrollment — 12S3101

Halaman 4 — Sistem pendaftaran kursus online sederhana (client-side)

Deskripsi
- Daftar kursus: nama kursus, durasi, biaya (ditampilkan sebagai card responsif)
- Form enrollment: nama, email, kursus dipilih
- Semua data peserta disimpan di localStorage
- Login sederhana: user login dengan username, setelah login user dapat melihat kursus yang dia ambil
- Bonus: CSS card layout responsif

Run lokal

1. Pastikan Node & npm terpasang.
2. Masuk ke folder proyek:

```powershell
cd d:/laragon/www/project1/linkset
```

3. Install dependency:

```powershell
npm install
```

4. Jalankan dev server:

```powershell
npm run dev
```

5. Buka `http://localhost:3000` dan coba fitur:
- Lihat daftar kursus (card grid)
- Isi form pendaftaran untuk menyimpan peserta ke localStorage
- Login (input username) lalu buka bagian "My Courses" untuk melihat kursus yang sudah didaftarkan oleh user

Storage
- Enrollments disimpan di key `linkset_enrollments` (JSON array)
- Username login disimpan di key `linkset_user`

Catatan pengembangan
- Aplikasi ini murni client-side (localStorage). Untuk menyimpan di server, perlu API + database.
- Login yang digunakan adalah demo/non-produktif (tanpa server). Jangan gunakan ini untuk produksi.
# LinkSet — URL Shortener

Simple Next.js + Prisma URL shortener project.

Requirements
- Node 18+ (recommended)
- PostgreSQL database

Quick start

1. Copy env example and update DATABASE_URL

   cp .env.example .env
   # edit .env and set DATABASE_URL

2. Install dependencies

   npm install

3. Generate Prisma client and run migration

   npx prisma generate
   npx prisma migrate dev --name init

4. (Optional) Seed sample data

   npx ts-node prisma/seed.ts

5. (Optional) NextAuth setup

   - Set env vars in `.env`:
     - NEXTAUTH_URL=http://localhost:3000
     - NEXTAUTH_SECRET=some_long_random_value
     - GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET (if using Google)

6. Run dev server

4. Run dev server

   npm run dev

Features
- Create short links with optional custom alias
- Redirect when visiting /:slug
- Click tracking and simple admin chart

Notes
- This is a scaffolded example. For production you should harden CORS, validation, rate-limiting and authentication.
- Optional: enable NextAuth using the NEXTAUTH_URL and Google credentials.

Troubleshooting
- If types/lint errors appear in the editor before installing deps, run `npm install` first.
- If `npx prisma migrate` fails with connection error, verify `DATABASE_URL` and that Postgres accepts connections.


# Droplets Dojo - System Design

## System Type
**Internal Multi-Branch Management System** (not SaaS multi-tenant)

Droplets Dojo adalah sistem manajemen internal untuk organisasi Taekwondo multi-cabang. Semua dojo merupakan bagian dari satu organisasi dan dikelola oleh Head Office.

## Architecture Overview

### Multi-Branch Structure
- Setiap dojo adalah **cabang/branch** dari satu organisasi
- Head Office (Super Admin) mengelola semua cabang
- Data diisolasi per dojo menggunakan `dojo_id`, tetapi Head Office memiliki akses penuh ke semua cabang

### Key Concepts

1. **Head Office (Super Admin)**
   - Akses penuh ke semua dojo/cabang
   - Tidak terikat pada dojo tertentu (dojo_id nullable)
   - Mengelola sistem secara keseluruhan

2. **Dojo/Branch**
   - Setiap cabang memiliki data terisolasi (dojo_id)
   - Dapat memiliki Owner, Finance, Coach, Students, Parents
   - Dikelola secara operasional oleh Owner, dikelola sistem oleh Head Office

3. **User Roles**
   - User dapat memiliki multiple roles di dojo berbeda
   - Role assignment tied to dojo_id
   - Super Admin tidak perlu role assignment per dojo

### Database Structure

- `dojos` - Master table untuk cabang/dojo
- `users` - dojo_id nullable (untuk Super Admin/Head Office)
- `user_roles` - Pivot table dengan dojo_id untuk role assignment per cabang
- Semua data entities memiliki `dojo_id` untuk isolasi data per cabang

### Permission Model

- Super Admin: Full access ke semua dojo
- Owner: Full operational control di dojo mereka
- Finance: Independent financial operations
- Coach: CRUD untuk data yang mereka kelola langsung
- Student/Parent: No DELETE permissions

### Data Isolation

- Semua queries di-scope dengan `dojo_id`
- Head Office dapat query across all dojos
- Middleware `EnsureDojoAccess` mengontrol akses per cabang


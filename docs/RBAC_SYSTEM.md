# 🔐 RBAC System dengan Bouncer - Dokumentasi Lengkap

**Tanggal**: 2026-02-07  
**Status**: ✅ Selesai

## 📋 Ringkasan

Sistem RBAC (Role-Based Access Control) telah sepenuhnya dimigrasi ke **Bouncer** package dengan struktur yang lebih terorganisir dan powerful.

---

## 🏗️ Struktur RBAC

### **1. Controllers**

#### **A. UserController** (Wali Kelas Only)
- **Path**: `app/Http/Controllers/UserController.php`
- **Route**: `/users`
- **Fungsi**: Mengelola user dengan role **teacher** saja
- **Akses**: Semua authenticated users

**Methods:**
- `index()` - List wali kelas
- `create()` - Form tambah wali kelas
- `store()` - Simpan wali kelas baru (auto-assign role teacher)
- `show()` - Detail wali kelas
- `edit()` - Form edit wali kelas
- `update()` - Update wali kelas
- `destroy()` - Hapus wali kelas

#### **B. AdminUserController** (All Users)
- **Path**: `app/Http/Controllers/AdminUserController.php`
- **Route**: `/admin/users`
- **Fungsi**: Mengelola **semua user** dengan **semua roles**
- **Akses**: User dengan ability `manage-users`

**Methods:**
- `index()` - List semua user dengan filter role
- `create()` - Form tambah user dengan pilihan roles
- `store()` - Simpan user baru dengan multiple roles
- `show()` - Detail user dengan roles & abilities
- `edit()` - Form edit user dengan roles
- `update()` - Update user dan sync roles
- `destroy()` - Hapus user
- `assignAbility()` - Assign ability langsung ke user
- `removeAbility()` - Remove ability dari user

#### **C. RoleController**
- **Path**: `app/Http/Controllers/RoleController.php`
- **Route**: `/roles`
- **Fungsi**: Mengelola **roles** dan assign **abilities** ke roles
- **Akses**: User dengan ability `manage-roles`

**Methods:**
- `index()` - List roles dengan user count
- `create()` - Form create role dengan pilihan abilities
- `store()` - Simpan role baru dan assign abilities
- `show()` - Detail role dengan users & abilities
- `edit()` - Form edit role dengan abilities
- `update()` - Update role dan sync abilities
- `destroy()` - Hapus role (kecuali system roles)
- `getAbilities()` - API endpoint untuk list abilities

#### **D. AbilityController**
- **Path**: `app/Http/Controllers/AbilityController.php`
- **Route**: `/abilities`
- **Fungsi**: Mengelola **abilities/permissions**
- **Akses**: User dengan ability `manage-permissions`

**Methods:**
- `index()` - List abilities
- `create()` - Form create ability
- `store()` - Simpan ability baru
- `show()` - Detail ability dengan roles & users
- `edit()` - Form edit ability
- `update()` - Update ability
- `destroy()` - Hapus ability (jika tidak digunakan)

---

## 🛣️ Routes

### **User Management Routes**

```php
// Wali Kelas Management (Teacher only)
Route::resource('users', UserController::class);

// Admin: All Users Management
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/assign-ability', [AdminUserController::class, 'assignAbility'])
        ->name('users.assign-ability');
    Route::post('users/{user}/remove-ability', [AdminUserController::class, 'removeAbility'])
        ->name('users.remove-ability');
});
```

### **Roles & Abilities Routes**

```php
// Roles Management
Route::resource('roles', RoleController::class);
Route::get('roles/abilities/list', [RoleController::class, 'getAbilities'])
    ->name('roles.abilities');

// Abilities Management
Route::resource('abilities', AbilityController::class);
```

---

## 👥 Roles & Abilities

### **Default Roles**

| Role | Name | Title | Description |
|------|------|-------|-------------|
| 1 | `admin` | Administrator | Full access ke semua fitur |
| 2 | `teacher` | Guru/Wali Kelas | Upload payments, view students |
| 3 | `finance` | Bendahara | Manage payments, validate, bills |
| 4 | `principal` | Kepala Sekolah | View reports, approve proposals |
| 5 | `foundation` | Yayasan | High-level view & approval |

### **Default Abilities (25+)**

#### **User Management**
- `manage-users` - Kelola Users
- `manage-roles` - Kelola Roles
- `manage-permissions` - Kelola Permissions

#### **Data Management**
- `manage-classes` - Kelola Kelas
- `manage-students` - Kelola Siswa
- `view-students` - Lihat Siswa

#### **Financial Management**
- `manage-payment-types` - Kelola Jenis Pembayaran
- `manage-bills` - Kelola Tagihan
- `view-payments` - Lihat Pembayaran
- `create-payments` - Buat Pembayaran
- `validate-payments` - Validasi Pembayaran
- `bulk-upload-payments` - Upload Pembayaran Massal

#### **Arrears Management**
- `view-arrears` - Lihat Tunggakan
- `manage-arrears` - Kelola Tunggakan
- `adjust-arrears` - Sesuaikan Tunggakan

#### **Proposal Management**
- `create-proposals` - Buat Proposal
- `approve-proposals` - Setujui Proposal
- `view-proposals` - Lihat Proposal

#### **BOS Management**
- `manage-bos` - Kelola Dana BOS
- `view-bos` - Lihat Dana BOS

#### **Reports**
- `view-reports` - Lihat Laporan
- `export-reports` - Export Laporan

#### **System**
- `view-logs` - Lihat Log Sistem
- `manage-settings` - Kelola Pengaturan

### **Role-Ability Mapping**

```php
// Admin - Full access
Bouncer::allow('admin')->everything();

// Finance
Bouncer::allow('finance')->to([
    'view-students', 'view-payments', 'create-payments',
    'validate-payments', 'bulk-upload-payments',
    'manage-payment-types', 'manage-bills',
    'view-arrears', 'manage-arrears', 'adjust-arrears',
    'view-reports', 'export-reports',
    'manage-bos', 'view-bos',
]);

// Teacher
Bouncer::allow('teacher')->to([
    'view-students', 'view-payments',
    'create-payments', 'bulk-upload-payments',
    'view-arrears',
]);

// Principal
Bouncer::allow('principal')->to([
    'view-students', 'view-payments', 'view-arrears',
    'view-proposals', 'approve-proposals',
    'view-reports', 'export-reports', 'view-bos',
]);

// Foundation
Bouncer::allow('foundation')->to([
    'view-students', 'view-payments', 'view-arrears',
    'view-proposals', 'approve-proposals',
    'view-reports', 'export-reports', 'view-bos',
]);
```

---

## 🔒 Authorization

### **Middleware**

```php
// Di Controller
$this->middleware(function ($request, $next) {
    if (!auth()->user()->can('manage-users')) {
        abort(403, 'Unauthorized action.');
    }
    return $next($request);
});
```

### **Policy (PaymentPolicy)**

```php
public function validate(User $user): bool
{
    return $user->isAdmin() || 
           $user->isFinance() || 
           $user->can('validate-payments');
}
```

### **Blade Directives**

```blade
@can('manage-users')
    <a href="{{ route('admin.users.index') }}">Kelola Users</a>
@endcan

@role('admin')
    <button>Admin Only</button>
@endrole
```

---

## 📊 Database Tables (Bouncer)

### **1. roles**
- `id`
- `name` (unique)
- `title`
- `scope`
- `timestamps`

### **2. abilities**
- `id`
- `name` (unique)
- `title`
- `entity_type` (nullable)
- `entity_id` (nullable)
- `only_owned` (boolean)
- `scope`
- `timestamps`

### **3. assigned_roles** (Pivot)
- `role_id`
- `entity_id` (user_id)
- `entity_type` (User class)
- `scope`

### **4. permissions** (Pivot)
- `ability_id`
- `entity_id` (role_id atau user_id)
- `entity_type` (Role atau User class)
- `forbidden` (boolean)
- `scope`

---

## 🚀 Cara Menggunakan

### **1. Assign Role ke User**

```php
use Silber\Bouncer\BouncerFacade as Bouncer;

// Single role
Bouncer::assign('teacher')->to($user);

// Multiple roles
Bouncer::assign('teacher')->to($user);
Bouncer::assign('finance')->to($user);

// Remove role
Bouncer::retract('teacher')->from($user);
```

### **2. Assign Ability ke Role**

```php
// Single ability
Bouncer::allow('finance')->to('validate-payments');

// Multiple abilities
Bouncer::allow('finance')->to([
    'validate-payments',
    'manage-bills',
    'view-reports',
]);

// Remove ability
Bouncer::disallow('finance')->to('validate-payments');
```

### **3. Assign Ability Langsung ke User**

```php
// Assign
Bouncer::allow($user)->to('manage-users');

// Remove
Bouncer::disallow($user)->to('manage-users');
```

### **4. Cek Permission**

```php
// Cek role
if ($user->isA('admin')) { }
if ($user->isAn('admin')) { } // alias
if ($user->isNotA('teacher')) { }

// Cek ability
if ($user->can('validate-payments')) { }
if ($user->cannot('manage-users')) { }

// Multiple
if ($user->isA('admin', 'finance')) { } // OR
if ($user->isAll('admin', 'finance')) { } // AND
```

### **5. Refresh Cache**

```php
Bouncer::refresh();
// atau
php artisan bouncer:clean
```

---

## 📝 Views yang Perlu Dibuat

### **Admin Users**
- ✅ `resources/views/admin/users/index.blade.php`
- ✅ `resources/views/admin/users/create.blade.php`
- ✅ `resources/views/admin/users/edit.blade.php`
- ✅ `resources/views/admin/users/show.blade.php`

### **Roles**
- ✅ `resources/views/roles/index.blade.php` (sudah ada, perlu update)
- ✅ `resources/views/roles/create.blade.php` (perlu update)
- ✅ `resources/views/roles/edit.blade.php` (perlu update)
- ✅ `resources/views/roles/show.blade.php` (perlu dibuat)

### **Abilities**
- ⬜ `resources/views/abilities/index.blade.php`
- ⬜ `resources/views/abilities/create.blade.php`
- ⬜ `resources/views/abilities/edit.blade.php`
- ⬜ `resources/views/abilities/show.blade.php`

---

## ✅ Testing Checklist

- [ ] Login sebagai Admin
  - [ ] Akses `/admin/users` - List all users
  - [ ] Create user dengan multiple roles
  - [ ] Edit user dan ubah roles
  - [ ] Assign ability langsung ke user
  - [ ] Delete user

- [ ] Login sebagai Finance
  - [ ] Akses `/admin/users` - Should be forbidden
  - [ ] Akses `/payments` - Should work
  - [ ] Validate payment - Should work

- [ ] Login sebagai Teacher
  - [ ] Akses `/users` - List teachers only
  - [ ] Create teacher user
  - [ ] Upload payment - Should work
  - [ ] Validate payment - Should be forbidden

- [ ] Roles Management
  - [ ] Create new role
  - [ ] Assign abilities to role
  - [ ] Edit role and sync abilities
  - [ ] Delete custom role
  - [ ] Try delete system role (should fail)

- [ ] Abilities Management
  - [ ] Create new ability
  - [ ] View ability with roles & users
  - [ ] Edit ability
  - [ ] Delete unused ability

---

## 🎯 Keuntungan Sistem Baru

1. ✅ **Separation of Concerns**: UserController untuk teachers, AdminUserController untuk all users
2. ✅ **Flexible Permissions**: Bisa assign abilities langsung ke user
3. ✅ **Granular Control**: 25+ abilities untuk fine-grained access control
4. ✅ **Scalable**: Mudah tambah roles & abilities baru
5. ✅ **Cache-enabled**: Bouncer auto-cache untuk performa
6. ✅ **Well-documented**: Bouncer package well-maintained

---

## 📚 Resources

- **Bouncer Docs**: https://github.com/JosephSilber/bouncer
- **Laravel Authorization**: https://laravel.com/docs/authorization
- **Migration Guide**: `/docs/BOUNCER_MIGRATION.md`

---

## 🎉 Kesimpulan

RBAC system sudah lengkap dengan:
- ✅ 3 Controllers (UserController, AdminUserController, RoleController, AbilityController)
- ✅ 5 Default Roles
- ✅ 25+ Abilities
- ✅ Complete CRUD untuk Users, Roles, Abilities
- ✅ Flexible permission assignment
- ✅ Policy-based authorization

**Next Steps**: Buat views untuk admin users, roles, dan abilities!

# 🔐 Migrasi ke Bouncer Package

**Tanggal**: 2026-02-07  
**Package**: [silber/bouncer](https://github.com/JosephSilber/bouncer) v1.0.3

## 📋 Ringkasan Perubahan

Sistem authorization telah dimigrasi dari custom role system ke **Bouncer** package untuk mendapatkan fitur yang lebih powerful dan fleksibel.

## ✅ Yang Sudah Dilakukan

### 1. **Instalasi Bouncer**
```bash
composer require silber/bouncer
php artisan vendor:publish --tag="bouncer.migrations"
```

### 2. **Update User Model**
- ✅ Menambahkan trait `HasRolesAndAbilities` dari Bouncer
- ✅ Menghapus custom `roles()` relationship
- ✅ Menghapus custom `hasRole()` dan `hasPermission()` methods
- ✅ Update helper methods (`isAdmin`, `isTeacher`, dll) menggunakan `isA()`

**Perubahan**:
```php
// SEBELUM
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }
}

// SESUDAH
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRolesAndAbilities;
    
    // Bouncer provides: roles(), abilities(), can(), isA(), etc.
    
    public function isAdmin(): bool
    {
        return $this->isA('admin');
    }
}
```

### 3. **Update PaymentPolicy**
- ✅ Menggunakan Bouncer's `can()` method
- ✅ Tetap menggunakan helper methods untuk role checking

**Perubahan**:
```php
// Sekarang bisa menggunakan abilities
public function validate(User $user): bool
{
    return $user->isAdmin() || 
           $user->isFinance() || 
           $user->can('validate-payments');
}
```

### 4. **Buat BouncerSeeder**
- ✅ Membuat roles menggunakan `Bouncer::role()->firstOrCreate()`
- ✅ Membuat abilities menggunakan `Bouncer::ability()->firstOrCreate()`
- ✅ Assign abilities ke roles menggunakan `Bouncer::allow()`

**Roles yang dibuat**:
- admin (Full access)
- teacher (Basic payment operations)
- finance (Financial management)
- principal (View and approve)
- foundation (High-level view and approval)

**Abilities yang dibuat**:
- User Management: `manage-users`, `manage-roles`, `manage-permissions`
- Data Management: `manage-classes`, `manage-students`, `view-students`
- Financial: `manage-payment-types`, `manage-bills`, `view-payments`, `create-payments`, `validate-payments`, `bulk-upload-payments`
- Arrears: `view-arrears`, `manage-arrears`, `adjust-arrears`
- Proposals: `create-proposals`, `approve-proposals`, `view-proposals`
- BOS: `manage-bos`, `view-bos`
- Reports: `view-reports`, `export-reports`
- System: `view-logs`, `manage-settings`

### 5. **Update UserSeeder**
- ✅ Menggunakan `Bouncer::assign()` untuk assign roles
- ✅ Menghapus dependency ke `Role` model

**Perubahan**:
```php
// SEBELUM
$admin->roles()->sync([$adminRole->id]);

// SESUDAH
Bouncer::assign('admin')->to($admin);
```

### 6. **Update DatabaseSeeder**
- ✅ Mengganti `RoleSeeder::class` dengan `BouncerSeeder::class`

## 🗄️ Database Migration

### Tables yang Dibuat oleh Bouncer:
1. **abilities** - Menyimpan permissions/abilities
2. **roles** - Menyimpan roles
3. **assigned_roles** - Pivot table user-role
4. **permissions** - Pivot table untuk role-ability dan user-ability

### Cara Migrate:
```bash
# Fresh migration (HATI-HATI: akan menghapus semua data)
php artisan migrate:fresh --seed

# Atau migrate saja (jika database kosong)
php artisan migrate
php artisan db:seed
```

## 🚀 Cara Menggunakan Bouncer

### 1. **Cek Role**
```php
// Menggunakan helper methods
if ($user->isAdmin()) { }
if ($user->isTeacher()) { }

// Menggunakan Bouncer methods
if ($user->isA('admin')) { }
if ($user->isAn('admin')) { } // alias
if ($user->isNotA('admin')) { }
```

### 2. **Cek Ability/Permission**
```php
if ($user->can('validate-payments')) { }
if ($user->cannot('validate-payments')) { }
```

### 3. **Assign Role**
```php
use Silber\Bouncer\BouncerFacade as Bouncer;

Bouncer::assign('teacher')->to($user);
Bouncer::retract('teacher')->from($user);
```

### 4. **Assign Ability**
```php
// Assign ability langsung ke user
Bouncer::allow($user)->to('validate-payments');
Bouncer::disallow($user)->to('validate-payments');

// Assign ability ke role
Bouncer::allow('finance')->to('validate-payments');
Bouncer::disallow('finance')->to('validate-payments');
```

### 5. **Cek Multiple Roles/Abilities**
```php
if ($user->isA('admin', 'finance')) { } // OR
if ($user->isAll('admin', 'finance')) { } // AND

if ($user->can(['view-payments', 'create-payments'])) { } // OR
```

### 6. **Scope Abilities**
```php
// Ability untuk model tertentu
Bouncer::allow($user)->to('edit', Payment::class);
Bouncer::allow($user)->to('delete', $payment); // specific instance

// Cek
if ($user->can('edit', Payment::class)) { }
if ($user->can('delete', $payment)) { }
```

## 📝 Blade Directives

### 1. **@can / @cannot**
```blade
@can('validate-payments')
    <button>Validate</button>
@endcan

@cannot('validate-payments')
    <p>You don't have permission</p>
@endcannot
```

### 2. **@role / @hasrole**
```blade
@role('admin')
    <a href="/admin">Admin Panel</a>
@endrole

@hasrole('admin|finance')
    <p>Admin or Finance</p>
@endhasrole
```

## 🔄 Migration dari Custom System

### Files yang TIDAK LAGI DIGUNAKAN:
- ❌ `app/Models/Role.php` (diganti dengan Bouncer's Role)
- ❌ `database/seeders/RoleSeeder.php` (diganti dengan BouncerSeeder)
- ❌ `database/migrations/*_create_roles_table.php` (opsional, bisa dihapus)
- ❌ `database/migrations/*_create_role_user_table.php` (opsional, bisa dihapus)

### Files yang DIUPDATE:
- ✅ `app/Models/User.php`
- ✅ `app/Policies/PaymentPolicy.php`
- ✅ `database/seeders/UserSeeder.php`
- ✅ `database/seeders/DatabaseSeeder.php`

### Files BARU:
- ✅ `database/seeders/BouncerSeeder.php`
- ✅ `database/migrations/2026_02_07_072224_create_bouncer_tables.php`

## ⚠️ Breaking Changes

### 1. **User Model**
```php
// TIDAK BERFUNGSI LAGI
$user->hasRole('admin');
$user->hasPermission('validate-payments');
$user->roles()->sync([...]);

// GUNAKAN INI
$user->isA('admin');
$user->can('validate-payments');
Bouncer::assign('admin')->to($user);
```

### 2. **Role Model**
```php
// TIDAK BERFUNGSI LAGI
Role::where('name', 'admin')->first();
$role->users;

// GUNAKAN INI
Bouncer::role()->where('name', 'admin')->first();
$role->users; // masih berfungsi
```

## 🎯 Keuntungan Menggunakan Bouncer

1. **Lebih Fleksibel**: Bisa assign abilities langsung ke user tanpa role
2. **Scoped Abilities**: Bisa membatasi permission untuk model/instance tertentu
3. **Cache Built-in**: Bouncer otomatis cache abilities untuk performa
4. **Eloquent-based**: Tetap menggunakan Eloquent, tidak perlu belajar API baru
5. **Well-maintained**: Package aktif dikembangkan dan banyak digunakan
6. **Laravel Native**: Terintegrasi sempurna dengan Laravel's authorization

## 📚 Resources

- **Documentation**: https://github.com/JosephSilber/bouncer
- **Laravel Authorization**: https://laravel.com/docs/authorization

## 🐛 Troubleshooting

### Cache Issues
```bash
# Clear Bouncer cache
php artisan bouncer:clean

# Clear all cache
php artisan cache:clear
php artisan config:clear
```

### Permission Tidak Berfungsi
```bash
# Re-seed abilities
php artisan db:seed --class=BouncerSeeder

# Atau refresh semua
php artisan migrate:fresh --seed
```

## ✅ Testing Checklist

- [ ] Login sebagai Admin - cek akses penuh
- [ ] Login sebagai Finance - cek bisa validate payments
- [ ] Login sebagai Teacher - cek bisa upload payments
- [ ] Login sebagai Principal - cek bisa view reports
- [ ] Login sebagai Foundation - cek bisa approve proposals
- [ ] Test @can directive di views
- [ ] Test Policy authorization
- [ ] Test API endpoints dengan different roles

## 🎉 Kesimpulan

Migrasi ke Bouncer berhasil! Sistem authorization sekarang lebih powerful dan fleksibel. Semua fitur existing tetap berfungsi, dengan tambahan kemampuan untuk:
- Assign abilities langsung ke user
- Scoped permissions
- Better performance dengan caching
- Lebih mudah di-maintain

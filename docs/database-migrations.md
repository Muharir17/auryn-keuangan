# 🗄️ Database Migrations Plan: SMP ASM Financial System

## 📋 Migration Execution Order

Migrations harus dijalankan sesuai urutan untuk menjaga referential integrity.

---

## 1️⃣ Authentication & Authorization Migrations

### Migration 1: Create Roles Table
**File**: `2024_01_01_000001_create_roles_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // teacher, finance, principal, foundation, admin
    $table->string('display_name');
    $table->text('description')->nullable();
    $table->json('permissions')->nullable(); // JSON array of permissions
    $table->timestamps();
});
```

**Seeder Data**:
- Teacher / Homeroom Teacher
- Finance / Treasurer
- Principal
- Foundation
- System Admin

---

### Migration 2: Create Role User Pivot Table
**File**: `2024_01_01_000002_create_role_user_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('role_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('role_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    $table->unique(['user_id', 'role_id']);
});
```

---

### Migration 3: Add Role Fields to Users Table
**File**: `2024_01_01_000003_add_role_fields_to_users_table.php`
**Status**: ⬜ Not Started

```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_active')->default(true)->after('password');
    $table->timestamp('last_login_at')->nullable()->after('is_active');
    $table->string('phone')->nullable()->after('email');
});
```

---

## 2️⃣ School Structure Migrations

### Migration 4: Create Classes Table
**File**: `2024_01_01_000004_create_classes_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('classes', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // 7A, 7B, 8A, etc.
    $table->integer('level'); // 7, 8, 9
    $table->string('academic_year'); // 2024/2025
    $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->onDelete('set null');
    $table->integer('student_count')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
    
    $table->unique(['name', 'academic_year']);
});
```

---

### Migration 5: Create Students Table
**File**: `2024_01_01_000005_create_students_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('students', function (Blueprint $table) {
    $table->id();
    $table->string('nis')->unique(); // Nomor Induk Siswa
    $table->string('nisn')->unique()->nullable(); // Nomor Induk Siswa Nasional
    $table->string('name');
    $table->foreignId('class_id')->constrained()->onDelete('restrict');
    $table->enum('gender', ['L', 'P']); // Laki-laki, Perempuan
    $table->date('birth_date')->nullable();
    $table->string('birth_place')->nullable();
    $table->text('address')->nullable();
    $table->string('parent_name')->nullable();
    $table->string('parent_phone')->nullable();
    $table->string('parent_email')->nullable();
    $table->date('enrollment_date')->nullable();
    $table->enum('status', ['active', 'graduated', 'transferred', 'dropped'])->default('active');
    $table->text('notes')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['class_id', 'status']);
});
```

---

## 3️⃣ Payment Structure Migrations

### Migration 6: Create Payment Types Table
**File**: `2024_01_01_000006_create_payment_types_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('payment_types', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique(); // SPP, BOOK, EXTRA, SPECIAL
    $table->string('name'); // SPP Bulanan, Buku, Ekstrakurikuler, Kegiatan Khusus
    $table->text('description')->nullable();
    $table->decimal('default_amount', 15, 2)->default(0);
    $table->enum('frequency', ['monthly', 'yearly', 'once', 'custom'])->default('monthly');
    $table->boolean('is_mandatory')->default(true);
    $table->boolean('is_active')->default(true);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    $table->softDeletes();
});
```

**Seeder Data**:
- SPP (Monthly tuition)
- BOOK (Books)
- EXTRA (Extracurricular)
- SPECIAL (Special activities)

---

### Migration 7: Create Bills Table
**File**: `2024_01_01_000007_create_bills_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('bills', function (Blueprint $table) {
    $table->id();
    $table->string('bill_number')->unique(); // AUTO: BILL-2024-01-00001
    $table->foreignId('student_id')->constrained()->onDelete('restrict');
    $table->foreignId('payment_type_id')->constrained()->onDelete('restrict');
    $table->integer('month')->nullable(); // 1-12 for monthly bills
    $table->integer('year'); // 2024
    $table->decimal('amount', 15, 2);
    $table->decimal('discount', 15, 2)->default(0);
    $table->decimal('final_amount', 15, 2); // amount - discount
    $table->date('due_date');
    $table->enum('status', ['unpaid', 'partial', 'paid', 'waived'])->default('unpaid');
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['student_id', 'year', 'month']);
    $table->index(['status', 'due_date']);
});
```

---

### Migration 8: Create Payments Table
**File**: `2024_01_01_000008_create_payments_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->string('payment_number')->unique(); // AUTO: PAY-2024-01-00001
    $table->foreignId('bill_id')->constrained()->onDelete('restrict');
    $table->foreignId('student_id')->constrained()->onDelete('restrict');
    $table->decimal('amount', 15, 2);
    $table->date('payment_date');
    $table->string('payment_method')->default('bank_transfer'); // bank_transfer, cash, other
    $table->string('bank_name')->nullable();
    $table->string('account_number')->nullable();
    $table->string('reference_number')->nullable();
    $table->enum('status', ['pending', 'validated', 'rejected', 'reversed'])->default('pending');
    $table->text('notes')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->foreignId('uploaded_by')->constrained('users');
    $table->foreignId('validated_by')->nullable()->constrained('users');
    $table->timestamp('validated_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['student_id', 'status']);
    $table->index(['bill_id', 'status']);
    $table->index(['payment_date']);
});
```

---

### Migration 9: Create Payment Slips Table
**File**: `2024_01_01_000009_create_payment_slips_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('payment_slips', function (Blueprint $table) {
    $table->id();
    $table->foreignId('payment_id')->constrained()->onDelete('cascade');
    $table->string('file_name');
    $table->string('file_path');
    $table->string('file_type'); // jpg, png, pdf
    $table->integer('file_size'); // in bytes
    $table->foreignId('uploaded_by')->constrained('users');
    $table->timestamps();
    $table->softDeletes();
});
```

---

## 4️⃣ Arrears Management Migrations

### Migration 10: Create Arrears Table
**File**: `2024_01_01_000010_create_arrears_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('arrears', function (Blueprint $table) {
    $table->id();
    $table->string('arrears_number')->unique(); // AUTO: ARR-2024-01-00001
    $table->foreignId('student_id')->constrained()->onDelete('restrict');
    $table->foreignId('bill_id')->constrained()->onDelete('restrict');
    $table->decimal('bill_amount', 15, 2);
    $table->decimal('paid_amount', 15, 2)->default(0);
    $table->decimal('arrears_amount', 15, 2); // bill_amount - paid_amount
    $table->date('due_date');
    $table->integer('days_overdue')->default(0);
    $table->enum('status', ['current', 'overdue_30', 'overdue_60', 'overdue_90', 'paid'])->default('current');
    $table->date('last_payment_date')->nullable();
    $table->timestamps();
    
    $table->index(['student_id', 'status']);
    $table->index(['due_date', 'status']);
    $table->unique(['bill_id']); // One arrears record per bill
});
```

---

### Migration 11: Create Arrears Adjustments Table
**File**: `2024_01_01_000011_create_arrears_adjustments_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('arrears_adjustments', function (Blueprint $table) {
    $table->id();
    $table->string('adjustment_number')->unique(); // AUTO: ADJ-2024-01-00001
    $table->foreignId('arrears_id')->constrained()->onDelete('restrict');
    $table->foreignId('student_id')->constrained()->onDelete('restrict');
    $table->enum('type', ['discount', 'postponement', 'waiver', 'correction']); // Diskon, Penundaan, Pembebasan, Koreksi
    $table->decimal('amount', 15, 2);
    $table->text('reason');
    $table->date('effective_date');
    $table->date('expiry_date')->nullable(); // For postponement
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->foreignId('requested_by')->constrained('users');
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->text('approval_notes')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['arrears_id', 'status']);
    $table->index(['student_id', 'type']);
});
```

---

## 5️⃣ Fund Proposal Migrations

### Migration 12: Create Proposals Table
**File**: `2024_01_01_000012_create_proposals_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('proposals', function (Blueprint $table) {
    $table->id();
    $table->string('proposal_number')->unique(); // AUTO: PROP-2024-01-00001
    $table->string('title');
    $table->text('description');
    $table->decimal('amount', 15, 2);
    $table->string('category'); // Operational, Event, Maintenance, etc.
    $table->date('needed_date')->nullable();
    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
    $table->enum('status', ['draft', 'submitted', 'principal_review', 'foundation_review', 'approved', 'rejected', 'disbursed'])->default('draft');
    $table->foreignId('requester_id')->constrained('users');
    $table->text('rejection_reason')->nullable();
    $table->date('disbursed_date')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['status', 'created_at']);
    $table->index(['requester_id', 'status']);
});
```

---

### Migration 13: Create Proposal Approvals Table
**File**: `2024_01_01_000013_create_proposal_approvals_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('proposal_approvals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
    $table->enum('level', ['principal', 'foundation']); // Multi-level approval
    $table->integer('sequence')->default(1); // 1 = Principal, 2 = Foundation
    $table->foreignId('approver_id')->nullable()->constrained('users');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('notes')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamps();
    
    $table->index(['proposal_id', 'level']);
    $table->index(['approver_id', 'status']);
});
```

---

### Migration 14: Create Proposal Attachments Table
**File**: `2024_01_01_000014_create_proposal_attachments_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('proposal_attachments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
    $table->string('file_name');
    $table->string('file_path');
    $table->string('file_type');
    $table->integer('file_size');
    $table->foreignId('uploaded_by')->constrained('users');
    $table->timestamps();
    $table->softDeletes();
});
```

---

## 6️⃣ BOS Fund Management Migrations

### Migration 15: Create Budgets Table
**File**: `2024_01_01_000015_create_budgets_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('budgets', function (Blueprint $table) {
    $table->id();
    $table->string('budget_number')->unique(); // AUTO: BDG-2024-00001
    $table->integer('year'); // 2024
    $table->string('category'); // Operational, Infrastructure, Teacher Training, etc.
    $table->text('description')->nullable();
    $table->decimal('allocated_amount', 15, 2);
    $table->decimal('realized_amount', 15, 2)->default(0);
    $table->decimal('remaining_amount', 15, 2); // allocated - realized
    $table->enum('status', ['draft', 'approved', 'active', 'closed'])->default('draft');
    $table->foreignId('created_by')->constrained('users');
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['year', 'category']);
    $table->index(['status']);
});
```

---

### Migration 16: Create BOS Transactions Table
**File**: `2024_01_01_000016_create_bos_transactions_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('bos_transactions', function (Blueprint $table) {
    $table->id();
    $table->string('transaction_number')->unique(); // AUTO: BOS-2024-01-00001
    $table->foreignId('budget_id')->constrained()->onDelete('restrict');
    $table->enum('type', ['income', 'expense']); // Pemasukan, Pengeluaran
    $table->decimal('amount', 15, 2);
    $table->date('transaction_date');
    $table->text('description');
    $table->string('reference_number')->nullable();
    $table->string('proof_file_path')->nullable(); // Bukti transaksi
    $table->foreignId('recorded_by')->constrained('users');
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['budget_id', 'type']);
    $table->index(['transaction_date']);
});
```

---

## 7️⃣ Notification & Audit Migrations

### Migration 17: Create Notifications Table
**File**: `2024_01_01_000017_create_notifications_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->string('type'); // PaymentUploaded, PaymentValidated, ProposalApproved, etc.
    $table->morphs('notifiable'); // User
    $table->text('data'); // JSON data
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
    
    $table->index(['notifiable_type', 'notifiable_id']);
});
```

---

### Migration 18: Create Audit Logs Table
**File**: `2024_01_01_000018_create_audit_logs_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    $table->string('event'); // created, updated, deleted, validated, approved, etc.
    $table->string('auditable_type'); // Model class name
    $table->unsignedBigInteger('auditable_id'); // Model ID
    $table->json('old_values')->nullable(); // Before change
    $table->json('new_values')->nullable(); // After change
    $table->text('description')->nullable();
    $table->string('ip_address')->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamps();
    
    $table->index(['auditable_type', 'auditable_id']);
    $table->index(['user_id', 'created_at']);
    $table->index(['event']);
});
```

---

## 8️⃣ System Configuration Migrations

### Migration 19: Create Settings Table
**File**: `2024_01_01_000019_create_settings_table.php`
**Status**: ⬜ Not Started

```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value')->nullable();
    $table->string('type')->default('string'); // string, integer, boolean, json
    $table->string('group')->default('general'); // general, payment, notification, etc.
    $table->text('description')->nullable();
    $table->timestamps();
});
```

**Seeder Data**:
- school_name
- school_address
- school_phone
- school_email
- current_academic_year
- default_spp_amount
- payment_due_day (tanggal jatuh tempo setiap bulan)
- arrears_reminder_days (berapa hari sebelum jatuh tempo kirim reminder)

---

## 📊 Migration Summary

| # | Table Name | Dependencies | Status |
|---|------------|--------------|--------|
| 1 | roles | - | ⬜ Not Started |
| 2 | role_user | users, roles | ⬜ Not Started |
| 3 | users (modify) | - | ⬜ Not Started |
| 4 | classes | users | ⬜ Not Started |
| 5 | students | classes | ⬜ Not Started |
| 6 | payment_types | - | ⬜ Not Started |
| 7 | bills | students, payment_types, users | ⬜ Not Started |
| 8 | payments | bills, students, users | ⬜ Not Started |
| 9 | payment_slips | payments, users | ⬜ Not Started |
| 10 | arrears | students, bills | ⬜ Not Started |
| 11 | arrears_adjustments | arrears, students, users | ⬜ Not Started |
| 12 | proposals | users | ⬜ Not Started |
| 13 | proposal_approvals | proposals, users | ⬜ Not Started |
| 14 | proposal_attachments | proposals, users | ⬜ Not Started |
| 15 | budgets | users | ⬜ Not Started |
| 16 | bos_transactions | budgets, users | ⬜ Not Started |
| 17 | notifications | - | ⬜ Not Started |
| 18 | audit_logs | users | ⬜ Not Started |
| 19 | settings | - | ⬜ Not Started |

**Total Migrations**: 19
**Completed**: 0
**Pending**: 19

---

## 🔄 Migration Execution Commands

```bash
# Create all migrations
php artisan make:migration create_roles_table
php artisan make:migration create_role_user_table
php artisan make:migration add_role_fields_to_users_table
php artisan make:migration create_classes_table
php artisan make:migration create_students_table
php artisan make:migration create_payment_types_table
php artisan make:migration create_bills_table
php artisan make:migration create_payments_table
php artisan make:migration create_payment_slips_table
php artisan make:migration create_arrears_table
php artisan make:migration create_arrears_adjustments_table
php artisan make:migration create_proposals_table
php artisan make:migration create_proposal_approvals_table
php artisan make:migration create_proposal_attachments_table
php artisan make:migration create_budgets_table
php artisan make:migration create_bos_transactions_table
php artisan make:migration create_notifications_table
php artisan make:migration create_audit_logs_table
php artisan make:migration create_settings_table

# Run all migrations
php artisan migrate

# Rollback if needed
php artisan migrate:rollback

# Fresh migration (drop all tables and re-run)
php artisan migrate:fresh

# Run seeders
php artisan db:seed
```

---

## 📝 Notes

- Semua tabel menggunakan `softDeletes()` untuk financial data
- Foreign keys menggunakan `onDelete('restrict')` untuk data keuangan
- Index ditambahkan pada kolom yang sering di-query
- Unique constraints untuk nomor-nomor dokumen
- Timestamps otomatis untuk audit trail

---

**Last Updated**: 2026-01-10
**Document Version**: 1.0

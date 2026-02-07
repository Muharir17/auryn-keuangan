# 🏗️ MVC Structure: SMP ASM Financial System

## 📋 Overview
Dokumen ini berisi struktur lengkap Models, Views, dan Controllers beserta Services, Repositories, dan komponen pendukung lainnya.

---

## 📁 Directory Structure

```
app/
├── Console/Commands/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Repositories/
├── Services/
└── Traits/

resources/views/
├── auth/
├── layouts/
├── dashboard/
├── students/
├── payments/
├── arrears/
├── proposals/
└── reports/
```

---

## 🎯 Models Summary

| # | Model Name | File | Status |
|---|------------|------|--------|
| 1 | User | app/Models/User.php | ✅ Completed |
| 2 | Role | app/Models/Role.php | ✅ Completed |
| 3 | ClassRoom | app/Models/ClassRoom.php | ✅ Completed |
| 4 | Student | app/Models/Student.php | ✅ Completed |
| 5 | PaymentType | app/Models/PaymentType.php | ✅ Completed |
| 6 | Bill | app/Models/Bill.php | ✅ Completed |
| 7 | Payment | app/Models/Payment.php | ⬜ Not Started |
| 8 | PaymentSlip | app/Models/PaymentSlip.php | ⬜ Not Started |
| 9 | Arrears | app/Models/Arrears.php | ⬜ Not Started |
| 10 | ArrearsAdjustment | app/Models/ArrearsAdjustment.php | ⬜ Not Started |
| 11 | Proposal | app/Models/Proposal.php | ⬜ Not Started |
| 12 | ProposalApproval | app/Models/ProposalApproval.php | ⬜ Not Started |
| 13 | ProposalAttachment | app/Models/ProposalAttachment.php | ⬜ Not Started |
| 14 | Budget | app/Models/Budget.php | ⬜ Not Started |
| 15 | BosTransaction | app/Models/BosTransaction.php | ⬜ Not Started |
| 16 | AuditLog | app/Models/AuditLog.php | ⬜ Not Started |
| 17 | Setting | app/Models/Setting.php | ⬜ Not Started |

**Total Models**: 17
**Completed**: 6/17 (35%)

---

## 🎮 Controllers Summary

| # | Controller Name | Location | Status |
|---|----------------|----------|--------|
| 1 | DashboardController | Admin/ | ✅ Completed |
| 2 | UserController | / | ✅ Completed |
| 3 | RoleController | Admin/ | ⬜ Not Started |
| 4 | SettingController | Admin/ | ⬜ Not Started |
| 5 | ClassController | / | ✅ Completed |
| 6 | StudentController | / | ✅ Completed |
| 7 | PaymentTypeController | / | ✅ Completed |
| 8 | BillController | / | ✅ Completed |
| 9 | PaymentController | Payment/ | ⬜ Not Started |
| 10 | ArrearsController | Arrears/ | ⬜ Not Started |
| 11 | ArrearsAdjustmentController | Arrears/ | ⬜ Not Started |
| 12 | ProposalController | Proposal/ | ⬜ Not Started |
| 13 | BudgetController | Bos/ | ⬜ Not Started |
| 14 | BosTransactionController | Bos/ | ⬜ Not Started |
| 15 | FinancialReportController | Report/ | ⬜ Not Started |
| 16 | PaymentReportController | Report/ | ⬜ Not Started |
| 17 | ArrearsReportController | Report/ | ⬜ Not Started |
| 18 | BosReportController | Report/ | ⬜ Not Started |

**Total Controllers**: 18
**Completed**: 7/18 (39%)

---

## 🔧 Services Summary

| # | Service Name | Purpose | Status |
|---|-------------|---------|--------|
| 1 | BillGenerationService | Auto-generate monthly bills | ⬜ Not Started |
| 2 | ArrearsCalculationService | Calculate arrears | ⬜ Not Started |
| 3 | PaymentValidationService | Validate payments | ⬜ Not Started |
| 4 | ProposalWorkflowService | Proposal approval workflow | ⬜ Not Started |
| 5 | NotificationService | Send notifications | ⬜ Not Started |
| 6 | ReportGenerationService | Generate reports | ⬜ Not Started |
| 7 | FileUploadService | Handle file uploads | ⬜ Not Started |

**Total Services**: 7

---

## 🎨 Views Summary

### Authentication Views
- login.blade.php ⬜ Not Started
- register.blade.php ⬜ Not Started
- forgot-password.blade.php ⬜ Not Started

### Dashboard Views
- teacher-dashboard.blade.php ⬜ Not Started
- finance-dashboard.blade.php ⬜ Not Started
- principal-dashboard.blade.php ⬜ Not Started
- foundation-dashboard.blade.php ⬜ Not Started
- admin-dashboard.blade.php ⬜ Not Started

### Student & Class Views
- classes/index.blade.php ✅ Completed
- classes/create.blade.php ✅ Completed
- classes/edit.blade.php ✅ Completed
- classes/show.blade.php ✅ Completed
- classes/students.blade.php ✅ Completed
- classes/payment-status.blade.php ✅ Completed
- students/index.blade.php ✅ Completed
- students/create.blade.php ✅ Completed
- students/edit.blade.php ✅ Completed
- students/show.blade.php ✅ Completed
- students/payment-history.blade.php ✅ Completed
- students/arrears-detail.blade.php ✅ Completed

### Payment Views
- payment-types/index.blade.php ✅ Completed
- payment-types/create.blade.php ✅ Completed
- payment-types/edit.blade.php ✅ Completed
- bills/index.blade.php ✅ Completed
- bills/create.blade.php ✅ Completed
- bills/edit.blade.php ✅ Completed
- bills/show.blade.php ✅ Completed
- payments/index.blade.php ⬜ Not Started
- payments/upload.blade.php ⬜ Not Started
- payments/validation-queue.blade.php ⬜ Not Started
- payments/history.blade.php ⬜ Not Started

### User Views
- users/index.blade.php ✅ Completed
- users/create.blade.php ✅ Completed
- users/edit.blade.php ✅ Completed
- users/show.blade.php ✅ Completed

**Total Views**: 35+
**Completed**: 19/35 (54%)

---

## 🛣️ Routes Summary

### Web Routes (web.php)

```php
// Authentication
Auth::routes();

// Dashboard (Role-based)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function() {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::get('settings', [SettingController::class, 'index']);
});

// Student & Class Management
Route::middleware(['auth'])->group(function() {
    Route::resource('classes', ClassController::class);
    Route::resource('students', StudentController::class);
});

// Payment Management
Route::middleware(['auth'])->prefix('payments')->group(function() {
    Route::resource('types', PaymentTypeController::class);
    Route::resource('bills', BillController::class);
    Route::resource('payments', PaymentController::class);
    Route::get('validation-queue', [PaymentController::class, 'validationQueue']);
    Route::post('{payment}/validate', [PaymentController::class, 'validate']);
});

// Arrears Management
Route::middleware(['auth'])->prefix('arrears')->group(function() {
    Route::get('/', [ArrearsController::class, 'index']);
    Route::get('by-student', [ArrearsController::class, 'byStudent']);
    Route::get('by-class', [ArrearsController::class, 'byClass']);
    Route::resource('adjustments', ArrearsAdjustmentController::class);
});

// Proposals
Route::middleware(['auth'])->prefix('proposals')->group(function() {
    Route::resource('proposals', ProposalController::class);
    Route::post('{proposal}/submit', [ProposalController::class, 'submit']);
    Route::post('{proposal}/approve', [ProposalController::class, 'approve']);
});

// BOS Management
Route::middleware(['auth'])->prefix('bos')->group(function() {
    Route::resource('budgets', BudgetController::class);
    Route::resource('transactions', BosTransactionController::class);
});

// Reports
Route::middleware(['auth'])->prefix('reports')->group(function() {
    Route::get('financial', [FinancialReportController::class, 'index']);
    Route::get('payment', [PaymentReportController::class, 'index']);
    Route::get('arrears', [ArrearsReportController::class, 'index']);
    Route::get('bos', [BosReportController::class, 'index']);
});
```

---

## 🔐 Middleware Summary

| # | Middleware Name | Purpose | Status |
|---|----------------|---------|--------|
| 1 | RoleMiddleware | Check user role | ⬜ Not Started |
| 2 | PermissionMiddleware | Check user permission | ⬜ Not Started |
| 3 | AuditLogMiddleware | Log user actions | ⬜ Not Started |

---

## 🧩 Traits Summary

| # | Trait Name | Purpose | Status |
|---|-----------|---------|--------|
| 1 | Auditable | Auto audit logging | ⬜ Not Started |
| 2 | HasRoles | Role management | ⬜ Not Started |
| 3 | GeneratesNumber | Auto number generation | ⬜ Not Started |

---

## 📝 Form Requests Summary

| # | Request Name | Purpose | Status |
|---|-------------|---------|--------|
| 1 | StoreStudentRequest | Validate student creation | ⬜ Not Started |
| 2 | StorePaymentRequest | Validate payment upload | ⬜ Not Started |
| 3 | ValidatePaymentRequest | Validate payment validation | ⬜ Not Started |
| 4 | StoreProposalRequest | Validate proposal creation | ⬜ Not Started |
| 5 | StoreBudgetRequest | Validate budget creation | ⬜ Not Started |

---

## 📊 Progress Summary

**Models**: 6/17 (35%)
**Controllers**: 7/18 (39%)
**Services**: 0/7 (0%)
**Views**: 19/35 (54%)
**Routes**: 1/1 (100%)
**Middleware**: 0/3 (0%)
**Traits**: 0/3 (0%)
**Form Requests**: 0/5 (0%)

**Overall Progress**: 35%

### ✅ Completed Components

**Phase 1 & 2 Foundation:**
- ✅ Authentication & RBAC system
- ✅ User management (AdminLTE integration)
- ✅ Class management (CRUD + views)
- ✅ Student management (CRUD + views)
- ✅ Payment types management (CRUD + views)
- ✅ Bills management (CRUD + views)
- ✅ Wali Kelas management (CRUD + views)
- ✅ All forms with Bootstrap horizontal layout
- ✅ Role-based menu system

### 🚧 Next Phase

**Phase 3: Payment Module**
- 🔄 Payment slip upload functionality
- 🔄 Payment validation workflow
- 🔄 Payment reporting

---

**Last Updated**: 2026-02-07
**Document Version**: 2.0
**Phase Completed**: Phase 2

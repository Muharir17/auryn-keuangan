🎯 ROLE

Act as a Senior Full-Stack Software Architect and Financial Systems Engineer with experience in education finance systems.

Your task is to design and build a complete, production-ready School Financial Management System for SMP Islam Terpadu (ASM).

🏫 BUSINESS CONTEXT
Student Payments Flow

Parents/students pay via bank

Receive payment slip

Submit slip to homeroom teacher

Teacher uploads slips per class

Finance validates payments

System generates financial recap

Fund Proposal Flow

Teacher submits fund request

Principal reviews and approves

Foundation gives final approval

Funds are disbursed and recorded

BOS Fund Management

Must be separated but integrated

Must support reporting and audits

👥 USER ROLES (MANDATORY)

Teacher / Homeroom Teacher

Finance / Treasurer

Principal

Foundation

System Admin

(Optional) Parent / Student

Use Role-Based Access Control (RBAC).

🧩 REQUIRED FEATURES (NO EXCEPTIONS)
1️⃣ Student Payment Module

Upload payment slips (image/PDF)

Payment validation by finance

Payment status: Pending / Valid / Rejected

Payment types:

Monthly tuition (SPP)

Books

Extracurricular

Special activities

Payment history per student

Payment recap per class

No hard delete (reversal/correction only)

2️⃣ Arrears Module (CRITICAL)
Per Student

Monthly arrears tracking

Arrears by payment type

Status: Paid / Partial / Overdue

Arrears history

Per Class

Total arrears per class

Class payment completion percentage

List of overdue students

Drill-down: Class → Student

Rules

Only validated payments affect arrears

Support:

Discounts

Postponement

Fee waivers

All changes must be audit-logged

3️⃣ Financial Dashboard

Total school income

Income by category

Monthly charts

Total outstanding arrears

Top overdue students

Class-level summaries

4️⃣ Reporting Module

Monthly & yearly financial reports

Student payment reports

Arrears reports:

Per student

Per class

BOS fund reports

Export to PDF & Excel

Audit-ready format

5️⃣ Fund Proposal Module

Teacher fund requests

Fields:

Amount

Category

Description

Attachments

Multi-level approval:

Principal

Foundation

Status tracking:

Draft

Pending

Approved

Rejected

Approval history

6️⃣ BOS Fund Management

Annual BOS budgeting

Budget allocation

Expense realization

Remaining balance

BOS-specific reports

Separate ledger, integrated system

7️⃣ Notifications

New payment slip uploaded

Payment rejected

New fund proposal

Proposal approved/rejected

Arrears reminders (Email/WhatsApp optional)

8️⃣ Security & Audit

RBAC enforcement

Audit logs for:

Payments

Corrections

Proposals

BOS transactions

Immutable financial records

User & timestamp tracking

🗃️ MINIMUM DATA MODELS

users, roles, permissions

students, classes

bills

payments

payment_slips

payment_types

arrears (calculated or materialized)

proposals

proposal_approvals

budgets

bos_transactions

audit_logs

🧠 BUSINESS RULES

Monthly tuition bills generated automatically

Only validated payments are counted

Arrears calculated automatically

No destructive delete on financial data

All adjustments must be logged

🧪 QUALITY REQUIREMENTS

Clean Architecture

Separation of Concerns

Strong validation

Clear error handling

Designed for long-term use (5+ years)

🛠️ TECH STACK (RECOMMENDED)

Backend: Laravel

Frontend: Admin Lte https://github.com/jeroennoten/Laravel-AdminLTE

Database: Mysql

Auth: Laravel UI

File storage: Local

Reporting: 
- PDF https://github.com/barryvdh/laravel-dompdf 
- Excel generators https://github.com/Maatwebsite/Laravel-Excel

📦 REQUIRED OUTPUTS

System architecture diagram

Complete ERD

Core process flowcharts

API design

Database schema

UI screen list & flow

Business logic pseudocode

Development roadmap (MVP → Production)

🚫 CONSTRAINTS

Do not omit arrears modules

Do not remove audit logging

Do not assume online payment integration

Do not build partial systems

✅ FINAL GOAL

Deliver a secure, transparent, and audit-ready School Financial Management System suitable for daily school operations and foundation oversight.

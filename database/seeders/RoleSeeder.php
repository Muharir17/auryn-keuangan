<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'System Administrator',
                'description' => 'Full system access and management',
                'permissions' => [
                    'manage_users',
                    'manage_roles',
                    'manage_settings',
                    'view_all_reports',
                    'manage_classes',
                    'manage_students',
                    'manage_payments',
                    'manage_arrears',
                    'manage_proposals',
                    'manage_bos',
                ],
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Teacher / Homeroom Teacher',
                'description' => 'Upload payment slips, view class data, submit proposals',
                'permissions' => [
                    'upload_payment_slips',
                    'view_class_data',
                    'view_student_data',
                    'submit_proposals',
                    'view_own_proposals',
                ],
            ],
            [
                'name' => 'finance',
                'display_name' => 'Finance / Treasurer',
                'description' => 'Validate payments, manage arrears, financial reports',
                'permissions' => [
                    'validate_payments',
                    'reject_payments',
                    'manage_arrears',
                    'manage_bills',
                    'view_financial_reports',
                    'export_reports',
                    'manage_payment_types',
                ],
            ],
            [
                'name' => 'principal',
                'display_name' => 'Principal',
                'description' => 'Approve proposals (level 1), view all reports',
                'permissions' => [
                    'approve_proposals_level1',
                    'reject_proposals',
                    'view_all_reports',
                    'view_dashboard',
                    'view_arrears',
                ],
            ],
            [
                'name' => 'foundation',
                'display_name' => 'Foundation',
                'description' => 'Final approval for proposals (level 2), oversight',
                'permissions' => [
                    'approve_proposals_level2',
                    'reject_proposals',
                    'view_all_reports',
                    'view_dashboard',
                    'approve_bos_budget',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}

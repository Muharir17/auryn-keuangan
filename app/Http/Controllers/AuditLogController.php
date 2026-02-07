<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        // Placeholder: Nanti bisa diintegrasikan dengan package audit log seperti spatie/laravel-activitylog
        $logs = collect([
            (object) [
                'id' => 1,
                'user' => 'Admin',
                'action' => 'Created',
                'model' => 'Payment',
                'description' => 'Created payment #123',
                'created_at' => now()->subHours(2),
            ],
            (object) [
                'id' => 2,
                'user' => 'Teacher',
                'action' => 'Updated',
                'model' => 'Student',
                'description' => 'Updated student data',
                'created_at' => now()->subHours(5),
            ],
        ]);

        return view('logs.index', compact('logs'));
    }
}

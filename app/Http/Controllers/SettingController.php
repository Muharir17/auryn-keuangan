<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        // Placeholder settings
        $settings = [
            'app_name' => 'SMP ASM Keuangan',
            'school_name' => 'SMP ASM',
            'school_address' => 'Jl. Contoh No. 123',
            'school_phone' => '021-12345678',
            'school_email' => 'info@smpasm.sch.id',
            'academic_year' => '2024/2025',
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        // Placeholder: Nanti bisa disimpan ke database atau config
        return redirect()->route('settings.index')
            ->with('success', 'Settings berhasil diupdate!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-roles');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')->latest()->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = new Role();
        return view('roles.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:roles,name',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('users');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Role tidak dapat dihapus karena masih memiliki user.');
        }

        // Prevent deletion of critical roles
        if (in_array($role->name, ['admin', 'teacher', 'finance', 'principal', 'foundation'])) {
            return back()->with('error', 'Role sistem tidak dapat dihapus.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    /**
     * Get available permissions for the application.
     */
    public function getPermissions()
    {
        $permissions = [
            // User Management
            'manage-users' => 'Kelola Users',
            'manage-roles' => 'Kelola Roles',
            'manage-permissions' => 'Kelola Permissions',
            'manage-wali-kelas' => 'Kelola Wali Kelas',
            
            // Data Management
            'manage-classes' => 'Kelola Kelas',
            'manage-students' => 'Kelola Siswa',
            
            // Financial Management
            'manage-payment-types' => 'Kelola Jenis Pembayaran',
            'manage-bills' => 'Kelola Tagihan',
            'view-payments' => 'Lihat Pembayaran',
            'validate-payments' => 'Validasi Pembayaran',
            
            // Reports
            'view-reports' => 'Lihat Laporan',
            'export-reports' => 'Export Laporan',
            
            // System
            'view-logs' => 'Lihat Log Sistem',
            'manage-settings' => 'Kelola Pengaturan',
        ];

        return response()->json($permissions);
    }
}

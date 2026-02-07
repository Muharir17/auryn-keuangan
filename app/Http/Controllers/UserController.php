<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('name')->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        $user = User::create($validated);

        // Assign teacher role
        $teacherRole = Role::where('name', 'teacher')->first();
        if ($teacherRole) {
            $user->roles()->attach($teacherRole->id);
        }

        return redirect()->route('users.index')
            ->with('success', 'Wali kelas berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'classes']);
        
        // Ensure user has teacher role
        if (!$user->hasRole('teacher')) {
            return redirect()->route('users.index')
                ->with('error', 'Data pengguna bukan wali kelas.');
        }

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Ensure user has teacher role
        if (!$user->hasRole('teacher')) {
            return redirect()->route('users.index')
                ->with('error', 'Data pengguna bukan wali kelas.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Ensure user has teacher role
        if (!$user->hasRole('teacher')) {
            return redirect()->route('users.index')
                ->with('error', 'Data pengguna bukan wali kelas.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Data wali kelas berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Ensure user has teacher role
        if (!$user->hasRole('teacher')) {
            return redirect()->route('users.index')
                ->with('error', 'Data pengguna bukan wali kelas.');
        }

        // Check if user is assigned as homeroom teacher to any class
        if ($user->classes()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus wali kelas yang masih terdaftar sebagai wali kelas.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Wali kelas berhasil dihapus.');
    }
}

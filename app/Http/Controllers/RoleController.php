<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Role;
use Silber\Bouncer\Database\Ability;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('manage-roles')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('abilities')->withCount('users')->latest()->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $abilities = Ability::all();
        return view('roles.create', compact('abilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:roles,name',
            'title' => 'required|string|max:100',
            'abilities' => 'array',
            'abilities.*' => 'exists:abilities,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role = Bouncer::role()->create([
            'name' => $request->name,
            'title' => $request->title,
        ]);

        // Assign abilities to role
        if ($request->has('abilities')) {
            $abilities = Ability::whereIn('id', $request->abilities)->get();
            foreach ($abilities as $ability) {
                Bouncer::allow($role)->to($ability->name);
            }
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('users', 'abilities');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $abilities = Ability::all();
        $roleAbilities = $role->abilities->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'abilities', 'roleAbilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'title' => 'required|string|max:100',
            'abilities' => 'array',
            'abilities.*' => 'exists:abilities,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role->update([
            'name' => $request->name,
            'title' => $request->title,
        ]);

        // Sync abilities
        // First, remove all existing abilities
        $currentAbilities = $role->abilities;
        foreach ($currentAbilities as $ability) {
            Bouncer::disallow($role)->to($ability->name);
        }

        // Then, assign new abilities
        if ($request->has('abilities')) {
            $abilities = Ability::whereIn('id', $request->abilities)->get();
            foreach ($abilities as $ability) {
                Bouncer::allow($role)->to($ability->name);
            }
        }

        // Refresh cache
        Bouncer::refresh();

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
     * Get available abilities for the application.
     */
    public function getAbilities()
    {
        $abilities = Ability::all()->pluck('title', 'name');
        return response()->json($abilities);
    }
}

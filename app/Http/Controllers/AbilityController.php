<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Silber\Bouncer\Database\Ability;

class AbilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('manage-permissions')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of abilities.
     */
    public function index()
    {
        $abilities = Ability::orderBy('name')->paginate(20);
        return view('abilities.index', compact('abilities'));
    }

    /**
     * Show the form for creating a new ability.
     */
    public function create()
    {
        return view('abilities.create');
    }

    /**
     * Store a newly created ability.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:abilities,name',
            'title' => 'required|string|max:255',
            'entity_type' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Ability::create([
            'name' => $request->name,
            'title' => $request->title,
            'entity_type' => $request->entity_type,
        ]);

        return redirect()->route('abilities.index')
            ->with('success', 'Ability berhasil dibuat.');
    }

    /**
     * Display the specified ability.
     */
    public function show(Ability $ability)
    {
        $ability->load('roles', 'users');
        return view('abilities.show', compact('ability'));
    }

    /**
     * Show the form for editing the specified ability.
     */
    public function edit(Ability $ability)
    {
        return view('abilities.edit', compact('ability'));
    }

    /**
     * Update the specified ability.
     */
    public function update(Request $request, Ability $ability)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:abilities,name,' . $ability->id,
            'title' => 'required|string|max:255',
            'entity_type' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ability->update([
            'name' => $request->name,
            'title' => $request->title,
            'entity_type' => $request->entity_type,
        ]);

        return redirect()->route('abilities.index')
            ->with('success', 'Ability berhasil diperbarui.');
    }

    /**
     * Remove the specified ability.
     */
    public function destroy(Ability $ability)
    {
        // Check if ability is assigned to any roles or users
        if ($ability->roles()->count() > 0 || $ability->users()->count() > 0) {
            return back()->with('error', 'Ability tidak dapat dihapus karena masih digunakan.');
        }

        $ability->delete();

        return redirect()->route('abilities.index')
            ->with('success', 'Ability berhasil dihapus.');
    }
}

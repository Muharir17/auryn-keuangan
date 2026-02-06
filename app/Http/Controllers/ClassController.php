<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::with('homeroomTeacher')
            ->withCount('students')
            ->orderBy('level')
            ->orderBy('name')
            ->paginate(10);

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        return view('classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:7|max:9',
            'academic_year' => 'required|string',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ClassRoom::create($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(ClassRoom $class)
    {
        $class->load(['homeroomTeacher', 'students']);

        return view('classes.show', compact('class'));
    }

    public function edit(ClassRoom $class)
    {
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        return view('classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:7|max:9',
            'academic_year' => 'required|string',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $class->update($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $class)
    {
        if ($class->students()->count() > 0) {
            return redirect()->route('classes.index')
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    public function students(ClassRoom $class)
    {
        $students = $class->students()->paginate(20);

        return view('classes.students', compact('class', 'students'));
    }

    public function paymentStatus(ClassRoom $class)
    {
        $students = $class->students()
            ->with(['bills' => function ($query) {
                $query->where('year', date('Y'));
            }])
            ->get();

        return view('classes.payment-status', compact('class', 'students'));
    }
}

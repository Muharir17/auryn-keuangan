<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassPromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-classes');
    }

    public function index()
    {
        $classes = ClassRoom::active()->orderBy('name')->get();
        return view('classes.promotion', compact('classes'));
    }

    public function students(ClassRoom $class)
    {
        $students = $class->students()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json($students);
    }

    public function process(Request $request)
    {
        $request->validate([
            'source_class_id' => 'required|exists:classes,id',
            'action' => 'required|in:promote,graduate',
            'target_class_id' => 'required_if:action,promote|nullable|exists:classes,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        DB::beginTransaction();
        try {
            $sourceClass = ClassRoom::findOrFail($request->source_class_id);
            $students = Student::whereIn('id', $request->student_ids)->get();
            $count = 0;

            if ($request->action === 'promote') {
                $targetClass = ClassRoom::findOrFail($request->target_class_id);

                foreach ($students as $student) {
                    // Update class and maintain active status
                    $student->update([
                        'class_id' => $targetClass->id,
                        'status' => 'active',
                    ]);

                    // Log history if needed (skipping for simplicity now)
                    $count++;
                }

                $message = "Berhasil menaikkan {$count} siswa dari {$sourceClass->name} ke {$targetClass->name}.";

            } elseif ($request->action === 'graduate') {
                $gradYear = date('Y');
                foreach ($students as $student) {
                    $student->update([
                        'class_id' => null, // Remove from class
                        'status' => 'graduated', // Set status to graduated
                        'notes' => "Lulus Tahun Ajaran $gradYear", // Add note
                    ]);
                    $count++;
                }

                $message = "Berhasil meluluskan {$count} siswa.";
            }

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

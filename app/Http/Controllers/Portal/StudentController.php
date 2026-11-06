<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Student records in both portals (/admin/students and /tech/students): each student with their latest cover page
 * and how many times they've printed again. Deleting a student deletes their print history too.
 */
class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::with(['department', 'latestPrint'])->withCount('prints')->latest('updated_at')->get()
            ->map(fn (Student $student): array => [
                'id' => $student->id,
                'name' => $student->name,
                'tu_roll' => $student->tu_roll,
                'std_dept' => $student->department?->dept_name ?? '—',
                'sem' => $student->semester,
                'section' => $student->section ?? '—',
                'subjects' => $student->latestPrint?->subject ?? '—',
                'code' => $student->latestPrint?->subject_code ?? '—',
                'f_name' => $student->latestPrint?->teacher ?? '—',
                'reprint' => max($student->prints_count - 1, 0),
                'dos' => ($student->latestPrint?->created_at ?? $student->updated_at)->toDateString(),
            ])
            ->all();

        return view('portal.students', [
            'portal' => $request->user()->role->value,
            'students' => $students,
            'departments' => Department::orderBy('dept_name')->get(['dept_name']),
        ]);
    }

    public function destroy(Request $request, Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route($request->user()->role->value.'.students')->with('status', "{$student->name}’s record deleted.");
    }
}

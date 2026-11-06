<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CoverPrint;
use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * The dashboard in both portals: students, teachers and subjects per department, and the cover pages printed today
 * (newest first).
 */
class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $departments = Department::withCount(['students', 'teachers', 'subjects'])->orderBy('dept_name')->get()
            ->map(fn (Department $department): array => [
                'dept_name' => $department->dept_name,
                'students' => $department->students_count,
                'teachers' => $department->teachers_count,
                'subjects' => $department->subjects_count,
            ])
            ->all();

        $todayPrints = CoverPrint::with('student')
            ->where('created_at', '>=', today())
            ->latest()
            ->latest('id')
            ->get()
            ->map(fn (CoverPrint $print): array => [
                'time' => $print->created_at->format('g:i a'),
                'tu_roll' => $print->student->tu_roll,
                'name' => $print->student->name,
                'subject' => $print->subject ?? '—',
                'page_type' => $print->page_type ?? '—',
            ])
            ->all();

        return view('portal.dashboard', [
            'portal' => $request->user()->role->value,
            'departments' => $departments,
            'todayPrints' => $todayPrints,
        ]);
    }
}

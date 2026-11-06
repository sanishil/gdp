<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Subject;
use Illuminate\Database\Seeder;

/**
 * Loads one branch's subjects: the first-year subjects common to all branches (semesters I and II, from the 2020-21
 * first-year curriculum) plus the branch's own semesters III to VI. Each subclass names the department and lists its
 * own subjects by semester. Elective options are separate subjects so students pick the one they take; open electives (offered by
 * other departments) aren't listed.
 *
 * Safe to run again: subjects are matched by department and code and updated in place, and the department is created if missing.
 */
abstract class DepartmentSubjectSeeder extends Seeder
{
    public const DEPARTMENT = '';

    /**
     * @var array<int, array<string, string>> semester => [code => name]
     */
    public const SUBJECTS = [];

    /**
     * Common to all branches, so every branch gets its own copy and can edit it independently.
     *
     * @var array<int, array<string, string>> semester => [code => name]
     */
    public const FIRST_YEAR = [
        1 => [
            'BS101' => 'Mathematics-I',
            'BS102' => 'Applied Physics-I',
            'BS103' => 'Applied Chemistry',
            'HS104' => 'Communication Skills in English',
            'ES105' => 'Engineering Graphics',
            'ES106' => 'Engineering Workshop Practice',
            'BS107' => 'Applied Physics-I Lab',
            'BS108' => 'Applied Chemistry Lab',
            'HS109' => 'Sports & Yoga',
            'HS110' => 'Communication Skills in English Lab',
        ],
        2 => [
            'BS201' => 'Mathematics-II',
            'BS202' => 'Applied Physics-II',
            'ES203' => 'Introduction to IT Systems',
            'ES204' => 'Fundamentals of Electrical & Electronics Engineering',
            'ES205' => 'Engineering Mechanics',
            'BS206' => 'Applied Physics-II Lab',
            'ES207' => 'Introduction to IT Systems Lab',
            'ES208' => 'Fundamentals of Electrical & Electronics Engineering Lab',
            'ES209' => 'Engineering Mechanics Lab',
            'AU210' => 'Environmental Science',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = Department::firstOrCreate(['dept_name' => static::DEPARTMENT]);

        foreach (static::FIRST_YEAR + static::SUBJECTS as $semester => $subjects) {
            foreach ($subjects as $code => $name) {
                Subject::updateOrCreate(
                    ['department_id' => $department->id, 'code' => $code],
                    ['semester' => $semester, 'name' => $name],
                );
            }
        }
    }
}

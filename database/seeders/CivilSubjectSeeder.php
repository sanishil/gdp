<?php

namespace Database\Seeders;

/**
 * Civil Engineering subjects, semesters III to VI, from the DCE 2020 syllabus. Elective options keep the
 * syllabus's letter codes (e.g. CEPE-407/A, /B, /C).
 */
class CivilSubjectSeeder extends DepartmentSubjectSeeder
{
    public const DEPARTMENT = 'Civil Engineering';

    /**
     * @var array<int, array<string, string>> semester => [code => name]
     */
    public const SUBJECTS = [
        3 => [
            'CEPC-301' => 'Building Materials and Construction',
            'CEPC-302' => 'Surveying',
            'CEPC-303' => 'Concrete Technology',
            'CEPC-304' => 'Mechanics of Materials & Theory of Structure',
            'CEPC-305' => 'Geotechnical Engineering',
            'CEPC-306' => 'Construction Materials & Mechanics of Materials Lab',
            'CEPC-307' => 'Surveying Lab',
            'CEPC-308' => 'Concrete Technology Lab',
            'CEPC-309' => 'Geotechnical Engineering Lab',
            'CESI-310' => 'Summer Internship-I',
        ],
        4 => [
            'CEPC-401' => 'Hydraulics',
            'CEPC-402' => 'Water Resource Engineering',
            'CEPC-403' => 'Transportation Engineering',
            'CEPC-404' => 'Hydraulics and Water Resource Engineering Lab',
            'CEPC-405' => 'Transportation Engineering Lab',
            'CEPC-406' => 'Building Planning and Drawing Lab',
            'CEPE-407/A' => 'Construction Management',
            'CEPE-407/B' => 'Rural Construction Technology',
            'CEPE-407/C' => 'Building Services and Maintenance',
            'HS408' => 'Professional Skill Development',
            'CEPR-409' => 'Minor Project',
            'AU-410' => 'Essence of Indian Knowledge and Tradition',
        ],
        5 => [
            'CEPC-501' => 'Design of RCC and Steel Structure',
            'CEPC-502' => 'Estimating, Costing and Valuation',
            'CEPC-503' => 'Design of RCC and Steel Structure Lab',
            'CEPC-504' => 'Estimating, Costing and Valuation Lab',
            'CEPC-505' => 'AutoCAD Lab',
            'CEPE-506/A' => 'Traffic Engineering',
            'CEPE-506/B' => 'Solid Waste Management',
            'CEPE-506/C' => 'Advanced Construction Technology',
            'CEPE-507/A' => 'Pavement Design & Maintenance',
            'CEPE-507/B' => 'Green Building and Energy Conservation',
            'CEPE-507/C' => 'Precast & Pre-stressed Concrete',
            'CESI-509' => 'Summer Internship-II',
            'CEPR-510' => 'Major Project-I',
        ],
        6 => [
            'CEPC-601' => 'Public Health Engineering',
            'CEPC-602' => 'Public Health Engineering Lab',
            'CEPE-603/A' => 'Airport Engineering',
            'CEPE-603/B' => 'Foundation Engineering',
            'CEPE-603/C' => 'Advanced Design of Structures',
            'HS604' => 'Entrepreneurship and Start-ups',
            'AU-606' => 'Indian Constitution',
            'CEPR-607' => 'Major Project-II',
            'CESE-608' => 'Seminar',
        ],
    ];
}

<?php

namespace Database\Seeders;

/**
 * Electrical Engineering subjects, semesters III to VI, from the DEE 2020 syllabus. Elective options keep the
 * syllabus's letter codes (e.g. EEPE-407/A, /B, /C).
 */
class EeSubjectSeeder extends DepartmentSubjectSeeder
{
    public const DEPARTMENT = 'Electrical Engineering';

    /**
     * @var array<int, array<string, string>> semester => [code => name]
     */
    public const SUBJECTS = [
        3 => [
            'EEPC-301' => 'Introduction to Electric Generation Systems',
            'EEPC-302' => 'Introduction to Electric Generation Systems Laboratory',
            'EEPC-303' => 'Electrical Circuits',
            'EEPC-304' => 'Electrical Circuits Laboratory',
            'EEPC-305' => 'Electrical and Electronic Measurements',
            'EEPC-306' => 'Electrical and Electronic Measurements Laboratory',
            'EEPC-307' => 'Electric Motors and Transformers',
            'EEPC-308' => 'Electric Motors and Transformers Laboratory',
            'EEPC-309' => 'Fundamental of Analog & Digital Electronics',
            'EESI-310' => 'Summer Internship-I',
        ],
        4 => [
            'EEPC-401' => 'Fundamentals of Power Electronics',
            'EEPC-402' => 'Fundamentals of Power Electronics Laboratory',
            'EEPC-403' => 'Electric Power Transmission and Distribution',
            'EEPC-404' => 'Electric Power Transmission and Distribution Laboratory',
            'EEPC-405' => 'Induction, Synchronous and Special Electrical Machines',
            'EEPC-406' => 'Induction, Synchronous and Special Electrical Machines Laboratory',
            'EEPE-407/A' => 'Electrical Estimation and Contracting',
            'EEPE-407/B' => 'Illumination Practices',
            'EEPE-407/C' => 'Electrical Testing and Commissioning',
            'HS-408' => 'Professional Skill Development',
            'PR-401' => 'Minor Project',
            'AU-402' => 'Essence of Indian Knowledge and Tradition',
        ],
        5 => [
            'EEPC-501' => 'Microprocessor and its Application',
            'EEPC-502' => 'Microprocessor and its Application Lab',
            'EEPC-503' => 'Energy Conservation and Audit',
            'EEPC-504' => 'Energy Conservation and Audit Laboratory',
            'EEPC-505' => 'Renewable Energy Power Plants',
            'EEPE-506/A' => 'Industrial Instrumentation and Condition Monitoring',
            'EEPE-506/B' => 'Industrial Automation & Control',
            'EEPE-506/C' => 'Switchgear and Protection',
            'EEPE-507/A' => 'Industrial Instrumentation and Condition Monitoring Lab',
            'EEPE-507/B' => 'Industrial Automation & Control Lab',
            'EEPE-507/C' => 'Switchgear and Protection Lab',
            'EESI-509' => 'Summer Internship-II',
            'EEPR-510' => 'Major Project (Part-I)',
        ],
        6 => [
            'EEPC-601' => 'Building Electrification',
            'EEPC-602' => 'Building Electrification Laboratory',
            'EEPE-603/A' => 'Communication Technologies',
            'EEPE-603/B' => 'Electric Vehicles',
            'EEPE-603/C' => 'Industrial Drives',
            'HS-604' => 'Entrepreneurship and Start-ups',
            'AU-606' => 'Indian Constitution',
            'EEPR-607' => 'Major Project (Part-II)',
            'EESE-608' => 'Seminar',
        ],
    ];
}

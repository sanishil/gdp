<?php

namespace Database\Seeders;

/**
 * Computer Science & Technology subjects, semesters III to VI, from the DCST 2020 syllabus. Elective options keep
 * the syllabus's suffixed codes (e.g. CSPE407-1, -2, -3).
 */
class CstSubjectSeeder extends DepartmentSubjectSeeder
{
    public const DEPARTMENT = 'Computer Science & Technology';

    /**
     * @var array<int, array<string, string>> semester => [code => name]
     */
    public const SUBJECTS = [
        3 => [
            'CSPC301' => 'Computer Programming',
            'CSPC302' => 'Computer System Organization',
            'CSPC303' => 'Operating Systems',
            'CSPC304' => 'Scripting Languages (Python / Perl)',
            'CSPC305' => 'Digital Logic Design & Microprocessor',
            'CSPC306' => 'Computer Programming Lab',
            'CSPC307' => 'Scripting Languages Lab',
            'CSPC308' => 'Operating Systems Lab',
            'CSPC309' => 'Digital Logic Design & Microprocessor Lab',
            'CSSI310' => 'Summer Internship-I',
        ],
        4 => [
            'CSPC401' => 'Data Structures & Algorithms',
            'CSPC402' => 'Introduction to DBMS',
            'CSPC403' => 'Computer Networks',
            'CSPC404' => 'Software Engineering',
            'CSPC405' => 'Data Structures Lab',
            'CSPC406' => 'Introduction to DBMS Lab',
            'CSPE407-1' => 'Mobile Application Development',
            'CSPE407-2' => 'Object Oriented Programming Methodology',
            'CSPE407-3' => 'Multimedia Technologies',
            'HS408' => 'Professional Skill Development',
            'CSPR409' => 'Mini Project',
            'AU401' => 'Essence of Indian Knowledge and Tradition',
        ],
        5 => [
            'CSPC501' => 'Introduction to e-Governance',
            'CSPC502' => 'Internet of Things (IoT)',
            'CSPC503' => 'Web Technologies',
            'CSPC504' => 'Networking Lab',
            'CSPC505' => 'Web Technology Lab',
            'CSPE506-1' => 'Distributed Systems',
            'CSPE506-2' => 'Cloud Computing',
            'CSPE506-3' => 'Data Science',
            'CSPE507-1' => 'Mobile Computing',
            'CSPE507-2' => 'Software Testing',
            'CSPE507-3' => 'FOSS (Free and Open-Source Software)',
            'CSSI509' => 'Summer Internship-II',
            'CSPR510' => 'Major Project (Part-I)',
        ],
        6 => [
            'CSPC601' => 'Wireless and Mobile Network',
            'CSPC602' => 'Fundamentals of AI',
            'CSPE603-1' => 'Advance Computer Networks',
            'CSPE603-2' => 'Information Security',
            'CSPE603-3' => 'Network Forensics',
            'HSS604' => 'Entrepreneurship and Start-ups',
            'AU606' => 'Indian Constitution',
            'CSPR607' => 'Major Project (Part-II)',
            'CSSE608' => 'Seminar',
        ],
    ];
}

@php
    $field = fn (string $key, string $placeholder) => '<span data-preview-target="'.$key.'" data-placeholder="'.e($placeholder).'">'.e($placeholder).'</span>';
    $college = 'Gomati District Polytechnic';
    $address = 'Fulkumari Part, Tripura 799013';
@endphp

{{--
    Always light, so it prints the same in either theme. Sizes use cqw so the preview and the A4 print match (1cqw ≈ 5.95pt on A4: 26pt = 4.37cqw, 18pt = 3.02cqw, 14pt = 2.35cqw, 12pt = 2.02cqw).
    The flex-1 spacers share the page's spare height between the sections, so no single gap is left at the bottom.
--}}
<div id="contentToPrint" data-bs-theme="light" class="cover-paper bg-white tw:@container tw:aspect-[210/297] tw:font-['Times_New_Roman',Times,serif] tw:text-[#1f2d3d]">
    <div class="d-flex flex-column align-items-center text-center h-100 tw:px-[6cqw] tw:pt-[5cqw] tw:pb-[5cqw] tw:text-[2.02cqw] tw:leading-normal">
        <p class="fw-bold mb-0 tw:text-[3.02cqw]">{!! $field('page_type', 'Assignment') !!}</p>
        <p class="fw-bold mb-0 tw:mt-[0.5cqw] tw:text-[4.37cqw] tw:leading-tight tw:text-balance">{{ $college }}</p>

        <div class="tw:flex-1 tw:min-h-[3.5cqw]" aria-hidden="true"></div>

        <img class="tw:size-[17cqw] tw:object-contain" src="{{ asset('img/logo.webp') }}" alt="" aria-hidden="true">

        <p class="fw-bold text-uppercase mb-0 tw:mt-[2.5cqw] tw:text-[2.35cqw]">{{ $address }}</p>

        <div class="tw:flex-1 tw:min-h-[2.5cqw]" aria-hidden="true"></div>

        <p class="mb-0 tw:text-[2.35cqw]"><strong>Subject Title:</strong> {!! $field('subject', 'Subject Title') !!}</p>
        <p class="mb-0 tw:mt-[1.2cqw] tw:text-[2.35cqw]"><strong>Subject Code:</strong> {!! $field('code', '—') !!}</p>

        <div class="tw:flex-1 tw:min-h-[4.5cqw]" aria-hidden="true"></div>

        <div>
            <p class="fw-bold text-decoration-underline mb-0 tw:mb-[1cqw] tw:text-[2.35cqw]">Submitted to</p>
            <p class="fw-bold mb-0">{!! $field('f_name', 'Teacher') !!}</p>
            <p class="mb-0">{!! $field('f_designation', 'Designation') !!}</p>
            <p class="fw-bold mb-0">Department of {!! $field('f_dept', 'Department') !!}</p>
            <p class="fw-bold mb-0">{{ $college }}</p>

            <div data-preview-row="f2_name" hidden class="tw:mt-[2.5cqw]">
                <p class="fw-bold mb-0">{!! $field('f2_name', 'Teacher') !!}</p>
                <p class="mb-0">{!! $field('f2_designation', 'Designation') !!}</p>
                <p class="fw-bold mb-0">Department of {!! $field('f2_dept', 'Department') !!}</p>
                <p class="fw-bold mb-0">{{ $college }}</p>
            </div>
        </div>

        <div class="tw:flex-1 tw:min-h-[4.5cqw]" aria-hidden="true"></div>

        <div>
            <p class="fw-bold text-decoration-underline mb-0 tw:mb-[1cqw] tw:text-[2.35cqw]">Submitted by</p>
            <p class="fw-bold mb-0">{!! $field('title', 'Mr.') !!} {!! $field('name', 'Your name') !!}</p>
            <p class="mb-0">Tripura University Roll No.:{!! $field('tu_roll', '—') !!}</p>
            <p class="mb-0">Semester: {!! $field('sem', '—') !!}</p>
            <p class="mb-0">Section: {!! $field('section', '—') !!}</p>
            <p class="fw-bold mb-0">Department of {!! $field('std_dept', 'Department') !!}</p>
            <p class="fw-bold mb-0">{{ $college }}</p>
        </div>

        <div class="tw:flex-1 tw:min-h-[3cqw]" aria-hidden="true"></div>

        <p class="fw-bold mb-0">Date of Submission: {!! $field('dos', '—') !!}</p>
    </div>
</div>

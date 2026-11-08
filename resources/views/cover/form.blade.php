<x-layouts.app title="Assignment cover page" :themeable="false" class="intake d-flex flex-column min-vh-100">
    <x-intro-loader />

    <x-site-header>
        @auth
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <a href="{{ route('help') }}" class="btn btn-sm btn-success"><i class="fa-solid fa-circle-question me-1" aria-hidden="true"></i> Help</a>
                @if (auth()->user()->role !== \App\Enums\UserRole::Student)
                    <a href="{{ route(auth()->user()->role->dashboardRoute()) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-gauge me-1" aria-hidden="true"></i> Open portal</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-arrow-right-from-bracket me-1" aria-hidden="true"></i> Sign out
                    </button>
                </form>
            </div>
        @else
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <a href="{{ route('help') }}" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-circle-question me-1" aria-hidden="true"></i> Help
                </a>
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-right-to-bracket me-1" aria-hidden="true"></i> Login
                </a>
            </div>
        @endauth
    </x-site-header>

    <main id="main" class="container-xl py-4 py-lg-5 flex-grow-1">
        <h1 class="font-display h2 mb-1">Assignment cover page</h1>
        <p class="intake-muted mb-4 mb-lg-5">Fill in the form, check the preview, then print.</p>

        <div class="row g-4 g-xl-5">
            <div class="col-lg-7">
                <form method="POST" action="{{ route('cover.students.store') }}" class="brand-form" data-print-form data-price="{{ $price }}" novalidate>
                    @csrf
                    <section class="form-part">
                        <h2 class="form-part-title"><i class="fa-solid fa-plus" aria-hidden="true"></i> Student details</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="tu_roll">Tripura University roll no.</label>
                                <input id="tu_roll" name="tu_roll" class="form-control" pattern="{{ \App\Http\Requests\SaveStudentRequest::ROLL_PATTERN }}" maxlength="30" autocomplete="off" required data-preview="tu_roll"
                                       data-student-lookup="{{ route('cover.students.show', ['tuRoll' => '00000']) }}">
                                <x-field-error for="tu_roll">Enter your university roll number.</x-field-error>
                            </div>
                            <div class="col-4 col-sm-3">
                                <label class="form-label" for="title">Title</label>
                                <select id="title" name="title" class="form-select" data-preview="title">
                                    @foreach ($options['titles'] ?? [] as $title)
                                        <option>{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8 col-sm-9">
                                <label class="form-label" for="name">Full name</label>
                                <input id="name" name="name" class="form-control" autocomplete="name" required data-preview="name">
                                <x-field-error for="name">Enter your name as it appears on your ID card.</x-field-error>
                            </div>
                        </div>
                    </section>

                    <section class="form-part">
                        <h2 class="form-part-title"><i class="fa-solid fa-plus" aria-hidden="true"></i> Department and subject</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="std_dept">Department</label>
                                <select id="std_dept" name="std_dept" class="form-select" required data-preview="std_dept">
                                    <option value="">Choose…</option>
                                    @foreach ($departments as $department)
                                        <option>{{ $department->dept_name }}</option>
                                    @endforeach
                                </select>
                                <x-field-error for="std_dept">Choose your department.</x-field-error>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="sem">Semester</label>
                                <select id="sem" name="sem" class="form-select" required data-preview="sem">
                                    <option value="">Choose…</option>
                                    @foreach ($semesters as $semester)
                                        <option>{{ $semester }}</option>
                                    @endforeach
                                </select>
                                <x-field-error for="sem">Choose a semester.</x-field-error>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="section">Section</label>
                                <select id="section" name="section" class="form-select" data-preview="section">
                                    <option value="">Choose…</option>
                                    @foreach ($options['sections'] ?? [] as $section)
                                        <option>{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label" for="subjects">Subject title</label>
                                <select id="subjects" name="subjects" class="form-select" required data-preview="subject" data-autofill="code"
                                        data-option-filter='{"dept":"std_dept","sem":"sem"}'>
                                    <option value="" data-label="Choose…" data-empty="No subjects for this department and semester">Choose…</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->name }}" data-value="{{ $subject->code }}" data-dept="{{ $subject->department->dept_name }}" data-sem="{{ $subject->semester }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <x-field-error for="subjects">Choose a subject.</x-field-error>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="code">Subject code</label>
                                <input id="code" name="code" class="form-control" readonly tabindex="-1" data-preview="code">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="page_type">This is a…</label>
                                <select id="page_type" name="page_type" class="form-select" data-preview="page_type">
                                    @foreach ($assignmentTypes as $assignmentType)
                                        <option>{{ $assignmentType }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="dos">Submission date</label>
                                <input id="dos" name="dos" type="date" class="form-control" required data-preview="dos">
                                <x-field-error for="dos">Pick the date you're handing it in.</x-field-error>
                            </div>
                        </div>
                    </section>

                    <section class="form-part">
                        <h2 class="form-part-title"><i class="fa-solid fa-plus" aria-hidden="true"></i> Submitted to</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="f_dept">Teacher's department</label>
                                <select id="f_dept" name="f_dept" class="form-select" required data-preview="f_dept">
                                    <option value="">Choose…</option>
                                    @foreach ($departments as $department)
                                        <option>{{ $department->dept_name }}</option>
                                    @endforeach
                                </select>
                                <x-field-error for="f_dept">Choose the teacher's department.</x-field-error>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="f_name">Teacher</label>
                                <select id="f_name" name="f_name" class="form-select" required data-preview="f_name" data-autofill="f_designation"
                                        data-option-filter='{"dept":"f_dept"}'>
                                    <option value="" data-label="Choose…" data-empty="No teachers in this department">Choose…</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->name }}" data-value="{{ $teacher->designationTitle() }}" data-dept="{{ $teacher->department->dept_name }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <x-field-error for="f_name">Choose the teacher.</x-field-error>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="f_designation">Designation</label>
                                <input id="f_designation" name="f_designation" class="form-control" readonly tabindex="-1" data-preview="f_designation">
                            </div>
                        </div>

                        {{-- A second teacher, optional. Disabled while hidden so it isn't required or sent. --}}
                        <fieldset class="second-teacher" id="second-teacher" aria-labelledby="second-teacher-title" hidden disabled>
                            <div class="second-teacher-head">
                                <p class="second-teacher-title" id="second-teacher-title"><i class="fa-solid fa-user-plus" aria-hidden="true"></i> Second teacher</p>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-second-teacher="remove">
                                    <i class="fa-solid fa-xmark me-1" aria-hidden="true"></i> Remove
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label" for="f2_dept">Teacher's department</label>
                                    <select id="f2_dept" name="f2_dept" class="form-select" required data-preview="f2_dept">
                                        <option value="">Choose…</option>
                                        @foreach ($departments as $department)
                                            <option>{{ $department->dept_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-field-error for="f2_dept">Choose the second teacher's department.</x-field-error>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="f2_name">Teacher</label>
                                    <select id="f2_name" name="f2_name" class="form-select" required data-preview="f2_name" data-autofill="f2_designation"
                                            data-option-filter='{"dept":"f2_dept"}'>
                                        <option value="" data-label="Choose…" data-empty="No teachers in this department">Choose…</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->name }}" data-value="{{ $teacher->designationTitle() }}" data-dept="{{ $teacher->department->dept_name }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-field-error for="f2_name">Choose the second teacher.</x-field-error>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="f2_designation">Designation</label>
                                    <input id="f2_designation" name="f2_designation" class="form-control" readonly tabindex="-1" data-preview="f2_designation">
                                </div>
                            </div>
                        </fieldset>

                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" data-second-teacher="add">
                            <i class="fa-solid fa-user-plus me-1" aria-hidden="true"></i> Add another teacher
                        </button>
                    </section>

                    {{-- Asked every time of guests; once of an account, then recorded on it. --}}
                    @unless (auth()->user()?->hasAcceptedTerms())
                        <x-terms-agreement class="mt-4" />
                    @endunless

                    @if ($price > 0)
                        <p class="small intake-muted mt-4 mb-0">
                            <i class="fa-solid fa-indian-rupee-sign me-1" aria-hidden="true"></i>
                            Each cover page costs <strong>{{ \App\Models\Payment::formatPaise($price) }}</strong>, paid securely through Razorpay (UPI, card or net banking) before it's printed or downloaded.
                        </p>
                    @endif

                    <div class="form-actions">
                        <button type="reset" class="btn btn-danger"><i class="fa-solid fa-eraser me-1" aria-hidden="true"></i> Clear</button>
                        <button type="submit" class="btn btn-outline-primary" data-output="print" data-busy="Saving…">
                            <i class="fa-solid fa-print me-1" aria-hidden="true"></i> Print
                        </button>
                        <button type="submit" class="btn btn-success" data-output="pdf" data-busy="Making PDF…">
                            <i class="fa-solid fa-file-pdf me-1" aria-hidden="true"></i> Download PDF
                        </button>
                    </div>
                </form>
            </div>

            <aside class="col-lg-5" aria-label="Preview">
                <div class="preview-panel sticky-lg-top tw:lg:top-6">
                    <p class="preview-label">Preview</p>
                    @include('cover.partials.paper')
                </div>
            </aside>
        </div>
    </main>

    @unless (auth()->user()?->hasAcceptedTerms())
        <x-terms-modal />
    @endunless

    <x-site-footer />
</x-layouts.app>

<link rel="stylesheet" href="{{ asset('css/chair-schedule-merge.css') }}?v=20261106-modal-stack-v1">

<div
    class="schedule-merge-overlay"
    id="scheduleMergeRequestModal"
    hidden
    role="presentation"
    data-subjects-url="{{ route('chair.merge.subjects') }}"
    data-request-url="{{ route('chair.merge.request') }}"
    data-local-request-url="{{ route('chair.merge.local') }}"
    data-remove-url="{{ route('chair.merge.remove', ['merge' => '__MERGE_ID__']) }}"
    data-current-department-id="{{ auth()->user()->department_id ?? '' }}"
    data-term-id="{{ $activeTerm->id ?? '' }}"
>
    <div class="schedule-merge-backdrop" data-merge-close></div>
    <section
        class="schedule-merge-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="scheduleMergeDialogTitle"
        aria-describedby="scheduleMergeDialogDescription"
    >
        <header class="schedule-merge-dialog-header">
            <div>
                <span class="schedule-merge-eyebrow" id="scheduleMergeEyebrow">SHARE A CLASS</span>
                <h2 id="scheduleMergeDialogTitle">Merge a course into this class</h2>
                <p id="scheduleMergeDialogDescription">Choose a course from your college’s Manage Subjects catalog.</p>
            </div>
            <button type="button" class="schedule-merge-close" aria-label="Close" data-merge-close>&times;</button>
        </header>

        <div class="schedule-merge-host-card">
            <div class="schedule-merge-card-label">SCHEDULED CLASS</div>
            <strong id="scheduleMergeHostCourse">Select a class first</strong>
            <div id="scheduleMergeHostDetails"></div>
        </div>

        <div class="schedule-merge-field">
            <label for="scheduleMergeSubjectSearch">Find your course</label>
            <input
                id="scheduleMergeSubjectSearch"
                type="search"
                placeholder="Search course code, title, or program"
                autocomplete="off"
            >
        </div>

        <div class="schedule-merge-field">
            <label for="scheduleMergeSubjectSelect">Course to add</label>
            <select id="scheduleMergeSubjectSelect" size="6" aria-describedby="scheduleMergeSubjectHelp">
                <option value="">Loading Manage Subjects courses…</option>
            </select>
            <small id="scheduleMergeSubjectHelp">Search by course code, title, program, year level, or major.</small>
        </div>

        <div class="schedule-merge-course-card" id="scheduleMergeSubjectDetails" hidden></div>
        <div class="schedule-merge-status" id="scheduleMergeStatus" role="status" aria-live="polite"></div>

        <footer class="schedule-merge-actions">
            <button type="button" class="schedule-merge-button secondary" data-merge-close>Cancel</button>
            <button type="button" class="schedule-merge-button primary" id="scheduleMergeSubmit" disabled>
                Continue
            </button>
        </footer>
    </section>
</div>

<div class="schedule-merge-confirm-overlay" id="scheduleMergeConfirmModal" hidden>
    <div class="schedule-merge-confirm-backdrop"></div>
    <section class="schedule-merge-confirm-card" role="dialog" aria-modal="true" aria-labelledby="scheduleMergeConfirmTitle">
        <div class="schedule-merge-card-label">CONFIRM COURSE MERGE</div>
        <h2 id="scheduleMergeConfirmTitle">Confirm this course</h2>
        <p id="scheduleMergeConfirmMessage"></p>
        <div class="schedule-merge-confirm-course" id="scheduleMergeConfirmCourse"></div>
        <div class="schedule-merge-actions">
            <button type="button" class="schedule-merge-button secondary" id="scheduleMergeConfirmBack">Back</button>
            <button type="button" class="schedule-merge-button primary" id="scheduleMergeConfirmSubmit">Confirm</button>
        </div>
    </section>
</div>

<div class="schedule-merge-confirm-overlay" id="scheduleUnmergeConfirmModal" hidden>
    <div class="schedule-merge-confirm-backdrop"></div>
    <section class="schedule-merge-confirm-card" role="dialog" aria-modal="true" aria-labelledby="scheduleUnmergeConfirmTitle">
        <div class="schedule-merge-card-label">REMOVE ACTIVE MERGE</div>
        <h2 id="scheduleUnmergeConfirmTitle">Unmerge this class?</h2>
        <p id="scheduleUnmergeConfirmMessage">The selected shared course will be removed from this class and its matching meeting entries.</p>
        <div class="schedule-merge-confirm-course" id="scheduleUnmergeConfirmCourses"></div>
        <div class="schedule-merge-actions">
            <button type="button" class="schedule-merge-button secondary" id="scheduleUnmergeConfirmBack">Cancel</button>
            <button type="button" class="schedule-merge-button primary" id="scheduleUnmergeConfirmSubmit">Unmerge class</button>
        </div>
    </section>
</div>

<div class="schedule-merge-success-overlay" id="scheduleMergeSuccessModal" hidden>
    <div class="schedule-merge-confirm-backdrop"></div>
    <section class="schedule-merge-success-card" role="dialog" aria-modal="true" aria-labelledby="scheduleMergeSuccessTitle">
        <div class="schedule-merge-success-icon" aria-hidden="true">✓</div>
        <h2 id="scheduleMergeSuccessTitle">Course merge complete</h2>
        <p id="scheduleMergeSuccessMessage"></p>
        <button type="button" class="schedule-merge-button primary" id="scheduleMergeSuccessDone">Done</button>
    </section>
</div>

@once
    <script src="{{ asset('js/chair-schedule-merge.js') }}?v=20261106-course-picker-v3-unmerge"></script>
@endonce

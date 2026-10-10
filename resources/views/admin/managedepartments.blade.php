<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                /* =========================================

                   PAGE

                ========================================= */

                .department-page {

                    width: 100%;

                }

                .title {

                    font-size: 26px;

                    font-weight: 700;

                    color: #2e7d32;

                    margin-bottom: 5px;

                }

                .subtitle {

                    color: #777;

                    font-size: 14px;

                    margin-bottom: 25px;

                }

                /* =========================================

                   MESSAGES

                ========================================= */

                .success-message {

                    background: #e8f5e9;

                    color: #2e7d32;

                    border-left: 5px solid #2e7d32;

                    padding: 13px 16px;

                    border-radius: 7px;

                    margin-bottom: 20px;

                }

                .error-message {

                    background: #ffebee;

                    color: #b71c1c;

                    border-left: 5px solid #c62828;

                    padding: 13px 16px;

                    border-radius: 7px;

                    margin-bottom: 20px;

                }

                .error-message ul {

                    margin-bottom: 0;

                }

                /* =========================================

                   ADD DEPARTMENT

                ========================================= */

                .add-card {

                    background: white;

                    padding: 22px;

                    border-radius: 12px;

                    margin-bottom: 25px;

                    box-shadow:

                        0 3px 12px rgba(0,0,0,.08);

                    border-top: 4px solid #2e7d32;

                }

                .add-card h3 {

                    margin-top: 0;

                    margin-bottom: 18px;

                    color: #2e7d32;

                    font-size: 19px;

                }

                .add-form {

                    display: grid;

                    grid-template-columns: 180px minmax(220px, 1fr) 180px 1fr auto;

                    gap: 15px;

                    align-items: end;

                }

                .form-group label {

                    display: block;

                    font-size: 14px;

                    font-weight: bold;

                    margin-bottom: 7px;

                }

                input {

                    width: 100%;

                    padding: 11px 12px;

                    border: 1px solid #ccc;

                    border-radius: 6px;

                    font-size: 14px;

                    outline: none;

                }

                input:focus {

                    border-color: #2e7d32;

                    box-shadow:

                        0 0 0 2px

                        rgba(46,125,50,.12);

                }

                .color-input {

                    min-height: 42px;

                    padding: 4px;

                    cursor: pointer;

                }

                .file-input {

                    min-height: 42px;

                    padding: 7px;

                    background: #fff;

                }

                .department-brand {

                    display: flex;

                    align-items: center;

                    gap: 10px;

                }

                .department-logo-preview {

                    width: 42px;

                    height: 42px;

                    object-fit: contain;

                    border: 1px solid #e5e7eb;

                    border-radius: 8px;

                    padding: 3px;

                    background: #fff;

                }

                .department-color-swatch {

                    display: inline-flex;

                    align-items: center;

                    gap: 8px;

                    font-size: 12px;

                    color: #555;

                }

                .department-color-dot {

                    width: 22px;

                    height: 22px;

                    border: 1px solid rgba(0,0,0,.15);

                    border-radius: 6px;

                    display: inline-block;

                }

                /* =========================================

                   BUTTONS

                ========================================= */

                .btn {

                    border: none;

                    padding: 9px 15px;

                    border-radius: 6px;

                    cursor: pointer;

                    font-size: 14px;

                    font-weight: 600;

                    text-decoration: none;

                    display: inline-flex;

                    align-items: center;

                    justify-content: center;

                    gap: 6px;

                    transition: .2s;

                }

                .btn:active {

                    transform: scale(.97);

                }

                .green {

                    background: #2e7d32;

                    color: white;

                }

                .green:hover {

                    background: #1b5e20;

                }

                .green:hover {

                    background: #1b5e20;

                }

                /* ADD COLLEGE DEPARTMENT BUTTON - BLUE */

                .add-department-btn {

                    background: #2563eb;

                    color: #ffffff;

                    box-shadow: 0 2px 5px rgba(37, 99, 235, 0.22);

                }

                .add-department-btn:hover {

                    background: #1d4ed8;

                    color: #ffffff;

                    box-shadow: 0 4px 9px rgba(37, 99, 235, 0.28);

                    transform: translateY(-1px);

                }

                .add-department-btn:focus-visible {

                    outline: 3px solid rgba(37, 99, 235, 0.30);

                    outline-offset: 2px;

                }

                .yellow {

                    background: #f9a825;

                    color: #1b5e20;

                }

                .yellow:hover {

                    background: #e89a00;

                    color: white;

                }

                .red {

                    background: #c62828;

                    color: white;

                }

                .red:hover {

                    background: #a61b1b;

                }

                .gray {

                    background: #757575;

                    color: white;

                }

                .gray:hover {

                    background: #616161;

                }

                /* =========================================

                   TABLE

                ========================================= */

                .table-card {

                    background: white;

                    border-radius: 12px;

                    overflow: hidden;

                    box-shadow:

                        0 3px 12px rgba(0,0,0,.08);

                }

                .table-header {

                    display: flex;

                    justify-content: space-between;

                    align-items: center;

                    padding: 18px 20px;

                    border-bottom: 1px solid #e5e5e5;

                }

                .table-header h3 {

                    margin: 0;

                    color: #2e7d32;

                    font-size: 19px;

                }

                .department-count {

                    background: #e8f5e9;

                    color: #2e7d32;

                    padding: 6px 10px;

                    border-radius: 20px;

                    font-size: 13px;

                    font-weight: bold;

                }

                .table-container {

                    width: 100%;

                    overflow-x: auto;

                }

                table {

                    width: 100%;

                    border-collapse: collapse;

                }

                th {

                    background: #2e7d32;

                    color: white;

                    padding: 14px 16px;

                    text-align: left;

                    font-size: 13px;

                }

                td {

                    padding: 14px 16px;

                    border-bottom: 1px solid #eeeeee;

                    font-size: 14px;

                    vertical-align: middle;

                }

                tbody tr:hover {

                    background: #f9fff9;

                }

                tbody tr:last-child td {

                    border-bottom: none;

                }

                /* =========================================

                   DEPARTMENT CODE

                ========================================= */

                .department-code {

                    display: inline-block;

                    background: #e8f5e9;

                    color: #2e7d32;

                    padding: 6px 10px;

                    border-radius: 6px;

                    font-weight: bold;

                    font-size: 13px;

                    min-width: 55px;

                    text-align: center;

                }

                .department-name {

                    font-weight: 600;

                    color: #333;

                }

                .id-number {

                    color: #777;

                    font-weight: bold;

                }

                .building-count {

                    color: #666;

                }

                /* =========================================

                   ACTIONS

                ========================================= */

                .actions {

                    display: flex;

                    gap: 7px;

                    align-items: center;

                }

                .actions form {

                    margin: 0;

                }

                /* =========================================

                   EMPTY STATE

                ========================================= */

                .empty-state {

                    text-align: center;

                    padding: 50px 20px;

                    color: #777;

                }

                .empty-state-icon {

                    font-size: 35px;

                    margin-bottom: 10px;

                }

                .empty-state h3 {

                    margin: 0 0 5px;

                    color: #555;

                }

                .empty-state p {

                    margin: 0;

                    font-size: 14px;

                }

                /* =========================================

                   EDIT MODAL

                ========================================= */

                .modal {

                    display: none;

                    position: fixed;

                    z-index: 9999;

                    left: 0;

                    top: 0;

                    width: 100%;

                    height: 100%;

                    background: rgba(0,0,0,.55);

                    overflow-y: auto;

                    padding: 30px 15px;

                }

                .modal-content {

                    width: 500px;

                    max-width: 100%;

                    background: white;

                    margin: 50px auto;

                    padding: 28px;

                    border-radius: 12px;

                    box-shadow:

                        0 10px 35px rgba(0,0,0,.25);

                    border-top: 5px solid #2e7d32;

                    animation:

                        modalOpen .2s ease;

                }

                @keyframes modalOpen {

                    from {

                        opacity: 0;

                        transform:

                            translateY(-15px)

                            scale(.98);

                    }

                    to {

                        opacity: 1;

                        transform:

                            translateY(0)

                            scale(1);

                    }

                }

                .modal-content h3 {

                    margin-top: 0;

                    margin-bottom: 7px;

                    color: #2e7d32;

                    font-size: 20px;

                }

                .modal-subtitle {

                    color: #777;

                    font-size: 14px;

                    margin-bottom: 22px;

                }

                .modal-form-group {

                    margin-bottom: 16px;

                }

                .modal-form-group label {

                    display: block;

                    font-size: 14px;

                    font-weight: bold;

                    margin-bottom: 7px;

                }

                .modal-buttons {

                    display: flex;

                    justify-content: flex-end;

                    gap: 9px;

                    margin-top: 22px;

                }

                /* =========================================

                   RESPONSIVE

                ========================================= */

                @media(max-width: 900px) {

                    .add-form {

                        grid-template-columns: 1fr 1fr;

                    }

                    .add-button {

                        grid-column: 1 / -1;

                    }

                }

                @media(max-width: 700px) {

                    .add-form {

                        grid-template-columns: 1fr;

                    }

                    .add-button {

                        grid-column: auto;

                    }

                    .table-header {

                        flex-direction: column;

                        align-items: flex-start;

                        gap: 10px;

                    }

                    .actions {

                        flex-wrap: wrap;

                    }

                    .modal-content {

                        margin: 20px auto;

                        padding: 22px;

                    }

                }

/* =========================================

   CENTER SUCCESS / ERROR POPUP

\\========================================= */

.action-message-overlay {

    position: fixed !important;

    inset: 0 !important;

    top: 0 !important;

    left: 0 !important;

    right: 0 !important;

    bottom: 0 !important;

    width: 100vw;

    height: 100vh;

    background: rgba(0, 0, 0, .45);

    backdrop-filter: blur(3px);

    display: flex !important;

    align-items: center !important;

    justify-content: center !important;

    padding: 20px;

    box-sizing: border-box;

    z-index: 999999 !important;

    margin: 0;

    animation: actionFadeIn .2s ease;

}

.action-message-modal {

    position: relative;

    width: 420px;

    max-width: 100%;

    max-height: calc(100vh - 40px);

    overflow-y: auto;

    margin: 0 !important;

    background: #fff;

    border-radius: 18px;

    padding: 38px 35px 32px;

    text-align: center;

    box-sizing: border-box;

    box-shadow: 0 20px 60px rgba(0,0,0,.20);

    animation: actionPop .25s ease;

}

.action-message-close {

    position: absolute;

    top: 12px;

    right: 15px;

    width: 34px;

    height: 34px;

    border: none;

    background: transparent;

    color: #777;

    font-size: 28px;

    line-height: 30px;

    cursor: pointer;

    border-radius: 50%;

}

.action-message-close:hover {

    background: #f3f4f6;

    color: #333;

}

.action-message-icon {

    width: 78px;

    height: 78px;

    margin: 0 auto 20px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

}

.action-message-icon.success {

    background: #dcfce7;

}

.action-message-icon.error {

    background: #fee2e2;

}

.action-message-icon svg {

    width: 52px;

    height: 52px;

    fill: none;

    stroke-width: 3;

    stroke-linecap: round;

    stroke-linejoin: round;

}

.action-message-icon.success svg {

    stroke: #15803d;

}

.action-message-icon.error svg {

    stroke: #dc2626;

}

.action-message-modal h2 {

    margin: 0 0 10px;

    font-size: 25px;

    font-weight: 700;

}

.action-message-modal.success h2 {

    color: #166534;

}

.action-message-modal.error h2 {

    color: #b91c1c;

}

.action-message-modal p {

    margin: 0 auto 25px;

    max-width: 330px;

    color: #555;

    font-size: 16px;

    line-height: 1.5;

}

.action-message-ok {

    width: 130px;

    padding: 11px 25px;

    border: none;

    border-radius: 8px;

    color: #fff;

    font-size: 15px;

    font-weight: 600;

    cursor: pointer;

    transition: all .2s ease;

}

.action-message-ok.success {

    background: #15803d;

}

.action-message-ok.success:hover {

    background: #166534;

    transform: translateY(-1px);

}

.action-message-ok.error {

    background: #dc2626;

}

.action-message-ok.error:hover {

    background: #b91c1c;

    transform: translateY(-1px);

}

@keyframes actionFadeIn {

    from { opacity: 0; }

    to { opacity: 1; }

}

@keyframes actionPop {

    from {

        opacity: 0;

        transform: scale(.85) translateY(10px);

    }

    to {

        opacity: 1;

        transform: scale(1) translateY(0);

    }

}

@media (max-width: 500px) {

    .action-message-modal {

        width: 100%;

        padding: 32px 22px 27px;

    }

    .action-message-modal h2 {

        font-size: 22px;

    }

    .action-message-modal p {

        font-size: 15px;

    }

}

</style>

            <div class="department-page">

                <!-- =====================================

                     TITLE

                ====================================== -->

                <div class="title">

                    Manage College Departments

                </div>

                <div class="subtitle">

                    Add, edit and remove departments.

                </div>

                <!-- =====================================

                {{-- SINGLE SUCCESS / ERROR MESSAGE POPUP --}}
                @php
                    $alertMessage = session('success')
                        ?? session('error')
                        ?? ($errors->any() ? $errors->first() : null);
                    $alertType = session()->has('success') ? 'success' : 'error';
                @endphp

                @if ($alertMessage)
                    <div id="departmentActionMessage" class="action-message-overlay"
                         role="alertdialog" aria-modal="true" aria-labelledby="actionMessageTitle">
                        <div class="action-message-modal {{ $alertType }}">
                            <button type="button" class="action-message-close"
                                    onclick="closeActionMessage()" aria-label="Close">&times;</button>

                            <div class="action-message-icon {{ $alertType }}" aria-hidden="true">
                                @if ($alertType === 'success')
                                    <svg viewBox="0 0 52 52">
                                        <circle cx="26" cy="26" r="24"></circle>
                                        <path d="M14 27l8 8 16-18"></path>
                                    </svg>
                                @else
                                    <svg viewBox="0 0 52 52">
                                        <circle cx="26" cy="26" r="24"></circle>
                                        <path d="M18 18l16 16M34 18L18 34"></path>
                                    </svg>
                                @endif
                            </div>

                            <h2 id="actionMessageTitle">
                                {{ $alertType === 'success' ? 'Successful!' : 'Please Check' }}
                            </h2>
                            <p>{{ $alertMessage }}</p>
                            <button type="button" class="action-message-ok {{ $alertType }}"
                                    onclick="closeActionMessage()">OK</button>
                        </div>
                    </div>
                @endif

                     ADD DEPARTMENT

                ====================================== -->

                <div class="add-card">

                    <h3>

                        Add New College Department

                    </h3>

                    <form

                        action="{{ route('admin.departments.store') }}"

                        method="POST"

                        enctype="multipart/form-data"

                    >

                        @csrf

                        <div class="add-form">

                            <!-- CODE -->

                            <div class="form-group">

                                <label>

                                    College Department Code

                                </label>

                                <input

                                    type="text"

                                    name="code"

                                    placeholder="Example: CAT"

                                    value="{{ old('code') }}"

                                    maxlength="50"

                                    required

                                >

                            </div>

                            <!-- NAME -->

                            <div class="form-group">

                                <label>

                                    College Department Name

                                </label>

                                <input

                                    type="text"

                                    name="name"

                                    placeholder="Example: College of Applied Technology"

                                    value="{{ old('name') }}"

                                    maxlength="255"

                                    required

                                >

                            </div>

                            <!-- THEME COLOR -->

                            <div class="form-group">

                                <label for="departmentThemeColor">Department Theme Color</label>

                                <input class="color-input" type="color" id="departmentThemeColor" name="theme_color" value="{{ old('theme_color', '#2563eb') }}" required>

                            </div>

                            <!-- DEPARTMENT LOGO -->

                            <div class="form-group">

                                <label for="departmentLogo">Department Logo (optional)</label>

                                <input class="file-input" type="file" id="departmentLogo" name="logo" accept="image/png,image/jpeg,image/webp">

                                <small>PNG, JPG, or WebP. Maximum 2 MB.</small>

                            </div>

                            <!-- ADD -->

                            <div class="add-button">

                                <button

                                    type="submit"

                                    class="btn add-department-btn"

                                >

                                    + Add College Department

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

                <!-- =====================================

                     DEPARTMENT LIST

                ====================================== -->

                <div class="table-card">

                    <div class="table-header">

                        <h3>

                            College Department List

                        </h3>

                        <span class="department-count">

                            {{ $departments->count() }}

                            College Department(s)

                        </span>

                    </div>

                    <div class="table-container">

                        @if($departments->count() > 0)

                            <table>

                                <thead>

                                    <tr>

                                        <th>

                                            ID

                                        </th>

                                        <th>

                                            Code

                                        </th>

                                        <th>

                                            College Department Name

                                        </th>

                                        <th>Logo</th>

                                        <th>Theme Color</th>

                                        <th>

                                            Buildings

                                        </th>

                                        <th>

                                            Actions

                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($departments as $department)

                                        <tr>

                                            <!-- ID -->

                                            <td>

                                                <span

                                                    class="id-number"

                                                >

                                                    {{ $department->id }}

                                                </span>

                                            </td>

                                            <!-- CODE -->

                                            <td>

                                                <span

                                                    class="department-code"

                                                >

                                                    {{ $department->code }}

                                                </span>

                                            </td>

                                            <!-- NAME -->

                                            <td>

                                                <span

                                                    class="department-name"

                                                >

                                                    {{ $department->name }}

                                                </span>

                                            </td>

                                            <!-- LOGO -->

                                            <td>

                                                @if($department->logo)

                                                    <img class="department-logo-preview" src="{{ asset('storage/' . $department->logo) }}" alt="{{ $department->name }} logo">

                                                @else

                                                    <span style="color:#9ca3af; font-size:12px;">No logo</span>

                                                @endif

                                            </td>

                                            <!-- THEME COLOR -->

                                            <td>

                                                <span class="department-color-swatch">

                                                    <span class="department-color-dot" style="background-color: {{ $department->theme_color ?: '#2563eb' }};"></span>

                                                    {{ $department->theme_color ?: '#2563eb' }}

                                                </span>

                                            </td>

                                            <!-- BUILDINGS -->

                                            <td>

                                                <span

                                                    class="building-count"

                                                >

                                                    {{ $department->buildings_count }}

                                                    building(s)

                                                </span>

                                            </td>

                                            <!-- ACTIONS -->

                                            <td>

                                                <div class="actions">

                                                    <!-- EDIT -->

                                                    <button

                                                        type="button"

                                                        class="btn yellow"

                                                        onclick="openEditDepartment(

                                                            {{ $department->id }},

                                                            @js($department->code),

                                                            @js($department->name),

                                                            @js($department->theme_color ?: '#2563eb'),

                                                            @js($department->logo)

                                                        )"

                                                    >

                                                        Edit

                                                    </button>

                                                    <!-- DELETE -->

                                                    <form

                                                        action="{{ route(

                                                            'admin.departments.delete',

                                                            $department->id

                                                        ) }}"

                                                        method="POST"

                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button

                                                            type="submit"

                                                            class="btn red"

                                                            onclick="return confirmDelete(

                                                                @js($department->name)

                                                            )"

                                                        >

                                                            Delete

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        @else

                            <div class="empty-state">

                                <div class="empty-state-icon">

                                    🏢

                                </div>

                                <h3>

                                    No Departments Found

                                </h3>

                                <p>

                                    Add your first department above.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </main>

    </div>

 @include('footerheader.footer')

</div>

<!-- ==================================================

     EDIT DEPARTMENT MODAL

\\=================================================== -->

<div

    id="editDepartmentModal"

    class="modal"

>

    <div class="modal-content">

        <h3>

            Edit Department

        </h3>

        <div class="modal-subtitle">

            Update the department information.

        </div>

        <form

            id="editDepartmentForm"

            method="POST"

            enctype="multipart/form-data"

        >

            @csrf

            @method('PUT')

            <!-- CODE -->

            <div class="modal-form-group">

                <label>

                    Department Code

                </label>

                <input

                    type="text"

                    name="code"

                    id="editDepartmentCode"

                    maxlength="50"

                    required

                >

            </div>

            <!-- NAME -->

            <div class="modal-form-group">

                <label>

                    Department Name

                </label>

                <input

                    type="text"

                    name="name"

                    id="editDepartmentName"

                    maxlength="255"

                    required

                >

            </div>

            <div class="modal-form-group">

                <label for="editDepartmentThemeColor">Department Theme Color</label>

                <input class="color-input" type="color" name="theme_color" id="editDepartmentThemeColor" value="#2563eb" required>

            </div>

            <div class="modal-form-group">

                <label for="editDepartmentLogo">Change Department Logo (optional)</label>

                <div id="editDepartmentCurrentLogo" style="margin:8px 0 12px;"></div>

                <input class="file-input" type="file" name="logo" id="editDepartmentLogo" accept="image/png,image/jpeg,image/webp">

                <small>Leave empty to keep the current logo. PNG, JPG, or WebP; maximum 2 MB.</small>

            </div>

            <div class="modal-buttons">

                <button

                    type="button"

                    class="btn gray"

                    onclick="closeEditDepartment()"

                >

                    Cancel

                </button>

                <button

                    type="submit"

                    class="btn green"

                >

                    Save Changes

                </button>

            </div>

        </form>

    </div>

</div>

<script>

/*

\|--------------------------------------------------------------------------

\| OPEN EDIT DEPARTMENT

\|--------------------------------------------------------------------------

*/

function openEditDepartment(

    id,

    code,

    name,

    themeColor,

    logoPath

)

{

    const modal =

        document.getElementById(

            'editDepartmentModal'

        );

    const form =

        document.getElementById(

            'editDepartmentForm'

        );

    const codeInput =

        document.getElementById(

            'editDepartmentCode'

        );

    const nameInput =

        document.getElementById(

            'editDepartmentName'

        );

    const colorInput = document.getElementById('editDepartmentThemeColor');

    const logoPreview = document.getElementById('editDepartmentCurrentLogo');

    const logoInput = document.getElementById('editDepartmentLogo');

    if (

        !modal ||

        !form ||

        !codeInput ||

        !nameInput ||

        !colorInput ||

        !logoPreview

    ) {

        console.error(

            'Edit Department modal not found.'

        );

        return;

    }

    codeInput.value = code;

    nameInput.value = name;

    colorInput.value = themeColor || '#2563eb';

    logoPreview.innerHTML = logoPath

        ? '<img class="department-logo-preview" src="/storage/' + String(logoPath).replace(/^\/+/, '') + '" alt="Current department logo">'

        : '<span style="color:#9ca3af;font-size:12px;">No logo uploaded yet.</span>';

    if (logoInput) logoInput.value = '';

    form.action =

        '/admin/departments/' + id;

    modal.style.display = 'block';

    setTimeout(function() {

        codeInput.focus();

    }, 100);

}

/*

\|--------------------------------------------------------------------------

\| CLOSE EDIT MODAL

\|--------------------------------------------------------------------------

*/

function closeEditDepartment()

{

    const modal =

        document.getElementById(

            'editDepartmentModal'

        );

    if (modal) {

        modal.style.display = 'none';

    }

}

/*

\|--------------------------------------------------------------------------

\| DELETE CONFIRMATION

\|--------------------------------------------------------------------------

*/

function confirmDelete(name)

{

    return confirm(

        'Are you sure you want to delete "' +

        name +

        '"?'

    );

}

/*

\|--------------------------------------------------------------------------

\| CLICK OUTSIDE MODAL

\|--------------------------------------------------------------------------

*/

window.addEventListener(

    'click',

    function(event)

    {

        const modal =

            document.getElementById(

                'editDepartmentModal'

            );

        if (

            modal &&

            event.target === modal

        ) {

            closeEditDepartment();

        }

    }

);

/*

\|--------------------------------------------------------------------------

\| ESCAPE KEY

\|--------------------------------------------------------------------------

*/

document.addEventListener(

    'keydown',

    function(event)

    {

        if (event.key === 'Escape') {

            closeEditDepartment();

        }

    }

);

</script>

<script>
(function () {
    const message = document.getElementById('departmentActionMessage');

    function closeActionMessage() {
        if (!message) return;
        message.style.display = 'none';
        message.setAttribute('aria-hidden', 'true');
    }

    function prepareActionMessage() {
        if (!message) return;
        if (message.parentElement !== document.body) {
            document.body.appendChild(message);
        }
        message.style.display = 'flex';
        message.setAttribute('aria-hidden', 'false');
        window.setTimeout(closeActionMessage, 4000);
    }

    window.closeActionMessage = closeActionMessage;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', prepareActionMessage, { once: true });
    } else {
        prepareActionMessage();
    }

    document.addEventListener('click', function (event) {
        if (message && event.target === message) closeActionMessage();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeActionMessage();
    });
})();
</script>

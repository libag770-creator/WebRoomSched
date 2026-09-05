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
                    grid-template-columns: 180px 1fr auto;
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
                        grid-template-columns:
                            1fr 1fr;
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
                     SUCCESS
                ====================================== -->

                @if(session('success'))

                    <div class="success-message">
                        {{ session('success') }}
                    </div>

                @endif


                <!-- =====================================
                     ERROR
                ====================================== -->

                @if(session('error'))

                    <div class="error-message">
                        {{ session('error') }}
                    </div>

                @endif


                <!-- =====================================
                     VALIDATION ERRORS
                ====================================== -->

                @if($errors->any())

                    <div class="error-message">

                        <strong>
                            Please fix the following:
                        </strong>

                        <ul>

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <!-- =====================================
                     ADD DEPARTMENT
                ====================================== -->

                <div class="add-card">

                    <h3>
                        Add New College Department
                    </h3>


                    <form
                        action="{{ route('admin.departments.store') }}"
                        method="POST"
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


                            <!-- ADD -->

                            <div class="add-button">

                                <button
                                    type="submit"
                                    class="btn green"
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
                                                            @js($department->name)
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
=================================================== -->

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
|--------------------------------------------------------------------------
| OPEN EDIT DEPARTMENT
|--------------------------------------------------------------------------
*/

function openEditDepartment(
    id,
    code,
    name
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


    if (
        !modal ||
        !form ||
        !codeInput ||
        !nameInput
    ) {

        console.error(
            'Edit Department modal not found.'
        );

        return;
    }


    codeInput.value = code;

    nameInput.value = name;


    form.action =
        '/admin/departments/' + id;


    modal.style.display = 'block';


    setTimeout(function() {

        codeInput.focus();

    }, 100);

}



/*
|--------------------------------------------------------------------------
| CLOSE EDIT MODAL
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| DELETE CONFIRMATION
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| CLICK OUTSIDE MODAL
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| ESCAPE KEY
|--------------------------------------------------------------------------
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
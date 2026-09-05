<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                /* =========================================
                   GENERAL
                ========================================= */

                * {
                    box-sizing: border-box;
                }

                body {
                    background: #f5f5f5;
                }


                /* =========================================
                   PAGE
                ========================================= */

                .edit-page {
                    min-height: calc(100vh - 120px);

                    display: flex;
                    justify-content: center;
                    align-items: center;

                    padding: 30px;
                }


                /* =========================================
                   FORM CARD
                ========================================= */

                .form-card {
                    background: white;

                    width: 100%;
                    max-width: 550px;

                    padding: 30px;

                    border-radius: 12px;

                    box-shadow:
                        0 3px 12px
                        rgba(0, 0, 0, .12);
                }


                /* =========================================
                   TITLE
                ========================================= */

                .page-title {
                    text-align: center;

                    font-size: 26px;

                    font-weight: bold;

                    color: #2e7d32;

                    margin-bottom: 5px;
                }


                .page-subtitle {
                    text-align: center;

                    color: #777;

                    margin-bottom: 25px;

                    font-size: 14px;
                }


                /* =========================================
                   FORM GROUP
                ========================================= */

                .form-group {
                    margin-bottom: 18px;
                }


                .form-group label {
                    display: block;

                    font-weight: bold;

                    margin-bottom: 7px;

                    color: #333;
                }


                .form-group input,
                .form-group select {
                    width: 100%;

                    padding: 11px;

                    border:
                        1px solid #ccc;

                    border-radius: 6px;

                    font-size: 14px;

                    background: white;

                    transition: .2s;
                }


                .form-group input:focus,
                .form-group select:focus {
                    outline: none;

                    border-color: #2e7d32;

                    box-shadow:
                        0 0 0 2px
                        rgba(46,125,50,.12);
                }


                /* =========================================
                   DEPARTMENT NOTE
                ========================================= */

                .department-note {
                    margin-top: 6px;

                    font-size: 12px;

                    color: #777;
                }


                /* =========================================
                   BUTTONS
                ========================================= */

                .buttons {
                    display: flex;

                    justify-content: space-between;

                    gap: 10px;

                    margin-top: 25px;
                }


                .btn {
                    flex: 1;

                    padding: 11px;

                    border: none;

                    border-radius: 6px;

                    cursor: pointer;

                    font-size: 14px;

                    font-weight: bold;

                    text-align: center;

                    text-decoration: none;

                    transition:
                        background .2s ease,
                        transform .15s ease;
                }


                .btn:active {
                    transform: scale(.97);
                }


                /* =========================================
                   BACK
                ========================================= */

                .btn-back {
                    background: #777;

                    color: white;
                }


                .btn-back:hover {
                    background: #5f5f5f;
                }


                /* =========================================
                   UPDATE
                ========================================= */

                .btn-update {
                    background: #2e7d32;

                    color: white;
                }


                .btn-update:hover {
                    background: #1b5e20;
                }


                /* =========================================
                   ERROR
                ========================================= */

                .error-box {
                    background: #f8d7da;

                    color: #842029;

                    padding: 12px;

                    border-radius: 6px;

                    margin-bottom: 20px;
                }


                .error-box ul {
                    margin: 0;

                    padding-left: 20px;
                }


                /* =========================================
                   SUCCESS
                ========================================= */

                .success-box {
                    background: #e8f5e9;

                    color: #2e7d32;

                    padding: 12px;

                    border-radius: 6px;

                    margin-bottom: 20px;
                }


                /* =========================================
                   RESPONSIVE
                ========================================= */

                @media (max-width: 700px) {

                    .edit-page {
                        padding: 20px;
                    }

                    .form-card {
                        padding: 24px;
                    }

                }

            </style>


            <!-- =========================================
                 EDIT PAGE
            ========================================= -->

            <div class="edit-page">

                <div class="form-card">


                    <!-- TITLE -->

                    <div class="page-title">
                        Edit User
                    </div>


                    <div class="page-subtitle">
                        Update the user's account information.
                    </div>


                    <!-- =====================================
                         ERROR
                    ====================================== -->

                    @if ($errors->any())

                        <div class="error-box">

                            <ul>

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <!-- =====================================
                         SUCCESS
                    ====================================== -->

                    @if(session('success'))

                        <div class="success-box">
                            {{ session('success') }}
                        </div>

                    @endif


                    <!-- =====================================
                         FORM
                    ====================================== -->

                    <form
                        action="{{ route(
                            'admin.manageusers.update',
                            $user->id
                        ) }}"
                        method="POST"
                    >

                        @csrf

                        @method('PUT')


                        <!-- =================================
                             NAME
                        ================================== -->

                        <div class="form-group">

                            <label>
                                Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old(
                                    'name',
                                    $user->name
                                ) }}"
                                required
                            >

                        </div>


                        <!-- =================================
                             USERNAME
                        ================================== -->

                        <div class="form-group">

                            <label>
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                value="{{ old(
                                    'username',
                                    $user->username
                                ) }}"
                                required
                            >

                        </div>


                        <!-- =================================
                             EMAIL
                        ================================== -->

                        <div class="form-group">

                            <label>
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old(
                                    'email',
                                    $user->email
                                ) }}"
                                required
                            >

                        </div>


                        <!-- =================================
                             ROLE
                        ================================== -->

                        <div class="form-group">

                            <label>
                                Role
                            </label>

                            <select
                                name="role"
                                id="role"
                                required
                            >

                                <option
                                    value="admin"
                                    {{ old(
                                        'role',
                                        $user->role
                                    ) == 'admin'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Admin
                                </option>


                                <option
                                    value="chair"
                                    {{ old(
                                        'role',
                                        $user->role
                                    ) == 'chair'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Department Chair
                                </option>


                                <option
                                    value="faculty"
                                    {{ old(
                                        'role',
                                        $user->role
                                    ) == 'faculty'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Faculty
                                </option>

                            </select>

                        </div>


                        <!-- =================================
                             DEPARTMENT
                        ================================== -->

                        <div
                            class="form-group"
                            id="department-group"
                        >

                            <label>
                                Department
                            </label>


                            <select
                                name="department_id"
                                id="department_id"
                            >

                                <option value="">
                                    Select Department
                                </option>


                                @foreach($departments as $department)

                                    <option
                                        value="{{ $department->id }}"
                                        {{ old(
                                            'department_id',
                                            $user->department_id
                                        ) == $department->id
                                            ? 'selected'
                                            : ''
                                        }}
                                    >

                                        {{ $department->code }}
                                        -
                                        {{ $department->name }}

                                    </option>

                                @endforeach

                            </select>


                            <div class="department-note">
                                Department is required for Faculty
                                and Department Chair.
                            </div>

                        </div>


                        <!-- =================================
                             BUTTONS
                        ================================== -->

                        <div class="buttons">


                            <a
                                href="{{ route(
                                    'admin.manageusers'
                                ) }}"
                                class="btn btn-back"
                            >
                                Back
                            </a>


                            <button
                                type="submit"
                                class="btn btn-update"
                            >
                                Update User
                            </button>


                        </div>


                    </form>

                </div>

            </div>


        </main>

    </div>


    @include('footerheader.footer')

</div>


<script>

/*
|--------------------------------------------------------------------------
| ROLE → DEPARTMENT
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function ()
    {

        const role =
            document.getElementById('role');

        const departmentGroup =
            document.getElementById(
                'department-group'
            );

        const department =
            document.getElementById(
                'department_id'
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE DEPARTMENT FIELD
        |--------------------------------------------------------------------------
        */

        function updateDepartment()
        {

            if (
                role.value === 'faculty' ||
                role.value === 'chair'
            ) {

                /*
                | Show department
                */

                departmentGroup.style.display =
                    'block';


                /*
                | Make department required
                */

                department.required =
                    true;

            }

            else {

                /*
                | Hide department
                */

                departmentGroup.style.display =
                    'none';


                /*
                | Remove required
                */

                department.required =
                    false;


                /*
                | Admin has no department
                */

                department.value =
                    '';

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ROLE CHANGE
        |--------------------------------------------------------------------------
        */

        role.addEventListener(
            'change',
            updateDepartment
        );


        /*
        |--------------------------------------------------------------------------
        | INITIAL STATE
        |--------------------------------------------------------------------------
        */

        updateDepartment();

    }
);

</script>
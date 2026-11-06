<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">


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
                                    College Chair
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
                                    Instructor
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
                                College
                            </label>


                            <select
                                name="department_id"
                                id="department_id"
                            >

                                <option value="">
                                    Select College
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
                                College is required for Instructor
                                and College Chair.
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
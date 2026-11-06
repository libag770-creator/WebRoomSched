<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

        


            <div class="add-page">

                <div class="form-card">

                    <div class="page-title">
                        Add User
                    </div>

                    <div class="page-subtitle">
                        Create a new Admin, Chair, or Instructor account.
                    </div>


                    @if ($errors->any())

                        <div class="error-box">

                            <ul>

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        action="{{ route('admin.manageusers.store') }}"
                        method="POST"
                    >

                        @csrf


                        <!-- Name -->

                        <div class="form-group">

                            <label>Name</label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                            >

                        </div>


                        <!-- Username -->

                        <div class="form-group">

                            <label>Username</label>

                            <input
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                            >

                        </div>


                        <!-- Email -->

                        <div class="form-group">

                            <label>Email</label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                            >

                        </div>


                        <!-- Password -->

                        <div class="form-group">

                            <label>Password</label>

                            <input
                                type="password"
                                name="password"
                                required
                            >

                        </div>


                        <!-- Role -->

                        <div class="form-group">

                            <label>Role</label>

                            <select name="role" required>

                                <option
                                    value="admin"
                                    {{ old('role') == 'admin' ? 'selected' : '' }}
                                >
                                    Admin
                                </option>

                                <option
                                    value="chair"
                                    {{ old('role') == 'chair' ? 'selected' : '' }}
                                >
                                    College Chair
                                </option>

                                <option
                                    value="faculty"
                                    {{ old('role') == 'faculty' ? 'selected' : '' }}
                                >
                                    Instructor
                                </option>

                            </select>

                        </div>
<!-- College -->

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
                {{ old('department_id') == $department->id ? 'selected' : '' }}
            >
                {{ $department->code }}
                -
                {{ $department->name }}
            </option>

        @endforeach

    </select>

</div>

                        <!-- Buttons -->

                        <div class="buttons">

                            <a
                                href="{{ route('admin.manageusers') }}"
                                class="btn btn-back"
                            >
                                Back
                            </a>

                            <button
                                type="submit"
                                class="btn btn-save"
                            >
                                Save User
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </main>

    </div>


</div>
<script>

document.addEventListener(
    'DOMContentLoaded',
    function()
    {

        const role =
            document.querySelector(
                'select[name="role"]'
            );

        const departmentGroup =
            document.getElementById(
                'department-group'
            );

        const department =
            document.getElementById(
                'department_id'
            );


        function updateDepartment()
        {

            if (
                role.value === 'faculty' ||
                role.value === 'chair'
            ) {

                departmentGroup.style.display =
                    'block';

                department.required =
                    true;

            }

            else {

                departmentGroup.style.display =
                    'none';

                department.required =
                    false;

                department.value =
                    '';

            }

        }


        role.addEventListener(
            'change',
            updateDepartment
        );


        updateDepartment();

    }
);

</script> 
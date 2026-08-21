<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>
                * {
                    box-sizing: border-box;
                }

                body {
                    background: #f5f5f5;
                }

                .add-page {
                    min-height: calc(100vh - 120px);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    padding: 30px;
                }

                .form-card {
                    background: white;
                    width: 100%;
                    max-width: 550px;
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, .12);
                }

                .page-title {
                    text-align: center;
                    font-size: 26px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .page-subtitle {
                    text-align: center;
                    color: #777;
                    margin-bottom: 25px;
                }

                .form-group {
                    margin-bottom: 18px;
                }

                .form-group label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 7px;
                }

                .form-group input,
                .form-group select {
                    width: 100%;
                    padding: 11px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    font-size: 14px;
                }

                .form-group input:focus,
                .form-group select:focus {
                    outline: none;
                    border-color: #2e7d32;
                }

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
                }

                .btn-back {
                    background: #777;
                    color: white;
                }

                .btn-back:hover {
                    background: #5f5f5f;
                }

                .btn-save {
                    background: #2e7d32;
                    color: white;
                }

                .btn-save:hover {
                    background: #256628;
                }

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
            </style>


            <div class="add-page">

                <div class="form-card">

                    <div class="page-title">
                        Add User
                    </div>

                    <div class="page-subtitle">
                        Create a new Admin, Chair, or Faculty account.
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
                                    Department Chair
                                </option>

                                <option
                                    value="faculty"
                                    {{ old('role') == 'faculty' ? 'selected' : '' }}
                                >
                                    Faculty
                                </option>

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

    @include('footerheader.footer')

</div>
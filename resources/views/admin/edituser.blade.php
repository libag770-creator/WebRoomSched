<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                /* =========================
                   PAGE TITLE
                ========================= */

                .page-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .page-subtitle {
                    color: #777;
                    margin-bottom: 20px;
                }


                /* =========================
                   PANEL
                ========================= */

                .edit-panel {
                    background: #fff;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    overflow: hidden;
                    max-width: 800px;
                }


                /* =========================
                   PANEL HEADER
                ========================= */

                .panel-header {
                    padding: 15px 20px;
                    border-bottom: 1px solid #ddd;
                    background: #fff;
                    font-size: 18px;
                    font-weight: bold;
                }


                /* =========================
                   FORM
                ========================= */

                .form-container {
                    padding: 20px;
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
                    padding: 10px 12px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-sizing: border-box;
                    font-size: 14px;
                    background: #fff;
                }

                .form-group input:focus,
                .form-group select:focus {
                    outline: none;
                    border-color: #2e7d32;
                }


                /* =========================
                   BUTTONS
                ========================= */

                .form-buttons {
                    display: flex;
                    justify-content: flex-end;
                    gap: 8px;
                    margin-top: 20px;
                    padding-top: 15px;
                    border-top: 1px solid #eee;
                }

                .btn {
                    padding: 9px 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: bold;
                    text-decoration: none;
                    display: inline-block;
                }


                /* CANCEL */

                .cancel-btn {
                    background: #777;
                    color: white;
                }

                .cancel-btn:hover {
                    background: #666;
                }


                /* UPDATE */

                .update-btn {
                    background: #2e7d32;
                    color: white;
                }

                .update-btn:hover {
                    background: #256628;
                }


                /* =========================
                   MOBILE
                ========================= */

                @media (max-width: 700px) {

                    .edit-panel {
                        width: 100%;
                    }

                    .form-buttons {
                        flex-direction: column;
                    }

                    .btn {
                        width: 100%;
                        text-align: center;
                        box-sizing: border-box;
                    }

                }

            </style>


            <!-- PAGE TITLE -->

            <div class="page-title">
                Edit User
            </div>

            <div class="page-subtitle">
                Update the user's information and role.
            </div>


            <!-- EDIT PANEL -->

            <div class="edit-panel">


                <!-- PANEL HEADER -->

                <div class="panel-header">
                    User Information
                </div>


                <!-- FORM -->

                <div class="form-container">

                    <form
                        action="{{ route('admin.manageusers.update', $user->id) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PUT')


                        <!-- NAME -->

                        <div class="form-group">

                            <label for="name">
                                Name
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ $user->name }}"
                                required
                            >

                        </div>


                        <!-- USERNAME -->

                        <div class="form-group">

                            <label for="username">
                                Username
                            </label>

                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ $user->username }}"
                                required
                            >

                        </div>


                        <!-- EMAIL -->

                        <div class="form-group">

                            <label for="email">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ $user->email }}"
                                required
                            >

                        </div>


                        <!-- ROLE -->

                        <div class="form-group">

                            <label for="role">
                                Role
                            </label>

                            <select
                                id="role"
                                name="role"
                                required
                            >

                                <option
                                    value="admin"
                                    {{ $user->role == 'admin' ? 'selected' : '' }}
                                >
                                    Admin
                                </option>

                                <option
                                    value="chair"
                                    {{ $user->role == 'chair' ? 'selected' : '' }}
                                >
                                    Chair
                                </option>

                                <option
                                    value="faculty"
                                    {{ $user->role == 'faculty' ? 'selected' : '' }}
                                >
                                    Faculty
                                </option>

                            </select>

                        </div>


                        <!-- BUTTONS -->

                        <div class="form-buttons">

                            <a
                                href="{{ route('admin.manageusers.index') }}"
                                class="btn cancel-btn"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn update-btn"
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
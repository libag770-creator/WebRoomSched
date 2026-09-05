<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                /* PAGE TITLE */

                .page-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .page-subtitle {
                    color: #777;
                    margin-bottom: 20px;
                }


                /* PANEL */

                .panel {
                    background: #fff;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    overflow: hidden;
                    margin-bottom: 20px;
                }


                /* TOOLBAR */

                .toolbar {
                    padding: 15px;
                    display: flex;
                    gap: 10px;
                    align-items: center;
                    border-bottom: 1px solid #ddd;
                    flex-wrap: wrap;
                }


                /* ADD USER BUTTON */

                .add-btn {
                    padding: 10px 18px;
                    background: #2e7d32;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    text-decoration: none;
                    font-weight: bold;
                    display: inline-block;
                }

                .add-btn:hover {
                    background: #256628;
                }


                /* SUCCESS MESSAGE */

                .success-message {
                    padding: 12px 15px;
                    margin: 15px;
                    background: #d4edda;
                    color: #155724;
                    border-radius: 5px;
                }


                /* TABLE */

                .table-container {
                    width: 100%;
                    overflow-x: auto;
                }

                .users-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .users-table th {
                    background: #f5f5f5;
                    padding: 12px 15px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                    font-weight: bold;
                }

                .users-table td {
                    padding: 12px 15px;
                    border-bottom: 1px solid #eee;
                }

                .users-table tr:hover {
                    background: #fafafa;
                }


                /* ROLE */

                .role-badge {
                    padding: 5px 10px;
                    border-radius: 15px;
                    background: #e8f5e9;
                    color: #2e7d32;
                    font-size: 13px;
                    font-weight: bold;
                }


                /* ACTION BUTTONS */

                .action-btn {
                    padding: 7px 12px;
                    border: none;
                    border-radius: 5px;
                    color: white;
                    cursor: pointer;
                    text-decoration: none;
                    font-size: 13px;
                    display: inline-block;
                    margin-right: 4px;
                }


                /* EDIT */

                .edit-btn {
                    background: #2e7d32;
                }

                .edit-btn:hover {
                    background: #256628;
                }


                /* RESET */

                .reset-btn {
                    background: #777;
                }

                .reset-btn:hover {
                    background: #666;
                }


                /* DELETE */

                .delete-btn {
                    background: #c62828;
                }

                .delete-btn:hover {
                    background: #a51f1f;
                }


                /* MOBILE */

                @media (max-width: 700px) {

                    .users-table th,
                    .users-table td {
                        padding: 8px;
                        font-size: 13px;
                    }

                    .action-btn {
                        margin-bottom: 5px;
                    }

                }

            </style>


            <!-- TITLE -->

            <div class="page-title">
                Manage Users
            </div>

            <div class="page-subtitle">
                Manage users, accounts, and access roles.
            </div>


            <!-- PANEL -->

            <div class="panel">


                <!-- TOOLBAR -->

                <div class="toolbar">

                    <a
                        href="{{ route('admin.manageusers.create') }}"
                        class="add-btn"
                    >
                        + Add User
                    </a>

                </div>


                <!-- SUCCESS -->

                @if(session('success'))

                    <div class="success-message">
                        {{ session('success') }}
                    </div>

                @endif


                <!-- TABLE -->

                <div class="table-container">

                    <table class="users-table">

                        <thead>

                            <tr>

                                <th>Name</th>

                                <th>Username</th>

                                <th>Email</th>

                                <th>Role</th>

                                <th>Department</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($users as $user)

                                <tr>

                                    <td>
                                        {{ $user->name }}
                                    </td>

                                    <td>
                                        {{ $user->username }}
                                    </td>

                                    <td>
                                        {{ $user->email }}
                                    </td>

                                    <td>

                                        <span class="role-badge">
                                            {{ ucfirst($user->role) }}
                                        </span>

                                    </td>
                                    <td>

    @if($user->department)

        <span class="role-badge">
            {{ $user->department->code }}
        </span>

    @else

        <span style="color:#999;">
            Not Assigned
        </span>

    @endif

</td>
                                    <td>


                                        <!-- EDIT -->

                                        <a
                                            href="{{ route('admin.manageusers.edit', $user->id) }}"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <!-- RESET PASSWORD -->

                                        <a
                                            href="{{ route('admin.manageusers.reset', $user->id) }}"
                                            class="action-btn reset-btn"
                                        >
                                            Reset Password
                                        </a>


                                        <!-- DELETE -->

                                        <form
                                            action="{{ route('admin.manageusers.delete', $user->id) }}"
                                            method="POST"
                                            style="display:inline;"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete-btn"
                                                onclick="return confirm('Delete this user?')"
                                            >
                                                Delete
                                            </button>

                                        </form>


                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>
 @include('footerheader.footer')
</div>

<div class="wrapper">

    <?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main-layout">

        <?php echo $__env->make('admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="content">

            <style>
                .mu-page {
                    max-width: 1600px;
                    margin: 0 auto;
                    padding: 28px 24px;
                    color: #172b3a;
                }

                .mu-page * {
                    box-sizing: border-box;
                }

                .mu-heading {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 18px;
                    flex-wrap: wrap;
                    margin-bottom: 22px;
                }

                .mu-title {
                    font-size: 27px;
                    font-weight: 750;
                    margin: 0 0 5px;
                }

                .mu-subtitle {
                    margin: 0;
                    color: #718096;
                    font-size: 14px;
                }

                .mu-heading-actions {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .mu-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    padding: 9px 13px;
                    border: 1px solid transparent;
                    border-radius: 8px;
                    font-size: 12px;
                    font-weight: 700;
                    cursor: pointer;
                    text-decoration: none;
                    white-space: nowrap;
                    transition: background .18s, transform .18s;
                }

                .mu-btn:hover {
                    transform: translateY(-1px);
                }

                .mu-primary {
                    background: #2563eb;
                    color: #fff;
                }

                .mu-primary:hover {
                    background: #1d4ed8;
                }

                .mu-secondary {
                    background: #e8edf4;
                    color: #24364b;
                }

                .mu-secondary:hover {
                    background: #dce4ef;
                }

                .mu-success {
                    background: #dcfce7;
                    color: #166534;
                }

                .mu-warning {
                    background: #fef3c7;
                    color: #92400e;
                }

                .mu-danger {
                    background: #fee2e2;
                    color: #b91c1c;
                }

                .mu-neutral {
                    background: #f1f5f9;
                    color: #334155;
                }

                .mu-panel {
                    background: #fff;
                    border: 1px solid #dce5ec;
                    border-radius: 15px;
                    box-shadow: 0 5px 22px rgba(15, 23, 42, .045);
                    overflow: hidden;
                    margin-bottom: 24px;
                }

                .mu-panel-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 16px;
                    padding: 18px 20px;
                    border-bottom: 1px solid #e5ebf0;
                }

                .mu-panel-header h2 {
                    font-size: 16px;
                    font-weight: 750;
                    margin: 0 0 4px;
                }

                .mu-count {
                    color: #718096;
                    font-size: 12px;
                }

                .mu-search {
                    width: 270px;
                    max-width: 100%;
                    padding: 11px 13px;
                    border: 1px solid #d4dfe7;
                    border-radius: 9px;
                    font-size: 13px;
                    outline: none;
                }

                .mu-search:focus,
                .mu-input:focus,
                .mu-select:focus {
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
                }

                .mu-table-wrap {
                    width: 100%;
                    overflow-x: auto;
                }

                .mu-table {
                    width: 100%;
                    min-width: 1050px;
                    border-collapse: collapse;
                }

                .mu-table th {
                    padding: 13px 15px;
                    background: #f4f7f6;
                    color: #526476;
                    text-align: left;
                    text-transform: uppercase;
                    letter-spacing: .035em;
                    font-size: 11px;
                    font-weight: 750;
                    white-space: nowrap;
                }

                .mu-table td {
                    padding: 14px 15px;
                    border-top: 1px solid #e8edf1;
                    vertical-align: middle;
                    font-size: 13px;
                }

                .mu-table tbody tr:hover {
                    background: #f8fbfa;
                }

                .mu-user-name {
                    display: block;
                    font-weight: 750;
                    color: #172b3a;
                    margin-bottom: 4px;
                }

                .mu-muted {
                    display: block;
                    color: #8190a0;
                    font-size: 11px;
                    overflow-wrap: anywhere;
                    margin-top: 3px;
                }

                .mu-email {
                    overflow-wrap: anywhere;
                    min-width: 145px;
                }

                .mu-role,
                .mu-status {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 20px;
                    padding: 5px 10px;
                    font-size: 11px;
                    font-weight: 750;
                    white-space: nowrap;
                }

                .mu-role {
                    background: #e0e7ff;
                    color: #3730a3;
                }

                .mu-status-active {
                    background: #dcfce7;
                    color: #166534;
                }

                .mu-status-inactive {
                    background: #fee2e2;
                    color: #991b1b;
                }

                .mu-actions {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    flex-wrap: wrap;
                    min-width: 245px;
                }

                .mu-actions .mu-btn {
                    padding: 7px 9px;
                    font-size: 11px;
                }

                .mu-alert {
                    margin: 15px 20px;
                    padding: 12px 14px;
                    border-radius: 9px;
                    font-size: 13px;
                }

                .mu-alert-success {
                    color: #166534;
                    background: #dcfce7;
                    border: 1px solid #bbf7d0;
                }

                .mu-alert-error {
                    color: #991b1b;
                    background: #fee2e2;
                    border: 1px solid #fecaca;
                }

                .mu-empty {
                    text-align: center;
                    padding: 38px 20px !important;
                    color: #718096;
                }

                /* MODALS */

                .mu-modal {
                    display: none;
                    position: fixed;
                    inset: 0;
                    z-index: 9999;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                    background: rgba(15, 23, 42, .56);
                    backdrop-filter: blur(3px);
                    overflow-y: auto;
                }

                .mu-modal.mu-open {
                    display: flex;
                }

                .mu-modal-card {
                    width: 100%;
                    max-width: 560px;
                    max-height: calc(100vh - 40px);
                    overflow-y: auto;
                    background: #fff;
                    border-radius: 15px;
                    box-shadow: 0 24px 80px rgba(0, 0, 0, .2);
                    animation: muAppear .18s ease-out;
                }

                .mu-modal-wide {
                    max-width: 700px;
                }

                @keyframes muAppear {
                    from {
                        opacity: 0;
                        transform: translateY(8px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .mu-modal-head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 12px;
                    padding: 19px 22px;
                    border-bottom: 1px solid #e5ebf0;
                }

                .mu-modal-head h2 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 750;
                }

                .mu-close {
                    width: 34px;
                    height: 34px;
                    border: none;
                    border-radius: 8px;
                    background: #f1f5f9;
                    color: #334155;
                    font-size: 22px;
                    cursor: pointer;
                }

                .mu-modal-body {
                    padding: 22px;
                }

                .mu-form-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 16px;
                }

                .mu-field {
                    display: flex;
                    flex-direction: column;
                    gap: 7px;
                    min-width: 0;
                }

                .mu-field-full {
                    grid-column: 1 / -1;
                }

                .mu-field label {
                    font-size: 12px;
                    font-weight: 750;
                    color: #334155;
                }

                .mu-input,
                .mu-select {
                    width: 100%;
                    min-height: 42px;
                    padding: 10px 12px;
                    border: 1px solid #d5dfe7;
                    border-radius: 8px;
                    background: #fff;
                    font-size: 13px;
                    outline: none;
                }

                .mu-help {
                    color: #8190a0;
                    font-size: 11px;
                    line-height: 1.5;
                }

                .mu-modal-footer {
                    display: flex;
                    justify-content: flex-end;
                    flex-wrap: wrap;
                    gap: 9px;
                    padding: 16px 22px;
                    border-top: 1px solid #e5ebf0;
                    background: #f8fafc;
                }

                .mu-error {
                    color: #b91c1c;
                    font-size: 12px;
                    margin-top: 3px;
                }

                .mu-password-wrap {
                    display: flex;
                    gap: 8px;
                }

                .mu-password-wrap .mu-input {
                    flex: 1;
                    min-width: 0;
                }

                @media (max-width: 760px) {
                    .mu-page {
                        padding: 18px 12px;
                    }

                    .mu-title {
                        font-size: 23px;
                    }

                    .mu-heading {
                        align-items: flex-start;
                    }

                    .mu-heading-actions {
                        width: 100%;
                    }

                    .mu-panel-header {
                        align-items: stretch;
                    }

                    .mu-search {
                        width: 100%;
                    }

                    .mu-form-grid {
                        grid-template-columns: 1fr;
                    }

                    .mu-field-full {
                        grid-column: auto;
                    }

                    .mu-modal {
                        padding: 10px;
                    }

                    .mu-modal-card {
                        max-height: calc(100vh - 20px);
                    }
                }
            </style>

            <div class="mu-page">

                <div class="mu-heading">
                    <div>
                        <h1 class="mu-title">Manage Users</h1>
                        <p class="mu-subtitle">
                            Add, edit, activate, deactivate, archive, restore,
                            and reset user passwords.
                        </p>
                    </div>

                    <div class="mu-heading-actions">
                        <button type="button"
                                class="mu-btn mu-primary"
                                onclick="muOpen('muAddModal')">
                            + Add User
                        </button>

                        <button type="button"
                                class="mu-btn mu-secondary"
                                onclick="muOpen('muArchiveModal')">
                            Archived Users (<?php echo e(isset($archivedUsers) ? $archivedUsers->count() : 0); ?>)
                        </button>
                    </div>
                </div>

                <?php if(session('success')): ?>
                    <div class="mu-alert mu-alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mu-alert mu-alert-error">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mu-alert mu-alert-error">
                        <strong>Please correct the following:</strong>
                        <ul style="margin:8px 0 0;padding-left:20px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <section class="mu-panel">

                    <div class="mu-panel-header">
                        <div>
                            <h2>Current Users</h2>
                            <span class="mu-count">
                                <?php echo e($users->count()); ?> account(s)
                            </span>
                        </div>

                        <input type="search"
                               id="muSearch"
                               class="mu-search"
                               placeholder="Search name, username, email, role..."
                               aria-label="Search users">
                    </div>

                    <div class="mu-table-wrap">
                        <table class="mu-table" id="muUsersTable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Username / Email</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="mu-user-row">
                                        <td>
                                            <span class="mu-user-name">
                                                <?php echo e($user->name); ?>

                                            </span>
                                            <span class="mu-muted">
                                                ID: <?php echo e($user->id); ?>

                                            </span>
                                        </td>

                                        <td>
                                            <div><?php echo e($user->username); ?></div>
                                            <span class="mu-muted mu-email">
                                                <?php echo e($user->email); ?>

                                            </span>
                                        </td>

                                        <td>
                                            <?php if($user->department): ?>
                                                <?php echo e($user->department->name
                                                    ?? $user->department->code
                                                    ?? 'Assigned'); ?>

                                            <?php else: ?>
                                                <span class="mu-muted">Not assigned</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <span class="mu-role">
                                                <?php echo e(ucfirst($user->role)); ?>

                                            </span>
                                        </td>

                                        <td>
                                            <?php if(($user->status ?? 'active') === 'inactive'): ?>
                                                <span class="mu-status mu-status-inactive">
                                                    Inactive
                                                </span>
                                            <?php else: ?>
                                                <span class="mu-status mu-status-active">
                                                    Active
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="mu-actions">

                                                <button type="button"
                                                        class="mu-btn mu-secondary"
                                                        onclick="muOpenEdit(this)"
                                                        data-id="<?php echo e($user->id); ?>"
                                                        data-name="<?php echo e($user->name); ?>"
                                                        data-username="<?php echo e($user->username); ?>"
                                                        data-email="<?php echo e($user->email); ?>"
                                                        data-role="<?php echo e($user->role); ?>"
                                                        data-department="<?php echo e($user->department_id); ?>"
                                                        data-status="<?php echo e($user->status ?? 'active'); ?>">
                                                    Edit
                                                </button>

                                                <button type="button"
                                                        class="mu-btn mu-neutral"
                                                        onclick="muOpenReset(this)"
                                                        data-id="<?php echo e($user->id); ?>"
                                                        data-name="<?php echo e($user->name); ?>">
                                                    Reset Password
                                                </button>

                                                <form method="POST"
                                                      action="<?php echo e(route('admin.manageusers.status', $user->id)); ?>"
                                                      style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>

                                                    <button type="submit"
                                                            class="mu-btn <?php echo e(($user->status ?? 'active') === 'inactive' ? 'mu-success' : 'mu-warning'); ?>"
                                                            onclick="return confirm('Change this account status?')">
                                                        <?php echo e(($user->status ?? 'active') === 'inactive' ? 'Activate' : 'Deactivate'); ?>

                                                    </button>
                                                </form>

                                                <form method="POST"
                                                      action="<?php echo e(route('admin.manageusers.delete', $user->id)); ?>"
                                                      style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>

                                                    <button type="submit"
                                                            class="mu-btn mu-danger"
                                                            onclick="return confirm('Archive this user account? The account will be hidden from the active user list.')">
                                                        Archive
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="mu-empty">
                                            No user accounts found.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <tr id="muNoSearchResults" style="display:none;">
                                    <td colspan="6" class="mu-empty">
                                        No users match your search.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            
            <div class="mu-modal" id="muAddModal" aria-hidden="true">
                <div class="mu-modal-card">
                    <form method="POST" action="<?php echo e(route('admin.manageusers.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mu-modal-head">
                            <h2>Add User</h2>
                            <button type="button" class="mu-close"
                                    onclick="muClose('muAddModal')">&times;</button>
                        </div>

                        <div class="mu-modal-body">
                            <div class="mu-form-grid">

                                <div class="mu-field">
                                    <label for="muAddName">Full Name</label>
                                    <input class="mu-input" id="muAddName"
                                           name="name" required
                                           value="<?php echo e(old('name')); ?>">
                                </div>

                                <div class="mu-field">
                                    <label for="muAddUsername">Username</label>
                                    <input class="mu-input" id="muAddUsername"
                                           name="username" required
                                           value="<?php echo e(old('username')); ?>">
                                </div>

                                <div class="mu-field mu-field-full">
                                    <label for="muAddEmail">Email</label>
                                    <input class="mu-input" id="muAddEmail"
                                           name="email" type="email" required
                                           value="<?php echo e(old('email')); ?>">
                                </div>

                                <div class="mu-field">
                                    <label for="muAddPassword">Password</label>
                                    <div class="mu-password-wrap">
                                        <input class="mu-input" id="muAddPassword"
                                               name="password" type="password"
                                               minlength="6" required>
                                        <button type="button" class="mu-btn mu-secondary"
                                                onclick="muTogglePassword('muAddPassword', this)">
                                            Show
                                        </button>
                                    </div>
                                </div>

                                <div class="mu-field">
                                    <label for="muAddPasswordConfirmation">
                                        Confirm Password
                                    </label>
                                    <input class="mu-input"
                                           id="muAddPasswordConfirmation"
                                           name="password_confirmation"
                                           type="password" minlength="6" required>
                                </div>

                                <div class="mu-field">
                                    <label for="muAddRole">Role</label>
                                    <select class="mu-select" id="muAddRole"
                                            name="role" required
                                            onchange="muDepartmentRequired('muAddRole','muAddDepartment')">
                                        <option value="">Select role</option>
                                        <option value="admin">Admin</option>
                                        <option value="chair">Chair</option>
                                        <option value="faculty">Faculty</option>
                                    </select>
                                </div>

                                <div class="mu-field">
                                    <label for="muAddDepartment">Department</label>
                                    <select class="mu-select" id="muAddDepartment"
                                            name="department_id">
                                        <option value="">No department</option>
                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($department->id); ?>">
                                                <?php echo e($department->name ?? $department->code); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <span class="mu-help">
                                        Required for Chair and Faculty accounts.
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="mu-modal-footer">
                            <button type="button" class="mu-btn mu-secondary"
                                    onclick="muClose('muAddModal')">Cancel</button>
                            <button type="submit" class="mu-btn mu-primary">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="mu-modal" id="muEditModal" aria-hidden="true">
                <div class="mu-modal-card">
                    <form method="POST" id="muEditForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mu-modal-head">
                            <h2>Edit User</h2>
                            <button type="button" class="mu-close"
                                    onclick="muClose('muEditModal')">&times;</button>
                        </div>

                        <div class="mu-modal-body">
                            <div class="mu-form-grid">

                                <div class="mu-field">
                                    <label for="muEditName">Full Name</label>
                                    <input class="mu-input" id="muEditName"
                                           name="name" required>
                                </div>

                                <div class="mu-field">
                                    <label for="muEditUsername">Username</label>
                                    <input class="mu-input" id="muEditUsername"
                                           name="username" required>
                                </div>

                                <div class="mu-field mu-field-full">
                                    <label for="muEditEmail">Email</label>
                                    <input class="mu-input" id="muEditEmail"
                                           name="email" type="email" required>
                                </div>

                                <div class="mu-field">
                                    <label for="muEditRole">Role</label>
                                    <select class="mu-select" id="muEditRole"
                                            name="role" required
                                            onchange="muDepartmentRequired('muEditRole','muEditDepartment')">
                                        <option value="admin">Admin</option>
                                        <option value="chair">Chair</option>
                                        <option value="faculty">Faculty</option>
                                    </select>
                                </div>

                                <div class="mu-field">
                                    <label for="muEditDepartment">Department</label>
                                    <select class="mu-select" id="muEditDepartment"
                                            name="department_id">
                                        <option value="">No department</option>
                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($department->id); ?>">
                                                <?php echo e($department->name ?? $department->code); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="mu-field mu-field-full">
                                    <label for="muEditStatus">Account Status</label>
                                    <select class="mu-select" id="muEditStatus"
                                            name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="mu-modal-footer">
                            <button type="button" class="mu-btn mu-secondary"
                                    onclick="muClose('muEditModal')">Cancel</button>
                            <button type="submit" class="mu-btn mu-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="mu-modal" id="muResetModal" aria-hidden="true">
                <div class="mu-modal-card">
                    <form method="POST" id="muResetForm">
                        <?php echo csrf_field(); ?>

                        <div class="mu-modal-head">
                            <h2>Reset Password</h2>
                            <button type="button" class="mu-close"
                                    onclick="muClose('muResetModal')">&times;</button>
                        </div>

                        <div class="mu-modal-body">
                            <p style="margin-top:0;color:#64748b;font-size:13px;">
                                Reset password for
                                <strong id="muResetUserName"></strong>.
                            </p>

                            <div class="mu-field" style="margin-bottom:16px;">
                                <label for="muResetPassword">New Password</label>
                                <div class="mu-password-wrap">
                                    <input class="mu-input" id="muResetPassword"
                                           name="password" type="password"
                                           minlength="6" required>
                                    <button type="button" class="mu-btn mu-secondary"
                                            onclick="muTogglePassword('muResetPassword', this)">
                                        Show
                                    </button>
                                </div>
                            </div>

                            <div class="mu-field">
                                <label for="muResetConfirmation">Confirm New Password</label>
                                <input class="mu-input" id="muResetConfirmation"
                                       name="password_confirmation"
                                       type="password" minlength="6" required>
                            </div>
                        </div>

                        <div class="mu-modal-footer">
                            <button type="button" class="mu-btn mu-secondary"
                                    onclick="muClose('muResetModal')">Cancel</button>
                            <button type="submit" class="mu-btn mu-primary">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="mu-modal" id="muArchiveModal" aria-hidden="true">
                <div class="mu-modal-card mu-modal-wide">
                    <div class="mu-modal-head">
                        <h2>Archived Users</h2>
                        <button type="button" class="mu-close"
                                onclick="muClose('muArchiveModal')">&times;</button>
                    </div>

                    <div class="mu-modal-body" style="padding:0;">
                        <div class="mu-table-wrap">
                            <table class="mu-table" style="min-width:600px;">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Username / Email</th>
                                        <th>Department</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = ($archivedUsers ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archivedUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <span class="mu-user-name">
                                                    <?php echo e($archivedUser->name); ?>

                                                </span>
                                                <span class="mu-muted">
                                                    ID: <?php echo e($archivedUser->id); ?>

                                                </span>
                                            </td>

                                            <td>
                                                <?php echo e($archivedUser->username); ?>

                                                <span class="mu-muted">
                                                    <?php echo e($archivedUser->email); ?>

                                                </span>
                                            </td>

                                            <td>
                                                <?php echo e($archivedUser->department->name
                                                    ?? $archivedUser->department->code
                                                    ?? 'Not assigned'); ?>

                                            </td>

                                            <td>
                                                <form method="POST"
                                                      action="<?php echo e(route('admin.manageusers.restore', $archivedUser->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>

                                                    <button type="submit"
                                                            class="mu-btn mu-success"
                                                            onclick="return confirm('Restore this user account?')">
                                                        Restore
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="mu-empty">
                                                No archived users.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mu-modal-footer">
                        <button type="button" class="mu-btn mu-secondary"
                                onclick="muClose('muArchiveModal')">Close</button>
                    </div>
                </div>
            </div>

            <script>
                function muOpen(id) {
                    const modal = document.getElementById(id);
                    if (!modal) return;

                    modal.classList.add('mu-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function muClose(id) {
                    const modal = document.getElementById(id);
                    if (!modal) return;

                    modal.classList.remove('mu-open');
                    modal.setAttribute('aria-hidden', 'true');

                    if (!document.querySelector('.mu-modal.mu-open')) {
                        document.body.style.overflow = '';
                    }
                }

                document.querySelectorAll('.mu-modal').forEach(function(modal) {
                    modal.addEventListener('click', function(event) {
                        if (event.target === modal) {
                            muClose(modal.id);
                        }
                    });
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        document.querySelectorAll('.mu-modal.mu-open').forEach(function(modal) {
                            muClose(modal.id);
                        });
                    }
                });

                function muOpenEdit(button) {
                    const id = button.dataset.id;
                    const base = <?php echo json_encode(url('/admin/manageusers'), 15, 512) ?>;

                    document.getElementById('muEditForm').action = base + '/' + id;
                    document.getElementById('muEditName').value = button.dataset.name || '';
                    document.getElementById('muEditUsername').value = button.dataset.username || '';
                    document.getElementById('muEditEmail').value = button.dataset.email || '';
                    document.getElementById('muEditRole').value = button.dataset.role || 'faculty';
                    document.getElementById('muEditDepartment').value = button.dataset.department || '';
                    document.getElementById('muEditStatus').value = button.dataset.status || 'active';

                    muDepartmentRequired('muEditRole', 'muEditDepartment');
                    muOpen('muEditModal');
                }

                function muOpenReset(button) {
                    const id = button.dataset.id;
                    const base = <?php echo json_encode(url('/admin/manageusers'), 15, 512) ?>;

                    document.getElementById('muResetForm').action =
                        base + '/' + id + '/reset-password';

                    document.getElementById('muResetUserName').textContent =
                        button.dataset.name || 'this user';

                    document.getElementById('muResetPassword').value = '';
                    document.getElementById('muResetConfirmation').value = '';

                    muOpen('muResetModal');
                }

                function muDepartmentRequired(roleId, departmentId) {
                    const role = document.getElementById(roleId);
                    const department = document.getElementById(departmentId);

                    if (!role || !department) return;

                    const required = ['chair', 'faculty'].includes(role.value);
                    department.required = required;

                    if (role.value === 'admin') {
                        department.value = '';
                    }
                }

                function muTogglePassword(inputId, button) {
                    const input = document.getElementById(inputId);
                    if (!input) return;

                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.textContent = isPassword ? 'Hide' : 'Show';
                }

                const muSearch = document.getElementById('muSearch');

                if (muSearch) {
                    muSearch.addEventListener('input', function() {
                        const term = this.value.toLowerCase().trim();
                        const rows = document.querySelectorAll(
                            '#muUsersTable tbody tr.mu-user-row'
                        );

                        let visibleCount = 0;

                        rows.forEach(function(row) {
                            const matches = row.textContent.toLowerCase().includes(term);
                            row.style.display = matches ? '' : 'none';

                            if (matches) visibleCount++;
                        });

                        const noResults = document.getElementById('muNoSearchResults');

                        if (noResults) {
                            noResults.style.display =
                                visibleCount === 0 && rows.length > 0 ? '' : 'none';
                        }
                    });
                }

                // Reopen a modal if the controller redirects back with validation errors.
                <?php if($errors->any()): ?>
                    <?php if(old('_method') === 'PUT'): ?>
                        muOpen('muEditModal');
                    <?php elseif(request()->isMethod('post') && old('password_confirmation') !== null): ?>
                        muOpen('muResetModal');
                    <?php else: ?>
                        muOpen('muAddModal');
                    <?php endif; ?>
                <?php endif; ?>

            </script>

        </main>
    </div>

    <?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/admin/manageusers.blade.php ENDPATH**/ ?>
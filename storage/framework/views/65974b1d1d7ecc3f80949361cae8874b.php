
<?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="wrapper">

    <div class="main-layout">

        <?php echo $__env->make('admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="content">

            <style>

                /* =========================================================
                   PROFESSIONAL ADMIN DASHBOARD
                ========================================================= */

                .admin-dashboard {
                    padding: 30px;
                    max-width: 1500px;
                    margin: 0 auto;
                    background: #f7f8fa;
                    min-height: calc(100vh - 70px);
                }

                /* =========================================================
                   HEADER
                ========================================================= */

                .dashboard-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-end;
                    margin-bottom: 30px;
                }

                .dashboard-header-left h1 {
                    margin: 0;
                    font-size: 30px;
                    font-weight: 700;
                    color: #1f2937;
                    letter-spacing: -.5px;
                }

                .dashboard-header-left p {
                    margin: 8px 0 0;
                    color: #6b7280;
                    font-size: 14px;
                }

                .dashboard-date {
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 10px 15px;
                    font-size: 13px;
                    color: #6b7280;
                }

                .dashboard-date i {
                    margin-right: 7px;
                    color: #198754;
                }


                /* =========================================================
                   SUMMARY CARDS
                ========================================================= */

                .summary-cards {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                    margin-bottom: 30px;
                }

                .summary-card {
                    position: relative;
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 22px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.04);
                    overflow: hidden;
                    transition: .2s ease;
                }

                .summary-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 16px rgba(0,0,0,.07);
                }

                .summary-card::after {
                    content: "";
                    position: absolute;
                    right: -25px;
                    top: -25px;
                    width: 90px;
                    height: 90px;
                    border-radius: 50%;
                    background: rgba(25,135,84,.05);
                }

                .summary-card-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    color: #6b7280;
                    font-size: 13px;
                    font-weight: 600;
                }

                .summary-card-header i {
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #edf8f2;
                    color: #198754;
                    border-radius: 9px;
                    font-size: 17px;
                }

                .summary-number {
                    margin-top: 17px;
                    font-size: 32px;
                    font-weight: 700;
                    color: #1f2937;
                    line-height: 1;
                }

                .summary-description {
                    margin-top: 9px;
                    font-size: 12px;
                    color: #9ca3af;
                }


                /* =========================================================
                   MAIN GRID
                ========================================================= */

                .dashboard-grid {
                    display: grid;
                    grid-template-columns: 1.7fr 1fr;
                    gap: 25px;
                    align-items: start;
                }


                /* =========================================================
                   PANELS
                ========================================================= */

                .dashboard-panel {
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.04);
                    overflow: hidden;
                    margin-bottom: 25px;
                }

                .panel-header {
                    padding: 20px 22px;
                    border-bottom: 1px solid #eef0f2;
                }

                .panel-header h2 {
                    margin: 0;
                    font-size: 17px;
                    font-weight: 700;
                    color: #1f2937;
                }

                .panel-header p {
                    margin: 5px 0 0;
                    color: #9ca3af;
                    font-size: 12px;
                }

                .panel-body {
                    padding: 22px;
                }


                /* =========================================================
                   SYSTEM OVERVIEW
                ========================================================= */

                .overview-list {
                    display: flex;
                    flex-direction: column;
                }

                .overview-item {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 15px 0;
                    border-bottom: 1px solid #f0f1f3;
                }

                .overview-item:first-child {
                    padding-top: 0;
                }

                .overview-item:last-child {
                    border-bottom: none;
                    padding-bottom: 0;
                }

                .overview-left {
                    display: flex;
                    align-items: center;
                    gap: 13px;
                }

                .overview-icon {
                    width: 38px;
                    height: 38px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #f3f7f5;
                    color: #198754;
                    border-radius: 8px;
                    font-size: 15px;
                }

                .overview-info strong {
                    display: block;
                    font-size: 13px;
                    color: #374151;
                    margin-bottom: 3px;
                }

                .overview-info span {
                    display: block;
                    font-size: 11px;
                    color: #9ca3af;
                }

                .overview-value {
                    font-size: 16px;
                    font-weight: 700;
                    color: #1f2937;
                }


                /* =========================================================
                   WORKFLOW
                ========================================================= */

                .workflow {
                    position: relative;
                }

                .step {
                    position: relative;
                    display: flex;
                    gap: 15px;
                    padding-bottom: 23px;
                }

                .step:last-child {
                    padding-bottom: 0;
                }

                .step:not(:last-child)::before {
                    content: "";
                    position: absolute;
                    left: 14px;
                    top: 29px;
                    bottom: 0;
                    width: 1px;
                    background: #dfe5e1;
                }

                .step-number {
                    position: relative;
                    z-index: 2;
                    min-width: 29px;
                    width: 29px;
                    height: 29px;
                    border-radius: 50%;
                    background: #198754;
                    color: #fff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 12px;
                    font-weight: 700;
                }

                .step-content {
                    padding-top: 2px;
                }

                .step-content strong {
                    display: block;
                    font-size: 13px;
                    color: #374151;
                    margin-bottom: 4px;
                }

                .step-content span {
                    display: block;
                    font-size: 12px;
                    color: #9ca3af;
                    line-height: 1.5;
                }


                /* =========================================================
                   SYSTEM STATUS
                ========================================================= */

                .status-box {
                    display: flex;
                    align-items: center;
                    gap: 14px;
                    padding: 15px;
                    border: 1px solid #e5e7eb;
                    border-radius: 9px;
                    background: #fafafa;
                }

                .status-indicator {
                    width: 11px;
                    height: 11px;
                    border-radius: 50%;
                    background: #198754;
                    box-shadow: 0 0 0 4px rgba(25,135,84,.12);
                    flex-shrink: 0;
                }

                .status-content strong {
                    display: block;
                    color: #374151;
                    font-size: 13px;
                    margin-bottom: 3px;
                }

                .status-content span {
                    color: #9ca3af;
                    font-size: 11px;
                }


                /* =========================================================
                   ADMIN NOTICE
                ========================================================= */

                .notice {
                    display: flex;
                    gap: 14px;
                    padding: 16px;
                    border-radius: 9px;
                    background: #f7faf8;
                    border: 1px solid #dceee3;
                }

                .notice-icon {
                    width: 35px;
                    height: 35px;
                    border-radius: 8px;
                    background: #e7f5ed;
                    color: #198754;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .notice-content strong {
                    display: block;
                    font-size: 13px;
                    color: #374151;
                    margin-bottom: 5px;
                }

                .notice-content p {
                    margin: 0;
                    font-size: 12px;
                    color: #6b7280;
                    line-height: 1.6;
                }


                /* =========================================================
                   FOOTER INFORMATION
                ========================================================= */

                .dashboard-footer {
                    margin-top: 5px;
                    text-align: center;
                    color: #9ca3af;
                    font-size: 11px;
                }


                /* =========================================================
                   RESPONSIVE
                ========================================================= */

                @media(max-width: 1100px) {

                    .summary-cards {
                        grid-template-columns: repeat(3, 1fr);
                    }

                    .dashboard-grid {
                        grid-template-columns: 1fr;
                    }

                }

                @media(max-width: 750px) {

                    .admin-dashboard {
                        padding: 20px 15px;
                    }

                    .dashboard-header {
                        display: block;
                    }

                    .dashboard-date {
                        display: inline-block;
                        margin-top: 15px;
                    }

                    .summary-cards {
                        grid-template-columns: 1fr;
                    }

                }

            </style>


            <div class="admin-dashboard">


                <!-- =====================================================
                     HEADER
                ====================================================== -->

                <div class="dashboard-header">

                    <div class="dashboard-header-left">

                        <h1>
                            Welcome, <?php echo e(Auth::user()->name); ?>!
                        </h1>

                        <p>
                            Here's an overview of your classroom scheduling system.
                        </p>

                    </div>

                    <div class="dashboard-date">

                        <i class="fas fa-calendar-day"></i>

                        <?php echo e(date('F d, Y')); ?>


                    </div>

                </div>


                <!-- =====================================================
                     SUMMARY CARDS
                ====================================================== -->

                <div class="summary-cards">


                    <!-- TOTAL ROOMS -->

                    <div class="summary-card">

                        <div class="summary-card-header">

                            <span>Total Rooms</span>

                            <i class="fas fa-door-open"></i>

                        </div>

                        <div class="summary-number">

                            <?php echo e($totalRooms ?? 0); ?>


                        </div>

                        <div class="summary-description">

                            Registered classrooms and facilities

                        </div>

                    </div>


                    <!-- TOTAL USERS -->

                    <div class="summary-card">

                        <div class="summary-card-header">

                            <span>Total Users</span>

                            <i class="fas fa-users"></i>

                        </div>

                        <div class="summary-number">

                            <?php echo e($totalUsers ?? 0); ?>


                        </div>

                        <div class="summary-description">

                            Faculty, chairs, and administrators

                        </div>

                    </div>


                    <!-- ACTIVE SCHEDULES -->

                    <div class="summary-card">

                        <div class="summary-card-header">

                            <span>Active Schedules</span>

                            <i class="fas fa-calendar-alt"></i>

                        </div>

                        <div class="summary-number">

                            <?php echo e($activeSchedules ?? 0); ?>


                        </div>

                        <div class="summary-description">

                            Current classroom schedules

                        </div>

                    </div>


                </div>


                <!-- =====================================================
                     MAIN CONTENT
                ====================================================== -->

                <div class="dashboard-grid">


                    <!-- =================================================
                         LEFT COLUMN
                    ================================================== -->

                    <div>


                        <!-- SYSTEM OVERVIEW -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    System Overview
                                </h2>

                                <p>
                                    Current information managed by the system.
                                </p>

                            </div>


                            <div class="panel-body">

                                <div class="overview-list">


                                    <div class="overview-item">

                                        <div class="overview-left">

                                            <div class="overview-icon">
                                                <i class="fas fa-door-open"></i>
                                            </div>

                                            <div class="overview-info">

                                                <strong>
                                                    Classroom Management
                                                </strong>

                                                <span>
                                                    Rooms currently registered in the system
                                                </span>

                                            </div>

                                        </div>

                                        <div class="overview-value">
                                            <?php echo e($totalRooms ?? 0); ?>

                                        </div>

                                    </div>


                                    <div class="overview-item">

                                        <div class="overview-left">

                                            <div class="overview-icon">
                                                <i class="fas fa-user-group"></i>
                                            </div>

                                            <div class="overview-info">

                                                <strong>
                                                    User Accounts
                                                </strong>

                                                <span>
                                                    Registered users with system access
                                                </span>

                                            </div>

                                        </div>

                                        <div class="overview-value">
                                            <?php echo e($totalUsers ?? 0); ?>

                                        </div>

                                    </div>


                                    <div class="overview-item">

                                        <div class="overview-left">

                                            <div class="overview-icon">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>

                                            <div class="overview-info">

                                                <strong>
                                                    Classroom Schedules
                                                </strong>

                                                <span>
                                                    Active schedules available for viewing
                                                </span>

                                            </div>

                                        </div>

                                        <div class="overview-value">
                                            <?php echo e($activeSchedules ?? 0); ?>

                                        </div>

                                    </div>


                                </div>

                            </div>

                        </div>


                        <!-- ADMIN WORKFLOW -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    Administrative Workflow
                                </h2>

                                <p>
                                    Recommended process for maintaining the scheduling system.
                                </p>

                            </div>


                            <div class="panel-body workflow">


                                <div class="step">

                                    <div class="step-number">
                                        1
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Maintain User Accounts
                                        </strong>

                                        <span>
                                            Review faculty, department chair, and administrator
                                            accounts and ensure that account information is current.
                                        </span>

                                    </div>

                                </div>


                                <div class="step">

                                    <div class="step-number">
                                        2
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Maintain Classroom Information
                                        </strong>

                                        <span>
                                            Keep classroom names, buildings, departments, and
                                            room assignments accurate.
                                        </span>

                                    </div>

                                </div>


                                <div class="step">

                                    <div class="step-number">
                                        3
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Monitor Schedules
                                        </strong>

                                        <span>
                                            Review classroom schedules and make sure that
                                            scheduling information remains accurate.
                                        </span>

                                    </div>

                                </div>


                                <div class="step">

                                    <div class="step-number">
                                        4
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Manage Room Assignments
                                        </strong>

                                        <span>
                                            Review room assignments and perform reassignment
                                            when classroom requirements change.
                                        </span>

                                    </div>

                                </div>


                            </div>

                        </div>


                    </div>


                    <!-- =================================================
                         RIGHT COLUMN
                    ================================================== -->

                    <div>


                        <!-- SYSTEM STATUS -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    System Status
                                </h2>

                                <p>
                                    Current status of the scheduling system.
                                </p>

                            </div>


                            <div class="panel-body">

                                <div class="status-box">

                                    <div class="status-indicator"></div>

                                    <div class="status-content">

                                        <strong>
                                            System Operational
                                        </strong>

                                        <span>
                                            Classroom scheduling services are currently available.
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- ADMIN NOTICE -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    Administrator Notice
                                </h2>

                            </div>


                            <div class="panel-body">

                                <div class="notice">

                                    <div class="notice-icon">

                                        <i class="fas fa-info"></i>

                                    </div>

                                    <div class="notice-content">

                                        <strong>
                                            Keep system information accurate
                                        </strong>

                                        <p>
                                            Regularly review users, classrooms, schedules,
                                            and room assignments to ensure faculty and
                                            department chairs receive reliable information.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- SYSTEM RESPONSIBILITY -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    Administrator Responsibilities
                                </h2>

                                <p>
                                    Key areas managed from the admin panel.
                                </p>

                            </div>


                            <div class="panel-body">

                                <div class="overview-list">


                                    <div class="overview-item">

                                        <div class="overview-left">

                                            <div class="overview-icon">
                                                <i class="fas fa-users-cog"></i>
                                            </div>

                                            <div class="overview-info">

                                                <strong>
                                                    User Management
                                                </strong>

                                                <span>
                                                    Manage system accounts
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="overview-item">

                                        <div class="overview-left">

                                            <div class="overview-icon">
                                                <i class="fas fa-calendar-days"></i>
                                            </div>

                                            <div class="overview-info">

                                                <strong>
                                                    Schedule Monitoring
                                                </strong>

                                                <span>
                                                    Review classroom schedules
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="overview-item">

                                        <div class="overview-left">

                                            <div class="overview-icon">
                                                <i class="fas fa-arrows-rotate"></i>
                                            </div>

                                            <div class="overview-info">

                                                <strong>
                                                    Room Reassignment
                                                </strong>

                                                <span>
                                                    Manage classroom transfers
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                </div>

                            </div>

                        </div>


                    </div>


                </div>


                <div class="dashboard-footer">

                    WebRoomSched Administration System

                </div>


            </div>


        </main>

    </div>

</div>

<?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>
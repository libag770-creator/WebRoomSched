<?php
    use Illuminate\Support\Facades\Auth;
?>

<?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="wrapper">

    <style>

        /* =========================
           DASHBOARD
        ========================= */


        .dashboard-header {
            margin-bottom: 25px;
        }

        .dashboard-header h2 {
            margin: 0 0 8px;
            font-size: 28px;
            color: #222;
        }

        .dashboard-header p {
            margin: 5px 0;
            color: #777;
        }

        .dashboard-header .welcome {
            font-size: 16px;
            color: #2e7d32;
            font-weight: 600;
        }
/* ================================
   DEPARTMENT SUMMARY CARDS
================================ */

.department-card {
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.department-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.department-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.department-card .card-header span {
    font-size: 18px;
    font-weight: 700;
}

.department-card .card-header i {
    font-size: 22px;
    color: #f9a825;
}

.department-card .card-number {
    margin-top: 12px;
    font-size: 38px;
    font-weight: 700;
    color: #2e7d32;
}

.card-description {
    margin-top: -3px;
    font-size: 14px;
    color: #777;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 18px;
    padding-top: 12px;
    border-top: 1px solid #eeeeee;
    font-size: 12px;
    color: #777;
}

.card-footer i {
    margin-right: 5px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2e7d32;
    display: inline-block;
}

        /* =========================
           SUMMARY CARDS
        ========================= */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

      .summary-card {
    background: #ffffff;
    border: 1px solid #e4e4e4;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
    transition: 0.2s;
}

.reservation-details {
    grid-template-columns: repeat(3, 1fr);
}

.detail-item {
    padding: 10px 12px;
    background: #f8f9fa;
    border-radius: 7px;
    border: 1px solid #eeeeee;
}

.detail-item strong {
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
    color: #2e7d32;
}

.detail-item strong i {
    margin-right: 5px;
}

@media(max-width: 1000px) {
    .reservation-details {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 600px) {
    .reservation-details {
        grid-template-columns: 1fr;
    }
}
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.09);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #666;
            font-size: 14px;
            font-weight: 600;
        }

        .card-header i {
            font-size: 22px;
        }

        .card-number {
            margin-top: 15px;
            font-size: 30px;
            font-weight: bold;
            color: #2e7d32;
        }


        /* =========================
           REQUEST / HISTORY CARD
        ========================= */

        .management-card {
            background: #fff;
            border: 1px solid #e2e2e2;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .management-header {
            padding: 22px 25px;
            border-bottom: 1px solid #eee;
        }

        .management-header h3 {
            margin: 0 0 5px;
            font-size: 21px;
            color: #222;
        }

        .management-header p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }


        /* =========================
           TABS
        ========================= */

        .reservation-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            background: #fafafa;
        }

        .tab-btn {
            border: none;
            background: transparent;
            padding: 15px 22px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #777;
            border-bottom: 3px solid transparent;
            transition: 0.2s;
        }

        .tab-btn:hover {
            color: #2e7d32;
            background: #f5f5f5;
        }

        .tab-btn.active {
            color: #2e7d32;
            border-bottom: 3px solid #2e7d32;
            background: #fff;
        }

        .tab-btn i {
            margin-right: 7px;
        }


        /* =========================
           TAB CONTENT
        ========================= */

        .tab-content {
            display: none;
            padding: 25px;
        }

        .tab-content.active {
            display: block;
        }


        /* =========================
           SECTION INTRO
        ========================= */

        .section-intro {
            background: #f8f9fa;
            border-left: 4px solid #f9a825;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .section-intro strong {
            display: block;
            margin-bottom: 4px;
            color: #333;
        }

        .section-intro span {
            color: #777;
            font-size: 13px;
        }


        /* =========================
           RESERVATION CARD
        ========================= */

        .reservation-card {
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            background: #fff;
            transition: 0.2s;
        }

        .reservation-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        }

        .reservation-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
        }

        .reservation-title {
            margin: 0;
            font-size: 18px;
            color: #222;
        }

        .reservation-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: #777;
        }

        .reservation-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 25px;
            margin-top: 15px;
        }

        .detail-item {
            font-size: 14px;
            color: #555;
        }

        .detail-item strong {
            color: #333;
        }


        /* =========================
           STATUS
        ========================= */

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-declined {
            background: #f8d7da;
            color: #721c24;
        }


        /* =========================
           ACTION BUTTONS
        ========================= */

        .reservation-actions {
            margin-top: 18px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        .btn-approve,
        .btn-decline {
            border: none;
            padding: 9px 17px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
        }

        .btn-approve {
            background: #2e7d32;
            color: white;
        }

        .btn-approve:hover {
            background: #256b29;
        }

        .btn-decline {
            background: #dc3545;
            color: white;
        }

        .btn-decline:hover {
            background: #bb2d3b;
        }


        /* =========================
           SWAP HISTORY
        ========================= */

        .swap-card {
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            background: #fff;
        }

        .swap-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .swap-card-header h4 {
            margin: 0;
            font-size: 17px;
        }

        .swap-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 25px;
        }

        .swap-detail {
            font-size: 14px;
            color: #555;
        }

        .swap-detail strong {
            color: #333;
        }


        /* =========================
           EMPTY STATE
        ========================= */

        .empty-room {
            text-align: center;
            padding: 50px 20px;
            color: #888;
        }

        .empty-room i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #bbb;
        }

        .empty-room h3 {
            margin: 0 0 5px;
            color: #555;
        }

        .empty-room p {
            margin: 0;
            font-size: 14px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width: 900px) {

            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .reservation-details,
            .swap-details {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width: 600px) {

            .content {
                padding: 20px;
            }

            .reservation-tabs {
                overflow-x: auto;
            }

            .tab-btn {
                white-space: nowrap;
            }

            .reservation-top,
            .swap-card-header {
                flex-direction: column;
            }

        }

    </style>


    <div class="main-layout">

        <?php echo $__env->make('chair.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <main class="content">


            <!-- =========================
                 DASHBOARD HEADER
            ========================= -->

            <div class="dashboard-header">

                <h2>
                    Department Chair Dashboard
                </h2>

                <p class="welcome">
                    Welcome, <?php echo e(Auth::user()?->name ?? 'Chair'); ?>!
                </p>

                <p>
                    Manage classroom schedules, room requests, and faculty room activities from one place.
                </p>

            </div>


       <!-- DEPARTMENT SUMMARY CARDS -->

<!-- =========================================================
     DEPARTMENT SUMMARY CARDS
     Automatically loads ALL departments from database
========================================================= -->

<div class="dashboard-cards">

    <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <div class="summary-card department-card">

            <div class="card-header">

                <span>
                    <?php echo e($department->name); ?>

                </span>

                <i class="fas fa-building"></i>

            </div>


            <div class="card-number">

                <?php echo e($department->rooms_count); ?>


            </div>


            <div class="card-description">

                <?php echo e($department->rooms_count == 1
                    ? 'Room'
                    : 'Rooms'); ?>


            </div>

        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <div
            class="summary-card"
            style="
                grid-column: 1 / -1;
                text-align: center;
            "
        >

            <i
                class="fas fa-building"
                style="
                    font-size: 30px;
                    color: #bbb;
                    margin-bottom: 8px;
                "
            ></i>

            <h3 style="margin: 0 0 5px;">
                No Departments
            </h3>

            <p style="margin: 0; color: #777;">
                No departments have been created yet.
            </p>

        </div>

    <?php endif; ?>

</div>


            <!-- =========================
                 MANAGEMENT CARD
            ========================= -->

            <div class="management-card">


                <!-- HEADER -->

                <div class="management-header">

                    <h3>
                        Faculty Requests & Activities
                    </h3>

                    <p>
                        Monitor room swap activity and merge requests submitted by faculty members.
                    </p>

                </div>


               <div class="reservation-tabs">


    <!-- =====================================================
         MERGE APPROVALS
    ====================================================== -->

    <button
        class="tab-btn active"
        onclick="showTab('mergeApprovals', this)"
    >

        <i class="fas fa-code-branch"></i>

        Merge Approvals

        <?php if(isset($mergeRequests) && $mergeRequests->count() > 0): ?>

            <span
                style="
                    background:#f9a825;
                    color:#fff;
                    border-radius:20px;
                    padding:2px 7px;
                    font-size:11px;
                    margin-left:5px;
                "
            >
                <?php echo e($mergeRequests->count()); ?>

            </span>

        <?php endif; ?>

    </button>


    <!-- =====================================================
         ROOM SWAP HISTORY
    ====================================================== -->

    <button
        class="tab-btn"
        onclick="showTab('swapped', this)"
    >

        <i class="fas fa-exchange-alt"></i>

        Room Swap History

    </button>

</div>

<!-- =========================================================
     MERGE APPROVALS
========================================================= -->

<div
    id="mergeApprovals"
    class="tab-content"
>

    <div class="section-intro">

        <strong>
            Merge Approval Requests
        </strong>

        <span>
            Review requests from other departments to merge
            their program with your existing class.
        </span>

    </div>


    <?php if(isset($mergeRequests) && $mergeRequests->count() > 0): ?>

        <?php $__currentLoopData = $mergeRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $merge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="reservation-card">

                <div class="reservation-top">

                    <div>

                        <h3 class="reservation-title">

                            <i
                                class="fas fa-code-branch"
                                style="color:#2e7d32;"
                            ></i>

                            <?php echo e($merge->facultySubject->course_code
                                ?? $merge->schedule->course_code
                                ?? 'Unknown Course'); ?>


                        </h3>

                        <div class="reservation-subtitle">

                            <?php echo e($merge->schedule->subject
                                ?? 'Unknown Subject'); ?>


                        </div>

                    </div>


                    <span class="status-badge status-pending">

                        Pending

                    </span>

                </div>


                <div class="reservation-details">

                    <div class="detail-item">

                        <strong>
                            Existing Program:
                        </strong>

                        <?php echo e($merge->schedule->program
                            ?? 'N/A'); ?>


                    </div>


                    <div class="detail-item">

                        <strong>
                            Requested Program:
                        </strong>

                        <?php echo e($merge->facultySubject->program
                            ?? 'N/A'); ?>


                    </div>


                    <div class="detail-item">

                        <strong>
                            Department:
                        </strong>

                        <?php echo e($merge->department->name
                            ?? 'N/A'); ?>


                    </div>


                    <div class="detail-item">

                        <strong>
                            Room:
                        </strong>

                        <?php echo e($merge->schedule->room->room_name
                            ?? 'N/A'); ?>


                    </div>


                    <div class="detail-item">

                        <strong>
                            Instructor:
                        </strong>

                        <?php echo e($merge->schedule->instructor
                            ?? 'N/A'); ?>


                    </div>


                    <div class="detail-item">

                        <strong>
                            Time:
                        </strong>

                        <?php echo e($merge->schedule->time
                            ?? 'N/A'); ?>


                    </div>

                </div>


                <!-- ACTIONS WILL BE CONNECTED NEXT -->

               <div class="reservation-actions">

    <?php if($merge->status === 'pending'): ?>

        

        <form
            action="<?php echo e(route(
                'chair.merge.approve',
                $merge->id
            )); ?>"
            method="POST"
        >

            <?php echo csrf_field(); ?>

            <button
                type="submit"
                class="btn-approve"
                onclick="
                    return confirm(
                        'Approve this merge request?'
                    );
                "
            >

                <i class="fas fa-check"></i>

                Approve Merge

            </button>

        </form>


        

        <form
            action="<?php echo e(route(
                'chair.merge.reject',
                $merge->id
            )); ?>"
            method="POST"
        >

            <?php echo csrf_field(); ?>

            <button
                type="submit"
                class="btn-decline"
                onclick="
                    return confirm(
                        'Reject this merge request?'
                    );
                "
            >

                <i class="fas fa-times"></i>

                Reject

            </button>

        </form>

    <?php elseif($merge->status === 'approved'): ?>

        

        <form
            action="<?php echo e(route(
                'chair.merge.remove',
                $merge->id
            )); ?>"
            method="POST"
        >

            <?php echo csrf_field(); ?>

            <?php echo method_field('DELETE'); ?>

            <button
                type="submit"
                class="btn-decline"
                onclick="
                    return confirm(
                        'Remove this merged program?\n\n' +
                        'The original class will remain unchanged.'
                    );
                "
            >

                <i class="fas fa-unlink"></i>

                Remove Merge

            </button>

        </form>

    <?php endif; ?>

</div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php else: ?>

        <div class="empty-room">

            <i class="fas fa-code-branch"></i>

            <h3>
                No Pending Merge Requests
            </h3>

            <p>
                Merge requests from other departments
                will appear here.
            </p>

        </div>

    <?php endif; ?>

</div>
                <!-- =========================
                     ROOM SWAP HISTORY
                ========================= -->

                <div
                    id="swapped"
                    class="tab-content"
                >


                    <div class="section-intro">

                        <strong>
                            Room Swap History
                        </strong>

                        <span>
                            View room swap requests and activities submitted by faculty members.
                        </span>

                    </div>


                    <?php if(isset($swapRequests) && $swapRequests->count() > 0): ?>


                        <?php $__currentLoopData = $swapRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $swap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                            <div class="swap-card">


                                <div class="swap-card-header">


                                    <h4>

                                        <i
                                            class="fas fa-exchange-alt"
                                            style="color:#f9a825;"
                                        ></i>

                                        Room Swap Request

                                    </h4>


                                    <?php if($swap->status === 'Approved'): ?>

                                        <span class="status-badge status-approved">
                                            Approved
                                        </span>

                                    <?php elseif($swap->status === 'Declined'): ?>

                                        <span class="status-badge status-declined">
                                            Declined
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-pending">
                                            Pending
                                        </span>

                                    <?php endif; ?>


                                </div>


                                <div class="swap-details">


                                    <div class="swap-detail">

                                        <strong>
                                            Requested by:
                                        </strong>

                                        <?php echo e($swap->user->name ?? 'Unknown User'); ?>


                                    </div>


                                    <div class="swap-detail">

                                        <strong>
                                            Current Room:
                                        </strong>

                                        <?php echo e($swap->currentRoom->room_name ?? 'N/A'); ?>


                                    </div>


                                    <div class="swap-detail">

                                        <strong>
                                            Requested Room:
                                        </strong>

                                        <?php echo e($swap->requestedRoom->room_name ?? 'N/A'); ?>


                                    </div>


                                    <div class="swap-detail">

                                        <strong>
                                            Date:
                                        </strong>

                                        <?php echo e(isset($swap->created_at)
                                            ? $swap->created_at->format('F d, Y')
                                            : 'N/A'); ?>


                                    </div>


                                </div>


                            </div>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    <?php else: ?>


                        <div class="empty-room">

                            <i class="fas fa-exchange-alt"></i>

                            <h3>
                                No Room Swap History
                            </h3>

                            <p>
                                Faculty room swap activities will appear here.
                            </p>

                        </div>


                    <?php endif; ?>


                </div>


            </div>


        </main>

    </div>


    

</div>

<?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script>

function showTab(tabId, button)
{

    // Remove active state from all buttons
    document
        .querySelectorAll('.tab-btn')
        .forEach(function(btn)
        {
            btn.classList.remove('active');
        });


    // Hide all tab contents
    document
        .querySelectorAll('.tab-content')
        .forEach(function(content)
        {
            content.classList.remove('active');
        });


    // Activate selected button
    button.classList.add('active');


    // Show selected tab
    document
        .getElementById(tabId)
        .classList.add('active');

}

</script><?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/chair/dashboard.blade.php ENDPATH**/ ?>
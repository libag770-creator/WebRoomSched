<?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="wrapper">

    

    <div class="main-layout">

        <?php echo $__env->make('chair.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="content">

            <style>

    * {
        box-sizing: border-box;
    }

    body {
        background: #f4f7f5;
    }

    .page {
        max-width: 1150px;
        margin: auto;
        padding-bottom: 40px;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 25px;
    }

    .title-area {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .title-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e8f5e9;
        color: #2e7d32;

        border-radius: 10px;

        font-size: 20px;
    }

    .title {
        font-size: 26px;
        font-weight: 700;

        color: #1f2937;

        margin: 0;
    }

    .subtitle {
        color: #667085;

        font-size: 13px;

        margin: 4px 0 0;
    }


    /* =========================================================
       MAIN SECTION
    ========================================================= */

    .section {
        background: #ffffff;

        padding: 25px;

        border-radius: 12px;

        border: 1px solid #e4e9e5;

        box-shadow:
            0 2px 8px rgba(0,0,0,.04);
    }


    /* =========================================================
       DEPARTMENT
    ========================================================= */

    .department-display {
        display: flex;
        align-items: center;
        gap: 14px;

        background: #f0f8f2;

        border: 1px solid #d8eadb;

        padding: 15px 18px;

        border-radius: 9px;

        margin-bottom: 25px;
    }

    .department-icon {
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #ffffff;

        color: #2e7d32;

        border-radius: 8px;

        font-size: 17px;
    }

    .department-label {
        font-size: 11px;

        color: #667085;

        margin-bottom: 2px;
    }

    .department-name {
        font-size: 18px;

        font-weight: 700;

        color: #176b3a;
    }


    /* =========================================================
       SECTION HEADER
    ========================================================= */

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 15px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;

        color: #1f2937;

        font-size: 16px;

        font-weight: 700;
    }

    .section-title i {
        color: #2e7d32;
    }

    .section-help {
        color: #98a2b3;

        font-size: 11px;
    }


    /* =========================================================
       BUILDING ACCORDION
    ========================================================= */

    .building-list {
        display: flex;
        flex-direction: column;

        gap: 10px;
    }

    .building {
        border: 1px solid #e0e6e2;

        border-radius: 10px;

        overflow: hidden;

        background: #ffffff;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .building.open {
        border-color: #b8d8bd;

        box-shadow:
            0 3px 10px rgba(46,125,50,.07);
    }


    /* =========================================================
       BUILDING BUTTON
    ========================================================= */

    .building-toggle {
        width: 100%;

        border: none;

        background: #ffffff;

        padding: 16px 18px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        cursor: pointer;

        text-align: left;

        transition: background .2s ease;
    }

    .building-toggle:hover {
        background: #f8fbf9;
    }

    .building.open .building-toggle {
        background: #f0f8f2;
    }

    .building-left {
        display: flex;

        align-items: center;

        gap: 13px;
    }

    .building-icon {
        width: 40px;
        height: 40px;

        display: flex;

        align-items: center;
        justify-content: center;

        background: #e8f5e9;

        color: #2e7d32;

        border-radius: 8px;

        font-size: 16px;
    }

    .building-info {
        display: flex;

        flex-direction: column;

        gap: 3px;
    }

    .building-name {
        color: #1f2937;

        font-size: 14px;

        font-weight: 700;
    }

    .building-meta {
        color: #98a2b3;

        font-size: 11px;
    }

    .building-arrow {
        width: 30px;
        height: 30px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 7px;

        color: #667085;

        transition: transform .25s ease,
                    background .2s ease;
    }

    .building-toggle:hover .building-arrow {
        background: #e8f5e9;

        color: #2e7d32;
    }

    .building.open .building-arrow {
        transform: rotate(180deg);

        background: #dcefdF;

        color: #2e7d32;
    }


    /* =========================================================
       ROOMS CONTAINER
    ========================================================= */

    .rooms-container {
        display: none;

        background: #fafcfb;

        border-top: 1px solid #e5ebe7;

        padding: 15px;
    }

    .building.open .rooms-container {
        display: block;
    }


    /* =========================================================
       ROOM GRID
    ========================================================= */

    .room-grid {
        display: grid;

        grid-template-columns:
            repeat(auto-fill, minmax(245px, 1fr));

        gap: 12px;
    }


    /* =========================================================
       ROOM CARD
    ========================================================= */

    .room-card {
        background: #ffffff;

        border: 1px solid #e1e7e3;

        border-radius: 9px;

        padding: 15px;

        transition:
            transform .18s ease,
            box-shadow .18s ease,
            border-color .18s ease;
    }

    .room-card:hover {
        transform: translateY(-2px);

        border-color: #b8d8bd;

        box-shadow:
            0 5px 13px rgba(0,0,0,.06);
    }

    .room-top {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-bottom: 14px;
    }

    .room-icon {
        width: 37px;
        height: 37px;

        display: flex;

        align-items: center;
        justify-content: center;

        background: #f0f8f2;

        color: #2e7d32;

        border-radius: 7px;

        font-size: 15px;
    }

    .room-name {
        color: #1f2937;

        font-size: 14px;

        font-weight: 700;
    }

    .room-label {
        color: #98a2b3;

        font-size: 10px;

        margin-top: 2px;
    }


    /* =========================================================
       SET SCHEDULE BUTTON
    ========================================================= */

    .btn {
        border: none;

        border-radius: 7px;

        padding: 9px 13px;

        cursor: pointer;

        font-weight: 700;

        text-decoration: none;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        font-size: 11px;
    }

    .green {
        background: #2e7d32;

        color: #ffffff;

        width: 100%;
    }

    .green:hover {
        background: #256b2a;

        color: #ffffff;
    }


    /* =========================================================
       PERMISSION
    ========================================================= */

    .permission-box {
        margin-top: 13px;

        padding-top: 12px;

        border-top: 1px solid #edf0ee;
    }

    .permission-label {
        display: flex;

        align-items: flex-start;

        gap: 8px;

        cursor: pointer;

        font-size: 11px;

        color: #667085;

        line-height: 1.4;
    }

    .permission-label input {
        width: 15px;

        height: 15px;

        margin-top: 1px;

        cursor: pointer;

        accent-color: #2e7d32;
    }

    .permission-status {
        margin-top: 5px;

        margin-left: 23px;

        font-size: 10px;

        font-weight: 700;
    }

    .status-on {
        color: #2e7d32;
    }

    .status-off {
        color: #98a2b3;
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .empty {
        padding: 22px;

        text-align: center;

        color: #98a2b3;

        background: #f9faf9;

        border-radius: 8px;

        font-size: 12px;
    }


    /* =========================================================
       MESSAGES
    ========================================================= */

    .message {
        display: flex;

        align-items: center;

        gap: 9px;

        padding: 12px 15px;

        border-radius: 8px;

        margin-bottom: 18px;

        font-size: 12px;

        font-weight: 600;
    }

    .success {
        background: #ecfdf3;

        border: 1px solid #b7e4c7;

        color: #176b3a;
    }

    .error {
        background: #fef3f2;

        border: 1px solid #f3c7c3;

        color: #b42318;
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 700px) {

        .page {
            padding: 0 12px 30px;
        }

        .section {
            padding: 17px;
        }

        .page-header {
            align-items: flex-start;
        }

        .title {
            font-size: 22px;
        }

        .section-help {
            display: none;
        }

        .room-grid {
            grid-template-columns: 1fr;
        }

    }

</style>


<div class="page-header">

    <div class="title-area">

        <div class="title-icon">

            <i class="fa-solid fa-calendar-days"></i>

        </div>

        <div>

            <h1 class="title">
                Set Schedule
            </h1>

            <div class="subtitle">
                Manage classroom schedules for your department.
            </div>

        </div>

    </div>

</div>

                

                <?php if(session('success')): ?>

                    <div class="message success">
                        <?php echo e(session('success')); ?>

                    </div>

                <?php endif; ?>


                

                <?php if(session('error')): ?>

                    <div class="message error">
                        <?php echo e(session('error')); ?>

                    </div>

                <?php endif; ?>


                

                

                <?php
                    $availableTerms = $availableTerms ?? collect();
                ?>


                

                <div
                    style="
                        margin-bottom:18px;
                        padding:14px 16px;
                        background:#eef8ef;
                        border:1px solid #d3e8d6;
                        border-radius:10px;
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        gap:14px;
                        flex-wrap:wrap;
                    "
                >

                    

                    <div>

                        <div
                            style="
                                font-size:11px;
                                color:#667085;
                                text-transform:uppercase;
                                font-weight:800;
                                letter-spacing:.3px;
                            "
                        >
                            Active Academic Term
                        </div>


                        <div
                            style="
                                font-size:18px;
                                color:#176b3a;
                                font-weight:800;
                            "
                        >
                            <?php echo e($activeTerm->academic_year); ?>

                            •
                            <?php echo e($activeTerm->semester); ?>

                        </div>


                        <div
                            style="
                                font-size:11px;
                                color:#667085;
                                margin-top:3px;
                            "
                        >
                            All schedules you create here are saved under this active term.
                        </div>

                    </div>


                    

                    <div
                        style="
                            display:flex;
                            gap:8px;
                            align-items:center;
                            flex-wrap:wrap;
                        "
                    >

                        <form
                            method="GET"
                            action="<?php echo e(route('chair.setschedule')); ?>"
                            style="
                                display:flex;
                                gap:7px;
                                align-items:center;
                            "
                        >

                            <select
                                name="term_id"
                                aria-label="Choose academic term to activate"
                                onchange="
                                    this.form.submit();
                                "
                                style="
                                    padding:9px 11px;
                                    border:1px solid #2e7d32;
                                    border-radius:8px;
                                    min-width:230px;
                                    background:#ffffff;
                                    color:#1f2937;
                                    font-size:12px;
                                    font-weight:600;
                                    outline:none;
                                    cursor:pointer;
                                "
                            >

                                <option value="">
                                    Choose term to activate...
                                </option>


                                <?php $__currentLoopData = $availableTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <option
                                        value="<?php echo e($term->id); ?>"
                                        <?php echo e((int) $term->id === (int) $activeTerm->id ? 'selected' : ''); ?>

                                    >

                                        <?php echo e($term->academic_year); ?>

                                        •
                                        <?php echo e($term->semester); ?>


                                        <?php if(
                                            (int) $term->id ===
                                            (int) $activeTerm->id
                                        ): ?>
                                            — Active
                                        <?php endif; ?>

                                    </option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            </select>

                        </form>


                        

                        <a
                            href="<?php echo e(route('chair.schedule.library')); ?>"
                            style="
                                padding:9px 12px;
                                border-radius:7px;
                                background:#2e7d32;
                                color:#ffffff;
                                text-decoration:none;
                                font-size:12px;
                                font-weight:700;
                            "
                        >

                            Schedule Library

                        </a>

                    </div>

                </div>


<div class="section">


                    

                   <div class="department-display">

    <div class="department-icon">

        <i class="fa-solid fa-building-columns"></i>

    </div>

    <div>

        <div class="department-label">
            Your Department
        </div>

        <div class="department-name">
            <?php echo e($department->name); ?>

        </div>

    </div>

</div>


                   

<div class="section-header">

    <div class="section-title">

        <i class="fa-solid fa-building"></i>

        <span>
            Buildings and Rooms
        </span>

    </div>

    <div class="section-help">
        Click a building to view its rooms
    </div>

</div>


<div class="building-list">

    <?php $__empty_1 = true; $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <?php
            $roomCount = $building->rooms->count();
        ?>


        <div class="building">


            

            <button
                type="button"
                class="building-toggle"
                onclick="toggleBuilding(this)"
            >

                <div class="building-left">

                    <div class="building-icon">

                        <i class="fa-solid fa-building"></i>

                    </div>


                    <div class="building-info">

                        <div class="building-name">

                            <?php echo e($building->name); ?>


                        </div>


                        <div class="building-meta">

                            <?php echo e($roomCount); ?>

                            <?php echo e($roomCount == 1 ? 'room' : 'rooms'); ?>


                        </div>

                    </div>

                </div>


                <div class="building-arrow">

                    <i class="fa-solid fa-chevron-down"></i>

                </div>

            </button>


            

            <div class="rooms-container">

                <div class="room-grid">


                    <?php $__empty_2 = true; $__currentLoopData = $building->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>

                        <div class="room-card">


                            <div class="room-top">

                                <div class="room-icon">

                                    <i class="fa-solid fa-door-open"></i>

                                </div>


                                <div>

                                    <div class="room-name">

                                        <?php echo e($room->room_name); ?>


                                    </div>

                                    <div class="room-label">

                                        Classroom

                                    </div>

                                </div>

                            </div>


                            

                            <a
                                href="<?php echo e(route(
                                    'chair.excel',
                                    $room->id
                                )); ?>"
                                class="btn green"
                            >

                                <i class="fa-solid fa-calendar-days"></i>

                                Set Schedule

                            </a>


                            

                            <div class="permission-box">

                                <label
                                    class="permission-label"
                                    for="permission-<?php echo e($room->id); ?>"
                                >

                                    <input
                                        type="checkbox"
                                        id="permission-<?php echo e($room->id); ?>"

                                        <?php echo e($room->allow_other_departments
                                            ? 'checked'
                                            : ''); ?>


                                        onchange="updateRoomPermission(
                                            <?php echo e($room->id); ?>,
                                            this
                                        )"
                                    >

                                    <span>
                                        Allow other departments
                                        to add to empty slots
                                    </span>

                                </label>


                                <div
                                    id="status-<?php echo e($room->id); ?>"
                                    class="permission-status
                                    <?php echo e($room->allow_other_departments
                                            ? 'status-on'
                                            : 'status-off'); ?>"
                                >

                                    <?php echo e($room->allow_other_departments
                                            ? 'ON'
                                            : 'OFF'); ?>


                                </div>

                            </div>


                        </div>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>

                        <div class="empty">

                            <i class="fa-solid fa-door-closed"></i>

                            <div style="margin-top:6px;">

                                No rooms in this building.

                            </div>

                        </div>

                    <?php endif; ?>


                </div>

            </div>

        </div>


    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <div class="empty">

            <i class="fa-solid fa-building-circle-xmark"></i>

            <div style="margin-top:6px;">

                No buildings are assigned
                to your department.

            </div>

        </div>

    <?php endif; ?>

</div>
</div>

            

            <script>

               function updateRoomPermission(roomId, checkbox)
{
    const allowed = checkbox.checked;

    fetch(
        "<?php echo e(url('/chair/room')); ?>/" + roomId + "/permission",
        {
            method: "PUT",

            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",

                "X-CSRF-TOKEN":
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).getAttribute('content')
            },

            body: JSON.stringify({
                allow_other_departments: allowed ? 1 : 0
            })
        }
    )
    .then(response => {

        if (!response.ok) {
            throw new Error(
                "HTTP Error: " + response.status
            );
        }

        return response.json();
    })
    .then(data => {

        if (!data.success) {
            throw new Error(
                data.message ||
                "Permission was not saved."
            );
        }

        const saved =
            Number(data.allow_other_departments) === 1;

        checkbox.checked = saved;

        const status =
            document.getElementById(
                "status-" + roomId
            );

        if (saved) {

            status.textContent = "ON";

            status.className =
                "permission-status status-on";

        } else {

            status.textContent = "OFF";

            status.className =
                "permission-status status-off";
        }

    })
    .catch(error => {

        console.error(error);

        checkbox.checked = !allowed;

        alert(
            "Unable to save permission.\n\n" +
            error.message
        );
    });


}
    function toggleBuilding(button)
{
    const building =
        button.closest('.building');

    if (!building) {
        return;
    }

    building.classList.toggle('open');
}

                
            </script>

        </main>

    </div>

    

</div>

<?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/chair/setSchedule.blade.php ENDPATH**/ ?>
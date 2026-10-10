<div class="wrapper">

    

    <?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <div class="main-layout">

        

        <?php echo $__env->make('admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        

        <main class="content">


            <style>

                /*
                |--------------------------------------------------------------------------
                | MAIN PAGE
                |--------------------------------------------------------------------------
                */

                .room-reassignment-page {
                    padding: 25px;
                    max-width: 1500px;
                    margin: auto;
                }


                /*
                |--------------------------------------------------------------------------
                | PAGE HEADER
                |--------------------------------------------------------------------------
                */

                .rr-header {
                    margin-bottom: 20px;
                }

                .rr-header h1 {
                    margin: 0;
                    font-size: 27px;
                    font-weight: 800;
                    color: #222;
                }

                .rr-header p {
                    margin-top: 6px;
                    color: #777;
                    font-size: 14px;
                }


                /*
                |--------------------------------------------------------------------------
                | MESSAGE
                |--------------------------------------------------------------------------
                */

                .rr-message {
                    padding: 13px 16px;
                    border-radius: 8px;
                    margin-bottom: 18px;
                    font-size: 13px;
                }

                .rr-success {
                    background: #ecfdf5;
                    border: 1px solid #bbf7d0;
                    color: #166534;
                }

                .rr-error {
                    background: #fef2f2;
                    border: 1px solid #fecaca;
                    color: #991b1b;
                }


                /*
                |--------------------------------------------------------------------------
                | PANEL
                |--------------------------------------------------------------------------
                */

                .rr-panel {
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.05);
                }

                .rr-panel-title {
                    font-size: 18px;
                    font-weight: 800;
                    color: #222;
                    margin-bottom: 5px;
                }

                .rr-panel-description {
                    color: #777;
                    font-size: 13px;
                    margin-bottom: 18px;
                }


                /*
                |--------------------------------------------------------------------------
                | STEP SELECTORS
                |--------------------------------------------------------------------------
                */

                .rr-step-grid {
                    display: grid;
                    grid-template-columns:
                        repeat(3, 1fr);
                    gap: 15px;
                }

                .rr-step {
                    position: relative;
                }

                .rr-step-number {
                    position: absolute;
                    left: 10px;
                    top: 34px;
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    background: #2563eb;
                    color: #fff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 11px;
                    font-weight: 800;
                    z-index: 2;
                }

                .rr-step label {
                    display: block;
                    margin-bottom: 7px;
                    font-size: 12px;
                    font-weight: 700;
                    color: #374151;
                }

                .rr-step select {
                    width: 100%;
                    height: 43px;
                    border: 1px solid #d1d5db;
                    border-radius: 7px;
                    padding:
                        0
                        10px
                        0
                        43px;
                    background: #fff;
                    font-size: 13px;
                    color: #222;
                }

                .rr-step select:disabled {
                    background: #f3f4f6;
                    color: #9ca3af;
                    cursor: not-allowed;
                }


                /*
                |--------------------------------------------------------------------------
                | SELECTED ROOM
                |--------------------------------------------------------------------------
                */

                .rr-selected-room {
                    margin-top: 18px;
                    padding: 14px;
                    background: #eff6ff;
                    border: 1px solid #bfdbfe;
                    border-radius: 8px;
                    display: none;
                }

                .rr-selected-room strong {
                    color: #1e3a8a;
                }


                /*
                |--------------------------------------------------------------------------
                | TERM
                |--------------------------------------------------------------------------
                */

                .rr-term {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                    margin-top: 15px;
                }

                .rr-term-box {
                    background: #f8fafc;
                    border: 1px solid #e5e7eb;
                    padding: 8px 12px;
                    border-radius: 7px;
                    font-size: 12px;
                }

                .rr-term-box strong {
                    color: #374151;
                }


                /*
                |--------------------------------------------------------------------------
                | EXCEL WRAPPER
                |--------------------------------------------------------------------------
                */

                .rr-table-wrapper {
                    overflow-x: auto;
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    background: #fff;
                }


                /*
                |--------------------------------------------------------------------------
                | EXCEL TABLE
                |--------------------------------------------------------------------------
                */

                #rrScheduleTable {
                    width: 100%;
                    min-width: 1050px;
                    border-collapse: collapse;
                    table-layout: fixed;
                }

                #rrScheduleTable th {
                    background: #1f2937;
                    color: #fff;
                    border: 1px solid #374151;
                    padding: 10px 6px;
                    text-align: center;
                    font-size: 12px;
                    font-weight: 800;
                }

                #rrScheduleTable td {
                    border: 1px solid #d1d5db;
                }


                /*
                |--------------------------------------------------------------------------
                | TIME
                |--------------------------------------------------------------------------
                */

                .rr-time {
                    width: 90px;
                    text-align: center;
                    vertical-align: middle;
                    background: #f8fafc;
                    font-size: 11px;
                    font-weight: 700;
                    color: #374151;
                }


                /*
                |--------------------------------------------------------------------------
                | SCHEDULE CELL
                |--------------------------------------------------------------------------
                */

                .rr-cell {
                    height: 90px;
                    padding: 6px;
                    vertical-align: middle;
                    text-align: center;
                    cursor: pointer;
                    background: #fff;
                    transition: .15s;
                }

                .rr-cell:hover {
                    box-shadow:
                        inset 0 0 0 2px #2563eb;
                }

                .rr-cell.selected {
                    background: #eff6ff !important;
                    box-shadow:
                        inset 0 0 0 3px #2563eb;
                }

                .rr-cell.empty {
                    color: #9ca3af;
                    cursor: default;
                }

                .rr-cell.empty:hover {
                    box-shadow: none;
                }


                /*
                |--------------------------------------------------------------------------
                | CELL CONTENT
                |--------------------------------------------------------------------------
                */

                .rr-course {
                    font-size: 12px;
                    font-weight: 800;
                    color: #111827;
                }

                .rr-subject {
                    font-size: 10px;
                    margin-top: 3px;
                    color: #374151;
                }

                .rr-instructor {
                    font-size: 9px;
                    margin-top: 4px;
                    color: #4b5563;
                }

                .rr-program {
                    font-size: 9px;
                    margin-top: 2px;
                    color: #6b7280;
                }


                /*
                |--------------------------------------------------------------------------
                | NOON BREAK
                |--------------------------------------------------------------------------
                */

                .rr-noon-break td {
                    height: 32px;
                    background: #f3f4f6;
                    color: #6b7280;
                    text-align: center;
                    font-size: 11px;
                    font-weight: 800;
                    letter-spacing: 3px;
                }


                /*
                |--------------------------------------------------------------------------
                | REASSIGNED
                |--------------------------------------------------------------------------
                */

                .rr-reassigned {
                    box-shadow:
                        inset 0 0 0 3px #f59e0b;
                }

                .rr-reassigned-label {
                    display: inline-block;
                    margin-top: 5px;
                    padding: 3px 7px;
                    background: #f59e0b;
                    color: #fff;
                    border-radius: 20px;
                    font-size: 8px;
                    font-weight: 800;
                }


                /*
                |--------------------------------------------------------------------------
                | FORM
                |--------------------------------------------------------------------------
                */

                .rr-form-grid {
                    display: grid;
                    grid-template-columns:
                        repeat(2, 1fr);
                    gap: 15px;
                }

                .rr-form-group {
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }

                .rr-form-group.full {
                    grid-column: 1 / -1;
                }

                .rr-form-group label {
                    font-size: 12px;
                    font-weight: 700;
                    color: #374151;
                }

                .rr-form-group select,
                .rr-form-group textarea {
                    width: 100%;
                    box-sizing: border-box;
                    border: 1px solid #d1d5db;
                    border-radius: 7px;
                    padding: 10px;
                    font-size: 13px;
                    background: #fff;
                }

                .rr-form-group textarea {
                    min-height: 80px;
                    resize: vertical;
                }


                /*
                |--------------------------------------------------------------------------
                | SELECTED SCHEDULE INFORMATION
                |--------------------------------------------------------------------------
                */

                .rr-selected-grid {
                    display: grid;
                    grid-template-columns:
                        repeat(4, 1fr);
                    gap: 10px;
                    margin-bottom: 18px;
                }

                .rr-info {
                    background: #f8fafc;
                    border: 1px solid #e5e7eb;
                    border-radius: 7px;
                    padding: 10px;
                }

                .rr-info small {
                    display: block;
                    color: #6b7280;
                    font-size: 9px;
                    margin-bottom: 3px;
                }

                .rr-info strong {
                    font-size: 12px;
                    color: #111827;
                }


                /*
                |--------------------------------------------------------------------------
                | BUTTONS
                |--------------------------------------------------------------------------
                */

                .rr-actions {
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                    margin-top: 18px;
                }

                .rr-button {
                    border: 0;
                    border-radius: 7px;
                    padding: 10px 18px;
                    font-size: 12px;
                    font-weight: 800;
                    cursor: pointer;
                }

                .rr-clear {
                    background: #e5e7eb;
                    color: #374151;
                }

                .rr-submit {
                    background: #2563eb;
                    color: #fff;
                }

                .rr-submit:disabled {
                    background: #9ca3af;
                    cursor: not-allowed;
                }


                /*
                |--------------------------------------------------------------------------
                | ACTIVE LIST
                |--------------------------------------------------------------------------
                */

                .rr-list {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                .rr-list-item {
                    display: grid;
                    grid-template-columns:
                        repeat(4, 1fr)
                        auto;
                    gap: 12px;
                    align-items: center;
                    padding: 12px;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    background: #fafafa;
                }

                .rr-list-item small {
                    display: block;
                    color: #6b7280;
                    font-size: 9px;
                }

                .rr-list-item strong {
                    font-size: 11px;
                }

                .rr-cancel {
                    background: #dc2626;
                    color: #fff;
                    border: 0;
                    border-radius: 6px;
                    padding: 7px 10px;
                    cursor: pointer;
                    font-size: 10px;
                    font-weight: 800;
                }


                /*
                |--------------------------------------------------------------------------
                | MOBILE
                |--------------------------------------------------------------------------
                */


                /* Department / building / room card navigation */
                .rr-location-panel { overflow: hidden; }
                .rr-breadcrumb { display:flex; flex-wrap:wrap; gap:8px; margin:16px 0 20px; }
                .rr-crumb { padding:7px 12px; border-radius:20px; background:#f3f4f6; color:#6b7280; font-size:11px; font-weight:800; }
                .rr-crumb.active { background:#dbeafe; color:#1d4ed8; }
                .rr-level-heading { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:10px 0 14px; }
                .rr-level-heading h3 { margin:0 0 4px; color:#1f2937; font-size:16px; font-weight:800; }
                .rr-level-heading span { color:#6b7280; font-size:12px; }
                .rr-card-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:13px; }
                .rr-location-card { position:relative; display:flex; flex-direction:column; align-items:flex-start; min-height:125px; padding:17px 42px 15px 16px; text-align:left; border:1px solid #dbe3ee; border-radius:10px; background:#fff; color:#1f2937; cursor:pointer; transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease; }
                .rr-location-card:hover { transform:translateY(-2px); border-color:#2563eb; box-shadow:0 7px 20px rgba(31,41,55,.09); }
                .rr-location-card:focus-visible { outline:3px solid #93c5fd; outline-offset:2px; }
                .rr-card-icon { display:flex; align-items:center; justify-content:center; width:37px; height:37px; margin-bottom:12px; border-radius:9px; background:#eff6ff; color:#1d4ed8; font-size:21px; }
                .rr-card-title { font-size:14px; line-height:1.35; font-weight:800; overflow-wrap:anywhere; }
                .rr-card-subtitle { margin-top:5px; color:#6b7280; font-size:11px; }
                .rr-card-arrow { position:absolute; top:50%; right:15px; transform:translateY(-50%); color:#2563eb; font-size:26px; }
                .rr-room-card { min-height:105px; }
                .rr-room-card .rr-card-icon { background:#ecfdf5; color:#047857; }
                .rr-room-card .rr-card-subtitle { line-height:1.5; }
                .rr-back-button { flex-shrink:0; border:1px solid #d1d5db; border-radius:7px; padding:8px 11px; background:#fff; color:#374151; font-size:11px; font-weight:800; cursor:pointer; }
                .rr-back-button:hover { background:#f9fafb; border-color:#9ca3af; }
                .rr-loading { display:flex; align-items:center; gap:10px; padding:18px 4px; color:#4b5563; font-size:12px; }
                .rr-spinner { width:17px; height:17px; border:2px solid #dbeafe; border-top-color:#2563eb; border-radius:50%; animation:rr-spin .7s linear infinite; }
                @keyframes rr-spin { to { transform:rotate(360deg); } }
                .rr-empty-state { grid-column:1/-1; padding:22px; border:1px dashed #d1d5db; border-radius:9px; color:#6b7280; text-align:center; font-size:13px; }
                [hidden] { display:none !important; }

                @media(max-width: 900px) {

                    .rr-step-grid {
                        grid-template-columns: 1fr;
                    }

                    .rr-selected-grid {
                        grid-template-columns: 1fr 1fr;
                    }

                    .rr-form-grid {
                        grid-template-columns: 1fr;
                    }

                    .rr-list-item {
                        grid-template-columns: 1fr 1fr;
                    }

                }


                @media(max-width: 600px) {

                    .room-reassignment-page {
                        padding: 12px;
                    }

                    .rr-selected-grid {
                        grid-template-columns: 1fr;
                    }

                }

            
                .rr-modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.58);z-index:9999;display:flex;align-items:center;justify-content:center;padding:18px;}
                .rr-modal{width:min(620px,100%);max-height:90vh;overflow:auto;background:#fff;border-radius:13px;box-shadow:0 24px 80px rgba(0,0,0,.28);}
                .rr-modal-header{display:flex;align-items:center;justify-content:space-between;background:#1d477b;color:#fff;padding:15px 20px;border-radius:13px 13px 0 0;}
                .rr-modal-header h2{margin:0;font-size:18px;font-weight:800;color:#fff;}
                .rr-modal-x{border:0;background:transparent;color:#fff;font-size:30px;line-height:1;cursor:pointer;padding:0 3px;}
                .rr-modal-body{padding:20px;color:#222;}
                .rr-detail-lines p{margin:7px 0;font-size:14px;}
                .rr-modal .rr-form-group label{display:block;font-weight:700;margin-bottom:7px;}
                .rr-modal .rr-form-group select,.rr-modal .rr-form-group input,.rr-modal .rr-form-group textarea{width:100%;border:1px solid #bfc7d1;border-radius:7px;padding:10px 12px;font:inherit;background:#fff;}
                .rr-modal-actions{display:flex;justify-content:flex-end;gap:9px;margin-top:22px;padding-top:14px;border-top:1px solid #e5e7eb;}
                .rr-available-room-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin-top:15px;}
                .rr-room-choice{border:1px solid #cbd5e1;border-radius:9px;padding:14px;text-align:left;background:#fff;cursor:pointer;transition:.15s;}
                .rr-room-choice:hover{border-color:#1d477b;background:#eff6ff;transform:translateY(-1px);}
                .rr-room-choice strong,.rr-room-choice span{display:block;}
                .rr-room-choice span{font-size:12px;color:#64748b;margin-top:5px;}
                .rr-room-unavailable{padding:16px;border-radius:8px;background:#fff7ed;color:#9a3412;grid-column:1/-1;}
                .rr-success-note{background:#ecfdf5;border:1px solid #bbf7d0;color:#166534;padding:12px;border-radius:8px;margin-bottom:15px;}
                .rr-help-text{color:#64748b;font-size:13px;line-height:1.5;}
                @media(max-width:600px){.rr-modal-backdrop{padding:8px;align-items:flex-start}.rr-modal{margin:12px 0;max-height:94vh}.rr-modal-body{padding:15px}.rr-modal-actions{flex-wrap:wrap}.rr-modal-actions button{flex:1;}}
</style>


            <div class="room-reassignment-page">


                

                <div class="rr-header">

                    <h1>
                        Room Reassignment
                    </h1>

                    <p>
                        Temporarily reassign a scheduled class to another room
                        without changing the original schedule.
                    </p>

                </div>


                

                <?php if(session('success')): ?>

                    <div class="rr-message rr-success">
                        <?php echo e(session('success')); ?>

                    </div>

                <?php endif; ?>


                <?php if(session('error')): ?>

                    <div class="rr-message rr-error">
                        <?php echo e(session('error')); ?>

                    </div>

                <?php endif; ?>


                <?php if($errors->any()): ?>

                    <div class="rr-message rr-error">

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div>
                                <?php echo e($error); ?>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                <?php endif; ?>


                

                <div class="rr-panel rr-location-panel">
                    <div class="rr-panel-title">Select Schedule Location</div>
                    <div class="rr-panel-description">Choose a department card, then a building card, then a room card. Buildings and rooms load only when you open each level.</div>

                    <div class="rr-breadcrumb" id="rrBreadcrumb">
                        <span class="rr-crumb active">1. Departments</span>
                        <span class="rr-crumb">2. Buildings</span>
                        <span class="rr-crumb">3. Rooms</span>
                    </div>

                    <section class="rr-location-level" id="rrDepartmentLevel">
                        <div class="rr-level-heading"><h3>Departments</h3><span>Select a department to view its buildings.</span></div>
                        <div class="rr-card-grid" id="rrDepartmentCards">
                            <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <button type="button" class="rr-location-card rr-department-card" data-department-id="<?php echo e($department->id); ?>" data-department-name="<?php echo e($department->name); ?>">
                                    <span class="rr-card-icon">▦</span>
                                    <span class="rr-card-title"><?php echo e($department->name); ?></span>
                                    <span class="rr-card-subtitle">Open department</span>
                                    <span class="rr-card-arrow">›</span>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="rr-empty-state">No departments were found.</div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="rr-location-level" id="rrBuildingLevel" hidden>
                        <div class="rr-level-heading">
                            <div><h3 id="rrBuildingHeading">Buildings</h3><span id="rrBuildingSubheading">Choose a building to load its rooms.</span></div>
                            <button type="button" class="rr-back-button" id="rrBackDepartments">← Departments</button>
                        </div>
                        <div class="rr-card-grid" id="rrBuildingCards"></div>
                    </section>

                    <section class="rr-location-level" id="rrRoomLevel" hidden>
                        <div class="rr-level-heading">
                            <div><h3 id="rrRoomHeading">Rooms</h3><span id="rrRoomSubheading">Choose a room to view its timetable.</span></div>
                            <button type="button" class="rr-back-button" id="rrBackBuildings">← Buildings</button>
                        </div>
                        <div class="rr-card-grid rr-room-card-grid" id="rrRoomCards"></div>
                    </section>

                    <div class="rr-loading" id="rrLocationLoading" hidden><span class="rr-spinner"></span><span id="rrLoadingText">Loading...</span></div>
                    <div class="rr-message rr-error" id="rrLocationError" hidden></div>

                    
                    <div hidden aria-hidden="true">
                        <select id="rrDepartment">
                            <option value="">Select Department</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department->id); ?>"><?php echo e($department->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <select id="rrBuilding"><option value="">Select Building</option></select>
                        <select id="rrRoom" disabled><option value="">Select Room</option></select>
                    </div>

                    <div class="rr-selected-room" id="selectedRoomDisplay">
                        Selected Room: <strong id="selectedRoomName">-</strong>
                    </div>


                    <div class="rr-term">

                        <div class="rr-term-box">

                            <strong>
                                Academic Year:
                            </strong>

                            <?php echo e($activeTerm->academic_year); ?>


                        </div>


                        <div class="rr-term-box">

                            <strong>
                                Semester:
                            </strong>

                            <?php echo e($activeTerm->semester); ?>


                        </div>

                    </div>

                </div>


                

                <div
                    class="rr-panel"
                    id="schedulePanel"
                    style="display:none;"
                >

                    <div class="rr-panel-title">
                        Room Schedule
                    </div>

                    <div class="rr-panel-description">
                        Click one schedule cell only.
                        Each day/time cell is independent.
                    </div>


                    <div class="rr-table-wrapper">

                        <table id="rrScheduleTable">

                            <thead>

                                <tr>

                                    <th>
                                        TIME
                                    </th>

                                    <th>
                                        MON
                                    </th>

                                    <th>
                                        WED
                                    </th>

                                    <th>
                                        FRI
                                    </th>

                                    <th>
                                        TIME
                                    </th>

                                    <th>
                                        TUE
                                    </th>

                                    <th>
                                        THU
                                    </th>

                                    <th>
                                        SAT
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="rrScheduleBody">

                                

                            </tbody>

                        </table>

                    </div>

                </div>


                
                <form method="POST" action="<?php echo e(route('admin.roomreassignment.store')); ?>" id="rrForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="schedule_id" id="rrScheduleId">
                    <input type="hidden" name="original_room_id" id="rrOriginalRoomId">
                    <input type="hidden" name="day" id="rrDay">
                    <input type="hidden" name="time" id="rrTime">
                    <input type="hidden" name="new_room_id" id="rrNewRoomId">
                    <select id="rrNewRoom" style="display:none" aria-hidden="true"><option value="">Select Available Room</option></select>
                    <input type="hidden" name="occupancy_decision" id="rrOccupancyDecision">

                    <input type="hidden" name="occupied_by" id="rrOccupiedBy">
                    <input type="hidden" name="occupancy_purpose" id="rrOccupancyPurpose">
                    <input type="hidden" name="occupancy_details" id="rrOccupancyDetails">
                    <input type="hidden" name="not_occupied_reason" id="rrNotOccupiedReason">
                    <input type="hidden" name="not_occupied_details" id="rrNotOccupiedDetails">
                    <input type="hidden" name="reason" id="rrReason">
                    <input type="hidden" name="details" id="rrDetails">

                    <div class="rr-modal-backdrop" id="rrWizardBackdrop" style="display:none;">
                        <section class="rr-modal" role="dialog" aria-modal="true" aria-labelledby="rrModalTitle">
                            <header class="rr-modal-header">
                                <h2 id="rrModalTitle">Reassign Schedule</h2>
                                <button type="button" class="rr-modal-x" data-rr-close aria-label="Close">&times;</button>
                            </header>
                            <div class="rr-modal-body">
                                <div class="rr-wizard-step" data-rr-step="details">
                                    <p>Would you like to reassign this schedule?</p>
                                    <div class="rr-detail-lines">
                                        <p><strong>Subject:</strong> <span id="rrSelectedCourse">-</span></p>
                                        <p><strong>Course:</strong> <span id="rrSelectedProgram">-</span></p>
                                        <p><strong>Instructor:</strong> <span id="rrSelectedInstructor">-</span></p>
                                        <p><strong>Day:</strong> <span id="rrSelectedDay">-</span></p>
                                        <p><strong>Time:</strong> <span id="rrSelectedTime">-</span></p>
                                        <p><strong>Current Room:</strong> <span id="rrSelectedRoom">-</span></p>
                                    </div>
                                    <div class="rr-form-group">
                                        <label for="rrReasonSelect">Reason for Reassignment</label>
                                        <select id="rrReasonSelect" required>
                                            <option value="Meeting">Meeting</option>
                                            <option value="Seminar">Seminar</option>
                                            <option value="Academic Activity">Academic Activity</option>
                                            <option value="Organization Activity">Organization Activity</option>
                                            <option value="Official Event">Official Event</option>
                                            <option value="Room Unavailable">Room Unavailable</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="rr-form-group" style="margin-top:12px;">
                                        <label for="rrReasonDetails">Details (Optional)</label>
                                        <textarea id="rrReasonDetails" rows="2" placeholder="Additional details..."></textarea>
                                    </div>
                                    <div class="rr-modal-actions">
                                        <button type="button" class="rr-button rr-clear" data-rr-close>NO</button>
                                        <button type="button" class="rr-button rr-submit" id="rrShowRooms">YES</button>
                                    </div>
                                </div>

                                <div class="rr-wizard-step" data-rr-step="rooms" hidden>
                                    <p>Select one of the available rooms for this schedule.</p>
                                    <div id="rrAvailableRoomCards" class="rr-available-room-cards"></div>
                                    <div class="rr-modal-actions">
                                        <button type="button" class="rr-button rr-clear" data-rr-back="details">Back</button>
                                    </div>
                                </div>

                                <div class="rr-wizard-step" data-rr-step="confirm" hidden>
                                    <h3>Confirm Selection</h3>
                                    <div class="rr-detail-lines">
                                        <p><strong>Building:</strong> <span id="rrConfirmBuilding">-</span></p>
                                        <p><strong>Department:</strong> <span id="rrConfirmDepartment">-</span></p>
                                        <p><strong>Room:</strong> <span id="rrConfirmRoom">-</span></p>
                                        <p><strong>Day:</strong> <span id="rrConfirmDay">-</span></p>
                                        <p><strong>Time:</strong> <span id="rrConfirmTime">-</span></p>
                                    </div>
                                    <div class="rr-modal-actions">
                                        <button type="button" class="rr-button rr-clear" data-rr-back="rooms">Back</button>
                                        <button type="button" class="rr-button rr-submit" id="rrConfirmRoomButton">Confirm</button>
                                    </div>
                                </div>

                                <div class="rr-wizard-step" data-rr-step="occupy-decision" hidden>
                                    <div class="rr-success-note"><strong>The schedule has been successfully reassigned.</strong></div>
                                    <p>Would you like to occupy the old room?</p>
                                    <div class="rr-detail-lines">
                                        <p><strong>Room:</strong> <span id="rrOldRoomName">-</span></p>
                                        <p><strong>Day:</strong> <span id="rrOldRoomDay">-</span></p>
                                        <p><strong>Time:</strong> <span id="rrOldRoomTime">-</span></p>
                                    </div>
                                    <p class="rr-help-text">Choose YES to state who will occupy the room and the purpose. Choose NO to state why the room will remain unoccupied.</p>
                                    <div class="rr-modal-actions">
                                        <button type="button" class="rr-button rr-clear" data-rr-back="confirm">Back</button>
                                        <button type="button" class="rr-button rr-clear" id="rrChooseNo">NO</button>
                                        <button type="button" class="rr-button rr-submit" id="rrChooseYes">YES</button>
                                    </div>
                                </div>

                                <div class="rr-wizard-step" data-rr-step="occupy-yes" hidden>
                                    <div class="rr-detail-lines">
                                        <p><strong>Room:</strong> <span class="rrOldRoomNameCopy">-</span></p>
                                        <p><strong>Day:</strong> <span class="rrOldRoomDayCopy">-</span></p>
                                        <p><strong>Time:</strong> <span class="rrOldRoomTimeCopy">-</span></p>
                                    </div>
                                    <div class="rr-form-group">
                                        <label for="rrOccupantInput">Who Will Occupy the Room?</label>
                                        <input type="text" id="rrOccupantInput" maxlength="255" placeholder="Enter name or group" required>
                                    </div>
                                    <div class="rr-form-group" style="margin-top:12px;">
                                        <label for="rrPurposeSelect">Reason / Purpose</label>
                                        <select id="rrPurposeSelect" required>
                                            <option value="Meeting">Meeting</option>
                                            <option value="Seminar">Seminar</option>
                                            <option value="Program">Program</option>
                                            <option value="Academic Activity">Academic Activity</option>
                                            <option value="Organization Activity">Organization Activity</option>
                                            <option value="Official Event">Official Event</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="rr-form-group" style="margin-top:12px;">
                                        <label for="rrOccupancyDetailsInput">Activity Details (Optional)</label>
                                        <textarea id="rrOccupancyDetailsInput" rows="3" maxlength="2000" placeholder="Additional activity details..."></textarea>
                                    </div>
                                    <div class="rr-modal-actions">
                                        <button type="button" class="rr-button rr-clear" data-rr-back="occupy-decision">Back</button>
                                        <button type="submit" class="rr-button rr-submit">Occupy Room</button>
                                    </div>
                                </div>

                                <div class="rr-wizard-step" data-rr-step="occupy-no" hidden>
                                    <div class="rr-detail-lines">
                                        <p><strong>Room:</strong> <span class="rrOldRoomNameCopy">-</span></p>
                                        <p><strong>Day:</strong> <span class="rrOldRoomDayCopy">-</span></p>
                                        <p><strong>Time:</strong> <span class="rrOldRoomTimeCopy">-</span></p>
                                    </div>
                                    <div class="rr-form-group">
                                        <label for="rrNotOccupiedReasonInput">Why will the room remain unoccupied?</label>
                                        <select id="rrNotOccupiedReasonInput" required>
                                            <option value="Room Unavailable">Room Unavailable</option>
                                            <option value="No activity scheduled">No activity scheduled</option>
                                            <option value="Room maintenance">Room maintenance</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="rr-form-group" style="margin-top:12px;">
                                        <label for="rrNotOccupiedDetailsInput">Details (Optional)</label>
                                        <textarea id="rrNotOccupiedDetailsInput" rows="3" maxlength="2000" placeholder="Additional details..."></textarea>
                                    </div>
                                    <div class="rr-modal-actions">
                                        <button type="button" class="rr-button rr-clear" data-rr-back="occupy-decision">Back</button>
                                        <button type="submit" class="rr-button rr-submit">Confirm Reassignment</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </form>

                

                <div class="rr-panel">

                    <div class="rr-panel-title">
                        Active Room Reassignments
                    </div>

                    <div class="rr-panel-description">
                        Temporary room changes currently active.
                    </div>


                    <?php if($reassignments->count()): ?>

                        <div class="rr-list">

                            <?php $__currentLoopData = $reassignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <div class="rr-list-item">


                                    <div>

                                        <small>
                                            ORIGINAL ROOM
                                        </small>

                                        <strong>
                                            <?php echo e($item->originalRoom->room_name ?? 'N/A'); ?>

                                        </strong>

                                    </div>


                                    <div>

                                        <small>
                                            NEW ROOM
                                        </small>

                                        <strong>
                                            <?php echo e($item->newRoom->room_name ?? 'N/A'); ?>

                                        </strong>

                                    </div>


                                    <div>

                                        <small>
                                            DAY / TIME
                                        </small>

                                        <strong>

                                            <?php echo e($item->day); ?>


                                            —

                                            <?php echo e(\Carbon\Carbon::parse(
                                                $item->start_time
                                            )->format('g:i A')); ?>


                                            -

                                            <?php echo e(\Carbon\Carbon::parse(
                                                $item->end_time
                                            )->format('g:i A')); ?>


                                        </strong>

                                    </div>


                                    <div>

                                        <small>
                                            REASON
                                        </small>

                                        <strong>
                                            <?php echo e($item->reason); ?>

                                        </strong>

                                    </div>


                                    <div>

                                        <form
                                            method="POST"
                                            action="<?php echo e(route(
                                                'admin.roomreassignment.destroy',
                                                $item
                                            )); ?>"
                                        >

                                            <?php echo csrf_field(); ?>

                                            <?php echo method_field('DELETE'); ?>

                                            <button
                                                type="submit"
                                                class="rr-cancel"
                                                onclick="
                                                    return confirm(
                                                        'Cancel this room reassignment?'
                                                    );
                                                "
                                            >
                                                Cancel
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    <?php else: ?>

                        <div
                            style="
                                color:#777;
                                font-size:13px;
                            "
                        >

                            No active room reassignments.

                        </div>

                    <?php endif; ?>

                </div>


            </div>


        </main>

    </div>


    

    <?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>


<?php

    $rrDepartments = $departments->map(function ($department) {
        return [
            'id' => $department->id,
            'name' => $department->name,
        ];
    })->values()->all();

    $rrRooms = $rooms->map(function ($room) {
        return [
            'id' => $room->id,
            'department_id' => $room->department_id,
            'building_id' => $room->building_id,
            'name' => $room->room_name,
            'building_name' => optional($room->building)->name,
            'department_name' => optional($room->department)->name,
        ];
    })->values()->all();

    $rrSchedules = $schedules->map(function ($schedule) {
        return [
            'id' => $schedule->id,
            'room_id' => $schedule->room_id,
            'day' => strtoupper(trim($schedule->day)),
            'time' => trim($schedule->time),
            'course_code' => $schedule->course_code,
            'subject' => $schedule->subject,
            'instructor' => $schedule->instructor,
            'program' => $schedule->program,
            'year_level' => $schedule->year_level,
            'major' => $schedule->major,
            'color' => $schedule->color ?? '#ffffff',
            'row_position' => $schedule->row_position,
        ];
    })->values()->all();

    /*
    |--------------------------------------------------------------------------
    | ROOM-SPECIFIC PUBLISHED TIME ROWS
    |--------------------------------------------------------------------------
    |
    | These rows are saved by Set Schedule even when a cell is empty.
    | They are therefore the source for the complete timetable structure.
    |
    | We still combine them with published schedules below because a newly
    | added schedule can introduce a time range that was not previously in
    | room_schedule_time_slots.
    |
    */

    $rrRoomTimeSlots = collect($roomTimeSlots ?? [])->map(function ($slot) {
        return [
            'id' => $slot->id,
            'room_id' => $slot->room_id,
            'academic_term_id' => $slot->academic_term_id,
            'day' => strtoupper(trim((string) $slot->day)),
            'start_time' => $slot->start_time,
            'end_time' => $slot->end_time,
            'row_position' => $slot->row_position,
            'is_active' => (bool) $slot->is_active,
        ];
    })->values()->all();

    $rrReassignments = $reassignments->map(function ($item) {
        return [
            'id' => $item->id,
            'schedule_id' => $item->schedule_id,
            'original_room_id' => $item->original_room_id,
            'new_room_id' => $item->new_room_id,
            'day' => strtoupper(trim((string) $item->day)),
            'start_time' => $item->start_time,
            'end_time' => $item->end_time,
            'status' => (string) $item->status,
            'occupancy_decision' => (string) ($item->occupancy_decision ?? ''),
            'occupied_by' => (string) ($item->occupied_by ?? ''),
            'occupancy_purpose' => (string) ($item->occupancy_purpose ?? ''),
            'occupancy_details' => (string) ($item->occupancy_details ?? ''),
            'starts_at' => $item->starts_at?->toIso8601String(),
            'expires_at' => $item->expires_at?->toIso8601String(),
        ];
    })->values()->all();

?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DATA FROM LARAVEL
    |--------------------------------------------------------------------------
    */

    const departments = <?php echo json_encode($rrDepartments, 15, 512) ?>;
    const rooms = <?php echo json_encode($rrRooms, 15, 512) ?>;
    const schedules = <?php echo json_encode($rrSchedules, 15, 512) ?>;
    const roomTimeSlots = <?php echo json_encode($rrRoomTimeSlots, 15, 512) ?>;
    const reassignments = <?php echo json_encode($rrReassignments, 15, 512) ?>;
    const activeTermId = <?php echo json_encode($activeTerm?->id, 15, 512) ?>;
    const buildingsUrlTemplate = <?php echo json_encode(route('admin.roomreassignment.buildings', ['department' => '__DEPARTMENT__']), 512) ?>;
    const roomsUrlTemplate = <?php echo json_encode(route('admin.roomreassignment.rooms', ['building' => '__BUILDING__']), 512) ?>;

    const departmentLevel = document.getElementById('rrDepartmentLevel');
    const buildingLevel = document.getElementById('rrBuildingLevel');
    const roomLevel = document.getElementById('rrRoomLevel');
    const departmentCards = document.getElementById('rrDepartmentCards');
    const buildingCards = document.getElementById('rrBuildingCards');
    const roomCards = document.getElementById('rrRoomCards');
    const locationLoading = document.getElementById('rrLocationLoading');
    const loadingText = document.getElementById('rrLoadingText');
    const locationError = document.getElementById('rrLocationError');
    const breadcrumb = document.getElementById('rrBreadcrumb');

    let selectedDepartmentId = null;
    let selectedDepartmentName = '';
    let selectedBuildingId = null;
    let selectedBuildingName = '';
    let loadedRoomCards = [];

    function setLocationLoading(show, message = 'Loading...') {
        locationLoading.hidden = !show;
        loadingText.textContent = message;
    }

    function showLocationError(message) {
        locationError.textContent = message;
        locationError.hidden = false;
    }

    function clearLocationError() {
        locationError.textContent = '';
        locationError.hidden = true;
    }

    function updateBreadcrumb(step) {
        breadcrumb.querySelectorAll('.rr-crumb').forEach((crumb, index) => {
            crumb.classList.toggle('active', index + 1 === step);
        });
    }

    async function fetchJson(url) {
        const response = await fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        });
        if (!response.ok) {
            let message = 'Unable to load data. Please try again.';
            try {
                const body = await response.json();
                if (body && body.message) message = body.message;
            } catch (_) {}
            throw new Error(message);
        }
        return response.json();
    }

    function makeLocationCard({title, subtitle, icon, className = '', onClick}) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `rr-location-card ${className}`.trim();
        const iconSpan = document.createElement('span');
        iconSpan.className = 'rr-card-icon';
        iconSpan.textContent = icon;
        const titleSpan = document.createElement('span');
        titleSpan.className = 'rr-card-title';
        titleSpan.textContent = title || 'Unnamed';
        const subtitleSpan = document.createElement('span');
        subtitleSpan.className = 'rr-card-subtitle';
        subtitleSpan.textContent = subtitle || '';
        const arrow = document.createElement('span');
        arrow.className = 'rr-card-arrow';
        arrow.textContent = '›';
        button.append(iconSpan, titleSpan, subtitleSpan, arrow);
        button.addEventListener('click', onClick);
        return button;
    }

    async function openDepartment(departmentId, departmentName) {
        selectedDepartmentId = String(departmentId);
        selectedDepartmentName = departmentName;
        selectedBuildingId = null;
        selectedBuildingName = '';
        clearLocationError();
        clearSchedule();
        resetBuilding();
        resetRoom();
        buildingCards.replaceChildren();
        roomCards.replaceChildren();
        document.getElementById('rrBuildingHeading').textContent = `${departmentName} — Buildings`;
        document.getElementById('rrBuildingSubheading').textContent = 'Choose a building to load its rooms.';
        departmentLevel.hidden = true;
        buildingLevel.hidden = false;
        roomLevel.hidden = true;
        updateBreadcrumb(2);
        setLocationLoading(true, 'Loading buildings...');
        try {
            const url = buildingsUrlTemplate.replace('__DEPARTMENT__', encodeURIComponent(departmentId));
            const buildings = await fetchJson(url);
            buildings.forEach(building => {
                const option = new Option(building.name, building.id);
                buildingSelect.add(option);
                buildingCards.appendChild(makeLocationCard({
                    title: building.name,
                    subtitle: `${Number(building.rooms_count ?? 0)} room${Number(building.rooms_count ?? 0) === 1 ? '' : 's'} · Open building`,
                    icon: '▤',
                    onClick: () => openBuilding(building.id, building.name)
                }));
            });
            if (!buildings.length) buildingCards.innerHTML = '<div class="rr-empty-state">No buildings are assigned to this department.</div>';
        } catch (error) {
            showLocationError(error.message || 'Could not load buildings.');
            buildingCards.innerHTML = '<div class="rr-empty-state">Building list could not be loaded.</div>';
        } finally {
            setLocationLoading(false);
        }
    }

    async function openBuilding(buildingId, buildingName) {
        selectedBuildingId = String(buildingId);
        selectedBuildingName = buildingName;
        buildingSelect.value = selectedBuildingId;
        clearLocationError();
        clearSchedule();
        resetRoom();
        roomCards.replaceChildren();
        document.getElementById('rrRoomHeading').textContent = `${buildingName} — Rooms`;
        document.getElementById('rrRoomSubheading').textContent = 'Choose a room to view its timetable and reassign a scheduled class.';
        buildingLevel.hidden = true;
        roomLevel.hidden = false;
        updateBreadcrumb(3);
        setLocationLoading(true, 'Loading rooms...');
        try {
            const url = roomsUrlTemplate.replace('__BUILDING__', encodeURIComponent(buildingId));
            const result = await fetchJson(url);
            loadedRoomCards = Array.isArray(result) ? result : [];
            loadedRoomCards.forEach(room => {
                const option = new Option(room.name, room.id);
                roomSelect.add(option);
                roomCards.appendChild(makeLocationCard({
                    title: room.name,
                    subtitle: `${room.building_name || buildingName} · Open timetable`,
                    icon: '▣',
                    className: 'rr-room-card',
                    onClick: () => {
                        roomSelect.value = String(room.id);
                        roomSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }));
            });
            roomSelect.disabled = loadedRoomCards.length === 0;
            if (!loadedRoomCards.length) roomCards.innerHTML = '<div class="rr-empty-state">No rooms are assigned to this building.</div>';
        } catch (error) {
            showLocationError(error.message || 'Could not load rooms.');
            roomCards.innerHTML = '<div class="rr-empty-state">Room list could not be loaded.</div>';
        } finally {
            setLocationLoading(false);
        }
    }

    departmentCards.querySelectorAll('[data-department-id]').forEach(card => {
        card.addEventListener('click', () => {
            departmentSelect.value = card.dataset.departmentId;
            openDepartment(card.dataset.departmentId, card.dataset.departmentName);
        });
    });

    document.getElementById('rrBackDepartments').addEventListener('click', () => {
        selectedDepartmentId = null;
        selectedDepartmentName = '';
        selectedBuildingId = null;
        selectedBuildingName = '';
        clearLocationError();
        clearSchedule();
        resetBuilding();
        resetRoom();
        buildingCards.replaceChildren();
        roomCards.replaceChildren();
        buildingLevel.hidden = true;
        roomLevel.hidden = true;
        departmentLevel.hidden = false;
        updateBreadcrumb(1);
    });

    document.getElementById('rrBackBuildings').addEventListener('click', () => {
        selectedBuildingId = null;
        selectedBuildingName = '';
        clearLocationError();
        clearSchedule();
        resetRoom();
        roomCards.replaceChildren();
        roomLevel.hidden = true;
        buildingLevel.hidden = false;
        updateBreadcrumb(2);
    });

    // Reassignment visibility is time-aware on the client as well as the server.
    // This prevents a stale open page from showing a reassignment after expiry.
    function isReassignmentActive(item) {
        if (!item || String(item.status).toLowerCase() !== 'active') {
            return false;
        }

        const now = Date.now();

        if (item.starts_at) {
            const startsAt = new Date(item.starts_at).getTime();
            if (!Number.isFinite(startsAt) || startsAt > now) return false;
        }

        if (item.expires_at) {
            const expiresAt = new Date(item.expires_at).getTime();
            if (!Number.isFinite(expiresAt) || expiresAt <= now) return false;
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const departmentSelect =
        document.getElementById('rrDepartment');

    const buildingSelect =
        document.getElementById('rrBuilding');

    const roomSelect =
        document.getElementById('rrRoom');

    const selectedRoomDisplay =
        document.getElementById('selectedRoomDisplay');

    const selectedRoomName =
        document.getElementById('selectedRoomName');

    const schedulePanel =
        document.getElementById('schedulePanel');

    const scheduleBody =
        document.getElementById('rrScheduleBody');

    const formPanel =
        document.getElementById('reassignmentFormPanel');

    const newRoomSelect =
        document.getElementById('rrNewRoom');

    const scheduleIdInput =
        document.getElementById('rrScheduleId');

    const originalRoomInput =
        document.getElementById('rrOriginalRoomId');

    const dayInput =
        document.getElementById('rrDay');

    const timeInput =
        document.getElementById('rrTime');

    const submitButton = document.getElementById('rrSubmit');

    let selectedSchedule = null;


    /*
    |--------------------------------------------------------------------------
    | DAYS
    |--------------------------------------------------------------------------
    */

    const leftDays = [
        'MON',
        'WED',
        'FRI'
    ];

    const rightDays = [
        'TUE',
        'THU',
        'SAT'
    ];


    /*
    |--------------------------------------------------------------------------
    | TIME HELPERS
    |--------------------------------------------------------------------------
    */

    function normalizeClock(value) {

        if (
            value === null ||
            value === undefined
        ) {
            return null;
        }

        let text =
            String(value)
                .trim()
                .toUpperCase()
                .replace(/\s+/g, ' ');

        const twelve =
            text.match(
                /^(\d{1,2}):(\d{2})\s*(AM|PM)$/
            );

        if (twelve) {

            let hour =
                parseInt(
                    twelve[1],
                    10
                );

            const minute =
                twelve[2];

            const period =
                twelve[3];

            if (
                period === 'AM' &&
                hour === 12
            ) {
                hour = 0;
            }

            if (
                period === 'PM' &&
                hour !== 12
            ) {
                hour += 12;
            }

            return (
                String(hour).padStart(2, '0')
                +
                ':'
                +
                minute
            );
        }

        const twentyFour =
            text.match(
                /^(\d{1,2}):(\d{2})(?::\d{2})?$/
            );

        if (twentyFour) {

            let hour = parseInt(twentyFour[1], 10);
            const minute = twentyFour[2];

            /*
             * Set Schedule sometimes stores Excel-style times without AM/PM.
             * In this campus timetable, 1:00-5:00 means afternoon, while
             * 8:00-11:00 means morning. Noon remains 12:00.
             */
            if (hour >= 1 && hour <= 5) {
                hour += 12;
            }

            if (hour < 0 || hour > 23 || Number(minute) > 59) {
                return null;
            }

            return (
                String(hour).padStart(2, '0')
                + ':' + minute
            );
        }

        return null;
    }


    function toMinutes(value) {

        const normalized =
            normalizeClock(value);

        if (!normalized) {
            return null;
        }

        const parts =
            normalized.split(':');

        return (
            parseInt(parts[0], 10) * 60
            +
            parseInt(parts[1], 10)
        );
    }


    function minutesToClock(minutes) {

        const hour =
            Math.floor(minutes / 60);

        const minute =
            minutes % 60;

        return (
            String(hour).padStart(2, '0')
            +
            ':'
            +
            String(minute).padStart(2, '0')
        );
    }


    function parseTimeRange(value) {

        if (!value) {
            return null;
        }

        const parts =
            String(value)
                .replace(/[–—]/g, '-')
                .split('-')
                .map(function (part) {
                    return part.trim();
                });

        if (parts.length !== 2) {
            return null;
        }

        const start =
            normalizeClock(parts[0]);

        const end =
            normalizeClock(parts[1]);

        if (!start || !end) {
            return null;
        }

        const startMinutes =
            toMinutes(start);

        const endMinutes =
            toMinutes(end);

        if (
            startMinutes === null ||
            endMinutes === null ||
            endMinutes <= startMinutes
        ) {
            return null;
        }

        return {
            start: start,
            end: end,
            startMinutes: startMinutes,
            endMinutes: endMinutes
        };
    }


    function formatClock(value) {

        const minutes =
            toMinutes(value);

        if (minutes === null) {
            return value || '';
        }

        let hour =
            Math.floor(minutes / 60);

        const minute =
            minutes % 60;

        const period =
            hour >= 12
                ? 'PM'
                : 'AM';

        if (hour === 0) {
            hour = 12;
        } else if (hour > 12) {
            hour -= 12;
        }

        return (
            hour
            +
            ':'
            +
            String(minute).padStart(2, '0')
            +
            ' '
            +
            period
        );
    }


    function normalizeDay(value) {

        if (!value) {
            return '';
        }

        const text =
            String(value)
                .trim()
                .toUpperCase();

        const aliases = {
            'MONDAY': 'MON',
            'TUESDAY': 'TUE',
            'WEDNESDAY': 'WED',
            'THURSDAY': 'THU',
            'FRIDAY': 'FRI',
            'SATURDAY': 'SAT',
            'SUNDAY': 'SUN',
            'TUES': 'TUE',
            'WEDS': 'WED',
            'THURS': 'THU'
        };

        return aliases[text] || text;
    }


    function rangesOverlap(
        first,
        second
    ) {

        if (!first || !second) {
            return false;
        }

        return (
            first.startMinutes <
            second.endMinutes
            &&
            first.endMinutes >
            second.startMinutes
        );
    }


    function scheduleOverlapsCell(
        scheduleTime,
        cellTime
    ) {

        return rangesOverlap(
            parseTimeRange(scheduleTime),
            parseTimeRange(cellTime)
        );
    }


    function timeOverlap(
        start,
        end,
        range
    ) {

        return rangesOverlap(
            parseTimeRange(
                String(start) +
                '-' +
                String(end)
            ),
            parseTimeRange(range)
        );
    }


  

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT / BUILDING / ROOM CARD NAVIGATION
    |--------------------------------------------------------------------------
    |
    | Building and room data are fetched only after the user opens that level.
    | The hidden selects remain as compatibility controls for the timetable.
    */

    /*
    |--------------------------------------------------------------------------
    | ROOM -> SCHEDULE
    |--------------------------------------------------------------------------
    */

    roomSelect.addEventListener(
        'change',
        function () {

            clearSelection();

            const roomId =
                this.value;

            if (!roomId) {
                clearSchedule();
                return;
            }

            const room =
                loadedRoomCards.find(item => String(item.id) === String(roomId))
                || rooms.find(item => String(item.id) === String(roomId));


            if (!room) {
                return;
            }


            selectedRoomName.textContent =
                room.name;

            selectedRoomDisplay.style.display =
                'block';

            schedulePanel.style.display =
                'block';

            renderSchedule(room.id);
        }
    );



/*
|--------------------------------------------------------------------------
| COMPLETE PUBLISHED TIMETABLE ROW BUILDER
|--------------------------------------------------------------------------
|
| Room Reassignment uses TWO sources:
|
| 1. room_schedule_time_slots
|    -> preserves EMPTY rows from the published Set Schedule.
|
| 2. schedules
|    -> guarantees that a newly published schedule such as
|       MON 4:00 PM - 5:00 PM is also included immediately.
|
| These are UNIONED, not used as fallbacks.
|
| Therefore:
|
| 8:00-9:00  empty
| 9:00-10:00 empty
| 4:00-5:00  IT XXX
|
| can all appear in the same room.
|
| Horizontal schedule merging is NEVER performed.
| MON/WED/FRI and TUE/THU/SAT are always independent cells.
|--------------------------------------------------------------------------
*/

function buildPublishedTimeRows(roomId) {

    const ranges = new Map();

    /*
    |--------------------------------------------------------------------------
    | 1. ROOM-SPECIFIC TIME SLOTS
    |--------------------------------------------------------------------------
    */

    roomTimeSlots.forEach(function (slot) {

        if (
            String(slot.room_id) !==
            String(roomId)
        ) {
            return;
        }

        if (
            activeTermId !== null &&
            activeTermId !== undefined &&
            Number(slot.academic_term_id) !==
            Number(activeTermId)
        ) {
            return;
        }

        if (slot.is_active === false) {
            return;
        }

        const start =
            normalizeClock(slot.start_time);

        const end =
            normalizeClock(slot.end_time);

        if (!start || !end) {
            return;
        }

        const startMinutes =
            toMinutes(start);

        const endMinutes =
            toMinutes(end);

        if (
            startMinutes === null ||
            endMinutes === null ||
            endMinutes <= startMinutes
        ) {
            return;
        }

        // 12:00 PM-1:00 PM is always the visual noon break, never a schedule row.
        if (startMinutes === 720 && endMinutes === 780) {
            return;
        }

        const key =
            startMinutes +
            '-' +
            endMinutes;

        if (!ranges.has(key)) {

            ranges.set(key, {
                start: start,
                end: end,
                startMinutes: startMinutes,
                endMinutes: endMinutes,
                rowPosition:
                    slot.row_position !== null &&
                    slot.row_position !== undefined
                        ? Number(slot.row_position)
                        : 999999
            });

        } else {

            const existing =
                ranges.get(key);

            if (
                existing.rowPosition === 999999 &&
                slot.row_position !== null &&
                slot.row_position !== undefined
            ) {
                existing.rowPosition =
                    Number(slot.row_position);
            }
        }
    });


    /*
    |--------------------------------------------------------------------------
    | 2. ALWAYS ADD PUBLISHED SCHEDULE TIMES
    |--------------------------------------------------------------------------
    |
    | This is the important fix.
    |
    | We do NOT check:
    |
    |     if (ranges.size === 0)
    |
    | because a room can already have empty timetable rows and then receive
    | a new schedule at a completely different time.
    |
    */

    schedules.forEach(function (schedule) {

        if (
            String(schedule.room_id) !==
            String(roomId)
        ) {
            return;
        }

        const range =
            parseTimeRange(schedule.time);

        if (!range) {
            return;
        }

        // Never treat the noon-break interval as a schedulable row.
        if (range.startMinutes === 720 && range.endMinutes === 780) {
            return;
        }

        const key =
            range.startMinutes +
            '-' +
            range.endMinutes;

        if (!ranges.has(key)) {

            ranges.set(key, {
                start: range.start,
                end: range.end,
                startMinutes: range.startMinutes,
                endMinutes: range.endMinutes,
                rowPosition:
                    schedule.row_position !== null &&
                    schedule.row_position !== undefined
                        ? Number(schedule.row_position)
                        : 999999
            });

        } else {

            const existing =
                ranges.get(key);

            if (
                existing.rowPosition === 999999 &&
                schedule.row_position !== null &&
                schedule.row_position !== undefined
            ) {
                existing.rowPosition =
                    Number(schedule.row_position);
            }
        }
    });


    /*
    |--------------------------------------------------------------------------
    | 3. ACTIVE INCOMING REASSIGNMENTS
    |--------------------------------------------------------------------------
    |
    | If a schedule is reassigned into this room, make sure its time row
    | exists even when the target room had no previous row at that time.
    |
    */

    reassignments.forEach(function (item) {

        if (!isReassignmentActive(item)) {
            return;
        }

        if (
            String(item.new_room_id) !==
            String(roomId)
        ) {
            return;
        }

        const start =
            normalizeClock(item.start_time);

        const end =
            normalizeClock(item.end_time);

        if (!start || !end) {
            return;
        }

        const startMinutes =
            toMinutes(start);

        const endMinutes =
            toMinutes(end);

        if (
            startMinutes === null ||
            endMinutes === null ||
            endMinutes <= startMinutes
        ) {
            return;
        }

        const key =
            startMinutes +
            '-' +
            endMinutes;

        if (!ranges.has(key)) {

            ranges.set(key, {
                start: start,
                end: end,
                startMinutes: startMinutes,
                endMinutes: endMinutes,
                rowPosition: 999999
            });
        }
    });


    /*
    |--------------------------------------------------------------------------
    | SORT
    |--------------------------------------------------------------------------
    */

    const sortedRanges =
        Array.from(
            ranges.values()
        );

    sortedRanges.sort(function (a, b) {

        if (
            a.startMinutes !==
            b.startMinutes
        ) {
            return (
                a.startMinutes -
                b.startMinutes
            );
        }

        if (
            a.endMinutes !==
            b.endMinutes
        ) {
            return (
                a.endMinutes -
                b.endMinutes
            );
        }

        return (
            a.rowPosition -
            b.rowPosition
        );
    });


    /*
    |--------------------------------------------------------------------------
    | BUILD FINAL ROWS
    |--------------------------------------------------------------------------
    */

    const rows = [];

    sortedRanges.forEach(function (range, index) {

        if (index > 0) {

            const previous =
                sortedRanges[index - 1];

            if (
                previous.endMinutes <= 720 &&
                range.startMinutes >= 780
            ) {

                rows.push({
                    type: 'NOON_BREAK'
                });
            }
        }

        rows.push({
            type: 'TIME',
            value:
                range.start +
                '-' +
                range.end,
            start:
                range.start,
            end:
                range.end,
            startMinutes:
                range.startMinutes,
            endMinutes:
                range.endMinutes
        });
    });

    return rows;
}


/*
|--------------------------------------------------------------------------
| TIME CELL
|--------------------------------------------------------------------------
*/

function createTimeCell(time) {

    const cell =
        document.createElement('td');

    cell.className =
        'rr-time';

    const range =
        parseTimeRange(time);

    if (range) {

        cell.textContent =
            formatClock(range.start) +
            ' - ' +
            formatClock(range.end);

    } else {

        cell.textContent =
            time || '';
    }

    return cell;
}


/*
|--------------------------------------------------------------------------
| REPLACE ORIGINAL SCHEDULE LOOKUP
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| Match the exact published time range first.
|
| Do NOT use an overlap fallback here because a long schedule such as
| 7:00-9:00 must remain one 7:00-9:00 row and must not be duplicated into
| another 8:00-9:00 row.
|--------------------------------------------------------------------------
*/

function findOriginalSchedule(
    roomId,
    day,
    time
) {

    const normalizedDay =
        normalizeDay(day);

    return schedules.find(function (item) {

        return (
            String(item.room_id) ===
            String(roomId)

            &&

            normalizeDay(item.day) ===
            normalizedDay

            &&

            scheduleRangesEqual(
                item.time,
                time
            )
        );

    }) || null;
}


/*
|--------------------------------------------------------------------------
| COMPARE TIME RANGES
|--------------------------------------------------------------------------
*/

function scheduleRangesEqual(
    first,
    second
) {

    const firstRange =
        parseTimeRange(first);

    const secondRange =
        parseTimeRange(second);

    if (
        !firstRange ||
        !secondRange
    ) {
        return false;
    }

    return (
        firstRange.startMinutes ===
        secondRange.startMinutes

        &&

        firstRange.endMinutes ===
        secondRange.endMinutes
    );
}


/*
|--------------------------------------------------------------------------
| FIND INCOMING REASSIGNMENT
|--------------------------------------------------------------------------
*/

function findIncomingReassignment(
    roomId,
    day,
    time
) {

    const normalizedDay =
        normalizeDay(day);

    return reassignments.find(function (item) {

        return (
            isReassignmentActive(item)

            &&

            String(item.new_room_id) ===
            String(roomId)

            &&

            normalizeDay(item.day) ===
            normalizedDay

            &&

            timeOverlap(
                item.start_time,
                item.end_time,
                time
            )
        );

    }) || null;
}


/*
|--------------------------------------------------------------------------
| FIND OUTGOING REASSIGNMENT
|--------------------------------------------------------------------------
*/

function findOutgoingReassignment(
    schedule,
    roomId,
    day,
    time
) {

    if (!schedule) {
        return null;
    }

    const normalizedDay =
        normalizeDay(day);

    return reassignments.find(function (item) {

        return (
            isReassignmentActive(item)

            &&

            Number(item.schedule_id) ===
            Number(schedule.id)

            &&

            String(item.original_room_id) ===
            String(roomId)

            &&

            normalizeDay(item.day) ===
            normalizedDay

            &&

            timeOverlap(
                item.start_time,
                item.end_time,
                time
            )
        );

    }) || null;
}


/*
|--------------------------------------------------------------------------
| RENDER COMPLETE ROOM TIMETABLE
|--------------------------------------------------------------------------
*/

function renderSchedule(roomId) {

    scheduleBody.innerHTML = '';

    const timeRows =
        buildPublishedTimeRows(roomId);

    if (!timeRows.length) {

        const row =
            document.createElement('tr');

        const cell =
            document.createElement('td');

        cell.colSpan = 8;
        cell.style.padding = '30px';
        cell.style.textAlign = 'center';
        cell.style.color = '#777';

        cell.textContent =
            'No published timetable rows for this room in the selected academic term.';

        row.appendChild(cell);
        scheduleBody.appendChild(row);

        return;
    }


    let previousEnd =
        null;


    timeRows.forEach(function (rowData) {

        if (
            rowData.type ===
            'NOON_BREAK'
        ) {

            const noonRow =
                document.createElement('tr');

            noonRow.className =
                'rr-noon-break';

            const noonCell =
                document.createElement('td');

            noonCell.colSpan = 8;
            noonCell.textContent =
                'N O O N   B R E A K';

            noonRow.appendChild(noonCell);
            scheduleBody.appendChild(noonRow);

            return;
        }


        const row =
            document.createElement('tr');


        /*
        |--------------------------------------------------------------------------
        | LEFT TIME + MON/WED/FRI
        |--------------------------------------------------------------------------
        */

        row.appendChild(
            createTimeCell(
                rowData.value
            )
        );

        leftDays.forEach(function (day) {

            const schedule =
                findOriginalSchedule(
                    roomId,
                    day,
                    rowData.value
                );

            const incoming =
                findIncomingReassignment(
                    roomId,
                    day,
                    rowData.value
                );

            let actualSchedule =
                schedule;

            if (
                !actualSchedule &&
                incoming
            ) {

                actualSchedule =
                    schedules.find(function (item) {

                        return (
                            Number(item.id) ===
                            Number(
                                incoming.schedule_id
                            )
                        );
                    }) || null;
            }

            row.appendChild(
                createScheduleCell(
                    roomId,
                    day,
                    rowData.value,
                    actualSchedule,
                    incoming
                )
            );
        });


        /*
        |--------------------------------------------------------------------------
        | RIGHT TIME + TUE/THU/SAT
        |--------------------------------------------------------------------------
        */

        row.appendChild(
            createTimeCell(
                rowData.value
            )
        );

        rightDays.forEach(function (day) {

            const schedule =
                findOriginalSchedule(
                    roomId,
                    day,
                    rowData.value
                );

            const incoming =
                findIncomingReassignment(
                    roomId,
                    day,
                    rowData.value
                );

            let actualSchedule =
                schedule;

            if (
                !actualSchedule &&
                incoming
            ) {

                actualSchedule =
                    schedules.find(function (item) {

                        return (
                            Number(item.id) ===
                            Number(
                                incoming.schedule_id
                            )
                        );
                    }) || null;
            }

            row.appendChild(
                createScheduleCell(
                    roomId,
                    day,
                    rowData.value,
                    actualSchedule,
                    incoming
                )
            );
        });


        scheduleBody.appendChild(row);

        previousEnd =
            rowData.endMinutes;
    });
}


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE CELL
    |--------------------------------------------------------------------------
    */

    function createScheduleCell(
        roomId,
        day,
        time,
        suppliedSchedule = null,
        suppliedIncoming = null,
        rowSpan = 1
    ) {

        const cell =
            document.createElement(
                'td'
            );

        cell.className =
            'rr-cell';


        let schedule =
            suppliedSchedule;


        let incoming =
            suppliedIncoming;


        if (!schedule) {

            schedule =
                findOriginalSchedule(
                    roomId,
                    day,
                    time
                );
        }


        if (!incoming) {

            incoming =
                findIncomingReassignment(
                    roomId,
                    day,
                    time
                );
        }


        /*
        |--------------------------------------------------------------------------
        | INCOMING REASSIGNMENT
        |--------------------------------------------------------------------------
        */

        if (
            !schedule &&
            incoming
        ) {

            schedule =
                schedules.find(
                    function (item) {

                        return (
                            Number(item.id)
                            ===
                            Number(
                                incoming.schedule_id
                            )
                        );
                    }
                ) || null;
        }


        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        if (!schedule) {

            cell.classList.add(
                'empty'
            );

            cell.innerHTML =
                '<span>Empty</span>';

            return cell;
        }


        /*
        |--------------------------------------------------------------------------
        | OUTGOING
        |--------------------------------------------------------------------------
        */

        const outgoing =
            findOutgoingReassignment(
                schedule,
                roomId,
                day,
                time
            );


        /*
        |--------------------------------------------------------------------------
        | ROWSPAN
        |--------------------------------------------------------------------------
        */

        if (rowSpan > 1) {

            cell.rowSpan =
                rowSpan;
        }


        /*
        |--------------------------------------------------------------------------
        | OUTGOING SCHEDULE
        |--------------------------------------------------------------------------
        */

        if (outgoing) {

            cell.classList.add(
                'rr-reassigned'
            );

            cell.style.backgroundColor =
                '#fff7ed';

            if (outgoing.occupancy_decision === 'yes') {
                cell.style.backgroundColor = '#fee2e2';
                cell.innerHTML = '<div class="rr-reassigned-label">ROOM OCCUPIED</div>' +
                    '<div class="rr-instructor">' + escapeHtml(outgoing.occupied_by || 'Admin') + '</div>' +
                    '<div class="rr-subject">' + escapeHtml(outgoing.occupancy_purpose || '') + '</div>' +
                    (outgoing.occupancy_details ? '<div class="rr-program">' + escapeHtml(outgoing.occupancy_details) + '</div>' : '');
            } else {
                cell.innerHTML = buildScheduleHtml(schedule, 'REASSIGNED TO OTHER ROOM');
            }

            cell.style.cursor =
                'not-allowed';

            return cell;
        }


        /*
        |--------------------------------------------------------------------------
        | INCOMING SCHEDULE
        |--------------------------------------------------------------------------
        */

        if (incoming) {

            cell.classList.add(
                'rr-reassigned'
            );

            cell.style.backgroundColor =
                schedule.color ||
                '#ffffff';

            cell.innerHTML =
                buildScheduleHtml(
                    schedule,
                    'REASSIGNED HERE'
                );

            cell.style.cursor =
                'not-allowed';

            return cell;
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL SCHEDULE
        |--------------------------------------------------------------------------
        */

        cell.style.backgroundColor =
            schedule.color ||
            '#ffffff';

        cell.innerHTML =
            buildScheduleHtml(
                schedule,
                ''
            );


        cell.addEventListener(
            'click',
            function () {

                selectSchedule(
                    schedule,
                    cell
                );
            }
        );


        return cell;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE CONTENT
    |--------------------------------------------------------------------------
    */

    function buildScheduleHtml(
        schedule,
        label
    ) {

        return `

            <div class="rr-course">
                ${escapeHtml(
                    schedule.course_code || ''
                )}
            </div>

            <div class="rr-subject">
                ${escapeHtml(
                    schedule.subject || ''
                )}
            </div>

            <div class="rr-instructor">
                ${escapeHtml(
                    schedule.instructor || ''
                )}
            </div>

            ${
                schedule.program
                ?
                `
                    <div class="rr-program">
                        ${escapeHtml(
                            schedule.program
                        )}
                    </div>
                `
                :
                ''
            }

            ${
                schedule.year_level
                ?
                `
                    <div class="rr-program">
                        ${escapeHtml(
                            schedule.year_level
                        )}
                    </div>
                `
                :
                ''
            }

            ${
                schedule.major
                ?
                `
                    <div class="rr-program">
                        ${escapeHtml(
                            schedule.major
                        )}
                    </div>
                `
                :
                ''
            }

            ${
                label
                ?
                `
                    <div class="rr-reassigned-label">
                        ${label}
                    </div>
                `
                :
                ''
            }

        `;
    }


    /*
    |--------------------------------------------------------------------------
    | SELECT SCHEDULE
    |--------------------------------------------------------------------------
    */

    function selectSchedule(
        schedule,
        cell
    ) {

        document
            .querySelectorAll(
                '.rr-cell.selected'
            )
            .forEach(
                function (item) {

                    item.classList.remove(
                        'selected'
                    );
                }
            );


        cell.classList.add(
            'selected'
        );


        selectedSchedule =
            schedule;


        scheduleIdInput.value =
            schedule.id;

        originalRoomInput.value =
            schedule.room_id;

        dayInput.value =
            schedule.day;

        timeInput.value =
            schedule.time;


        document.getElementById(
            'rrSelectedDay'
        ).textContent =
            schedule.day;

        document.getElementById(
            'rrSelectedTime'
        ).textContent =
            schedule.time;

        document.getElementById('rrSelectedCourse').textContent = schedule.course_code || '-';
        document.getElementById('rrSelectedProgram').textContent = schedule.program || schedule.year_level || '-';
        document.getElementById('rrSelectedInstructor').textContent = schedule.instructor || '-';

        document.getElementById(
            'rrSelectedRoom'
        ).textContent =
            roomSelect.options[
                roomSelect.selectedIndex
            ].text;


        buildAvailableRooms(schedule);
        showWizardStep('details');
        document.getElementById('rrWizardBackdrop').style.display = 'flex';
    }


    /*
    |--------------------------------------------------------------------------
    | AVAILABLE ROOMS
    |--------------------------------------------------------------------------
    */

    function buildAvailableRooms(schedule) {
        newRoomSelect.innerHTML = '<option value="">Select Available Room</option>';
        const cards = document.getElementById('rrAvailableRoomCards');
        cards.replaceChildren();
        let count = 0;

        rooms.forEach(function (room) {
            if (String(room.id) === String(schedule.room_id)) return;
            const occupied = schedules.some(function (item) {
                return String(item.room_id) === String(room.id) &&
                    normalizeDay(item.day) === normalizeDay(schedule.day) &&
                    scheduleOverlapsCell(item.time, schedule.time);
            });
            const hasReassignment = reassignments.some(function (item) {
                return String(item.new_room_id) === String(room.id) &&
                    normalizeDay(item.day) === normalizeDay(schedule.day) &&
                    timeOverlap(item.start_time, item.end_time, schedule.time);
            });
            if (occupied || hasReassignment) return;

            count++;
            const option = document.createElement('option');
            option.value = room.id;
            option.textContent = room.name + ' — ' + (room.building_name || 'Building');
            newRoomSelect.appendChild(option);

            const card = document.createElement('button');
            card.type = 'button';
            card.className = 'rr-room-choice';
            card.innerHTML = '<strong>' + escapeHtml(room.name) + '</strong>' +
                '<span>' + escapeHtml(room.building_name || 'Building') + '</span>' +
                '<span>' + escapeHtml(room.department_name || 'Department') + '</span>';
            card.addEventListener('click', function () {
                newRoomSelect.value = String(room.id);
                document.getElementById('rrNewRoomId').value = String(room.id);
                document.getElementById('rrConfirmBuilding').textContent = room.building_name || 'N/A';
                document.getElementById('rrConfirmDepartment').textContent = room.department_name || 'N/A';
                document.getElementById('rrConfirmRoom').textContent = room.name;
                document.getElementById('rrConfirmDay').textContent = schedule.day;
                document.getElementById('rrConfirmTime').textContent = schedule.time;
                showWizardStep('confirm');
            });
            cards.appendChild(card);
        });
        if (!count) cards.innerHTML = '<div class="rr-room-unavailable">No available rooms were found for this day and time.</div>';
    }

    function showWizardStep(step) {
        document.querySelectorAll('[data-rr-step]').forEach(function (panel) {
            panel.hidden = panel.dataset.rrStep !== step;
        });
        const titles = {
            details: 'Reassign Schedule', rooms: 'Available Rooms', confirm: 'Confirm Selection',
            'occupy-decision': 'Room Reassignment Complete', 'occupy-yes': 'Occupy Old Room', 'occupy-no': 'Keep Old Room Unoccupied'
        };
        document.getElementById('rrModalTitle').textContent = titles[step] || 'Reassign Schedule';
    }

    function closeWizard() {
        document.getElementById('rrWizardBackdrop').style.display = 'none';
    }

    document.querySelectorAll('[data-rr-close]').forEach(function (button) {
        button.addEventListener('click', function () {
            closeWizard();
            clearSelection();
        });
    });
    document.querySelectorAll('[data-rr-back]').forEach(function (button) {
        button.addEventListener('click', function () { showWizardStep(button.dataset.rrBack); });
    });
    document.getElementById('rrShowRooms').addEventListener('click', function () {
        document.getElementById('rrReason').value = document.getElementById('rrReasonSelect').value;
        document.getElementById('rrDetails').value = document.getElementById('rrReasonDetails').value;
        showWizardStep('rooms');
    });
    document.getElementById('rrConfirmRoomButton').addEventListener('click', function () {
        if (!document.getElementById('rrNewRoomId').value) return;
        const room = rooms.find(function (item) { return String(item.id) === String(document.getElementById('rrNewRoomId').value); });
        const original = rooms.find(function (item) { return String(item.id) === String(document.getElementById('rrOriginalRoomId').value); });
        document.getElementById('rrOldRoomName').textContent = original ? original.name : (document.getElementById('rrSelectedRoom').textContent || 'Old room');
        document.getElementById('rrOldRoomDay').textContent = document.getElementById('rrDay').value;
        document.getElementById('rrOldRoomTime').textContent = document.getElementById('rrTime').value;
        document.querySelectorAll('.rrOldRoomNameCopy').forEach(function (el) { el.textContent = document.getElementById('rrOldRoomName').textContent; });
        document.querySelectorAll('.rrOldRoomDayCopy').forEach(function (el) { el.textContent = document.getElementById('rrOldRoomDay').textContent; });
        document.querySelectorAll('.rrOldRoomTimeCopy').forEach(function (el) { el.textContent = document.getElementById('rrOldRoomTime').textContent; });
        showWizardStep('occupy-decision');
    });
    document.getElementById('rrChooseYes').addEventListener('click', function () {
        document.getElementById('rrOccupancyDecision').value = 'yes';
        document.getElementById('rrOccupantInput').required = true;
        document.getElementById('rrPurposeSelect').required = true;
        document.getElementById('rrNotOccupiedReasonInput').required = false;
        showWizardStep('occupy-yes');
    });
    document.getElementById('rrChooseNo').addEventListener('click', function () {
        document.getElementById('rrOccupancyDecision').value = 'no';
        document.getElementById('rrOccupantInput').required = false;
        document.getElementById('rrPurposeSelect').required = false;
        document.getElementById('rrNotOccupiedReasonInput').required = true;
        showWizardStep('occupy-no');
    });
    document.getElementById('rrForm').addEventListener('submit', function (event) {
        if (document.getElementById('rrOccupancyDecision').value === 'yes') {
            document.getElementById('rrOccupiedBy').value = document.getElementById('rrOccupantInput').value.trim();
            document.getElementById('rrOccupancyPurpose').value = document.getElementById('rrPurposeSelect').value;
            document.getElementById('rrOccupancyDetails').value = document.getElementById('rrOccupancyDetailsInput').value.trim();
            document.getElementById('rrNotOccupiedReason').value = '';
            document.getElementById('rrNotOccupiedDetails').value = '';
        } else if (document.getElementById('rrOccupancyDecision').value === 'no') {
            document.getElementById('rrNotOccupiedReason').value = document.getElementById('rrNotOccupiedReasonInput').value;
            document.getElementById('rrNotOccupiedDetails').value = document.getElementById('rrNotOccupiedDetailsInput').value.trim();
            document.getElementById('rrOccupiedBy').value = '';
            document.getElementById('rrOccupancyPurpose').value = '';
            document.getElementById('rrOccupancyDetails').value = '';
        } else {
            event.preventDefault();
        }
    });


    /*
    |--------------------------------------------------------------------------
    | NEW ROOM VALIDATION
    |--------------------------------------------------------------------------
    */




    /*
    |--------------------------------------------------------------------------
    | CLEAR BUTTON
    |--------------------------------------------------------------------------
    */




    /*
    |--------------------------------------------------------------------------
    | CLEAR SELECTION
    |--------------------------------------------------------------------------
    */

    function clearSelection() {

        selectedSchedule =
            null;

        scheduleIdInput.value =
            '';

        originalRoomInput.value =
            '';

        dayInput.value =
            '';

        timeInput.value =
            '';


        document.getElementById(
            'rrSelectedDay'
        ).textContent =
            '-';

        document.getElementById(
            'rrSelectedTime'
        ).textContent =
            '-';

        document.getElementById(
            'rrSelectedCourse'
        ).textContent =
            '-';

        document.getElementById(
            'rrSelectedRoom'
        ).textContent =
            '-';


        newRoomSelect.innerHTML = `
            <option value="">
                Select Available Room
            </option>
        `;


        if (submitButton) submitButton.disabled = true;
        const newRoomHidden = document.getElementById('rrNewRoomId');
        if (newRoomHidden) newRoomHidden.value = '';
        const decisionHidden = document.getElementById('rrOccupancyDecision');
        if (decisionHidden) decisionHidden.value = '';


        document
            .querySelectorAll(
                '.rr-cell.selected'
            )
            .forEach(
                function (cell) {

                    cell.classList.remove(
                        'selected'
                    );
                }
            );


        if (formPanel) formPanel.style.display = 'none';
    }


    /*
    |--------------------------------------------------------------------------
    | RESET BUILDING
    |--------------------------------------------------------------------------
    */

    function resetBuilding() {

        buildingSelect.innerHTML = `
            <option value="">
                Select Building
            </option>
        `;

        buildingSelect.disabled =
            true;
    }


    /*
    |--------------------------------------------------------------------------
    | RESET ROOM
    |--------------------------------------------------------------------------
    */

    function resetRoom() {

        roomSelect.innerHTML = `
            <option value="">
                Select Room
            </option>
        `;

        roomSelect.disabled =
            true;

        selectedRoomDisplay.style.display =
            'none';
    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR SCHEDULE
    |--------------------------------------------------------------------------
    */

    function clearSchedule() {

        schedulePanel.style.display =
            'none';

        if (formPanel) formPanel.style.display = 'none';

        scheduleBody.innerHTML =
            '';

        clearSelection();
    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement(
                'div'
            );

        div.textContent =
            value ?? '';

        return div.innerHTML;
    }

    // Refresh just after the next active reassignment expires so the original
    // published schedule is shown again without changing the schedules table.
    function scheduleReassignmentExpirationRefresh() {
        const now = Date.now();
        const expirations = reassignments
            .filter(function (item) {
                if (String(item.status).toLowerCase() !== 'active' || !item.expires_at) {
                    return false;
                }
                const expiry = new Date(item.expires_at).getTime();
                return Number.isFinite(expiry) && expiry > now;
            })
            .map(function (item) {
                return new Date(item.expires_at).getTime();
            });

        if (!expirations.length) return;

        const nextExpiry = Math.min(...expirations);
        const delay = nextExpiry - now + 1000;

        // Avoid browser timeout overflow by refreshing in manageable intervals.
        window.setTimeout(function () {
            if (Date.now() >= nextExpiry) {
                window.location.reload();
            } else {
                scheduleReassignmentExpirationRefresh();
            }
        }, Math.min(Math.max(delay, 1000), 2147483647));
    }

    scheduleReassignmentExpirationRefresh();

});


</script>
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/admin/roomreassignment.blade.php ENDPATH**/ ?>
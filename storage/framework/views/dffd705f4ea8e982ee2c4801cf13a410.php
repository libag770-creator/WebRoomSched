<div class="wrapper">

    <?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main-layout">

        <?php echo $__env->make('admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="content">

            <style>

                body {
                    background: #f5f5f5;
                }

                .title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .subtitle {
                    color: #777;
                    margin-bottom: 20px;
                }

                /* SUCCESS / ERROR */

                .success-message {
    background: #d4edda;
    color: #155724;
    padding: 14px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    border-left: 5px solid #2e7d32;
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
}


/* =========================================================
   PROFESSIONAL SUCCESS POPUP
   ========================================================= */

.success-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(3px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
    z-index: 99999;
    animation: successFadeIn 0.25s ease;
}

.success-modal {
    position: relative;
    width: 420px;
    max-width: 100%;
    background: #ffffff;
    border-radius: 18px;
    padding: 38px 35px 32px;
    text-align: center;
    box-sizing: border-box;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.20);
    animation: successPop 0.30s ease;
}

.success-close {
    position: absolute;
    top: 12px;
    right: 15px;
    width: 34px;
    height: 34px;
    border: none;
    background: transparent;
    color: #777777;
    font-size: 28px;
    line-height: 30px;
    cursor: pointer;
    border-radius: 50%;
}

.success-close:hover {
    background: #f3f4f6;
    color: #333333;
}

.success-icon {
    width: 78px;
    height: 78px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
}

.success-icon svg {
    width: 52px;
    height: 52px;
    fill: none;
    stroke: #15803d;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-linejoin: round;
    animation: checkDraw 0.5s ease;
}

.success-modal h2 {
    margin: 0 0 10px;
    color: #166534;
    font-size: 25px;
    font-weight: 700;
}

.success-modal p {
    margin: 0 auto 25px;
    color: #555555;
    font-size: 16px;
    line-height: 1.5;
    max-width: 330px;
}

.success-ok-btn {
    width: 130px;
    padding: 11px 25px;
    border: none;
    border-radius: 8px;
    background: #15803d;
    color: #ffffff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.success-ok-btn:hover {
    background: #166534;
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(21, 128, 61, 0.25);
}

@keyframes successFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes successPop {
    from {
        opacity: 0;
        transform: scale(0.85) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes checkDraw {
    from {
        opacity: 0;
        transform: scale(0.6);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width: 500px) {
    .success-modal {
        width: 100%;
        padding: 32px 22px 27px;
    }

    .success-icon {
        width: 70px;
        height: 70px;
    }

    .success-modal h2 {
        font-size: 22px;
    }

    .success-modal p {
        font-size: 15px;
    }
}

.new-room-highlight {
    border: 3px solid #f4b400 !important;
    background: #fff3a6 !important;
    box-shadow: 0 0 0 4px rgba(244, 180, 0, .20), 0 6px 18px rgba(244, 180, 0, .25);
    animation: newRoomPulse 1.2s ease-in-out 3;
    position: relative;
    z-index: 2;
}

@keyframes newRoomPulse {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-2px); }
}

                .error-message {
                    background: #f8d7da;
                    color: #721c24;
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 15px;
                }

                /* DEPARTMENT BUTTONS */

                .departments {
                    display: flex;
                    gap: 15px;
                    margin-bottom: 20px;
                    flex-wrap: wrap;
                }

                .dept-btn {
                    padding: 12px 25px;
                    border: none;
                    background: #e0e0e0;
                    cursor: pointer;
                    border-radius: 8px;
                    font-weight: bold;
                }

                .dept-btn.active {
                    background: #2e7d32;
                    color: white;
                }

                .dept-content {
                    display: none;
                }

                .dept-content.active {
                    display: block;
                }

                /* ADD BUILDING */

                .add-form {
                    background: white;
                    padding: 18px;
                    border-radius: 10px;
                    margin-bottom: 25px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.1);
                }

                /* BUILDING */

                .building-card {
                    background: white;
                    border-radius: 10px;
                    padding: 18px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.1);
                }

                .building-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 10px;
                }

                .building-header h3 {
                    margin: 0;
                }

                .building-actions {
                    display: flex;
                    gap: 8px;
                }

                /* ROOMS */

                .room-section {
                    margin-top: 20px;
                }

                .room-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }

                .room-card {
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 15px;
                    margin-bottom: 10px;
                    background: #fafafa;
                }

                .room-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 10px;
                }

                .room-header strong {
                    font-size: 16px;
                }

                .room-actions {
                    display: flex;
                    gap: 6px;
                }

                .room-details {
                    margin-top: 10px;
                    color: #555;
                    font-size: 14px;
                    line-height: 1.7;
                }

                .no-rooms {
                    color: #777;
                    padding: 10px 0;
                }

                /* FORMS */

                label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                input,
                select,
                textarea {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 12px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-sizing: border-box;
                }

                textarea {
                    min-height: 90px;
                    resize: vertical;
                }

                .checkbox-group {
                    display: flex;
                    gap: 20px;
                    margin-bottom: 12px;
                }

                .checkbox-group label {
                    font-weight: normal;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }

                .checkbox-group input {
                    width: auto;
                    margin: 0;
                }
/* =========================================
   MOVE ROOM BUTTON
   ========================================= */

.move-btn {
    background: #f9a825;
    color: #1b5e20;
    padding: 12px 24px;
    font-size: 16px;
    min-width: 180px;
    border-radius: 6px;
}

.move-btn:hover {
    background: #e89a00;
    color: white;
    transform: translateY(-1px);
}

/* =========================================
   MOVE ROOM MODAL
   ========================================= */

.move-description {
    color: #555;
    margin-bottom: 20px;
    line-height: 1.6;
}


.move-description strong {
    color: #2e7d32;
}


.move-warning {
    background: #fff8e1;
    border-left: 4px solid #f9a825;

    color: #6d4c00;

    padding: 12px;

    margin-top: 5px;
    margin-bottom: 15px;

    border-radius: 5px;

    font-size: 14px;

    line-height: 1.5;
}
                /* BUTTONS */

                .btn {
                    padding: 8px 14px;
                    border: none;
                    cursor: pointer;
                    border-radius: 5px;
                    font-weight: bold;
                }

                .green {
                    background: #2e7d32;
                    color: white;
                }

                .blue {
                    background: #1976d2;
                    color: white;
                }

                .red {
                    background: #c62828;
                    color: white;
                }

                .gray {
                    background: #777;
                    color: white;
                }

                /* MODAL */

                .modal {
                    display: none;
                    position: fixed;
                    z-index: 9999;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,.5);
                    overflow-y: auto;
                }

                .modal-content {
                    background: white;
                    width: 500px;
                    max-width: 90%;
                    margin: 50px auto;
                    padding: 25px;
                    border-radius: 10px;
                    box-sizing: border-box;
                }

                .modal-content h3 {
                    margin-top: 0;
                }
                /* BUILDING NAME + ARROW */

.building-name-area {
    display: flex;
    align-items: center;
    gap: 10px;
}

.building-name-area h3 {
    margin: 0;
}


/* BUILDING ARROW */

.building-toggle {
    width: 36px;
    height: 36px;
    border: none;
    background: #e9ecef;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: 0.2s;
}

.building-toggle:hover {
    background: #d5d5d5;
}

.building-toggle .arrow {
    transition: transform 0.2s ease;
}


/* ROOMS ARE HIDDEN BY DEFAULT */

.building-rooms {
    display: none;
    margin-top: 20px;
}


/* WHEN BUILDING IS OPEN */

.building-rooms.open {
    display: block;
}


/* ROTATE ARROW */

.building-toggle.open .arrow {
    transform: rotate(180deg);
    
    .building-rooms {
    display: none;
}

                .modal-buttons {
                    display: flex;
                    justify-content: flex-end;
                    gap: 8px;
                    margin-top: 10px;
                }

                @media(max-width:700px) {

                    .building-header,
                    .room-header {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                    .building-actions,
                    .room-actions {
                        width: 100%;
                    }

                }

            

/* =========================================
   PROFESSIONAL ROOM SEARCH
========================================= */

.room-search-box {
    width: 100%;
    box-sizing: border-box;
    background: #ffffff;
    padding: 22px 24px;
    margin: 20px 0 28px;
    border: 1px solid #e3e7eb;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
}

.room-search-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}

.room-search-title {
    color: #1f2937;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.3;
}

.room-search-subtitle {
    margin-top: 3px;
    color: #6b7280;
    font-size: 13px;
}

.room-search-controls {
    display: flex;
    align-items: stretch;
    gap: 10px;
    width: 100%;
}

.room-search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 0;
}

.room-search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-51%);
    color: #6b7280;
    font-size: 25px;
    line-height: 1;
    pointer-events: none;
}

#roomSearchInput {
    width: 100%;
    height: 48px;
    box-sizing: border-box;
    padding: 0 15px 0 43px;
    border: 1px solid #cfd5dc;
    border-radius: 8px;
    background: #ffffff;
    color: #1f2937;
    font-size: 16px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

#roomSearchInput::placeholder {
    color: #9ca3af;
}

#roomSearchInput:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, .12);
}



.room-search-result {
    min-height: 20px;
    margin-top: 10px;
    font-size: 14px;
    font-weight: 600;
}

.room-search-no-result {
    color: #c62828;
}

.room-search-found {
    color: #2e7d32;
}

/* The searched room: same yellow style as your example. */
.new-building-highlight {
    background: #f0fdf4 !important;
    border: 3px solid #22c55e !important;
    box-shadow: 0 0 0 5px rgba(34, 197, 94, .12), 0 10px 28px rgba(20, 83, 45, .12) !important;
    position: relative;
    z-index: 20;
    transition: all .35s ease;
}

.room-search-highlight {
    background-color: #fff3a6 !important;
    border: 4px solid #f4b400 !important;
    outline: 3px solid #ffd54f !important;
    box-shadow:
        0 0 0 5px rgba(244, 180, 0, .25),
        0 8px 25px rgba(244, 180, 0, .35) !important;
    position: relative;
    z-index: 999;
    transition: all .25s ease;
}

/* Hide rooms that do not match the search. */
.room-search-hidden {
    display: none !important;
}

@media (max-width: 600px) {
    .room-search-box {
        padding: 18px;
    }

    .room-search-controls {
        display: flex !important;
        flex-direction: row !important;
        align-items: center;
        gap: 8px;
    }

    .room-search-input-wrap {
        flex: 1 1 auto;
        min-width: 0;
    }

    #roomSearchInput {
        font-size: 15px;
    }
}


/* Search panel collapses after a room is found */
.room-search-collapsed .room-search-header,
.room-search-collapsed .room-search-controls,
.room-search-collapsed .room-search-result {
    display: none !important;
}

.room-search-collapsed {
    padding: 0;
    margin: 0 0 18px;
    border: 0;
    box-shadow: none;
    background: transparent;
}

.room-search-collapsed .show-room-search {
    display: inline-flex;
}

.show-room-search {
    display: none;
    margin-top: 10px;
    padding: 8px 14px;
    border: 1px solid #cfd5dc;
    border-radius: 7px;
    background: #f8f9fa;
    color: #374151;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.show-room-search:hover {
    background: #eef0f2;
}


/* Room list order note */
.room-order-note {
    margin: -10px 0 14px;
    color: #6b7280;
    font-size: 13px;
    font-weight: 500;
}

/* Add Room button in building header */
.building-actions .building-add-room {
    min-width: 125px;
    height: 46px;
    padding: 0 18px;
    font-size: 15px;
    font-weight: 700;
    white-space: nowrap;
    margin: 0 !important;
}

.building-actions {
    align-items: center;
}


/* Selected building shown in Add Room form */
.selected-building-box {
    margin: 4px 0 18px;
    padding: 12px 14px;
    border: 1px solid #dfe4e8;
    border-radius: 8px;
    background: #f7f9fa;
}
.selected-building-label {
    display: block;
    margin-bottom: 3px;
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
}
#addRoomBuildingName {
    color: #1f2937;
    font-size: 16px;
}
.room-search-location {
    margin-top: 5px;
    color: #6b7280;
    font-size: 13px;
}
</style>


            <!-- TITLE -->

            <div class="title">
                Manage Buildings
            </div>

            <div class="subtitle">
                Add, edit and remove buildings and rooms by department.
            </div>


            <!-- Success message is handled by the shared header to prevent duplicate message boxes. -->

            <!-- VALIDATION ERRORS -->

            <?php if($errors->any()): ?>

                <div class="error-message">

                    <strong>Please fix the following:</strong>

                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                </div>

            <?php endif; ?>


            <!-- ADD BUILDING -->

            <div class="add-form">

                <h3>Add New Building</h3>

                <form action="<?php echo e(route('admin.buildings.store')); ?>" method="POST">

                    <?php echo csrf_field(); ?>

                    <label>
                        Department
                    </label>

                    <select name="department_id" required>

                        <option value="">
                            Select Department
                        </option>

                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($dept->id); ?>">
                                <?php echo e($dept->name); ?>

                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>


                    <label>
                        Building Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Example: Flavier Building"
                        required
                    >


                    <button type="submit" class="btn green">
                        Add Building
                    </button>

                </form>

            </div>


            
<!-- =========================================
     ROOM SEARCH
========================================= -->
<!-- DEPARTMENTS -->

            



<!-- =========================================================
     ROOM FINDER
     Professional White + Green Design
========================================================= -->

<style>
    .room-finder {
        margin: 24px 0 30px;
        border: 1px solid #dce8df;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(20, 83, 45, .06);
        overflow: hidden;
    }

    .room-finder__top {
        padding: 21px 24px;
        background: #ffffff;
        border-bottom: 1px solid #e6eee8;
    }

    .room-finder__heading {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .room-finder__title {
        margin: 0;
        color: #166534;
        font-size: 19px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -.01em;
    }

    .room-finder__description {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.45;
    }

    .room-finder__body {
        padding: 20px 24px 22px;
        background: #ffffff;
    }

    /* Simple green progress indicator */
    .room-finder__steps {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 16px;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .room-finder__step {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .room-finder__step-number {
        width: 21px;
        height: 21px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        font-size: 10px;
        font-weight: 800;
    }

    .room-finder__step--active {
        color: #15803d;
    }

    .room-finder__step--active .room-finder__step-number {
        background: #16a34a;
        border-color: #16a34a;
        color: #ffffff;
    }

    .room-finder__arrow {
        color: #bbd5c2;
    }

    .room-finder__grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
    }

    .room-finder__field {
        min-width: 0;
    }

    .room-finder__label {
        display: block;
        margin: 0 0 7px;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .035em;
        text-transform: uppercase;
    }

    .room-finder__required {
        color: #16a34a;
        font-weight: 900;
    }

    .room-finder__control {
        position: relative;
    }

    .room-finder__select,
    .room-finder__input {
        width: 100%;
        height: 49px;
        box-sizing: border-box;
        border: 1px solid #cfdcd3;
        border-radius: 9px;
        outline: none;
        background: #ffffff;
        color: #1f2937;
        font-family: inherit;
        font-size: 14px;
        transition:
            border-color .18s ease,
            box-shadow .18s ease,
            background-color .18s ease;
    }

    .room-finder__select {
        padding: 0 14px;
        cursor: pointer;
    }

    .room-finder__input {
        padding: 0 42px 0 14px;
    }

    .room-finder__select:hover,
    .room-finder__input:not(:disabled):hover {
        border-color: #86b896;
    }

    .room-finder__select:focus,
    .room-finder__input:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, .11);
    }

    .room-finder__input:disabled {
        background: #f8faf9;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .room-finder__input::placeholder {
        color: #9ca3af;
    }

    /* No search icon */
    .room-finder__clear {
        position: absolute;
        right: 9px;
        top: 50%;
        width: 30px;
        height: 30px;
        transform: translateY(-50%);
        display: none;
        border: 0;
        border-radius: 7px;
        background: transparent;
        color: #9ca3af;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
    }

    .room-finder__clear:hover {
        background: #f0fdf4;
        color: #15803d;
    }

    .room-finder__hint {
        margin: 7px 2px 0;
        color: #8a978e;
        font-size: 11px;
    }

    .room-finder__status {
        min-height: 20px;
        margin-top: 15px;
    }

    .room-finder__message {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
    }

    .room-finder__message-icon {
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        display: grid;
        place-items: center;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 900;
    }

    .room-finder__message--info {
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .room-finder__message--info .room-finder__message-icon {
        background: #dcfce7;
        color: #15803d;
    }

    .room-finder__message--success {
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #86efac;
    }

    .room-finder__message--success .room-finder__message-icon {
        background: #22c55e;
        color: #ffffff;
    }

    .room-finder__message--error {
        color: #166534;
        background: #f7fcf8;
        border: 1px solid #cde3d2;
    }

    .room-finder__message--error .room-finder__message-icon {
        background: #dcfce7;
        color: #15803d;
    }

    /* Room search result */
    .room-search-hidden {
        display: none !important;
    }

    .room-search-highlight {
        background: #f0fdf4 !important;
        border: 3px solid #22c55e !important;
        outline: 2px solid #bbf7d0 !important;
        box-shadow:
            0 0 0 4px rgba(34, 197, 94, .13),
            0 8px 22px rgba(20, 83, 45, .10) !important;
        position: relative;
        z-index: 20;
        transition: all .2s ease;
    }

    @media (max-width: 900px) {
        .room-finder__top,
        .room-finder__body {
            padding-left: 16px;
            padding-right: 16px;
        }

        .room-finder__grid {
            grid-template-columns: 1fr;
        }
    }
</style>


<section
    class="room-finder"
    id="roomFinder"
    aria-labelledby="roomFinderTitle"
>

    <div class="room-finder__top">
        <div class="room-finder__heading">
            <div>
                <h2 class="room-finder__title" id="roomFinderTitle">
                    Find a Room
                </h2>
                <p class="room-finder__description">
                    Select a college first, then choose a building and search for a room.
                </p>
            </div>
        </div>
    </div>

    <div class="room-finder__body">

        <!-- STEPS -->
        <div class="room-finder__steps">

            <span class="room-finder__step room-finder__step--active" id="stepCollege">
                <span class="room-finder__step-number">1</span>
                College
            </span>

            <span class="room-finder__arrow">→</span>

            <span class="room-finder__step" id="stepBuilding">
                <span class="room-finder__step-number">2</span>
                Building
            </span>

            <span class="room-finder__arrow">→</span>

            <span class="room-finder__step" id="stepRoom">
                <span class="room-finder__step-number">3</span>
                Room
            </span>

        </div>

        <div class="room-finder__grid">

            <!-- COLLEGE -->
            <div class="room-finder__field">

                <label class="room-finder__label" for="roomSearchCollege">
                    College
                    <span class="room-finder__required">*</span>
                </label>

                <div class="room-finder__control">
                    <select
                        id="roomSearchCollege"
                        class="room-finder__select"
                        aria-label="Select college"
                    >
                        <option value="">— Select a college —</option>

                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dept->id); ?>">
                                <?php echo e($dept->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="room-finder__hint">
                    Choose the college where the room belongs.
                </div>

            </div>

            <!-- BUILDING -->
            <div class="room-finder__field">

                <label class="room-finder__label" for="roomSearchBuilding">
                    Building
                    <span class="room-finder__required">*</span>
                </label>

                <div class="room-finder__control">
                    <select
                        id="roomSearchBuilding"
                        class="room-finder__select"
                        aria-label="Select building"
                        disabled
                    >
                        <option value="">Select a college first</option>

                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $dept->buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($building->id); ?>"
                                    data-department-id="<?php echo e($dept->id); ?>"
                                    hidden
                                >
                                    <?php echo e($building->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="room-finder__hint">
                    Choose a building from the selected college.
                </div>

            </div>

            <!-- ROOM -->
            <div class="room-finder__field">

                <label class="room-finder__label" for="roomSearchInput">
                    Room
                </label>

                <div class="room-finder__control">
                    <input
                        type="search"
                        id="roomSearchInput"
                        class="room-finder__input"
                        placeholder="Select a building first"
                        autocomplete="off"
                        disabled
                        aria-label="Search room"
                    >

                    <button
                        type="button"
                        id="roomSearchClear"
                        class="room-finder__clear"
                        aria-label="Clear room search"
                        title="Clear search"
                    >×</button>
                </div>

                <div class="room-finder__hint">
                    Enter a room number or room name.
                </div>

            </div>

        </div>

        <div
            class="room-finder__status"
            id="roomSearchResult"
            aria-live="polite"
        ></div>

    </div>

</section>


<script>
(function () {
    'use strict';

    function initRoomFinder() {

        const collegeSelect = document.getElementById('roomSearchCollege');
        const buildingSelect = document.getElementById('roomSearchBuilding');
        const roomInput = document.getElementById('roomSearchInput');
        const clearButton = document.getElementById('roomSearchClear');
        const resultElement = document.getElementById('roomSearchResult');

        const stepCollege = document.getElementById('stepCollege');
        const stepBuilding = document.getElementById('stepBuilding');
        const stepRoom = document.getElementById('stepRoom');

        if (!collegeSelect || !buildingSelect || !roomInput || !clearButton || !resultElement) {
            return;
        }

        const getRooms = function () {
            return Array.from(document.querySelectorAll('.room-card'));
        };

        const getCollegeName = function () {
            const option = collegeSelect.options[collegeSelect.selectedIndex];
            return option ? option.textContent.trim() : 'College';
        };

        const getBuildingName = function () {
            const option = buildingSelect.options[buildingSelect.selectedIndex];
            return option ? option.textContent.trim() : 'Building';
        };

        const setStatus = function (type, icon, message) {
            resultElement.innerHTML =
                '<div class="room-finder__message room-finder__message--' + type + '">' +
                '<span class="room-finder__message-icon">' + icon + '</span>' +
                '<span>' + message + '</span>' +
                '</div>';
        };

        const resetRooms = function () {
            getRooms().forEach(function (room) {
                room.classList.remove('room-search-hidden', 'room-search-highlight');

                ['background-color', 'border', 'box-shadow', 'outline'].forEach(function (property) {
                    room.style.removeProperty(property);
                });
            });
        };

        const updateClearButton = function () {
            clearButton.style.display = roomInput.value.trim() ? 'block' : 'none';
        };

        /*
         * When a college is selected above, automatically activate
         * the matching college/department button below the finder
         * and show that college's buildings.
         */
        const syncCollegeBelow = function (collegeId) {

            if (!collegeId) {
                return;
            }

            const department = document.getElementById('dept' + collegeId);

            if (!department) {
                return;
            }

            // Hide other college sections.
            document.querySelectorAll('.dept-content').forEach(function (content) {
                content.classList.remove('active');
            });

            // Remove active state from all college buttons.
            document.querySelectorAll('.dept-btn').forEach(function (button) {
                button.classList.remove('active');
            });

            // Show selected college.
            department.classList.add('active');

            // Activate the matching button below.
            const matchingButton = Array.from(
                document.querySelectorAll('.dept-btn')
            ).find(function (button) {

                const onclickValue = button.getAttribute('onclick') || '';

                return onclickValue.includes('showDept(' + collegeId);
            });

            if (matchingButton) {
                matchingButton.classList.add('active');

                // Bring the selected college button/section into view.
                matchingButton.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'nearest'
                });
            }
        };

        const openBuilding = function (buildingId) {
            const roomsContainer = document.getElementById('rooms-' + buildingId);

            if (!roomsContainer) {
                return;
            }

            const department = roomsContainer.closest('.dept-content');

            if (department) {
                document.querySelectorAll('.dept-content').forEach(function (content) {
                    content.classList.remove('active');
                });

                department.classList.add('active');

                const departmentId = department.id.replace('dept', '');

                document.querySelectorAll('.dept-btn').forEach(function (button) {
                    button.classList.remove('active');

                    const action = button.getAttribute('onclick');

                    if (action && action.includes('showDept(' + departmentId)) {
                        button.classList.add('active');
                    }
                });
            }

            document.querySelectorAll('.building-rooms').forEach(function (container) {
                container.classList.remove('open');
            });

            document.querySelectorAll('.building-toggle').forEach(function (button) {
                button.classList.remove('open');
                button.setAttribute('aria-expanded', 'false');
            });

            roomsContainer.classList.add('open');

            const buildingButton = document.querySelector(
                '.building-toggle[aria-controls="rooms-' + buildingId + '"]'
            );

            if (buildingButton) {
                buildingButton.classList.add('open');
                buildingButton.setAttribute('aria-expanded', 'true');
            }
        };

        /* COLLEGE SELECTED */
        collegeSelect.addEventListener('change', function () {

            const collegeId = this.value.trim();

            resetRooms();
            buildingSelect.value = '';
            roomInput.value = '';
            roomInput.disabled = true;
            updateClearButton();
            resultElement.innerHTML = '';

            stepCollege.classList.add('room-finder__step--active');
            stepBuilding.classList.remove('room-finder__step--active');
            stepRoom.classList.remove('room-finder__step--active');

            if (!collegeId) {
                buildingSelect.disabled = true;
                buildingSelect.value = '';
                roomInput.placeholder = 'Select a building first';

                setStatus('info', '1', 'Select a college before choosing a building.');
                return;
            }

            /* Hide every building first */
            Array.from(buildingSelect.options).forEach(function (option) {
                if (!option.value) {
                    return;
                }

                const departmentId = option.getAttribute('data-department-id');
                option.hidden = String(departmentId) !== String(collegeId);
            });

            // Automatically follow the selected college down below.
            syncCollegeBelow(collegeId);

            buildingSelect.disabled = false;
            stepBuilding.classList.add('room-finder__step--active');

            setStatus(
                'success',
                '✓',
                getCollegeName() + ' selected below. Now choose a building.'
            );
        });

        /* BUILDING SELECTED */
        buildingSelect.addEventListener('change', function () {

            const buildingId = this.value.trim();

            resetRooms();
            roomInput.value = '';
            updateClearButton();
            resultElement.innerHTML = '';

            if (!buildingId) {
                roomInput.disabled = true;
                roomInput.placeholder = 'Select a building first';
                stepRoom.classList.remove('room-finder__step--active');
                return;
            }

            roomInput.disabled = false;
            roomInput.placeholder = 'Enter room number or name';

            openBuilding(buildingId);

            stepCollege.classList.add('room-finder__step--active');
            stepBuilding.classList.add('room-finder__step--active');
            stepRoom.classList.add('room-finder__step--active');

            setStatus(
                'success',
                '✓',
                getCollegeName() + ' → ' + getBuildingName() +
                ' selected. You can now search for a room.'
            );

            roomInput.focus();
        });

        /* ROOM SEARCH */
        roomInput.addEventListener('input', function () {

            const term = this.value.trim().toLowerCase();
            const buildingId = buildingSelect.value.trim();

            updateClearButton();
            resetRooms();

            if (!collegeSelect.value) {
                this.value = '';
                this.disabled = true;
                updateClearButton();
                setStatus('info', '1', 'Select a college before searching.');
                return;
            }

            if (!buildingId) {
                this.value = '';
                this.disabled = true;
                updateClearButton();
                setStatus('info', '2', 'Select a building before searching.');
                return;
            }

            openBuilding(buildingId);

            if (!term) {
                resultElement.innerHTML = '';
                return;
            }

            const rooms = getRooms();
            const exactMatches = [];
            const partialMatches = [];

            rooms.forEach(function (room) {

                const roomBuildingId = room.getAttribute('data-building-id');

                if (String(roomBuildingId) !== String(buildingId)) {
                    room.classList.add('room-search-hidden');
                    return;
                }

                const roomName = (
                    room.getAttribute('data-room-name') ||
                    room.querySelector('strong')?.textContent ||
                    ''
                ).trim().toLowerCase();

                if (roomName === term) {
                    exactMatches.push(room);
                } else if (roomName.includes(term)) {
                    partialMatches.push(room);
                }
            });

            const matches = exactMatches.length ? exactMatches : partialMatches;

            rooms.forEach(function (room) {
                room.classList.add('room-search-hidden');
            });

            if (!matches.length) {
                setStatus(
                    'error',
                    '–',
                    'No room matching "' + this.value.trim() + '" was found in ' +
                    getBuildingName() + '.'
                );
                return;
            }

            matches.forEach(function (room) {
                room.classList.remove('room-search-hidden');
                room.classList.add('room-search-highlight');
            });

            const firstRoom = matches[0];
            const roomName = firstRoom.querySelector('strong')?.textContent.trim() || this.value.trim();

            setStatus(
                'success',
                '✓',
                getCollegeName() + ' → ' + getBuildingName() +
                ' → Room "' + roomName + '" found.'
            );

            window.setTimeout(function () {
                firstRoom.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 180);
        });

        /* CLEAR SEARCH */
        clearButton.addEventListener('click', function () {

            roomInput.value = '';
            updateClearButton();
            resetRooms();
            resultElement.innerHTML = '';

            if (collegeSelect.value && buildingSelect.value) {
                openBuilding(buildingSelect.value);

                setStatus(
                    'info',
                    '✓',
                    getCollegeName() + ' → ' + getBuildingName() +
                    ' selected. You can search another room.'
                );

                roomInput.focus();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRoomFinder);
    } else {
        initRoomFinder();
    }
})();
</script>


<div class="departments">

                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <button
                        type="button"
                        class="dept-btn <?php echo e($index == 0 ? 'active' : ''); ?>"
                        onclick="showDept(<?php echo e($dept->id); ?>, this)"
                    >

                        <?php echo e($dept->name); ?>


                    </button>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>


            <!-- DEPARTMENT CONTENT -->

            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div
                    id="dept<?php echo e($dept->id); ?>"
                    class="dept-content <?php echo e($index == 0 ? 'active' : ''); ?>"
                >

                   <?php $__empty_1 = true; $__currentLoopData = $dept->buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

    <div
        class="building-card"
        data-building-id="<?php echo e($building->id); ?>"
    >

        <!-- BUILDING HEADER -->
        <div class="building-header">

            <div class="building-name-area">

                <button
                    type="button"
                    class="building-toggle"
                    onclick="toggleBuilding(<?php echo e($building->id); ?>, this)"
                    aria-expanded="false"
                    aria-controls="rooms-<?php echo e($building->id); ?>"
                >
                    <span class="arrow">▼</span>
                </button>

                <h3>
                    <?php echo e($building->name); ?>

                </h3>

            </div>


            <div class="building-actions">

                <!-- ADD ROOM -->
                <button
                    type="button"
                    class="btn green building-add-room"
                    onclick="openAddRoom(
                        <?php echo e($dept->id); ?>,
                        <?php echo e($building->id); ?>

                    )"
                >
                    + Add Room
                </button>

                <!-- EDIT BUILDING -->
                <button
                    type="button"
                    class="btn blue"
                    onclick="openEditBuilding(
                        <?php echo e($building->id); ?>,
                        <?php echo e($dept->id); ?>,
                        <?php echo \Illuminate\Support\Js::from($building->name)->toHtml() ?>
                    )"
                >
                    Edit
                </button>


                <!-- DELETE BUILDING -->
                <form
                    action="<?php echo e(route('admin.buildings.delete', $building->id)); ?>"
                    method="POST"
                >

                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <button
                        type="submit"
                        class="btn red"
                        onclick="return confirm(
                            'Delete this building and its rooms?'
                        )"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>


        <!-- ROOMS - HIDDEN INITIALLY -->
        <div
            id="rooms-<?php echo e($building->id); ?>"
            class="building-rooms"
        >

            <div class="room-section">

                <div class="room-title">
                    Rooms
                </div>

                <div class="room-order-note">
                    Newly added rooms appear at the top.
                </div>


                <?php $__empty_2 = true; $__currentLoopData = $building->rooms->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>

                    <div class="room-card" data-room-id="<?php echo e($room->id); ?>" data-room-name="<?php echo e(strtolower($room->room_name)); ?>" data-building-name="<?php echo e(strtolower($building->name)); ?>" data-building-id="<?php echo e($building->id); ?>">

                        <div class="room-header">

                            <strong>
                                <?php echo e($room->room_name); ?>

                            </strong>


                           <div class="room-actions">

    <!-- EDIT ROOM -->
    <button
        type="button"
        class="btn blue"
        onclick="openEditRoom(
            <?php echo e($room->id); ?>,
            <?php echo e($dept->id); ?>,
            <?php echo e($building->id); ?>,
            <?php echo \Illuminate\Support\Js::from($room->room_name)->toHtml() ?>,
            <?php echo \Illuminate\Support\Js::from($room->capacity)->toHtml() ?>,
            <?php echo e($room->has_tv ? 'true' : 'false'); ?>,
            <?php echo e($room->has_projector ? 'true' : 'false'); ?>,
            <?php echo \Illuminate\Support\Js::from($room->computers)->toHtml() ?>,
            <?php echo \Illuminate\Support\Js::from($room->purpose)->toHtml() ?>,
            <?php echo \Illuminate\Support\Js::from($room->description)->toHtml() ?>
        )"
    >
        Edit
    </button>


    <!-- MOVE ROOM -->
  <button
    type="button"
    class="btn move-btn"
    data-room-id="<?php echo e($room->id); ?>"
    data-room-name="<?php echo e($room->room_name); ?>"
    onclick="openMoveRoom(this)"
>
    Reassign Room
</button>


    <!-- DELETE ROOM -->
    <form
        action="<?php echo e(route('admin.rooms.delete', $room->id)); ?>"
        method="POST"
    >

        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>

        <button
            type="submit"
            class="btn red"
            onclick="return confirm(
                'Delete this room?'
            )"
        >
            Delete
        </button>

    </form>

</div>

                        </div>


                        <!-- ROOM DETAILS -->
                        <div class="room-details">

                            <div>
                                <strong>Capacity:</strong>
                                <?php echo e($room->capacity ?? 'Not specified'); ?>

                            </div>

                            <div>
                                <strong>TV:</strong>
                                <?php echo e($room->has_tv ? 'Yes' : 'No'); ?>

                            </div>

                            <div>
                                <strong>Projector:</strong>
                                <?php echo e($room->has_projector ? 'Yes' : 'No'); ?>

                            </div>

                            <div>
                                <strong>Computers:</strong>
                                <?php echo e($room->computers ?? 0); ?>

                            </div>

                            <div>
                                <strong>Purpose:</strong>
                                <?php echo e($room->purpose ?? 'Not specified'); ?>

                            </div>

                            <?php if($room->description): ?>

                                <div>
                                    <strong>Description:</strong>
                                    <?php echo e($room->description); ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>

                    <div class="no-rooms">
                        No rooms yet.
                    </div>

                <?php endif; ?>
</div>

        </div>

    </div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

    <div class="building-card">
        No buildings for this department yet.
    </div>

<?php endif; ?>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



            <!-- ============================= -->
            <!-- EDIT BUILDING MODAL -->
            <!-- ============================= -->

            <div id="editBuildingModal" class="modal">

                <div class="modal-content">

                    <h3>
                        Edit Building
                    </h3>

                    <form
                        id="editBuildingForm"
                        method="POST"
                    >

                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>


                        <label>
                            Department
                        </label>

                        <select
                            name="department_id"
                            id="editBuildingDept"
                            required
                        >

                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($dept->id); ?>">
                                    <?php echo e($dept->name); ?>

                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>


                        <label>
                            Building Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="editBuildingName"
                            required
                        >


                        <div class="modal-buttons">

                            <button
                                type="button"
                                class="btn gray"
                                onclick="closeEditBuilding()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn green"
                            >
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            </div>



            <!-- ============================= -->
            <!-- ADD ROOM MODAL -->
            <!-- ============================= -->

            <div id="addRoomModal" class="modal">

                <div class="modal-content">

                    <h3>
                        Add Room
                    </h3>

                    <form
                        action="<?php echo e(route('admin.rooms.store')); ?>"
                        method="POST"
                    >

                        <?php echo csrf_field(); ?>


                        <input
                            type="hidden"
                            name="department_id"
                            id="addRoomDept"
                        >


                        <input
                            type="hidden"
                            name="building_id"
                            id="addRoomBuilding"
                        >

                        <div class="selected-building-box">
                            <span class="selected-building-label">Building</span>
                            <strong id="addRoomBuildingName">Select a building</strong>
                        </div>


                        <label>
                            Room Name
                        </label>

                        <input
                            type="text"
                            name="room_name"
                            placeholder="Example: Room 101"
                            required
                        >


                        <label>
                            Capacity <span style="color:#dc2626;">*</span>
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            min="1"
                            required
                            placeholder="Example: 40"
                        >


                        <label>
                            Number of Computers
                        </label>

                        <input
                            type="number"
                            name="computers"
                            min="0"
                            value=""
                        >


                        <div class="checkbox-group">

                            <label>

                                <input
                                    type="checkbox"
                                    name="has_tv"
                                    value="1"
                                >

                                TV

                            </label>


                            <label>

                                <input
                                    type="checkbox"
                                    name="has_projector"
                                    value="1"
                                >

                                Projector

                            </label>

                        </div>


                        <label>
                            Room Purpose
                        </label>

                        <input
                            type="text"
                            name="purpose"
                            placeholder="Example: Computer Laboratory"
                        >


                        <label>
                            Description
                        </label>

                        <textarea
                            name="description"
                            placeholder="Room description..."
                        ></textarea>


                        <div class="modal-buttons">

                            <button
                                type="button"
                                class="btn gray"
                                onclick="closeAddRoom()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn green"
                            >
                                Add Room
                            </button>

                        </div>

                    </form>

                </div>

            </div>



            <!-- ============================= -->
            <!-- EDIT ROOM MODAL -->
            <!-- ============================= -->

            <div id="editRoomModal" class="modal">

                <div class="modal-content">

                    <h3>
                        Edit Room
                    </h3>

                    <form
                        id="editRoomForm"
                        method="POST"
                    >

                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>


                        <input
                            type="hidden"
                            name="department_id"
                            id="editRoomDept"
                        >


                        <input
                            type="hidden"
                            name="building_id"
                            id="editRoomBuilding"
                        >


                        <label>
                            Room Name
                        </label>

                        <input
                            type="text"
                            name="room_name"
                            id="editRoomName"
                            required
                        >


                        <label>
                            Capacity <span style="color:#dc2626;">*</span>
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            id="editRoomCapacity"
                            min="1"
                            required
                        >


                        <label>
                            Number of Computers
                        </label>

                        <input
                            type="number"
                            name="computers"
                            id="editRoomComputers"
                            min="0"
                        >


                        <div class="checkbox-group">

                            <label>

                                <input
                                    type="checkbox"
                                    name="has_tv"
                                    value="1"
                                    id="editRoomTV"
                                >

                                TV

                            </label>


                            <label>

                                <input
                                    type="checkbox"
                                    name="has_projector"
                                    value="1"
                                    id="editRoomProjector"
                                >

                                Projector

                            </label>

                        </div>


                        <label>
                            Room Purpose
                        </label>

                        <input
                            type="text"
                            name="purpose"
                            id="editRoomPurpose"
                        >


                        <label>
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="editRoomDescription"
                        ></textarea>


                        <div class="modal-buttons">

                            <button
                                type="button"
                                class="btn gray"
                                onclick="closeEditRoom()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn green"
                            >
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            </div>


        </main>
<!-- ========================================= -->
<!-- MOVE ROOM MODAL -->
<!-- ========================================= -->

<div id="moveRoomModal" class="modal">

    <div class="modal-content">

        <h3>Move Room</h3>

        <p class="move-description">
            You are moving:

            <strong id="moveRoomName"></strong>
        </p>


        <form
            action="<?php echo e(route('admin.rooms.move')); ?>"
            method="POST"
            id="moveRoomForm"
        >

            <?php echo csrf_field(); ?>


            <!-- ROOM ID -->

            <input
                type="hidden"
                name="room_id"
                id="moveRoomId"
            >


            <!-- DEPARTMENT -->

            <label>
                Destination Department
            </label>

            <select
                name="department_id"
                id="moveDepartment"
                required
            >

                <option value="">
                    -- Select Department --
                </option>

                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option value="<?php echo e($dept->id); ?>">
                        <?php echo e($dept->name); ?>

                    </option>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>


            <!-- BUILDING -->

            <label>
                Destination Building
            </label>

            <select
                name="building_id"
                id="moveBuilding"
                required
                disabled
            >

                <option value="">
                    -- Select Department First --
                </option>

            </select>


            <!-- WARNING -->

            <div class="move-warning">

                The room will be moved to the
                selected department and building.

            </div>


            <!-- BUTTONS -->

            <div class="modal-buttons">

                <button
                    type="button"
                    class="btn gray"
                    onclick="closeMoveRoom()"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="btn green"
                >
                    Confirm Reassign
                </button>

            </div>

        </form>

    </div>

</div>
    </div>
 <?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>



<script>

/*
|--------------------------------------------------------------------------
| DEPARTMENT BUILDINGS DATA
|--------------------------------------------------------------------------
*/

const departmentBuildings = <?php echo json_encode(
    $departments->mapWithKeys(function ($department) {

        return [
            $department->id => $department->buildings->map(function ($building) {

                return [
                    'id' => $building->id, 'name' => $building->name
                ];

            })->values()
        ];

    }), 512) ?>;


/*
|--------------------------------------------------------------------------
| DEPARTMENT SWITCH
|--------------------------------------------------------------------------
*/

function showDept(id, btn)
{
    document
        .querySelectorAll('.dept-content')
        .forEach(function(content) {

            content.classList.remove('active');

        });


    document
        .querySelectorAll('.dept-btn')
        .forEach(function(button) {

            button.classList.remove('active');

        });


    const department =
        document.getElementById('dept' + id);


    if (department) {

        department.classList.add('active');

    }


    btn.classList.add('active');
}



/*
|--------------------------------------------------------------------------
| EDIT BUILDING
|--------------------------------------------------------------------------
*/

function openEditBuilding(id, dept, name)
{
    const modal =
        document.getElementById('editBuildingModal');

    const nameInput =
        document.getElementById('editBuildingName');

    const deptInput =
        document.getElementById('editBuildingDept');

    const form =
        document.getElementById('editBuildingForm');


    if (!modal || !nameInput || !deptInput || !form) {

        console.error(
            'Edit Building elements not found.'
        );

        return;
    }


    modal.style.display = 'block';

    nameInput.value = name;

    deptInput.value = dept;

    form.action =
        '/admin/buildings/' + id;
}


function closeEditBuilding()
{
    const modal =
        document.getElementById('editBuildingModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| ADD ROOM
|--------------------------------------------------------------------------
*/

function openAddRoom(dept, building)
{
    const modal =
        document.getElementById('addRoomModal');

    const deptInput =
        document.getElementById('addRoomDept');

    const buildingInput =
        document.getElementById('addRoomBuilding');


    if (!modal || !deptInput || !buildingInput) {

        console.error(
            'Add Room elements not found.'
        );

        return;
    }


    modal.style.display = 'block';

    deptInput.value = dept;

    buildingInput.value = building;

    const buildingNameElement =
        document.getElementById('addRoomBuildingName');

    if (buildingNameElement) {
        const buildingButton = document.querySelector(
            '.building-toggle[aria-controls="rooms-' + building + '"]'
        );

        const buildingCard = buildingButton
            ? buildingButton.closest('.building-card')
            : null;

        const heading = buildingCard
            ? buildingCard.querySelector('h3')
            : null;

        buildingNameElement.textContent =
            heading ? heading.textContent.trim() : 'Selected building';
    }

    // Every new room starts with empty optional information.
    const form = modal.querySelector('form');

    if (form) {
        const capacity = form.querySelector('[name="capacity"]');
        const computers = form.querySelector('[name="computers"]');
        const purpose = form.querySelector('[name="purpose"]');
        const description = form.querySelector('[name="description"]');

        if (capacity) capacity.value = '';
        if (computers) computers.value = '';
        if (purpose) purpose.value = '';
        if (description) description.value = '';

        form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }
}


function closeAddRoom()
{
    const modal =
        document.getElementById('addRoomModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| EDIT ROOM
|--------------------------------------------------------------------------
*/

function openEditRoom(
    id,
    dept,
    building,
    roomName,
    capacity,
    hasTV,
    hasProjector,
    computers,
    purpose,
    description
)
{
    const modal =
        document.getElementById('editRoomModal');


    if (!modal) {

        console.error(
            'Edit Room modal not found.'
        );

        return;
    }


    modal.style.display = 'block';


    document
        .getElementById('editRoomDept')
        .value = dept;


    document
        .getElementById('editRoomBuilding')
        .value = building;


    document
        .getElementById('editRoomName')
        .value = roomName;


    document
        .getElementById('editRoomCapacity')
        .value = capacity ?? '';


    document
        .getElementById('editRoomComputers')
        .value = computers ?? 0;


    document
        .getElementById('editRoomTV')
        .checked = hasTV;


    document
        .getElementById('editRoomProjector')
        .checked = hasProjector;


    document
        .getElementById('editRoomPurpose')
        .value = purpose ?? '';


    document
        .getElementById('editRoomDescription')
        .value =
            description ?? '';


    document
        .getElementById('editRoomForm')
        .action =
            '/admin/rooms/' + id;
}


function closeEditRoom()
{
    const modal =
        document.getElementById('editRoomModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| BUILDING OPEN / CLOSE
|--------------------------------------------------------------------------
*/

function toggleBuilding(buildingId, button)
{
    const rooms =
        document.getElementById(
            'rooms-' + buildingId
        );


    if (!rooms) {

        console.error(
            'Building rooms container not found.'
        );

        return;
    }


    const isOpen =
        rooms.classList.contains('open');


    if (isOpen) {

        rooms.classList.remove('open');

        button.classList.remove('open');

        button.setAttribute(
            'aria-expanded',
            'false'
        );

    }

    else {

        rooms.classList.add('open');

        button.classList.add('open');

        button.setAttribute(
            'aria-expanded',
            'true'
        );

    }
}



/*
|--------------------------------------------------------------------------
| MOVE ROOM
|--------------------------------------------------------------------------
*/

function openMoveRoom(button)
{
    const modal =
        document.getElementById('moveRoomModal');

    const roomId =
        document.getElementById('moveRoomId');

    const roomName =
        document.getElementById('moveRoomName');

    const department =
        document.getElementById('moveDepartment');

    const building =
        document.getElementById('moveBuilding');


    /*
    |--------------------------------------------------------------------------
    | CHECK ELEMENTS
    |--------------------------------------------------------------------------
    */

    if (!modal) {

        console.error(
            'moveRoomModal was not found.'
        );

        alert(
            'Move window could not be opened.'
        );

        return;
    }


    if (!roomId ||
        !roomName ||
        !department ||
        !building) {

        console.error(
            'Move Room modal elements are missing.'
        );

        alert(
            'Move Room form is incomplete.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROOM INFORMATION
    |--------------------------------------------------------------------------
    */

    const id =
        button.getAttribute(
            'data-room-id'
        );


    const name =
        button.getAttribute(
            'data-room-name'
        );


    /*
    |--------------------------------------------------------------------------
    | SET ROOM INFORMATION
    |--------------------------------------------------------------------------
    */

    roomId.value = id;

    roomName.textContent = name;


    /*
    |--------------------------------------------------------------------------
    | RESET DEPARTMENT
    |--------------------------------------------------------------------------
    */

    department.value = '';


    /*
    |--------------------------------------------------------------------------
    | RESET BUILDING
    |--------------------------------------------------------------------------
    */

    building.innerHTML =
        '<option value="">-- Select Department First --</option>';

    building.disabled = true;


    /*
    |--------------------------------------------------------------------------
    | SHOW MODAL
    |--------------------------------------------------------------------------
    */

    modal.style.display = 'block';


    console.log(
        'Move Room opened:',
        id,
        name
    );
}



/*
|--------------------------------------------------------------------------
| CLOSE MOVE ROOM
|--------------------------------------------------------------------------
*/

function closeMoveRoom()
{
    const modal =
        document.getElementById('moveRoomModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| LOAD BUILDINGS WHEN DEPARTMENT CHANGES
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function()
    {

        const department =
            document.getElementById(
                'moveDepartment'
            );


        const building =
            document.getElementById(
                'moveBuilding'
            );


        if (!department || !building) {

            console.error(
                'Move Department or Move Building select not found.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT CHANGE
        |--------------------------------------------------------------------------
        */

        department.addEventListener(
            'change',
            function()
            {

                const departmentId =
                    this.value;


                /*
                |--------------------------------------------------------------------------
                | RESET BUILDING
                |--------------------------------------------------------------------------
                */

                building.innerHTML =
                    '<option value="">-- Select Building --</option>';

                building.disabled = true;


                /*
                |--------------------------------------------------------------------------
                | NO DEPARTMENT
                |--------------------------------------------------------------------------
                */

                if (!departmentId) {

                    building.innerHTML =
                        '<option value="">-- Select Department First --</option>';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | GET BUILDINGS
                |--------------------------------------------------------------------------
                */

                const buildings =
                    departmentBuildings[departmentId] || [];


                /*
                |--------------------------------------------------------------------------
                | NO BUILDINGS
                |--------------------------------------------------------------------------
                */

                if (buildings.length === 0) {

                    building.innerHTML =
                        '<option value="">No buildings available</option>';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | ADD BUILDINGS
                |--------------------------------------------------------------------------
                */

                buildings.forEach(
                    function(item)
                    {

                        const option =
                            document.createElement(
                                'option'
                            );


                        option.value =
                            item.id;


                        option.textContent =
                            item.name;


                        building.appendChild(
                            option
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | ENABLE BUILDING SELECT
                |--------------------------------------------------------------------------
                */

                building.disabled = false;

            }
        );

    }
);



/*
|--------------------------------------------------------------------------
| CLOSE MODALS WHEN CLICKING OUTSIDE
|--------------------------------------------------------------------------
*/

window.onclick = function(event)
{

    const editBuildingModal =
        document.getElementById(
            'editBuildingModal'
        );


    const addRoomModal =
        document.getElementById(
            'addRoomModal'
        );


    const editRoomModal =
        document.getElementById(
            'editRoomModal'
        );


    const moveRoomModal =
        document.getElementById(
            'moveRoomModal'
        );


    /*
    |--------------------------------------------------------------------------
    | EDIT BUILDING
    |--------------------------------------------------------------------------
    */

    if (
        editBuildingModal &&
        event.target === editBuildingModal
    ) {

        editBuildingModal.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | ADD ROOM
    |--------------------------------------------------------------------------
    */

    if (
        addRoomModal &&
        event.target === addRoomModal
    ) {

        addRoomModal.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | EDIT ROOM
    |--------------------------------------------------------------------------
    */

    if (
        editRoomModal &&
        event.target === editRoomModal
    ) {

        editRoomModal.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | MOVE ROOM
    |--------------------------------------------------------------------------
    */

    if (
        moveRoomModal &&
        event.target === moveRoomModal
    ) {

        moveRoomModal.style.display =
            'none';

    }

};
// =========================================
// SHOW AND HIGHLIGHT NEWLY ADDED BUILDING
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    <?php if(session('building_added')): ?>

        const buildingDepartmentId = <?php echo json_encode(session('building_department_id'), 15, 512) ?>;
        const newBuildingId = <?php echo json_encode(session('building_id'), 15, 512) ?>;

        const department = document.getElementById('dept' + buildingDepartmentId);

        if (department) {
            document.querySelectorAll('.dept-content').forEach(function (content) {
                content.classList.remove('active');
            });
            department.classList.add('active');

            document.querySelectorAll('.dept-btn').forEach(function (button) {
                button.classList.remove('active');
                const onclickValue = button.getAttribute('onclick');
                if (onclickValue && onclickValue.includes('showDept(' + buildingDepartmentId)) {
                    button.classList.add('active');
                }
            });
        }

        const newBuilding = document.querySelector(
            '.building-card[data-building-id="' + newBuildingId + '"]'
        );

        if (newBuilding) {
            const roomsContainer = newBuilding.querySelector('.building-rooms');
            const toggleButton = newBuilding.querySelector('.building-toggle');

            if (roomsContainer) {
                roomsContainer.classList.add('open');
            }

            if (toggleButton) {
                toggleButton.classList.add('open');
                toggleButton.setAttribute('aria-expanded', 'true');
            }

            newBuilding.classList.add('new-building-highlight');

            setTimeout(function () {
                newBuilding.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 250);

            setTimeout(function () {
                newBuilding.classList.remove('new-building-highlight');
            }, 8000);
        }

    <?php endif; ?>

});


// =========================================
// SHOW AND HIGHLIGHT NEWLY ADDED ROOM
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    <?php if(session('room_added')): ?>

        const departmentId = <?php echo json_encode(session('room_department_id'), 15, 512) ?>;
        const buildingId = <?php echo json_encode(session('room_building_id'), 15, 512) ?>;
        const roomId = <?php echo json_encode(session('room_id'), 15, 512) ?>;

        // 1. Open the correct department
        const department = document.getElementById('dept' + departmentId);

        if (department) {

            document.querySelectorAll('.dept-content').forEach(function(content) {
                content.classList.remove('active');
            });

            department.classList.add('active');

            document.querySelectorAll('.dept-btn').forEach(function(button) {
                button.classList.remove('active');

                const onclickValue = button.getAttribute('onclick');

                if (
                    onclickValue &&
                    onclickValue.includes('showDept(' + departmentId)
                ) {
                    button.classList.add('active');
                }
            });
        }

        // 2. Open the correct building
        const roomsContainer = document.getElementById('rooms-' + buildingId);

        if (roomsContainer) {

            roomsContainer.classList.add('open');

            const toggleButton = document.querySelector(
                '.building-toggle[aria-controls="rooms-' + buildingId + '"]'
            );

            if (toggleButton) {
                toggleButton.classList.add('open');
                toggleButton.setAttribute('aria-expanded', 'true');
            }

            // 3. Find the exact room that was just created
            setTimeout(function () {

                let newRoom = null;

                if (roomId) {
                    newRoom = roomsContainer.querySelector(
                        '[data-room-id="' + roomId + '"]'
                    );
                }

                // Fallback: if the ID is unavailable, at least show the building.
                if (!newRoom) {
                    const buildingCard = roomsContainer.closest('.building-card');

                    if (buildingCard) {
                        buildingCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return;
                }

                // 4. Highlight the exact new room in yellow
                newRoom.classList.add('new-room-highlight');

                // 5. Scroll directly to it
                newRoom.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // 6. Keep the yellow highlight long enough to identify it
                setTimeout(function () {
                    newRoom.classList.remove('new-room-highlight');
                }, 8000);

            }, 350);
        }

    <?php endif; ?>

});




// =========================================
// ROOM SEARCH
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('roomSearchInput');
    const resultMessage = document.getElementById('roomSearchResult');
    const searchBox = document.querySelector('.room-search-box');
    const showSearchButton = document.getElementById('showRoomSearch');

    if (!searchInput) {
        return;
    }

    function clearSearchState() {

        document.querySelectorAll('.room-search-highlight').forEach(function (room) {
            room.classList.remove('room-search-highlight');

            room.style.removeProperty('background-color');
            room.style.removeProperty('border');
            room.style.removeProperty('box-shadow');
        });

        document.querySelectorAll('.room-search-hidden').forEach(function (room) {
            room.classList.remove('room-search-hidden');
        });
    }

    function openRoomLocation(room) {
        const roomsContainer = room.closest('.building-rooms');

        if (!roomsContainer) {
            return;
        }

        // Open building
        roomsContainer.classList.add('open');

        const buildingId = roomsContainer.id.replace('rooms-', '');

        const toggleButton = document.querySelector(
            '.building-toggle[aria-controls="rooms-' + buildingId + '"]'
        );

        if (toggleButton) {
            toggleButton.classList.add('open');
            toggleButton.setAttribute('aria-expanded', 'true');
        }

        // Open the department containing this building
        const departmentContent = roomsContainer.closest('.dept-content');

        if (departmentContent) {

            document.querySelectorAll('.dept-content').forEach(function (dept) {
                dept.classList.remove('active');
            });

            departmentContent.classList.add('active');

            const departmentId = departmentContent.id.replace('dept', '');

            document.querySelectorAll('.dept-btn').forEach(function (button) {

                button.classList.remove('active');

                const onclickValue = button.getAttribute('onclick');

                if (
                    onclickValue &&
                    onclickValue.includes('showDept(' + departmentId)
                ) {
                    button.classList.add('active');
                }
            });
        }
    }

    function searchRooms() {

        const searchValue = searchInput.value.trim().toLowerCase();

        // Restore all rooms before applying the new search.
        clearSearchState();

        if (searchValue === '') {
            resultMessage.innerHTML = '';
            return;
        }

        const rooms = Array.from(document.querySelectorAll('.room-card'));
        let exactMatches = [];
        let partialMatches = [];

        rooms.forEach(function (room) {

            const roomName =
                (
                    room.getAttribute('data-room-name') ||
                    room.querySelector('strong')?.textContent.trim() ||
                    ''
                ).toLowerCase();

            if (roomName === searchValue) {
                exactMatches.push(room);
            } else if (roomName.includes(searchValue)) {
                partialMatches.push(room);
            }
        });

        // Prefer an exact room name. Example: searching "101"
        // will show Room 101 instead of other rooms containing 101.
        const foundRooms =
            exactMatches.length > 0
                ? exactMatches
                : partialMatches;

        // Hide ALL rooms first.
        rooms.forEach(function (room) {
            room.classList.add('room-search-hidden');
        });

        if (foundRooms.length === 0) {

            resultMessage.innerHTML =
                '<span class="room-search-no-result">' +
                'No room found for "' +
                searchInput.value +
                '"' +
                '</span>';

            return;
        }

        // Show ONLY the matching room.
        foundRooms.forEach(function (room) {
            room.classList.remove('room-search-hidden');
            room.classList.add('room-search-highlight');

            // Force the exact yellow search result appearance.
            room.style.setProperty('background-color', '#fff3a6', 'important');
            room.style.setProperty('border', '4px solid #f4b400', 'important');
            room.style.setProperty('box-shadow',
                '0 0 0 5px rgba(244, 180, 0, 0.25), 0 8px 25px rgba(244, 180, 0, 0.35)',
                'important'
            );
        });

        // Open the correct department and building.
        openRoomLocation(foundRooms[0]);

        resultMessage.innerHTML =
            '<span class="room-search-found">' +
            'Room "' + searchInput.value + '" found' +
            '</span>';

        // Scroll directly to the yellow room.
        setTimeout(function () {
            foundRooms[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 300);
    }


    if (showSearchButton) {
        showSearchButton.addEventListener('click', function () {
            clearSearchState();

            if (searchBox) {
                searchBox.classList.remove('room-search-collapsed');
            }

            showSearchButton.style.display = 'none';
            searchInput.value = '';
            resultMessage.innerHTML = '';
            searchInput.focus();
        });
    }

    searchInput.addEventListener('input', searchRooms);
});

</script>
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/admin/buildings.blade.php ENDPATH**/ ?>
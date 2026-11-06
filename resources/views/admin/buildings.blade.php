<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content buildings-admin-view" data-initial-department="{{ old('department_id', '') }}">

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

            <header class="buildings-hero">
                <div class="buildings-hero-copy">
                    <p class="buildings-hero-eyebrow">CAMPUS FACILITIES</p>
                    <h1>Manage Buildings and Rooms</h1>
                    <p class="buildings-hero-description">Follow the steps below to organize classrooms by department and building.</p>
                </div>
                <div class="buildings-hero-mark" aria-hidden="true"><i class="fa-solid fa-building-columns"></i><span>LOCATION DIRECTORY</span></div>
            </header>


            <!-- Success message is handled by the shared header to prevent duplicate message boxes. -->

            <!-- VALIDATION ERRORS -->

            @if($errors->any())

                <div class="error-message">

                    <strong>Please fix the following:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            <!-- ADD BUILDING -->

            <div class="add-form">

                <div class="building-form-step">STEP 2</div>
                <h3>Add a Building</h3>
                <p class="building-form-instruction">Choose a name for a new building under the department you selected, then select <strong>Add Building</strong>.</p>

                <form action="{{ route('admin.buildings.store') }}" method="POST">

                    @csrf

                    <label>
                        Department
                    </label>

                    <select name="department_id" required>

                        <option value="">
                            Select Department
                        </option>

                        @foreach($departments as $dept)

                            <option value="{{ $dept->id }}" {{ (string) old('department_id') === (string) $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>

                        @endforeach

                    </select>


                    <label>
                        Building Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Example: Flavier Building"
                        value="{{ old('name') }}"
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

                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">
                                {{ $dept->name }}
                            </option>
                        @endforeach
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

                        @foreach($departments as $dept)
                            @foreach($dept->buildings as $building)
                                <option
                                    value="{{ $building->id }}"
                                    data-department-id="{{ $dept->id }}"
                                    hidden
                                >
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        @endforeach
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
                button.setAttribute('aria-pressed', 'false');
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
                if (typeof window.showDept === 'function') {
                    window.showDept(collegeId, matchingButton);
                } else {
                    matchingButton.classList.add('active');
                }

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
                const departmentId = department.id.replace('dept', '');
                const departmentButton = document.querySelector('.dept-btn[data-department-id="' + departmentId + '"]');
                if (typeof window.showDept === 'function') window.showDept(departmentId, departmentButton);
                else department.classList.add('active');
            }

            document.querySelectorAll('.building-rooms').forEach(function (container) {
                container.classList.remove('open');
                const addRoomButton = container.closest('.building-card')?.querySelector('.building-add-room');
                if (addRoomButton) addRoomButton.style.display = 'none';
            });

            document.querySelectorAll('.building-toggle').forEach(function (button) {
                button.classList.remove('open');
                button.setAttribute('aria-expanded', 'false');
            });

            roomsContainer.classList.add('open');
            const addRoomButton = roomsContainer.closest('.building-card')?.querySelector('.building-add-room');
            if (addRoomButton) addRoomButton.style.display = 'inline-flex';

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

<style>
    /* Administrator building workflow: choose a department, add buildings, then open a building for rooms. */
    .buildings-admin-view {
        --ba-ink: #263b31;
        --ba-muted: #52665a;
        --ba-green: #3f7b5d;
        --ba-line: #d8e2da;
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        min-width: 0;
        padding: 22px clamp(18px, 3vw, 38px) 42px !important;
        color: var(--ba-ink) !important;
        font-size: 17px;
        line-height: 1.55;
    }
    .buildings-admin-view > .buildings-hero { order: 1; position: relative; display: flex; align-items: center; justify-content: space-between; gap: 24px; min-height: 174px; margin: 0 0 24px; padding: clamp(25px, 4vw, 38px); overflow: hidden; border: 1px solid #cbdacf; border-radius: 22px; background: linear-gradient(120deg, #e6eee7 0%, #f5f8f4 68%, #e9f0e9 100%); box-shadow: 9px 9px 20px #dce3dc, -8px -8px 20px #fff; }
    .buildings-admin-view .buildings-hero-copy { position: relative; z-index: 1; min-width: 0; }
    .buildings-admin-view .buildings-hero-eyebrow { margin: 0 0 10px; color: #3c7358; font-size: 13px; font-weight: 850; letter-spacing: .12em; }
    .buildings-admin-view .buildings-hero h1 { margin: 0; color: #203c2d; font-size: clamp(31px, 3vw, 40px); font-weight: 850; line-height: 1.2; letter-spacing: -.025em; }
    .buildings-admin-view .buildings-hero-description { margin: 10px 0 0; color: #4c6254; font-size: 18px; line-height: 1.55; }
    .buildings-admin-view .buildings-hero-mark { display: flex; flex: 0 0 116px; flex-direction: column; align-items: center; justify-content: center; gap: 11px; width: 116px; height: 116px; border: 1px solid #d3dfd4; border-radius: 20px; background: #eaf0ea; color: #36785c; box-shadow: 5px 5px 12px #dce3dc, -5px -5px 12px #fff; }
    .buildings-admin-view .buildings-hero-mark i { font-size: 37px; }
    .buildings-admin-view .buildings-hero-mark span { color: #52665a; font-size: 10px; font-weight: 850; letter-spacing: .08em; text-align: center; }
    .buildings-admin-view > .error-message { order: 2; margin: 0 0 18px; padding: 15px 18px; border: 1px solid #d9b7b9; border-left: 5px solid #9a4a50; border-radius: 12px; background: #f3e9e9 !important; color: #642f34 !important; font-size: 16px; }
    .buildings-admin-view > .workflow-dept-heading { order: 3; margin: 0 0 12px; color: #294b37; font-size: 22px; line-height: 1.4; }
    .buildings-admin-view > .departments { order: 4; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin: 0 0 22px; }
    .buildings-admin-view > .no-buildings-step { order: 4; }
    .buildings-admin-view > .add-form { order: 5; display: none; margin: 0 0 22px; padding: 23px; border: 1px solid var(--ba-line); border-radius: 18px; border-top: 3px solid #7da58a; background: #f1f5f1; box-shadow: 7px 7px 16px #dce3dc, -7px -7px 16px #fff; }
    .buildings-admin-view > .dept-content { order: 6; width: 100%; }
    .buildings-admin-view > .room-finder { order: 7; width: 100%; margin-top: 28px; }
    .buildings-admin-view > .modal { order: 8; }
    .buildings-admin-view .workflow-dept-heading { display: flex; align-items: flex-start; gap: 13px; }
    .buildings-admin-view .workflow-dept-heading .step-number { display: grid; place-items: center; flex: 0 0 38px; width: 38px; height: 38px; border: 1px solid #c8d9ca; border-radius: 12px; background: #e6eee7; color: #315f43; font-size: 18px; font-weight: 850; box-shadow: 3px 3px 7px #dce3dc, -3px -3px 7px #fff; }
    .buildings-admin-view .workflow-dept-heading.workflow-step-complete .step-number { border-color: #3f7b5d; background: #3f7b5d; color: #fff; }
    .buildings-admin-view .workflow-dept-heading p { margin: 3px 0 0; color: #52665a; font-size: 16px; font-weight: 500; }
    .buildings-admin-view .departments .dept-btn {
        display: flex; flex-direction: column; align-items: flex-start; gap: 7px;
        min-width: 0; min-height: 112px; padding: 18px;
        border: 1px solid var(--ba-line) !important; border-radius: 16px !important;
        background: #f1f5f1 !important; color: #263b31 !important;
        text-align: left; box-shadow: 5px 5px 11px #dce3dc, -5px -5px 11px #fff;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .buildings-admin-view .departments .dept-btn:hover { transform: translateY(-2px); border-color: #a9c1ad !important; }
    .buildings-admin-view .departments .dept-btn.active { border: 2px solid #6f9d7b !important; background: #e7efe8 !important; color: #294b37 !important; box-shadow: inset 3px 3px 7px #d8e2d9, inset -3px -3px 7px #f7faf7; }
    .buildings-admin-view .dept-btn-name { font-size: 20px; font-weight: 850; line-height: 1.3; }
    .buildings-admin-view .dept-btn-hint { color: #52665a; font-size: 15px; font-weight: 600; }
    .buildings-admin-view .dept-btn.active .dept-btn-hint { color: #3f6d50; }
    .buildings-admin-view .add-form h3 { margin: 3px 0 5px; color: #294b37; font-size: 25px; line-height: 1.35; }
    .buildings-admin-view .building-form-step { color: #3c7358; font-size: 13px; font-weight: 850; letter-spacing: .1em; }
    .buildings-admin-view .building-form-instruction { margin: 0 0 18px; color: #52665a; font-size: 16px; line-height: 1.55; }
    .buildings-admin-view .add-form form { display: grid; grid-template-columns: minmax(180px, .8fr) minmax(240px, 1.4fr) auto; grid-template-areas: "dept-label name-label ." "dept name submit"; align-items: end; gap: 8px 16px; }
    .buildings-admin-view .add-form form label { margin: 0; color: #344a3b; font-size: 16px; }
    .buildings-admin-view .add-form form label:nth-of-type(1) { grid-area: dept-label; }
    .buildings-admin-view .add-form form label:nth-of-type(2) { grid-area: name-label; }
    .buildings-admin-view .add-form form select,
    .buildings-admin-view .add-form form input { min-height: 52px; margin: 0; border: 1px solid #cbdacf; border-radius: 11px; background: #edf2ed; color: #263b31; font: inherit; font-size: 17px; box-shadow: inset 3px 3px 6px #dce3dc, inset -3px -3px 6px #fff; }
    .buildings-admin-view .add-form form select { grid-area: dept; }
    .buildings-admin-view .add-form form input[name="name"] { grid-area: name; }
    .buildings-admin-view .add-form form button { grid-area: submit; min-height: 52px; padding: 11px 20px; border: 1px solid #6f9d7b; border-radius: 12px; background: #3f7b5d; color: #fff; font-size: 16px; font-weight: 800; white-space: nowrap; box-shadow: 4px 4px 9px #d1dbd2, -4px -4px 9px #fff; }
    .buildings-admin-view .add-form form button:hover { background: #32684d; }
    .buildings-admin-view .dept-content { margin: 0 0 18px; }
    .buildings-admin-view .department-building-guide { margin: 0 0 16px; padding: 17px 20px; border: 1px solid #d8e2da; border-radius: 15px; background: #eaf0ea; color: #294b37; box-shadow: inset 3px 3px 6px #dce3dc, inset -3px -3px 6px #fff; }
    .buildings-admin-view .department-building-guide strong { display: block; margin-bottom: 3px; color: #294b37; font-size: 18px; }
    .buildings-admin-view .department-building-guide span { color: #52665a; font-size: 16px; }
    .buildings-admin-view .building-card { overflow: hidden; margin-bottom: 14px; padding: 0; border: 1px solid var(--ba-line); border-radius: 17px; background: #f1f5f1; box-shadow: 6px 6px 14px #dce3dc, -6px -6px 14px #fff; }
    .buildings-admin-view .building-header { min-height: 86px; padding: 17px 20px; border-radius: 17px; background: #f1f5f1; }
    .buildings-admin-view .building-name-area { gap: 13px; }
    .buildings-admin-view .building-name-area h3 { color: #294b37; font-size: 20px; line-height: 1.4; cursor: pointer; }
    .buildings-admin-view .building-toggle { flex: 0 0 42px; width: 42px; height: 42px; border: 1px solid #d2dfd3; border-radius: 12px; background: #e8efe8; color: #36785c; box-shadow: 3px 3px 7px #dce3dc, -3px -3px 7px #fff; }
    .buildings-admin-view .building-toggle:hover,
    .buildings-admin-view .building-toggle.open { background: #dfeae0; color: #315f43; }
    .buildings-admin-view .building-actions { gap: 9px; flex-wrap: wrap; }
    .buildings-admin-view .building-add-room { display: none; }
    .buildings-admin-view .building-actions .btn,
    .buildings-admin-view .room-actions .btn { min-height: 43px; padding: 9px 14px; border: 1px solid transparent; border-radius: 10px; font-size: 15px; font-weight: 750; }
    .buildings-admin-view .building-actions .btn.green,
    .buildings-admin-view .room-actions .btn.green { background: #3f7b5d; color: #fff; }
    .buildings-admin-view .building-actions .btn.blue,
    .buildings-admin-view .room-actions .btn.blue { border-color: #b9c9d4; background: #e7edf0; color: #334b5c; }
    .buildings-admin-view .building-actions .btn.red,
    .buildings-admin-view .room-actions .btn.red { border-color: #ca9a9e; background: #f4e9e9; color: #752e36; }
    .buildings-admin-view .building-actions .move-btn,
    .buildings-admin-view .room-actions .move-btn { border: 1px solid #cdbb8c; background: #f2eddf; color: #5b4a25; }
    .buildings-admin-view .building-rooms { margin: 0; padding: 0 20px 20px; }
    .buildings-admin-view .building-rooms.open { padding-top: 2px; }
    .buildings-admin-view .building-rooms.open ~ .building-header .building-add-room { display: inline-flex; }
    .buildings-admin-view .building-card:has(.building-rooms.open) .building-add-room { display: inline-flex; }
    .buildings-admin-view .building-step-note { margin: 0 0 16px; padding: 14px 16px; border: 1px solid #d7e2d8; border-radius: 13px; background: #eaf0ea; color: #52665a; font-size: 16px; line-height: 1.5; }
    .buildings-admin-view .building-step-note strong { color: #294b37; }
    .buildings-admin-view .room-title { margin-bottom: 6px; color: #294b37; font-size: 20px; }
    .buildings-admin-view .room-order-note { margin: 0 0 14px; color: #52665a; font-size: 15px; }
    .buildings-admin-view .room-card { padding: 17px; border: 1px solid #d7e1d8; border-radius: 14px; background: #f7f9f7; }
    .buildings-admin-view .room-header strong { color: #263b31; font-size: 18px; }
    .buildings-admin-view .room-details { color: #405649; font-size: 16px; line-height: 1.7; }
    .buildings-admin-view .room-details strong { color: #294b37; }
    .buildings-admin-view .no-rooms,
    .buildings-admin-view .no-buildings-step { padding: 16px; border: 1px dashed #c6d5c8; border-radius: 12px; background: #edf2ed; color: #52665a; font-size: 16px; }
    .buildings-admin-view .room-finder { border-color: var(--ba-line); border-radius: 18px; background: #f1f5f1; box-shadow: 7px 7px 16px #dce3dc, -7px -7px 16px #fff; }
    .buildings-admin-view .room-finder__top { border-bottom-color: #dce5dc; background: #e9f0e9; }
    .buildings-admin-view .room-finder__title { color: #294b37; font-size: 23px; }
    .buildings-admin-view .room-finder__description { color: #52665a; font-size: 16px; }
    .buildings-admin-view .room-finder__body { background: #f1f5f1; }
    .buildings-admin-view .room-finder__steps { color: #52665a; font-size: 13px; }
    .buildings-admin-view .room-finder__step--active { color: #315f43; }
    .buildings-admin-view .room-finder__step-number { width: 28px; height: 28px; border-color: #c8d9ca; background: #e6eee7; color: #315f43; font-size: 14px; }
    .buildings-admin-view .room-finder__step--active .room-finder__step-number { border-color: #3f7b5d; background: #3f7b5d; color: #fff; }
    .buildings-admin-view .room-finder__label { color: #344a3b; font-size: 15px; }
    .buildings-admin-view .room-finder__select,
    .buildings-admin-view .room-finder__input { height: 52px; border-color: #cbdacf; border-radius: 11px; background: #edf2ed; color: #263b31; font-size: 17px; box-shadow: inset 3px 3px 6px #dce3dc, inset -3px -3px 6px #fff; }
    .buildings-admin-view .room-finder__hint { color: #52665a; font-size: 14px; }
    .buildings-admin-view .room-finder__message--info,
    .buildings-admin-view .room-finder__message--success { border-color: #cbdacf; border-radius: 11px; background: #eaf0ea; color: #344a3b; font-size: 16px; }
    .buildings-admin-view .room-finder__message--info { border-color: #cbdacf; border-radius: 11px; background: #eef1ed; color: #344a3b; font-size: 16px; }
    .buildings-admin-view .room-finder__message--error { border-color: #d9c3c4; border-radius: 11px; background: #f3eaea; color: #683b3d; font-size: 16px; }
    .buildings-admin-view .room-finder__message-icon { background: #dce8dd; color: #315f43; }
    .buildings-admin-view .room-finder__message--error .room-finder__message-icon { background: #ead9da; color: #82454a; }
    .buildings-admin-view .room-search-highlight { border: 3px solid #6e9577 !important; outline: 2px solid #ccddce !important; background: #e6eee7 !important; box-shadow: 0 0 0 4px rgba(77,126,88,.12), 0 8px 18px rgba(50,70,55,.12) !important; }
    .buildings-admin-view .new-building-highlight { border-color: #7da58a !important; background: #e9f0e9 !important; box-shadow: 0 0 0 4px rgba(77,126,88,.10), 0 8px 18px rgba(50,70,55,.10) !important; }
    .buildings-admin-view .new-room-highlight { border: 3px solid #6e9577 !important; background: #e6eee7 !important; box-shadow: 0 0 0 4px rgba(77,126,88,.12), 0 8px 18px rgba(50,70,55,.12) !important; }
    .buildings-admin-view .success-overlay,
    .buildings-admin-view .modal { background: rgba(29, 45, 35, .48) !important; backdrop-filter: blur(5px); }
    .buildings-admin-view .success-modal,
    .buildings-admin-view .modal-content { border: 1px solid #d3dfd4 !important; border-top: 3px solid #7da58a !important; border-radius: 18px; background: #f1f5f1 !important; color: #263b31 !important; box-shadow: 8px 8px 24px rgba(42,61,47,.22), -4px -4px 12px rgba(255,255,255,.72) !important; }
    .buildings-admin-view .confirm-delete-content { width: min(500px, 92vw); padding: 30px; text-align: center; }
    .buildings-admin-view .delete-confirm-icon { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 14px; border: 1px solid #dfc9c9; border-radius: 17px; background: #f0e5e5; color: #82454a; font-size: 28px; font-weight: 850; box-shadow: 4px 4px 9px #dce3dc, -4px -4px 9px #fff; }
    .buildings-admin-view #deleteConfirmMessage { margin: 8px 0; color: #405649; font-size: 17px; line-height: 1.55; }
    .buildings-admin-view .delete-confirm-note { margin: 0; color: #69796e; font-size: 14px; }
    .buildings-admin-view .confirm-delete-content .modal-buttons { justify-content: center; margin-top: 22px; }
    .buildings-admin-view .success-modal h2,
    .buildings-admin-view .modal-content h3 { color: #294b37 !important; font-size: 25px !important; }
    .buildings-admin-view .modal-step-instruction { margin: -8px 0 18px; color: #52665a; font-size: 16px; line-height: 1.55; }
    .buildings-admin-view .success-modal p,
    .buildings-admin-view .move-description { color: #52665a !important; font-size: 17px; line-height: 1.6; }
    .buildings-admin-view .success-icon { background: #e3ece4 !important; }
    .buildings-admin-view .success-icon svg { stroke: #3f7b5d; }
    .buildings-admin-view .success-modal.error .success-icon { background: #f0e5e5 !important; }
    .buildings-admin-view .success-modal.error .success-icon svg { stroke: #82454a; }
    .buildings-admin-view .success-modal.error .success-ok-btn { background: #82454a; }
    .buildings-admin-view .success-ok-btn { background: #3f7b5d; color: #fff; font-size: 16px; }
    .buildings-admin-view .success-close { color: #52665a; }
    .buildings-admin-view .success-close:hover { background: #e5ece5; color: #294b37; }
    .buildings-admin-view .modal-content label { color: #344a3b; font-size: 16px; }
    .buildings-admin-view .modal-content input:not([type=checkbox]),
    .buildings-admin-view .modal-content select,
    .buildings-admin-view .modal-content textarea { min-height: 48px; border: 1px solid #cbdacf; border-radius: 10px; background: #edf2ed; color: #263b31; font: inherit; font-size: 17px; box-shadow: inset 3px 3px 6px #dce3dc, inset -3px -3px 6px #fff; }
    .buildings-admin-view .modal-content input::placeholder,
    .buildings-admin-view .modal-content textarea::placeholder { color: #69796e; opacity: 1; }
    .buildings-admin-view .selected-building-box { border-color: #d3dfd4; border-radius: 12px; background: #e8efe8; }
    .buildings-admin-view .selected-building-label { color: #52665a; font-size: 13px; }
    .buildings-admin-view #addRoomBuildingName { color: #294b37; font-size: 17px; }
    .buildings-admin-view .move-warning { border: 1px solid #d5c9a8; border-left: 4px solid #a28b51; border-radius: 10px; background: #f1eee5; color: #51472e; font-size: 15px; }
    .buildings-admin-view .modal-buttons .btn { min-height: 46px; padding: 10px 16px; border-radius: 10px; font-size: 16px; }
    .buildings-admin-view .modal-buttons .btn.green { background: #3f7b5d; color: #fff; }
    .buildings-admin-view .modal-buttons .btn.gray { border: 1px solid #cbdacf; background: #e6ece6; color: #344a3b; }
    .buildings-admin-view .modal-buttons .btn.red { background: #8b3b45; color: #fff; }
    .buildings-admin-view :focus-visible { outline: 3px solid #438463 !important; outline-offset: 3px !important; }
    @media (max-width: 760px) {
        .buildings-admin-view { padding: 16px 14px 28px !important; }
        .buildings-admin-view .buildings-hero-mark { display: none; }
        .buildings-admin-view .buildings-hero { min-height: 0; padding: 23px 20px; }
        .buildings-admin-view .buildings-hero-description { font-size: 16px; }
        .buildings-admin-view > .departments { grid-template-columns: 1fr; }
        .buildings-admin-view .add-form { padding: 18px; }
        .buildings-admin-view .add-form form { grid-template-columns: 1fr; grid-template-areas: "dept-label" "dept" "name-label" "name" "submit"; gap: 8px; }
        .buildings-admin-view .building-header,
        .buildings-admin-view .room-header { align-items: flex-start; flex-direction: column; }
        .buildings-admin-view .building-actions,
        .buildings-admin-view .room-actions { width: 100%; }
        .buildings-admin-view .building-actions .btn,
        .buildings-admin-view .room-actions .btn { flex: 1 1 auto; }
        .buildings-admin-view .building-rooms { padding: 0 14px 16px; }
        .buildings-admin-view .room-finder__grid { grid-template-columns: 1fr; }
        .buildings-admin-view .room-finder__steps { flex-wrap: wrap; gap: 8px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .buildings-admin-view *, .buildings-admin-view *::before, .buildings-admin-view *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="workflow-dept-heading">
    <span class="step-number">1</span>
    <div>
        Choose a Department
        <p>Select the department where the building will be added. Its buildings and rooms appear after selection.</p>
    </div>
</div>


<div class="departments">

                @foreach($departments as $index => $dept)

                    <button
                        type="button"
                        class="dept-btn"
                        data-department-id="{{ $dept->id }}"
                        aria-pressed="false"
                        onclick="showDept({{ $dept->id }}, this)"
                    >
                        <span class="dept-btn-name">{{ $dept->name }}</span>
                        <span class="dept-btn-hint">{{ $dept->buildings->count() }} building(s) · Select to manage</span>

                    </button>

                @endforeach

            </div>

            @if($departments->isEmpty())
                <div class="no-buildings-step">No departments are available yet. Add a department first, then return here to create its buildings and rooms.</div>
            @endif


            <!-- DEPARTMENT CONTENT -->

            @foreach($departments as $index => $dept)

                <div
                    id="dept{{ $dept->id }}"
                    class="dept-content"
                >

                    <div class="department-building-guide">
                        <strong>Step 3 — Choose a building to manage its rooms</strong>
                        <span>Use the building arrow or name to open it. The Add Room button appears after the building is opened.</span>
                    </div>

                   @forelse($dept->buildings as $building)

    <div
        class="building-card"
        data-building-id="{{ $building->id }}"
    >

        <!-- BUILDING HEADER -->
        <div class="building-header">

            <div class="building-name-area">

                <button
                    type="button"
                    class="building-toggle"
                    onclick="toggleBuilding({{ $building->id }}, this)"
                    aria-expanded="false"
                    aria-controls="rooms-{{ $building->id }}"
                >
                    <span class="arrow">▼</span>
                </button>

                <h3 role="button" tabindex="0"
                    onclick="toggleBuilding({{ $building->id }}, this.parentElement.querySelector('.building-toggle'))"
                    onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); toggleBuilding({{ $building->id }}, this.parentElement.querySelector('.building-toggle')); }">
                    {{ $building->name }}
                </h3>

            </div>


            <div class="building-actions">

                <!-- ADD ROOM -->
                <button
                    type="button"
                    class="btn green building-add-room"
                    onclick="openAddRoom(
                        {{ $dept->id }},
                        {{ $building->id }}
                    )"
                >
                    + Add Room
                </button>

                <!-- EDIT BUILDING -->
                <button
                    type="button"
                    class="btn blue"
                    onclick="openEditBuilding(
                        {{ $building->id }},
                        {{ $dept->id }},
                        @js($building->name)
                    )"
                >
                    Edit
                </button>


                <!-- DELETE BUILDING -->
                <form
                    action="{{ route('admin.buildings.delete', $building->id) }}"
                    method="POST"
                    data-confirm-delete="Delete this building and its rooms?"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn red"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>


        <!-- ROOMS - HIDDEN INITIALLY -->
        <div
            id="rooms-{{ $building->id }}"
            class="building-rooms"
        >

            <div class="building-step-note"><strong>Step 3 — Add or manage rooms:</strong> Select <strong>+ Add Room</strong> to create a room for this building. Existing rooms and their details appear below.</div>

            <div class="room-section">

                <div class="room-title">
                    Rooms
                </div>

                <div class="room-order-note">
                    Newly added rooms appear at the top.
                </div>


                @forelse($building->rooms->sortByDesc('id') as $room)

                    <div class="room-card" data-room-id="{{ $room->id }}" data-room-name="{{ strtolower($room->room_name) }}" data-building-name="{{ strtolower($building->name) }}" data-building-id="{{ $building->id }}">

                        <div class="room-header">

                            <strong>
                                {{ $room->room_name }}
                            </strong>


                           <div class="room-actions">

    <!-- EDIT ROOM -->
    <button
        type="button"
        class="btn blue"
        onclick="openEditRoom(
            {{ $room->id }},
            {{ $dept->id }},
            {{ $building->id }},
            @js($room->room_name),
            @js($room->capacity),
            {{ $room->has_tv ? 'true' : 'false' }},
            {{ $room->has_projector ? 'true' : 'false' }},
            @js($room->computers),
            @js($room->purpose),
            @js($room->description)
        )"
    >
        Edit
    </button>


    <!-- MOVE ROOM -->
  <button
    type="button"
    class="btn move-btn"
    data-room-id="{{ $room->id }}"
    data-room-name="{{ $room->room_name }}"
    onclick="openMoveRoom(this)"
>
    Reassign Room
</button>


    <!-- DELETE ROOM -->
    <form
        action="{{ route('admin.rooms.delete', $room->id) }}"
        method="POST"
        data-confirm-delete="Delete this room?"
    >

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="btn red"
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
                                {{ $room->capacity ?? 'Not specified' }}
                            </div>

                            <div>
                                <strong>TV:</strong>
                                {{ $room->has_tv ? 'Yes' : 'No' }}
                            </div>

                            <div>
                                <strong>Projector:</strong>
                                {{ $room->has_projector ? 'Yes' : 'No' }}
                            </div>

                            <div>
                                <strong>Computers:</strong>
                                {{ $room->computers ?? 0 }}
                            </div>

                            <div>
                                <strong>Purpose:</strong>
                                {{ $room->purpose ?? 'Not specified' }}
                            </div>

                            @if($room->description)

                                <div>
                                    <strong>Description:</strong>
                                    {{ $room->description }}
                                </div>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="no-rooms">
                        No rooms yet.
                    </div>

                @endforelse
</div>

        </div>

    </div>

@empty

    <div class="building-card">
        No buildings for this department yet.
    </div>

@endforelse

                </div>

            @endforeach



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

                        @csrf
                        @method('PUT')


                        <label>
                            Department
                        </label>

                        <select
                            name="department_id"
                            id="editBuildingDept"
                            required
                        >

                            @foreach($departments as $dept)

                                <option value="{{ $dept->id }}">
                                    {{ $dept->name }}
                                </option>

                            @endforeach

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
                    <p class="modal-step-instruction">Step 3: Add this room to the building you opened. Capacity is required; equipment and notes are optional.</p>

                    <form
                        action="{{ route('admin.rooms.store') }}"
                        method="POST"
                    >

                        @csrf


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

                        @csrf
                        @method('PUT')


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
            action="{{ route('admin.rooms.move') }}"
            method="POST"
            id="moveRoomForm"
        >

            @csrf


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

                @foreach($departments as $dept)

                    <option value="{{ $dept->id }}">
                        {{ $dept->name }}
                    </option>

                @endforeach

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

<div id="deleteConfirmModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
    <div class="modal-content confirm-delete-content">
        <div class="delete-confirm-icon" aria-hidden="true">!</div>
        <h3 id="deleteConfirmTitle">Confirm deletion</h3>
        <p id="deleteConfirmMessage">Are you sure you want to delete this item?</p>
        <p class="delete-confirm-note">This action cannot be undone.</p>
        <div class="modal-buttons">
            <button type="button" class="btn gray" id="cancelDeleteButton">Cancel</button>
            <button type="button" class="btn red" id="acceptDeleteButton">Delete</button>
        </div>
    </div>
</div>

        </main>
    </div>
 @include('footerheader.footer')
</div>



<script>

/*
|--------------------------------------------------------------------------
| DEPARTMENT BUILDINGS DATA
|--------------------------------------------------------------------------
*/

const departmentBuildings = @json(
    $departments->mapWithKeys(function ($department) {

        return [
            $department->id => $department->buildings->map(function ($building) {

                return [
                    'id' => $building->id,
                    'name' => $building->name
                ];

            })->values()
        ];

    })
);


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
            button.setAttribute('aria-pressed', 'false');

        });


    const department =
        document.getElementById('dept' + id);


    if (department) department.classList.add('active');

    const selectedButton = btn || document.querySelector('.dept-btn[data-department-id="' + id + '"]');
    if (selectedButton) {
        selectedButton.classList.add('active');
        selectedButton.setAttribute('aria-pressed', 'true');
    }

    const buildingForm = document.querySelector('.buildings-admin-view .add-form');
    const buildingDepartmentSelect = document.querySelector('.buildings-admin-view .add-form select[name="department_id"]');
    if (buildingForm) buildingForm.style.display = 'block';
    if (buildingDepartmentSelect) buildingDepartmentSelect.value = String(id);

    document.querySelectorAll('.building-rooms.open').forEach(function (rooms) {
        rooms.classList.remove('open');
        const toggle = document.querySelector('.building-toggle[aria-controls="' + rooms.id + '"]');
        if (toggle) {
            toggle.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
        const addRoomButton = rooms.closest('.building-card')?.querySelector('.building-add-room');
        if (addRoomButton) addRoomButton.style.display = 'none';
    });

    const selectedDepartmentStep = document.querySelector('.buildings-admin-view .workflow-dept-heading');
    if (selectedDepartmentStep) {
        selectedDepartmentStep.classList.add('workflow-step-complete');
    }
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
        const addRoomButton = button.closest('.building-card')?.querySelector('.building-add-room');
        if (addRoomButton) addRoomButton.style.display = 'none';

    }

    else {

        document.querySelectorAll('.building-rooms.open').forEach(function (otherRooms) {
            if (otherRooms === rooms) return;

            otherRooms.classList.remove('open');
            const otherCard = otherRooms.closest('.building-card');
            const otherToggle = otherCard ? otherCard.querySelector('.building-toggle') : null;
            const otherAddRoom = otherCard ? otherCard.querySelector('.building-add-room') : null;
            if (otherToggle) {
                otherToggle.classList.remove('open');
                otherToggle.setAttribute('aria-expanded', 'false');
            }
            if (otherAddRoom) otherAddRoom.style.display = 'none';
        });

        rooms.classList.add('open');

        button.classList.add('open');

        button.setAttribute(
            'aria-expanded',
            'true'
        );
        const addRoomButton = button.closest('.building-card')?.querySelector('.building-add-room');
        if (addRoomButton) addRoomButton.style.display = 'inline-flex';

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

    @if(session('building_added'))

        const buildingDepartmentId = @json(session('building_department_id'));
        const newBuildingId = @json(session('building_id'));

        const department = document.getElementById('dept' + buildingDepartmentId);

        if (department) {
            const departmentButton = document.querySelector('.dept-btn[data-department-id="' + buildingDepartmentId + '"]');
            showDept(buildingDepartmentId, departmentButton);
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
            const addRoomButton = newBuilding.querySelector('.building-add-room');
            if (addRoomButton) addRoomButton.style.display = 'inline-flex';

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

    @endif

});


// =========================================
// SHOW AND HIGHLIGHT NEWLY ADDED ROOM
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    @if(session('room_added'))

        const departmentId = @json(session('room_department_id'));
        const buildingId = @json(session('room_building_id'));
        const roomId = @json(session('room_id'));

        // 1. Open the correct department
        const department = document.getElementById('dept' + departmentId);

        if (department) {
            const departmentButton = document.querySelector('.dept-btn[data-department-id="' + departmentId + '"]');
            showDept(departmentId, departmentButton);
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
            const addRoomButton = roomsContainer.closest('.building-card')?.querySelector('.building-add-room');
            if (addRoomButton) addRoomButton.style.display = 'inline-flex';

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

                // 4. Highlight the exact new room with the sage accent
                newRoom.classList.add('new-room-highlight');

                // 5. Scroll directly to it
                newRoom.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // 6. Keep the sage highlight long enough to identify it
                setTimeout(function () {
                    newRoom.classList.remove('new-room-highlight');
                }, 8000);

            }, 350);
        }

    @endif

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

        const buildingId = roomsContainer.id.replace('rooms-', '');

        const toggleButton = document.querySelector(
            '.building-toggle[aria-controls="rooms-' + buildingId + '"]'
        );

        // Open the department containing this building
        const departmentContent = roomsContainer.closest('.dept-content');

        if (departmentContent) {
            const departmentId = departmentContent.id.replace('dept', '');
            const departmentButton = document.querySelector('.dept-btn[data-department-id="' + departmentId + '"]');
            if (typeof window.showDept === 'function') window.showDept(departmentId, departmentButton);
            else departmentContent.classList.add('active');
        }

        // Open the building after selecting its department, because selecting a department closes other building panels.
        document.querySelectorAll('.building-rooms.open').forEach(function (container) {
            container.classList.remove('open');
            const addButton = container.closest('.building-card')?.querySelector('.building-add-room');
            if (addButton) addButton.style.display = 'none';
        });
        roomsContainer.classList.add('open');
        if (toggleButton) {
            toggleButton.classList.add('open');
            toggleButton.setAttribute('aria-expanded', 'true');
        }

        const addRoomButton = roomsContainer.closest('.building-card')?.querySelector('.building-add-room');
        if (addRoomButton) addRoomButton.style.display = 'inline-flex';
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

            // Apply the restrained sage search highlight.
            room.style.setProperty('background-color', '#e6eee7', 'important');
            room.style.setProperty('border', '3px solid #6e9577', 'important');
            room.style.setProperty('box-shadow',
                '0 0 0 4px rgba(77, 126, 88, 0.12), 0 8px 18px rgba(50, 70, 55, 0.12)',
                'important'
            );
        });

        // Open the correct department and building.
        openRoomLocation(foundRooms[0]);

        resultMessage.innerHTML =
            '<span class="room-search-found">' +
            'Room "' + searchInput.value + '" found' +
            '</span>';

        // Scroll directly to the matching room.
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.buildings-admin-view');
    const initialDepartmentId = page ? page.getAttribute('data-initial-department') : '';
    const departmentSelect = document.querySelector('.buildings-admin-view .add-form select[name="department_id"]');

    if (initialDepartmentId) {
        const departmentButton = document.querySelector('.dept-btn[data-department-id="' + initialDepartmentId + '"]');
        if (typeof window.showDept === 'function') window.showDept(initialDepartmentId, departmentButton);
    }

    if (departmentSelect) {
        departmentSelect.addEventListener('change', function () {
            if (this.value && typeof window.showDept === 'function') {
                window.showDept(this.value);
            }
        });
    }

    let pendingDeleteForm = null;
    let deleteTrigger = null;
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteMessage = document.getElementById('deleteConfirmMessage');
    const cancelDelete = document.getElementById('cancelDeleteButton');
    const acceptDelete = document.getElementById('acceptDeleteButton');

    const closeDeleteModal = function () {
        if (deleteModal) deleteModal.style.display = 'none';
        pendingDeleteForm = null;
        if (deleteTrigger) deleteTrigger.focus();
        deleteTrigger = null;
    };

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-confirm-delete] button[type="submit"]');
        if (!trigger) return;

        const form = trigger.closest('[data-confirm-delete]');
        if (!form || !deleteModal) return;

        event.preventDefault();
        pendingDeleteForm = form;
        deleteTrigger = trigger;
        if (deleteMessage) deleteMessage.textContent = form.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this item?';
        deleteModal.style.display = 'block';
        if (cancelDelete) cancelDelete.focus();
    });

    if (cancelDelete) cancelDelete.addEventListener('click', closeDeleteModal);
    if (deleteModal) deleteModal.addEventListener('click', function (event) {
        if (event.target === deleteModal) closeDeleteModal();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'block') closeDeleteModal();
    });
    if (acceptDelete) acceptDelete.addEventListener('click', function () {
        if (!pendingDeleteForm) return;
        const form = pendingDeleteForm;
        pendingDeleteForm = null;
        form.submit();
    });
});
</script>

<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

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
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 15px;
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
}

.move-btn:hover {
    background: #e89a00;
    color: white;
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

            </style>


            <!-- TITLE -->

            <div class="title">
                Manage Buildings
            </div>

            <div class="subtitle">
                Add, edit and remove buildings and rooms by department.
            </div>


            <!-- SUCCESS -->

            @if(session('success'))

                <div class="success-message">
                    {{ session('success') }}
                </div>

            @endif


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

                <h3>Add New Building</h3>

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
                        placeholder="Example: Flavier Building"
                        required
                    >


                    <button type="submit" class="btn green">
                        Add Building
                    </button>

                </form>

            </div>


            <!-- DEPARTMENTS -->

            <div class="departments">

                @foreach($departments as $index => $dept)

                    <button
                        type="button"
                        class="dept-btn {{ $index == 0 ? 'active' : '' }}"
                        onclick="showDept({{ $dept->id }}, this)"
                    >

                        {{ $dept->name }}

                    </button>

                @endforeach

            </div>


            <!-- DEPARTMENT CONTENT -->

            @foreach($departments as $index => $dept)

                <div
                    id="dept{{ $dept->id }}"
                    class="dept-content {{ $index == 0 ? 'active' : '' }}"
                >

                   @forelse($dept->buildings as $building)

    <div class="building-card">

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

                <h3>
                    {{ $building->name }}
                </h3>

            </div>


            <div class="building-actions">

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
                >

                    @csrf
                    @method('DELETE')

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
            id="rooms-{{ $building->id }}"
            class="building-rooms"
        >

            <div class="room-section">

                <div class="room-title">
                    Rooms
                </div>


                @forelse($building->rooms as $room)

                    <div class="room-card">

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
    >

        @csrf
        @method('DELETE')

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


                <!-- ADD ROOM -->
                <button
                    type="button"
                    class="btn green"
                    onclick="openAddRoom(
                        {{ $dept->id }},
                        {{ $building->id }}
                    )"
                >
                    + Add Room
                </button>

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


                        <label>
                            Room Name
                        </label>

                        <input
                            type="text"
                            name="room_name"
                            placeholder="Example: Lab 1"
                            required
                        >


                        <label>
                            Capacity
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            min="1"
                            placeholder="Example: 40"
                        >


                        <label>
                            Number of Computers
                        </label>

                        <input
                            type="number"
                            name="computers"
                            min="0"
                            value="0"
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
                            Capacity
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            id="editRoomCapacity"
                            min="1"
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

</script>
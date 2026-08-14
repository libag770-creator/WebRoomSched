<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                * {
                    box-sizing: border-box;
                }

                .page-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .page-subtitle {
                    color: #777;
                    margin-bottom: 20px;
                }

                .panel {
                    background: #fff;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    overflow: hidden;
                    margin-bottom: 20px;
                    max-width: 800px;
                }

                .panel-header {
                    padding: 15px 20px;
                    border-bottom: 1px solid #ddd;
                }

                .panel-header h3 {
                    margin: 0;
                    font-size: 18px;
                }

                .panel-header p {
                    margin: 5px 0 0;
                    color: #777;
                    font-size: 13px;
                }

                .form-body {
                    padding: 20px;
                }

                .form-group {
                    margin-bottom: 18px;
                }

                .form-group label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 7px;
                    font-size: 14px;
                }

                .form-group select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background: white;
                    font-size: 14px;
                }

                .form-group select:focus {
                    outline: none;
                    border-color: #2e7d32;
                }

                .room-info {
                    background: #f5f5f5;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    padding: 12px;
                    margin-top: 10px;
                    color: #555;
                }

                .room-info strong {
                    color: #333;
                }

                .success-message {
                    background: #d4edda;
                    color: #155724;
                    padding: 12px 15px;
                    margin-bottom: 15px;
                    border-radius: 5px;
                }

                .error-message {
                    background: #f8d7da;
                    color: #721c24;
                    padding: 12px 15px;
                    margin-bottom: 15px;
                    border-radius: 5px;
                }

                .error-message ul {
                    margin: 5px 0 0 20px;
                }

                .form-buttons {
                    display: flex;
                    gap: 10px;
                    margin-top: 20px;
                }

                .btn {
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    font-weight: bold;
                    cursor: pointer;
                    text-decoration: none;
                    text-align: center;
                }

                .btn-green {
                    background: #2e7d32;
                    color: white;
                }

                .btn-green:hover {
                    background: #256628;
                }

                .btn-red {
                    background: #c62828;
                    color: white;
                }

                .btn-red:hover {
                    background: #a51f1f;
                }

                @media(max-width: 700px) {

                    .panel {
                        width: 100%;
                    }

                    .form-buttons {
                        flex-direction: column;
                    }

                    .btn {
                        width: 100%;
                    }

                }

            </style>


            <!-- PAGE TITLE -->

            <div class="page-title">
                Room Re-Assignment
            </div>

            <div class="page-subtitle">
                Transfer a room from one department building to another department building.
            </div>


            <!-- SUCCESS MESSAGE -->

            @if(session('success'))

                <div class="success-message">
                    {{ session('success') }}
                </div>

            @endif


            <!-- ERROR MESSAGE -->

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


            <!-- ASSIGNED ROOM -->

            <div class="panel">

                <div class="panel-header">

                    <h3>
                        Assigned Room
                    </h3>

                    <p>
                        Select the department, building, and room that you want to reassign.
                    </p>

                </div>


                <div class="form-body">

                    <form
                        action="{{ route('roomreassignment.update') }}"
                        method="POST"
                        id="reassignmentForm"
                    >

                        @csrf


                        <!-- CURRENT DEPARTMENT -->

                        <div class="form-group">

                            <label for="department">
                                Select Department
                            </label>

                            <select id="department" required>

                                <option value="">
                                    Select Department
                                </option>

                                @foreach($departments as $department)

                                    <option value="{{ $department->id }}">
                                        {{ $department->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- CURRENT BUILDING -->

                        <div class="form-group">

                            <label for="current_building">
                                Select Building
                            </label>

                            <select id="current_building" required disabled>

                                <option value="">
                                    Choose Building
                                </option>

                            </select>

                        </div>


                        <!-- CURRENT ROOM -->

                        <div class="form-group">

                            <label for="current_room">
                                Select Room
                            </label>

                            <select
                                id="current_room"
                                required
                                disabled
                            >

                                <option value="">
                                    Choose Room
                                </option>

                            </select>

                        </div>


                        <!-- REASON -->

                        <div class="room-info">

                            <strong>Reason:</strong>

                            <span id="roomReason">
                                Select a room
                            </span>

                        </div>


                        <!-- HIDDEN ROOM ID -->

                        <input
                            type="hidden"
                            name="room_id"
                            id="room_id"
                        >


                        <br>


                        <!-- REASSIGN TO -->

                        <h3>
                            Re-Assign Room
                        </h3>


                        <!-- NEW DEPARTMENT -->

                        <div class="form-group">

                            <label for="new_department">
                                Select Department
                            </label>

                            <select id="new_department" required>

                                <option value="">
                                    Select Department
                                </option>

                                @foreach($departments as $department)

                                    <option value="{{ $department->id }}">
                                        {{ $department->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- NEW BUILDING -->

                        <div class="form-group">

                            <label for="building_id">
                                Select Destination Building
                            </label>

                            <select
                                name="building_id"
                                id="building_id"
                                required
                                disabled
                            >

                                <option value="">
                                    Choose Building
                                </option>

                            </select>

                        </div>


                        <!-- BUTTONS -->

                        <div class="form-buttons">

                            <button
                                type="submit"
                                class="btn btn-green"
                            >
                                Apply
                            </button>

                            <button
                                type="reset"
                                class="btn btn-red"
                                onclick="resetForm()"
                            >
                                Cancel
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            <script>

                /*
                |--------------------------------------------------------------------------
                | Department Data
                |--------------------------------------------------------------------------
                */

                const departments = @json($departments);


                /*
                |--------------------------------------------------------------------------
                | Current Department
                |--------------------------------------------------------------------------
                */

                const departmentSelect =
                    document.getElementById('department');

                const buildingSelect =
                    document.getElementById('current_building');

                const roomSelect =
                    document.getElementById('current_room');


                departmentSelect.addEventListener('change', function () {

                    const departmentId = this.value;

                    buildingSelect.innerHTML =
                        '<option value="">Choose Building</option>';

                    roomSelect.innerHTML =
                        '<option value="">Choose Room</option>';

                    buildingSelect.disabled = true;
                    roomSelect.disabled = true;

                    if (!departmentId) {
                        return;
                    }


                    const department = departments.find(
                        d => d.id == departmentId
                    );


                    if (
                        department &&
                        department.buildings
                    ) {

                        department.buildings.forEach(function (building) {

                            buildingSelect.innerHTML += `
                                <option value="${building.id}">
                                    ${building.name}
                                </option>
                            `;

                        });

                        buildingSelect.disabled = false;
                    }

                });


                /*
                |--------------------------------------------------------------------------
                | Current Building
                |--------------------------------------------------------------------------
                */

                buildingSelect.addEventListener('change', function () {

                    const buildingId = this.value;

                    roomSelect.innerHTML =
                        '<option value="">Choose Room</option>';

                    roomSelect.disabled = true;

                    if (!buildingId) {
                        return;
                    }


                    const department =
                        departments.find(
                            d => d.id == departmentSelect.value
                        );


                    if (!department) {
                        return;
                    }


                    const building =
                        department.buildings.find(
                            b => b.id == buildingId
                        );


                    if (
                        building &&
                        building.rooms
                    ) {

                        building.rooms.forEach(function (room) {

                            roomSelect.innerHTML += `
                                <option value="${room.id}">
                                    ${room.name ?? room.room_number ?? 'Room ' + room.id}
                                </option>
                            `;

                        });

                        roomSelect.disabled = false;
                    }

                });


                /*
                |--------------------------------------------------------------------------
                | Select Current Room
                |--------------------------------------------------------------------------
                */

                roomSelect.addEventListener('change', function () {

                    const roomId = this.value;

                    document.getElementById('room_id').value =
                        roomId;

                    const department =
                        departments.find(
                            d => d.id == departmentSelect.value
                        );

                    const building =
                        department?.buildings.find(
                            b => b.id == buildingSelect.value
                        );

                    const room =
                        building?.rooms.find(
                            r => r.id == roomId
                        );


                    if (room) {

                        document.getElementById('roomReason').innerText =
                            room.reason ?? 'Room selected';

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | New Department
                |--------------------------------------------------------------------------
                */

                const newDepartment =
                    document.getElementById('new_department');

                const newBuilding =
                    document.getElementById('building_id');


                newDepartment.addEventListener('change', function () {

                    const departmentId = this.value;

                    newBuilding.innerHTML =
                        '<option value="">Choose Building</option>';

                    newBuilding.disabled = true;


                    if (!departmentId) {
                        return;
                    }


                    const department = departments.find(
                        d => d.id == departmentId
                    );


                    if (
                        department &&
                        department.buildings
                    ) {

                        department.buildings.forEach(function (building) {

                            newBuilding.innerHTML += `
                                <option value="${building.id}">
                                    ${building.name}
                                </option>
                            `;

                        });

                        newBuilding.disabled = false;
                    }

                });


                /*
                |--------------------------------------------------------------------------
                | Reset
                |--------------------------------------------------------------------------
                */

                function resetForm() {

                    document.getElementById('current_building').disabled = true;

                    document.getElementById('current_room').disabled = true;

                    document.getElementById('building_id').disabled = true;

                    document.getElementById('room_id').value = '';

                    document.getElementById('roomReason').innerText =
                        'Select a room';

                }

            </script>


        </main>

    </div>

</div>
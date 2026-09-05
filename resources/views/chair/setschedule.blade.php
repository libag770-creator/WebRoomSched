<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content">

            <style>

                * {
                    box-sizing: border-box;
                }

                body {
                    background: #f5f5f5;
                }

                .page {
                    max-width: 1200px;
                    margin: auto;
                }

                .title {
                    font-size: 28px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 5px;
                }

                .subtitle {
                    color: #777;
                    margin-bottom: 25px;
                }

                .section {
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    box-shadow: 0 2px 10px rgba(0,0,0,.07);
                }

                /* DEPARTMENT */

                .department-display {
                    background: #e8f5e9;
                    border-left: 5px solid #2e7d32;
                    padding: 15px 18px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                }

                .department-label {
                    font-size: 12px;
                    color: #777;
                    margin-bottom: 3px;
                }

                .department-name {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2e7d32;
                }

                /* BUILDINGS */

                .building {
                    margin-top: 20px;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .building-title {
                    padding: 13px 15px;
                    background: #e8f5e9;
                    color: #2e7d32;
                    font-weight: bold;
                }

                /* ROOMS */

                .room-grid {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 12px;
                    padding: 15px;
                }

                .room-card {
                    width: 220px;
                    background: #fafafa;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 15px;
                }

                .room-name {
                    font-weight: bold;
                    margin-bottom: 12px;
                    color: #333;
                }

                /* BUTTON */

                .btn {
                    border: none;
                    border-radius: 6px;
                    padding: 9px 14px;
                    cursor: pointer;
                    font-weight: bold;
                    text-decoration: none;
                    display: inline-block;
                }

                .green {
                    background: #2e7d32;
                    color: white;
                }

                .green:hover {
                    background: #1b5e20;
                }

                /* PERMISSION */

                .permission-box {
                    margin-top: 12px;
                    padding-top: 12px;
                    border-top: 1px solid #ddd;
                }

                .permission-label {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    cursor: pointer;
                    font-size: 12px;
                    color: #555;
                    line-height: 1.4;
                }

                .permission-label input {
                    width: 16px;
                    height: 16px;
                    cursor: pointer;
                    accent-color: #2e7d32;
                }

                .permission-status {
                    margin-top: 5px;
                    margin-left: 24px;
                    font-size: 11px;
                    font-weight: bold;
                }

                .status-on {
                    color: #2e7d32;
                }

                .status-off {
                    color: #999;
                }

                /* MESSAGES */

                .message {
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .success {
                    background: #e8f5e9;
                    color: #2e7d32;
                }

                .error {
                    background: #ffebee;
                    color: #b71c1c;
                }

                .empty {
                    padding: 20px;
                    text-align: center;
                    color: #777;
                    background: #fafafa;
                    border-radius: 8px;
                }

            </style>


            <div class="page">

                <div class="title">
                    Set Schedule
                </div>

                <div class="subtitle">
                    Set schedules for your department's classrooms.
                </div>


                {{-- SUCCESS MESSAGE --}}

                @if(session('success'))

                    <div class="message success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR MESSAGE --}}

                @if(session('error'))

                    <div class="message error">
                        {{ session('error') }}
                    </div>

                @endif


                <div class="section">


                    {{-- =====================================================
                         DEPARTMENT
                    ====================================================== --}}

                    <div class="department-display">

                        <div class="department-label">
                            Your Department
                        </div>

                        <div class="department-name">
                            {{ $department->name }}
                        </div>

                    </div>


                    {{-- =====================================================
                         BUILDINGS AND ROOMS
                    ====================================================== --}}

                    <h3 style="color:#2e7d32; margin-top:0;">
                        Buildings and Rooms
                    </h3>


                    @forelse($buildings as $building)

                        <div class="building">

                            <div class="building-title">

                                {{ $building->name }}

                            </div>


                            <div class="room-grid">


                                @forelse($building->rooms as $room)

                                    <div class="room-card">

                                        <div class="room-name">

                                            {{ $room->room_name }}

                                        </div>


                                        {{-- SET SCHEDULE BUTTON --}}

                                        <a
                                            href="{{ route(
                                                'chair.excel',
                                                $room->id
                                            ) }}"
                                            class="btn green"
                                        >

                                            Set Schedule

                                        </a>


                                        {{-- =================================================
                                             CROSS-DEPARTMENT PERMISSION
                                        ================================================== --}}

                                        <div class="permission-box">

                                            <label
                                                class="permission-label"
                                                for="permission-{{ $room->id }}"
                                            >

                                                <input
                                                    type="checkbox"
                                                    id="permission-{{ $room->id }}"
                                                    {{ $room->allow_other_departments ? 'checked' : '' }}
                                                    onchange="updateRoomPermission(
                                                        {{ $room->id }},
                                                        this
                                                    )"
                                                >

                                                <span>
                                                    Allow other departments
                                                    to add to empty slots
                                                </span>

                                            </label>


                                            <div
                                                id="status-{{ $room->id }}"
                                                class="permission-status
                                                {{
                                                    $room->allow_other_departments
                                                        ? 'status-on'
                                                        : 'status-off'
                                                }}"
                                            >

                                                {{
                                                    $room->allow_other_departments
                                                        ? 'ON'
                                                        : 'OFF'
                                                }}

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <div class="empty">

                                        No rooms in this building.

                                    </div>

                                @endforelse


                            </div>

                        </div>

                    @empty

                        <div class="empty">

                            No buildings are assigned
                            to your department.

                        </div>

                    @endforelse


                </div>

            </div>


            {{-- =============================================================
                 PERMISSION JAVASCRIPT
            ============================================================== --}}

            <script>

               function updateRoomPermission(roomId, checkbox)
{
    const allowed = checkbox.checked;

    fetch(
        "{{ url('/chair/room') }}/" + roomId + "/permission",
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

                
            </script>

        </main>

    </div>

    @include('footerheader.footer')

</div>
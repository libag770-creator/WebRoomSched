<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content">
            <style>
body{
    background:#f5f5f5;
}

.schedule-container{
    max-width:1200px;
    margin:auto;
}

.title{
    font-size:18px;
    font-weight:bold;
}

.subtitle{
    color:#777;
    font-size:12px;
}

.departments{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.department{
    width:150px;
    background:#fff;
    border-radius:6px;
    box-shadow:0 1px 5px rgba(0,0,0,.1);
    cursor:pointer;
    border:2px solid transparent;
    transition:.3s;
}

.department.active{
    border-color:#28a745;
}

.department h3{
    margin:15px 15px 0;
    font-size:30px;
}

.department p{
    margin:0 15px 15px;
    font-size:18px;
    color:#555;
}

.content{
    margin-top:15px;
    background:#fff;
    border:1px solid #ccc;
    border-radius:6px;
    min-height:550px;
    padding:20px;
}


.building{
    border:1px solid #bbb;
    margin-bottom:15px;
}

.building-title{
    background:#efefef;
    padding:8px 10px;
    font-weight:bold;
}

.rooms{
    display:flex;
    flex-wrap:wrap;
    gap:15px;
    padding:15px;
    align-items:flex-start;
}

.room{
    width:100%;
    height:140px;
    border:1px solid #ccc;
    border-radius:4px;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#fff;
    color:#888;
    box-sizing:border-box;
}

.room img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.room-container{
    width:220px;
}

.room-label{
    font-size:16px;
    font-weight:bold;
    margin-bottom:5px;
    text-align:left; 
}
</style>

<div class="schedule-container">

    <div class="title">Classroom Schedule</div>
    <div class="subtitle">View classroom schedules by department</div>

    <div class="departments">

        <div class="department active" onclick="showDept('cat',this)">
            <h3>CAT</h3>
            <p>3</p>
        </div>

        <div class="department" onclick="showDept('ccjepa',this)">
            <h3>CCJEPA</h3>
            <p>3</p>
        </div>

        <div class="department" onclick="showDept('ced',this)">
            <h3>CED</h3>
            <p>3</p>
        </div>

    </div>


    <div class="content">

    <div class="schedule-content">











   <!-- CAT -->
<div id="cat" class="dept" style="display:block">

    @foreach($rooms->where('department_id',1)->groupBy('building') as $building => $buildingRooms)

    <div class="building">

        <div class="building-title">
            {{ $building }}
        </div>

        <div class="rooms">

            @foreach($buildingRooms as $room)

            <div class="room-container">

                <div class="room-label">
                    {{ $room->room_name }}
                </div>

                <a href="{{ route('chair.excel', $room->id) }}">

                    <div class="room">

@if($room->schedules->count())

    <strong>✓ Schedule Available</strong>

    <br><br>

    <a href="{{ route('chair.excel', $room->id) }}">
        Edit
    </a>

    |

    <form action="{{ route('chair.schedule.delete', $room->id) }}"
          method="POST"
          style="display:inline">

        @csrf
        @method('DELETE')

        <button type="submit"
                onclick="return confirm('Delete this schedule?')">
            Delete
        </button>

    </form>

@else

    <a href="{{ route('chair.excel', $room->id) }}">
        Set Schedule
    </a>

@endif

</div>

                </a>

            </div>

            @endforeach

        </div>

    </div>

    @endforeach

</div>



<!-- CCJEPA -->
<div id="ccjepa" class="dept" style="display:none">

    @foreach($rooms->where('department_id',2)->groupBy('building') as $building => $buildingRooms)

    <div class="building">

        <div class="building-title">
            {{ $building }}
        </div>


        <div class="rooms">

            @foreach($buildingRooms as $room)

            <div class="room-container">

                <div class="room-label">
                    {{ $room->room_name }}
                </div>


                <a href="{{ route('chair.excel', $room->id) }}">

                    <div class="room">

@if($room->schedules->count())

    <strong>✓ Schedule Available</strong>

    <br><br>

    <a href="{{ route('chair.excel', $room->id) }}">
        Edit
    </a>

    |

    <form action="{{ route('chair.schedule.delete', $room->id) }}"
          method="POST"
          style="display:inline">

        @csrf
        @method('DELETE')

        <button type="submit"
                onclick="return confirm('Delete this schedule?')">
            Delete
        </button>

    </form>

@else

    <a href="{{ route('chair.excel', $room->id) }}">
        Set Schedule
    </a>

@endif

</div>

                </a>


            </div>

            @endforeach


        </div>

    </div>


    @endforeach

</div>




<!-- CED -->
<div id="ced" class="dept" style="display:none">


    @foreach($rooms->where('department_id',3)->groupBy('building') as $building => $buildingRooms)

    

    <div class="building">


        <div class="building-title">

            {{ $building }}

        </div>


        <div class="rooms">


            @foreach($buildingRooms as $room)


            <div class="room-container">


                <div class="room-label">

                    {{ $room->room_name }}

                </div>



                <a href="{{ route('chair.excel', $room->id) }}">


                    <div class="room">

@if($room->schedules->count())

    <strong>✓ Schedule Available</strong>

    <br><br>

    <a href="{{ route('chair.excel', $room->id) }}">
        Edit
    </a>

    |

   <form action="{{ route('chair.schedule.delete', $room->id) }}"
      method="POST"
      onsubmit="return confirm('Delete all schedules for this room?')">

    @csrf
    @method('DELETE')

    <button type="submit">
        Delete Schedule
    </button>

</form>

@else

    <a href="{{ route('chair.excel', $room->id) }}">
        Set Schedule
    </a>

@endif

</div>


                </a>



            </div>


            @endforeach



        </div>


    </div>


    @endforeach


</div>

<script>
function showDept(id, element){

    // Hide all department contents
    document.querySelectorAll('.dept').forEach(function(div){
        div.style.display = 'none';
    });

    // Show selected department
    document.getElementById(id).style.display = 'block';

    // Remove active state from all department cards
    document.querySelectorAll('.department').forEach(function(card){
        card.classList.remove('active');
    });

    // Highlight selected department
    element.classList.add('active');
}
</script>

        </main>

    </div>

    @include('footerheader.footer')

</div>
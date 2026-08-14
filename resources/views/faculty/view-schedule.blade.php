<!DOCTYPE html>
<html>

<head>

    <title>{{ $room->room_name }}</title>

    <style>

        body{
            font-family:Arial;
            padding:30px;
            background:#f5f5f5;
        }

        table{

            border-collapse:collapse;
            width:100%;
            background:white;

        }

        th,td{

            border:1px solid #ccc;
            padding:12px;
            text-align:center;

        }

        th{

            background:#28a745;
            color:white;

        }

        h2{

            margin-bottom:20px;

        }

    </style>

</head>

<body>

<h2>

Room :
{{ $room->room_name }}

</h2>

<table>

<tr>

<th>Time</th>

<th>Monday</th>

<th>Tuesday</th>

<th>Wednesday</th>

<th>Thursday</th>

<th>Friday</th>

</tr>

@php

$times = [
'8:00-9:00',
'9:00-10:00',
'10:00-11:00'
];

$days = [
'MON',
'TUE',
'WED',
'THU',
'FRI'
];

@endphp

@foreach($times as $time)

<tr>

<td>

{{ $time }}

</td>

@foreach($days as $day)

<td>

@foreach($schedules as $schedule)

@if($schedule->day==$day && $schedule->time==$time)

{{ $schedule->subject }}

@endif

@endforeach

</td>

@endforeach

</tr>

@endforeach

</table>

</body>

</html>
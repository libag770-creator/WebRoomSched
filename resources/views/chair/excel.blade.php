<!DOCTYPE html>
<html>
<head>

    <title>Chair Schedule Editor</title>

    <style>

        body{
            font-family:Arial, Helvetica, sans-serif;
            background:#f5f5f5;
            margin:30px;
        }

        h2{
            margin-bottom:20px;
        }

        table{
            border-collapse:collapse;
            background:white;
        }

        th,td{
            border:1px solid #888;
            width:120px;
            height:45px;
            text-align:center;
        }

        th{
            background:#e9ecef;
        }

        td{
            cursor:text;
        }

        td:focus{
            outline:2px solid #0d6efd;
        }

        .toolbar{
            margin-bottom:20px;
        }

        button{
            padding:8px 15px;
            margin-right:10px;
            cursor:pointer;
        }

    </style>

</head>

<body>


<h2>
    Schedule Editor - {{ $room->room_name }}
</h2>


<form method="POST" action="{{ route('chair.save.schedule') }}" onsubmit="prepareSchedule()">

@csrf

<input type="hidden" name="room_id" value="{{ $room->id }}">

<div class="toolbar">

    <button type="button" onclick="addRow()">
        + Row
    </button>

    <button type="button" onclick="addColumn()">
        + Column
    </button>

    <input
        type="color"
        id="cellColor"
        value="#ffffff">

    <button
        type="button"
        onclick="applyColor()">
        Change Color
    </button>

    <button type="submit">
        Upload Schedule
    </button>

</div>

<hr><br>

<h3>Selected Cell</h3>

<table style="width:500px; margin-bottom:15px;">

    <tr>
        <td>Course Code</td>
        <td>
            <input type="text" id="course_code">
        </td>
    </tr>

    <tr>
        <td>Subject</td>
        <td>
            <input type="text" id="subject">
        </td>
    </tr>

   <tr>
    <td>Instructor</td>
    <td>
        <select id="instructor">
            <option value="">-- Select Instructor --</option>

            @foreach($users as $user)
                <option
                    value="{{ $user->id }}"
                    data-name="{{ $user->name }}">
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <small id="instructorError"
               style="display:none; color:red;">
            User not found.
        </small>
    </td>
</tr>

    <tr>
        <td>Description</td>
        <td>
            <input type="text" id="description">
        </td>
    </tr>

</table>

<button
    type="button"
    onclick="saveCell()">
    Save Cell
</button>

<br><br>

<table id="scheduleTable">

<thead>
<tr>

<th>TIME</th>

<th>MON</th>

<th>TUE</th>

<th>WED</th>

<th>THU</th>

<th>FRI</th>

</tr>

</thead>
<tbody>
<tr>

<td contenteditable="true">8:00-9:00</td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

</tr>


<tr>

<td contenteditable="true">9:00-10:00</td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

</tr>


<tr>

<td contenteditable="true">10:00-11:00</td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

<td contenteditable="true"></td>

</tr>


</tbody>


</table>


<div id="hiddenInputs"></div>


</form>



<script>

let selectedCell = null;


// ADD THIS HERE
function selectCell(cell){

    if(cell.cellIndex == 0){
        return;
    }


    if(selectedCell){
        selectedCell.style.outline = "";
    }


    selectedCell = cell;


    selectedCell.style.outline = "3px solid #0d6efd";

}



document.querySelectorAll("#scheduleTable td").forEach(function(cell){

    // Ignore the first column (TIME)
    if(cell.cellIndex == 0) return;

   cell.addEventListener("click", function(){

    if(selectedCell){
        selectedCell.style.outline = "";
    }


    selectedCell = this;


    selectedCell.style.outline = "3px solid #0d6efd";

        document.getElementById("course_code").value =
            this.dataset.course || "";

        document.getElementById("subject").value =
            this.dataset.subject || "";

        document.getElementById("instructor").value =
            this.dataset.instructor || "";

        document.getElementById("description").value =
            this.dataset.description || "";

        document.getElementById("cellColor").value =
            this.dataset.color || "#ffffff";

    });

});
function saveCell(){

    if(selectedCell == null){

        alert("Please select a schedule cell first.");

        return;
    }


    let course = document.getElementById("course_code").value;

    let subject = document.getElementById("subject").value;

    let instructor = document.getElementById("instructor").value;

    let description = document.getElementById("description").value;

    let color = document.getElementById("cellColor").value;



    selectedCell.innerHTML = 
    `
    <b>${course}</b><br>
    ${subject}<br>
    <small>${instructor}</small>
    `;



    selectedCell.style.backgroundColor = color;



    // Store data inside the cell

    selectedCell.dataset.course = course;

    selectedCell.dataset.subject = subject;

    selectedCell.dataset.instructor = instructor;

    selectedCell.dataset.description = description;

    selectedCell.dataset.color = color;

}



function addRow(){

    let table=document.getElementById("scheduleTable");

    let cols=table.rows[0].cells.length;

    let row=table.insertRow(-1);


    for(let i=0;i<cols;i++){

        let cell=row.insertCell();

        cell.contentEditable=true;

    }

}



function addColumn(){

    let table=document.getElementById("scheduleTable");

    let rows=table.rows;


    for(let i=0;i<rows.length;i++){

        let cell;


        if(i==0){

            cell=document.createElement("th");

            cell.innerHTML="NEW";

        }

        else{

            cell=document.createElement("td");

            cell.contentEditable=true;

        }


        rows[i].appendChild(cell);

    }

function saveCell(){

    if(selectedCell == null){

        alert("Select a schedule cell first.");

        return;

    }

    let course =
        document.getElementById("course_code").value;

    let subject =
        document.getElementById("subject").value;

    let instructor =
        document.getElementById("instructor").value;

    let description =
        document.getElementById("description").value;

    let color =
        document.getElementById("cellColor").value;


    selectedCell.dataset.course = course;

    selectedCell.dataset.subject = subject;

    selectedCell.dataset.instructor = instructor;

    selectedCell.dataset.description = description;

    selectedCell.dataset.color = color;


    selectedCell.style.background = color;


    selectedCell.innerHTML =
        "<strong>"+course+"</strong><br>"
        + instructor;

}

function applyColor(){

    if(selectedCell==null){

        alert("Select a cell first.");

        return;

    }

    selectedCell.style.background =
        document.getElementById("cellColor").value;

}
}




function prepareSchedule(){

    let table = document.getElementById("scheduleTable");

    let hidden = document.getElementById("hiddenInputs");

    hidden.innerHTML = "";


    let rows = table.tBodies[0].rows;


    for(let i = 0; i < rows.length; i++){

        let time = rows[i].cells[0].innerText;


        for(let j = 1; j < rows[i].cells.length; j++){

            let cell = rows[i].cells[j];


            let subject = cell.dataset.subject;


            // only save cells with data
            if(subject && subject.trim() !== ""){


                let days = [
                    "MON",
                    "TUE",
                    "WED",
                    "THU",
                    "FRI"
                ];


                hidden.innerHTML += `

                <input type="hidden"
                name="schedule[${i}][day]"
                value="${days[j-1]}">


                <input type="hidden"
                name="schedule[${i}][time]"
                value="${time}">


                <input type="hidden"
                name="schedule[${i}][course_code]"
                value="${cell.dataset.course}">


                <input type="hidden"
                name="schedule[${i}][subject]"
                value="${cell.dataset.subject}">


                <input type="hidden"
                name="schedule[${i}][instructor]"
                value="${cell.dataset.instructor}">


                <input type="hidden"
                name="schedule[${i}][description]"
                value="${cell.dataset.description}">


                <input type="hidden"
                name="schedule[${i}][color]"
                value="${cell.dataset.color}">


                `;

            }

        }

    }

}


</script>


</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>WebRoom Sched</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
        }

        .left{
            width:50%;
            background:#2e8b57;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .right{
            width:50%;
            background:#f8fff8;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
        }

        .card{
            width:320px;
            background:white;
            padding:35px;
            border-radius:8px;
            box-shadow:0 0 15px rgba(0,0,0,.2);
        }

        .buttons{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:15px;
        }

        .btn{
            background:#2ca25f;
            color:white;
            text-decoration:none;
            padding:12px;
            text-align:center;
            border-radius:5px;
            transition:.3s;
        }

        .btn:hover{
            background:#21834b;
        }

        img{
            width:170px;
        }

        h1{
            margin-top:20px;
            font-size:60px;
            text-align:center;
        }

        p{
            margin-top:10px;
            color:#555;
        }

    </style>

</head>
<body>

<div class="left">

    <div class="card">

        <div class="buttons">

            <a href="{{ route('faculty.login') }}" class="btn">
                Admin
            </a>

            <a href="{{ route('student.choosedep') }}" class="btn">
                Student
            </a>

            <a href="{{ route('faculty.login') }}" class="btn">
                Faculty
            </a>

           <a href="{{ route('faculty.login') }}" class="btn">
    Department Chair
</a>

        </div>

    </div>

</div>

<div class="right">

    <img src="{{ asset('images/images.jpg') }}" alt="Logo">

    <p>BSU Bokod Campus</p>

    <h1>
        WebRoom<br>Sched
    </h1>

</div>

</body>
</html>
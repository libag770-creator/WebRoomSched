<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student access · WebRoomSched</title>
    <link rel="stylesheet" href="{{ asset('css/brand-system.css') }}">
    <style>
        body{min-height:100vh;margin:0;display:grid;place-items:center;padding:22px;background:#f3f6f2;color:#203328;font-family:"DM Sans",Arial,sans-serif}
        .access-card{width:min(440px,100%);padding:36px;border:1px solid #e3eae3;border-radius:22px;background:white;box-shadow:0 20px 60px #18352218}
        .back-link{display:inline-flex;align-items:center;gap:7px;margin:-8px 0 18px;color:#276747;font-size:13px;font-weight:750;text-decoration:none}.back-link:hover{text-decoration:underline}.back-link:focus-visible{outline:3px solid #27674733;outline-offset:3px;border-radius:4px}
        .access-icon{width:54px;height:54px;display:grid;place-items:center;border-radius:16px;background:#eaf4ec;color:#276747;font-size:24px}
        h1{margin:20px 0 8px;font:800 25px Manrope,Arial,sans-serif;letter-spacing:-.5px}p{margin:0;color:#66756b;font-size:14px;line-height:1.6}
        label{display:block;margin:24px 0 8px;font-size:12px;font-weight:750;color:#344b3a}.code-input{width:100%;height:54px;box-sizing:border-box;border:1px solid #d5dfd6;border-radius:11px;padding:0 15px;font-size:20px;font-weight:800;letter-spacing:3px;text-align:center;text-transform:uppercase;color:#203328}.code-input:focus{outline:3px solid #27674722;border-color:#276747}
        .error{margin-top:7px;color:#b42318;font-size:12px}.submit{width:100%;height:48px;margin-top:16px;border:0;border-radius:11px;background:#276747;color:white;font-weight:800;cursor:pointer}.submit:hover{background:#1d5238}.help{margin-top:17px;text-align:center;font-size:12px}.brand{margin-top:28px;text-align:center;color:#89958d;font-size:10px;font-weight:750;letter-spacing:1.3px}
        @media(max-width:480px){.access-card{padding:28px 22px}}
    </style>
</head>
<body>
    <main class="access-card">
        <a class="back-link" href="{{ route('welcome') }}"><span aria-hidden="true">←</span> Back to welcome page</a>
        <div class="access-icon" aria-hidden="true">⌑</div>
        <h1>Enter your student code</h1>
        <p>Your college uses an access code to share its current class schedules. Ask an instructor or your college chair for the code.</p>
        <form method="POST" action="{{ route('student.access.verify') }}">
            @csrf
            <label for="studentAccessCode">College access code</label>
            <input id="studentAccessCode" class="code-input" name="code" type="text" maxlength="9" placeholder="ABCD-2345" autocomplete="one-time-code" autocapitalize="characters" required autofocus>
            @error('code')<div class="error" role="alert">{{ $message }}</div>@enderror
            <button class="submit" type="submit">Continue to schedules</button>
        </form>
        <p class="help">The code opens schedules for one college. Keep it within your class community.</p>
        <div class="brand">WEBROOMSCHED · STUDENT ACCESS</div>
    </main>
    <script>
        document.getElementById('studentAccessCode')?.addEventListener('input', function () {
            const raw = this.value.toUpperCase().replace(/[^A-HJ-NP-Z2-9]/g, '').slice(0, 8);
            this.value = raw.length > 4 ? raw.slice(0, 4) + '-' + raw.slice(4) : raw;
        });
    </script>
</body>
</html>

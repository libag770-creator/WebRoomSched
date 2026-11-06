@include('footerheader.header')
<div class="wrapper">
    <div class="main-layout">
        @include('faculty.sidebar')
        <main class="content">
            <style>
                .swap-inbox-page{max-width:1420px;margin:0 auto;padding:18px 10px 42px;color:#24392b}
                .swap-inbox-hero{padding:25px;margin-bottom:20px;background:#fff;border:1px solid #d8e2da;border-radius:16px;box-shadow:0 3px 14px rgba(25,55,37,.065)}
                .swap-inbox-hero h1{margin:0;color:#20352a;font-size:clamp(28px,3vw,36px);line-height:1.2}
                .swap-inbox-hero p{margin:7px 0 0;color:#56685c;font-size:16px}
                .swap-inbox-card{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:20px;align-items:center;margin-bottom:14px;padding:20px;background:#fff;border:1px solid #d8e2da;border-radius:14px;box-shadow:0 3px 12px rgba(25,55,37,.055)}
                .swap-inbox-card h2{margin:0 0 7px;color:#20352a;font-size:20px}
                .swap-inbox-card p{margin:4px 0;color:#53675a;font-size:15px}
                .swap-inbox-actions{display:flex;gap:9px;flex-wrap:wrap}
                .swap-inbox-actions button{min-height:44px;padding:10px 15px;border:1px solid #276747;border-radius:9px;background:#276747;color:#fff;font-size:15px;font-weight:750;cursor:pointer}
                .swap-inbox-actions .decline{border-color:#a63b36;background:#a63b36}
                .swap-inbox-empty{padding:28px;text-align:center;background:#fff;border:1px solid #d8e2da;border-radius:14px;color:#56685c;font-size:16px}
                @media(max-width:760px){.swap-inbox-card{grid-template-columns:1fr}.swap-inbox-actions{width:100%}.swap-inbox-actions form{flex:1}.swap-inbox-actions button{width:100%}}
            </style>
            <div class="swap-inbox-page">
                <header class="swap-inbox-hero"><h1>Room swap requests</h1><p>Review requests from faculty who would like to exchange scheduled rooms.</p></header>
                @if($requests->isNotEmpty())
                    @foreach($requests as $swapRequest)
                        @php($source = $swapRequest->requesterSchedule)
                        @php($target = $swapRequest->targetSchedule)
                        <article class="swap-inbox-card">
                            <div>
                                <h2>{{ $swapRequest->requester?->name ?? 'Faculty member' }} sent a room swap request</h2>
                                <p><strong>Your class:</strong> {{ $target?->course_code ?? 'Class' }} — {{ $target?->subject ?? 'Subject' }}</p>
                                <p><strong>Requested room:</strong> {{ $swapRequest->requesterRoom?->room_name ?? 'Room' }} · {{ $source?->day ?? '—' }} · {{ $source?->time ?? '—' }}</p>
                                <p><strong>Current room:</strong> {{ $swapRequest->targetRoom?->room_name ?? 'Room' }} · {{ $target?->day ?? '—' }} · {{ $target?->time ?? '—' }}</p>
                                @if($swapRequest->reason)<p><strong>Reason:</strong> {{ $swapRequest->reason }}</p>@endif
                                @if($swapRequest->swap_date)<p><strong>Date:</strong> {{ $swapRequest->swap_date->format('M j, Y') }}</p>@endif
                            </div>
                            <div class="swap-inbox-actions">
                                <form method="POST" action="{{ route('faculty.room-swap.approve', $swapRequest->id) }}">@csrf<button type="submit">Accept request</button></form>
                                <form method="POST" action="{{ route('faculty.room-swap.decline', $swapRequest->id) }}">@csrf<button class="decline" type="submit">Decline</button></form>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="swap-inbox-empty">There are no pending room swap requests.</div>
                @endif
            </div>
        </main>
    </div>
</div>
@include('footerheader.footer')

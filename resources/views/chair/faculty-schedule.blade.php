@include('footerheader.header')

<style>
    body.portal-shell.chair-portal .wrapper { min-height:100vh; background:#eef2ec; }
    body.portal-shell.chair-portal .main-layout { min-height:100vh; grid-template-columns:252px minmax(0,1fr); align-items:stretch; }
    body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:sticky; top:0; align-self:start; width:252px; height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); }
    body.portal-shell.chair-portal .top-header { position:fixed; top:16px; right:28px; left:280px; z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    body.portal-shell.chair-portal .header-title small { color:#9a8450; font-size:11px; }
    body.portal-shell.chair-portal .header-title h3 { color:#173b33; font-size:20px; }
    body.portal-shell.chair-portal .main-layout > .chair-faculty-spreadsheet { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }
    .chair-faculty-spreadsheet .spreadsheet-page { width:min(100%,1480px); margin:0 auto; color:#20372a; }
    .chair-faculty-spreadsheet .spreadsheet-hero { position:relative; overflow:hidden; margin:0 0 18px; padding:clamp(25px,3vw,36px); border:1px solid rgba(231,214,173,.22); border-radius:22px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.15); color:#fff; }
    .chair-faculty-spreadsheet .spreadsheet-back { position:relative; z-index:1; display:inline-flex; align-items:center; gap:7px; min-height:37px; margin-bottom:18px; padding:0 12px; border:1px solid rgba(255,255,255,.28); border-radius:9px; background:rgba(255,255,255,.1); color:#fff; font-size:12px; font-weight:800; text-decoration:none; }
    .chair-faculty-spreadsheet .spreadsheet-back:hover { background:rgba(255,255,255,.18); }
    .chair-faculty-spreadsheet .spreadsheet-kicker { display:block; position:relative; z-index:1; margin-bottom:8px; color:#e2c987; font-size:10px; font-weight:850; letter-spacing:.15em; text-transform:uppercase; }
    .chair-faculty-spreadsheet h1 { position:relative; z-index:1; margin:0 0 8px; color:#fff; font:800 clamp(28px,3vw,39px)/1.15 var(--wr-display); letter-spacing:-.04em; }
    .chair-faculty-spreadsheet .spreadsheet-subtitle { position:relative; z-index:1; margin:0; color:#e1e8df; font-size:13px; line-height:1.55; }
    .chair-faculty-spreadsheet .spreadsheet-meta { display:flex; flex-wrap:wrap; gap:8px; margin:0 0 14px; }
    .chair-faculty-spreadsheet .spreadsheet-meta span { padding:8px 11px; border:1px solid #dfe7dd; border-radius:9px; background:#fff; color:#365644; font-size:11px; font-weight:800; }
    .chair-faculty-spreadsheet .spreadsheet-shell { overflow:hidden; border:1px solid #dfe7dd; border-radius:16px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.06); }
    .chair-faculty-spreadsheet .spreadsheet-scroll { width:100%; overflow:auto; }
    .chair-faculty-spreadsheet table { width:100%; min-width:1320px; border-collapse:separate; border-spacing:0; table-layout:fixed; }
    .chair-faculty-spreadsheet thead th { position:sticky; top:0; z-index:2; height:48px; padding:10px; border-right:1px solid #557b63; border-bottom:1px solid #557b63; background:#245940; color:#fff; font-size:11px; font-weight:850; letter-spacing:.04em; text-align:center; }
    .chair-faculty-spreadsheet thead th:first-child { width:150px; background:#173b33; }
    .chair-faculty-spreadsheet thead th:nth-child(5),.chair-faculty-spreadsheet thead th:nth-child(8) { background:#173b33; }
    .chair-faculty-spreadsheet tbody th { width:150px; padding:11px 12px; border-right:1px solid #dfe7dd; border-bottom:1px solid #e5ebe3; background:#f4f7f1; color:#355343; font-size:11px; font-weight:850; text-align:left; vertical-align:top; }
    .chair-faculty-spreadsheet tbody .faculty-schedule-time { width:120px; white-space:nowrap; }
    .chair-faculty-spreadsheet .faculty-schedule-noon-break td { height:34px; background:#edf3eb; color:#315b43; font-size:10px; font-weight:900; text-align:center; }
    .chair-faculty-spreadsheet .faculty-schedule-empty-cell { display:block; padding:9px 4px; color:#a3ada5; font-size:9px; text-align:center; }
    .chair-faculty-spreadsheet td { min-height:88px; padding:7px; border-right:1px solid #e5ebe3; border-bottom:1px solid #e5ebe3; background:#fff; vertical-align:top; }
    .chair-faculty-spreadsheet tbody tr:nth-child(even) td { background:#fbfcfa; }
    .chair-faculty-spreadsheet .class-card { display:grid; gap:4px; margin:0 0 6px; padding:9px; border:1px solid #d9e6d9; border-left:3px solid #32704d; border-radius:8px; background:linear-gradient(145deg,#f0f7ef,#fbfdf9); }
    .chair-faculty-spreadsheet .class-card:last-child { margin-bottom:0; }
    .chair-faculty-spreadsheet .class-card strong { color:#1f6844; font-size:11px; font-weight:900; overflow-wrap:anywhere; }
    .chair-faculty-spreadsheet .class-card span { color:#53675a; font-size:10px; font-weight:650; line-height:1.35; overflow-wrap:anywhere; }
    .chair-faculty-spreadsheet .class-card small { color:#708078; font-size:9px; line-height:1.35; overflow-wrap:anywhere; }
    .chair-faculty-spreadsheet .spreadsheet-empty { padding:35px 20px; color:#65756a; font-size:13px; text-align:center; }
    @media(max-width:760px) {
        body.portal-shell.chair-portal .top-header { position:sticky; top:0; right:auto; left:auto; min-height:64px; margin:0; padding:9px 12px 9px 54px; border:0; border-radius:0 0 15px 15px; }
        body.portal-shell.chair-portal .main-layout { display:block; }
        body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:fixed; top:0; bottom:0; left:0; z-index:1100; width:262px; height:100dvh; min-height:0; padding:20px 15px; }
        body.portal-shell.chair-portal .main-layout > .chair-faculty-spreadsheet { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
    }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include('chair.sidebar')
        <main class="content chair-faculty-spreadsheet">
            <div class="spreadsheet-page">
                @include('chair.partials.faculty-schedule-grid', ['scheduleModal' => false])
            </div>
        </main>
    </div>
</div>

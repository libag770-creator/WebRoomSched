@include('footerheader.header')

@php
    $pendingMergeCount = collect($mergeRequests ?? [])->where('status', 'pending')->count();
    $swapRequestCount = collect($swapRequests ?? [])->count();
@endphp

<style>
    body.portal-shell.chair-portal { --chair-sidebar-width:252px; --chair-shell-gutter:28px; background:#e9eee8; }
    body.portal-shell.chair-portal .wrapper { min-height:100vh; background:#e9eee8; }
    body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); align-items:stretch; min-height:100vh; }
    body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:sticky; top:0; align-self:start; width:var(--chair-sidebar-width); height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); }
    body.portal-shell.chair-portal .top-header { position:fixed; top:16px; right:var(--chair-shell-gutter); left:calc(var(--chair-sidebar-width) + var(--chair-shell-gutter)); z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    body.portal-shell.chair-portal .header-title small { color:#9a8450; font-size:11px; }
    body.portal-shell.chair-portal .header-title h3 { color:#173b33; font-size:20px; }
    body.portal-shell.chair-portal .header-action { color:#375448; }
    body.portal-shell.chair-portal .sidebar-toggle { top:36px; left:calc(var(--chair-sidebar-width) - 15px); z-index:1200; border-color:rgba(216,193,140,.52); background:#f8f5eb; color:#224336; }
    body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout { grid-template-columns:76px minmax(0,1fr); }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { width:76px; padding-right:10px; padding-left:10px; }
    body.portal-shell.chair-portal.sidebar-collapsed .sidebar-toggle { left:61px; }
    body.portal-shell.chair-portal .main-layout > .content.chair-requests-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }
    .chair-requests-view { color:#293d31; font-family:var(--wr-body,'DM Sans',Arial,sans-serif); }
    .chair-requests-page { width:min(100%,1320px); margin:0 auto; }
    .chair-requests-hero { position:relative; display:flex; min-height:190px; align-items:center; justify-content:space-between; gap:22px; overflow:hidden; margin:0 0 22px; padding:clamp(28px,4vw,42px); border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); color:#fff; }
    .chair-requests-hero::after { position:absolute; right:-48px; bottom:-165px; width:330px; height:330px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .chair-requests-hero-copy { position:relative; z-index:1; max-width:820px; }
    .chair-requests-eyebrow { display:flex; align-items:center; gap:8px; margin:0 0 11px; color:#e2c987; font-size:11px; font-weight:850; letter-spacing:.15em; text-transform:uppercase; }
    .chair-requests-hero h1 { margin:0; color:#fff; font:850 clamp(32px,3.2vw,44px)/1.12 var(--wr-display,'Manrope',sans-serif); letter-spacing:-.045em; }
    .chair-requests-hero p { max-width:740px; margin:10px 0 0; color:#e1e8df; font-size:15px; font-weight:550; line-height:1.65; }
    .chair-requests-hero-icon { position:absolute; top:24px; right:30px; z-index:0; display:grid; width:118px; height:118px; place-items:center; color:rgba(232,215,174,.62); opacity:.2; font-size:82px; pointer-events:none; }
    .chair-requests-alert { margin:0 0 15px; padding:13px 16px; border:1px solid #d8e6d8; border-radius:12px; background:#f0f7ef; color:#31583c; font-size:14px; font-weight:750; }
    .chair-requests-alert.error { border-color:#efd2cd; background:#fff5f3; color:#873f39; }
    .chair-requests-view .chair-panel { overflow:hidden; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 10px 28px rgba(31,57,43,.055); }
    .chair-requests-view .chair-panel-header { display:flex; align-items:center; justify-content:space-between; gap:18px; padding:22px 24px; border-bottom:1px solid #e7eee5; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .chair-requests-view .chair-panel-header h2 { margin:0; color:#173b33; font-size:21px; font-weight:850; }
    .chair-requests-view .chair-panel-header p { margin:6px 0 0; color:#5c6d62; font-size:14px; line-height:1.55; }
    .chair-requests-view .chair-panel-kicker { display:block; margin-bottom:6px; color:#917b4a; font-size:10px; font-weight:850; letter-spacing:.14em; }
    .chair-requests-view .chair-panel-icon { display:grid; flex:0 0 42px; width:42px; height:42px; place-items:center; border:1px solid #dfe8dd; border-radius:13px; background:#edf3eb; color:#285940; font-size:17px; }
    .chair-requests-view .chair-tabs { display:flex; flex-wrap:wrap; gap:8px; padding:13px 22px 0; border-bottom:1px solid #e9eee7; }
    .chair-requests-view .chair-tab { display:inline-flex; min-height:45px; align-items:center; gap:8px; padding:0 14px; border:0; border-bottom:3px solid transparent; background:transparent; color:#68796e; font:800 13px var(--wr-body,'DM Sans',Arial,sans-serif); cursor:pointer; }
    .chair-requests-view .chair-tab.active { border-bottom-color:#285940; color:#24583d; }
    .chair-requests-view .chair-tab:focus-visible { outline:3px solid rgba(72,129,83,.25); outline-offset:-3px; }
    .chair-requests-view .chair-tab-count { margin-left:2px; padding:3px 8px; border-radius:999px; background:#edf3eb; color:#285940; font-size:11px; font-weight:850; }
    .chair-requests-view .chair-tab-count.muted { background:#f0f2ef; color:#718077; }
    .chair-requests-view .chair-tab-panel { padding:19px 22px 22px; }
    .chair-requests-view .chair-request-list,.chair-requests-view .chair-swap-list { display:grid; gap:12px; }
    .chair-requests-view .chair-request-card,.chair-requests-view .chair-swap-row { min-width:0; margin:0; padding:17px; border:1px solid #e1e9df; border-radius:14px; background:#fff; box-shadow:0 4px 12px rgba(31,57,43,.025); }
    .chair-requests-view .chair-request-card.sent.approved { border-color:#d4e5d7; }
    .chair-requests-view .chair-request-card.sent.rejected { border-color:#ead7d3; }
    .chair-requests-view .chair-request-heading { display:flex; align-items:flex-start; gap:12px; }
    .chair-requests-view .chair-request-icon,.chair-requests-view .chair-swap-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; border:1px solid #dfe8dd; border-radius:12px; background:#edf3eb; color:#285940; font-size:16px; line-height:1; }
    .chair-requests-view .chair-request-title { min-width:0; flex:1 1 auto; }
    .chair-requests-view .chair-request-title h3 { margin:0; color:#203d30; font-size:16px; font-weight:850; line-height:1.4; overflow-wrap:anywhere; }
    .chair-requests-view .chair-request-title p { margin:4px 0 0; color:#5d6e63; font-size:13px; font-weight:600; line-height:1.5; }
    .chair-requests-view .chair-status { flex:0 0 auto; padding:5px 10px; border-radius:999px; background:#f2f3ed; color:#68746d; font-size:11px; font-weight:850; }
    .chair-requests-view .chair-status.pending { background:#fbf3df; color:#896e37; }
    .chair-requests-view .chair-status.approved { background:#eaf4e9; color:#316844; }
    .chair-requests-view .chair-status.rejected { background:#fbefed; color:#954b42; }
    .chair-requests-view .chair-request-details { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin:16px 0; padding:14px; border:1px solid #edf1eb; border-radius:11px; background:#fafbf9; }
    .chair-requests-view .chair-request-details div { display:grid; min-width:0; gap:4px; }
    .chair-requests-view .chair-request-details span { color:#69796f; font-size:11px; font-weight:700; }
    .chair-requests-view .chair-request-details strong { color:#2f483a; font-size:13px; font-weight:800; line-height:1.4; overflow-wrap:anywhere; }
    .chair-requests-view .chair-request-actions { display:flex; flex-wrap:wrap; align-items:center; gap:9px; }
    .chair-requests-view .chair-request-actions form { display:flex; flex-wrap:wrap; align-items:center; gap:8px; }
    .chair-requests-view .chair-request-actions button { display:inline-flex; min-height:40px; align-items:center; justify-content:center; gap:7px; padding:0 13px; border:1px solid #dfe7df; border-radius:10px; background:#f8faf7; color:#355d4d; font:800 12px var(--wr-body,'DM Sans',Arial,sans-serif); cursor:pointer; }
    .chair-requests-view .chair-request-actions .chair-approve { border-color:#285940; background:#285940; color:#fff; }
    .chair-requests-view .chair-request-actions .chair-decline { border-color:#ead8d4; background:#fff7f5; color:#984940; }
    .chair-requests-view .chair-merge-note-input { width:min(280px,100%); min-height:40px; padding:0 11px; border:1px solid #dfe7df; border-radius:9px; color:#394d40; font:600 13px var(--wr-body,'DM Sans',Arial,sans-serif); }
    .chair-requests-view .chair-approved-note { display:inline-flex; align-items:center; gap:7px; color:#32684c; font-size:13px; font-weight:800; }
    .chair-requests-view .chair-merge-decision-note { margin:12px 0; padding:11px 13px; border-left:3px solid #c6a355; border-radius:0 9px 9px 0; background:#fbf7ea; color:#665834; font-size:13px; line-height:1.55; }
    .chair-requests-view .chair-sent-merge-requests { margin-top:20px; padding-top:19px; border-top:1px solid #e4ebe2; }
    .chair-requests-view .chair-merge-section-title { margin:0 0 12px; color:#294437; font-size:16px; font-weight:850; }
    .chair-requests-view .chair-empty-state { display:grid; justify-items:center; padding:42px 20px; border:1px dashed #dce5da; border-radius:13px; background:#fafbf9; text-align:center; }
    .chair-requests-view .chair-empty-state > span { display:grid; width:48px; height:48px; place-items:center; margin-bottom:12px; border-radius:15px; background:#edf3eb; color:#285940; font-size:20px; }
    .chair-requests-view .chair-empty-state h3 { margin:0; color:#294437; font-size:16px; font-weight:850; }
    .chair-requests-view .chair-empty-state p { max-width:500px; margin:5px 0 0; color:#647268; font-size:13px; line-height:1.5; }
    .chair-requests-view .chair-swap-row { display:flex; align-items:flex-start; gap:13px; }
    .chair-requests-view .chair-swap-icon { flex-basis:40px; }
    .chair-requests-view .chair-swap-content { min-width:0; flex:1; }
    .chair-requests-view .chair-swap-topline { display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .chair-requests-view .chair-swap-topline strong { color:#294437; font-size:15px; font-weight:850; }
    .chair-requests-view .chair-swap-content > p { margin:7px 0; color:#2c4939; font-size:14px; font-weight:750; }
    .chair-requests-view .chair-swap-meta { display:flex; flex-wrap:wrap; gap:12px; color:#5d6d62; font-size:12px; font-weight:650; }
    .chair-requests-view .chair-swap-meta span { display:inline-flex; align-items:center; gap:6px; }
    .chair-requests-view .chair-swap-reason { margin-top:10px; color:#5f7065; font-size:13px; line-height:1.55; }
    .chair-requests-view [hidden] { display:none !important; }
    @media(max-width:1100px) {
        body.portal-shell.chair-portal { --chair-sidebar-width:226px; --chair-shell-gutter:22px; }
        body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
        body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); }
        body.portal-shell.chair-portal .main-layout > .content.chair-requests-view { padding-right:24px; padding-left:24px; }
    }
    @media(max-width:760px) {
        body.portal-shell.chair-portal .top-header { position:sticky; top:0; right:auto; left:auto; min-height:64px; margin:0; padding:9px 12px 9px 54px; border:0; border-radius:0 0 15px 15px; box-shadow:0 5px 18px rgba(31,57,43,.08); }
        body.portal-shell.chair-portal .header-title h3 { font-size:17px; }
        body.portal-shell.chair-portal .main-layout { display:block; }
        body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:fixed; top:0; bottom:0; left:0; z-index:1100; width:262px; height:100dvh; min-height:0; padding:20px 15px; transform:translateX(0); }
        body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { width:262px; transform:translateX(-110%); }
        body.portal-shell.chair-portal .sidebar-backdrop { position:fixed; inset:0; z-index:1050; }
        body.portal-shell.chair-portal .sidebar-toggle,body.portal-shell.chair-portal.sidebar-collapsed .sidebar-toggle { top:17px; left:14px; z-index:1200; }
        body.portal-shell.chair-portal:not(.sidebar-collapsed) .sidebar-toggle { top:17px; left:270px; }
        body.portal-shell.chair-portal .main-layout > .content.chair-requests-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .chair-requests-hero { min-height:170px; align-items:flex-start; padding:27px 23px; }
        .chair-requests-view .chair-panel-header { padding:18px; }
        .chair-requests-view .chair-tabs { padding-right:14px; padding-left:14px; }
        .chair-requests-view .chair-tab-panel { padding:14px; }
        .chair-requests-view .chair-request-details { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:540px) {
        .chair-requests-hero-icon { top:18px; right:15px; width:76px; height:76px; font-size:55px; opacity:.13; }
        .chair-requests-view .chair-panel-header { align-items:flex-start; }
        .chair-requests-view .chair-request-heading { flex-wrap:wrap; }
        .chair-requests-view .chair-request-details { grid-template-columns:minmax(0,1fr); }
        .chair-requests-view .chair-request-actions form { width:100%; }
        .chair-requests-view .chair-merge-note-input { flex:1 1 100%; }
        .chair-requests-view .chair-swap-row { gap:10px; padding:13px; }
        .chair-requests-view .chair-swap-icon { flex-basis:35px; width:35px; height:35px; }
        .chair-requests-view .chair-swap-topline { align-items:flex-start; flex-direction:column; }
    }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include('chair.sidebar')
        <main class="content chair-requests-view">
            <div class="chair-requests-page">
                <header class="chair-requests-hero">
                    <div class="chair-requests-hero-copy">
                        <span class="chair-requests-eyebrow"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> COLLEGE WORKSPACE</span>
                        <h1>Requests &amp; Activity</h1>
                        <p>Review course merge requests, track requests sent by your college, and follow instructor room swap activity.</p>
                    </div>
                    <span class="chair-requests-hero-icon" aria-hidden="true"><i class="fa-solid fa-inbox"></i></span>
                </header>
                @if(session('success'))<div class="chair-requests-alert" role="status">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="chair-requests-alert error" role="alert">Please review the form and try again.</div>@endif
                @include('chair.partials.request-activity-card')
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = Array.from(document.querySelectorAll('.chair-requests-view .chair-tab'));
    const panels = Array.from(document.querySelectorAll('.chair-requests-view .chair-tab-panel'));
    function activateTab(tab) {
        tabs.forEach(item => {
            const active = item === tab;
            item.classList.toggle('active', active);
            item.setAttribute('aria-selected', active ? 'true' : 'false');
            item.tabIndex = active ? 0 : -1;
        });
        panels.forEach(panel => { panel.hidden = panel.id !== tab.dataset.tab; });
    }
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => activateTab(tab));
        tab.addEventListener('keydown', event => {
            if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
            event.preventDefault();
            const direction = event.key === 'ArrowRight' ? 1 : -1;
            const next = tabs[(index + direction + tabs.length) % tabs.length];
            activateTab(next);
            next.focus();
        });
    });
});
</script>

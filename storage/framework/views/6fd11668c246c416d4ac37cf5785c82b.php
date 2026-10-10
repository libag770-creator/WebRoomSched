<?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="wrapper">

    <div class="main-layout">

        <?php echo $__env->make('faculty.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="content faculty-room-swap-view">

            <style>
/* WebRoomSched — Room Swap Request | complete visual refresh */
/* Step 2 confirmation and Step 3 modal workflow */
.swap-modal-overlay { position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; padding:20px; background:rgba(15,32,22,.58); backdrop-filter:blur(3px); overflow-y:auto; }
.swap-modal-card { width:min(100%,680px); max-height:min(90vh,900px); overflow-y:auto; margin:auto; border:1px solid #dce7df; border-radius:20px; background:#fff; box-shadow:0 24px 80px rgba(0,0,0,.24); animation:swapModalIn .18s ease-out; }
.swap-confirm-card { width:min(100%,520px); padding:26px; border:1px solid #dce7df; border-radius:20px; background:#fff; box-shadow:0 24px 80px rgba(0,0,0,.24); animation:swapModalIn .18s ease-out; }
.swap-modal-icon { display:grid; place-items:center; width:48px; height:48px; margin-bottom:15px; border-radius:15px; background:#edf7f0; color:#176b3a; font-size:21px; }
.swap-modal-heading { margin:0 0 8px; color:#172b21; font-size:21px; font-weight:850; }
.swap-modal-copy { margin:0 0 18px; color:#66756d; font-size:13px; line-height:1.65; }
.swap-modal-summary { display:grid; gap:9px; margin:0 0 22px; padding:14px; border:1px solid #e1eae3; border-radius:12px; background:#f8fbf8; }
.swap-modal-summary-row { display:flex; justify-content:space-between; gap:16px; font-size:12px; line-height:1.5; }
.swap-modal-summary-row span { color:#66756d; }
.swap-modal-summary-row strong { color:#172b21; text-align:right; overflow-wrap:anywhere; }
.swap-modal-actions { display:flex; justify-content:flex-end; flex-wrap:wrap; gap:10px; }
.swap-modal-actions button { min-height:42px; padding:10px 16px; border:1px solid #dce7df; border-radius:10px; background:#fff; color:#34483a; font:inherit; font-size:13px; font-weight:800; cursor:pointer; }
.swap-modal-actions .swap-modal-primary { border-color:#176b3a; background:#176b3a; color:#fff; }
.swap-modal-actions .swap-modal-primary:hover { background:#10552d; }
@keyframes swapModalIn { from { opacity:0; transform:translateY(8px) scale(.99); } to { opacity:1; transform:translateY(0) scale(1); } }
@media(max-width:560px) { .swap-modal-overlay { padding:10px; align-items:flex-start; } .swap-modal-card { max-height:calc(100vh - 20px); } .swap-confirm-card { margin:auto; padding:20px; } .swap-modal-actions { flex-direction:column-reverse; } .swap-modal-actions button { width:100%; } }
.swap-container, .swap-container * { box-sizing: border-box; }
.active-academic-term-banner { display:flex; align-items:center; gap:12px; margin:18px 0 22px; padding:14px 16px; border:1px solid #b9ddc3; border-radius:13px; background:linear-gradient(110deg,#edf8f0 0%,#fbfefb 78%); box-shadow:0 3px 10px rgba(23,107,58,.045); }
.active-academic-term-icon { display:grid; place-items:center; flex:0 0 40px; width:40px; height:40px; border-radius:11px; background:#176b3a; color:#fff; font-size:16px; }
.active-academic-term-copy { display:flex; flex:1; min-width:0; flex-direction:column; gap:3px; }
.active-academic-term-label { color:#52705c; font-size:10px; font-weight:800; letter-spacing:.7px; text-transform:uppercase; }
.active-academic-term-copy strong { color:#174b2b; font-size:14px; line-height:1.5; overflow-wrap:anywhere; }
.active-academic-term-separator { padding:0 4px; color:#83a58d; }
.active-academic-term-status { display:inline-flex; align-items:center; gap:6px; flex-shrink:0; padding:6px 9px; border:1px solid #c5e4ce; border-radius:999px; background:#fff; color:#176b3a; font-size:10px; font-weight:800; }
.active-academic-term-status i { font-size:7px; }
@media(max-width:560px) { .active-academic-term-banner { align-items:flex-start; padding:12px; gap:10px; } .active-academic-term-icon { flex-basis:35px; width:35px; height:35px; } .active-academic-term-copy strong { font-size:12px; } .active-academic-term-status { padding:5px 7px; font-size:9px; } }
.swap-container {
    --swap-green: #176b3a; --swap-green-dark: #10552d; --swap-green-soft: #edf7f0;
    --swap-ink: #172b21; --swap-muted: #66756d; --swap-line: #dce7df;
    width: min(100%, 1440px); margin: 0 auto; padding: clamp(16px, 2.4vw, 32px);
    color: var(--swap-ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}
.swap-title { display:flex; align-items:center; gap:12px; margin:0 0 7px; color:var(--swap-ink); font-size:clamp(25px, 2.4vw, 34px); line-height:1.2; font-weight:800; letter-spacing:-.7px; }
.swap-title::before { content:"⇄"; display:grid; place-items:center; flex:0 0 46px; width:46px; height:46px; border-radius:14px; background:var(--swap-green-soft); color:var(--swap-green); font-size:27px; }
.swap-subtitle { max-width:760px; margin:0 0 26px 58px; color:var(--swap-muted); font-size:14px; line-height:1.65; }
.alert-success,.alert-error { display:flex; gap:10px; align-items:flex-start; margin:0 0 18px; padding:14px 16px; border:1px solid; border-radius:12px; font-size:13px; line-height:1.55; font-weight:600; }
.alert-success { color:#17643a; background:#effaf2; border-color:#b8e0c3; }
.alert-success::before { content:"✓"; font-weight:900; }
.alert-error { color:#a12622; background:#fff3f2; border-color:#f1c1bd; }
.alert-error::before { content:"!"; display:grid; place-items:center; flex:0 0 19px; width:19px; height:19px; border:1px solid currentColor; border-radius:50%; font-size:12px; }
#swapForm { display:grid; gap:20px; }
.step { position:relative; min-width:0; padding:clamp(18px, 2vw, 28px); border:1px solid var(--swap-line); border-radius:18px; background:#fff; box-shadow:0 5px 22px rgba(26,65,42,.045); }
.step-title { display:flex; align-items:center; gap:12px; margin:0 0 7px; color:var(--swap-ink); font-size:19px; line-height:1.35; font-weight:800; }
.step-title::before { display:grid; place-items:center; flex:0 0 34px; width:34px; height:34px; border-radius:10px; background:var(--swap-green); color:#fff; font-size:14px; font-weight:800; }
.step:nth-of-type(1) .step-title::before { content:"1"; }
.step:nth-of-type(2) .step-title::before { content:"2"; }
.step:nth-of-type(3) .step-title::before { content:"3"; }
.step-description { margin:0 0 20px 46px; color:var(--swap-muted); font-size:13px; line-height:1.65; }
.swap-search-box { margin:0 0 20px; padding:15px; border:1px solid #e0e9e2; border-radius:13px; background:#f8fbf8; }
.swap-search-label,.swap-navigation-title { display:flex; align-items:center; gap:9px; margin:0 0 10px; color:#234a32; font-size:13px; font-weight:800; }
.swap-search-label i,.swap-navigation-title i { color:var(--swap-green); }
.swap-search-row { position:relative; display:flex; align-items:center; }
.swap-search-input { width:100%; min-width:0; height:46px; padding:0 44px 0 14px; border:1px solid #cddbd0; border-radius:10px; outline:none; background:#fff; color:var(--swap-ink); font:inherit; font-size:13px; transition:border-color .18s,box-shadow .18s; }
.swap-search-input::placeholder { color:#94a39a; }
.swap-search-input:focus { border-color:var(--swap-green); box-shadow:0 0 0 3px rgba(23,107,58,.12); }
.swap-search-clear { position:absolute; right:7px; display:grid; place-items:center; width:32px; height:32px; border:0; border-radius:8px; background:transparent; color:#66756d; cursor:pointer; }
.swap-search-clear:hover { background:#e7f1e9; color:var(--swap-green); }
.swap-search-help { margin-top:8px; color:var(--swap-muted); font-size:11px; line-height:1.55; }
.swap-navigation-title { margin-top:20px; margin-bottom:12px; font-size:14px; }
/* Clean room selection cards for both Step 1 and Step 2 */
.swap-navigation-list,.swap-room-grid,.swap-room-search-results { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,245px),1fr)); gap:14px; align-items:stretch; }
.swap-room-card { position:relative; display:flex; flex-direction:column; align-items:stretch; min-width:0; min-height:220px; padding:17px; border:1px solid #dfe8e1; border-radius:15px; background:#fff; color:var(--swap-ink); box-shadow:0 2px 7px rgba(20,55,32,.035); transition:transform .18s ease,border-color .18s ease,box-shadow .18s ease,background .18s ease; }
.swap-room-card:hover { transform:translateY(-2px); border-color:#83b995; background:#fcfefc; box-shadow:0 8px 20px rgba(23,107,58,.09); }
.swap-room-card .swap-room-header { align-items:center; gap:12px; margin-bottom:14px; }
.swap-room-card .swap-room-icon { flex:0 0 46px; width:46px; height:46px; border:1px solid #d9eadf; border-radius:13px; background:#edf7f0; font-size:18px; }
.swap-room-card .swap-room-name { font-size:16px; line-height:1.4; overflow-wrap:anywhere; }
.swap-room-card .swap-room-building { font-size:12px; line-height:1.5; }
.swap-room-card .swap-room-information { grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; margin:0 0 13px; }
.swap-room-card .swap-room-info-item { min-width:0; padding:10px 11px; border-color:#e5ece7; border-radius:10px; background:#f8fbf8; }
.swap-room-card .swap-room-info-label { font-size:9px; letter-spacing:.45px; }
.swap-room-card .swap-room-info-value { font-size:12px; line-height:1.45; }
.swap-room-card .swap-view-room-button { display:flex; align-items:center; justify-content:center; gap:8px; width:100%; min-height:39px; margin-top:auto; padding:10px 12px; border:1px solid #cfe2d3; border-radius:9px; background:#f2f8f3; color:var(--swap-green); font-size:12px; font-weight:800; cursor:pointer; transition:background .16s ease,border-color .16s ease; }
.swap-room-card .swap-view-room-button:hover { border-color:#83b995; background:#e5f3e8; }
.swap-room-card .my-room-selected-note { margin:0 0 12px; padding:9px 11px; border-radius:9px; font-size:11px; }

.auto-matching-department-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 0 0 16px;
}
.auto-matching-department-filter {
    border: 1px solid #d5dfd8;
    border-radius: 999px;
    padding: 8px 14px;
    background: #fff;
    color: #33463a;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s ease, color .15s ease, border-color .15s ease;
}
.auto-matching-department-filter:hover,
.auto-matching-department-filter.active {
    border-color: #26734d;
    background: #26734d;
    color: #fff;
}


.auto-swap-match-day-time { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; margin:2px 0 10px; }
.auto-swap-match-day,.auto-swap-match-time { display:flex; align-items:center; gap:8px; min-width:0; padding:8px 9px; border:1px solid #eadcdc; border-radius:9px; background:#fff7f7; }
.auto-swap-match-day > i,.auto-swap-match-time > i { flex:0 0 15px; color:#b32626; font-size:12px; }
.auto-swap-match-day div,.auto-swap-match-time div { display:flex; flex-direction:column; min-width:0; gap:2px; }
.auto-swap-match-day span,.auto-swap-match-time span { color:#8b6f6f; font-size:8px; font-weight:800; text-transform:uppercase; letter-spacing:.35px; }
.auto-swap-match-day strong,.auto-swap-match-time strong { color:#542b2b; font-size:10px; line-height:1.35; overflow-wrap:anywhere; }
@media(max-width:520px){ .auto-swap-match-day-time { grid-template-columns:1fr; } }
.auto-swap-match-card { display:flex; flex-direction:column; gap:10px; min-width:0; padding:15px; border:1px solid #d8c8f2; border-radius:13px; background:#fbf8ff; box-shadow:0 2px 7px rgba(89,54,145,.05); }
.auto-swap-match-card strong { color:#38245b; font-size:13px; line-height:1.45; overflow-wrap:anywhere; }
.auto-swap-match-card .auto-swap-match-meta { display:grid; gap:5px; color:#67567f; font-size:11px; line-height:1.5; }
.auto-swap-match-card .auto-swap-match-time { display:inline-flex; align-self:flex-start; padding:5px 8px; border-radius:7px; background:#efe6ff; color:#6539a6; font-size:10px; font-weight:800; }
.auto-swap-match-card button { min-height:38px; margin-top:auto; padding:9px 12px; border:1px solid #c8b2ed; border-radius:9px; background:#6d42a6; color:#fff; font-size:12px; font-weight:800; cursor:pointer; }
.auto-swap-match-card button:hover { background:#58318d; }
@media (max-width:520px) { .swap-room-grid { grid-template-columns:1fr; } .swap-room-card { min-height:0; } }
.swap-navigation-card { display:flex; align-items:center; gap:13px; min-width:0; padding:15px; border:1px solid var(--swap-line); border-radius:13px; background:#fff; color:inherit; text-align:left; cursor:pointer; transition:transform .16s,border-color .16s,box-shadow .16s,background .16s; }
.swap-navigation-card:hover,.swap-navigation-card:focus-visible { transform:translateY(-2px); border-color:#83b995; background:#fbfefb; box-shadow:0 7px 18px rgba(23,107,58,.09); outline:none; }
.swap-navigation-left { display:flex; align-items:center; gap:12px; min-width:0; flex:1; }
.swap-navigation-icon,.swap-room-icon,.swap-search-room-icon { display:grid; place-items:center; flex:0 0 42px; width:42px; height:42px; border-radius:12px; background:var(--swap-green-soft); color:var(--swap-green); font-size:17px; }
.swap-navigation-name { display:block; overflow-wrap:anywhere; color:var(--swap-ink); font-size:13px; font-weight:800; line-height:1.45; }
.swap-navigation-description { display:block; margin-top:3px; color:var(--swap-muted); font-size:11px; line-height:1.5; }
.swap-navigation-arrow { color:#83958a; font-size:12px; }
.swap-breadcrumb { display:flex; flex-wrap:wrap; align-items:center; gap:9px; margin:2px 0 16px; color:#9aa79f; font-size:12px; }
.swap-breadcrumb button { display:inline-flex; align-items:center; gap:7px; padding:7px 10px; border:1px solid var(--swap-line); border-radius:8px; background:#fff; color:var(--swap-green); font:inherit; font-weight:700; cursor:pointer; }
.swap-breadcrumb button:hover { background:var(--swap-green-soft); }
.swap-breadcrumb strong { color:var(--swap-ink); font-size:12px; }
.swap-room-header { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
.swap-room-name { color:var(--swap-ink); font-size:17px; font-weight:800; }
.swap-room-building { margin-top:3px; color:var(--swap-muted); font-size:12px; }
.swap-room-information { display:grid; grid-template-columns:repeat(auto-fit,minmax(135px,1fr)); gap:10px; margin:0 0 18px; }
.swap-room-info-item { padding:12px 13px; border:1px solid #e2ebe4; border-radius:11px; background:#f9fcf9; }
.swap-room-info-label { display:block; margin-bottom:5px; color:var(--swap-muted); font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
.swap-room-info-value { display:block; color:var(--swap-ink); font-size:13px; font-weight:800; overflow-wrap:anywhere; }
.swap-room-info-value.available { color:#176b3a; }
.swap-room-search-results { margin:0 0 18px; }
.swap-search-room-button { display:flex; align-items:center; gap:12px; width:100%; min-width:0; padding:13px; border:1px solid var(--swap-line); border-radius:12px; background:#fff; text-align:left; cursor:pointer; transition:.16s ease; }
.swap-search-room-button:hover { border-color:#83b995; box-shadow:0 5px 14px rgba(23,107,58,.08); }
.swap-search-room-details { flex:1; min-width:0; }
.swap-search-room-details strong { display:block; color:var(--swap-ink); font-size:13px; line-height:1.45; }
.swap-search-room-details small { display:block; margin-top:4px; color:var(--swap-muted); font-size:11px; line-height:1.5; }
.swap-view-room-button { flex-shrink:0; padding:8px 10px; border:1px solid #cfe2d3; border-radius:8px; background:var(--swap-green-soft); color:var(--swap-green); font-size:11px; font-weight:800; }
.swap-selected-room-note,.my-room-selected-note { margin-top:12px; padding:11px 13px; border:1px solid #b8dfc2; border-radius:10px; background:#eff9f1; color:#17643a; font-size:12px; font-weight:700; line-height:1.55; }
.selected-swap-box,.selected-target-schedule { margin-top:16px; padding:15px; border:1px solid #b8dfc2; border-radius:12px; background:#eff9f1; color:#205b35; font-size:12px; line-height:1.6; }
.selected-swap-box:empty,.selected-target-schedule:empty { display:none; }
.selected-requester-detail { margin-top:0; padding:clamp(16px,2.5vw,28px); border-color:#cfe2d3; background:linear-gradient(135deg,#f7fcf8 0%,#fff 72%); color:var(--swap-ink); }
.selected-requester-header { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; margin-bottom:20px; }
.selected-requester-eyebrow { display:inline-flex; align-items:center; gap:7px; color:var(--swap-green); font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.7px; }
.selected-requester-header h3 { margin:8px 0 5px; color:var(--swap-ink); font-size:clamp(18px,2vw,24px); line-height:1.35; overflow-wrap:anywhere; }
.selected-requester-header p { margin:0; color:var(--swap-muted); font-size:12px; line-height:1.6; }
.selected-requester-back { display:inline-flex; align-items:center; justify-content:center; gap:7px; flex:0 0 auto; min-height:38px; padding:8px 12px; border:1px solid #d5e2d8; border-radius:9px; background:#fff; color:#355443; font:inherit; font-size:12px; font-weight:800; cursor:pointer; }
.selected-requester-back:hover { border-color:#83b995; background:var(--swap-green-soft); color:var(--swap-green); }
.selected-requester-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,190px),1fr)); gap:11px; }
.selected-requester-item { min-width:0; padding:13px 14px; border:1px solid #e0eae2; border-radius:11px; background:#fff; }
.selected-requester-item span { display:block; margin-bottom:5px; color:var(--swap-muted); font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.55px; }
.selected-requester-item strong { display:block; color:var(--swap-ink); font-size:13px; line-height:1.5; overflow-wrap:anywhere; }
@media (max-width:520px) { .selected-requester-header { flex-direction:column; } .selected-requester-back { align-self:flex-end; } }
/* Clean selectable schedule cards for Step 1 */
.my-room-schedule-list { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,265px),1fr)); gap:14px; align-items:stretch; }
.my-room-schedule-card { position:relative; display:flex; flex-direction:column; min-width:0; min-height:175px; padding:17px; border:1px solid #dfe8e1; border-radius:15px; background:#fff; color:var(--swap-ink); text-align:left; cursor:pointer; box-shadow:0 2px 7px rgba(20,55,32,.035); transition:border-color .18s ease,box-shadow .18s ease,transform .18s ease,background .18s ease; }
.my-room-schedule-card::after { content:"Select schedule  →"; display:flex; align-items:center; justify-content:center; align-self:flex-start; margin-top:auto; padding:7px 10px; border:1px solid #cfe2d3; border-radius:8px; background:#f5faf6; color:var(--swap-green); font-size:11px; font-weight:800; }
.my-room-schedule-card:hover,.my-room-schedule-card:focus-visible { transform:translateY(-2px); border-color:#83b995; box-shadow:0 8px 20px rgba(23,107,58,.09); outline:none; }
.my-room-schedule-card.selected { border-color:var(--swap-green); background:#f5fbf6; box-shadow:0 0 0 2px rgba(23,107,58,.10); }
.my-room-schedule-card.selected::after { content:"✓ Selected"; border-color:#b8dfc2; background:#eaf7ed; }
.swap-status-badge { display:inline-flex; align-items:center; gap:6px; width:max-content; max-width:100%; margin:0 0 11px; padding:6px 9px; border:1px solid transparent; border-radius:8px; font-size:10px; font-weight:850; letter-spacing:.2px; line-height:1.3; }
.swap-status-badge.pending { border-color:#f2c078; background:#fff4df; color:#9a5700; }
.swap-status-badge.cancelled { color:#5f6963; background:#eef1ef; }
                .swap-status-badge.expired { border-color:#cfd6d1; background:#f1f3f2; color:#59655e; }
                .swap-status-badge.approved { border-color:#e6a0a0; background:#fff0f0; color:#b32626; }
.my-room-schedule-card.swap-pending,.auto-swap-match-card.swap-pending { border-color:#eab15a; background:#fffaf0; }
.my-room-schedule-card.swap-approved,.auto-swap-match-card.swap-approved { border-color:#df8e8e; background:#fff6f6; }
.my-room-schedule-card.swap-locked { cursor:not-allowed; }
.my-room-schedule-card.swap-locked::after { content:"Schedule already in a swap"; border-color:#e8d7b8; background:#fff4df; color:#8c560a; }
.my-room-schedule-card.swap-approved::after { content:"● Room Swap Active"; border-color:#e6a0a0; background:#fff0f0; color:#b32626; }
.my-room-schedule-card.swap-pending::after { content:"◷ Pending Room Swap"; border-color:#f2c078; background:#fff4df; color:#9a5700; }
.auto-swap-match-card button:disabled { opacity:.7; cursor:not-allowed; background:#8b8b8b; border-color:#8b8b8b; }
.my-room-schedule-card .my-room-schedule-top { align-items:center; margin-bottom:12px; }
.my-room-schedule-card .my-room-schedule-course { padding:5px 8px; border-radius:7px; background:#edf7f0; color:#176b3a; font-size:11px; letter-spacing:.2px; }
.my-room-schedule-card .my-room-schedule-day { padding:5px 8px; border-radius:7px; background:#f4f6f4; color:#596b5f; font-size:10px; }
.my-room-schedule-card .my-room-schedule-subject { margin-bottom:12px; font-size:15px; line-height:1.4; }
.my-room-schedule-card .my-room-schedule-details { gap:8px; margin-bottom:15px; font-size:12px; }
.my-room-schedule-card .my-room-schedule-details span { display:flex; align-items:flex-start; gap:7px; }
.my-room-schedule-card .my-room-schedule-details i { flex:0 0 15px; margin-top:2px; }
@media (max-width:520px) { .my-room-schedule-list { grid-template-columns:1fr; } .my-room-schedule-card { min-height:0; } }
.my-room-schedule-top { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:10px; }
.my-room-schedule-day { color:var(--swap-green); font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.45px; }
.my-room-schedule-course { display:inline-flex; padding:4px 7px; border-radius:6px; background:#eef3ef; color:#43574a; font-size:10px; font-weight:800; }
.my-room-schedule-subject { margin:0 0 8px; color:var(--swap-ink); font-size:14px; font-weight:800; line-height:1.5; }
.my-room-schedule-details { display:grid; gap:6px; color:var(--swap-muted); font-size:11px; line-height:1.5; }
.my-room-schedule-details i { width:15px; color:var(--swap-green); }
.swap-schedule-filter { display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin:0 0 14px; padding:11px 13px; border:1px solid #cfe2d3; border-radius:10px; background:var(--swap-green-soft); color:#255c37; font-size:12px; }
.swap-schedule-filter strong { font-weight:800; }
.swap-excel-wrapper { width:100%; overflow:auto; border:1px solid var(--swap-line); border-radius:12px; background:#fff; }
.swap-excel-table { width:100%; min-width:680px; border-collapse:separate; border-spacing:0; font-size:11px; }
.swap-excel-table th { position:sticky; top:0; padding:11px 9px; border-right:1px solid rgba(255,255,255,.13); background:#1d5834; color:#fff; text-align:center; font-size:10px; font-weight:800; letter-spacing:.4px; }
.swap-excel-table td { min-width:94px; height:66px; padding:7px; border-right:1px solid #e5ece7; border-bottom:1px solid #e5ece7; background:#fff; vertical-align:top; }
.swap-excel-table tr:nth-child(even) td { background:#fbfdfb; }
.swap-excel-time { color:#43574a; font-weight:800; white-space:nowrap; }
.swap-excel-course { display:block; color:#176b3a; font-weight:800; line-height:1.4; }
.swap-excel-subject { display:block; margin-top:3px; color:#304338; line-height:1.4; }
.swap-excel-instructor { display:block; margin-top:4px; color:#7a887f; font-size:10px; line-height:1.4; }
.swap-excel-program,.swap-merge-program { display:inline-block; margin-top:4px; padding:2px 5px; border-radius:5px; background:#eaf4ed; color:#2c6740; font-size:9px; font-weight:800; }
.swap-merge-badge { display:inline-block; margin:3px 0 0 3px; padding:2px 5px; border-radius:5px; background:#fff2d6; color:#825a0b; font-size:9px; font-weight:800; }
.swap-excel-occupied { display:block; width:100%; min-height:50px; padding:7px; border:1px solid #b8d9c1; border-radius:7px; background:#eff8f1; color:#1a5d31; text-align:left; cursor:pointer; }
.swap-excel-occupied:hover { border-color:#176b3a; background:#e0f2e5; }
.swap-excel-occupied.disabled-swap { border-color:#e5e9e6; background:#f3f5f3; color:#7c8980; cursor:not-allowed; }
.swap-excel-empty { color:#a2ada5; text-align:center; }
.swap-noon-break { background:#f5f7f5 !important; color:#78857c; text-align:center; font-size:10px; font-weight:700; }
.swap-schedule-instruction { display:flex; align-items:flex-start; gap:9px; margin-top:14px; padding:12px 14px; border:1px solid #d8e5f3; border-radius:10px; background:#f3f8fd; color:#36536f; font-size:12px; line-height:1.6; }
.swap-schedule-instruction i { margin-top:2px; }
.empty-swap-message { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:7px; min-height:125px; padding:24px; border:1px dashed #ccd9cf; border-radius:12px; background:#fafcfa; color:var(--swap-muted); text-align:center; }
.empty-swap-message i { color:#71927b; font-size:24px; }
.empty-swap-message strong { color:var(--swap-ink); font-size:13px; }
.empty-swap-message span { max-width:400px; font-size:12px; line-height:1.6; }
.reason-options { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,185px),1fr)); gap:10px; }
.reason-option { display:flex; align-items:center; min-height:54px; padding:12px 14px; border:1px solid var(--swap-line); border-radius:11px; background:#fff; color:#34483a; font-size:13px; font-weight:700; cursor:pointer; transition:.16s ease; }
.reason-option::before { content:""; flex:0 0 16px; width:16px; height:16px; margin-right:10px; border:1.5px solid #b8c8bc; border-radius:50%; background:#fff; }
.reason-option:hover { border-color:#83b995; background:#fbfefb; }
.reason-option.selected,.reason-option.active { border-color:var(--swap-green); background:var(--swap-green-soft); color:var(--swap-green); box-shadow:0 0 0 1px var(--swap-green) inset; }
.reason-option.selected::before,.reason-option.active::before { border:5px solid var(--swap-green); }
#otherReason { width:100%; min-height:100px; margin-top:12px; padding:12px 14px; border:1px solid #cddbd0; border-radius:10px; outline:none; resize:vertical; font:inherit; font-size:13px; line-height:1.6; }
#otherReason:focus { border-color:var(--swap-green); box-shadow:0 0 0 3px rgba(23,107,58,.1); }
.buttons { display:flex; flex-wrap:wrap; align-items:center; justify-content:flex-end; gap:10px; margin-top:24px; padding-top:18px; border-top:1px solid #e8eee9; }
.btn { display:inline-flex; align-items:center; justify-content:center; gap:9px; min-height:44px; padding:0 18px; border:1px solid transparent; border-radius:10px; font:inherit; font-size:13px; font-weight:800; cursor:pointer; transition:transform .15s,background .15s,box-shadow .15s; }
.btn:focus-visible { outline:3px solid rgba(23,107,58,.2); outline-offset:2px; }
.btn-reset { border-color:#d6dfd8; background:#fff; color:#526157; }
.btn-reset:hover { background:#f4f7f4; }
.btn-send { background:var(--swap-green); color:#fff; box-shadow:0 4px 10px rgba(23,107,58,.16); }
.btn-send:hover { transform:translateY(-1px); background:var(--swap-green-dark); box-shadow:0 6px 15px rgba(23,107,58,.22); }
.btn-send:disabled { opacity:.55; cursor:not-allowed; transform:none; }
@media (max-width: 760px) {
    .swap-container { padding:14px; }
    .swap-title { align-items:flex-start; font-size:25px; }
    .swap-title::before { flex-basis:40px; width:40px; height:40px; }
    .swap-subtitle { margin-left:0; font-size:13px; }
    .step { padding:17px 14px; border-radius:14px; }
    .step-title { align-items:flex-start; font-size:17px; }
    .step-description { margin-left:0; }
    .swap-search-box { padding:12px; }
    .swap-navigation-list,.swap-room-grid,.swap-room-search-results,.my-room-schedule-list { grid-template-columns:1fr; }
    .swap-room-information { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .reason-options { grid-template-columns:1fr; }
    .buttons { flex-direction:column-reverse; align-items:stretch; }
    .btn { width:100%; }
}
@media (prefers-reduced-motion: reduce) { .swap-container * { transition:none !important; scroll-behavior:auto !important; } }

/* Match the faculty workspace visual system while preserving the existing swap flow. */
.faculty-room-swap-view { background: #f5f8f6; }
.swap-container { width: min(100%, 1320px); padding: 12px 0 42px; --swap-green:#19764d; --swap-green-dark:#145f3d; --swap-green-soft:#edf6ef; --swap-ink:#193e2d; --swap-muted:#708078; --swap-line:#e2ebe5; }
.swap-hero { position:relative; margin-bottom:19px; padding:25px 28px; overflow:hidden; border:1px solid #dfeae2; border-radius:20px; background:linear-gradient(115deg,#fff 0%,#f0f8f2 100%); box-shadow:0 8px 24px rgba(22,73,45,.045); }
.swap-hero::after { position:absolute; top:-62px; right:-30px; width:210px; height:210px; border:1px solid rgba(28,117,74,.10); border-radius:50%; box-shadow:0 0 0 24px rgba(28,117,74,.035),0 0 0 48px rgba(28,117,74,.025); content:""; }
.swap-eyebrow { position:relative; z-index:1; margin:0 0 9px 58px; color:#56816a; font-size:9px; font-weight:800; letter-spacing:.14em; }
.swap-title { position:relative; z-index:1; margin-bottom:6px; color:#193e2d; font-size:27px; font-weight:760; letter-spacing:-.035em; }
.swap-title::before { content:"⇄"; background:#eaf4ed; color:#19764d; }
.swap-subtitle { position:relative; z-index:1; margin:0 0 0 58px; color:#708078; font-size:12px; line-height:1.65; }
.active-academic-term-banner { margin:0 0 20px; border-color:#dce9df; border-radius:14px; background:linear-gradient(110deg,#edf7ef,#fff 85%); box-shadow:none; }
.active-academic-term-icon { background:#19764d; }
.active-academic-term-label { color:#708078; }
.active-academic-term-copy strong { color:#244b35; font-size:13px; }
.active-academic-term-status { border-color:#dce9df; color:#19764d; }
#swapForm { gap:15px; }
#swapForm > .step { padding:23px; border-color:#e2ebe5; border-radius:16px; box-shadow:0 5px 17px rgba(25,57,39,.04); }
.step-title { color:#244b35; font-size:17px; }
.step-title::before { background:#19764d; box-shadow:0 4px 9px rgba(25,118,77,.14); }
.step-description { color:#76847b; font-size:12px; }
.swap-search-box { border-color:#e3ebe5; border-radius:12px; background:#f8fbf9; }
.swap-search-label,.swap-navigation-title { color:#315b41; }
.swap-search-input { border-color:#dce6df; border-radius:11px; }
.swap-search-input:focus { border-color:#278257; box-shadow:0 0 0 4px rgba(39,130,87,.11); }
.swap-search-clear:hover { background:#eaf4ed; color:#19764d; }
.swap-navigation-card,.swap-room-card { border-color:#e3ebe5; border-radius:13px; box-shadow:0 3px 10px rgba(25,57,39,.035); }
.swap-navigation-card:hover,.swap-navigation-card:focus-visible,.swap-room-card:hover { border-color:#b9d7c4; box-shadow:0 9px 20px rgba(25,80,49,.075); }
.swap-navigation-icon,.swap-room-icon,.swap-search-room-icon { background:#edf6ef; color:#19764d; }
.swap-navigation-name,.swap-room-name { color:#244b35; }
.swap-breadcrumb button { border-color:#e0e9e3; border-radius:9px; color:#19764d; }
.swap-breadcrumb button:hover { background:#f1f8f3; }
.swap-room-card .swap-view-room-button { border-color:#d7e7db; background:#f2f8f3; color:#19764d; }
.swap-room-card .swap-view-room-button:hover { border-color:#b9d7c4; background:#eaf4ed; }
.auto-matching-department-filter.active,.auto-matching-department-filter:hover { border-color:#19764d; background:#19764d; }
.btn-send,.swap-modal-actions .swap-modal-primary { background:#19764d; }
.btn-send:hover,.swap-modal-actions .swap-modal-primary:hover { background:#145f3d; }
@media(max-width:760px) {
    .swap-container { padding:4px 0 28px; }
    .swap-hero { margin-bottom:15px; padding:21px 19px; border-radius:16px; }
    .swap-hero::after { right:-115px; }
    .swap-eyebrow { margin-left:0; }
    .swap-title { font-size:23px; }
    .swap-subtitle { margin-left:0; }
    #swapForm > .step { padding:18px 15px; }
}
</style>


            <div class="swap-container">

                <div class="swap-hero">
                    <p class="swap-eyebrow">FACULTY WORKSPACE / ROOM COORDINATION</p>
                    <h1 class="swap-title">Room Swap Request</h1>
                    <p class="swap-subtitle">
                        Exchange your scheduled room with another faculty member. Both schedules must have the same day and time.
                    </p>
                </div>

                <?php
                    /* Display the system-wide active academic term on this page.
                       The indicator is read-only and does not activate/change a term. */
                    $roomSwapActiveTerm = $activeTerm ?? null;

                    if (!$roomSwapActiveTerm) {
                        try {
                            $roomSwapActiveTerm = \App\Models\AcademicTerm::query()
                                ->where('is_active', true)
                                ->orderByDesc('id')
                                ->first();
                        } catch (\Throwable $e) {
                            $roomSwapActiveTerm = null;
                        }
                    }
                ?>

                <div class="active-academic-term-banner" role="status" aria-live="polite">
                    <span class="active-academic-term-icon"><i class="fa-solid fa-calendar-check"></i></span>
                    <span class="active-academic-term-copy">
                        <span class="active-academic-term-label">Active Academic Term</span>
                        <?php if($roomSwapActiveTerm): ?>
                            <strong>
                                <?php echo e($roomSwapActiveTerm->academic_year ?? 'Academic year not set'); ?>

                                <span class="active-academic-term-separator">·</span>
                                <?php echo e($roomSwapActiveTerm->semester ?? 'Semester not set'); ?>

                            </strong>
                        <?php else: ?>
                            <strong>No active academic term set</strong>
                        <?php endif; ?>
                    </span>
                    <span class="active-academic-term-status"><i class="fa-solid fa-circle"></i> Active</span>
                </div>


                
                <?php if(session('success')): ?>

                    <div class="alert-success">
                        <?php echo e(session('success')); ?>

                    </div>

                <?php endif; ?>


                
                <?php if(session('error')): ?>

                    <div class="alert-error">
                        <?php echo e(session('error')); ?>

                    </div>

                <?php endif; ?>


                
                <?php if($errors->any()): ?>

                    <div class="alert-error">

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div>
                                <?php echo e($error); ?>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                <?php endif; ?>


                <form
                    action="<?php echo e(route('faculty.room.swap.store')); ?>"
                    method="POST"
                    id="swapForm"
                >

                    <?php echo csrf_field(); ?>


                    

                    <div class="step">

                        <div class="step-title">
                            Step 1 — Select Your Schedule
                        </div>

                        <div class="step-description">
                            Select your building, room, and then one of your scheduled classes.
                        </div>

                        <div id="step1BrowsePanel">
                        <div class="swap-search-box">
                            <div class="swap-search-label">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search My Buildings or Rooms
                            </div>

                            <div class="swap-search-row">
                                <input
                                    type="text"
                                    id="myScheduleSearch"
                                    class="swap-search-input"
                                    placeholder="Search building, room, course code, or subject..."
                                    oninput="filterMySwapRooms()"
                                >

                                <button
                                    type="button"
                                    class="swap-search-clear"
                                    onclick="clearMyScheduleSearch()"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <div class="swap-search-help">
                                First select a building, then a room. Only your scheduled classes in that room will be shown.
                            </div>
                        </div>

                        
                        <div id="mySwapScheduleSearchResults" class="my-room-schedule-list" style="display:none;" aria-live="polite"></div>

                        
                        <div id="mySwapBuildings">
                            <div class="swap-navigation-title">
                                <i class="fa-solid fa-building"></i>
                                My Buildings
                            </div>

                            <div
                                id="mySwapBuildingList"
                                class="swap-navigation-list"
                            ></div>
                        </div>

                        
                        <div
                            id="mySwapRooms"
                            style="display:none;"
                        >
                            <div class="swap-breadcrumb">
                                <button
                                    type="button"
                                    onclick="backToMySwapBuildings()"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    My Buildings
                                </button>
                                <span>/</span>
                                <strong id="mySwapBuildingName">
                                    Building
                                </strong>
                            </div>

                            <div class="swap-navigation-title">
                                <i class="fa-solid fa-door-open"></i>
                                My Rooms
                            </div>

                            <div
                                id="mySwapRoomList"
                                class="swap-room-grid"
                            ></div>
                        </div>

                        
                        <div
                            id="mySwapRoomSchedule"
                            style="display:none;"
                        >
                            <div class="swap-breadcrumb">
                                <button
                                    type="button"
                                    onclick="backToMySwapRooms()"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    My Rooms
                                </button>
                                <span>/</span>
                                <strong id="mySwapRoomName">
                                    Room
                                </strong>
                            </div>

                            <div
                                id="mySwapRoomInformation"
                                class="swap-room-information"
                            ></div>

                            <div class="swap-navigation-title">
                                <i class="fa-solid fa-calendar-days"></i>
                                My Scheduled Classes in This Room
                            </div>

                            <div
                                id="mySwapScheduleList"
                                class="my-room-schedule-list"
                            ></div>
                        </div>

                        </div> 

                        <div
                            id="selectedRequesterSchedule"
                            class="selected-swap-box selected-requester-detail"
                            style="display:none;"
                        ></div>

                    </div>


                    

                    <div class="step">

                        <div class="step-title">
                            Step 2 — Select Another Faculty Schedule
                        </div>

                        <div class="step-description">
                            Matching schedules from other faculty are loaded automatically
                            based on the same day and time you selected in Step 1.
                            Review the faculty and room details below, then select a matching schedule to continue.
                        </div>


                        
                        <div id="autoMatchingSchedulesPanel" style="display:none; margin-bottom:20px;">
                            <div class="swap-navigation-title">
                                <i class="fa-solid fa-calendar-check"></i>
                                Other Faculty Schedules With the Same Day and Time
                            </div>
                            <div class="swap-search-help" style="margin:0 0 12px;">
                                All schedules with the same day and time are loaded automatically. Choose a department below to narrow the results, or select All Departments to view every match.
                            </div>
                            <div id="autoMatchingDepartmentFilters" class="auto-matching-department-filters" role="group" aria-label="Filter matching schedules by department"></div>
                            <div id="autoMatchingSchedules" class="swap-room-search-results"></div>
                        </div>

                    </div>


                    
                    <div id="targetScheduleConfirmModal" class="swap-modal-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="targetConfirmTitle">
                        <div class="swap-confirm-card">
                            <div class="swap-modal-icon"><i class="fa-solid fa-right-left"></i></div>
                            <h2 id="targetConfirmTitle" class="swap-modal-heading">Confirm Schedule Selection</h2>
                            <p class="swap-modal-copy">Do you want to select this faculty schedule for your room swap? Confirming will open Step 3 so you can choose the reason for the swap.</p>
                            <div id="targetConfirmSummary" class="swap-modal-summary"></div>
                            <div class="swap-modal-actions">
                                <button type="button" onclick="cancelTargetScheduleConfirmation()">Cancel</button>
                                <button type="button" class="swap-modal-primary" onclick="confirmPendingTargetSchedule()"><i class="fa-solid fa-check"></i> Confirm Schedule</button>
                            </div>
                        </div>
                    </div>

                    

                    <div id="step3Modal" class="swap-modal-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="step3ModalTitle">
                    <div class="step swap-modal-card" style="position:relative;">
                        <button type="button" aria-label="Close Step 3" onclick="closeStep3Modal()" style="position:sticky;top:0;float:right;z-index:2;display:grid;place-items:center;width:36px;height:36px;margin:-8px -8px 4px 8px;border:1px solid #dce7df;border-radius:10px;background:#fff;color:#52645a;cursor:pointer;" title="Close"><i class="fa-solid fa-xmark"></i></button>
                        <div class="step-title" id="step3ModalTitle">
                            Step 3 — Reason for Room Swap
                        </div>

                        <div class="step-description">
                            Select a reason for your room swap request.
                        </div>


                        <div class="reason-options">

                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Need computers',
                                    this
                                )"
                            >
                                Need computers
                            </div>


                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Need projector/TV',
                                    this
                                )"
                            >
                                Need projector/TV
                            </div>


                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Need 40+ room capacity',
                                    this
                                )"
                            >
                                Need 40+ room capacity
                            </div>


                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Other',
                                    this
                                )"
                            >
                                Other
                            </div>

                        </div>


                        <textarea
                            id="otherReason"
                            placeholder="Enter other reason..."
                        ></textarea>


                        

                        <input
                            type="hidden"
                            name="requester_type"
                            value="schedule"
                        >

                        <input
                            type="hidden"
                            name="target_type"
                            value="schedule"
                        >

                        <input
                            type="hidden"
                            name="requester_schedule_id"
                            id="requester_schedule_id"
                        >

                        <input
                            type="hidden"
                            name="requester_room_id"
                            id="requester_room_id"
                        >

                        <input
                            type="hidden"
                            name="target_schedule_id"
                            id="target_schedule_id"
                        >

                        <input
                            type="hidden"
                            name="target_room_id"
                            id="target_room_id"
                        >

                        <input
                            type="hidden"
                            name="target_user_id"
                            id="target_user_id"
                        >

                        <input
                            type="hidden"
                            name="swap_date"
                            id="swap_date"
                        >

                        <input
                            type="hidden"
                            name="start_time"
                            id="start_time"
                        >

                        <input
                            type="hidden"
                            name="end_time"
                            id="end_time"
                        >

                        <input
                            type="hidden"
                            name="reason"
                            id="reason"
                        >


                        

                        <div class="buttons">

                            <button
                                type="button"
                                class="btn btn-reset"
                                onclick="resetSwap()"
                            >
                                Reset
                            </button>


                            <button
                                type="submit"
                                class="btn btn-send"
                            >
                                <i class="fa-solid fa-paper-plane"></i>

                                Send Room Swap Request

                            </button>

                        </div>

                    </div>
                    </div> 

                </form>

            </div>


            <script>

                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                */

                const swapDepartmentData =
    <?php echo json_encode($departments, 15, 512) ?>;

const swapTargetScheduleData =
    <?php echo json_encode($targetSchedules, 15, 512) ?>;

                /*
                |--------------------------------------------------------------------------
                | PREPARE MERGED PROGRAM DATA
                |--------------------------------------------------------------------------
                |
                | Every approved merge attached to a schedule is converted to
                | merged_programs so Step 2 can display:
                |
                | BSIT / EDUC
                |
                | without changing the original schedule program.
                |
                */

                swapDepartmentData.forEach(
                    function(department)
                    {
                        (
                            department.buildings ||
                            []
                        ).forEach(
                            function(building)
                            {
                                (
                                    building.rooms ||
                                    []
                                ).forEach(
                                    function(room)
                                    {
                                        (
                                            room.schedules ||
                                            []
                                        ).forEach(
                                            function(schedule)
                                            {
                                                const programs = [];

                                                if (
                                                    schedule.program
                                                ) {
                                                    programs.push(
                                                        String(
                                                            schedule.program
                                                        ).trim()
                                                    );
                                                }

                                                (
                                                    schedule.merges ||
                                                    []
                                                ).forEach(
                                                    function(merge)
                                                    {
                                                        if (
                                                            String(
                                                                merge.status ||
                                                                ''
                                                            ).toLowerCase() !==
                                                            'approved'
                                                        ) {
                                                            return;
                                                        }

                                                        const mergeProgram =
                                                            merge.faculty_subject
                                                                ?.program ||
                                                            merge.facultySubject
                                                                ?.program ||
                                                            '';

                                                        if (
                                                            String(
                                                                mergeProgram
                                                            ).trim() &&
                                                            !programs.includes(
                                                                String(
                                                                    mergeProgram
                                                                ).trim()
                                                            )
                                                        ) {
                                                            programs.push(
                                                                String(
                                                                    mergeProgram
                                                                ).trim()
                                                            );
                                                        }
                                                    }
                                                );

                                                schedule.merged_programs =
                                                    programs
                                                        .filter(Boolean)
                                                        .filter(
                                                            function(value, index, array)
                                                            {
                                                                return array.indexOf(value) === index;
                                                            }
                                                        );
                                            }
                                        );
                                    }
                                );
                            }
                        );
                    }
                );


                let selectedRequesterSchedule =
                    null;

                let selectedSwapDepartment =
                    null;

                let selectedSwapBuilding =
                    null;

                let selectedSwapRoom =
                    null;

                let selectedTargetSchedule =
                    null;

                let pendingTargetScheduleId = null;


                /*
                |--------------------------------------------------------------------------
                | ESCAPE HTML
                |--------------------------------------------------------------------------
                */

                function escapeSwapHtml(value)
                {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function swapStatusBadge(schedule)
                {
                    if (schedule?.swap_status === 'pending' || schedule?.is_swap_pending) {
                        const partner = schedule.swap_partner_faculty ? ` · ${escapeSwapHtml(schedule.swap_partner_faculty)}` : '';
                        return `<div class="swap-status-badge pending"><i class="fa-regular fa-clock"></i> Pending Room Swap${partner}</div>`;
                    }
                    if (schedule?.swap_status === 'approved' || schedule?.is_swapped) {
                        const partner = schedule.swap_partner_faculty ? ` · ${escapeSwapHtml(schedule.swap_partner_faculty)}` : '';
                        return `<div class="swap-status-badge approved"><i class="fa-solid fa-circle"></i> Room Swap Active${partner}</div>`;
                    }
                    if (schedule?.swap_status === 'expired') {
                        return `<div class="swap-status-badge expired"><i class="fa-solid fa-rotate-left"></i> Swap Completed</div>`;
                    }
                    if (schedule?.swap_status === 'cancelled') {
                        return `<div class="swap-status-badge cancelled"><i class="fa-solid fa-ban"></i> Room Swap Cancelled</div>`;
                    }
                    return '';
                }

                function scheduleHasActiveSwap(schedule)
                {
                    return schedule?.swap_status === 'pending' || schedule?.swap_status === 'approved' ||
                        Boolean(schedule?.is_swap_pending) || Boolean(schedule?.is_swapped);
                }


                /*
                |--------------------------------------------------------------------------
                | STEP 1 DATA
                |--------------------------------------------------------------------------
                */

                const mySwapScheduleData =
                    <?php echo json_encode($mySchedules, 15, 512) ?>;

                let selectedMySwapBuilding = null;
                let selectedMySwapRoom = null;


                /*
                |--------------------------------------------------------------------------
                | BUILD MY BUILDINGS
                |--------------------------------------------------------------------------
                |
                | Only buildings that contain schedules belonging to the
                | currently logged-in faculty are shown.
                */

                function getMySwapBuildings()
                {
                    const buildingMap = {};

                    mySwapScheduleData.forEach(
                        function(schedule)
                        {
                            if (!schedule.room) {
                                return;
                            }

                            const building =
                                schedule.room.building || {};

                            const buildingId =
                                building.id ||
                                schedule.room.building_id;

                            if (!buildingId) {
                                return;
                            }

                            if (!buildingMap[buildingId]) {
                                buildingMap[buildingId] = {
                                    id: Number(buildingId),
                                    name: building.name || 'Building',
                                    rooms: {}
                                };
                            }

                            const roomId =
                                schedule.room.id ||
                                schedule.room_id;

                            if (!roomId) {
                                return;
                            }

                            if (!buildingMap[buildingId].rooms[roomId]) {
                                buildingMap[buildingId].rooms[roomId] = {
                                    id: Number(roomId),
                                    room_name:
                                        schedule.room.room_name || 'Room',
                                    department:
                                        schedule.room.department || {},
                                    schedules: []
                                };
                            }

                            buildingMap[buildingId]
                                .rooms[roomId]
                                .schedules
                                .push(schedule);
                        }
                    );

                    return Object.values(buildingMap).map(
                        function(building)
                        {
                            building.rooms =
                                Object.values(building.rooms);

                            return building;
                        }
                    );
                }


                function renderMySwapBuildings()
                {
                    const list =
                        document.getElementById(
                            'mySwapBuildingList'
                        );

                    if (!list) {
                        return;
                    }

                    list.innerHTML = '';

                    const buildings =
                        getMySwapBuildings();

                    if (!buildings.length) {
                        list.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-building-circle-xmark"></i>
                                <strong>No scheduled buildings found</strong>
                                <span>
                                    You currently have no scheduled classes assigned to a room.
                                </span>
                            </div>
                        `;
                        return;
                    }

                    buildings.forEach(
                        function(building)
                        {
                            const card =
                                document.createElement('button');

                            card.type = 'button';
                            card.className =
                                'swap-navigation-card my-swap-building-card';

                            // Include building, room, and schedule details so Step 1
                            // can find a match while the user types each character.
                            card.dataset.search = (
                                String(building.name || '') + ' ' +
                                (building.rooms || []).map(function(room) {
                                    const scheduleText = (room.schedules || []).map(function(schedule) {
                                        return [
                                            schedule.course_code,
                                            schedule.subject,
                                            schedule.day,
                                            schedule.time,
                                            schedule.instructor,
                                            schedule.program,
                                            schedule.major,
                                            schedule.year_level
                                        ].filter(Boolean).join(' ');
                                    }).join(' ');

                                    return String(room.room_name || '') + ' ' + scheduleText;
                                }).join(' ')
                            ).toLowerCase();

                            card.onclick =
                                function()
                                {
                                    openMySwapBuilding(
                                        building.id
                                    );
                                };

                            card.innerHTML = `
                                <div class="swap-navigation-left">
                                    <div class="swap-navigation-icon">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="swap-navigation-name">
                                            ${escapeSwapHtml(building.name)}
                                        </div>
                                        <div class="swap-navigation-description">
                                            ${building.rooms.length}
                                            ${building.rooms.length === 1 ? 'room' : 'rooms'}
                                            with your schedule
                                        </div>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right swap-navigation-arrow"></i>
                            `;

                            list.appendChild(card);
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | OPEN MY BUILDING
                |--------------------------------------------------------------------------
                */

                function openMySwapBuilding(buildingId)
                {
                    const buildings =
                        getMySwapBuildings();

                    selectedMySwapBuilding =
                        buildings.find(
                            function(building)
                            {
                                return Number(building.id) ===
                                    Number(buildingId);
                            }
                        );

                    if (!selectedMySwapBuilding) {
                        return;
                    }

                    document.getElementById(
                        'mySwapBuildingName'
                    ).textContent =
                        selectedMySwapBuilding.name;

                    const roomList =
                        document.getElementById(
                            'mySwapRoomList'
                        );

                    roomList.innerHTML = '';

                    const rooms =
                        selectedMySwapBuilding.rooms || [];

                    if (!rooms.length) {
                        roomList.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-door-closed"></i>
                                <strong>No scheduled rooms found</strong>
                                <span>
                                    You have no scheduled classes in this building.
                                </span>
                            </div>
                        `;
                    } else {
                        rooms.forEach(
                            function(room)
                            {
                                const card =
                                    document.createElement('div');

                                card.className =
                                    'swap-room-card my-swap-room-card';

                                card.dataset.search = [
                                    room.room_name,
                                    selectedMySwapBuilding.name,
                                    ...(room.schedules || []).flatMap(function(schedule) {
                                        return [
                                            schedule.course_code,
                                            schedule.subject,
                                            schedule.day,
                                            schedule.time,
                                            schedule.instructor,
                                            schedule.program,
                                            schedule.major,
                                            schedule.year_level
                                        ];
                                    })
                                ].filter(Boolean).join(' ').toLowerCase();

                                card.innerHTML = `
                                    <div class="swap-room-header">
                                        <div class="swap-room-icon">
                                            <i class="fa-solid fa-door-open"></i>
                                        </div>
                                        <div>
                                            <div class="swap-room-name">
                                                ${escapeSwapHtml(room.room_name)}
                                            </div>
                                            <div class="swap-room-building">
                                                ${escapeSwapHtml(selectedMySwapBuilding.name)}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="swap-room-information">
                                        ${buildRoomInfo(room)}
                                    </div>

                                    <div class="my-room-selected-note">
                                        <i class="fa-solid fa-calendar-check"></i>
                                        ${room.schedules.length}
                                        ${room.schedules.length === 1 ? 'scheduled class' : 'scheduled classes'}
                                    </div>

                                    <button
                                        type="button"
                                        class="swap-view-room-button"
                                        onclick="openMySwapRoom(${room.id})"
                                    >
                                        <i class="fa-solid fa-calendar-days"></i>
                                        View My Classes
                                    </button>
                                `;

                                roomList.appendChild(card);
                            }
                        );
                    }

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'block';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'none';

                    // Re-apply the current search after the room list is rendered.
                    filterMySwapRooms();
                }


                /*
                |--------------------------------------------------------------------------
                | OPEN MY ROOM
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | This does NOT build an Excel timetable.
                | It displays only the schedules belonging to the
                | logged-in faculty in this selected room.
                */

                function openMySwapRoom(roomId)
                {
                    if (!selectedMySwapBuilding) {
                        return;
                    }

                    selectedMySwapRoom =
                        selectedMySwapBuilding.rooms.find(
                            function(room)
                            {
                                return Number(room.id) ===
                                    Number(roomId);
                            }
                        );

                    if (!selectedMySwapRoom) {
                        return;
                    }

                    document.getElementById(
                        'mySwapRoomName'
                    ).textContent =
                        selectedMySwapRoom.room_name;

                    document.getElementById(
                        'mySwapRoomInformation'
                    ).innerHTML =
                        buildRoomInfo(selectedMySwapRoom);

                    renderMyRoomSchedules(
                        selectedMySwapRoom.schedules || []
                    );

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'block';

                    // Apply Step 1 search to the schedules shown in this room.
                    filterMySwapRooms();
                }


                /*
                |--------------------------------------------------------------------------
                | RENDER ONLY FACULTY SCHEDULES
                |--------------------------------------------------------------------------
                */

                function renderMyRoomSchedules(schedules)
                {
                    const list =
                        document.getElementById(
                            'mySwapScheduleList'
                        );

                    list.innerHTML = '';

                    if (!schedules.length) {
                        list.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <strong>No scheduled classes</strong>
                                <span>
                                    You do not have a scheduled class in this room.
                                </span>
                            </div>
                        `;
                        return;
                    }

                    schedules
                        .slice()
                        .sort(
                            function(a, b)
                            {
                                return Number(a.row_position ?? 9999) -
                                    Number(b.row_position ?? 9999);
                            }
                        )
                        .forEach(
                            function(schedule)
                            {
                                const card =
                                    document.createElement('div');

                                card.className = 'my-room-schedule-card' + (schedule.swap_status === 'pending' ? ' swap-pending swap-locked' : (schedule.swap_status === 'approved' ? ' swap-approved swap-locked' : ''));

                                card.dataset.scheduleId =
                                    schedule.id;
                                card.setAttribute('role', 'button');
                                card.setAttribute('tabindex', '0');
                                card.setAttribute('aria-label', 'Select schedule ' + (schedule.course_code || schedule.course || 'class') + ' for ' + (schedule.day || 'scheduled day'));

                                card.onkeydown = function(event) {
                                    if (event.key === 'Enter' || event.key === ' ') {
                                        event.preventDefault();
                                        selectMyRoomSchedule(schedule.id, card);
                                    }
                                };

                                card.onclick =
                                    function()
                                    {
                                        selectMyRoomSchedule(
                                            schedule.id,
                                            card
                                        );
                                    };

                                const programs = [];

                                if (schedule.program) {
                                    programs.push(
                                        String(schedule.program).trim()
                                    );
                                }

                                (schedule.merged_programs || [])
                                    .forEach(
                                        function(program)
                                        {
                                            const value =
                                                String(program || '').trim();

                                            if (
                                                value &&
                                                !programs.includes(value)
                                            ) {
                                                programs.push(value);
                                            }
                                        }
                                    );

                                card.innerHTML = `
                                    ${swapStatusBadge(schedule)}
                                    <div class="my-room-schedule-top">
                                        <div class="my-room-schedule-course">
                                            ${escapeSwapHtml(schedule.course_code || '-')}
                                        </div>
                                        <div class="my-room-schedule-day">
                                            ${escapeSwapHtml(schedule.day || '')}
                                            •
                                            ${escapeSwapHtml(schedule.time || '')}
                                        </div>
                                    </div>

                                    <div class="my-room-schedule-subject">
                                        ${escapeSwapHtml(schedule.subject || '-')}
                                    </div>

                                    <div class="my-room-schedule-details">
                                        <div>
                                            <i class="fa-solid fa-user"></i>
                                            <strong>Faculty:</strong>
                                            ${escapeSwapHtml(schedule.instructor || 'Unknown Faculty')}
                                        </div>

                                        ${
                                            programs.length
                                                ? `
                                                    <div>
                                                        <i class="fa-solid fa-graduation-cap"></i>
                                                        <strong>Program:</strong>
                                                        ${escapeSwapHtml(programs.join(' / '))}
                                                    </div>
                                                `
                                                : ''
                                        }

                                        ${
                                            schedule.year_level
                                                ? `
                                                    <div>
                                                        <i class="fa-solid fa-layer-group"></i>
                                                        <strong>Year:</strong>
                                                        ${escapeSwapHtml(schedule.year_level)}
                                                    </div>
                                                `
                                                : ''
                                        }

                                        ${
                                            schedule.major
                                                ? `
                                                    <div>
                                                        <i class="fa-solid fa-bookmark"></i>
                                                        <strong>Major:</strong>
                                                        ${escapeSwapHtml(schedule.major)}
                                                    </div>
                                                `
                                                : ''
                                        }
                                    </div>
                                `;

                                list.appendChild(card);
                            }
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | SELECT MY ROOM SCHEDULE
                |--------------------------------------------------------------------------
                */

                function selectMyRoomSchedule(
                    scheduleId,
                    element
                )
                {
                    const scheduleStatusRecord = (mySwapScheduleData || []).find(function(item) {
                        return Number(item.id) === Number(scheduleId);
                    });
                    if (scheduleHasActiveSwap(scheduleStatusRecord)) {
                        alert(scheduleStatusRecord.swap_status === 'approved'
                            ? 'This schedule has already been swapped and cannot be selected for another room swap.'
                            : 'This schedule already has a pending room swap request. Wait until it is accepted or declined.');
                        return;
                    }
                    const schedule =
                        (selectedMySwapRoom?.schedules || [])
                            .find(
                                function(item)
                                {
                                    return Number(item.id) ===
                                        Number(scheduleId);
                                }
                            );

                    if (!schedule) {
                        return;
                    }

                    document
                        .querySelectorAll(
                            '.my-room-schedule-card'
                        )
                        .forEach(
                            function(card)
                            {
                                card.classList.remove('selected');
                            }
                        );

                    element.classList.add('selected');

                    const room =
                        selectedMySwapRoom;

                    const buildingName =
                        selectedMySwapBuilding?.name ||
                        'Building';

                    selectedRequesterSchedule = {
                        id: Number(schedule.id),
                        room_id: Number(schedule.room_id),
                        day: String(schedule.day || '')
                            .trim()
                            .toUpperCase(),
                        time: String(schedule.time || '')
                            .trim(),
                        course_code: schedule.course_code || '',
                        subject: schedule.subject || '',
                        room: room?.room_name || 'Room',
                        building: buildingName,
                        department:
                            room?.department?.name ||
                            'Department',
                        instructor: schedule.instructor || '',
                        faculty_id: schedule.faculty_id || schedule.user_id || schedule.teacher_id || null
                    };

                    document.getElementById(
                        'requester_schedule_id'
                    ).value = schedule.id;

                    document.getElementById(
                        'requester_room_id'
                    ).value = schedule.room_id;

                    document.getElementById(
                        'swap_date'
                    ).value =
                        new Date()
                            .toISOString()
                            .split('T')[0];

                    const timeParts =
                        String(schedule.time || '').split('-');

                    if (timeParts.length === 2) {
                        document.getElementById(
                            'start_time'
                        ).value = timeParts[0].trim();

                        document.getElementById(
                            'end_time'
                        ).value = timeParts[1].trim();
                    }

                    const selectedBox =
                        document.getElementById(
                            'selectedRequesterSchedule'
                        );

                    const programs = [];
                    if (schedule.program) {
                        programs.push(String(schedule.program).trim());
                    }
                    (schedule.merged_programs || []).forEach(function(program) {
                        const value = String(program || '').trim();
                        if (value && !programs.includes(value)) {
                            programs.push(value);
                        }
                    });

                    selectedBox.innerHTML = `
                        <div class="selected-requester-header">
                            <div>
                                <span class="selected-requester-eyebrow">
                                    <i class="fa-solid fa-circle-check"></i> Schedule selected
                                </span>
                                <h3>${escapeSwapHtml(schedule.course_code || '-')} — ${escapeSwapHtml(schedule.subject || '-')}</h3>
                                <p>Your selected schedule will be used for the room swap request.</p>
                            </div>
                            <button type="button" class="selected-requester-back" onclick="backToStep1ScheduleSelection()" aria-label="Back to Step 1 schedule selection" title="Back to Step 1">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Back</span>
                            </button>
                        </div>
                        <div class="selected-requester-grid">
                            <div class="selected-requester-item"><span>Department</span><strong>${escapeSwapHtml(room?.department?.name || 'Department')}</strong></div>
                            <div class="selected-requester-item"><span>Building</span><strong>${escapeSwapHtml(buildingName)}</strong></div>
                            <div class="selected-requester-item"><span>Room</span><strong>${escapeSwapHtml(room?.room_name || 'Room')}</strong></div>
                            <div class="selected-requester-item"><span>Day</span><strong>${escapeSwapHtml(schedule.day || '-')}</strong></div>
                            <div class="selected-requester-item"><span>Time</span><strong>${escapeSwapHtml(schedule.time || '-')}</strong></div>
                            <div class="selected-requester-item"><span>Faculty</span><strong>${escapeSwapHtml(schedule.instructor || 'Unknown Faculty')}</strong></div>
                            ${programs.length ? `<div class="selected-requester-item"><span>Program</span><strong>${escapeSwapHtml(programs.join(' / '))}</strong></div>` : ''}
                            ${schedule.year_level ? `<div class="selected-requester-item"><span>Year level</span><strong>${escapeSwapHtml(schedule.year_level)}</strong></div>` : ''}
                            ${schedule.major ? `<div class="selected-requester-item"><span>Major</span><strong>${escapeSwapHtml(schedule.major)}</strong></div>` : ''}
                            ${room?.capacity !== undefined && room?.capacity !== null ? `<div class="selected-requester-item"><span>Room capacity</span><strong>${escapeSwapHtml(room.capacity)}</strong></div>` : ''}
                            ${room?.computers !== undefined && room?.computers !== null ? `<div class="selected-requester-item"><span>Computers</span><strong>${escapeSwapHtml(room.computers)}</strong></div>` : ''}
                        </div>
                    `;

                    const step1BrowsePanel =
    document.getElementById('step1BrowsePanel');

if (step1BrowsePanel) {
    step1BrowsePanel.style.display = 'none';
}

if (selectedBox) {
    selectedBox.style.display = 'block';
}

selectedTargetSchedule = null;

const requesterScheduleIdField =
    document.getElementById('requester_schedule_id');

if (requesterScheduleIdField) {
    requesterScheduleIdField.value = schedule.id;
}

const requesterRoomIdField =
    document.getElementById('requester_room_id');

if (requesterRoomIdField) {
    requesterRoomIdField.value = schedule.room_id;
}

const targetScheduleIdField =
    document.getElementById('target_schedule_id');

if (targetScheduleIdField) {
    targetScheduleIdField.value = '';
}

const targetRoomIdField =
    document.getElementById('target_room_id');

if (targetRoomIdField) {
    targetRoomIdField.value = '';
}

const targetUserIdField =
    document.getElementById('target_user_id');

if (targetUserIdField) {
    targetUserIdField.value = '';
}

const selectedTargetBox =
    document.getElementById('selectedTargetSchedule');

if (selectedTargetBox) {
    selectedTargetBox.style.display = 'none';
}

                    // Automatically populate Step 2 with other faculty schedules
                    // that match the selected schedule's exact day and time.
                    renderAutoMatchingTargetSchedules();
                }


                function backToStep1ScheduleSelection()
                {
                    const browsePanel = document.getElementById('step1BrowsePanel');
                    const selectedBox = document.getElementById('selectedRequesterSchedule');

                    if (browsePanel) browsePanel.style.display = 'block';
                    if (selectedBox) {
                        selectedBox.style.display = 'none';
                        selectedBox.innerHTML = '';
                    }

                    selectedRequesterSchedule = null;

                    const fieldsToClear = [
                        'requester_schedule_id',
                        'requester_room_id',
                        'swap_date',
                        'start_time',
                        'end_time'
                    ];
                    fieldsToClear.forEach(function(id) {
                        const field = document.getElementById(id);
                        if (field) field.value = '';
                    });

                    selectedTargetSchedule = null;
                    ['target_schedule_id', 'target_room_id', 'target_user_id'].forEach(function(id) {
                        const field = document.getElementById(id);
                        if (field) field.value = '';
                    });

                    const targetBox = document.getElementById('selectedTargetSchedule');
                    if (targetBox) {
                        targetBox.style.display = 'none';
                        targetBox.innerHTML = '';
                    }
                    const autoPanel = document.getElementById('autoMatchingSchedulesPanel');
                    const autoResults = document.getElementById('autoMatchingSchedules');
                    if (autoPanel) autoPanel.style.display = 'none';
                    if (autoResults) autoResults.innerHTML = '';
                }


                /*
                |--------------------------------------------------------------------------
                | SEARCH MY BUILDINGS / ROOMS
                |--------------------------------------------------------------------------
                */

                function filterMySwapRooms()
                {
                    const input = document.getElementById('myScheduleSearch');
                    const search = String(input?.value || '').trim().toLowerCase();
                    const searchResults = document.getElementById('mySwapScheduleSearchResults');
                    const buildingsPanel = document.getElementById('mySwapBuildings');
                    const roomsPanel = document.getElementById('mySwapRooms');
                    const schedulesPanel = document.getElementById('mySwapRoomSchedule');

                    // While typing, show matching schedules directly instead of only
                    // showing the building that contains the schedule.
                    if (search) {
                        if (buildingsPanel) buildingsPanel.style.display = 'none';
                        if (roomsPanel) roomsPanel.style.display = 'none';
                        if (schedulesPanel) schedulesPanel.style.display = 'none';
                        if (searchResults) {
                            searchResults.style.display = 'grid';

                            const matches = (mySwapScheduleData || []).filter(function(schedule) {
                                const room = schedule.room || {};
                                const building = room.building || {};
                                const department = room.department || {};
                                const searchableText = [
                                    schedule.course_code,
                                    schedule.course,
                                    schedule.subject,
                                    schedule.day,
                                    schedule.time,
                                    schedule.instructor,
                                    schedule.program,
                                    schedule.major,
                                    schedule.year_level,
                                    room.room_name,
                                    building.name,
                                    department.name
                                ].filter(Boolean).join(' ').toLowerCase();

                                return searchableText.includes(search);
                            });

                            searchResults.innerHTML = '';

                            if (!matches.length) {
                                searchResults.innerHTML = `
                                    <div class="empty-swap-message" style="grid-column:1/-1;">
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                        <strong>No matching schedules found</strong>
                                        <span>Try a course code, subject, day, time, room, or building name.</span>
                                    </div>
                                `;
                            } else {
                                matches.forEach(function(schedule) {
                                    const roomData = schedule.room || {};
                                    const buildingData = roomData.building || {};
                                    const roomId = Number(roomData.id || schedule.room_id || 0);
                                    const buildingId = Number(buildingData.id || roomData.building_id || 0);
                                    const sameRoomSchedules = (mySwapScheduleData || []).filter(function(item) {
                                        return Number(item.room_id || item.room?.id || 0) === roomId;
                                    });

                                    const card = document.createElement('div');
                                    card.className = 'my-room-schedule-card' + (schedule.swap_status === 'pending' ? ' swap-pending swap-locked' : (schedule.swap_status === 'approved' ? ' swap-approved swap-locked' : ''));
                                    card.dataset.scheduleId = schedule.id;
                                    card.setAttribute('role', 'button');
                                    card.setAttribute('tabindex', '0');
                                    card.setAttribute('aria-label', 'Select schedule ' + (schedule.course_code || schedule.subject || 'class'));

                                    const selectThisSchedule = function() {
                                        const room = {
                                            ...roomData,
                                            id: roomId,
                                            room_name: roomData.room_name || 'Room',
                                            department: roomData.department || {},
                                            schedules: sameRoomSchedules
                                        };

                                        selectedMySwapBuilding = {
                                            id: buildingId,
                                            name: buildingData.name || 'Building',
                                            rooms: [room]
                                        };
                                        selectedMySwapRoom = room;
                                        selectMyRoomSchedule(schedule.id, card);
                                    };

                                    card.onclick = selectThisSchedule;
                                    card.onkeydown = function(event) {
                                        if (event.key === 'Enter' || event.key === ' ') {
                                            event.preventDefault();
                                            selectThisSchedule();
                                        }
                                    };

                                    const programs = [];
                                    if (schedule.program) programs.push(String(schedule.program).trim());
                                    (schedule.merged_programs || []).forEach(function(program) {
                                        const value = String(program || '').trim();
                                        if (value && !programs.includes(value)) programs.push(value);
                                    });

                                    card.innerHTML = `
                                        ${swapStatusBadge(schedule)}
                                        <div class="my-room-schedule-top">
                                            <div class="my-room-schedule-course">${escapeSwapHtml(schedule.course_code || '-')}</div>
                                            <div class="my-room-schedule-day">${escapeSwapHtml(schedule.day || '-')} • ${escapeSwapHtml(schedule.time || '-')}</div>
                                        </div>
                                        <div class="my-room-schedule-subject">${escapeSwapHtml(schedule.subject || schedule.course || '-')}</div>
                                        <div class="my-room-schedule-details">
                                            <span><i class="fa-solid fa-building"></i><strong>Building:</strong> ${escapeSwapHtml(buildingData.name || 'Building')}</span>
                                            <span><i class="fa-solid fa-door-open"></i><strong>Room:</strong> ${escapeSwapHtml(roomData.room_name || 'Room')}</span>
                                            <span><i class="fa-solid fa-user"></i><strong>Faculty:</strong> ${escapeSwapHtml(schedule.instructor || 'Unknown Faculty')}</span>
                                            ${programs.length ? `<span><i class="fa-solid fa-graduation-cap"></i><strong>Program:</strong> ${escapeSwapHtml(programs.join(' / '))}</span>` : ''}
                                            ${schedule.year_level ? `<span><i class="fa-solid fa-layer-group"></i><strong>Year:</strong> ${escapeSwapHtml(schedule.year_level)}</span>` : ''}
                                        </div>
                                    `;
                                    searchResults.appendChild(card);
                                });
                            }
                        }
                        return;
                    }

                    // No search text: restore the normal building → room → schedule browser.
                    if (searchResults) {
                        searchResults.innerHTML = '';
                        searchResults.style.display = 'none';
                    }

                    if (selectedMySwapRoom && schedulesPanel?.style.display === 'block') {
                        if (buildingsPanel) buildingsPanel.style.display = 'none';
                        if (roomsPanel) roomsPanel.style.display = 'none';
                        document.querySelectorAll('#mySwapRoomSchedule .my-room-schedule-card').forEach(function(card) {
                            card.style.display = '';
                        });
                    } else if (selectedMySwapBuilding && roomsPanel?.style.display === 'block') {
                        if (buildingsPanel) buildingsPanel.style.display = 'none';
                        document.querySelectorAll('.my-swap-room-card').forEach(function(card) {
                            card.style.display = '';
                        });
                    } else {
                        if (buildingsPanel) buildingsPanel.style.display = 'block';
                        if (roomsPanel) roomsPanel.style.display = 'none';
                        if (schedulesPanel) schedulesPanel.style.display = 'none';
                        document.querySelectorAll('.my-swap-building-card').forEach(function(card) {
                            card.style.display = '';
                        });
                    }
                }


                function clearMyScheduleSearch()
                {
                    const input =
                        document.getElementById(
                            'myScheduleSearch'
                        );

                    if (!input) {
                        return;
                    }

                    input.value = '';
                    filterMySwapRooms();
                }


                function backToMySwapBuildings()
                {
                    selectedMySwapBuilding = null;
                    selectedMySwapRoom = null;

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'block';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'none';

                    filterMySwapRooms();
                }


                function backToMySwapRooms()
                {
                    selectedMySwapRoom = null;

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'block';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'none';

                    filterMySwapRooms();
                }


                /* Show My Buildings immediately when Step 1 loads. */
                renderMySwapBuildings();


                /*
                |--------------------------------------------------------------------------
                | AUTO-DISPLAY MATCHING FACULTY SCHEDULES IN STEP 2
                |--------------------------------------------------------------------------
                */
             function renderAutoMatchingTargetSchedules()
{
    const panel = document.getElementById('autoMatchingSchedulesPanel');
    const results = document.getElementById('autoMatchingSchedules');

    if (!panel || !results) {
        return;
    }

    results.innerHTML = '';

    const departmentFilters =
        document.getElementById('autoMatchingDepartmentFilters');

    if (departmentFilters) {
        departmentFilters.innerHTML = '';
    }

    /*
    |--------------------------------------------------------------------------
    | No Step 1 schedule selected
    |--------------------------------------------------------------------------
    */

    if (!selectedRequesterSchedule) {
        panel.style.display = 'none';
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | USE THE FLAT TARGET SCHEDULE LIST
    |--------------------------------------------------------------------------
    |
    | This contains schedules from ALL:
    | - Departments
    | - Buildings
    | - Rooms
    | - Faculty
    |
    */

    const allSchedules =
        Array.isArray(swapTargetScheduleData)
            ? swapTargetScheduleData
            : [];

          

    /*
    |--------------------------------------------------------------------------
    | Selected faculty information
    |--------------------------------------------------------------------------
    */

    const requesterFacultyId =
        selectedRequesterSchedule.faculty_id ||
        selectedRequesterSchedule.user_id ||
        selectedRequesterSchedule.teacher_id ||
        null;

    const requesterInstructor =
        String(
            selectedRequesterSchedule.instructor || ''
        )
        .trim()
        .toLowerCase();

    /*
    |--------------------------------------------------------------------------
    | Find matching schedules
    |--------------------------------------------------------------------------
    */

    const matches = allSchedules.filter(function(schedule)
    {
        /*
        Prevent selecting the same schedule.
        */

        if (
            Number(schedule.id) ===
            Number(selectedRequesterSchedule.id)
        ) {
            return false;
        }

        /*
        Prevent schedules from the same room.
        */

        if (
            schedule.room_id !== null &&
            schedule.room_id !== undefined &&
            selectedRequesterSchedule.room_id !== null &&
            selectedRequesterSchedule.room_id !== undefined &&
            Number(schedule.room_id) ===
            Number(selectedRequesterSchedule.room_id)
        ) {
            return false;
        }

        /*
        Same faculty is not allowed.
        */

        const targetFacultyId =
            schedule.faculty_id ||
            schedule.user_id ||
            schedule.teacher_id ||
            null;

        const targetInstructor =
            String(
                schedule.instructor || ''
            )
            .trim()
            .toLowerCase();

        if (
            requesterFacultyId &&
            targetFacultyId &&
            String(requesterFacultyId) ===
            String(targetFacultyId)
        ) {
            return false;
        }

        if (
            requesterInstructor &&
            targetInstructor &&
            requesterInstructor === targetInstructor
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | SAME DAY
        |--------------------------------------------------------------------------
        */

        const requesterDays =
            normalizeSwapDay(
                selectedRequesterSchedule.day
            )
            .split(',')
            .filter(Boolean);

        const targetDays =
            normalizeSwapDay(
                schedule.day
            )
            .split(',')
            .filter(Boolean);

        const sameDay =
            requesterDays.some(function(day)
            {
                return targetDays.includes(day);
            });

        if (!sameDay) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | SAME TIME
        |--------------------------------------------------------------------------
        */

        const requesterTime =
            normalizeSwapTime(
                selectedRequesterSchedule.time
            );

        const targetTime =
            normalizeSwapTime(
                schedule.time
            );

        if (
            !requesterTime ||
            !targetTime ||
            requesterTime !== targetTime
        ) {
            return false;
        }

        return true;
    });

    /*
    |--------------------------------------------------------------------------
    | Always show Step 2 after Step 1 selection
    |--------------------------------------------------------------------------
    */

    panel.style.display = 'block';

    /*
    |--------------------------------------------------------------------------
    | No matches
    |--------------------------------------------------------------------------
    */

    if (!matches.length) {

        results.innerHTML = `
            <div
                class="empty-swap-message"
                style="grid-column:1/-1;"
            >
                <i class="fa-solid fa-calendar-xmark"></i>

                <strong>
                    No matching faculty schedules found
                </strong>

                <span>
                    No other faculty schedule matches
                    ${escapeSwapHtml(selectedRequesterSchedule.day || '-')}
                    at
                    ${escapeSwapHtml(selectedRequesterSchedule.time || '-')}.
                </span>
            </div>
        `;

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Build department information from the schedule itself
    |--------------------------------------------------------------------------
    */

    const preparedMatches = matches.map(function(schedule)
    {
        const room =
            schedule.room || {};

        const department =
            room.department ||
            schedule.department ||
            {};

        const building =
            room.building ||
            schedule.building ||
            {};

        return {
            schedule: schedule,
            room: room,
            department: department,
            building: building
        };
    });

    /*
    |--------------------------------------------------------------------------
    | Sort results
    |--------------------------------------------------------------------------
    */

    preparedMatches.sort(function(a, b)
    {
        const departmentA =
            String(
                a.department.name ||
                ''
            );

        const departmentB =
            String(
                b.department.name ||
                ''
            );

        const buildingA =
            String(
                a.building.name ||
                ''
            );

        const buildingB =
            String(
                b.building.name ||
                ''
            );

        const roomA =
            String(
                a.room.room_name ||
                a.room.name ||
                ''
            );

        const roomB =
            String(
                b.room.room_name ||
                b.room.name ||
                ''
            );

        return (
            departmentA.localeCompare(departmentB) ||
            buildingA.localeCompare(buildingB) ||
            roomA.localeCompare(roomB)
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Department filters
    |--------------------------------------------------------------------------
    */

    const departments = [];
    const seenDepartments = new Set();

    preparedMatches.forEach(function(item)
    {
        const departmentId =
            item.department.id ??
            item.schedule.department_id ??
            item.department.name ??
            'unknown';

        const key =
            String(departmentId);

        if (!seenDepartments.has(key)) {

            seenDepartments.add(key);

            departments.push({
                key: key,
                name:
                    String(
                        item.department.name ||
                        'Unknown Department'
                    )
            });
        }
    });

    departments.sort(function(a, b)
    {
        return a.name.localeCompare(b.name);
    });

    /*
    |--------------------------------------------------------------------------
    | Render department filters
    |--------------------------------------------------------------------------
    */

    if (departmentFilters) {

        const filterOptions = [
            {
                key: 'all',
                name: 'All Departments'
            },
            ...departments
        ];

        filterOptions.forEach(function(option, index)
        {
            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'auto-matching-department-filter' +
                (
                    index === 0
                        ? ' active'
                        : ''
                );

            button.textContent =
                option.name;

            button.setAttribute(
                'aria-pressed',
                index === 0
                    ? 'true'
                    : 'false'
            );

            button.addEventListener(
                'click',
                function()
                {
                    departmentFilters
                        .querySelectorAll(
                            '.auto-matching-department-filter'
                        )
                        .forEach(function(filterButton)
                        {
                            const active =
                                filterButton === button;

                            filterButton.classList.toggle(
                                'active',
                                active
                            );

                            filterButton.setAttribute(
                                'aria-pressed',
                                active
                                    ? 'true'
                                    : 'false'
                            );
                        });

                    results
                        .querySelectorAll(
                            '.auto-swap-match-card'
                        )
                        .forEach(function(matchCard)
                        {
                            matchCard.style.display =
                                option.key === 'all' ||
                                matchCard.dataset.departmentKey ===
                                option.key
                                    ? ''
                                    : 'none';
                        });
                }
            );

            departmentFilters.appendChild(button);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Render matching schedule cards
    |--------------------------------------------------------------------------
    */

    preparedMatches.forEach(function(item)
    {
        const schedule =
            item.schedule;

        const room =
            item.room;

        const department =
            item.department;

        const building =
            item.building;

        const card =
            document.createElement('div');

        const departmentKey =
            String(
                department.id ??
                schedule.department_id ??
                department.name ??
                'unknown'
            );

        card.className =
            'auto-swap-match-card' +
            (
                typeof scheduleHasActiveSwap === 'function' &&
                scheduleHasActiveSwap(schedule)
                    ? (
                        schedule.swap_status === 'approved'
                            ? ' swap-approved'
                            : ' swap-pending'
                    )
                    : ''
            );

        card.dataset.departmentKey =
            departmentKey;

        const hasActiveSwap =
            typeof scheduleHasActiveSwap === 'function' &&
            scheduleHasActiveSwap(schedule);

        const statusBadge =
            typeof swapStatusBadge === 'function'
                ? swapStatusBadge(schedule)
                : '';

        const roomName =
            room.room_name ||
            room.name ||
            schedule.room_name ||
            '-';

        const departmentName =
            department.name ||
            schedule.department_name ||
            '-';

        const buildingName =
            building.name ||
            schedule.building_name ||
            '-';

        card.innerHTML = `
            ${statusBadge}

            <span class="auto-swap-match-time">
                ${escapeSwapHtml(schedule.day || '-')}
                ·
                ${escapeSwapHtml(schedule.time || '-')}
            </span>

            <strong>
                ${escapeSwapHtml(schedule.course_code || '-')}
                —
                ${escapeSwapHtml(schedule.subject || '-')}
            </strong>

            <div class="auto-swap-match-meta">

                <div>
                    <i class="fa-solid fa-user"></i>
                    <b>Faculty:</b>
                    ${escapeSwapHtml(
                        schedule.instructor ||
                        'Unknown Faculty'
                    )}
                </div>

                <div>
                    <i class="fa-solid fa-building-columns"></i>
                    <b>Department:</b>
                    ${escapeSwapHtml(
                        departmentName
                    )}
                </div>

                <div>
                    <i class="fa-solid fa-building"></i>
                    <b>Building:</b>
                    ${escapeSwapHtml(
                        buildingName
                    )}
                </div>

                <div>
                    <i class="fa-solid fa-door-open"></i>
                    <b>Room:</b>
                    ${escapeSwapHtml(
                        roomName
                    )}
                </div>

            </div>

            <button
                type="button"
                ${hasActiveSwap ? 'disabled' : ''}
            >
                <i class="fa-solid fa-right-left"></i>

                ${
                    hasActiveSwap
                        ? (
                            schedule.swap_status === 'approved'
                                ? 'Room Already Swapped'
                                : 'Swap Pending'
                        )
                        : 'Select This Schedule'
                }
            </button>
        `;

        const button =
            card.querySelector('button');

        if (button && !hasActiveSwap) {

            button.addEventListener(
                'click',
                function()
                {
                    selectedSwapDepartment =
                        department;

                    selectedSwapBuilding =
                        building;

                    selectedSwapRoom =
                        room;

                    selectTargetSwapSchedule(
                        schedule.id
                    );
                }
            );
        }

        results.appendChild(card);
    });
}
                /*
                |--------------------------------------------------------------------------
                | STEP 2 ROOM SEARCH
                |--------------------------------------------------------------------------
                */

                function filterSwapRoomSearch()
                {
                    const input =
                        document.getElementById(
                            'swapRoomSearch'
                        );

                    const results =
                        document.getElementById(
                            'swapRoomSearchResults'
                        );

                    if (!input || !results) {
                        return;
                    }

                    const search =
                        input.value
                            .trim()
                            .toLowerCase();

                    if (!search) {
                        results.innerHTML = '';
                        results.style.display = 'none';
                        return;
                    }

                    const rooms = [];

                    swapDepartmentData.forEach(
                        function(department)
                        {
                            (
                                department.buildings ||
                                []
                            ).forEach(
                                function(building)
                                {
                                    (
                                        building.rooms ||
                                        []
                                    ).forEach(
                                        function(room)
                                        {
                                            if (
                                                selectedRequesterSchedule &&
                                                Number(room.id) ===
                                                Number(
                                                    selectedRequesterSchedule.room_id
                                                )
                                            ) {
                                                return;
                                            }

                                            const searchable =
                                                (
                                                    String(room.room_name || '') +
                                                    ' ' +
                                                    String(building.name || '') +
                                                    ' ' +
                                                    String(department.name || '')
                                                ).toLowerCase();

                                            if (
                                                searchable.includes(
                                                    search
                                                )
                                            ) {
                                                rooms.push({
                                                    room: room,
                                                    building: building,
                                                    department: department
                                                });
                                            }
                                        }
                                    );
                                }
                            );
                        }
                    );

                    results.innerHTML = '';

                    if (!rooms.length) {
                        results.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <strong>No rooms found</strong>
                                <span>
                                    Try another room, building, or department.
                                </span>
                            </div>
                        `;

                        results.style.display = 'block';
                        return;
                    }

                    rooms.forEach(
                        function(item)
                        {
                            const card =
                                document.createElement(
                                    'div'
                                );

                            card.className =
                                'swap-search-room-card';

                            card.innerHTML = `
                                <div class="swap-search-room-icon">
                                    <i class="fa-solid fa-door-open"></i>
                                </div>

                                <div class="swap-search-room-details">

                                    <strong>
                                        ${escapeSwapHtml(
                                            item.room.room_name
                                        )}
                                    </strong>

                                    <span>
                                        ${escapeSwapHtml(
                                            item.department.name
                                        )}
                                    </span>

                                    <span>
                                        ${escapeSwapHtml(
                                            item.building.name
                                        )}
                                    </span>

                                </div>

                                <button
                                    type="button"
                                    class="swap-search-room-button"
                                >
                                    View Schedule
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            `;

                            card
                                .querySelector(
                                    '.swap-search-room-button'
                                )
                                .onclick =
                                function()
                                {
                                    selectedSwapDepartment =
                                        item.department;

                                    selectedSwapBuilding =
                                        item.building;

                                    openSwapRoomFromSearch(
                                        item.room.id
                                    );
                                };

                            results.appendChild(
                                card
                            );
                        }
                    );

                    results.style.display =
                        'grid';
                }


                function clearSwapRoomSearch()
                {
                    const input =
                        document.getElementById(
                            'swapRoomSearch'
                        );

                    if (!input) {
                        return;
                    }

                    input.value = '';

                    filterSwapRoomSearch();
                }


                function openSwapRoomFromSearch(
                    roomId
                )
                {
                    if (
                        !selectedSwapBuilding
                    ) {
                        return;
                    }

                    selectedSwapRoom =
                        (
                            selectedSwapBuilding.rooms ||
                            []
                        ).find(
                            function(room)
                            {
                                return Number(room.id) ===
                                    Number(roomId);
                            }
                        );

                    if (!selectedSwapRoom) {
                        return;
                    }

                    if (
                        selectedRequesterSchedule &&
                        Number(selectedSwapRoom.id) ===
                        Number(
                            selectedRequesterSchedule.room_id
                        )
                    ) {
                        alert(
                            'You cannot select the same room you selected in Step 1.'
                        );
                        return;
                    }

                    openSelectedSwapRoom();
                }


                function openSelectedSwapRoom()
                {
                    if (!selectedSwapRoom) {
                        return;
                    }

                    document.getElementById(
                        'swapRoomName'
                    ).textContent =
                        selectedSwapRoom.room_name;

                    document.getElementById(
                        'swapRoomInformation'
                    ).innerHTML =
                        buildRoomInfo(
                            selectedSwapRoom
                        );

                    buildSwapExcelSchedule(
                        selectedSwapRoom
                    );

                    const searchResults =
                        document.getElementById(
                            'swapRoomSearchResults'
                        );

                    if (searchResults) {
                        searchResults.style.display = 'none';
                    }

                    document.getElementById(
                        'swapDepartments'
                    ).style.display = 'none';

                    document.getElementById(
                        'swapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'swapRooms'
                    ).style.display = 'none';

                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display = 'block';
                }


                /*
                |--------------------------------------------------------------------------
                | OPEN DEPARTMENT
                |--------------------------------------------------------------------------
                */

                function openSwapDepartment(
                    departmentId
                )
                {

                    selectedSwapDepartment =
                        swapDepartmentData.find(
                            function(department)
                            {
                                return Number(
                                    department.id
                                ) ===
                                Number(
                                    departmentId
                                );
                            }
                        );


                    if (
                        !selectedSwapDepartment
                    ) {
                        return;
                    }


                    document.getElementById(
                        'swapDepartmentName'
                    ).textContent =
                        selectedSwapDepartment.name;


                    const buildingList =
                        document.getElementById(
                            'swapBuildingList'
                        );


                    buildingList.innerHTML = '';


                    const buildings =
                        selectedSwapDepartment.buildings ||
                        [];


                    if (
                        buildings.length === 0
                    ) {

                        buildingList.innerHTML = `

                            <div class="empty-swap-message">

                                <i class="
                                    fa-solid
                                    fa-building-circle-xmark
                                "></i>

                                <strong>
                                    No buildings found
                                </strong>

                                <span>
                                    This department has no buildings assigned.
                                </span>

                            </div>

                        `;

                    } else {

                        buildings.forEach(
                            function(building)
                            {

                                const rooms =
                                    building.rooms ||
                                    [];


                                const card =
                                    document.createElement(
                                        'button'
                                    );


                                card.type =
                                    'button';


                                card.className =
                                    'swap-navigation-card';


                                card.onclick =
                                    function()
                                    {
                                        openSwapBuilding(
                                            building.id
                                        );
                                    };


                                card.innerHTML = `

                                    <div class="swap-navigation-left">

                                        <div class="swap-navigation-icon">

                                            <i class="
                                                fa-solid
                                                fa-building
                                            "></i>

                                        </div>

                                        <div>

                                            <div class="swap-navigation-name">

                                                ${escapeSwapHtml(
                                                    building.name
                                                )}

                                            </div>

                                            <div class="swap-navigation-description">

                                                ${rooms.length}

                                                ${
                                                    rooms.length === 1
                                                        ? 'room'
                                                        : 'rooms'
                                                }

                                            </div>

                                        </div>

                                    </div>


                                    <i class="
                                        fa-solid
                                        fa-chevron-right
                                        swap-navigation-arrow
                                    "></i>

                                `;


                                buildingList.appendChild(
                                    card
                                );

                            }
                        );

                    }


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | OPEN BUILDING
                |--------------------------------------------------------------------------
                */

                function openSwapBuilding(
                    buildingId
                )
                {

                    if (
                        !selectedSwapDepartment
                    ) {
                        return;
                    }


                    selectedSwapBuilding =
                        (
                            selectedSwapDepartment.buildings ||
                            []
                        ).find(
                            function(building)
                            {
                                return Number(
                                    building.id
                                ) ===
                                Number(
                                    buildingId
                                );
                            }
                        );


                    if (
                        !selectedSwapBuilding
                    ) {
                        return;
                    }


                    document.getElementById(
                        'swapBuildingName'
                    ).textContent =
                        selectedSwapBuilding.name;


                    const roomList =
                        document.getElementById(
                            'swapRoomList'
                        );


                    roomList.innerHTML = '';


                    const rooms =
                        (
                            selectedSwapBuilding.rooms ||
                            []
                        ).filter(
                            function(room)
                            {
                                if (
                                    !selectedRequesterSchedule
                                ) {
                                    return true;
                                }

                                return Number(room.id) !==
                                    Number(
                                        selectedRequesterSchedule.room_id
                                    );
                            }
                        );


                    if (
                        rooms.length === 0
                    ) {

                        roomList.innerHTML = `

                            <div class="empty-swap-message">

                                <i class="
                                    fa-solid
                                    fa-door-closed
                                "></i>

                                <strong>
                                    No rooms found
                                </strong>

                                <span>
                                    This building has no rooms assigned.
                                </span>

                            </div>

                        `;

                    } else {

                        rooms.forEach(
                            function(room)
                            {

                                const card =
                                    document.createElement(
                                        'div'
                                    );


                                card.className =
                                    'swap-room-card';


                                card.innerHTML = `

                                    <div class="swap-room-header">

                                        <div class="swap-room-icon">

                                            <i class="
                                                fa-solid
                                                fa-door-open
                                            "></i>

                                        </div>

                                        <div>

                                            <div class="swap-room-name">

                                                ${escapeSwapHtml(
                                                    room.room_name
                                                )}

                                            </div>

                                            <div class="swap-room-building">

                                                ${escapeSwapHtml(
                                                    selectedSwapBuilding.name
                                                )}

                                            </div>

                                        </div>

                                    </div>


                                    <div class="swap-room-information">

                                        ${buildRoomInfo(room)}

                                    </div>


                                    <button
                                        type="button"
                                        class="swap-view-room-button"
                                        onclick="
                                            openSwapRoom(
                                                ${room.id}
                                            )
                                        "
                                    >

                                        <i class="
                                            fa-solid
                                            fa-calendar-days
                                        "></i>

                                        View Room Schedule

                                    </button>

                                `;


                                roomList.appendChild(
                                    card
                                );

                            }
                        );

                    }


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | ROOM INFORMATION
                |--------------------------------------------------------------------------
                */

                function buildRoomInfo(
                    room
                )
                {

                    let html = '';


                    if (
                        room.capacity !== null &&
                        room.capacity !== undefined &&
                        room.capacity !== ''
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Capacity
                                </div>

                                <div class="swap-room-info-value">

                                    ${escapeSwapHtml(
                                        room.capacity
                                    )}
                                    students

                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.computers !== null &&
                        room.computers !== undefined &&
                        room.computers !== '' &&
                        Number(room.computers) > 0
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Computers
                                </div>

                                <div class="swap-room-info-value">

                                    ${escapeSwapHtml(
                                        room.computers
                                    )}

                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.has_tv
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    TV
                                </div>

                                <div class="
                                    swap-room-info-value
                                    available
                                ">
                                    ✓ Available
                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.has_projector
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Projector
                                </div>

                                <div class="
                                    swap-room-info-value
                                    available
                                ">
                                    ✓ Available
                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.purpose
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Purpose
                                </div>

                                <div class="swap-room-info-value">

                                    ${escapeSwapHtml(
                                        room.purpose
                                    )}

                                </div>

                            </div>

                        `;

                    }


                    return html;

                }


                /*
                |--------------------------------------------------------------------------
                | OPEN ROOM
                |--------------------------------------------------------------------------
                */

                function openSwapRoom(
                    roomId
                )
                {

                    if (
                        !selectedSwapBuilding
                    ) {
                        return;
                    }


                    selectedSwapRoom =
                        (
                            selectedSwapBuilding.rooms ||
                            []
                        ).find(
                            function(room)
                            {
                                return Number(
                                    room.id
                                ) ===
                                Number(
                                    roomId
                                );
                            }
                        );


                    if (
                        !selectedSwapRoom
                    ) {
                        return;
                    }


                    if (
                        selectedRequesterSchedule &&
                        Number(selectedSwapRoom.id) ===
                        Number(
                            selectedRequesterSchedule.room_id
                        )
                    ) {
                        alert(
                            'You cannot select the same room you selected in Step 1.'
                        );
                        return;
                    }

                    openSelectedSwapRoom();

                }


                /*
                |--------------------------------------------------------------------------
                | NORMALIZE DAY
                |--------------------------------------------------------------------------
                */

               function normalizeSwapDay(day)
{
    const value = String(day || '').trim().toUpperCase();
    const aliases = {
        MONDAY: 'MON', MON: 'MON', M: 'MON',
        TUESDAY: 'TUE', TUES: 'TUE', TUE: 'TUE', TU: 'TUE',
        WEDNESDAY: 'WED', WED: 'WED', W: 'WED',
        THURSDAY: 'THU', THURSD: 'THU', THURS: 'THU', THUR: 'THU', THU: 'THU', TH: 'THU',
        FRIDAY: 'FRI', FRI: 'FRI', F: 'FRI',
        SATURDAY: 'SAT', SAT: 'SAT',
        SUNDAY: 'SUN', SUN: 'SUN'
    };

    // Handle values such as "Monday, Wednesday" and "MON/WED/FRI".
    const parts = value.split(/[\s,\/|&+]+/).filter(Boolean);
    const normalized = parts.map(function(part) {
        return aliases[part] || part;
    });
    return normalized.join(',');
}

                /*
                |--------------------------------------------------------------------------
                | NORMALIZE TIME
                |--------------------------------------------------------------------------
                */
function normalizeSwapTime(time)
{
    const raw = String(time || '')
        .trim()
        .toLowerCase()
        .replace(/[–—−]/g, '-')
        .replace(/\bto\b/g, '-')
        .replace(/\s+/g, ' ');

    if (!raw) return '';

    function normalizeEndpoint(endpoint) {
        let value = String(endpoint || '').trim().toLowerCase();
        const match = value.match(/(\d{1,2})(?::(\d{2}))?\s*(a\.?m\.?|p\.?m\.?)?/i);
        if (!match) return value.replace(/\s+/g, '');

        let hour = Number(match[1]);
        const minute = Number(match[2] || 0);
        const meridiem = String(match[3] || '').replace(/\./g, '').toLowerCase();
        if (meridiem === 'pm' && hour < 12) hour += 12;
        if (meridiem === 'am' && hour === 12) hour = 0;
        return String(hour).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
    }

    // Normalize each end of a range. If only the first end has AM/PM,
    // inherit it for the second end (e.g. "8:00 AM - 10:00").
    const range = raw.split(/\s*-\s*/);
    if (range.length >= 2) {
        let left = range[0];
        let right = range.slice(1).join('-');
        const leftMeridiem = left.match(/(a\.?m\.?|p\.?m\.?)\s*$/i);
        const rightMeridiem = right.match(/(a\.?m\.?|p\.?m\.?)\s*$/i);
        if (leftMeridiem && !rightMeridiem) right += ' ' + leftMeridiem[1];
        return normalizeEndpoint(left) + '-' + normalizeEndpoint(right);
    }

    return normalizeEndpoint(raw);
}

                /*
                |--------------------------------------------------------------------------
                | CHECK SAME DAY/TIME
                |--------------------------------------------------------------------------
                */

           function canSelectTargetSchedule(schedule)
{
    if (!selectedRequesterSchedule || !schedule) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | GET REQUESTER DAY AND TIME
    |--------------------------------------------------------------------------
    */

    const requesterDayRaw = String(
        selectedRequesterSchedule.day || ''
    ).trim();

    const requesterTimeRaw = String(
        selectedRequesterSchedule.time || ''
    ).trim();


    /*
    |--------------------------------------------------------------------------
    | GET TARGET DAY AND TIME
    |--------------------------------------------------------------------------
    */

    const targetDayRaw = String(
        schedule.day || ''
    ).trim();

    const targetTimeRaw = String(
        schedule.time || ''
    ).trim();


    if (
        requesterDayRaw === '' ||
        requesterTimeRaw === '' ||
        targetDayRaw === '' ||
        targetTimeRaw === ''
    ) {
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DAYS
    |--------------------------------------------------------------------------
    */

    const requesterDays = normalizeSwapDay(
        requesterDayRaw
    )
        .split(',')
        .map(function(day) {
            return String(day).trim().toUpperCase();
        })
        .filter(Boolean);


    const targetDays = normalizeSwapDay(
        targetDayRaw
    )
        .split(',')
        .map(function(day) {
            return String(day).trim().toUpperCase();
        })
        .filter(Boolean);


    /*
    |--------------------------------------------------------------------------
    | SAME DAY
    |--------------------------------------------------------------------------
    */

    const sameDay = requesterDays.some(
        function(requesterDay) {
            return targetDays.includes(
                requesterDay
            );
        }
    );


    if (!sameDay) {
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE TIMES
    |--------------------------------------------------------------------------
    */

    const requesterTime = normalizeSwapTime(
        requesterTimeRaw
    );

    const targetTime = normalizeSwapTime(
        targetTimeRaw
    );


    /*
    |--------------------------------------------------------------------------
    | SAME EXACT TIME
    |--------------------------------------------------------------------------
    */

    if (
        requesterTime === '' ||
        targetTime === ''
    ) {
        return false;
    }


    return requesterTime === targetTime;
}

                /*
                |--------------------------------------------------------------------------
                | BUILD EXCEL SCHEDULE
                |--------------------------------------------------------------------------
                */

                function buildSwapExcelSchedule(
                    room
                )
                {

                    const body =
                        document.getElementById(
                            'swapExcelBody'
                        );


                    body.innerHTML = '';


                    const schedules =
                        room.schedules ||
                        [];


                    /*
                    | Show selected allowed day/time
                    */

                    const filterBox =
                        document.getElementById(
                            'swapScheduleFilter'
                        );


                    if (
                        selectedRequesterSchedule
                    ) {

                        document.getElementById(
                            'swapAllowedDay'
                        ).textContent =
                            selectedRequesterSchedule.day;


                        document.getElementById(
                            'swapAllowedTime'
                        ).textContent =
                            selectedRequesterSchedule.time;


                        filterBox.style.display =
                            'block';

                    } else {

                        filterBox.style.display =
                            'none';

                    }


                    /*
                    | Default Excel rows
                    */

                    const defaultTimes = [

                        '8:00-9:00',

                        '9:00-10:00',

                        '10:00-11:00',

                        '11:00-12:00',

                        '12:00-1:00',

                        '1:00-2:00',

                        '2:00-3:00',

                        '3:00-4:00',

                        '4:00-5:00'

                    ];


                   function findSchedule(day, time)
{
    return schedules.find(
        function(schedule)
        {
            const scheduleDay =
                normalizeSwapDay(
                    schedule.day
                );

            const scheduleTime =
                normalizeSwapTime(
                    schedule.time
                );

            const wantedDay =
                normalizeSwapDay(
                    day
                );

            const wantedTime =
                normalizeSwapTime(
                    time
                );


            return (
                scheduleDay === wantedDay &&
                scheduleTime === wantedTime
            );
        }
    );
}

                    defaultTimes.forEach(
                        function(
                            time,
                            index
                        )
                        {

                            /*
                            | NOON BREAK
                            */

                            if (
                                index === 4
                            ) {

                                body.innerHTML += `

                                    <tr class="swap-noon-break">

                                        <td colspan="7">

                                            N O O N &nbsp; B R E A K

                                        </td>

                                    </tr>

                                `;

                            }


                            const mon =
                                findSchedule(
                                    'MON',
                                    time
                                );


                            const wed =
                                findSchedule(
                                    'WED',
                                    time
                                );


                            const fri =
                                findSchedule(
                                    'FRI',
                                    time
                                );


                            const tue =
                                findSchedule(
                                    'TUE',
                                    time
                                );


                            const thu =
                                findSchedule(
                                    'THU',
                                    time
                                );


                            body.innerHTML += `

                                <tr>

                                    <td class="swap-excel-time">

                                        ${escapeSwapHtml(
                                            time
                                        )}

                                    </td>


                                    ${buildSwapScheduleCell(
                                        mon
                                    )}


                                    ${buildSwapScheduleCell(
                                        wed
                                    )}


                                    ${buildSwapScheduleCell(
                                        fri
                                    )}


                                    <td class="swap-excel-time">

                                        ${escapeSwapHtml(
                                            time
                                        )}

                                    </td>


                                    ${buildSwapScheduleCell(
                                        tue
                                    )}


                                    ${buildSwapScheduleCell(
                                        thu
                                    )}

                                </tr>

                            `;

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | BUILD SCHEDULE CELL
                |--------------------------------------------------------------------------
                */

                function buildSwapScheduleCell(
                    schedule
                )
                {

                    if (
                        !schedule
                    ) {

                        return `

                            <td class="swap-excel-empty">
                                Empty
                            </td>

                        `;

                    }


                    const allowed =
                        canSelectTargetSchedule(
                            schedule
                        );


                    const programs = [];


                    if (
                        schedule.program
                    ) {

                        programs.push(
                            schedule.program
                        );

                    }


                    const mergedPrograms =
                        schedule.merged_programs ||
                        [];


                    mergedPrograms.forEach(
                        function(program)
                        {

                            if (
                                program &&
                                !programs.includes(
                                    program
                                )
                            ) {

                                programs.push(
                                    program
                                );

                            }

                        }
                    );


                    const isMergedClass =
                        programs.length > 1;


                    const mergeLabel =
                        isMergedClass
                            ? `
                                <div class="swap-merge-badge">
                                    <i class="fa-solid fa-link"></i>
                                    Merged Class
                                </div>

                                <div class="swap-merge-program">
                                    ${escapeSwapHtml(
                                        programs.join(' / ')
                                    )}
                                </div>
                            `
                            : '';


                    /*
                    | WRONG DAY/TIME
                    */

                    if (
                        !allowed
                    ) {

                        return `

                            <td
                                class="
                                    swap-excel-occupied
                                    disabled-swap
                                "
                                title="
                                    Only the same day and time
                                    as your selected schedule
                                    can be swapped.
                                "
                            >

                                <div class="swap-excel-course">

                                    ${escapeSwapHtml(
                                        schedule.course_code ||
                                        ''
                                    )}

                                </div>

                                <div class="swap-excel-subject">

                                    ${escapeSwapHtml(
                                        schedule.subject ||
                                        ''
                                    )}

                                </div>

                                ${mergeLabel}

                                <div style="
                                    font-size:9px;
                                    margin-top:5px;
                                    color:#98a2b3;
                                    font-weight:700;
                                ">

                                    Same day/time required

                                </div>

                            </td>

                        `;

                    }


                    /*
                    | ALLOWED
                    */

                    return `

                        <td
                            class="
                                swap-excel-occupied
                            "
                            data-schedule-id="
                                ${schedule.id}
                            "
                            onclick="
                                selectTargetSwapSchedule(
                                    ${schedule.id}
                                )
                            "
                        >

                            <div class="swap-excel-course">

                                ${escapeSwapHtml(
                                    schedule.course_code ||
                                    ''
                                )}

                            </div>


                            <div class="swap-excel-subject">

                                ${escapeSwapHtml(
                                    schedule.subject ||
                                    ''
                                )}

                            </div>


                            <div class="swap-excel-instructor">

                                ${escapeSwapHtml(
                                    schedule.instructor ||
                                    'Unknown Faculty'
                                )}

                            </div>


                            ${
                                isMergedClass
                                    ? mergeLabel
                                    : (
                                        programs.length
                                            ? `

                                                <div class="
                                                    swap-excel-program
                                                ">

                                                    ${escapeSwapHtml(
                                                        programs.join(
                                                            ' / '
                                                        )
                                                    )}

                                                </div>

                                            `
                                            : ''
                                    )
                            }

                        </td>

                    `;

                }


                /*
                |--------------------------------------------------------------------------
                | STEP 2: CONFIRM TARGET SCHEDULE BEFORE OPENING STEP 3
                |--------------------------------------------------------------------------
                */
              function selectTargetSwapSchedule(scheduleId)
{
    if (!selectedRequesterSchedule) {
        alert('Please select your schedule first.');
        return;
    }

    /*
     * Find the target schedule from the COMPLETE schedule list.
     * This allows Step 2 to select schedules from:
     * - other rooms
     * - other buildings
     * - other departments
     * - other faculty
     */
    const schedule = (typeof swapTargetScheduleData !== 'undefined'
        ? swapTargetScheduleData
        : []
    ).find(function(item) {
        return Number(item.id) === Number(scheduleId);
    });

    if (!schedule) {
        alert('The selected target schedule could not be found.');
        return;
    }

    /*
     * Do not allow selecting your own schedule.
     */
    if (Number(schedule.id) === Number(selectedRequesterSchedule.id)) {
        alert('You cannot swap a schedule with itself.');
        return;
    }

    /*
     * Do not allow schedules from the same room.
     */
    if (
        schedule.room_id !== null &&
        schedule.room_id !== undefined &&
        selectedRequesterSchedule.room_id !== null &&
        selectedRequesterSchedule.room_id !== undefined &&
        Number(schedule.room_id) === Number(selectedRequesterSchedule.room_id)
    ) {
        alert('Both schedules are already assigned to the same room.');
        return;
    }

    /*
     * Target must have the same day and time.
     */
    if (!canSelectTargetSchedule(schedule)) {
        alert(
            'You can only swap with a schedule that has the same day and same time as your selected schedule.'
        );
        return;
    }

    /*
     * Save the selected target schedule.
     */
    pendingTargetScheduleId = Number(schedule.id);

    /*
     * Get room information.
     */
    let roomName = '-';

    if (schedule.room) {
        roomName =
            schedule.room.room_name ||
            schedule.room.name ||
            schedule.room.room_number ||
            '-';
    }

    /*
     * Get department information.
     */
    let departmentName =
        schedule.department_name ||
        (schedule.room && schedule.room.department
            ? schedule.room.department.name
            : null) ||
        '-';

    /*
     * Get building information.
     */
    let buildingName =
        schedule.building_name ||
        (schedule.room && schedule.room.building
            ? schedule.room.building.name
            : null) ||
        '-';

    /*
     * Display Step 3 confirmation.
     */
    const summary = document.getElementById('targetConfirmSummary');

    if (summary) {
        summary.innerHTML = `
            <div class="swap-modal-summary-row">
                <span>Course</span>
                <strong>
                    ${escapeSwapHtml(schedule.course_code || '-')}
                    —
                    ${escapeSwapHtml(schedule.subject || '-')}
                </strong>
            </div>

            <div class="swap-modal-summary-row">
                <span>Faculty</span>
                <strong>
                    ${escapeSwapHtml(schedule.instructor || 'Unknown Faculty')}
                </strong>
            </div>

            <div class="swap-modal-summary-row">
                <span>Department</span>
                <strong>
                    ${escapeSwapHtml(departmentName)}
                </strong>
            </div>

            <div class="swap-modal-summary-row">
                <span>Building</span>
                <strong>
                    ${escapeSwapHtml(buildingName)}
                </strong>
            </div>

            <div class="swap-modal-summary-row">
                <span>Room</span>
                <strong>
                    ${escapeSwapHtml(roomName)}
                </strong>
            </div>

            <div class="swap-modal-summary-row">
                <span>Day</span>
                <strong>
                    ${escapeSwapHtml(schedule.day || '-')}
                </strong>
            </div>

            <div class="swap-modal-summary-row">
                <span>Time</span>
                <strong>
                    ${escapeSwapHtml(schedule.time || '-')}
                </strong>
            </div>
        `;
    }

    const modal = document.getElementById('targetScheduleConfirmModal');

    if (modal) {
        modal.style.display = 'flex';
    }
}

                function cancelTargetScheduleConfirmation()
                {
                    pendingTargetScheduleId = null;
                    const modal = document.getElementById('targetScheduleConfirmModal');
                    if (modal) modal.style.display = 'none';
                }

                function confirmPendingTargetSchedule()
                {
                    if (pendingTargetScheduleId === null) return;
                    const scheduleId = pendingTargetScheduleId;
                    const confirmModal = document.getElementById('targetScheduleConfirmModal');
                    if (confirmModal) confirmModal.style.display = 'none';

                    applyTargetSwapSchedule(scheduleId);
                    pendingTargetScheduleId = null;

                    if (selectedTargetSchedule) {
                        const step3Modal = document.getElementById('step3Modal');
                        if (step3Modal) step3Modal.style.display = 'flex';
                    }
                }

                function closeStep3Modal()
                {
                    const modal = document.getElementById('step3Modal');
                    if (modal) modal.style.display = 'none';
                }

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        const step3 = document.getElementById('step3Modal');
                        if (step3 && step3.style.display !== 'none') closeStep3Modal();
                        else cancelTargetScheduleConfirmation();
                    }
                });

                /*
                |--------------------------------------------------------------------------
                | SELECT TARGET SCHEDULE
                |--------------------------------------------------------------------------
                */
function applyTargetSwapSchedule(scheduleId)
{
    if (!selectedRequesterSchedule) {
        alert('Please select your schedule first.');
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | FIND TARGET FROM THE COMPLETE TARGET SCHEDULE LIST
    |--------------------------------------------------------------------------
    */

    const schedules = Array.isArray(swapTargetScheduleData)
        ? swapTargetScheduleData
        : [];

    const schedule = schedules.find(function(item) {
        return Number(item.id) === Number(scheduleId);
    });

    if (!schedule) {
        alert('The selected target schedule could not be found.');
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | PREVENT INVALID SWAP
    |--------------------------------------------------------------------------
    */

    if (scheduleHasActiveSwap(schedule)) {
        alert(
            schedule.swap_status === 'approved'
                ? 'This target schedule has already been swapped.'
                : 'This target schedule already has a pending room swap request.'
        );
        return;
    }

    if (Number(schedule.id) === Number(selectedRequesterSchedule.id)) {
        alert('You cannot swap a schedule with itself.');
        return;
    }

    if (
        schedule.room_id !== null &&
        schedule.room_id !== undefined &&
        selectedRequesterSchedule.room_id !== null &&
        selectedRequesterSchedule.room_id !== undefined &&
        Number(schedule.room_id) ===
        Number(selectedRequesterSchedule.room_id)
    ) {
        alert('Both schedules are already assigned to the same room.');
        return;
    }

    if (!canSelectTargetSchedule(schedule)) {
        alert(
            'You can only swap with a schedule that has the same day and same time as your selected schedule.'
        );
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE SELECTED TARGET
    |--------------------------------------------------------------------------
    */

    selectedTargetSchedule = schedule;

    /*
    |--------------------------------------------------------------------------
    | HIDDEN FORM VALUES
    |--------------------------------------------------------------------------
    */

    const targetScheduleIdField =
        document.getElementById('target_schedule_id');

    if (targetScheduleIdField) {
        targetScheduleIdField.value = schedule.id;
    }

    const targetRoomIdField =
        document.getElementById('target_room_id');

    if (targetRoomIdField) {
        targetRoomIdField.value = schedule.room_id || '';
    }

    const targetUserIdField =
        document.getElementById('target_user_id');

    if (targetUserIdField) {
        targetUserIdField.value =
            schedule.faculty_id ||
            schedule.user_id ||
            schedule.teacher_id ||
            '';
    }

    /*
    |--------------------------------------------------------------------------
    | SWAP DATE / TIME
    |--------------------------------------------------------------------------
    */

    const swapDateField =
        document.getElementById('swap_date');

    if (swapDateField) {
        swapDateField.value =
            new Date().toISOString().slice(0, 10);
    }

    const timeParts =
        String(schedule.time || '').split('-');

    const startTime =
        timeParts[0]
            ? timeParts[0].trim()
            : '';

    const endTime =
        timeParts.length > 1
            ? timeParts[1].trim()
            : '';

    const startTimeField =
        document.getElementById('start_time');

    if (startTimeField) {
        startTimeField.value = startTime;
    }

    const endTimeField =
        document.getElementById('end_time');

    if (endTimeField) {
        endTimeField.value = endTime;
    }

    /*
    |--------------------------------------------------------------------------
    | DISPLAY SELECTED TARGET
    |--------------------------------------------------------------------------
    */

    const room = schedule.room || {};
    const building = room.building || {};
    const department = room.department || {};

    const roomName =
        room.room_name ||
        room.name ||
        room.room_number ||
        '-';

    const buildingName =
        building.name ||
        schedule.building_name ||
        '-';

    const departmentName =
        department.name ||
        schedule.department_name ||
        '-';

    const selectedTargetBox =
        document.getElementById('selectedTargetSchedule');

    if (selectedTargetBox) {

        selectedTargetBox.innerHTML = `
            <div class="selected-requester-header">
                <div>
                    <span class="selected-requester-eyebrow">
                        <i class="fa-solid fa-right-left"></i>
                        Target Schedule Selected
                    </span>

                    <h3>
                        ${escapeSwapHtml(
                            schedule.course_code || '-'
                        )}
                    </h3>

                    <p>
                        ${escapeSwapHtml(
                            schedule.subject || '-'
                        )}
                    </p>
                </div>
            </div>

            <div class="selected-requester-grid">

                <div class="selected-requester-item">
                    <span>Faculty</span>
                    <strong>
                        ${escapeSwapHtml(
                            schedule.instructor ||
                            'Unknown Faculty'
                        )}
                    </strong>
                </div>

                <div class="selected-requester-item">
                    <span>Department</span>
                    <strong>
                        ${escapeSwapHtml(
                            departmentName
                        )}
                    </strong>
                </div>

                <div class="selected-requester-item">
                    <span>Building</span>
                    <strong>
                        ${escapeSwapHtml(
                            buildingName
                        )}
                    </strong>
                </div>

                <div class="selected-requester-item">
                    <span>Room</span>
                    <strong>
                        ${escapeSwapHtml(
                            roomName
                        )}
                    </strong>
                </div>

                <div class="selected-requester-item">
                    <span>Day</span>
                    <strong>
                        ${escapeSwapHtml(
                            schedule.day || '-'
                        )}
                    </strong>
                </div>

                <div class="selected-requester-item">
                    <span>Time</span>
                    <strong>
                        ${escapeSwapHtml(
                            schedule.time || '-'
                        )}
                    </strong>
                </div>

            </div>
        `;

        selectedTargetBox.style.display = 'block';
    }

    /*
    |--------------------------------------------------------------------------
    | OPEN STEP 3
    |--------------------------------------------------------------------------
    */

    const step3Modal =
        document.getElementById('step3Modal');

    if (step3Modal) {
        step3Modal.style.display = 'flex';
    }
}

                /*
                |--------------------------------------------------------------------------
                | REASON
                |--------------------------------------------------------------------------
                */

                function selectReason(
                    reason,
                    element
                )
                {

                    document
                        .querySelectorAll(
                            '.reason-option'
                        )
                        .forEach(
                            function(option)
                            {

                                option.classList.remove(
                                    'selected'
                                );

                            }
                        );


                    element.classList.add(
                        'selected'
                    );


                    const reasonField =
                        document.getElementById(
                            'reason'
                        );


                    const otherReason =
                        document.getElementById(
                            'otherReason'
                        );


                    if (
                        reason === 'Other'
                    ) {

                        reasonField.value =
                            '';


                        otherReason.focus();

                    } else {

                        reasonField.value =
                            reason;

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | BACK TO DEPARTMENTS
                |--------------------------------------------------------------------------
                */

                function backToSwapDepartments()
                {

                    selectedSwapDepartment =
                        null;

                    selectedSwapBuilding =
                        null;

                    selectedSwapRoom =
                        null;


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | BACK TO BUILDINGS
                |--------------------------------------------------------------------------
                */

                function backToSwapBuildings()
                {

                    selectedSwapBuilding =
                        null;

                    selectedSwapRoom =
                        null;


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | BACK TO ROOMS
                |--------------------------------------------------------------------------
                */

                function backToSwapRooms()
                {

                    selectedSwapRoom =
                        null;


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | RESET
                |--------------------------------------------------------------------------
                */

                function resetSwap()
                {
                    const autoPanel = document.getElementById('autoMatchingSchedulesPanel');
                    const autoResults = document.getElementById('autoMatchingSchedules');
                    if (autoPanel) autoPanel.style.display = 'none';
                    if (autoResults) autoResults.innerHTML = '';

                    document
                        .getElementById(
                            'swapForm'
                        )
                        .reset();


                    selectedRequesterSchedule =
                        null;

                    selectedSwapDepartment =
                        null;

                    selectedSwapBuilding =
                        null;

                    selectedSwapRoom =
                        null;

                    selectedTargetSchedule =
                        null;
                    pendingTargetScheduleId = null;
                    const confirmModal = document.getElementById('targetScheduleConfirmModal');
                    const step3Modal = document.getElementById('step3Modal');
                    if (confirmModal) confirmModal.style.display = 'none';
                    if (step3Modal) step3Modal.style.display = 'none';


                    document
                        .querySelectorAll(
                            '.booking-card'
                        )
                        .forEach(
                            function(card)
                            {

                                card.classList.remove(
                                    'selected'
                                );

                            }
                        );


                    document
                        .querySelectorAll(
                            '.reason-option'
                        )
                        .forEach(
                            function(option)
                            {

                                option.classList.remove(
                                    'selected'
                                );

                            }
                        );


                    document.getElementById(
                        'selectedRequesterSchedule'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'selectedTargetSchedule'
                    ).style.display =
                        'none';

                    const mySearch =
                        document.getElementById(
                            'myScheduleSearch'
                        );

                    if (mySearch) {
                        mySearch.value = '';
                    }

                    const roomSearch =
                        document.getElementById(
                            'swapRoomSearch'
                        );

                    if (roomSearch) {
                        roomSearch.value = '';
                    }

                    const roomResults =
                        document.getElementById(
                            'swapRoomSearchResults'
                        );

                    if (roomResults) {
                        roomResults.innerHTML = '';
                        roomResults.style.display = 'none';
                    }

                    const selectedRoomNote =
                        document.getElementById(
                            'swapSelectedRoomNote'
                        );

                    if (selectedRoomNote) {
                        selectedRoomNote.style.display = 'none';
                    }

                    backToSwapDepartments();

                }


                /*
                |--------------------------------------------------------------------------
                | BEFORE SUBMIT
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById(
                        'swapForm'
                    )
                    .addEventListener(
                        'submit',
                        function(event)
                        {

                            /*
                            | Make sure requester schedule exists
                            */

                            if (
                                !selectedRequesterSchedule
                            ) {

                                event.preventDefault();

                                alert(
                                    'Please select your schedule first.'
                                );

                                return;

                            }


                            /*
                            | Make sure target exists
                            */

                            if (
                                !selectedTargetSchedule
                            ) {

                                event.preventDefault();

                                alert(
                                    'Please select another faculty schedule.'
                                );

                                return;

                            }


                            /*
                            | FINAL SAME DAY CHECK
                            */

                            const requesterDay =
                                normalizeSwapDay(
                                    selectedRequesterSchedule.day
                                );


                            const targetDay =
                                normalizeSwapDay(
                                    selectedTargetSchedule.day
                                );


                            if (
                                requesterDay !==
                                targetDay
                            ) {

                                event.preventDefault();

                                alert(
                                    'Room swap requires the same day.'
                                );

                                return;

                            }


                            /*
                            | FINAL SAME TIME CHECK
                            */

                            const requesterTime =
                                normalizeSwapTime(
                                    selectedRequesterSchedule.time
                                );


                            const targetTime =
                                normalizeSwapTime(
                                    selectedTargetSchedule.time
                                );


                            if (
                                requesterTime !==
                                targetTime
                            ) {

                                event.preventDefault();

                                alert(
                                    'Room swap requires the same time.'
                                );

                                return;

                            }


                            /*
                            | Same room
                            */

                            if (
                                Number(
                                    selectedRequesterSchedule.room_id
                                ) ===
                                Number(
                                    selectedTargetSchedule.room_id
                                )
                            ) {

                                event.preventDefault();

                                alert(
                                    'Both schedules already use the same room.'
                                );

                                return;

                            }


                            /*
                            | Reason
                            */

                            const reasonField =
                                document.getElementById(
                                    'reason'
                                );


                            const selectedReason =
                                document.querySelector(
                                    '.reason-option.selected'
                                );


                            if (
                                selectedReason &&
                                selectedReason.innerText.trim()
                                    === 'Other'
                            ) {

                                reasonField.value =
                                    document
                                        .getElementById(
                                            'otherReason'
                                        )
                                        .value
                                        .trim();

                            }


                            if (
                                !reasonField.value.trim()
                            ) {

                                event.preventDefault();

                                alert(
                                    'Please select or enter a reason.'
                                );

                                return;

                            }


                            /*
                            | IMPORTANT:
                            | No preventDefault here.
                            | The form is allowed to submit.
                            */

                        }
                    );

            </script>

        </main>

    </div>

</div>

<?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/faculty/room-swap.blade.php ENDPATH**/ ?>
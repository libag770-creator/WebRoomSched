@include('footerheader.header')

<style>

    .portal-shell.admin-portal { --buildings-sidebar-width:252px; --buildings-shell-gutter:28px; background:#e9eee8; }
    .portal-shell.admin-portal .wrapper { min-height:100vh; background:#e9eee8; }
    .portal-shell.admin-portal .main-layout { grid-template-columns:var(--buildings-sidebar-width) minmax(0,1fr); align-items:stretch; min-height:100vh; }
    .portal-shell.admin-portal .main-layout > .sidebar.admin-sidebar { position:sticky; top:0; align-self:start; width:var(--buildings-sidebar-width); height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); scrollbar-color:#496a59 transparent; }
    .portal-shell.admin-portal .portal-brand-mark { background:#d8c18c; color:#173b33; box-shadow:0 8px 18px rgba(0,0,0,.18); }
    .portal-shell.admin-portal .portal-brand-copy strong { color:#fffdf5; }
    .portal-shell.admin-portal .portal-brand-copy small { color:#bdc9bd; }
    .portal-shell.admin-portal .faculty-sidebar-section { color:#c3b27f; }
    .portal-shell.admin-portal .sidebar-menu a { color:#d1ddd3; }
    .portal-shell.admin-portal .sidebar-menu a:hover { border-color:rgba(255,255,255,.09); background:rgba(255,255,255,.08); color:#fff; }
    .portal-shell.admin-portal .sidebar-menu li.active > a,.portal-shell.admin-portal .sidebar-menu a[aria-current="page"] { border-color:rgba(220,199,143,.26); background:rgba(220,199,143,.13); color:#fff9e8; }
    .portal-shell.admin-portal .sidebar-menu li.active > a::before,.portal-shell.admin-portal .sidebar-menu a[aria-current="page"]::before { background:#d8c18c; }
    .portal-shell.admin-portal .faculty-nav-icon { color:#b5c5b9; }
    .portal-shell.admin-portal .sidebar-menu li.active .faculty-nav-icon,.portal-shell.admin-portal .sidebar-menu a[aria-current="page"] .faculty-nav-icon { color:#e2ca8d; }
    .portal-shell.admin-portal .faculty-sidebar-footer { border-color:rgba(255,255,255,.16); }
    .portal-shell.admin-portal .faculty-sidebar-footer strong { color:#f3f2e9; }
    .portal-shell.admin-portal .faculty-sidebar-footer span:not(.faculty-footer-indicator) { color:#b9c7bc; }
    .portal-shell.admin-portal .top-header { position:fixed; top:16px; right:var(--buildings-shell-gutter); left:calc(var(--buildings-sidebar-width) + var(--buildings-shell-gutter)); z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    .portal-shell.admin-portal .header-title small { color:#9a8450; font-size:10px; }
    .portal-shell.admin-portal .header-title h3 { color:#173b33; font-size:20px; font-weight:800; }
    .portal-shell.admin-portal .header-action { color:#375448; }
    .portal-shell.admin-portal .sidebar-toggle { top:36px; left:calc(var(--buildings-sidebar-width) - 15px); z-index:1200; border-color:rgba(216,193,140,.52); background:#f8f5eb; color:#224336; }
    .portal-shell.admin-portal.sidebar-collapsed { --buildings-sidebar-width:76px; }
    .portal-shell.admin-portal.sidebar-collapsed .main-layout { grid-template-columns:76px minmax(0,1fr); }
    .portal-shell.admin-portal.sidebar-collapsed .main-layout > .sidebar.admin-sidebar { width:76px; padding-right:10px; padding-left:10px; }
    .portal-shell.admin-portal.sidebar-collapsed .sidebar-toggle { left:61px; }
    .portal-shell.admin-portal .main-layout > .content.buildings-admin-view { min-width:0; min-height:100vh; padding:124px clamp(20px,3vw,42px) 46px; background:radial-gradient(ellipse at 92% 2%,rgba(214,194,143,.22),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .buildings-admin-view,.buildings-admin-view * { box-sizing:border-box; }
    .buildings-admin-view { color:#293d31; font-family:'DM Sans',Arial,sans-serif; font-size:15px; line-height:1.55; }
    .buildings-admin-view > * { max-width:1480px; margin-right:auto; margin-left:auto; }
    .buildings-hero { position:relative; display:flex; align-items:center; justify-content:space-between; gap:28px; min-height:215px; overflow:hidden; margin-top:0; margin-bottom:22px; padding:clamp(28px,4vw,42px); border:1px solid rgba(231,214,173,.25); border-radius:24px; background:radial-gradient(circle at 83% 20%,rgba(215,194,143,.18),transparent 24%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.15); color:#fff; }
    .buildings-hero::after { position:absolute; right:-45px; bottom:-175px; width:340px; height:340px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .buildings-hero-copy { position:relative; z-index:1; max-width:760px; }
    .buildings-hero-eyebrow { display:flex; align-items:center; gap:9px; margin:0 0 13px; color:#e2c987; font-size:12px; font-weight:850; letter-spacing:.15em; }
    .buildings-hero h1 { margin:0; color:#fffdf7; font-family:'Manrope','DM Sans',sans-serif; font-size:clamp(32px,3.5vw,46px); font-weight:850; letter-spacing:-.04em; line-height:1.12; }
    .buildings-hero-description { max-width:660px; margin:14px 0 0; color:#d8e2d8; font-size:16px; font-weight:550; line-height:1.65; }
    .buildings-hero-mark { position:relative; z-index:1; display:grid; flex:0 0 150px; justify-items:center; gap:12px; width:150px; height:150px; align-content:center; border:1px solid rgba(255,255,255,.21); border-radius:50%; background:rgba(255,255,255,.075); color:#e6d39e; }
    .buildings-hero-mark i { font-size:38px; }
    .buildings-hero-mark span { color:#e8e6dc; font-size:10px; font-weight:850; letter-spacing:.12em; text-align:center; }

    .buildings-admin-view .error-message { max-width:1480px; margin:0 auto 18px; padding:15px 18px; border:1px solid #f0ceca; border-radius:13px; background:#fff5f3; color:#853d37; font-size:14px; font-weight:600; }
    .buildings-admin-view .error-message strong { display:block; margin-bottom:5px; font-size:15px; font-weight:850; }
    .buildings-admin-view .error-message ul { margin:5px 0 0; padding-left:22px; }

    .buildings-admin-view .add-form { max-width:1480px; margin:0 auto 20px; padding:22px 24px 24px; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .building-form-step { display:inline-flex; min-height:28px; align-items:center; padding:0 10px; border:1px solid #eadfbf; border-radius:999px; background:#f8f4e8; color:#826e3e; font-size:11px; font-weight:850; letter-spacing:.1em; }
    .buildings-admin-view .add-form h3 { margin:11px 0 5px; color:#173b33; font-family:'Manrope','DM Sans',sans-serif; font-size:22px; font-weight:850; letter-spacing:-.025em; }
    .building-form-instruction { max-width:760px; margin:0 0 16px; color:#596b60; font-size:15px; font-weight:550; line-height:1.65; }
    .building-form-instruction strong { color:#31593e; font-weight:850; }
    .buildings-admin-view .add-form form { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1.35fr) auto; align-items:end; gap:10px 14px; }
    .buildings-admin-view .add-form label { grid-row:1; margin:0 0 -8px; color:#35483c; font-size:14px; font-weight:800; }
    .buildings-admin-view .add-form label:nth-of-type(2) { grid-column:2; }
    .buildings-admin-view .add-form select,.buildings-admin-view .add-form input[type=text] { width:100%; min-width:0; min-height:48px; padding:11px 13px; border:1px solid #d7e0d6; border-radius:10px; outline:none; background:#fff; color:#263c30; font:600 14px 'DM Sans',Arial,sans-serif; transition:border-color .16s ease,box-shadow .16s ease; }
    .buildings-admin-view .add-form select { grid-column:1; grid-row:2; }
    .buildings-admin-view .add-form input[type=text] { grid-column:2; grid-row:2; }
    .buildings-admin-view .add-form select:focus,.buildings-admin-view .add-form input:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.13); }
    .buildings-admin-view .add-form select:invalid { color:#829087; }
    .buildings-admin-view .add-form button { grid-column:3; grid-row:2; white-space:nowrap; }

    .room-finder { max-width:1480px; margin:0 auto 24px; overflow:hidden; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .room-finder__top { padding:20px 23px 17px; border-bottom:1px solid #e9eee7; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .room-finder__title { margin:0; color:#173b33; font-family:'Manrope','DM Sans',sans-serif; font-size:22px; font-weight:850; letter-spacing:-.025em; }
    .room-finder__description { margin:5px 0 0; color:#5c6d62; font-size:14px; font-weight:550; }
    .room-finder__body { padding:19px 23px 22px; }
    .room-finder__steps { display:flex; align-items:center; gap:11px; margin:0 0 18px; color:#586b5f; font-size:13px; font-weight:800; }
    .room-finder__step { display:inline-flex; align-items:center; gap:8px; }
    .room-finder__step-number { display:grid; width:28px; height:28px; place-items:center; border:1px solid #dfe7dc; border-radius:50%; background:#f3f6f1; color:#566a5d; font-size:12px; font-weight:850; }
    .room-finder__step--active .room-finder__step-number { border-color:#285940; background:#285940; color:#fff; }
    .room-finder__arrow { color:#a1aca3; font-size:16px; font-weight:850; }
    .room-finder__grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; }
    .room-finder__field { min-width:0; }
    .room-finder__label { display:block; margin-bottom:7px; color:#35483c; font-size:14px; font-weight:850; }
    .room-finder__required { color:#bd534a; font-weight:900; }
    .room-finder__control { position:relative; display:flex; align-items:center; }
    .room-finder__select,.room-finder__input { display:block; width:100%; min-height:47px; padding:10px 13px; border:1px solid #d7e0d6; border-radius:10px; outline:none; background:#fff; color:#263c30; font:600 14px 'DM Sans',Arial,sans-serif; }
    .room-finder__select:focus,.room-finder__input:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.13); }
    .room-finder__select:disabled,.room-finder__input:disabled { background:#f1f4f0; color:#87938a; cursor:not-allowed; }
    .room-finder__clear { position:absolute; right:7px; display:none; width:32px; height:32px; border:0; border-radius:8px; background:#edf3eb; color:#405b49; font-size:22px; font-weight:800; cursor:pointer; }
    .room-finder__hint { margin-top:6px; color:#718077; font-size:12px; font-weight:550; }
    .room-finder__status { min-height:0; margin-top:14px; color:#53685a; font-size:13px; font-weight:700; }
    .room-finder__status:empty { display:none; }
    .room-finder__message { display:flex; align-items:flex-start; gap:9px; margin:0; padding:11px 13px; border:1px solid #dce8dc; border-radius:10px; background:#f2f7f1; color:#31583c; font-size:13px; font-weight:700; }
    .room-finder__message-icon { flex:0 0 auto; font-size:17px; font-weight:900; }
    .room-search-found { outline:3px solid rgba(55,128,77,.26); outline-offset:2px; }
    .room-search-no-result { color:#a7443d; font-size:14px; font-weight:800; }

    .workflow-dept-heading { display:flex; align-items:flex-start; gap:13px; max-width:1480px; margin:0 auto 13px; padding:18px 20px; border:1px solid #dfe7dd; border-radius:16px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.035); color:#30483a; font-size:16px; font-weight:850; }
    .workflow-dept-heading .step-number { display:grid; flex:0 0 34px; width:34px; height:34px; place-items:center; border:1px solid #dce6d9; border-radius:11px; background:#edf3eb; color:#285940; font-size:15px; font-weight:900; }
    .workflow-dept-heading p { margin:4px 0 0; color:#65756a; font-size:13px; font-weight:550; }
    .workflow-dept-heading.workflow-step-complete .step-number { border-color:#285940; background:#285940; color:#fff; }
    .departments { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:11px; max-width:1480px; margin:0 auto 19px; }
    .dept-btn { position:relative; display:flex; min-height:82px; flex-direction:column; justify-content:center; gap:5px; padding:14px 16px; border:1px solid #dfe7dd; border-radius:14px; background:#fff; color:#2d4537; text-align:left; cursor:pointer; box-shadow:0 5px 15px rgba(31,57,43,.035); transition:transform .16s ease,border-color .16s ease,background .16s ease,box-shadow .16s ease; }
    .dept-btn:hover { transform:translateY(-2px); border-color:#afc5b0; background:#fbfcfa; box-shadow:0 10px 21px rgba(31,57,43,.075); }
    .dept-btn.active { border-color:#4c7c58; background:linear-gradient(115deg,#edf4eb,#fff); box-shadow:inset 4px 0 #34704a,0 8px 20px rgba(31,57,43,.06); }
    .dept-btn-name { color:#294437; font-size:15px; font-weight:850; line-height:1.4; }
    .dept-btn-hint { color:#68796e; font-size:12px; font-weight:650; }
    .no-buildings-step { max-width:1480px; margin:0 auto 18px; padding:17px 19px; border:1px dashed #cdd9cb; border-radius:13px; background:#f7faf5; color:#52665a; font-size:14px; font-weight:650; }

    .dept-content { display:none; max-width:1480px; margin:0 auto; }
    .dept-content.active { display:block; }
    .department-building-guide { display:flex; flex-direction:column; gap:4px; margin:0 0 13px; padding:15px 18px; border:1px solid #e5dfc9; border-radius:13px; background:#f8f6ec; color:#594f35; }
    .department-building-guide strong { font-size:14px; font-weight:850; }
    .department-building-guide span { color:#6d6859; font-size:13px; font-weight:550; }
    .building-card { overflow:hidden; margin:0 0 13px; border:1px solid #dfe7dd; border-radius:16px; background:#fff; box-shadow:0 7px 20px rgba(31,57,43,.04); }
    .building-header { display:flex; align-items:center; justify-content:space-between; gap:15px; padding:16px 18px; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .building-name-area { display:flex; min-width:0; align-items:center; gap:11px; }
    .building-toggle { display:grid; flex:0 0 36px; width:36px; height:36px; place-items:center; border:1px solid #dfe7dc; border-radius:10px; background:#edf3eb; color:#315c3e; cursor:pointer; }
    .building-toggle .arrow { font-size:13px; font-weight:900; transition:transform .16s ease; }
    .building-toggle.open .arrow { transform:rotate(180deg); }
    .building-name-area h3 { margin:0; color:#213f30; font-family:'Manrope','DM Sans',sans-serif; font-size:19px; font-weight:850; letter-spacing:-.02em; line-height:1.35; cursor:pointer; }
    .building-name-area h3:focus-visible,.building-toggle:focus-visible,.dept-btn:focus-visible,.btn:focus-visible { outline:3px solid rgba(72,129,83,.28); outline-offset:2px; }
    .building-actions,.room-actions { display:flex; flex-wrap:wrap; align-items:center; justify-content:flex-end; gap:7px; }
    .building-actions form,.room-actions form { margin:0; }
    .buildings-admin-view .btn { display:inline-flex; min-height:38px; align-items:center; justify-content:center; gap:7px; padding:0 12px; border:1px solid transparent; border-radius:9px; cursor:pointer; font:800 12px 'DM Sans',Arial,sans-serif; text-decoration:none; white-space:nowrap; transition:transform .16s ease,background .16s ease,box-shadow .16s ease; }
    .buildings-admin-view .btn:hover { transform:translateY(-1px); }
    .buildings-admin-view .btn.green { background:#24583d; color:#fff; box-shadow:0 6px 13px rgba(36,88,61,.12); }
    .buildings-admin-view .btn.green:hover { background:#19482f; }
    .buildings-admin-view .btn.blue { border-color:#d7e4e8; background:#edf5f7; color:#315968; }
    .buildings-admin-view .btn.blue:hover { background:#e0eff2; }
    .buildings-admin-view .btn.red { border-color:#f0d9d5; background:#fff7f5; color:#a7443d; }
    .buildings-admin-view .btn.red:hover { background:#ffebe7; }
    .buildings-admin-view .btn.gray { border-color:#e0e6df; background:#f5f7f4; color:#526258; }
    .buildings-admin-view .btn.move-btn { border-color:#e9dfc8; background:#f8f5ec; color:#79683d; }
    .building-add-room[style*="display: none"] { display:none !important; }
    .building-rooms { display:none; padding:18px; border-top:1px solid #e8eee6; background:#fbfcfa; }
    .building-rooms.open { display:block; }
    .building-step-note { margin:0 0 14px; padding:12px 14px; border:1px solid #e5dfc9; border-radius:10px; background:#f8f6ec; color:#63593c; font-size:13px; font-weight:600; }
    .building-step-note strong { color:#554a2e; font-weight:850; }
    .room-section { padding:16px; border:1px solid #e3eae1; border-radius:13px; background:#fff; }
    .room-title { color:#234532; font-family:'Manrope','DM Sans',sans-serif; font-size:17px; font-weight:850; }
    .room-order-note { margin:3px 0 13px; color:#718077; font-size:12px; font-weight:550; }
    .room-card { margin-top:10px; padding:15px; border:1px solid #e3e9e1; border-radius:12px; background:#fff; }
    .room-header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding-bottom:12px; border-bottom:1px solid #edf1eb; }
    .room-header > strong { color:#2d4738; font-size:15px; font-weight:850; }
    .room-details { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:9px; padding-top:13px; }
    .room-details > div { min-width:0; padding:9px 10px; border:1px solid #edf1eb; border-radius:9px; background:#fafbf9; color:#58695e; font-size:13px; font-weight:600; overflow-wrap:anywhere; }
    .room-details strong { color:#334b3b; font-weight:850; }
    .no-rooms { padding:20px 14px; border:1px dashed #d9e2d7; border-radius:10px; color:#65756a; font-size:14px; font-weight:700; text-align:center; }
    .room-search-hidden { display:none !important; }
    .room-search-highlight { border-color:#76a882 !important; background:#f4faf2 !important; box-shadow:0 0 0 3px rgba(69,132,78,.11); }
    .room-finder__search-control { max-width:760px; }
    .room-finder__status { margin-top:12px; }
    .room-search-summary { margin:0 0 8px; color:#52665a; font-size:13px; font-weight:700; }
    .room-search-results { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:8px; }
    .room-search-result { display:flex; min-width:0; flex-direction:column; gap:3px; padding:10px 12px; border:1px solid #dfe7dd; border-radius:10px; background:#fff; color:#294535; text-align:left; cursor:pointer; }
    .room-search-result:hover,.room-search-result:focus-visible { border-color:#85a98b; background:#f3f8f1; outline:2px solid rgba(72,129,83,.16); }
    .room-search-result strong { font-size:14px; font-weight:850; }
    .room-search-result span { overflow:hidden; color:#68786d; font-size:12px; text-overflow:ellipsis; white-space:nowrap; }
    .quick-room-form { display:grid; grid-template-columns:minmax(0,1fr) 150px auto; align-items:end; gap:10px; margin:4px 0 14px; padding:14px; border:1px solid #e2e9df; border-radius:12px; background:#f7faf5; }
    .quick-room-form label { display:block; margin:0 0 5px; color:#35483c; font-size:12px; font-weight:800; }
    .quick-room-form input { width:100%; min-width:0; min-height:42px; padding:9px 11px; border:1px solid #d7e0d6; border-radius:9px; outline:none; background:#fff; color:#263c30; font:600 14px 'DM Sans',Arial,sans-serif; }
    .quick-room-form input:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.13); }
    .quick-room-form-note { grid-column:1/-1; margin:-2px 0 0; color:#6b796f; font-size:12px; }
    .management-toolbar { display:flex; align-items:center; gap:16px; margin:0 auto 20px; }
    .management-tabs { display:flex; width:min(420px,100%); flex:0 0 auto; justify-content:center; gap:6px; margin:0; padding:6px; border:1px solid #dfe7dd; border-radius:14px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.06); }
    .management-search { position:relative; display:flex; min-width:220px; flex:1 1 360px; align-items:center; }
    .management-search input { width:100%; min-height:48px; padding:10px 44px 10px 42px; border:1px solid #d9e3d8; border-radius:12px; background:#fff; color:#294535; font:650 14px 'DM Sans',Arial,sans-serif; outline:none; box-shadow:0 6px 18px rgba(31,57,43,.04); }
    .management-search input:focus { border-color:#71977a; box-shadow:0 0 0 3px rgba(73,123,83,.12); }
    .management-search > i { position:absolute; left:15px; color:#718275; }
    .management-search button { position:absolute; right:8px; width:32px; height:32px; border:0; border-radius:8px; background:#f0f4ef; color:#53675a; font-size:20px; cursor:pointer; }
    .management-search button:hover { background:#e5eee3; color:#24583d; }
    .management-tab { display:flex; min-height:46px; flex:1 1 0; align-items:center; justify-content:center; gap:9px; border:0; border-radius:10px; background:transparent; color:#627267; font:800 14px 'DM Sans',Arial,sans-serif; cursor:pointer; transition:background .16s ease,color .16s ease,box-shadow .16s ease; }
    .management-tab:hover { background:#f3f7f1; color:#294535; }
    .management-tab.is-active { background:#24583d; color:#fff; box-shadow:0 5px 12px rgba(36,88,61,.2); }
    .management-tab i { font-size:15px; }
    .management-panel[hidden] { display:none !important; }
    .management-panel { max-width:1480px; margin:0 auto 24px; }
    .management-panel > .workflow-dept-heading { margin-top:0; }
    .building-add-card { margin-bottom:20px !important; }
    .college-picker { display:flex; flex-wrap:wrap; gap:9px; margin:0 0 16px; }
    .college-picker-button { display:flex; min-height:42px; align-items:center; gap:8px; padding:9px 13px; border:1px solid #dfe7dd; border-radius:10px; background:#fff; color:#41564a; font:750 13px 'DM Sans',Arial,sans-serif; cursor:pointer; }
    .college-picker-button:hover,.college-picker-button.is-selected { border-color:#71977a; background:#edf5ec; color:#24583d; }
    .college-picker-button.is-selected { box-shadow:0 0 0 2px rgba(55,108,66,.1); }
    .selection-hint { margin:0 0 14px; padding:12px 14px; border:1px dashed #cfdace; border-radius:10px; color:#68786d; background:#fafcf9; font-size:13px; }
    .building-add-card.is-locked { opacity:.68; }
    .building-add-card fieldset:disabled { opacity:.62; }
    .college-group-card[data-college-id],.rooms-college-group[data-department-id] { display:none; }
    .college-group-card.is-visible,.rooms-college-group.is-visible { display:block; }
    .room-building-select { max-width:480px; margin:0 0 16px; }
    .room-building-select label { display:block; margin:0 0 6px; color:#35483c; font-size:13px; font-weight:800; }
    .room-building-select select { width:100%; min-height:44px; padding:9px 12px; border:1px solid #d7e0d6; border-radius:10px; background:#fff; color:#294535; font:650 14px 'DM Sans',Arial,sans-serif; }
    .room-add-card.is-locked { opacity:.65; }
    .room-building-group[data-building-id] { display:none; }
    .room-building-group.is-visible { display:block; }
    .college-building-list,.rooms-college-list { display:grid; gap:16px; }
    .college-group-card,.rooms-college-group { overflow:visible; border:1px solid #dfe7dd; border-radius:16px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.04); }
    .college-group-header { display:flex; align-items:center; gap:12px; padding:16px 18px; border-bottom:1px solid #e9eee7; background:linear-gradient(110deg,#f6f8f3,#fff); border-radius:16px 16px 0 0; }
    .college-group-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; border:1px solid #dfe7dc; border-radius:12px; background:#edf3eb; color:#315c3e; }
    .college-group-header h2 { margin:0; color:#213f30; font:850 17px 'Manrope','DM Sans',sans-serif; }
    .college-group-header p { margin:3px 0 0; color:#708077; font-size:12px; font-weight:650; }
    .building-list { display:grid; gap:9px; padding:13px; }
    .building-row-card { display:flex; min-width:0; align-items:center; gap:13px; padding:14px; border:1px solid #e5ebe3; border-radius:12px; background:#fff; transition:background .15s ease,border-color .15s ease; }
    .building-row-card:hover { border-color:#cbd9c9; background:#fbfcfa; }
    .building-row-icon { display:grid; flex:0 0 42px; width:42px; height:42px; place-items:center; border-radius:12px; background:#f2f5ef; color:#48634f; }
    .building-row-copy { min-width:0; flex:1 1 auto; }
    .building-row-copy h3 { margin:0; color:#294535; font-size:15px; font-weight:850; }
    .building-row-copy p { margin:3px 0 0; color:#758278; font-size:12px; font-weight:650; }
    .room-building-group { overflow:hidden; margin:13px; border:1px solid #e1e8df; border-radius:14px; background:#fff; }
    .room-building-group.room-search-target { border-color:#85a98b; box-shadow:0 0 0 3px rgba(69,132,78,.11); }
    .room-building-header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:13px 15px; background:#f6f8f3; }
    .room-building-header h3 { margin:0; color:#294535; font-size:15px; font-weight:850; }
    .room-building-header span { color:#728076; font-size:12px; font-weight:650; }
    .room-building-count { white-space:nowrap; }
    .room-create-form { margin:12px; padding:15px; border:1px solid #e3eade; border-radius:12px; background:#fafbf8; }
    .room-form-heading { display:flex; flex-direction:column; gap:3px; margin-bottom:12px; }
    .room-form-heading strong { color:#294535; font-size:14px; font-weight:850; }
    .room-form-heading span { color:#728076; font-size:12px; font-weight:600; }
    .room-form-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:11px; }
    .room-form-field { min-width:0; }
    .room-form-field label,.room-feature-field legend { display:block; margin:0 0 5px; color:#46594b; font-size:12px; font-weight:800; }
    .room-form-field label span { color:#849087; font-weight:600; }
    .room-form-field input,.room-form-field select,.room-form-field textarea { width:100%; min-width:0; min-height:41px; padding:9px 10px; border:1px solid #d7e0d6; border-radius:9px; outline:none; background:#fff; color:#263c30; font:600 13px 'DM Sans',Arial,sans-serif; }
    .room-form-field textarea { min-height:42px; resize:vertical; }
    .room-form-field input:focus,.room-form-field select:focus,.room-form-field textarea:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.12); }
    .room-form-field-wide { grid-column:span 2; }
    .room-feature-field { display:flex; min-width:0; align-items:center; flex-wrap:wrap; gap:8px; margin:0; padding:7px 9px 9px; border:1px solid #e2e8df; border-radius:9px; }
    .room-feature-field legend { padding:0 3px; }
    .room-feature-field label { display:inline-flex; min-height:29px; align-items:center; gap:5px; padding:4px 7px; border:1px solid #e2e8df; border-radius:8px; background:#fff; color:#526458; font-size:11px; font-weight:750; white-space:nowrap; }
    .room-feature-field input { width:15px; height:15px; margin:0; accent-color:#285940; }
    .room-form-footer { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:12px; padding-top:11px; border-top:1px solid #e8eee6; }
    .room-form-footer > span { color:#748177; font-size:11px; font-weight:650; }
    .room-list { display:grid; gap:8px; padding:0 12px 12px; }
    .room-row-card { display:flex; min-width:0; align-items:flex-start; gap:11px; margin:0; padding:12px; }
    .room-row-main { display:flex; min-width:0; flex:1 1 auto; align-items:center; gap:10px; }
    .room-row-icon { display:grid; flex:0 0 36px; width:36px; height:36px; place-items:center; border-radius:10px; background:#f2f5ef; color:#48634f; }
    .room-row-copy { min-width:110px; flex:1 1 auto; }
    .room-row-copy .room-header { display:block; padding:0; border:0; }
    .room-row-copy .room-header strong { color:#294535; font-size:14px; }
    .room-row-copy p { margin:3px 0 0; color:#79867d; font-size:11px; font-weight:650; }
    .room-row-copy p span { padding:0 3px; color:#b1bab1; }
    .room-feature-chips { display:flex; justify-content:flex-end; flex-wrap:wrap; gap:5px; }
    .room-feature-chips span { display:inline-flex; min-height:24px; align-items:center; padding:0 8px; border:1px solid #e2e9df; border-radius:999px; background:#f7f9f5; color:#526458; font-size:10px; font-weight:750; white-space:nowrap; }
    .room-row-description { margin:8px 0 0 46px; color:#69776d; font-size:12px; line-height:1.5; }
    .rooms-empty-inline { margin:0 12px 12px; padding:14px; border:1px dashed #d9e2d7; border-radius:10px; color:#718077; font-size:12px; font-weight:700; text-align:center; }
    .management-empty { display:grid; justify-items:center; gap:7px; padding:27px 16px; color:#77847a; text-align:center; }
    .management-empty > i { color:#829584; font-size:22px; }
    .management-empty strong { color:#3e5546; font-size:14px; font-weight:850; }
    .management-empty span { font-size:12px; }
    .ba-actions { position:relative; display:flex; flex:0 0 auto; align-items:center; justify-content:flex-end; }
    .ba-menu-trigger { display:grid; width:38px; height:38px; place-items:center; border:1px solid #dfe7dc; border-radius:10px; background:#fff; color:#375442; font-size:16px; cursor:pointer; transition:background .16s ease,border-color .16s ease,transform .16s ease; }
    .ba-menu-trigger:hover,.ba-menu-trigger[aria-expanded="true"] { transform:translateY(-1px); border-color:#b8cbb8; background:#f1f6ef; }
    .ba-menu-trigger:focus-visible,.ba-menu-item:focus-visible { outline:3px solid rgba(73,123,83,.24); outline-offset:2px; }
    .ba-actions-menu { position:fixed; z-index:3200; display:none; width:min(228px,calc(100vw - 24px)); max-height:min(320px,calc(100vh - 24px)); overflow:auto; padding:6px; border:1px solid #dfe7dc; border-radius:12px; background:#fff; box-shadow:0 16px 40px rgba(24,54,37,.18); }
    .ba-actions-menu.is-open { display:grid; gap:3px; }
    .ba-menu-item { display:flex; width:100%; min-height:40px; align-items:center; gap:10px; padding:0 10px; border:0; border-radius:8px; background:transparent; color:#344b3b; font:750 13px 'DM Sans',Arial,sans-serif; text-align:left; text-decoration:none; cursor:pointer; }
    .ba-menu-item:hover { background:#f1f6ef; color:#1f5134; }
    .ba-menu-item > i { width:17px; color:#718278; text-align:center; }
    .ba-menu-item.danger { color:#a7443d; }
    .ba-menu-item.danger > i { color:#b65b50; }
    .ba-menu-form { margin:0; }
    .ba-menu-separator { height:1px; margin:4px 2px; background:#edf1eb; }
    .new-building-highlight { outline:3px solid rgba(74,137,86,.22); outline-offset:2px; }

    .buildings-admin-view .modal { position:fixed; inset:0; z-index:3000; display:none; overflow:auto; padding:clamp(18px,5vh,54px) 18px; background:rgba(15,34,26,.56); backdrop-filter:blur(5px); }
    .buildings-admin-view .modal-content { position:relative; width:min(540px,100%); max-height:calc(100vh - 42px); overflow:auto; margin:0 auto; padding:27px; border:1px solid rgba(255,255,255,.7); border-radius:20px; background:#fff; box-shadow:0 24px 70px rgba(9,35,22,.26); color:#293d31; }
    .buildings-admin-view .modal-content h3 { margin:0 0 8px; color:#183c2e; font-family:'Manrope','DM Sans',sans-serif; font-size:23px; font-weight:850; letter-spacing:-.03em; }
    .buildings-admin-view .modal-content > p { margin:0 0 17px; color:#65746a; font-size:14px; font-weight:600; line-height:1.6; }
    .buildings-admin-view .modal-step-instruction { padding:11px 13px; border:1px solid #e5dfc9; border-radius:10px; background:#f8f6ec; color:#64593b !important; }
    .buildings-admin-view .modal-content label { display:block; margin:14px 0 7px; color:#35483c; font-size:14px; font-weight:800; }
    .buildings-admin-view .modal-content input:not([type=checkbox]):not([type=hidden]),.buildings-admin-view .modal-content select,.buildings-admin-view .modal-content textarea { display:block; width:100%; min-height:47px; padding:10px 12px; border:1px solid #d7e0d6; border-radius:10px; outline:none; background:#fff; color:#263c30; font:600 14px 'DM Sans',Arial,sans-serif; }
    .buildings-admin-view .modal-content textarea { min-height:94px; resize:vertical; }
    .buildings-admin-view .modal-content input:focus,.buildings-admin-view .modal-content select:focus,.buildings-admin-view .modal-content textarea:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.13); }
    .buildings-admin-view .checkbox-group { display:flex; flex-wrap:wrap; gap:12px; margin:15px 0; }
    .buildings-admin-view .modal-content .checkbox-group label { display:flex; align-items:center; gap:9px; margin:0; padding:10px 12px; border:1px solid #e3e9e1; border-radius:9px; background:#f8faf7; font-size:14px; font-weight:750; }
    .buildings-admin-view .checkbox-group input[type=checkbox] { width:17px; height:17px; accent-color:#285940; }
    .selected-building-box { display:flex; flex-direction:column; gap:3px; margin:14px 0 9px; padding:12px 14px; border:1px solid #dce7d9; border-radius:11px; background:#f3f7f1; }
    .selected-building-label { color:#748177; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .selected-building-box strong { color:#2a4a36; font-size:15px; font-weight:850; }
    .move-description strong { color:#294b36; font-size:15px; font-weight:850; }
    .move-warning { margin-top:14px; padding:12px 13px; border:1px solid #ebdfc2; border-radius:10px; background:#fbf7ec; color:#665b3d; font-size:13px; font-weight:650; line-height:1.55; }
    .modal-buttons { display:flex; justify-content:flex-end; flex-wrap:wrap; gap:9px; margin-top:21px; padding-top:16px; border-top:1px solid #edf1eb; }
    .confirm-delete-content { max-width:430px !important; text-align:center; }
    .delete-confirm-icon { display:grid; width:54px; height:54px; place-items:center; margin:0 auto 14px; border:1px solid #f0d9d5; border-radius:17px; background:#fff3f1; color:#b14e45; font-size:25px; font-weight:900; }
    .confirm-delete-content .delete-confirm-note { margin-top:-8px !important; color:#9a6c66 !important; font-size:12px !important; font-weight:650 !important; }
    .confirm-delete-content .modal-buttons { justify-content:center; }
    .room-finder__control:has(.room-finder__clear[style*="display: block"]) .room-finder__input { padding-right:48px; }

    @media(max-width:1120px) {
        .portal-shell.admin-portal { --buildings-sidebar-width:226px; --buildings-shell-gutter:22px; }
        .portal-shell.admin-portal.sidebar-collapsed { --buildings-sidebar-width:76px; }
        .portal-shell.admin-portal .main-layout { grid-template-columns:var(--buildings-sidebar-width) minmax(0,1fr); }
        .portal-shell.admin-portal .main-layout > .content.buildings-admin-view { padding-right:24px; padding-left:24px; }
        .buildings-admin-view .add-form form { grid-template-columns:minmax(0,1fr) minmax(0,1fr); }
        .buildings-admin-view .add-form label:nth-of-type(2) { grid-column:2; }
        .buildings-admin-view .add-form button { grid-column:1 / -1; grid-row:3; justify-self:start; }
        .room-finder__grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .room-finder__field:last-child { grid-column:1 / -1; }
    }
    @media(max-width:760px) {
        .portal-shell.admin-portal .top-header { position:sticky; top:0; right:auto; left:auto; min-height:64px; margin:0; padding:9px 12px 9px 54px; border:0; border-radius:0 0 15px 15px; box-shadow:0 5px 18px rgba(31,57,43,.08); }
        .portal-shell.admin-portal .header-title h3 { font-size:17px; }
        .portal-shell.admin-portal .wrapper,.portal-shell.admin-portal .main-layout { min-height:calc(100vh - 64px); }
        .portal-shell.admin-portal .main-layout { display:block; }
        .portal-shell.admin-portal .main-layout > .sidebar.admin-sidebar { position:fixed; top:0; bottom:0; left:0; z-index:1100; width:262px; height:100dvh; min-height:0; padding:20px 15px; transform:translateX(0); }
        .portal-shell.admin-portal.sidebar-collapsed .main-layout > .sidebar.admin-sidebar { width:262px; transform:translateX(-110%); }
        .portal-shell.admin-portal .sidebar-backdrop { position:fixed; inset:0; z-index:1050; }
        .portal-shell.admin-portal .sidebar-toggle,.portal-shell.admin-portal.sidebar-collapsed .sidebar-toggle { top:17px; left:14px; z-index:1200; }
        .portal-shell.admin-portal:not(.sidebar-collapsed) .sidebar-toggle { top:17px; left:270px; }
        .portal-shell.admin-portal .main-layout > .content.buildings-admin-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .buildings-hero { align-items:flex-start; min-height:0; padding:28px 24px; }
        .buildings-hero-mark { flex-basis:112px; width:112px; height:112px; }
        .buildings-hero-mark i { font-size:29px; }
        .buildings-hero-mark span { font-size:9px; }
        .building-header,.room-header { align-items:flex-start; flex-direction:column; }
        .building-actions,.room-actions { justify-content:flex-start; }
        .room-details { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .room-form-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:560px) {
        .buildings-hero { flex-direction:column; gap:18px; }
        .buildings-hero-mark { display:none; }
        .buildings-hero-description { font-size:14px; }
        .buildings-admin-view .add-form { padding:19px; }
        .buildings-admin-view .add-form form { grid-template-columns:minmax(0,1fr); gap:8px; }
        .buildings-admin-view .add-form label { grid-row:auto; margin:10px 0 -3px; }
        .buildings-admin-view .add-form label:nth-of-type(2) { grid-column:1; }
        .buildings-admin-view .add-form select,.buildings-admin-view .add-form input[type=text] { grid-column:1; grid-row:auto; }
        .buildings-admin-view .add-form button { grid-column:1; grid-row:auto; justify-self:stretch; margin-top:7px; }
        .room-finder__top,.room-finder__body { padding-right:17px; padding-left:17px; }
        .room-finder__grid { grid-template-columns:minmax(0,1fr); }
        .room-finder__field:last-child { grid-column:auto; }
        .room-finder__steps { gap:6px; font-size:11px; }
        .room-finder__step { gap:5px; }
        .room-finder__step-number { width:25px; height:25px; }
        .departments { grid-template-columns:minmax(0,1fr); }
        .building-header,.building-rooms { padding:14px; }
        .building-name-area h3 { font-size:17px; }
        .building-actions,.room-actions { width:100%; display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); }
        .building-actions form,.room-actions form { width:100%; }
        .building-actions form .btn,.room-actions form .btn { width:100%; }
        .room-details { grid-template-columns:minmax(0,1fr); }
        .management-toolbar { align-items:stretch; flex-direction:column; gap:10px; }
        .management-tabs { width:100%; }
        .management-tab { min-height:42px; font-size:13px; }
        .room-form-grid { grid-template-columns:minmax(0,1fr); }
        .room-form-field-wide { grid-column:auto; }
        .room-row-card { align-items:flex-start; flex-wrap:wrap; }
        .room-row-main { flex:1 1 calc(100% - 50px); flex-wrap:wrap; }
        .room-feature-chips { width:100%; justify-content:flex-start; padding-left:46px; }
        .room-row-description { margin-left:46px; }
        .room-form-footer { align-items:flex-start; flex-direction:column; }
        .quick-room-form { grid-template-columns:minmax(0,1fr) 110px; }
        .quick-room-form button { grid-column:1/-1; width:100%; }
        .room-section { padding:12px; }
        .room-card { padding:12px; }
        .buildings-admin-view .modal-content { padding:23px 19px; }
        .modal-buttons { justify-content:stretch; }
        .modal-buttons .btn { flex:1 1 auto; }
    }
    @media(prefers-reduced-motion:reduce) {
        .buildings-admin-view * { scroll-behavior:auto !important; animation-duration:.01ms !important; transition-duration:.01ms !important; }
    }

</style>

<div class="wrapper">
    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content buildings-admin-view" data-initial-department="{{ old('department_id', session('room_department_id', '')) }}" data-initial-building="{{ old('building_id', session('room_building_id', '')) }}" data-new-building-department="{{ session('building_department_id', '') }}">

        

            <!-- TITLE -->

            <header class="buildings-hero">
                <div class="buildings-hero-copy">
                    <p class="buildings-hero-eyebrow">CAMPUS FACILITIES</p>
                    <h1>Manage Buildings and Rooms</h1>
                    <p class="buildings-hero-description">Follow the steps below to organize classrooms by college and building.</p>
                </div>
                <div class="buildings-hero-mark" aria-hidden="true"><i class="fa-solid fa-building-columns"></i><span>LOCATION DIRECTORY</span></div>
            </header>


            <!-- Success message is handled by the shared header to prevent duplicate message boxes. -->

            <div class="management-toolbar"><div class="management-tabs" role="tablist" aria-label="Manage campus spaces">
                <button type="button" class="management-tab is-active" id="buildingsTab" role="tab" aria-selected="true" aria-controls="buildingsPanel" data-management-tab="buildings">
                    <i class="fa-solid fa-building" aria-hidden="true"></i><span>Buildings</span>
                </button>
                <button type="button" class="management-tab" id="roomsTab" role="tab" aria-selected="false" aria-controls="roomsPanel" data-management-tab="rooms" tabindex="-1">
                    <i class="fa-solid fa-door-open" aria-hidden="true"></i><span>Rooms</span>
                </button>
            </div><label class="management-search"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><input type="search" id="managementSearchInput" placeholder="Search buildings…" autocomplete="off" aria-label="Search buildings"><button type="button" id="managementSearchClear" aria-label="Clear search">×</button></label></div>

            <section class="management-panel is-active" id="buildingsPanel" role="tabpanel" aria-labelledby="buildingsTab" data-management-panel="buildings">
                @if($errors->any())
                    <div class="error-message"><strong>Please fix the following:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <div class="workflow-dept-heading"><span class="step-number">1</span><div><strong>Choose a college</strong><p>Select a college to manage its buildings.</p></div></div>
                <div class="college-picker" role="group" aria-label="Choose a college for buildings">
                    @foreach($departments as $dept)
                        <button type="button" class="college-picker-button" data-building-college="{{ $dept->id }}"><i class="fa-solid fa-building-columns" aria-hidden="true"></i>{{ $dept->name }}</button>
                    @endforeach
                </div>
                <div class="add-form building-add-card is-locked" id="buildingAddCard">
                    <div class="building-form-step">ADD BUILDING</div><h3>Create a building</h3>
                    <p class="building-form-instruction">Choose a college above before adding a building.</p>
                    <form action="{{ route('admin.buildings.store') }}" method="POST">
                        @csrf<input type="hidden" name="manage_view" value="buildings">
                        <input type="hidden" id="buildingDepartment" name="department_id" value="{{ old('department_id') }}" required>
                        <fieldset id="buildingFields" disabled style="border:0;padding:0;margin:0">
                            <label for="newBuildingName">Building name</label><input id="newBuildingName" type="text" name="name" placeholder="Example: Flavier Building" value="{{ old('name') }}" maxlength="255" required>
                            <button type="submit" class="btn green"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add Building</button>
                        </fieldset>
                    </form>
                </div>

                <div class="college-building-list">
                    @forelse($departments as $dept)
                        <section class="college-group-card" data-college-id="{{ $dept->id }}" data-college-name="{{ strtolower($dept->name) }}" aria-labelledby="buildingCollege-{{ $dept->id }}">
                            <header class="college-group-header">
                                <span class="college-group-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                                <div><h2 id="buildingCollege-{{ $dept->id }}">{{ $dept->name }}</h2><p>{{ $dept->buildings->count() }} {{ $dept->buildings->count() === 1 ? 'building' : 'buildings' }}</p></div>
                            </header>
                            <div class="building-list">
                                @forelse($dept->buildings as $building)
                                    <article class="building-row-card" data-building-id="{{ $building->id }}">
                                        <div class="building-row-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></div>
                                        <div class="building-row-copy"><h3>{{ $building->name }}</h3><p>{{ $building->rooms->count() }} {{ $building->rooms->count() === 1 ? 'room' : 'rooms' }} in this building</p></div>
                                        <div class="ba-actions">
                                            <button type="button" class="ba-menu-trigger" aria-label="Actions for {{ $building->name }}" aria-haspopup="menu" aria-expanded="false" onclick="baToggleActionsMenu(this)"><i class="fa-solid fa-ellipsis-vertical" aria-hidden="true"></i></button>
                                            <div class="ba-actions-menu" role="menu">
                                                <button type="button" class="ba-menu-item" role="menuitem" onclick="openEditBuilding({{ $building->id }},{{ $dept->id }},@js($building->name))"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>Edit building</button>
                                                <span class="ba-menu-separator" aria-hidden="true"></span>
                                                <form action="{{ route('admin.buildings.delete',$building->id) }}" method="POST" data-confirm-delete="Delete this building and its rooms?" class="ba-menu-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="ba-menu-item danger" role="menuitem"><i class="fa-solid fa-trash" aria-hidden="true"></i>Delete building</button>
                                                </form>
                                            </div>
                                        </div>
                                    </article>
                                @empty
                                    <div class="management-empty"><i class="fa-regular fa-building" aria-hidden="true"></i><strong>No buildings in this college yet</strong><span>Use the form above to add its first building.</span></div>
                                @endforelse
                            </div>
                        </section>
                    @empty
                        <div class="management-empty"><i class="fa-solid fa-building-columns" aria-hidden="true"></i><strong>No colleges available</strong><span>Add a college before creating buildings.</span></div>
                    @endforelse
                </div>
            </section>

            <section class="management-panel" id="roomsPanel" role="tabpanel" aria-labelledby="roomsTab" data-management-panel="rooms" hidden>
                @if($errors->any())
                    <div class="error-message"><strong>Please fix the following:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <div class="workflow-dept-heading"><span class="step-number">1</span><div><strong>Choose a college</strong><p>Then choose one of its buildings to view and manage rooms.</p></div></div>
                <div class="college-picker" role="group" aria-label="Choose a college for rooms">
                    @foreach($departments as $dept)
                        <button type="button" class="college-picker-button" data-room-college="{{ $dept->id }}"><i class="fa-solid fa-building-columns" aria-hidden="true"></i>{{ $dept->name }}</button>
                    @endforeach
                </div>
                <div class="room-building-select"><label for="roomBuildingChoice">Building</label><select id="roomBuildingChoice" disabled><option value="">Select a college first</option>
                    @foreach($departments as $dept) @foreach($dept->buildings as $building)
                        <option value="{{ $building->id }}" data-department-id="{{ $dept->id }}">{{ $building->name }}</option>
                    @endforeach @endforeach
                </select></div>
                <form action="{{ route('admin.rooms.store') }}" method="POST" class="room-create-form room-add-card is-locked" id="roomAddForm">
                    @csrf<input type="hidden" name="manage_view" value="rooms">
                    <input type="hidden" name="department_id" id="roomDepartmentId" value="{{ old('department_id') }}">
                    <input type="hidden" name="building_id" id="roomBuildingId" value="{{ old('building_id') }}">
                    <div class="room-form-heading"><strong>Add a room</strong><span id="roomAddContext">Choose a college and building to enable this form.</span></div>
                    <fieldset id="roomFields" disabled style="border:0;padding:0;margin:0"><div class="room-form-grid">
                        <div class="room-form-field"><label for="roomName">Room name or number</label><input id="roomName" type="text" name="room_name" placeholder="Example: Room 101" maxlength="255" required></div>
                        <div class="room-form-field"><label for="roomCapacity">Capacity</label><input id="roomCapacity" type="number" name="capacity" min="1" placeholder="e.g. 40" required></div>
                        <div class="room-form-field"><label for="roomComputers">Computers</label><input id="roomComputers" type="number" name="computers" min="0" placeholder="0"></div>
                        <div class="room-form-field"><label for="roomPurpose">Room type or purpose</label><select id="roomPurpose" name="purpose"><option value="">Choose a room type</option><option value="Classroom">Classroom</option><option value="Laboratory">Laboratory</option><option value="Computer Laboratory">Computer Laboratory</option><option value="Lecture Hall">Lecture Hall</option><option value="Seminar Room">Seminar Room</option><option value="Other">Other</option></select></div>
                        <fieldset class="room-feature-field"><legend>Facilities</legend><label><input type="checkbox" name="has_tv" value="1"> TV</label><label><input type="checkbox" name="has_projector" value="1"> Projector</label></fieldset>
                        <div class="room-form-field room-form-field-wide"><label for="roomDescription">Notes <span>Optional</span></label><textarea id="roomDescription" name="description" rows="2" placeholder="Accessibility, equipment, or other room notes"></textarea></div>
                    </div><div class="room-form-footer"><span id="roomFormLocation">Room will be listed under the selected college and building.</span><button type="submit" class="btn green"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add Room</button></div></fieldset>
                </form>

                <div class="rooms-college-list">
                    @forelse($departments as $dept)
                        <section class="rooms-college-group" data-department-id="{{ $dept->id }}" aria-labelledby="roomsCollege-{{ $dept->id }}">
                            <header class="college-group-header"><span class="college-group-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span><div><h2 id="roomsCollege-{{ $dept->id }}">{{ $dept->name }}</h2><p>{{ $dept->buildings->sum(fn($building) => $building->rooms->count()) }} rooms across {{ $dept->buildings->count() }} {{ $dept->buildings->count() === 1 ? 'building' : 'buildings' }}</p></div></header>
                            @forelse($dept->buildings as $building)
                                <section class="room-building-group" id="rooms-{{ $building->id }}" data-building-id="{{ $building->id }}" data-building-name="{{ strtolower($building->name) }}" data-department-id="{{ $dept->id }}">
                                    <header class="room-building-header"><div><h3>{{ $building->name }}</h3><span>{{ $dept->name }}</span></div><span class="room-building-count">{{ $building->rooms->count() }} {{ $building->rooms->count() === 1 ? 'room' : 'rooms' }}</span></header>
                                    <div class="room-list">
                                        @forelse($building->rooms->sortBy('room_name') as $room)
                                            <article class="room-card room-row-card" data-room-id="{{ $room->id }}" data-room-name="{{ strtolower($room->room_name) }}" data-building-name="{{ strtolower($building->name) }}" data-building-id="{{ $building->id }}">
                                                <div class="room-row-main"><span class="room-row-icon"><i class="fa-solid {{ $room->purpose && str_contains(strtolower($room->purpose),'lab') ? 'fa-flask' : 'fa-door-closed' }}" aria-hidden="true"></i></span><div class="room-row-copy"><div class="room-header"><strong>{{ $room->room_name }}</strong></div><p>{{ $dept->name }} <span>·</span> {{ $building->name }}</p></div>
                                                    <div class="room-feature-chips">
                                                        <span>{{ $room->capacity ?? '—' }} seats</span>
                                                        @if($room->purpose)
                                                            <span>{{ $room->purpose }}</span>
                                                        @endif
                                                        @if($room->computers)
                                                            <span>{{ $room->computers }} computers</span>
                                                        @endif
                                                        @if($room->has_tv)
                                                            <span>TV</span>
                                                        @endif
                                                        @if($room->has_projector)
                                                            <span>Projector</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($room->description)
                                                    <p class="room-row-description">{{ $room->description }}</p>
                                                @endif
                                                <div class="ba-actions">
                                                    <button type="button" class="ba-menu-trigger" aria-label="Actions for {{ $room->room_name }}" aria-haspopup="menu" aria-expanded="false" onclick="baToggleActionsMenu(this)"><i class="fa-solid fa-ellipsis-vertical" aria-hidden="true"></i></button>
                                                    <div class="ba-actions-menu" role="menu">
                                                        <button type="button" class="ba-menu-item" role="menuitem" onclick="openEditRoom({{ $room->id }},{{ $dept->id }},{{ $building->id }},@js($room->room_name),@js($room->capacity),{{ $room->has_tv ? 'true' : 'false' }},{{ $room->has_projector ? 'true' : 'false' }},@js($room->computers),@js($room->purpose),@js($room->description))"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>Edit room</button>
                                                        <button type="button" class="ba-menu-item" role="menuitem" data-room-id="{{ $room->id }}" data-room-name="{{ $room->room_name }}" onclick="openMoveRoom(this)"><i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i>Move to another building</button>
                                                        <span class="ba-menu-separator" aria-hidden="true"></span>
                                                        <form action="{{ route('admin.rooms.delete',$room->id) }}" method="POST" data-confirm-delete="Delete this room?" class="ba-menu-form">@csrf @method('DELETE')<button type="submit" class="ba-menu-item danger" role="menuitem"><i class="fa-solid fa-trash" aria-hidden="true"></i>Delete room</button></form>
                                                    </div>
                                                </div>
                                            </article>
                                        @empty
                                            <div class="rooms-empty-inline">No rooms in this building yet. Choose this building above, then use the Add a room form.</div>
                                        @endforelse
                                    </div>
                                </section>
                            @empty
                                <div class="management-empty"><i class="fa-solid fa-building" aria-hidden="true"></i><strong>No buildings in this college</strong><span>Add a building in the Buildings tab before adding rooms.</span></div>
                            @endforelse
                        </section>
                    @empty
                        <div class="management-empty"><i class="fa-solid fa-building-columns" aria-hidden="true"></i><strong>No colleges available</strong><span>Add a college before creating buildings and rooms.</span></div>
                    @endforelse
                </div>
            </section>

            <!-- ============================= -->            <!-- ============================= -->
            <!-- EDIT BUILDING MODAL -->
            <!-- ============================= -->

            <div id="editBuildingModal" class="modal">

                <div class="modal-content">

                    <h3>
                        Edit Building
                    </h3>

                    <form
                        id="editBuildingForm"
                        method="POST"
                    >

                        @csrf
                        @method('PUT')


                        <label>
                            College
                        </label>

                        <select
                            name="department_id"
                            id="editBuildingDept"
                            required
                        >

                            @foreach($departments as $dept)

                                <option value="{{ $dept->id }}">
                                    {{ $dept->name }}
                                </option>

                            @endforeach

                        </select>


                        <label>
                            Building Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="editBuildingName"
                            required
                        >


                        <div class="modal-buttons">

                            <button
                                type="button"
                                class="btn gray"
                                onclick="closeEditBuilding()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn green"
                            >
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            </div>



            <!-- ============================= -->
            <!-- ADD ROOM MODAL -->
            <!-- ============================= -->

            <div id="addRoomModal" class="modal">

                <div class="modal-content">

                    <h3>
                        Add Room
                    </h3>
                    <p class="modal-step-instruction">Step 3: Add this room to the building you opened. Capacity is required; equipment and notes are optional.</p>

                    <form
                        action="{{ route('admin.rooms.store') }}"
                        method="POST"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="department_id"
                            id="addRoomDept"
                        >


                        <input
                            type="hidden"
                            name="building_id"
                            id="addRoomBuilding"
                        >

                        <div class="selected-building-box">
                            <span class="selected-building-label">Building</span>
                            <strong id="addRoomBuildingName">Select a building</strong>
                        </div>


                        <label>
                            Room Name
                        </label>

                        <input
                            type="text"
                            name="room_name"
                            placeholder="Example: Room 101"
                            required
                        >


                        <label>
                            Capacity <span style="color:#dc2626;">*</span>
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            min="1"
                            required
                            placeholder="Example: 40"
                        >


                        <label>
                            Number of Computers
                        </label>

                        <input
                            type="number"
                            name="computers"
                            min="0"
                            value=""
                        >


                        <div class="checkbox-group">

                            <label>

                                <input
                                    type="checkbox"
                                    name="has_tv"
                                    value="1"
                                >

                                TV

                            </label>


                            <label>

                                <input
                                    type="checkbox"
                                    name="has_projector"
                                    value="1"
                                >

                                Projector

                            </label>

                        </div>


                        <label>
                            Room Purpose
                        </label>

                        <input
                            type="text"
                            name="purpose"
                            placeholder="Example: Computer Laboratory"
                        >


                        <label>
                            Description
                        </label>

                        <textarea
                            name="description"
                            placeholder="Room description..."
                        ></textarea>


                        <div class="modal-buttons">

                            <button
                                type="button"
                                class="btn gray"
                                onclick="closeAddRoom()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn green"
                            >
                                Add Room
                            </button>

                        </div>

                    </form>

                </div>

            </div>



            <!-- ============================= -->
            <!-- EDIT ROOM MODAL -->
            <!-- ============================= -->

            <div id="editRoomModal" class="modal">

                <div class="modal-content">

                    <h3>
                        Edit Room
                    </h3>

                    <form
                        id="editRoomForm"
                        method="POST"
                    >

                        @csrf
                        @method('PUT')


                        <input
                            type="hidden"
                            name="department_id"
                            id="editRoomDept"
                        >


                        <input
                            type="hidden"
                            name="building_id"
                            id="editRoomBuilding"
                        >


                        <label>
                            Room Name
                        </label>

                        <input
                            type="text"
                            name="room_name"
                            id="editRoomName"
                            required
                        >


                        <label>
                            Capacity <span style="color:#dc2626;">*</span>
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            id="editRoomCapacity"
                            min="1"
                            required
                        >


                        <label>
                            Number of Computers
                        </label>

                        <input
                            type="number"
                            name="computers"
                            id="editRoomComputers"
                            min="0"
                        >


                        <div class="checkbox-group">

                            <label>

                                <input
                                    type="checkbox"
                                    name="has_tv"
                                    value="1"
                                    id="editRoomTV"
                                >

                                TV

                            </label>


                            <label>

                                <input
                                    type="checkbox"
                                    name="has_projector"
                                    value="1"
                                    id="editRoomProjector"
                                >

                                Projector

                            </label>

                        </div>


                        <label>
                            Room Purpose
                        </label>

                        <input
                            type="text"
                            name="purpose"
                            id="editRoomPurpose"
                        >


                        <label>
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="editRoomDescription"
                        ></textarea>


                        <div class="modal-buttons">

                            <button
                                type="button"
                                class="btn gray"
                                onclick="closeEditRoom()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn green"
                            >
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            </div>

<!-- ========================================= -->
<!-- MOVE ROOM MODAL -->
<!-- ========================================= -->

<div id="moveRoomModal" class="modal">

    <div class="modal-content">

        <h3>Move Room</h3>

        <p class="move-description">
            You are moving:

            <strong id="moveRoomName"></strong>
        </p>


        <form
            action="{{ route('admin.rooms.move') }}"
            method="POST"
            id="moveRoomForm"
        >

            @csrf


            <!-- ROOM ID -->

            <input
                type="hidden"
                name="room_id"
                id="moveRoomId"
            >


            <!-- DEPARTMENT -->

            <label>
                Destination College
            </label>

            <select
                name="department_id"
                id="moveDepartment"
                required
            >

                <option value="">
                    -- Select College --
                </option>

                @foreach($departments as $dept)

                    <option value="{{ $dept->id }}">
                        {{ $dept->name }}
                    </option>

                @endforeach

            </select>


            <!-- BUILDING -->

            <label>
                Destination Building
            </label>

            <select
                name="building_id"
                id="moveBuilding"
                required
                disabled
            >

                <option value="">
                    -- Select College First --
                </option>

            </select>


            <!-- WARNING -->

            <div class="move-warning">

                The room will be moved to the
                selected college and building.

            </div>


            <!-- BUTTONS -->

            <div class="modal-buttons">

                <button
                    type="button"
                    class="btn gray"
                    onclick="closeMoveRoom()"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="btn green"
                >
                    Confirm Reassign
                </button>

            </div>

        </form>

    </div>

</div>

<div id="deleteConfirmModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
    <div class="modal-content confirm-delete-content">
        <div class="delete-confirm-icon" aria-hidden="true">!</div>
        <h3 id="deleteConfirmTitle">Confirm deletion</h3>
        <p id="deleteConfirmMessage">Are you sure you want to delete this item?</p>
        <p class="delete-confirm-note">This action cannot be undone.</p>
        <div class="modal-buttons">
            <button type="button" class="btn gray" id="cancelDeleteButton">Cancel</button>
            <button type="button" class="btn red" id="acceptDeleteButton">Delete</button>
        </div>
    </div>
</div>

        </main>
    </div>
</div>



<script>

/*
|--------------------------------------------------------------------------
| DEPARTMENT BUILDINGS DATA
|--------------------------------------------------------------------------
*/

const departmentBuildings = @json(
    $departments->mapWithKeys(function ($department) {

        return [
            $department->id => $department->buildings->map(function ($building) {

                return [
                    'id' => $building->id,
                    'name' => $building->name
                ];

            })->values()
        ];

    })
);


/*
|--------------------------------------------------------------------------
| DEPARTMENT SWITCH
|--------------------------------------------------------------------------
*/

function showDept(id, btn)
{
    document
        .querySelectorAll('.dept-content')
        .forEach(function(content) {

            content.classList.remove('active');

        });


    document
        .querySelectorAll('.dept-btn')
        .forEach(function(button) {

            button.classList.remove('active');
            button.setAttribute('aria-pressed', 'false');

        });


    const department =
        document.getElementById('dept' + id);


    if (department) department.classList.add('active');

    const selectedButton = btn || document.querySelector('.dept-btn[data-department-id="' + id + '"]');
    if (selectedButton) {
        selectedButton.classList.add('active');
        selectedButton.setAttribute('aria-pressed', 'true');
    }

    const buildingForm = document.querySelector('.buildings-admin-view .add-form');
    const buildingDepartmentSelect = document.querySelector('.buildings-admin-view .add-form select[name="department_id"]');
    if (buildingForm) buildingForm.style.display = 'block';
    if (buildingDepartmentSelect) buildingDepartmentSelect.value = String(id);

    document.querySelectorAll('.building-rooms.open').forEach(function (rooms) {
        rooms.classList.remove('open');
        const toggle = document.querySelector('.building-toggle[aria-controls="' + rooms.id + '"]');
        if (toggle) {
            toggle.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
        const addRoomButton = rooms.closest('.building-card')?.querySelector('.building-add-room');
        if (addRoomButton) addRoomButton.style.display = 'none';
    });

    const selectedDepartmentStep = document.querySelector('.buildings-admin-view .workflow-dept-heading');
    if (selectedDepartmentStep) {
        selectedDepartmentStep.classList.add('workflow-step-complete');
    }
}



/*
|--------------------------------------------------------------------------
| EDIT BUILDING
|--------------------------------------------------------------------------
*/

function openEditBuilding(id, dept, name)
{
    const modal =
        document.getElementById('editBuildingModal');

    const nameInput =
        document.getElementById('editBuildingName');

    const deptInput =
        document.getElementById('editBuildingDept');

    const form =
        document.getElementById('editBuildingForm');


    if (!modal || !nameInput || !deptInput || !form) {

        console.error(
            'Edit Building elements not found.'
        );

        return;
    }


    modal.style.display = 'block';

    nameInput.value = name;

    deptInput.value = dept;

    form.action =
        '/admin/buildings/' + id;
}


function closeEditBuilding()
{
    const modal =
        document.getElementById('editBuildingModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| ADD ROOM
|--------------------------------------------------------------------------
*/

function openAddRoom(dept, building)
{
    const modal =
        document.getElementById('addRoomModal');

    const deptInput =
        document.getElementById('addRoomDept');

    const buildingInput =
        document.getElementById('addRoomBuilding');


    if (!modal || !deptInput || !buildingInput) {

        console.error(
            'Add Room elements not found.'
        );

        return;
    }


    modal.style.display = 'block';

    deptInput.value = dept;

    buildingInput.value = building;

    const buildingNameElement =
        document.getElementById('addRoomBuildingName');

    if (buildingNameElement) {
        const buildingButton = document.querySelector(
            '.building-toggle[aria-controls="rooms-' + building + '"]'
        );

        const buildingCard = buildingButton
            ? buildingButton.closest('.building-card')
            : null;

        const heading = buildingCard
            ? buildingCard.querySelector('h3')
            : null;

        buildingNameElement.textContent =
            heading ? heading.textContent.trim() : 'Selected building';
    }

    // Every new room starts with empty optional information.
    const form = modal.querySelector('form');

    if (form) {
        const capacity = form.querySelector('[name="capacity"]');
        const computers = form.querySelector('[name="computers"]');
        const purpose = form.querySelector('[name="purpose"]');
        const description = form.querySelector('[name="description"]');

        if (capacity) capacity.value = '';
        if (computers) computers.value = '';
        if (purpose) purpose.value = '';
        if (description) description.value = '';

        form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }
}

function openQuickAddRoom(dept, building)
{
    const roomsContainer = document.getElementById('rooms-' + building);
    const toggle = document.querySelector('.building-toggle[aria-controls="rooms-' + building + '"]');
    const departmentButton = document.querySelector('.dept-btn[data-department-id="' + dept + '"]');

    if (typeof window.showDept === 'function') window.showDept(dept, departmentButton);
    if (roomsContainer && !roomsContainer.classList.contains('open') && toggle) {
        toggleBuilding(building, toggle);
    }

    const input = document.querySelector('#quickRoomForm-' + building + ' [name="room_name"]');
    if (input) {
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        window.setTimeout(function () { input.focus(); }, 180);
    }
}


function closeAddRoom()
{
    const modal =
        document.getElementById('addRoomModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| EDIT ROOM
|--------------------------------------------------------------------------
*/

function openEditRoom(
    id,
    dept,
    building,
    roomName,
    capacity,
    hasTV,
    hasProjector,
    computers,
    purpose,
    description
)
{
    const modal =
        document.getElementById('editRoomModal');


    if (!modal) {

        console.error(
            'Edit Room modal not found.'
        );

        return;
    }


    modal.style.display = 'block';


    document
        .getElementById('editRoomDept')
        .value = dept;


    document
        .getElementById('editRoomBuilding')
        .value = building;


    document
        .getElementById('editRoomName')
        .value = roomName;


    document
        .getElementById('editRoomCapacity')
        .value = capacity ?? '';


    document
        .getElementById('editRoomComputers')
        .value = computers ?? 0;


    document
        .getElementById('editRoomTV')
        .checked = hasTV;


    document
        .getElementById('editRoomProjector')
        .checked = hasProjector;


    document
        .getElementById('editRoomPurpose')
        .value = purpose ?? '';


    document
        .getElementById('editRoomDescription')
        .value =
            description ?? '';


    document
        .getElementById('editRoomForm')
        .action =
            '/admin/rooms/' + id;
}


function closeEditRoom()
{
    const modal =
        document.getElementById('editRoomModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| BUILDING OPEN / CLOSE
|--------------------------------------------------------------------------
*/

function toggleBuilding(buildingId, button)
{
    const rooms =
        document.getElementById(
            'rooms-' + buildingId
        );


    if (!rooms) {

        console.error(
            'Building rooms container not found.'
        );

        return;
    }


    const isOpen =
        rooms.classList.contains('open');


    if (isOpen) {

        rooms.classList.remove('open');

        button.classList.remove('open');

        button.setAttribute(
            'aria-expanded',
            'false'
        );
        const addRoomButton = button.closest('.building-card')?.querySelector('.building-add-room');
        if (addRoomButton) addRoomButton.style.display = 'none';

    }

    else {

        document.querySelectorAll('.building-rooms.open').forEach(function (otherRooms) {
            if (otherRooms === rooms) return;

            otherRooms.classList.remove('open');
            const otherCard = otherRooms.closest('.building-card');
            const otherToggle = otherCard ? otherCard.querySelector('.building-toggle') : null;
            const otherAddRoom = otherCard ? otherCard.querySelector('.building-add-room') : null;
            if (otherToggle) {
                otherToggle.classList.remove('open');
                otherToggle.setAttribute('aria-expanded', 'false');
            }
            if (otherAddRoom) otherAddRoom.style.display = 'none';
        });

        rooms.classList.add('open');

        button.classList.add('open');

        button.setAttribute(
            'aria-expanded',
            'true'
        );
        const addRoomButton = button.closest('.building-card')?.querySelector('.building-add-room');
        if (addRoomButton) addRoomButton.style.display = 'inline-flex';

    }
}



/*
|--------------------------------------------------------------------------
| MOVE ROOM
|--------------------------------------------------------------------------
*/

function openMoveRoom(button)
{
    const modal =
        document.getElementById('moveRoomModal');

    const roomId =
        document.getElementById('moveRoomId');

    const roomName =
        document.getElementById('moveRoomName');

    const department =
        document.getElementById('moveDepartment');

    const building =
        document.getElementById('moveBuilding');


    /*
    |--------------------------------------------------------------------------
    | CHECK ELEMENTS
    |--------------------------------------------------------------------------
    */

    if (!modal) {

        console.error(
            'moveRoomModal was not found.'
        );

        alert(
            'Move window could not be opened.'
        );

        return;
    }


    if (!roomId ||
        !roomName ||
        !department ||
        !building) {

        console.error(
            'Move Room modal elements are missing.'
        );

        alert(
            'Move Room form is incomplete.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROOM INFORMATION
    |--------------------------------------------------------------------------
    */

    const id =
        button.getAttribute(
            'data-room-id'
        );


    const name =
        button.getAttribute(
            'data-room-name'
        );


    /*
    |--------------------------------------------------------------------------
    | SET ROOM INFORMATION
    |--------------------------------------------------------------------------
    */

    roomId.value = id;

    roomName.textContent = name;


    /*
    |--------------------------------------------------------------------------
    | RESET DEPARTMENT
    |--------------------------------------------------------------------------
    */

    department.value = '';


    /*
    |--------------------------------------------------------------------------
    | RESET BUILDING
    |--------------------------------------------------------------------------
    */

    building.innerHTML =
        '<option value="">-- Select College First --</option>';

    building.disabled = true;


    /*
    |--------------------------------------------------------------------------
    | SHOW MODAL
    |--------------------------------------------------------------------------
    */

    modal.style.display = 'block';


    console.log(
        'Move Room opened:',
        id,
        name
    );
}



/*
|--------------------------------------------------------------------------
| CLOSE MOVE ROOM
|--------------------------------------------------------------------------
*/

function closeMoveRoom()
{
    const modal =
        document.getElementById('moveRoomModal');


    if (modal) {

        modal.style.display = 'none';

    }
}



/*
|--------------------------------------------------------------------------
| LOAD BUILDINGS WHEN DEPARTMENT CHANGES
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function()
    {

        const department =
            document.getElementById(
                'moveDepartment'
            );


        const building =
            document.getElementById(
                'moveBuilding'
            );


        if (!department || !building) {

            console.error(
                'Move College or Move Building select not found.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT CHANGE
        |--------------------------------------------------------------------------
        */

        department.addEventListener(
            'change',
            function()
            {

                const departmentId =
                    this.value;


                /*
                |--------------------------------------------------------------------------
                | RESET BUILDING
                |--------------------------------------------------------------------------
                */

                building.innerHTML =
                    '<option value="">-- Select Building --</option>';

                building.disabled = true;


                /*
                |--------------------------------------------------------------------------
                | NO DEPARTMENT
                |--------------------------------------------------------------------------
                */

                if (!departmentId) {

                    building.innerHTML =
                        '<option value="">-- Select College First --</option>';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | GET BUILDINGS
                |--------------------------------------------------------------------------
                */

                const buildings =
                    departmentBuildings[departmentId] || [];


                /*
                |--------------------------------------------------------------------------
                | NO BUILDINGS
                |--------------------------------------------------------------------------
                */

                if (buildings.length === 0) {

                    building.innerHTML =
                        '<option value="">No buildings available</option>';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | ADD BUILDINGS
                |--------------------------------------------------------------------------
                */

                buildings.forEach(
                    function(item)
                    {

                        const option =
                            document.createElement(
                                'option'
                            );


                        option.value =
                            item.id;


                        option.textContent =
                            item.name;


                        building.appendChild(
                            option
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | ENABLE BUILDING SELECT
                |--------------------------------------------------------------------------
                */

                building.disabled = false;

            }
        );

    }
);



/*
|--------------------------------------------------------------------------
| CLOSE MODALS WHEN CLICKING OUTSIDE
|--------------------------------------------------------------------------
*/

window.onclick = function(event)
{

    const editBuildingModal =
        document.getElementById(
            'editBuildingModal'
        );


    const addRoomModal =
        document.getElementById(
            'addRoomModal'
        );


    const editRoomModal =
        document.getElementById(
            'editRoomModal'
        );


    const moveRoomModal =
        document.getElementById(
            'moveRoomModal'
        );


    /*
    |--------------------------------------------------------------------------
    | EDIT BUILDING
    |--------------------------------------------------------------------------
    */

    if (
        editBuildingModal &&
        event.target === editBuildingModal
    ) {

        editBuildingModal.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | ADD ROOM
    |--------------------------------------------------------------------------
    */

    if (
        addRoomModal &&
        event.target === addRoomModal
    ) {

        addRoomModal.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | EDIT ROOM
    |--------------------------------------------------------------------------
    */

    if (
        editRoomModal &&
        event.target === editRoomModal
    ) {

        editRoomModal.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | MOVE ROOM
    |--------------------------------------------------------------------------
    */

    if (
        moveRoomModal &&
        event.target === moveRoomModal
    ) {

        moveRoomModal.style.display =
            'none';

    }

};
// =========================================
// SHOW AND HIGHLIGHT NEWLY ADDED BUILDING
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    @if(session('building_added'))

        const buildingDepartmentId = @json(session('building_department_id'));
        const newBuildingId = @json(session('building_id'));

        const newBuilding = document.querySelector(
            '.building-row-card[data-building-id="' + newBuildingId + '"]'
        );

        if (newBuilding) {
            newBuilding.classList.add('new-building-highlight');

            setTimeout(function () {
                newBuilding.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 250);

            setTimeout(function () {
                newBuilding.classList.remove('new-building-highlight');
            }, 8000);
        }

    @endif

});


// =========================================
// SHOW AND HIGHLIGHT NEWLY ADDED ROOM
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    @if(session('room_added'))

        const departmentId = @json(session('room_department_id'));
        const buildingId = @json(session('room_building_id'));
        const roomId = @json(session('room_id'));

        // 1. Open the correct department
        const department = document.getElementById('dept' + departmentId);

        if (department) {
            const departmentButton = document.querySelector('.dept-btn[data-department-id="' + departmentId + '"]');
            showDept(departmentId, departmentButton);
        }

        // 2. Open the correct building
        const roomsContainer = document.getElementById('rooms-' + buildingId);

        if (roomsContainer) {

            roomsContainer.classList.add('open');

            const toggleButton = document.querySelector(
                '.building-toggle[aria-controls="rooms-' + buildingId + '"]'
            );

            if (toggleButton) {
                toggleButton.classList.add('open');
                toggleButton.setAttribute('aria-expanded', 'true');
            }
            const addRoomButton = roomsContainer.closest('.building-card')?.querySelector('.building-add-room');
            if (addRoomButton) addRoomButton.style.display = 'inline-flex';

            // 3. Find the exact room that was just created
            setTimeout(function () {

                let newRoom = null;

                if (roomId) {
                    newRoom = roomsContainer.querySelector(
                        '[data-room-id="' + roomId + '"]'
                    );
                }

                // Fallback: if the ID is unavailable, at least show the building.
                if (!newRoom) {
                    const buildingCard = roomsContainer.closest('.building-card');

                    if (buildingCard) {
                        buildingCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return;
                }

                // 4. Highlight the exact new room with the sage accent
                newRoom.classList.add('new-room-highlight');

                // 5. Scroll directly to it
                newRoom.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // 6. Keep the sage highlight long enough to identify it
                setTimeout(function () {
                    newRoom.classList.remove('new-room-highlight');
                }, 8000);

            }, 350);
        }

    @endif

});





</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let pendingDeleteForm = null;
    let deleteTrigger = null;
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteMessage = document.getElementById('deleteConfirmMessage');
    const cancelDelete = document.getElementById('cancelDeleteButton');
    const acceptDelete = document.getElementById('acceptDeleteButton');

    const closeDeleteModal = function () {
        if (deleteModal) deleteModal.style.display = 'none';
        pendingDeleteForm = null;
        if (deleteTrigger) deleteTrigger.focus();
        deleteTrigger = null;
    };

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-confirm-delete] button[type="submit"]');
        if (!trigger) return;

        const form = trigger.closest('[data-confirm-delete]');
        if (!form || !deleteModal) return;

        window.persistBuildingsRoomsState?.();
        event.preventDefault();
        pendingDeleteForm = form;
        deleteTrigger = trigger;
        if (deleteMessage) deleteMessage.textContent = form.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this item?';
        deleteModal.style.display = 'block';
        if (cancelDelete) cancelDelete.focus();
    });

    if (cancelDelete) cancelDelete.addEventListener('click', closeDeleteModal);
    if (deleteModal) deleteModal.addEventListener('click', function (event) {
        if (event.target === deleteModal) closeDeleteModal();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'block') closeDeleteModal();
    });
    if (acceptDelete) acceptDelete.addEventListener('click', function () {
        if (!pendingDeleteForm) return;
        const form = pendingDeleteForm;
        pendingDeleteForm = null;
        form.submit();
    });
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = Array.from(document.querySelectorAll('[data-management-tab]'));
    const panels = Array.from(document.querySelectorAll('[data-management-panel]'));
    let initialTab = @json(old('manage_view', session('room_added') ? 'rooms' : 'buildings'));

    function openManagementTab(name, focusTab) {
        tabs.forEach(function (tab) {
            const selected = tab.dataset.managementTab === name;
            tab.classList.toggle('is-active', selected);
            tab.setAttribute('aria-selected', selected ? 'true' : 'false');
            tab.tabIndex = selected ? 0 : -1;
            if (selected && focusTab) tab.focus();
        });
        panels.forEach(function (panel) {
            const selected = panel.dataset.managementPanel === name;
            panel.classList.toggle('is-active', selected);
            panel.hidden = !selected;
        });
        if (search) search.placeholder = name === 'rooms' ? 'Search rooms…' : 'Search buildings…';
        if (typeof filterActiveList === 'function') filterActiveList();
        document.querySelectorAll('.ba-actions-menu.is-open').forEach(function (menu) {
            menu.classList.remove('is-open');
            menu.parentElement.querySelector('.ba-menu-trigger')?.setAttribute('aria-expanded', 'false');
        });
    }

    const page = document.querySelector('.buildings-admin-view');
    const search = document.getElementById('managementSearchInput');
    const clearSearch = document.getElementById('managementSearchClear');
    const buildingDeptInput = document.getElementById('buildingDepartment');
    const buildingFields = document.getElementById('buildingFields');
    const buildingAddCard = document.getElementById('buildingAddCard');
    const roomDeptInput = document.getElementById('roomDepartmentId');
    const roomBuildingInput = document.getElementById('roomBuildingId');
    const roomFields = document.getElementById('roomFields');
    const roomAddForm = document.getElementById('roomAddForm');
    const buildingChoice = document.getElementById('roomBuildingChoice');
    const roomContext = document.getElementById('roomAddContext');
    const roomLocation = document.getElementById('roomFormLocation');
    const normalize = value => (value || '').trim().toLocaleLowerCase();
    const managementStateKey = 'adminBuildingsRoomsViewState';
    let chosenBuildingCollegeId = '';
    let chosenRoomCollegeId = '';
    let initializingManagementView = true;
    function persistManagementState() {
        if (initializingManagementView) return;
        try {
            sessionStorage.setItem(managementStateKey, JSON.stringify({
                tab: document.querySelector('.management-panel.is-active')?.dataset.managementPanel || initialTab,
                buildingCollege: chosenBuildingCollegeId,
                roomCollege: chosenRoomCollegeId,
                roomBuilding: roomBuildingInput.value || '',
                search: search?.value || '',
                scrollY: window.scrollY
            }));
        } catch (error) { /* Storage may be unavailable in private browsing. */ }
    }
    window.persistBuildingsRoomsState = persistManagementState;

    function chooseBuildingCollege(id) {
        if (!id) return;
        chosenBuildingCollegeId = id;
        buildingDeptInput.value = id;
        buildingFields.disabled = false;
        buildingAddCard.classList.remove('is-locked');
        document.querySelectorAll('[data-building-college]').forEach(button => button.classList.toggle('is-selected', button.dataset.buildingCollege === id));
        document.querySelectorAll('.college-group-card[data-college-id]').forEach(card => card.classList.toggle('is-visible', card.dataset.collegeId === id));
        filterActiveList();
    }
    document.querySelectorAll('[data-building-college]').forEach(button => button.addEventListener('click', () => {
        chooseBuildingCollege(button.dataset.buildingCollege);
        persistManagementState();
    }));

    function chooseRoomCollege(id) {
        if (!id) return;
        chosenRoomCollegeId = id;
        roomDeptInput.value = id;
        roomBuildingInput.value = '';
        buildingChoice.value = '';
        buildingChoice.disabled = false;
        buildingChoice.querySelectorAll('option[data-department-id]').forEach(option => { option.hidden = option.dataset.departmentId !== id; });
        buildingChoice.options[0].textContent = 'Select a building';
        document.querySelectorAll('[data-room-college]').forEach(button => button.classList.toggle('is-selected', button.dataset.roomCollege === id));
        document.querySelectorAll('.rooms-college-group[data-department-id]').forEach(group => group.classList.toggle('is-visible', group.dataset.departmentId === id));
        document.querySelectorAll('.room-building-group').forEach(group => group.classList.remove('is-visible'));
        roomFields.disabled = true;
        roomAddForm.classList.add('is-locked');
        roomContext.textContent = 'Choose a building to enable this form.';
        filterActiveList();
        persistManagementState();
    }
    document.querySelectorAll('[data-room-college]').forEach(button => button.addEventListener('click', () => chooseRoomCollege(button.dataset.roomCollege)));

    buildingChoice.addEventListener('change', function () {
        const id = this.value;
        const option = this.selectedOptions[0];
        const deptId = option?.dataset.departmentId || roomDeptInput.value;
        if (!id) return;
        roomDeptInput.value = deptId;
        roomBuildingInput.value = id;
        const chosenName = option.textContent.trim();
        const collegeName = document.querySelector('[data-room-college="' + deptId + '"]')?.textContent.trim() || 'selected college';
        document.querySelectorAll('.room-building-group').forEach(group => group.classList.toggle('is-visible', group.dataset.buildingId === id));
        roomFields.disabled = false;
        roomAddForm.classList.remove('is-locked');
        roomContext.textContent = collegeName + ' · ' + chosenName;
        roomLocation.textContent = 'Room will be listed under ' + collegeName + ' · ' + chosenName + '.';
        filterActiveList();
        persistManagementState();
    });

    function filterActiveList() {
        if (!search) return;
        const query = normalize(search.value);
        const active = document.querySelector('.management-panel.is-active');
        if (!active) return;
        if (active.id === 'buildingsPanel') {
            const groups = Array.from(active.querySelectorAll('.college-group-card[data-college-id]'));
            groups.forEach(group => {
                const rows = Array.from(group.querySelectorAll('.building-row-card'));
                const collegeMatches = !!query && normalize(group.dataset.collegeName).includes(query);
                rows.forEach(card => { card.hidden = !!query && !collegeMatches && !normalize(card.textContent).includes(query); });
                const hasMatch = collegeMatches || rows.some(card => !card.hidden);
                const selected = query ? hasMatch : group.dataset.collegeId === chosenBuildingCollegeId;
                group.classList.toggle('is-visible', selected);
                const button = document.querySelector('[data-building-college="' + group.dataset.collegeId + '"]');
                button?.classList.toggle('is-selected', selected);
            });
        } else {
            const selectedCollege = document.querySelector('[data-room-college].is-selected')?.dataset.roomCollege || '';
            const selectedBuilding = roomBuildingInput.value || '';
            active.querySelectorAll('.rooms-college-group').forEach(college => {
                let hasVisibleBuilding = false;
                college.querySelectorAll('.room-building-group').forEach(building => {
                    const buildingMatch = !!query && normalize(building.querySelector('.room-building-header')?.textContent).includes(query);
                    const cards = Array.from(building.querySelectorAll('.room-card'));
                    cards.forEach(card => { card.hidden = !!query && !buildingMatch && !normalize(card.textContent).includes(query); });
                    const hasMatch = buildingMatch || cards.some(card => !card.hidden);
                    const show = query ? hasMatch : building.dataset.buildingId === selectedBuilding && college.dataset.departmentId === selectedCollege;
                    building.classList.toggle('is-visible', show);
                    hasVisibleBuilding = hasVisibleBuilding || show;
                });
                college.classList.toggle('is-visible', query ? hasVisibleBuilding : college.dataset.departmentId === selectedCollege);
            });
        }
    }
    if (search) search.addEventListener('input', function () { filterActiveList(); persistManagementState(); });
    if (clearSearch) clearSearch.addEventListener('click', function () { search.value = ''; filterActiveList(); persistManagementState(); search.focus(); });

    const oldDepartment = page?.dataset.initialDepartment || '';
    const oldBuilding = page?.dataset.initialBuilding || '';
    const newlyAddedBuildingDepartment = page?.dataset.newBuildingDepartment || '';
    if (oldDepartment && document.querySelector('[data-building-college="' + oldDepartment + '"]')) chooseBuildingCollege(oldDepartment);
    if (newlyAddedBuildingDepartment && document.querySelector('[data-building-college="' + newlyAddedBuildingDepartment + '"]')) chooseBuildingCollege(newlyAddedBuildingDepartment);
    if (oldDepartment && document.querySelector('[data-room-college="' + oldDepartment + '"]')) {
        chooseRoomCollege(oldDepartment);
        if (oldBuilding && buildingChoice.querySelector('option[value="' + oldBuilding + '"]')) { buildingChoice.value = oldBuilding; buildingChoice.dispatchEvent(new Event('change')); }
    }
    let savedState = null;
    try { savedState = JSON.parse(sessionStorage.getItem(managementStateKey) || 'null'); } catch (error) { savedState = null; }
    const serverHasLocation = !!(oldDepartment || oldBuilding || newlyAddedBuildingDepartment);
    if (savedState) {
        if (!serverHasLocation) {
            if (savedState.buildingCollege && document.querySelector('[data-building-college="' + savedState.buildingCollege + '"]')) chooseBuildingCollege(savedState.buildingCollege);
            if (savedState.roomCollege && document.querySelector('[data-room-college="' + savedState.roomCollege + '"]')) {
                chooseRoomCollege(savedState.roomCollege);
                if (savedState.roomBuilding && buildingChoice.querySelector('option[value="' + savedState.roomBuilding + '"]')) {
                    buildingChoice.value = savedState.roomBuilding;
                    buildingChoice.dispatchEvent(new Event('change'));
                }
            }
        }
        if (savedState.search) search.value = savedState.search;
        if (savedState.tab === 'rooms' || savedState.tab === 'buildings') initialTab = savedState.tab;
    }
    filterActiveList();
    if (search) search.placeholder = 'Search buildings…';

    tabs.forEach(function (tab, index) {
        tab.addEventListener('click', function () { openManagementTab(tab.dataset.managementTab, false); persistManagementState(); });
        tab.addEventListener('keydown', function (event) {
            if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
            event.preventDefault();
            const next = tabs[(index + (event.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length];
            openManagementTab(next.dataset.managementTab, true);
            persistManagementState();
        });
    });
    openManagementTab(initialTab, false);
    initializingManagementView = false;
    document.querySelectorAll('.buildings-admin-view form').forEach(form => form.addEventListener('submit', persistManagementState));
    if (savedState && !serverHasLocation && Number.isFinite(Number(savedState.scrollY))) {
        requestAnimationFrame(() => requestAnimationFrame(() => window.scrollTo({ top: Number(savedState.scrollY), behavior: 'auto' })));
    }

    window.baCloseActionsMenus = function () {
        document.querySelectorAll('.ba-actions-menu.is-open').forEach(function (menu) {
            menu.classList.remove('is-open');
            menu.parentElement.querySelector('.ba-menu-trigger')?.setAttribute('aria-expanded', 'false');
        });
    };

    window.baToggleActionsMenu = function (trigger) {
        const menu = trigger.parentElement.querySelector('.ba-actions-menu');
        if (!menu) return;
        const wasOpen = menu.classList.contains('is-open');
        window.baCloseActionsMenus();
        if (wasOpen) return;
        menu.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        const rect = trigger.getBoundingClientRect();
        const menuRect = menu.getBoundingClientRect();
        const left = Math.max(12, Math.min(rect.right - menuRect.width, window.innerWidth - menuRect.width - 12));
        let top = rect.bottom + 6;
        if (top + menuRect.height > window.innerHeight - 12) top = rect.top - menuRect.height - 6;
        menu.style.left = left + 'px';
        menu.style.top = Math.max(12, top) + 'px';
    };

    document.addEventListener('click', function (event) {
        if (event.target.closest('.ba-menu-item')) window.baCloseActionsMenus();
        else if (!event.target.closest('.ba-actions')) window.baCloseActionsMenus();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') window.baCloseActionsMenus();
    });
    window.addEventListener('resize', window.baCloseActionsMenus);
    window.addEventListener('scroll', window.baCloseActionsMenus, true);
});
</script>


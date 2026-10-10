<?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="wrapper">
    <div class="main-layout">
        <?php echo $__env->make('chair.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="content">
            <style>
                *{box-sizing:border-box}
                body{background:#f5f7f5;margin:0}
                .curr-page{width:100%;max-width:1700px;margin:0 auto;padding:24px 22px}
                .header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px}
                .create-only{max-width:520px;margin:10px auto 0}
                .selected-header{display:flex;justify-content:space-between;gap:16px;align-items:flex-start}
                .selected-actions{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}
                .top-action{min-width:90px}
                .back-btn{background:#edf1ee;color:#38423b}
                .delete-top{background:#b42318;color:#fff}
                .small-note{margin-top:10px;padding:10px 12px;border-radius:9px;background:#f6faf6;border:1px solid #e1ebe2;color:#68736c;font-size:11px;line-height:1.5}
                .title{margin:0;color:#183b20;font-size:29px;font-weight:800}
                .subtitle{margin:6px 0 0;color:#6f7c73;font-size:13px}
                .grid{display:flex;flex-direction:column;gap:20px;align-items:stretch;width:100%}
                .card{background:#fff;border:1px solid #e2e8e3;border-radius:14px;padding:18px;box-shadow:0 3px 12px rgba(0,0,0,.04);margin-bottom:18px}
                .card h2{margin:0 0 15px;color:#245d2e;font-size:18px}
                .field{margin-bottom:13px}
                label{display:block;font-size:12px;font-weight:700;color:#39443d;margin-bottom:6px}
                input,select{width:100%;padding:10px 11px;border:1px solid #d4ddd6;border-radius:8px;background:#fff;font:inherit;font-size:13px}
                input:focus,select:focus{outline:none;border-color:#3d8b4a;box-shadow:0 0 0 3px rgba(61,139,74,.1)}
                .btn{border:0;border-radius:8px;padding:9px 12px;font-size:12px;font-weight:700;text-decoration:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center}
                .green{background:#2e7d32;color:#fff}.gray{background:#edf1ee;color:#38423b}.red{background:#b42318;color:#fff}.yellow{background:#f5c542;color:#332900}
                .message{padding:11px 13px;border-radius:9px;margin-bottom:15px;font-size:12px}
                .success{background:#eaf7ec;border:1px solid #c5e3ca;color:#216b2d}
                .error{background:#fff0f1;border:1px solid #efcaca;color:#a42d2d}
                .curr-list{display:flex;flex-direction:column;gap:10px}
                .curr-item{border:1px solid #e1e7e2;border-radius:10px;padding:13px;background:#fbfcfb}
                .curr-item.active{border-color:#2e7d32;background:#f1f8f2}
                .curr-name{font-weight:800;color:#26352a;font-size:14px}
                .muted{color:#748078;font-size:11px;line-height:1.5}
                .actions{display:flex;gap:7px;flex-wrap:wrap;margin-top:10px}
                .actions form{margin:0;display:inline-flex}
                .course-meta{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:15px}
                .pill{padding:5px 9px;border-radius:999px;background:#eef6ef;color:#286331;font-size:11px;font-weight:700}
                .table-wrap{width:100%;max-width:none;overflow-x:auto;overflow-y:visible;border:1px solid #dfe7e0;border-radius:10px;background:#fff}
                .master-course-table{width:100%;min-width:1300px;border-collapse:collapse}
                .master-course-table th,.master-course-table td{font-size:13px;padding:12px 11px}
                .master-course-table th{font-size:12px}
                .table-wrap table{width:100%;min-width:1300px}
                .table-wrap th,.table-wrap td{white-space:normal}
                .table-wrap th:nth-child(1),.table-wrap td:nth-child(1){width:110px}
                .table-wrap th:nth-child(2),.table-wrap td:nth-child(2){width:330px;min-width:280px}
                .table-wrap th:nth-child(3),.table-wrap td:nth-child(3){width:75px}
                .table-wrap th:nth-child(4),.table-wrap td:nth-child(4){width:95px}
                .table-wrap th:nth-child(5),.table-wrap td:nth-child(5){width:75px}
                .table-wrap th:nth-child(6),.table-wrap td:nth-child(6){width:300px;min-width:250px}
                .table-wrap th:nth-child(7),.table-wrap td:nth-child(7){width:100px;min-width:90px}
                .table-wrap td:nth-child(2),.table-wrap td:nth-child(6){line-height:1.5}
                .master-course-card{width:100%;max-width:none}
                .master-course-card .section-title{font-size:18px;margin-bottom:14px}
                .master-course-card .semester-heading{font-size:13px;padding:12px 14px}
                table{width:100%;border-collapse:collapse;min-width:760px}
                th,td{padding:10px 9px;border-bottom:1px solid #edf0ed;text-align:left;font-size:12px;vertical-align:top}
                th{background:#f6f8f6;color:#536059;font-size:11px}
                tr:last-child td{border-bottom:0}
                .empty{text-align:center;padding:25px;color:#7b857f;font-size:12px}
                .document{background:#f7faf7;border:1px solid #e0e8e1;border-radius:9px;padding:10px;margin-top:8px}
                .document a{color:#23652d;font-weight:700;font-size:12px;text-decoration:none}
                .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
                .full{grid-column:1/-1}
                .section-title{font-size:14px;font-weight:800;color:#245d2e;margin:22px 0 10px}
                .semester-heading{
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    margin:18px 0 10px;
                    padding:10px 12px;
                    border-radius:9px;
                    font-size:12px;
                    font-weight:800;
                    letter-spacing:.3px;
                }
                .semester-heading.first{
                    background:#eaf7ec;
                    border:1px solid #c8e5cd;
                    color:#216b2d;
                }
                .semester-heading.second{
                    background:#eef5ff;
                    border:1px solid #cbdcf4;
                    color:#245d8a;
                }
                .semester-heading.other{
                    background:#f5f5f5;
                    border:1px solid #dedede;
                    color:#666;
                }
                .semester-count{
                    font-size:11px;
                    font-weight:700;
                    opacity:.8;
                }
                .semester-badge{
                    display:inline-block;
                    padding:4px 7px;
                    border-radius:999px;
                    font-size:10px;
                    font-weight:800;
                    white-space:nowrap;
                }
                .first-badge{
                    background:#eaf7ec;
                    color:#216b2d;
                }
                .second-badge{
                    background:#eef5ff;
                    color:#245d8a;
                }
                .modal-backdrop{position:fixed;inset:0;background:rgba(17,30,21,.58);backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);display:none;align-items:center;justify-content:center;padding:20px;z-index:9999;}
                .modal-backdrop.show{display:flex;}
                .modal-box{width:min(620px,100%);max-height:calc(100vh - 40px);overflow-y:auto;background:#fff;border:1px solid #dfe8e0;border-radius:18px;box-shadow:0 20px 70px rgba(0,0,0,.24);padding:22px;animation:modalIn .18s ease-out;}
                .modal-box.large{width:min(820px,100%);}
                .modal-head{display:flex;align-items:flex-start;justify-content:space-between;gap:15px;margin-bottom:18px;}
                .modal-head h2{margin:0;color:#1f5528;font-size:20px;font-weight:800;}
                .modal-head p{margin:5px 0 0;color:#748078;font-size:11px;line-height:1.5;}
                .modal-close{width:34px;height:34px;border:0;border-radius:50%;background:#edf1ee;color:#39443d;font-size:20px;line-height:1;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;}
                .modal-close:hover{background:#e1e8e2;}
                .modal-actions{display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;margin-top:18px;padding-top:14px;border-top:1px solid #edf0ed;}
                .modal-trigger-card{display:flex;align-items:center;justify-content:space-between;gap:15px;}
                .modal-trigger-card h3{margin:0;color:#245d2e;font-size:15px;}
                .modal-trigger-card p{margin:5px 0 0;color:#748078;font-size:11px;line-height:1.5;}
                .trigger-actions{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;}
                .danger-note{padding:11px 12px;border-radius:9px;background:#fff5f5;border:1px solid #f0cccc;color:#8e2929;font-size:11px;line-height:1.5;}
                body.modal-open{overflow:hidden;}
                @keyframes modalIn{from{opacity:0;transform:translateY(10px) scale(.985)}to{opacity:1;transform:translateY(0) scale(1)}}
                .method-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;}
                .method-card{border:1px solid #dfe7e0;background:#fbfdfb;border-radius:14px;padding:22px 18px;text-align:left;cursor:pointer;transition:.18s ease;min-height:230px;}
                .method-card:hover{border-color:#3d8b4a;background:#f2f9f3;transform:translateY(-2px);box-shadow:0 8px 22px rgba(36,93,46,.10);}
                .method-icon{width:54px;height:54px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:27px;font-weight:800;margin-bottom:14px;}
                .manual-icon{background:#eaf7ec;color:#2e7d32;}
                .upload-icon{background:#eef5ff;color:#245d8a;}
                .method-title{font-size:15px;font-weight:800;color:#26352a;margin-bottom:7px;}
                .method-text{font-size:11px;line-height:1.6;color:#748078;min-height:54px;}
                .method-link{display:block;margin-top:17px;color:#2e7d32;font-size:11px;font-weight:800;}
                .upload-note{margin-top:15px;padding:11px 12px;border-radius:9px;background:#f6faf7;border:1px solid #dfe9e0;color:#68736c;font-size:11px;line-height:1.55;}
                .master-list-header{
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    gap:16px;
                    margin-bottom:18px;
                }
                .master-count-pill{
                    padding:7px 11px;
                    border-radius:999px;
                    background:#eef6ef;
                    color:#286331;
                    font-size:11px;
                    font-weight:800;
                    white-space:nowrap;
                }
                .year-block{
                    border:1px solid #dfe7e0;
                    border-radius:14px;
                    padding:14px;
                    background:#fbfdfb;
                    margin-bottom:18px;
                }
                .year-heading{
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    gap:12px;
                    margin-bottom:12px;
                    padding:11px 13px;
                    border-radius:10px;
                    background:#eaf7ec;
                    border:1px solid #c8e5cd;
                    color:#216b2d;
                    font-size:14px;
                    font-weight:900;
                    letter-spacing:.4px;
                }
                .semester-grid{
                    display:grid;
                    grid-template-columns:minmax(0,1fr) minmax(0,1fr);
                    gap:14px;
                    align-items:start;
                }
                .semester-column{
                    min-width:0;
                }
                .year-table-wrap{
                    overflow-x:auto;
                    overflow-y:visible;
                }
                .year-master-table{
                    min-width:820px !important;
                }
                .year-master-table th,
                .year-master-table td{
                    font-size:12px;
                    padding:10px 9px;
                }
                .year-master-table th:nth-child(1),
                .year-master-table td:nth-child(1){
                    width:105px;
                    min-width:100px;
                }
                .year-master-table th:nth-child(2),
                .year-master-table td:nth-child(2){
                    width:260px;
                    min-width:220px;
                    line-height:1.45;
                }
                .year-master-table th:nth-child(3),
                .year-master-table td:nth-child(3){
                    width:65px;
                    min-width:60px;
                }
                .year-master-table th:nth-child(4),
                .year-master-table td:nth-child(4){
                    width:70px;
                    min-width:65px;
                }
                .year-master-table th:nth-child(5),
                .year-master-table td:nth-child(5){
                    width:180px;
                    min-width:160px;
                    line-height:1.4;
                }
                .year-master-table th:nth-child(6),
                .year-master-table td:nth-child(6){
                    width:86px;
                    min-width:80px;
                }
                .year-master-table .btn{
                    padding:8px 10px;
                }


                .checklist-editor-card{overflow:visible}
                .master-grid-actions{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}
                .checklist-year-block{border:1px solid #d7e2d8;border-radius:14px;background:#fbfdfb;padding:12px;margin:0 0 20px;box-shadow:0 2px 8px rgba(0,0,0,.025)}
                .checklist-year-toolbar{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:10px;padding:10px 12px;border-radius:10px;background:#f0f7f1;border:1px solid #cfe3d2}
                .checklist-year-title{font-size:15px;font-weight:900;color:#1f5b2a;letter-spacing:.35px}
                .grid-row-column-actions{display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end}
                .grid-edit-only{min-width:88px}
                .checklist-table-scroll{width:100%;overflow:auto;border:1px solid #aeb9b0;background:#fff;border-radius:7px;position:relative}
                .checklist-grid-table{width:max-content;min-width:100%;border-collapse:collapse;table-layout:fixed;font-family:Arial,sans-serif;background:#fff}
                .checklist-grid-table col{min-width:45px}
                .checklist-grid-table th,.checklist-grid-table td{border:1px solid #8f9991;padding:6px 7px;vertical-align:middle;text-align:center;font-size:11px;line-height:1.2;position:relative}
                .checklist-grid-table th{background:#f5f7f5;color:#1f2a22;font-weight:800}
                .checklist-grid-table .group-head{background:#e7ece8;font-size:11px;letter-spacing:.2px}
                .checklist-grid-table .first-sem-group{background:#edf6ee;color:#1f5b2a}
                .checklist-grid-table .second-sem-group{background:#edf4fc;color:#245d8a}
                .checklist-grid-table .additional-group{background:#f7f1e5;color:#765d1f}
                .checklist-grid-table .year-head{background:#dfe8e0;color:#1f5b2a;font-weight:900}
                .checklist-grid-table .checklist-subhead-row th{font-size:10px;background:#fafbfa}
                .checklist-grid-table td{background:#fff;color:#26352a;min-height:30px;white-space:pre-wrap;word-break:break-word}
                .checklist-grid-table td[data-col-index="0"]{font-weight:800;background:#f2f7f3;color:#1f5b2a}
                .checklist-grid-table tr.selected-row td{background:#fff8df}
                .checklist-grid-table th.selected-column{outline:2px solid #2e7d32;outline-offset:-2px}
                .checklist-grid-table td.selected-column-cell{background:#f1f8f2}
                .checklist-grid-table.editing td,.checklist-grid-table.editing th{cursor:text}
                .checklist-grid-table.editing td[contenteditable="true"],.checklist-grid-table.editing th[contenteditable="true"]{background:#fffef2;box-shadow:inset 0 0 0 1px rgba(46,125,50,.15)}
                .checklist-grid-table td.row-resize-cell{display:none}
                .checklist-grid-table .resize-col-handle{position:absolute;right:-3px;top:0;width:7px;height:100%;cursor:col-resize;z-index:7}
                .checklist-grid-table td .resize-row-handle{position:absolute;left:0;right:0;bottom:-3px;height:7px;cursor:row-resize;z-index:6}
                .checklist-grid-table .custom-col-head{background:#f7f1e5;color:#765d1f}
                .checklist-help{display:flex;gap:14px;flex-wrap:wrap;margin-top:8px;font-size:10px;color:#6e786f}
                .checklist-help strong{color:#245d2e}
                .checklist-total-bar{display:grid;grid-template-columns:1fr 1fr;border:1px solid #8f9991;border-top:0;background:#fafcfa;font-size:11px;font-weight:700;color:#26352a}.checklist-total-bar>div{padding:9px 12px;text-align:center}.checklist-total-bar>div+div{border-left:1px solid #8f9991}
                .grid-editing-note{padding:9px 11px;border-radius:8px;background:#fff8df;border:1px solid #eadfae;color:#6d5b14;font-size:11px;margin-bottom:10px}
                .grid-disabled{opacity:.55;pointer-events:none}
                .checklist-year-toolbar{display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
                .drag-course-cell{cursor:grab}
                .checklist-grid-table.editing td.drag-course-cell{outline:1px dashed #8ebc96;outline-offset:-3px}
                .checklist-grid-table td.drop-course-target{background:#eaf7ed!important;box-shadow:inset 0 0 0 2px #39834a}
                .year-print-btn{white-space:nowrap}
                @media print{
                    body.printing-year *{visibility:hidden!important}
                    body.printing-year .checklist-year-block.print-target,
                    body.printing-year .checklist-year-block.print-target *{visibility:visible!important}
                    body.printing-year .checklist-year-block.print-target{display:block!important;position:absolute!important;left:0!important;top:0!important;width:100%!important;margin:0!important;padding:0!important;box-shadow:none!important;border:0!important}
                    body.printing-year .grid-row-column-actions,
                    body.printing-year .checklist-help,
                    body.printing-year .master-grid-actions,
                    body.printing-year #masterGridSaveForm{display:none!important}
                    body.printing-year .checklist-table-scroll{overflow:visible!important}
                    body.printing-year .checklist-grid-table{width:100%!important;min-width:0!important;font-size:8pt!important}
                    body.printing-year .checklist-grid-table td,
                    body.printing-year .checklist-grid-table th{overflow-wrap:anywhere!important;print-color-adjust:exact;-webkit-print-color-adjust:exact}
                    @page{size:landscape;margin:10mm}
                }

                @media(max-width:1100px){.grid{grid-template-columns:1fr}.form-grid{grid-template-columns:1fr}.curr-page{padding:18px}}
                @media(max-width:650px){.method-grid{grid-template-columns:1fr}.modal-box{padding:17px}.modal-backdrop{padding:12px}.method-card{min-height:auto;}}
            </style>

            <div class="curr-page">
                <div class="header">
                    <div>
                        <h1 class="title">Curriculum</h1>
                        <p class="subtitle">
                            Create separate curricula, upload the official file separately,
                            and manage the master course list.
                        </p>
                    </div>
                </div>

                <?php if(session('success')): ?>
                    <div class="message success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="message error"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="message error"><?php echo e($errors->first()); ?></div>
                <?php endif; ?>

                
                <?php if(!$selectedCurriculum): ?>

                    <?php if(!$curricula->count()): ?>
                        
                        <div class="create-only" style="max-width:760px;">
                            <div class="card" style="text-align:center;padding:34px 26px;">
                                <div style="width:64px;height:64px;margin:0 auto 14px;border-radius:18px;background:#eaf7ec;color:#2e7d32;display:flex;align-items:center;justify-content:center;font-size:28px;">📚</div>
                                <h2 style="font-size:22px;margin-bottom:8px;">Create Your First Curriculum</h2>
                                <p class="muted" style="max-width:520px;margin:0 auto 18px;">
                                    Create the curriculum information first. After it is created, you can upload the official file separately and manage the master courses at the bottom.
                                </p>
                                <button class="btn green" type="button" onclick="openModal('curriculumMethodModal')">
                                    + New Curriculum
                                </button>
                            </div>
                        </div>

                        
                        <div class="modal-backdrop show" id="curriculumMethodModal" aria-hidden="false">
                            <div class="modal-box" style="width:min(760px,100%);" role="dialog" aria-modal="true" aria-labelledby="curriculumMethodTitle">
                                <div class="modal-head">
                                    <div>
                                        <h2 id="curriculumMethodTitle">Create Curriculum</h2>
                                        <p>Choose how you want to create your curriculum.</p>
                                    </div>
                                    <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('curriculumMethodModal')">&times;</button>
                                </div>

                                <div class="method-grid">
                                    <button type="button" class="method-card" onclick="chooseCurriculumMethod('manual')">
                                        <div class="method-icon manual-icon">✎</div>
                                        <div class="method-title">Manually Adding Curriculum</div>
                                        <div class="method-text">Enter the program, major, curriculum code and status. After creation, enter all courses manually in the curriculum checklist.</div>
                                        <span class="method-link">Choose Manual &rarr;</span>
                                    </button>

                                    <button type="button" class="method-card" onclick="chooseCurriculumMethod('upload')">
                                        <div class="method-icon upload-icon">↑</div>
                                        <div class="method-title">Via Upload</div>
                                        <div class="method-text">Enter the curriculum information and upload the official curriculum file. The file can then be read for its master courses.</div>
                                        <span class="method-link">Choose Upload &rarr;</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="modal-backdrop" id="newCurriculumModal" aria-hidden="true">
                            <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="newCurriculumTitle">
                                <div class="modal-head">
                                    <div>
                                        <h2 id="newCurriculumTitle">Manually Add Curriculum</h2>
                                        <p>Enter the curriculum information first. After creation, enter all courses manually in the curriculum checklist or upload the official file.</p>
                                    </div>
                                    <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('newCurriculumModal')">&times;</button>
                                </div>

                                <form method="POST" action="<?php echo e(route('chair.curriculum.store')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="field"><label>Program</label><input name="program" value="<?php echo e(old('program')); ?>" placeholder="Bachelor of Science in Information Technology" required></div>
                                    <div class="field"><label>Major <span style="font-weight:400">(Optional)</span></label><input name="major" value="<?php echo e(old('major')); ?>" placeholder="Network Administration"></div>
                                    <div class="field"><label>Curriculum Code</label><input name="curriculum_code" value="<?php echo e(old('curriculum_code')); ?>" placeholder="BSIT-2015-2022" required></div>
                                    <div class="field"><label>Status</label><select name="status"><option value="active" <?php if(old('status','active') === 'active'): echo 'selected'; endif; ?>>Active</option><option value="inactive" <?php if(old('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option></select></div>
                                    <div class="modal-actions">
                                        <button class="btn gray" type="button" onclick="backToCurriculumMethod('newCurriculumModal')">Back</button>
                                        <button class="btn green" type="submit">Create Curriculum</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                        <div class="modal-backdrop" id="uploadNewCurriculumModal" aria-hidden="true">
                            <div class="modal-box large" role="dialog" aria-modal="true" aria-labelledby="uploadNewCurriculumTitle">
                                <div class="modal-head">
                                    <div>
                                        <h2 id="uploadNewCurriculumTitle">Create Curriculum via Upload</h2>
                                        <p>Enter the curriculum information and upload the official curriculum file in one step.</p>
                                    </div>
                                    <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('uploadNewCurriculumModal')">&times;</button>
                                </div>

                                <form method="POST" action="<?php echo e(route('chair.curriculum.store.upload')); ?>" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-grid">
                                        <div><label>Program</label><input name="program" value="<?php echo e(old('program')); ?>" placeholder="Bachelor of Science in Information Technology" required></div>
                                        <div><label>Major <span style="font-weight:400">(Optional)</span></label><input name="major" value="<?php echo e(old('major')); ?>" placeholder="Network Administration"></div>
                                        <div><label>Curriculum Code</label><input name="curriculum_code" value="<?php echo e(old('curriculum_code')); ?>" placeholder="BSIT-2015-2022" required></div>
                                        <div><label>Status</label><select name="status"><option value="active" <?php if(old('status','active') === 'active'): echo 'selected'; endif; ?>>Active</option><option value="inactive" <?php if(old('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option></select></div>
                                        <div><label>Curriculum Files</label><input id="uploadNewCurriculumFiles" type="file" name="curriculum_file[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" multiple required><div id="uploadNewCurriculumFilesInfo" class="muted" style="margin-top:6px;">Select one or multiple PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG or WEBP files. Maximum 50 MB per file.</div></div>
                                    </div>
                                    <div class="upload-note">
                                        <strong>Via Upload:</strong> The curriculum will be created first, then the uploaded official file will be stored and processed for its master course information.
                                    </div>
                                    <div class="modal-actions">
                                        <button class="btn gray" type="button" onclick="backToCurriculumMethod('uploadNewCurriculumModal')">Back</button>
                                        <button class="btn green" type="submit">Create &amp; Upload Curriculum</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                        <?php if(($postedCurricula ?? collect())->count()): ?>
                            <div class="card" style="max-width:1100px;margin:20px auto 0;">
                                <div class="selected-header" style="align-items:center;">
                                    <div>
                                        <h2 style="margin-bottom:4px;">Posted Curricula</h2>
                                        <p class="muted" style="margin:0;">Active curricula published by other departments. You may use them, but only the original department can edit them.</p>
                                    </div>
                                </div>
                                <div class="curr-list">
                                    <?php $__currentLoopData = $postedCurricula; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curriculum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="curr-item">
                                            <div class="curr-name">
                                                <?php echo e($curriculum->program); ?>

                                                <?php if($curriculum->major): ?> • <?php echo e($curriculum->major); ?> <?php endif; ?>
                                            </div>
                                            <div class="muted" style="margin-top:4px;">
                                                Code: <strong><?php echo e($curriculum->curriculum_code); ?></strong>
                                                • Posted by: <strong><?php echo e($curriculum->department->name ?? 'Department'); ?></strong>
                                                • <?php echo e($curriculum->courses_count); ?> course(s)
                                                • <strong style="color:#267a3d;">Active / Available</strong>
                                            </div>
                                            <div class="actions">
                                                <a class="btn gray" href="<?php echo e(route('chair.curriculum', ['curriculum' => $curriculum->id])); ?>">View / Use</a>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        
                        <div class="card" style="max-width:1100px;margin:0 auto 20px;">
                            <div class="selected-header" style="align-items:center;">
                                <div>
                                    <h2 style="margin-bottom:4px;">My Department Curricula</h2>
                                    <p class="muted" style="margin:0;">Open a curriculum to manage its file and master courses.</p>
                                </div>
                                <button class="btn green" type="button" onclick="openModal('curriculumMethodModal')">+ New Curriculum</button>
                            </div>
                        </div>

                        <div class="card" style="max-width:1100px;margin:0 auto;">
                            <div class="curr-list">
                                <?php $__currentLoopData = $curricula; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curriculum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="curr-item">
                                        <div class="curr-name">
                                            <?php echo e($curriculum->program); ?>

                                            <?php if($curriculum->major): ?> • <?php echo e($curriculum->major); ?> <?php endif; ?>
                                        </div>
                                        <div class="muted" style="margin-top:4px;">
                                            Code: <strong><?php echo e($curriculum->curriculum_code); ?></strong>
                                            • <?php echo e($curriculum->courses_count); ?> course(s)
                                            • Status:
                                            <strong style="color:<?php echo e($curriculum->status === 'active' ? '#267a3d' : '#777'); ?>;">
                                                <?php echo e(ucfirst($curriculum->status)); ?>

                                            </strong>
                                        </div>
                                        <div class="actions">
                                            <a class="btn gray" href="<?php echo e(route('chair.curriculum', ['curriculum' => $curriculum->id])); ?>">Open</a>
                                            <?php if($curriculum->status !== 'active'): ?>
                                                <form method="POST" action="<?php echo e(route('chair.curriculum.activate', $curriculum->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button class="btn green" type="submit">Set Active</button>
                                                </form>
                                            <?php endif; ?>
                                            <form method="POST" action="<?php echo e(route('chair.curriculum.delete', $curriculum->id)); ?>" onsubmit="return confirm('Delete this curriculum? Its master courses and uploaded curriculum documents may also be removed.');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn red" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <?php if(($postedCurricula ?? collect())->count()): ?>
                            <div class="card" style="max-width:1100px;margin:20px auto 0;">
                                <div class="selected-header" style="align-items:center;">
                                    <div>
                                        <h2 style="margin-bottom:4px;">Posted Curricula from Other Departments</h2>
                                        <p class="muted" style="margin:0;">These active curricula are universal. Other Chairs may use them, but only the original department can edit, update, upload, or delete them.</p>
                                    </div>
                                </div>
                                <div class="curr-list">
                                    <?php $__currentLoopData = $postedCurricula; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curriculum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="curr-item">
                                            <div class="curr-name">
                                                <?php echo e($curriculum->program); ?>

                                                <?php if($curriculum->major): ?> • <?php echo e($curriculum->major); ?> <?php endif; ?>
                                            </div>
                                            <div class="muted" style="margin-top:4px;">
                                                Code: <strong><?php echo e($curriculum->curriculum_code); ?></strong>
                                                • Posted by: <strong><?php echo e($curriculum->department->name ?? 'Department'); ?></strong>
                                                • <?php echo e($curriculum->courses_count); ?> course(s)
                                                • <strong style="color:#267a3d;">Active / Available</strong>
                                            </div>
                                            <div class="actions">
                                                <a class="btn gray" href="<?php echo e(route('chair.curriculum', ['curriculum' => $curriculum->id])); ?>">View / Use</a>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <div class="modal-backdrop" id="curriculumMethodModal" aria-hidden="true">
                            <div class="modal-box" style="width:min(760px,100%);" role="dialog" aria-modal="true" aria-labelledby="curriculumMethodTitle">
                                <div class="modal-head">
                                    <div>
                                        <h2 id="curriculumMethodTitle">Create Curriculum</h2>
                                        <p>Choose how you want to create this curriculum.</p>
                                    </div>
                                    <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('curriculumMethodModal')">&times;</button>
                                </div>
                                <div class="method-grid">
                                    <button type="button" class="method-card" onclick="chooseCurriculumMethod('manual')">
                                        <div class="method-icon manual-icon">✎</div>
                                        <div class="method-title">Manually Adding Curriculum</div>
                                        <div class="method-text">Enter all curriculum information yourself and upload the official file later if needed.</div>
                                        <span class="method-link">Choose Manual &rarr;</span>
                                    </button>
                                    <button type="button" class="method-card" onclick="chooseCurriculumMethod('upload')">
                                        <div class="method-icon upload-icon">↑</div>
                                        <div class="method-title">Via Upload</div>
                                        <div class="method-text">Enter the curriculum information and upload the official curriculum file at the same time.</div>
                                        <span class="method-link">Choose Upload &rarr;</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="modal-backdrop" id="newCurriculumModal" aria-hidden="true">
                            <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="newCurriculumTitle">
                                <div class="modal-head">
                                    <div><h2 id="newCurriculumTitle">Manually Add Curriculum</h2><p>Enter the curriculum information first. After creation, enter all courses manually in the curriculum checklist or upload the official file.</p></div>
                                    <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('newCurriculumModal')">&times;</button>
                                </div>
                                <form method="POST" action="<?php echo e(route('chair.curriculum.store')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="field"><label>Program</label><input name="program" value="<?php echo e(old('program')); ?>" placeholder="Bachelor of Science in Information Technology" required></div>
                                    <div class="field"><label>Major <span style="font-weight:400">(Optional)</span></label><input name="major" value="<?php echo e(old('major')); ?>" placeholder="Network Administration"></div>
                                    <div class="field"><label>Curriculum Code</label><input name="curriculum_code" value="<?php echo e(old('curriculum_code')); ?>" placeholder="BSIT-2015-2022" required></div>
                                    <div class="field"><label>Status</label><select name="status"><option value="active" <?php if(old('status','active') === 'active'): echo 'selected'; endif; ?>>Active</option><option value="inactive" <?php if(old('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option></select></div>
                                    <div class="modal-actions"><button class="btn gray" type="button" onclick="backToCurriculumMethod('newCurriculumModal')">Back</button><button class="btn green" type="submit">Create Curriculum</button></div>
                                </form>
                            </div>
                        </div>

                        
                        <div class="modal-backdrop" id="uploadNewCurriculumModal" aria-hidden="true">
                            <div class="modal-box large" role="dialog" aria-modal="true" aria-labelledby="uploadNewCurriculumTitle">
                                <div class="modal-head">
                                    <div><h2 id="uploadNewCurriculumTitle">Create Curriculum via Upload</h2><p>Enter the curriculum information and upload the official file in one step.</p></div>
                                    <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('uploadNewCurriculumModal')">&times;</button>
                                </div>
                                <form method="POST" action="<?php echo e(route('chair.curriculum.store.upload')); ?>" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-grid">
                                        <div><label>Program</label><input name="program" value="<?php echo e(old('program')); ?>" placeholder="Bachelor of Science in Information Technology" required></div>
                                        <div><label>Major <span style="font-weight:400">(Optional)</span></label><input name="major" value="<?php echo e(old('major')); ?>" placeholder="Network Administration"></div>
                                        <div><label>Curriculum Code</label><input name="curriculum_code" value="<?php echo e(old('curriculum_code')); ?>" placeholder="BSIT-2015-2022" required></div>
                                        <div><label>Status</label><select name="status"><option value="active" <?php if(old('status','active') === 'active'): echo 'selected'; endif; ?>>Active</option><option value="inactive" <?php if(old('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option></select></div>
                                        <div><label>Curriculum Files</label><input id="uploadNewCurriculumFiles" type="file" name="curriculum_file[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" multiple required><div id="uploadNewCurriculumFilesInfo" class="muted" style="margin-top:6px;">Select one or multiple PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG or WEBP files. Maximum 50 MB per file.</div></div>
                                    </div>
                                    <div class="upload-note"><strong>Via Upload:</strong> You can select multiple curriculum files at once. All detected courses are merged into the Master Course List and grouped by year level and semester.</div>
                                    <div class="modal-actions"><button class="btn gray" type="button" onclick="backToCurriculumMethod('uploadNewCurriculumModal')">Back</button><button class="btn green" type="submit">Create &amp; Upload Curriculum</button></div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>

                    
                    <div class="grid">

                        
                        <div class="card" style="margin-bottom:0;">
                            <div class="selected-header" style="align-items:center;">
                                <div>
                                    <h2 style="margin-bottom:4px;"><?php echo e($selectedCurriculum->program); ?><?php if($selectedCurriculum->major): ?> • <?php echo e($selectedCurriculum->major); ?> <?php endif; ?></h2>
                                    <div class="muted">
                                        <?php echo e($selectedCurriculum->curriculum_code); ?>

                                        • <?php echo e($selectedCurriculum->courses->count()); ?> course(s)
                                        • Status: <strong style="color:<?php echo e($selectedCurriculum->status === 'active' ? '#267a3d' : '#777'); ?>;"><?php echo e(ucfirst($selectedCurriculum->status)); ?></strong>
                                    </div>
                                </div>
                                <div class="actions" style="display:flex;gap:8px;align-items:center;">
                                    <?php if($isCurriculumOwner): ?>
                                        <?php if($selectedCurriculum->status !== 'active'): ?>
                                            <form method="POST" action="<?php echo e(route('chair.curriculum.activate', $selectedCurriculum->id)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button class="btn green" type="submit">Set Active</button>
                                            </form>
                                        <?php endif; ?>
                                        <span class="status-badge" style="padding:7px 10px;border-radius:999px;background:#eaf7ec;color:#267a3d;font-size:11px;font-weight:800;">OWNER — EDITABLE</span>
                                    <?php else: ?>
                                        <span class="status-badge" style="padding:7px 10px;border-radius:999px;background:#f1f5f2;color:#53645a;font-size:11px;font-weight:800;">POSTED — READ ONLY</span>
                                    <?php endif; ?>
                                    <a class="btn back-btn top-action" href="<?php echo e(route('chair.curriculum')); ?>">← Back</a>
                                </div>
                            </div>
                        </div>

                        
                        <div>

                            <?php if(!$isCurriculumOwner): ?>
                                <div class="card" style="margin-bottom:18px;border-left:4px solid #2e7d32;">
                                    <h3 style="margin:0 0 5px;">Universal Posted Curriculum</h3>
                                    <p class="muted" style="margin:0;">
                                        This curriculum was posted by <strong><?php echo e($selectedCurriculum->department->name ?? 'another department'); ?></strong>.
                                        You can view and use its master courses in Faculty &amp; Subjects, but you cannot edit, upload, add, update, or delete its curriculum data.
                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if($isCurriculumOwner): ?>
                            
                            <div class="card">
                                <div class="modal-trigger-card">
                                    <div>
                                        <h3>Curriculum Information</h3>
                                        <p>Edit program, major, curriculum code, or status.</p>
                                    </div>
                                    <button class="btn green" type="button" onclick="openModal('updateCurriculumModal')">Edit Curriculum</button>
                                </div>
                            </div>

                            <div class="modal-backdrop" id="updateCurriculumModal" aria-hidden="true">
                                <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="updateCurriculumTitle">
                                    <div class="modal-head">
                                        <div>
                                            <h2 id="updateCurriculumTitle">Edit Curriculum</h2>
                                            <p>Update the information for <strong><?php echo e($selectedCurriculum->curriculum_code); ?></strong>.</p>
                                        </div>
                                        <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('updateCurriculumModal')">&times;</button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('chair.curriculum.update', $selectedCurriculum->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="form-grid">
                                            <div><label>Program</label><input name="program" value="<?php echo e($selectedCurriculum->program); ?>" required></div>
                                            <div><label>Major <span style="font-weight:400">(Optional)</span></label><input name="major" value="<?php echo e($selectedCurriculum->major); ?>"></div>
                                            <div><label>Curriculum Code</label><input name="curriculum_code" value="<?php echo e($selectedCurriculum->curriculum_code); ?>" required></div>
                                            <div><label>Status</label><select name="status"><option value="active" <?php if($selectedCurriculum->status === 'active'): echo 'selected'; endif; ?>>Active</option><option value="inactive" <?php if($selectedCurriculum->status === 'inactive'): echo 'selected'; endif; ?>>Inactive</option></select></div>
                                        </div>
                                        <div class="modal-actions"><button class="btn gray" type="button" onclick="closeModal('updateCurriculumModal')">Cancel</button><button class="btn green" type="submit">Save Changes</button></div>
                                    </form>
                                </div>
                            </div>

                            
                            <div class="card">
                                <div class="modal-trigger-card">
                                    <div>
                                        <h3>Official Curriculum File</h3>
                                        <p>Upload one or multiple official files separately. Existing master courses are kept and merged by default. Use Rebuild Master Course List when correcting an earlier import.</p>
                                    </div>
                                    <button class="btn green" type="button" onclick="openModal('uploadCurriculumModal')">Upload File</button>
                                </div>
                                <?php $__empty_1 = true; $__currentLoopData = $selectedCurriculum->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="document" style="margin-top:12px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                                        <div>
                                            <div class="muted">Uploaded curriculum file</div>
                                            <a href="<?php echo e(asset('storage/'.$document->file_path)); ?>" target="_blank" rel="noopener">📄 <?php echo e($document->original_name); ?></a>
                                        </div>
                                        <?php if($isCurriculumOwner): ?>
                                            <form method="POST" action="<?php echo e(route('chair.curriculum.document.delete', [$selectedCurriculum->id, $document->id])); ?>" onsubmit="return confirm('Delete this uploaded file only? The curriculum and Master Course List will remain.');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn red">Delete File</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="small-note">No curriculum file has been uploaded yet.</div>
                                <?php endif; ?>
                            </div>

                            <div class="modal-backdrop" id="uploadCurriculumModal" aria-hidden="true">
                                <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="uploadCurriculumTitle">
                                    <div class="modal-head">
                                        <div>
                                            <h2 id="uploadCurriculumTitle">Upload Curriculum</h2>
                                            <p>Add one or multiple official files for <strong><?php echo e($selectedCurriculum->curriculum_code); ?></strong>. Their courses will be merged into the Master Course List.</p>
                                        </div>
                                        <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('uploadCurriculumModal')">&times;</button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('chair.curriculum.upload', $selectedCurriculum->id)); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="field">
                                            <label>Curriculum Files</label>
                                            <input id="uploadCurriculumFiles" type="file" name="curriculum_file[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" multiple required>
                                            <div id="uploadCurriculumFilesInfo" class="muted" style="margin-top:6px;">Select one or multiple PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG or WEBP files. Maximum 50 MB per file.</div>
                                        </div>
                                        <label style="display:flex;align-items:flex-start;gap:8px;margin-top:12px;font-size:12px;line-height:1.4;">
                                            <input type="checkbox" name="replace_master" value="1" style="margin-top:2px;">
                                            <span><strong>Rebuild Master Course List</strong><br>Use this when correcting an earlier bad import. It clears the old imported course rows and rebuilds them from the selected official file(s).</span>
                                        </label>
                                        <div class="modal-actions"><button class="btn gray" type="button" onclick="closeModal('uploadCurriculumModal')">Cancel</button><button class="btn green" type="submit">Upload Curriculum</button></div>
                                    </form>
                                </div>
                            </div>

                            
                            <div class="card">
                                <div class="modal-trigger-card">
                                    <div>
                                        <h3>Master Courses</h3>
                                        <p>Add a course manually. The complete master course list stays at the bottom.</p>
                                    </div>
                                    <button class="btn green" type="button" onclick="openModal('addMasterCourseModal')">+ Add Course</button>
                                </div>
                            </div>

                            <div class="modal-backdrop" id="addMasterCourseModal" aria-hidden="true">
                                <div class="modal-box large" role="dialog" aria-modal="true" aria-labelledby="addMasterCourseTitle">
                                    <div class="modal-head">
                                        <div>
                                            <h2 id="addMasterCourseTitle">Add Master Course</h2>
                                            <p>Manually add a course to <strong><?php echo e($selectedCurriculum->curriculum_code); ?></strong>.</p>
                                        </div>
                                        <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('addMasterCourseModal')">&times;</button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('chair.curriculum.course.store', $selectedCurriculum->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-grid">
                                            <div><label>Course Code</label><input name="course_code" placeholder="IT 111" required></div>
                                            <div><label>Course Title</label><input name="subject" placeholder="Introduction to Computing (Lec)" required></div>
                                            <div><label>Units - Lec</label><input type="number" name="lec_units" min="0" max="20" step="0.5" value="3"></div>
                                            <div><label>Units - Lab</label><input type="number" name="lab_units" min="0" max="20" step="0.5" value="0"></div>
                                            <div><label>Hours/Week - Lec</label><input type="number" name="lec_hours" min="0" max="30" step="0.5" value="3"></div>
                                            <div><label>Hours/Week - Lab</label><input type="number" name="lab_hours" min="0" max="30" step="0.5" value="0"></div>
                                            <div><label>Year Level</label><select name="year_level" required><option>1st Year</option><option>2nd Year</option><option>3rd Year</option><option>4th Year</option></select></div>
                                            <div><label>Semester</label><select name="semester" required><option>1st Semester</option><option>2nd Semester</option></select></div>
                                            <div><label>Subject Type</label><select name="subject_type"><option value="Lec">Lec</option><option value="Lab">Lab</option><option value="Lec/Lab">Lec/Lab</option></select></div>
                                            <div><label>Grade</label><input name="grade" placeholder=""></div>
                                            <div class="full"><label>Pre-Requisite</label><input name="prerequisite" placeholder="None / IT 113"></div>
                                        </div>
                                        <div class="modal-actions"><button class="btn gray" type="button" onclick="closeModal('addMasterCourseModal')">Cancel</button><button class="btn green" type="submit">Add Master Course</button></div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal-backdrop" id="deleteCurriculumModal" aria-hidden="true">
                                <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteCurriculumTitle">
                                    <div class="modal-head">
                                        <div>
                                            <h2 id="deleteCurriculumTitle">Delete Curriculum?</h2>
                                            <p>This action will remove the selected curriculum and its related records according to your database relationships.</p>
                                        </div>
                                        <button class="modal-close" type="button" aria-label="Close" onclick="closeModal('deleteCurriculumModal')">&times;</button>
                                    </div>
                                    <div class="danger-note">
                                        You are about to delete <strong><?php echo e($selectedCurriculum->curriculum_code); ?></strong>. Make sure you no longer need its master courses and uploaded curriculum file.
                                    </div>
                                    <form method="POST" action="<?php echo e(route('chair.curriculum.delete', $selectedCurriculum->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <div class="modal-actions">
                                            <button class="btn gray" type="button" onclick="closeModal('deleteCurriculumModal')">Cancel</button>
                                            <button class="btn red" type="submit">Yes, Delete Curriculum</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <?php endif; ?>

                            
                            <div class="card master-course-card checklist-editor-card">
                                <div class="master-list-header">
                                    <div>
                                        <div class="section-title" style="margin-top:0;">Master Course List</div>
                                        <div class="muted">
                                            Editable BSU-style curriculum checklist. Each year starts with 16 rows and 13 visible columns.
                                            You can edit cells, add or delete rows, add extra columns, and resize rows/columns.
                                        </div>
                                    </div>
                                    <div class="master-grid-actions">
                                        <?php if($isCurriculumOwner): ?>
                                            <button type="button" class="btn gray" id="masterGridEditBtn" onclick="toggleMasterGridEdit()">Edit Table</button>
                                            <button type="button" class="btn green" id="masterGridSaveBtn" onclick="saveMasterGrid()" disabled>Save Table</button>
                                        <?php else: ?>
                                            <span class="status-badge" style="padding:7px 10px;border-radius:999px;background:#f1f5f2;color:#53645a;font-size:11px;font-weight:800;">READ ONLY</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if($isCurriculumOwner): ?>
                                <form id="masterGridSaveForm" method="POST" action="<?php echo e(route('chair.curriculum.grid.save', $selectedCurriculum->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="layout" id="masterGridLayoutInput">
                                </form>
                                <?php endif; ?>

                                <?php
                                    $defaultLeafHeaders = [
                                        'Year Level',
                                        'Course Code', 'Course Title', 'Lec', 'Lab', 'Lec', 'Lab', 'Pre-Requisite', 'Grade',
                                        'Course Code', 'Course Title', 'Lec', 'Lab', 'Lec', 'Lab', 'Pre-Requisite', 'Grade',
                                    ];

                                    $defaultWidths = [
                                        105, 105, 260, 52, 52, 60, 60, 150, 65,
                                        105, 260, 52, 52, 60, 60, 150, 65,
                                    ];

                                    $defaultYears = [
                                        '1st Year' => 'FIRST YEAR',
                                        '2nd Year' => 'SECOND YEAR',
                                        '3rd Year' => 'THIRD YEAR',
                                        '4th Year' => 'FOURTH YEAR',
                                    ];

                                    $layoutYears = $gridLayout['years'] ?? [];
                                ?>

                                <div id="masterGridRoot">
                                    <?php $__currentLoopData = $defaultYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearValue => $yearLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $yearGrid = $layoutYears[$yearValue] ?? [];
                                            $rows = $yearGrid['rows'] ?? [];
                                            $widths = $yearGrid['widths'] ?? [105,105,300,75,90,170,65,105,300,75,90,170,65];
                                            $headers = $yearGrid['headers'] ?? [
                                                'Year Level','Course Code','Course Title','Units','Hours/Week','Pre-Requisite','Grade',
                                                'Course Code','Course Title','Units','Hours/Week','Pre-Requisite','Grade'
                                            ];
                                            $rowHeights = $yearGrid['rowHeights'] ?? [];
                                        ?>

                                        <section class="checklist-year-block" data-year="<?php echo e($yearValue); ?>">
                                            <div class="checklist-year-toolbar">
                                                <div>
                                                    <div class="checklist-year-title"><?php echo e($yearLabel); ?></div>
                                                    <div class="muted">
                                                        16 default rows • 13 base columns • First Semester + Second Semester
                                                    </div>
                                                </div>
                                                <div class="grid-row-column-actions">
                                                    <button type="button" class="btn gray year-print-btn" onclick="printCurriculumYear(this)">Print This Year</button>
                                                    <?php if($isCurriculumOwner): ?>
                                                        <button type="button" class="btn gray grid-edit-only" onclick="addGridRow(this)">+ Row</button>
                                                        <button type="button" class="btn red grid-edit-only" onclick="deleteGridRow(this)">− Row</button>
                                                        <button type="button" class="btn gray grid-edit-only" onclick="addGridColumn(this)">+ Column</button>
                                                        <button type="button" class="btn red grid-edit-only" onclick="deleteGridColumn(this)">− Column</button>
                                                    <?php else: ?>
                                                        <span class="muted" style="font-size:11px;">Posted curriculum · read only</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="checklist-table-scroll">
                                                <table class="checklist-grid-table" data-year="<?php echo e($yearValue); ?>">
                                                    <colgroup>
                                                        <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hi => $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <col style="width: <?php echo e((int)($widths[$hi] ?? 100)); ?>px;" data-col-index="<?php echo e($hi); ?>">
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </colgroup>
                                                    <thead>
                                                        <tr class="checklist-group-row">
                                                            <th class="year-head" rowspan="2" data-col-index="0"><?php echo e($yearLabel); ?></th>
                                                            <th class="group-head first-sem-group" colspan="6">FIRST SEMESTER</th>
                                                            <th class="group-head second-sem-group" colspan="6">SECOND SEMESTER</th>
                                                            <?php if(count($headers) > 13): ?>
                                                                <th class="group-head additional-group" colspan="<?php echo e(count($headers) - 13); ?>">ADDITIONAL COLUMNS</th>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <tr class="checklist-label-row">
                                                            <th data-col-index="1"><?php echo e($headers[1] ?? 'Course Code'); ?></th>
                                                            <th data-col-index="2"><?php echo e($headers[2] ?? 'Course Title'); ?></th>
                                                            <th data-col-index="3"><?php echo e($headers[3] ?? 'Units'); ?></th>
                                                            <th data-col-index="4"><?php echo e($headers[4] ?? 'Hours/Week'); ?></th>
                                                            <th data-col-index="5"><?php echo e($headers[5] ?? 'Pre-Requisite'); ?></th>
                                                            <th data-col-index="6"><?php echo e($headers[6] ?? 'Grade'); ?></th>
                                                            <th data-col-index="7"><?php echo e($headers[7] ?? 'Course Code'); ?></th>
                                                            <th data-col-index="8"><?php echo e($headers[8] ?? 'Course Title'); ?></th>
                                                            <th data-col-index="9"><?php echo e($headers[9] ?? 'Units'); ?></th>
                                                            <th data-col-index="10"><?php echo e($headers[10] ?? 'Hours/Week'); ?></th>
                                                            <th data-col-index="11"><?php echo e($headers[11] ?? 'Pre-Requisite'); ?></th>
                                                            <th data-col-index="12"><?php echo e($headers[12] ?? 'Grade'); ?></th>
                                                            <?php for($hi = 13; $hi < count($headers); $hi++): ?>
                                                                <th data-col-index="<?php echo e($hi); ?>" class="custom-col-head"><?php echo e($headers[$hi] ?? ('Column '.($hi+1))); ?></th>
                                                            <?php endfor; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ri => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $row = array_values($row);
                                                                $row = array_pad($row, max(13, count($headers)), '');
                                                                $height = (int)($rowHeights[$ri] ?? 42);
                                                            ?>
                                                            <tr data-row-index="<?php echo e($ri); ?>" style="height:<?php echo e(max(30,$height)); ?>px;">
                                                                <?php for($ci = 0; $ci < count($headers); $ci++): ?>
                                                                    <td
                                                                        data-col-index="<?php echo e($ci); ?>"
                                                                        data-row-index="<?php echo e($ri); ?>"
                                                                        <?php if($ci === 1): ?> data-first-course-id="<?php echo e($yearGrid['rowMeta'][$ri]['first_course_id'] ?? ''); ?>" <?php endif; ?>
                                                                        <?php if($ci === 7): ?> data-second-course-id="<?php echo e($yearGrid['rowMeta'][$ri]['second_course_id'] ?? ''); ?>" <?php endif; ?>
                                                                        style="height:<?php echo e(max(30,$height)); ?>px;"
                                                                    ><?php echo e($row[$ci] ?? ''); ?></td>
                                                                <?php endfor; ?>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="checklist-total-bar">
                                                <div><strong>Total/GWA:</strong> <span class="total-units-first">0</span> units · <strong>GWA:</strong> <span class="gwa-first">—</span></div>
                                                <div><strong>Total/GWA:</strong> <span class="total-units-second">0</span> units · <strong>GWA:</strong> <span class="gwa-second">—</span></div>
                                            </div>

                                            <div class="checklist-help">
                                                <span>Click <strong>Edit Table</strong> to edit course rows, titles, units, hours, prerequisites and grades.</span>
                                                <span>+ Row / − Row and + Column / − Column automatically enter edit mode.</span>
                                                <span>Drag the right edge of a column or bottom edge of a row to resize it.</span>
                                            </div>
                                        </section>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="small-note">
                                    <strong>BSU-style master checklist:</strong>
                                    each year starts with 16 rows and the BSU checklist's 13 visible base columns. The first column identifies the year;
                                    columns 2–7 represent First Semester and columns 8–13 represent Second Semester.
                                    Extra columns can be added and removed without changing the standard 13-column template.
                                </div>
                            </div>
                            </div>

                        </div>
                    </div>

                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        const firstField = modal.querySelector('input, select, button.modal-close, button.method-card');
        if (firstField) setTimeout(() => firstField.focus(), 80);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        if (!document.querySelector('.modal-backdrop.show')) {
            document.body.classList.remove('modal-open');
        }
    }

    function chooseCurriculumMethod(method) {
        closeModal('curriculumMethodModal');

        if (method === 'manual') {
            openModal('newCurriculumModal');
            return;
        }

        if (method === 'upload') {
            openModal('uploadNewCurriculumModal');
        }
    }

    function backToCurriculumMethod(currentModal) {
        closeModal(currentModal);
        openModal('curriculumMethodModal');
    }

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('modal-backdrop')) {
            closeModal(event.target.id);
        }
    });

    document.addEventListener('dragstart', function (event) {
        const cell = event.target.closest('.checklist-grid-table tbody td[data-col-index="1"], .checklist-grid-table tbody td[data-col-index="7"]');
        if (!cell || !curriculumIsOwner || !masterGridEditing || !cell.dataset.courseDragId) {
            event.preventDefault();
            return;
        }
        event.dataTransfer.setData('text/plain', cell.dataset.courseDragId);
        event.dataTransfer.effectAllowed = 'move';
        window.masterGridDraggedCell = cell;
    });

    document.addEventListener('dragover', function (event) {
        const target = event.target.closest('.checklist-grid-table tbody td[data-col-index="1"], .checklist-grid-table tbody td[data-col-index="7"]');
        if (!target || !curriculumIsOwner || !masterGridEditing || !window.masterGridDraggedCell) return;
        event.preventDefault();
        target.classList.add('drop-course-target');
        event.dataTransfer.dropEffect = 'move';
    });

    document.addEventListener('dragleave', function (event) {
        const target = event.target.closest('.checklist-grid-table tbody td[data-col-index="1"], .checklist-grid-table tbody td[data-col-index="7"]');
        if (target) target.classList.remove('drop-course-target');
    });

    document.addEventListener('drop', function (event) {
        const target = event.target.closest('.checklist-grid-table tbody td[data-col-index="1"], .checklist-grid-table tbody td[data-col-index="7"]');
        if (!target || !window.masterGridDraggedCell || !curriculumIsOwner || !masterGridEditing) return;
        event.preventDefault();
        const source = window.masterGridDraggedCell;
        moveGridCourse(source, target);
        window.masterGridDraggedCell = null;
        bindCourseDragDrop();
        setGridControlState();
    });

    document.addEventListener('click', function (event) {
        const row = event.target.closest('.checklist-grid-table tbody tr');
        if (row && masterGridEditing) {
            selectGridRow(row);
        }

        const header = event.target.closest('.checklist-label-row th[data-col-index]');
        if (header && masterGridEditing && !event.target.classList.contains('resize-col-handle')) {
            selectGridColumn(header);
        }
    });

    function bindMultiFileInfo(inputId, infoId) {
        const input = document.getElementById(inputId);
        const info = document.getElementById(infoId);

        if (!input || !info) return;

        input.addEventListener('change', function () {
            const files = Array.from(input.files || []);

            if (!files.length) {
                info.textContent =
                    'Select one or multiple PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG or WEBP files. Maximum 50 MB per file.';
                return;
            }

            info.innerHTML =
                '<strong>' + files.length +
                ' file(s) selected:</strong> ' +
                files.map(file => file.name).join(', ');
        });
    }

    bindMultiFileInfo(
        'uploadNewCurriculumFiles',
        'uploadNewCurriculumFilesInfo'
    );

    bindMultiFileInfo(
        'uploadCurriculumFiles',
        'uploadCurriculumFilesInfo'
    );


    /* ================================================================
       MASTER COURSE CHECKLIST GRID EDITOR
       ================================================================ */
    const curriculumIsOwner = <?php echo json_encode((bool)($isCurriculumOwner ?? false), 15, 512) ?>;
    let masterGridEditing = false;
    let masterGridSelectedRow = null;
    let masterGridSelectedCol = null;

    function masterGridTables() {
        return Array.from(document.querySelectorAll('.checklist-grid-table'));
    }

    function enterMasterGridEditMode() {
        if (!curriculumIsOwner) return;
        if (!masterGridEditing) {
            masterGridEditing = true;
            setGridControlState();
        }
    }

    function printCurriculumYear(button) {
        const block = button.closest('.checklist-year-block');
        if (!block) return;
        document.querySelectorAll('.checklist-year-block').forEach(item => item.classList.remove('print-target'));
        block.classList.add('print-target');
        document.body.classList.add('printing-year');
        const cleanup = () => {
            document.body.classList.remove('printing-year');
            document.querySelectorAll('.checklist-year-block').forEach(item => item.classList.remove('print-target'));
            window.removeEventListener('afterprint', cleanup);
        };
        window.addEventListener('afterprint', cleanup);
        window.print();
        setTimeout(cleanup, 1200);
    }

    function getCoursePair(cell) {
        if (!cell) return null;
        const index = Number(cell.dataset.colIndex);
        if (index === 1) return { start: 1, idKey: 'firstCourseId', semester: '1st Semester' };
        if (index === 7) return { start: 7, idKey: 'secondCourseId', semester: '2nd Semester' };
        return null;
    }

    function getPairCells(row, start) {
        return Array.from({length: 6}, (_, offset) =>
            row.querySelector('td[data-col-index="' + (start + offset) + '"]')
        );
    }

    function pairHasContent(cells) {
        return cells.some(cell => cell && cell.innerText.trim() !== '');
    }

    function clearPair(cells) {
        cells.forEach(cell => {
            if (cell) cell.innerText = '';
        });
    }

    function moveGridCourse(sourceCell, targetCell) {
        if (!curriculumIsOwner || !masterGridEditing) return;
        const sourceRow = sourceCell.closest('tr');
        const targetRow = targetCell.closest('tr');
        const sourceTable = sourceCell.closest('table');
        const targetTable = targetCell.closest('table');
        const sourcePair = getCoursePair(sourceCell);
        const targetPair = getCoursePair(targetCell);
        if (!sourceRow || !targetRow || !sourcePair || !targetPair) return;
        if (!sourceCell.dataset.courseDragId) return;

        const sourceCells = getPairCells(sourceRow, sourcePair.start);
        const targetCells = getPairCells(targetRow, targetPair.start);
        if (!sourceCells.every(Boolean) || !targetCells.every(Boolean)) return;
        if (pairHasContent(targetCells)) {
            alert('Choose an empty semester slot before moving this course.');
            return;
        }

        const courseId = sourceCell.dataset.courseDragId;
        const values = sourceCells.map(cell => cell.innerText);
        const sourceMetaCell = sourceRow.querySelector('td[data-col-index="' + sourcePair.start + '"]');
        const targetMetaCell = targetRow.querySelector('td[data-col-index="' + targetPair.start + '"]');
        const sourceIdAttribute = sourcePair.start === 1 ? 'firstCourseId' : 'secondCourseId';
        const targetIdAttribute = targetPair.start === 1 ? 'firstCourseId' : 'secondCourseId';

        targetCells.forEach((cell, index) => {
            cell.innerText = values[index] || '';
            if (index === 0) cell.dataset.courseDragId = courseId;
        });
        sourceCells.forEach(cell => {
            delete cell.dataset.courseDragId;
        });

        if (sourceMetaCell) {
            if (sourcePair.start === 1) sourceMetaCell.dataset.firstCourseId = '';
            else sourceMetaCell.dataset.secondCourseId = '';
        }
        if (targetMetaCell) {
            if (targetPair.start === 1) targetMetaCell.dataset.firstCourseId = courseId;
            else targetMetaCell.dataset.secondCourseId = courseId;
        }

        // Keep the row's year label and all unrelated cells untouched.
        calculateAllSemesterTotals();
        sourceTable.querySelectorAll('td.drop-course-target').forEach(cell => cell.classList.remove('drop-course-target'));
        targetTable.querySelectorAll('td.drop-course-target').forEach(cell => cell.classList.remove('drop-course-target'));
    }

    function bindCourseDragDrop() {
        masterGridTables().forEach(table => {
            table.querySelectorAll('tbody td[data-col-index="1"], tbody td[data-col-index="7"]').forEach(cell => {
                const row = cell.closest('tr');
                const start = Number(cell.dataset.colIndex);
                const id = start === 1 ? row?.querySelector('td[data-col-index="1"]')?.dataset.firstCourseId : row?.querySelector('td[data-col-index="7"]')?.dataset.secondCourseId;
                if (id) {
                    cell.dataset.courseDragId = id;
                    cell.classList.add('drag-course-cell');
                    cell.draggable = true;
                } else {
                    delete cell.dataset.courseDragId;
                    cell.draggable = false;
                    cell.classList.remove('drag-course-cell');
                }
            });
        });
    }

    function setGridControlState() {
        const editBtn = document.getElementById('masterGridEditBtn');
        const saveBtn = document.getElementById('masterGridSaveBtn');

        if (editBtn) {
            editBtn.textContent = masterGridEditing ? 'Exit Edit Mode' : 'Edit Table';
            editBtn.classList.toggle('green', masterGridEditing);
            editBtn.classList.toggle('gray', !masterGridEditing);
        }

        if (saveBtn) saveBtn.disabled = !masterGridEditing;

        masterGridTables().forEach(table => {
            table.classList.toggle('editing', masterGridEditing);

            table.querySelectorAll('td[data-col-index]').forEach(td => {
                if (masterGridEditing) td.setAttribute('contenteditable', 'true');
                else td.removeAttribute('contenteditable');
            });

            table.querySelectorAll('th[data-col-index]').forEach(th => {
                if (masterGridEditing && !th.classList.contains('year-head')) {
                    th.setAttribute('contenteditable', 'true');
                } else {
                    th.removeAttribute('contenteditable');
                }
            });
        });

        bindGridResizeHandles();
        bindCourseDragDrop();
    }

    function toggleMasterGridEdit() {
        if (!curriculumIsOwner) return;
        if (masterGridEditing) {
            const exit = confirm('Exit edit mode? Click Save Table first if you want to keep your changes.');
            if (!exit) return;
            masterGridEditing = false;
        } else {
            masterGridEditing = true;
        }
        setGridControlState();
    }

    function selectGridRow(tr) {
        enterMasterGridEditMode();
        const table = tr.closest('table');
        table.querySelectorAll('tbody tr').forEach(row => row.classList.remove('selected-row'));
        tr.classList.add('selected-row');
        masterGridSelectedRow = tr;
    }

    function selectGridColumn(th) {
        enterMasterGridEditMode();
        const table = th.closest('table');
        const colIndex = parseInt(th.dataset.colIndex || '-1', 10);
        if (Number.isNaN(colIndex) || colIndex < 0) return;

        table.querySelectorAll('.selected-column').forEach(el => el.classList.remove('selected-column'));
        table.querySelectorAll('.selected-column-cell').forEach(el => el.classList.remove('selected-column-cell'));

        table.querySelectorAll('[data-col-index="' + colIndex + '"]').forEach(el => {
            if (el.tagName === 'TH') el.classList.add('selected-column');
            if (el.tagName === 'TD') el.classList.add('selected-column-cell');
        });

        masterGridSelectedCol = { table, index: colIndex };
    }

    function addGridRow(button) {
        if (!curriculumIsOwner) return;
        enterMasterGridEditMode();
        const table = button.closest('.checklist-year-block').querySelector('.checklist-grid-table');
        const tbody = table.querySelector('tbody');
        const currentCols = table.querySelectorAll('col').length;
        const yearLabel = table.closest('.checklist-year-block').querySelector('.checklist-year-title')?.textContent.trim() || 'YEAR';
        const rowIndex = tbody.children.length;
        const row = document.createElement('tr');
        row.dataset.rowIndex = String(rowIndex);
        row.style.height = '42px';

        for (let c = 0; c < currentCols; c++) {
            const td = document.createElement('td');
            td.dataset.colIndex = String(c);
            td.dataset.rowIndex = String(rowIndex);
            td.contentEditable = 'true';
            if (c === 1) td.dataset.firstCourseId = '';
            if (c === 7) td.dataset.secondCourseId = '';
            td.textContent = c === 0 ? yearLabel : '';
            td.style.height = '42px';
            row.appendChild(td);
        }

        tbody.appendChild(row);
        refreshRowIndexes(table);
        bindGridResizeHandles();
        selectGridRow(row);
    }

    function deleteGridRow(button) {
        if (!curriculumIsOwner) return;
        enterMasterGridEditMode();
        const table = button.closest('.checklist-year-block').querySelector('.checklist-grid-table');
        const row = table.querySelector('tbody tr.selected-row');

        if (!row) {
            alert('Select the row you want to delete first.');
            return;
        }

        if (table.querySelectorAll('tbody tr').length <= 1) {
            alert('At least one row must remain.');
            return;
        }

        if (!confirm('Delete the selected row? Any course in that row will be removed from the Master Course List when you save.')) {
            return;
        }

        row.remove();
        masterGridSelectedRow = null;
        refreshRowIndexes(table);
    }

    function addGridColumn(button) {
        if (!curriculumIsOwner) return;
        enterMasterGridEditMode();
        const block = button.closest('.checklist-year-block');
        const table = block.querySelector('.checklist-grid-table');
        const colgroup = table.querySelector('colgroup');
        const newIndex = colgroup.querySelectorAll('col').length;

        const col = document.createElement('col');
        col.dataset.colIndex = String(newIndex);
        col.style.width = '140px';
        colgroup.appendChild(col);

        const labelRow = table.querySelector('.checklist-label-row');
        const groupRow = table.querySelector('.checklist-group-row');
        const groupHead = table.querySelector('.additional-group');

        if (groupHead) {
            groupHead.colSpan = Math.max(1, parseInt(groupHead.colSpan || '1', 10) + 1);
        } else {
            const g = document.createElement('th');
            g.className = 'group-head additional-group';
            g.colSpan = 1;
            g.textContent = 'ADDITIONAL COLUMNS';
            groupRow.appendChild(g);
        }

        const th = document.createElement('th');
        th.dataset.colIndex = String(newIndex);
        th.className = 'custom-col-head';
        th.textContent = 'Column ' + (newIndex + 1);
        labelRow.appendChild(th);

        table.querySelectorAll('tbody tr').forEach((row, ri) => {
            const td = document.createElement('td');
            td.dataset.colIndex = String(newIndex);
            td.dataset.rowIndex = String(ri);
            td.contentEditable = 'true';
            td.textContent = '';
            row.appendChild(td);
        });

        setGridControlState();
    }

    function deleteGridColumn(button) {
        if (!curriculumIsOwner) return;
        enterMasterGridEditMode();
        const block = button.closest('.checklist-year-block');
        const table = block.querySelector('.checklist-grid-table');
        const cols = table.querySelectorAll('col');
        const count = cols.length;

        if (count <= 13) {
            alert('The standard BSU template keeps its 13 base columns. Add an extra column first, then you can delete the extra column.');
            return;
        }

        let index = masterGridSelectedCol && masterGridSelectedCol.table === table
            ? masterGridSelectedCol.index
            : count - 1;

        if (index < 13) index = count - 1;
        if (!confirm('Delete this additional column?')) return;

        cols[index].remove();
        table.querySelectorAll('[data-col-index="' + index + '"]').forEach(el => el.remove());

        table.querySelectorAll('[data-col-index]').forEach(el => {
            const current = parseInt(el.dataset.colIndex || '-1', 10);
            if (current > index) el.dataset.colIndex = String(current - 1);
        });

        table.querySelectorAll('col').forEach((col, i) => col.dataset.colIndex = String(i));

        const group = table.querySelector('.additional-group');
        if (group) {
            const newSpan = count - 1 - 13;
            if (newSpan <= 0) group.remove();
            else group.colSpan = newSpan;
        }

        masterGridSelectedCol = null;
        setGridControlState();
    }

    function refreshRowIndexes(table) {
        table.querySelectorAll('tbody tr').forEach((row, ri) => {
            row.dataset.rowIndex = String(ri);
            row.querySelectorAll('td[data-col-index]').forEach(td => td.dataset.rowIndex = String(ri));
        });
    }

    function calculateSemesterTotals(block) {
        const table = block.querySelector('.checklist-grid-table');
        if (!table) return;

        let firstUnits = 0;
        let secondUnits = 0;
        let firstWeighted = 0;
        let secondWeighted = 0;
        let firstGradedUnits = 0;
        let secondGradedUnits = 0;

        table.querySelectorAll('tbody tr').forEach(row => {
            const cells = {};
            row.querySelectorAll('td[data-col-index]').forEach(td => {
                cells[Number(td.dataset.colIndex)] = td.innerText.trim();
            });

            const first = parseGridPair(cells[3]);
            const second = parseGridPair(cells[9]);
            firstUnits += first[0];
            secondUnits += second[0];

            const grade1 = parseGrade(cells[6]);
            const grade2 = parseGrade(cells[12]);
            if (grade1 !== null && first[0] > 0) {
                firstWeighted += grade1 * first[0];
                firstGradedUnits += first[0];
            }
            if (grade2 !== null && second[0] > 0) {
                secondWeighted += grade2 * second[0];
                secondGradedUnits += second[0];
            }
        });

        block.querySelector('.total-units-first').textContent = formatNumber(firstUnits);
        block.querySelector('.total-units-second').textContent = formatNumber(secondUnits);
        block.querySelector('.gwa-first').textContent = firstGradedUnits ? (firstWeighted / firstGradedUnits).toFixed(2) : '—';
        block.querySelector('.gwa-second').textContent = secondGradedUnits ? (secondWeighted / secondGradedUnits).toFixed(2) : '—';
    }

    function calculateAllSemesterTotals() {
        document.querySelectorAll('.checklist-year-block').forEach(calculateSemesterTotals);
    }

    function parseGridPair(value) {
        const match = String(value || '').trim().match(/^(\d+(?:\.\d+)?)\s*\/\s*(\d+(?:\.\d+)?)$/);
        if (match) return [Number(match[1]), Number(match[2])];
        if (value && !Number.isNaN(Number(value))) return [Number(value), 0];
        return [0, 0];
    }

    function parseGrade(value) {
        const n = Number(String(value || '').trim());
        return Number.isFinite(n) && n > 0 ? n : null;
    }

    function formatNumber(value) {
        return Math.abs(value - Math.round(value)) < 0.0001 ? String(Math.round(value)) : value.toFixed(2).replace(/0+$/, '').replace(/\.$/, '');
    }

    function collectMasterGridLayout() {
        const layout = {version:3, years:{}};

        document.querySelectorAll('.checklist-year-block').forEach(block => {
            const year = block.dataset.year;
            const table = block.querySelector('.checklist-grid-table');
            const headers = [];
            const widths = [];

            table.querySelectorAll('col').forEach(col => {
                widths.push(Math.round(parseFloat(col.style.width) || col.getBoundingClientRect().width || 100));
            });

            table.querySelectorAll('.checklist-label-row th[data-col-index]').forEach(th => {
                const index = Number(th.dataset.colIndex);
                headers[index] = th.innerText.trim();
            });
            headers[0] = block.querySelector('.year-head')?.innerText.trim() || headers[0] || 'Year Level';

            for (let i = 0; i < widths.length; i++) headers[i] = headers[i] || ('Column ' + (i + 1));

            const rows = [];
            const rowMeta = [];
            const rowHeights = [];

            table.querySelectorAll('tbody tr').forEach(row => {
                const rowValues = [];
                row.querySelectorAll('td[data-col-index]').forEach(td => {
                    rowValues[Number(td.dataset.colIndex)] = td.innerText.trim();
                });
                while (rowValues.length < widths.length) rowValues.push('');
                rows.push(rowValues);
                rowMeta.push({
                    first_course_id: row.querySelector('td[data-col-index="1"]')?.dataset.firstCourseId || null,
                    second_course_id: row.querySelector('td[data-col-index="7"]')?.dataset.secondCourseId || null,
                });
                rowHeights.push(Math.round(row.getBoundingClientRect().height || 42));
            });

            layout.years[year] = {
                headers,
                widths,
                rows,
                rowMeta,
                rowHeights,
                yearLabel: block.querySelector('.year-head')?.innerText.trim() || year.toUpperCase(),
            };
        });

        return layout;
    }

    function saveMasterGrid() {
        enterMasterGridEditMode();
        calculateAllSemesterTotals();

        const input = document.getElementById('masterGridLayoutInput');
        const form = document.getElementById('masterGridSaveForm');
        if (!input || !form) return;

        input.value = JSON.stringify(collectMasterGridLayout());
        form.submit();
    }

    function bindGridResizeHandles() {
        masterGridTables().forEach(table => {
            table.querySelectorAll('.resize-col-handle').forEach(el => el.remove());
            table.querySelectorAll('.resize-row-handle').forEach(el => el.remove());

            table.querySelectorAll('thead th[data-col-index]').forEach(th => {
                if (th.classList.contains('group-head')) return;
                const handle = document.createElement('span');
                handle.className = 'resize-col-handle';
                th.appendChild(handle);

                handle.addEventListener('pointerdown', function (event) {
                    enterMasterGridEditMode();
                    event.preventDefault();
                    event.stopPropagation();
                    const index = parseInt(th.dataset.colIndex || '-1', 10);
                    const col = table.querySelector('col[data-col-index="' + index + '"]');
                    if (!col) return;
                    const startX = event.clientX;
                    const startWidth = th.getBoundingClientRect().width;

                    function move(ev) {
                        const width = Math.max(45, Math.min(600, startWidth + (ev.clientX - startX)));
                        col.style.width = Math.round(width) + 'px';
                    }
                    function stop() {
                        document.removeEventListener('pointermove', move);
                        document.removeEventListener('pointerup', stop);
                    }
                    document.addEventListener('pointermove', move);
                    document.addEventListener('pointerup', stop, {once:true});
                });
            });

            table.querySelectorAll('tbody tr').forEach(row => {
                const firstCell = row.querySelector('td[data-col-index="0"]');
                if (!firstCell) return;
                const handle = document.createElement('span');
                handle.className = 'resize-row-handle';
                firstCell.appendChild(handle);

                handle.addEventListener('pointerdown', function (event) {
                    enterMasterGridEditMode();
                    event.preventDefault();
                    event.stopPropagation();
                    const startY = event.clientY;
                    const startHeight = row.getBoundingClientRect().height;

                    function move(ev) {
                        const height = Math.max(30, Math.min(300, startHeight + (ev.clientY - startY)));
                        row.style.height = Math.round(height) + 'px';
                        row.querySelectorAll('td').forEach(td => td.style.height = Math.round(height) + 'px');
                    }
                    function stop() {
                        document.removeEventListener('pointermove', move);
                        document.removeEventListener('pointerup', stop);
                    }
                    document.addEventListener('pointermove', move);
                    document.addEventListener('pointerup', stop, {once:true});
                });
            });
        });
    }

    document.addEventListener('click', function (event) {
        const row = event.target.closest('.checklist-grid-table tbody tr');
        if (row && !event.target.classList.contains('resize-row-handle')) {
            selectGridRow(row);
        }

        const th = event.target.closest('.checklist-grid-table th[data-col-index]');
        if (th && !event.target.classList.contains('resize-col-handle')) {
            selectGridColumn(th);
        }
    });

    document.addEventListener('input', function (event) {
        if (event.target.closest('.checklist-grid-table')) {
            calculateAllSemesterTotals();
        }
    });

    setGridControlState();
    bindCourseDragDrop();
    calculateAllSemesterTotals();
</script>

<?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/chair/curriculum.blade.php ENDPATH**/ ?>
(function () {
    'use strict';

    const dayNames = { MON: 'Monday', TUE: 'Tuesday', WED: 'Wednesday', THU: 'Thursday', FRI: 'Friday', SAT: 'Saturday', SUN: 'Sunday' };

    function textFrom(block, selector) {
        return String(block.querySelector(selector)?.textContent || '').trim();
    }

    function formatTime(value) {
        if (typeof window.publishedFormatScheduleTime === 'function') {
            return window.publishedFormatScheduleTime(value);
        }
        return String(value || '').replace(/\s*[-–—]\s*/, ' – ').trim();
    }

    function appendField(list, label, value) {
        const clean = String(value || '').trim();
        if (!clean) return;
        const wrapper = document.createElement('div');
        wrapper.className = 'schedule-details-field';
        const term = document.createElement('dt');
        term.textContent = label;
        const description = document.createElement('dd');
        description.textContent = clean;
        wrapper.append(term, description);
        list.appendChild(wrapper);
    }

    function appendGroupListField(list, label, items) {
        const cleanItems = [...new Set((Array.isArray(items) ? items : []).map(value => String(value || '').trim()).filter(Boolean))];
        if (!cleanItems.length) return;
        const wrapper = document.createElement('div');
        wrapper.className = 'schedule-details-field';
        const term = document.createElement('dt');
        term.textContent = label;
        const description = document.createElement('dd');
        const listElement = document.createElement('ul');
        listElement.className = 'schedule-details-group-list';
        cleanItems.forEach(function(value) {
            const item = document.createElement('li');
            item.textContent = value;
            listElement.appendChild(item);
        });
        description.appendChild(listElement);
        wrapper.append(term, description);
        list.appendChild(wrapper);
    }

    function readSharedGroupItems(block) {
        const raw = String(block.dataset.detailSharedGroups || '').trim();
        if (raw) {
            try {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) {
                    return parsed.map(function(group) {
                        if (typeof group === 'string') return group;
                        return [group.program || group.program_code || '', group.major ? 'Major: ' + group.major : '', group.year_level || group.year || '', group.semester || '']
                            .filter(Boolean).join(' · ');
                    }).filter(Boolean);
                }
            } catch (_) {}
            return [raw];
        }
        const fromMarkup = textFrom(block, '.cell-shared-majors span') || textFrom(block, '.cell-shared-majors');
        return fromMarkup ? fromMarkup.split(/\s*[·•]\s*/).map(value => value.trim()).filter(Boolean) : [];
    }

    const lastClickedSchedule = new WeakMap();

    function handleScheduleClick(block, event) {
        if (event?.target instanceof Element && event.target.closest('.schedule-resize-handle, .cell-shared-cohort-summary')) return;
        if (block.dataset.detailsSingleClick === '1') {
            openScheduleDetails(block);
            return;
        }
        if (block.dataset.detailsKeyboardPending === '1') {
            block.dataset.detailsKeyboardPending = '0';
            openScheduleDetails(block);
            return;
        }
        const now = Date.now();
        const previous = lastClickedSchedule.get(block);
        if (previous && now - previous <= 700) {
            lastClickedSchedule.delete(block);
            openScheduleDetails(block);
        } else {
            lastClickedSchedule.set(block, now);
        }
    }

    function openScheduleDetails(block) {
        if (!block || block.matches('.shared-reservation-entry, .student-reservation-block')) return;
        const isStudentSchedule = block.matches('.student-schedule-block');
        const course = String(block.dataset.detailCourse || textFrom(block, isStudentSchedule ? '.student-block-code' : '.class-course')).trim();
        const subject = String(block.dataset.detailSubject || textFrom(block, '.class-subject')).trim();
        const instructor = String(block.dataset.detailInstructor || textFrom(block, '.class-instructor, .student-block-instructor')).trim();
        if (!course && !subject && !instructor) return;

        let modal = document.getElementById('scheduleDetailsPopup');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'scheduleDetailsPopup';
            modal.className = 'schedule-details-overlay';
            modal.hidden = true;
            modal.innerHTML = '<section class="schedule-details-dialog" role="dialog" aria-modal="true" aria-labelledby="scheduleDetailsTitle" tabindex="-1">'
                + '<header class="schedule-details-header"><div><span class="schedule-details-kicker">SCHEDULE DETAILS</span><h2 id="scheduleDetailsTitle">Class schedule</h2></div>'
                + '<button type="button" class="schedule-details-close" data-close-schedule-details aria-label="Close schedule details">×</button></header>'
                + '<div class="schedule-details-list" id="scheduleDetailsList"></div></section>';
            document.body.appendChild(modal);
        }

        const list = modal.querySelector('#scheduleDetailsList');
        list.replaceChildren();
        const article = document.createElement('article');
        article.className = 'schedule-details-entry';
        const heading = document.createElement('div');
        heading.className = 'schedule-details-entry-title';
        heading.textContent = [course, subject].filter(Boolean).join(' · ') || 'Scheduled class';
        const instructorLine = document.createElement('span');
        instructorLine.className = 'schedule-details-entry-subtitle';
        instructorLine.textContent = instructor || 'Instructor not listed';
        heading.appendChild(instructorLine);

        const fields = document.createElement('dl');
        fields.className = 'schedule-details-fields';
        const rawDays = String(block.dataset.viewScheduleDays || block.dataset.viewScheduleDay || block.dataset.detailDays || '').split(',')
            .map(day => day.trim().toUpperCase()).filter(Boolean);
        const days = [...new Set(rawDays)].map(day => dayNames[day] || day).join(', ');
        appendField(fields, 'Day', days);
        appendField(fields, 'Time', formatTime(block.dataset.viewScheduleTime || block.dataset.detailTime || ''));
        appendField(fields, 'Room', block.dataset.detailRoom || document.getElementById('scheduleRoomName')?.textContent || '');
        appendField(fields, 'Building', block.dataset.detailBuilding || document.getElementById('scheduleRoomBuilding')?.textContent || '');
        appendField(fields, 'Program', block.dataset.detailProgram || textFrom(block, '.class-program'));
        appendField(fields, 'Year level', block.dataset.detailYear || block.dataset.viewScheduleYear || textFrom(block, '.class-year'));
        appendField(fields, 'Major', block.dataset.detailMajor || textFrom(block, '.class-major'));
        appendField(fields, 'Class type', block.dataset.detailType || textFrom(block, '.class-type'));
        appendGroupListField(fields, 'Additional majors / groups', readSharedGroupItems(block));
        const activeMergedClasses = block.dataset.detailMergedClasses
            || Array.from(block.querySelectorAll('.merged-course-details:not(.cell-shared-majors)'))
                .map(item => String(item.textContent || '').trim()).filter(Boolean).join(' · ');
        appendField(fields, 'Active merged classes', activeMergedClasses);
        appendField(fields, 'Room change', textFrom(block, '.room-swap-details'));
        article.append(heading, fields);
        list.appendChild(article);

        modal._returnFocus = block;
        modal.hidden = false;
        document.body.classList.add('schedule-details-open');
        modal.querySelector('[data-close-schedule-details]')?.focus({ preventScroll: true });
    }

    function closeScheduleDetails(modal) {
        if (!modal || modal.hidden) return;
        modal.hidden = true;
        document.body.classList.remove('schedule-details-open');
        const target = modal._returnFocus;
        modal._returnFocus = null;
        if (target?.isConnected) target.focus({ preventScroll: true });
    }

    document.addEventListener('click', function (event) {
        const target = event.target instanceof Element ? event.target : null;
        if (!target) return;
        const modal = target.closest('#scheduleDetailsPopup');
        if (modal && (target === modal || target.closest('[data-close-schedule-details]'))) {
            closeScheduleDetails(modal);
            return;
        }
        const block = target.closest('.published-schedule-block.class-entry:not(.shared-reservation-entry), .student-schedule-block:not(.student-reservation-block)');
        if (!block) return;
        handleScheduleClick(block, event);
    });

    document.addEventListener('keydown', function (event) {
        const modal = document.getElementById('scheduleDetailsPopup');
        if (modal && !modal.hidden && event.key === 'Escape') {
            event.preventDefault();
            closeScheduleDetails(modal);
            return;
        }
        const target = event.target instanceof Element ? event.target : null;
        const block = target?.closest('.published-schedule-block.class-entry:not(.shared-reservation-entry), .student-schedule-block:not(.student-reservation-block)');
        if (block && !target?.closest('.cell-shared-cohort-summary, .schedule-resize-handle') && (event.key === 'Enter' || event.key === ' ')) {
            event.preventDefault();
            block.dataset.detailsKeyboardPending = '1';
            block.click();
            block.dataset.detailsKeyboardPending = '0';
        }
    });
})();

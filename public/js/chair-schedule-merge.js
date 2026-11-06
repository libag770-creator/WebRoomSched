(function () {
    const modal = document.getElementById('scheduleMergeRequestModal');
    if (!modal || modal.dataset.initialized === '1') return;
    modal.dataset.initialized = '1';

    const subjectSearch = document.getElementById('scheduleMergeSubjectSearch');
    const subjectSelect = document.getElementById('scheduleMergeSubjectSelect');
    const subjectDetails = document.getElementById('scheduleMergeSubjectDetails');
    const hostCourse = document.getElementById('scheduleMergeHostCourse');
    const hostDetails = document.getElementById('scheduleMergeHostDetails');
    const status = document.getElementById('scheduleMergeStatus');
    const submit = document.getElementById('scheduleMergeSubmit');
    const eyebrow = document.getElementById('scheduleMergeEyebrow');
    const dialogTitle = document.getElementById('scheduleMergeDialogTitle');
    const dialogDescription = document.getElementById('scheduleMergeDialogDescription');
    const subjectHelp = document.getElementById('scheduleMergeSubjectHelp');
    const confirmModal = document.getElementById('scheduleMergeConfirmModal');
    const confirmTitle = document.getElementById('scheduleMergeConfirmTitle');
    const confirmMessage = document.getElementById('scheduleMergeConfirmMessage');
    const confirmCourse = document.getElementById('scheduleMergeConfirmCourse');
    const confirmSubmit = document.getElementById('scheduleMergeConfirmSubmit');
    const unmergeConfirmModal = document.getElementById('scheduleUnmergeConfirmModal');
    const unmergeConfirmMessage = document.getElementById('scheduleUnmergeConfirmMessage');
    const unmergeConfirmCourses = document.getElementById('scheduleUnmergeConfirmCourses');
    const unmergeConfirmSubmit = document.getElementById('scheduleUnmergeConfirmSubmit');
    const successModal = document.getElementById('scheduleMergeSuccessModal');
    const successTitle = document.getElementById('scheduleMergeSuccessTitle');
    const successMessage = document.getElementById('scheduleMergeSuccessMessage');
    const closeButtons = modal.querySelectorAll('[data-merge-close]');

    let subjectList = [];
    let subjectRequest = null;
    let selectedSchedule = null;
    let activeTrigger = null;
    let unmergeSelection = null;
    let reloadAfterSuccess = false;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function setStatus(message, kind) {
        status.textContent = message || '';
        status.className = 'schedule-merge-status' + (kind ? ' ' + kind : '');
    }

    function normalizeSchedule(schedule) {
        return {
            id: schedule.schedule_id || schedule.id || schedule.mergeScheduleId || '',
            course_code: schedule.course_code || schedule.courseCode || '',
            subject: schedule.subject || schedule.course_title || schedule.courseTitle || '',
            instructor: schedule.instructor || '',
            department: schedule.department || schedule.department_name || '',
            program: schedule.program || '',
            building: schedule.building || schedule.building_name || '',
            room: schedule.room || schedule.room_name || '',
            day: schedule.day || '',
            time: schedule.time || '',
            semester: schedule.semester || '',
            academic_year: schedule.academic_year || schedule.academicYear || '',
            department_id: schedule.department_id || schedule.departmentId || ''
        };
    }

    function renderHost(schedule) {
        const title = [schedule.course_code, schedule.subject].filter(Boolean).join(' — ');
        hostCourse.textContent = title || 'Scheduled class';
        const details = [
            schedule.department,
            schedule.program ? 'Program: ' + schedule.program : '',
            [schedule.day, schedule.time].filter(Boolean).join(' · '),
            [schedule.building, schedule.room].filter(Boolean).join(' · '),
            schedule.instructor ? 'Instructor: ' + schedule.instructor : '',
            [schedule.semester, schedule.academic_year].filter(Boolean).join(' · ')
        ].filter(Boolean);
        hostDetails.textContent = details.join('  •  ');
    }

    function filteredSubjects() {
        const query = subjectSearch.value.trim().toLowerCase();
        return subjectList.filter(function (subject) {
            if (!query) return true;
            return [
                subject.course_code,
                subject.course_title,
                subject.program,
                subject.program_code,
                subject.year_level,
                subject.major,
                subject.semester
            ].some(function (value) {
                return String(value || '').toLowerCase().includes(query);
            });
        });
    }

    function renderSubjects() {
        const subjects = filteredSubjects();
        const selectedId = subjectSelect.value;
        subjectSelect.replaceChildren();

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = subjects.length ? 'Choose a course from Manage Subjects' : 'No matching courses';
        subjectSelect.appendChild(placeholder);

        subjects.forEach(function (subject) {
            const option = document.createElement('option');
            option.value = String(subject.id);
            option.textContent = [
                subject.course_code + ' — ' + subject.course_title,
                subject.program,
                [subject.year_level, subject.major].filter(Boolean).join(' · ')
            ].filter(Boolean).join('  |  ');
            if (String(subject.id) === selectedId) option.selected = true;
            subjectSelect.appendChild(option);
        });

        updateSelectedSubject();
    }

    function updateSelectedSubject() {
        const selected = subjectList.find(function (subject) {
            return String(subject.id) === subjectSelect.value;
        });
        submit.disabled = !selected || !selectedSchedule;

        if (!selected) {
            subjectDetails.hidden = true;
            subjectDetails.replaceChildren();
            return;
        }

        const courseName = document.createElement('strong');
        courseName.textContent = selected.course_code + ' — ' + selected.course_title;
        const courseInfo = document.createElement('div');
        courseInfo.textContent = [
            selected.department,
            selected.program,
            selected.program_code ? '(' + selected.program_code + ')' : '',
            selected.year_level,
            selected.major,
            selected.semester
        ].filter(Boolean).join(' · ');
        subjectDetails.replaceChildren(courseName, courseInfo);
        subjectDetails.hidden = false;
    }

    function loadSubjects() {
        if (subjectRequest) return subjectRequest;

        subjectSelect.innerHTML = '<option value="">Loading Manage Subjects courses…</option>';
        subjectRequest = fetch(modal.dataset.subjectsUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                return response.json().catch(function () { return null; }).then(function (data) {
                    if (!response.ok) {
                        throw new Error(data?.message || 'Could not load courses from Manage Subjects (HTTP ' + response.status + '). Refresh the page and try again.');
                    }
                    if (!data) throw new Error('The course list returned an unreadable response. Refresh the page and try again.');
                    return data;
                });
            })
            .then(function (data) {
                if (!data.success || !Array.isArray(data.subjects)) {
                    throw new Error(data.message || 'Could not load courses from Manage Subjects.');
                }
                subjectList = data.subjects;
                renderSubjects();
                if (!subjectList.length) {
                    setStatus('Your department has no saved Manage Subjects courses to request.', 'error');
                }
                return subjectList;
            })
            .catch(function (error) {
                subjectSelect.innerHTML = '<option value="">Unable to load courses</option>';
                setStatus(error.message, 'error');
                subjectRequest = null;
                throw error;
            });

        return subjectRequest;
    }

    function openDialog(schedule, trigger) {
        selectedSchedule = normalizeSchedule(schedule || {});
        if (!selectedSchedule.id) return;
        activeTrigger = trigger || null;
        const localSchedule = String(selectedSchedule.department_id || '') === String(modal.dataset.currentDepartmentId || '');
        eyebrow.textContent = localSchedule ? 'SAME-COLLEGE CLASS' : 'CROSS-COLLEGE REQUEST';
        dialogTitle.textContent = localSchedule ? 'Merge a course into this class' : 'Request to join this class';
        dialogDescription.textContent = localSchedule
            ? 'Choose another course from your college’s Manage Subjects catalog. It will be added to this class after confirmation.'
            : 'Choose a course from your college’s Manage Subjects catalog. The class-owning chair must approve it before it appears.';
        subjectHelp.textContent = localSchedule
            ? 'Search by course code, title, program, year level, or major. The course will be added to all matching class meetings.'
            : 'Search by course code, title, program, year level, or major. The class owner will review your request.';
        subjectSearch.value = '';
        subjectSelect.value = '';
        subjectDetails.hidden = true;
        submit.disabled = true;
        submit.hidden = false;
        submit.textContent = localSchedule ? 'Review merge' : 'Review request';
        if (subjectList.length) renderSubjects();
        setStatus(localSchedule
            ? 'Confirm the selected subject to add it to this class immediately.'
            : 'The class owner will be notified and can approve or decline your request.');
        renderHost(selectedSchedule);
        modal.hidden = false;
        document.body.classList.add('schedule-merge-open');

        loadSubjects()
            .then(function () {
                subjectSearch.focus();
            })
            .catch(function () {});
    }

    function closeDialog() {
        modal.hidden = true;
        confirmModal.hidden = true;
        document.body.classList.remove('schedule-merge-open');
        document.body.classList.remove('schedule-merge-confirm-open');
        selectedSchedule = null;
        activeTrigger = null;
        subjectSelect.value = '';
        subjectDetails.hidden = true;
        submit.disabled = true;
        setStatus('');
    }

    window.openScheduleMergeRequest = openDialog;

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-merge-request-button]');
        if (!button) return;
        event.preventDefault();
        event.stopPropagation();
        openDialog({
            id: button.dataset.mergeScheduleId,
            course_code: button.dataset.mergeCourseCode,
            subject: button.dataset.mergeSubject,
            instructor: button.dataset.mergeInstructor,
            department: button.dataset.mergeDepartment,
            program: button.dataset.mergeProgram,
            building: button.dataset.mergeBuilding,
            room: button.dataset.mergeRoom,
            day: button.dataset.mergeDay,
            time: button.dataset.mergeTime,
            semester: button.dataset.mergeSemester,
            academic_year: button.dataset.mergeAcademicYear
        }, button);
    });

    closeButtons.forEach(function (button) {
        button.addEventListener('click', closeDialog);
    });

    subjectSearch.addEventListener('input', renderSubjects);
    subjectSelect.addEventListener('change', updateSelectedSubject);

    document.addEventListener('keydown', function (event) {
        if (!modal.hidden && event.key === 'Escape') closeDialog();
        if (!confirmModal.hidden && event.key === 'Escape') closeConfirmation();
        if (!unmergeConfirmModal.hidden && event.key === 'Escape') {
            unmergeConfirmModal.hidden = true;
            document.body.classList.remove('schedule-merge-confirm-open');
            unmergeSelection = null;
        }
        if (!successModal.hidden && event.key === 'Escape') closeSuccess();
    });

    submit.addEventListener('click', function () {
        const selectedSubject = subjectList.find(subject => String(subject.id) === String(subjectSelect.value));
        if (!selectedSchedule || !selectedSchedule.id || !selectedSubject) return;

        const localSchedule = String(selectedSchedule.department_id || '') === String(modal.dataset.currentDepartmentId || '');
        confirmTitle.textContent = localSchedule ? 'Add this course to the class?' : 'Send this course merge request?';
        confirmMessage.textContent = localSchedule
            ? 'This course will be added immediately to the selected class and its matching meeting entries.'
            : 'This request will be sent to ' + (selectedSchedule.department || 'the class-owning college') + '. It will appear on the schedule only after that chair approves it.';
        confirmCourse.textContent = [
            selectedSubject.course_code + ' — ' + selectedSubject.course_title,
            selectedSubject.program_code || selectedSubject.program,
            [selectedSubject.year_level, selectedSubject.major].filter(Boolean).join(' · ')
        ].filter(Boolean).join(' | ');
        confirmSubmit.textContent = localSchedule ? 'Confirm merge' : 'Send for approval';
        confirmSubmit.disabled = false;
        confirmModal.hidden = false;
        document.body.classList.add('schedule-merge-confirm-open');
    });

    function closeConfirmation() {
        confirmModal.hidden = true;
        document.body.classList.remove('schedule-merge-confirm-open');
    }

    function closeSuccess() {
        successModal.hidden = true;
        document.body.classList.remove('schedule-merge-success-open');
        if (reloadAfterSuccess) window.location.reload();
    }

    document.getElementById('scheduleMergeConfirmBack').addEventListener('click', closeConfirmation);
    document.getElementById('scheduleMergeSuccessDone').addEventListener('click', closeSuccess);
    document.getElementById('scheduleUnmergeConfirmBack').addEventListener('click', function () {
        unmergeConfirmModal.hidden = true;
        document.body.classList.remove('schedule-merge-confirm-open');
        unmergeSelection = null;
    });

    window.openScheduleUnmergeConfirm = function (schedule, merges, trigger) {
        const activeMerges = Array.isArray(merges) ? merges.filter(item => item && item.merge_id) : [];
        if (!activeMerges.length) return;
        unmergeSelection = { schedule: normalizeSchedule(schedule || {}), merges: activeMerges, trigger: trigger || null };
        const labels = activeMerges.map(function (merge) {
            return [merge.course_code, merge.course_title, merge.program_code].filter(Boolean).join(' — ');
        });
        unmergeConfirmMessage.textContent = activeMerges.length === 1
            ? 'Remove this merged course from the class and all matching meeting entries? The class-owning and requesting colleges will see the change.'
            : 'Remove these merged courses from the class and all matching meeting entries? The class-owning and requesting colleges will see the change.';
        unmergeConfirmCourses.textContent = labels.join('  •  ');
        unmergeConfirmSubmit.disabled = false;
        unmergeConfirmSubmit.textContent = 'Unmerge class';
        unmergeConfirmModal.hidden = false;
        document.body.classList.add('schedule-merge-confirm-open');
    };

    unmergeConfirmSubmit.addEventListener('click', async function () {
        if (!unmergeSelection) return;
        const merges = unmergeSelection.merges;
        const urlTemplate = modal.dataset.removeUrl || '';
        unmergeConfirmSubmit.disabled = true;
        unmergeConfirmSubmit.textContent = 'Removing merge…';
        try {
            for (const merge of merges) {
                const url = urlTemplate.replace('__MERGE_ID__', encodeURIComponent(String(merge.merge_id)));
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                const data = await response.json().catch(function () { return null; });
                if (!response.ok || !data?.success) {
                    throw new Error(data?.message || 'The merge could not be removed (HTTP ' + response.status + ').');
                }
                unmergeSelection.merges = unmergeSelection.merges.filter(item => String(item.merge_id) !== String(merge.merge_id));
            }

            unmergeConfirmModal.hidden = true;
            document.body.classList.remove('schedule-merge-confirm-open');
            unmergeSelection = null;
            reloadAfterSuccess = true;
            successTitle.textContent = 'Class unmerged';
            successMessage.textContent = merges.length === 1
                ? 'The merged course has been removed from this class and its matching meetings.'
                : 'The merged courses have been removed from this class and its matching meetings.';
            successModal.hidden = false;
            document.body.classList.add('schedule-merge-success-open');
        } catch (error) {
            unmergeConfirmMessage.textContent = error.message;
            unmergeConfirmSubmit.disabled = false;
            unmergeConfirmSubmit.textContent = 'Try again';
        }
    });

    confirmSubmit.addEventListener('click', function () {
        const subjectId = subjectSelect.value;
        if (!selectedSchedule || !selectedSchedule.id || !subjectId) return;
        const localSchedule = String(selectedSchedule.department_id || '') === String(modal.dataset.currentDepartmentId || '');
        const submittedSchedule = selectedSchedule;
        const selectedSubject = subjectList.find(subject => String(subject.id) === String(subjectId));
        const requestUrl = localSchedule ? modal.dataset.localRequestUrl : modal.dataset.requestUrl;

        confirmSubmit.disabled = true;
        confirmSubmit.textContent = localSchedule ? 'Adding course…' : 'Sending request…';
        fetch(requestUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                schedule_id: selectedSchedule.id,
                subject_id: subjectId,
                term_id: modal.dataset.termId
            })
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'The course merge could not be completed.');
                    }
                    return data;
                });
            })
            .then(function (data) {
                if (localSchedule && window.applyApprovedScheduleMerge) {
                    window.applyApprovedScheduleMerge(data.schedule_ids || [submittedSchedule.id], data.course || selectedSubject);
                }
                if (!localSchedule && activeTrigger && activeTrigger.dataset) {
                    activeTrigger.dataset.mergePending = '1';
                    const label = document.createElement('span');
                    label.className = 'schedule-merge-pending-label';
                    label.textContent = 'Merge request pending';
                    activeTrigger.querySelector('.schedule-cell-content')?.appendChild(label);
                }
                const message = data.message || (localSchedule
                    ? 'The course was added to this class.'
                    : 'The request was sent to the class-owning chair for approval.');
                const courseLabel = selectedSubject
                    ? [selectedSubject.course_code, selectedSubject.course_title].filter(Boolean).join(' — ')
                    : 'The selected course';
                closeConfirmation();
                closeDialog();
                successTitle.textContent = localSchedule ? 'Course merged successfully' : 'Merge request sent';
                successMessage.textContent = localSchedule
                    ? courseLabel + ' is now included in this class and its matching meetings.'
                    : courseLabel + ' was sent to ' + (submittedSchedule.department || 'the class-owning college') + ' for approval.';
                successModal.hidden = false;
                document.body.classList.add('schedule-merge-success-open');
            })
            .catch(function (error) {
                closeConfirmation();
                setStatus(error.message, 'error');
                confirmSubmit.disabled = false;
                confirmSubmit.textContent = localSchedule ? 'Confirm merge' : 'Send for approval';
            });
    });
})();

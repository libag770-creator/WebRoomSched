(function () {
    'use strict';

    function setMaximized(sheet, maximized) {
        if (!sheet) return;

        document.querySelectorAll('[data-schedule-sheet].is-schedule-maximized').forEach(function (otherSheet) {
            if (otherSheet !== sheet) setMaximized(otherSheet, false);
        });

        sheet.classList.toggle('is-schedule-maximized', maximized);
        document.body.classList.toggle('schedule-sheet-active', Boolean(document.querySelector('[data-schedule-sheet].is-schedule-maximized')));

        const maximizeButton = sheet.querySelector('[data-schedule-maximize]');
        const minimizeButton = sheet.querySelector('[data-schedule-minimize]');
        if (maximizeButton) maximizeButton.hidden = maximized;
        if (minimizeButton) minimizeButton.hidden = !maximized;

        if (maximized) {
            window.requestAnimationFrame(function () { minimizeButton?.focus(); });
        } else if (document.activeElement === minimizeButton) {
            maximizeButton?.focus();
        }
    }

    document.addEventListener('click', function (event) {
        const target = event.target instanceof Element ? event.target : event.target.parentElement;
        const dismissButton = target?.closest('.close-schedule, .change-room-button');
        if (dismissButton) {
            const activeSheet = dismissButton.closest('[data-schedule-sheet]');
            if (activeSheet?.classList.contains('is-schedule-maximized')) setMaximized(activeSheet, false);
        }
        const maximizeButton = target?.closest('[data-schedule-maximize]');
        const minimizeButton = target?.closest('[data-schedule-minimize]');
        const button = maximizeButton || minimizeButton;
        if (!button) return;

        const sheet = button.closest('[data-schedule-sheet]');
        if (!sheet) return;
        event.preventDefault();
        setMaximized(sheet, Boolean(maximizeButton));
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        const sheet = document.querySelector('[data-schedule-sheet].is-schedule-maximized');
        if (sheet) setMaximized(sheet, false);
    });
})();

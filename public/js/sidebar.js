(() => {
    const storageKey = 'webroomsched-sidebar-collapsed';

    const readCollapsed = () => {
        try {
            const saved = localStorage.getItem(storageKey);
            if (saved !== null) return saved === 'true';
        } catch {
            // Fall through to the small-screen default when storage is unavailable.
        }

        return window.matchMedia('(max-width: 760px)').matches;
    };

    const init = () => {
        const sidebars = document.querySelectorAll('.sidebar');
        if (!sidebars.length) return;
        if (document.body.dataset.sidebarInitialized === 'true') return;

        document.body.dataset.sidebarInitialized = 'true';
        document.body.classList.toggle('sidebar-collapsed', readCollapsed());

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'sidebar-toggle';
        toggle.setAttribute('aria-label', 'Collapse navigation');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.title = 'Collapse navigation';
        toggle.innerHTML =
            '<i class="fa-solid fa-angles-left" aria-hidden="true"></i>';
        document.body.append(toggle);

        const backdrop = document.createElement('button');
        backdrop.type = 'button';
        backdrop.className = 'sidebar-backdrop';
        backdrop.setAttribute('aria-label', 'Close navigation');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.append(backdrop);

        sidebars.forEach((sidebar) => {
            sidebar
                .querySelectorAll('.sidebar-menu a, li a, ul a')
                .forEach((link) => {
                    const label =
                        link.querySelector('.sidebar-text')?.textContent?.trim() ||
                        link.textContent.replace(/\s+/g, ' ').trim();

                    if (!label) return;

                    link.dataset.tooltip = label;
                    link.setAttribute('aria-label', label);

                    const placeTooltip = () => {
                        const bounds = link.getBoundingClientRect();
                        link.style.setProperty(
                            '--sidebar-tooltip-top',
                            `${bounds.top + bounds.height / 2}px`
                        );
                    };

                    link.addEventListener('mouseenter', placeTooltip);
                    link.addEventListener('focus', placeTooltip);
                });
        });

        const updateToggleState = () => {
            const collapsed =
                document.body.classList.contains('sidebar-collapsed');

            toggle.setAttribute('aria-expanded', String(!collapsed));
            toggle.setAttribute(
                'aria-label',
                collapsed ? 'Expand navigation' : 'Collapse navigation'
            );
            toggle.title = collapsed
                ? 'Expand navigation'
                : 'Collapse navigation';
            toggle.innerHTML = collapsed
                ? '<i class="fa-solid fa-angles-right" aria-hidden="true"></i>'
                : '<i class="fa-solid fa-angles-left" aria-hidden="true"></i>';
            backdrop.setAttribute('aria-hidden', String(collapsed));
        };

        toggle.addEventListener('click', () => {
            const collapsed =
                !document.body.classList.contains('sidebar-collapsed');

            document.body.classList.toggle(
                'sidebar-collapsed',
                collapsed
            );

            try {
                localStorage.setItem(
                    storageKey,
                    String(collapsed)
                );
            } catch {
                // Storage may be unavailable in private browsing.
            }

            updateToggleState();
        });

        backdrop.addEventListener('click', () => {
            document.body.classList.add('sidebar-collapsed');
            try {
                localStorage.setItem(storageKey, 'true');
            } catch {
                // Storage may be unavailable in private browsing.
            }
            updateToggleState();
        });

        document.addEventListener('keydown', (event) => {
            if (
                event.key === 'Escape' &&
                window.matchMedia('(max-width: 760px)').matches &&
                !document.body.classList.contains('sidebar-collapsed')
            ) {
                document.body.classList.add('sidebar-collapsed');
                updateToggleState();
            }
        });

        updateToggleState();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();

(() => {
    const storageKey = 'webroomsched-sidebar-collapsed';
    const toggleStyleId = 'sidebar-toggle-overlay-styles';

    const readCollapsed = () => {
        try {
            return localStorage.getItem(storageKey) === 'true';
        } catch {
            return false;
        }
    };

    const installToggleStyles = () => {
        if (document.getElementById(toggleStyleId)) return;

        const style = document.createElement('style');
        style.id = toggleStyleId;
        style.textContent = `
            body > .sidebar-toggle {
                position: fixed !important;
                top: 50% !important;
                left: var(--sidebar-toggle-left, 0px) !important;
                z-index: 1450 !important;
                display: grid !important;
                place-items: center !important;
                box-sizing: border-box !important;
                width: 28px !important;
                min-width: 28px !important;
                height: 58px !important;
                margin: 0 !important;
                padding: 0 !important;
                color: #285b3f !important;
                background: #f7faf7 !important;
                border: 1px solid #cfddd2 !important;
                border-radius: 12px !important;
                box-shadow: 4px 7px 18px rgba(11, 33, 19, .2) !important;
                transform: translateY(-50%) !important;
                cursor: pointer !important;
                transition: background .16s ease, color .16s ease,
                    border-color .16s ease, box-shadow .16s ease !important;
            }

            body > .sidebar-toggle:hover {
                color: #17472e !important;
                background: #fff !important;
                border-color: #a8cbb0 !important;
                box-shadow: 5px 8px 20px rgba(11, 33, 19, .24) !important;
            }

            body > .sidebar-toggle:focus-visible {
                outline: 3px solid #a6d1ae !important;
                outline-offset: 2px !important;
            }
        `;
        document.head.appendChild(style);
    };

    const init = () => {
        const sidebars = Array.from(document.querySelectorAll('.sidebar'));
        if (!sidebars.length) return;
        if (document.body.dataset.sidebarInitialized === 'true') return;

        document.body.dataset.sidebarInitialized = 'true';
        document.body.classList.toggle('sidebar-collapsed', readCollapsed());

        document.body.classList.add('page-entering');
        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => {
                document.body.classList.remove('page-entering');
            });
        });

        installToggleStyles();

        // Keep one toggle outside the scrollable sidebar so its border cannot be clipped.
        const sidebar = sidebars[0];
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'sidebar-toggle';
        toggle.setAttribute('aria-label', 'Collapse navigation');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.title = 'Collapse navigation';
        toggle.innerHTML =
            '<i class="fa-solid fa-angles-left" aria-hidden="true"></i>';
        document.body.appendChild(toggle);

        const placeToggle = () => {
            const sidebarRight = sidebar.getBoundingClientRect().right;
            const left = Math.max(
                0,
                Math.min(sidebarRight - 8, window.innerWidth - 30)
            );
            toggle.style.setProperty('--sidebar-toggle-left', `${left}px`);
        };

        placeToggle();
        window.addEventListener('resize', placeToggle, { passive: true });

        if ('ResizeObserver' in window) {
            const observer = new ResizeObserver(placeToggle);
            observer.observe(sidebar);
            toggle._sidebarResizeObserver = observer;
        }

        sidebars.forEach((currentSidebar) => {
            currentSidebar
                .querySelectorAll('.sidebar-menu a, li a, ul a')
                .forEach((link) => {
                    const label =
                        link.querySelector('.sidebar-text')?.textContent?.trim() ||
                        link.textContent.replace(/\s+/g, ' ').trim();

                    if (!label) return;

                    link.dataset.tooltip = label;
                    link.title = label;

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
        };

        toggle.addEventListener('click', () => {
            const collapsed =
                !document.body.classList.contains('sidebar-collapsed');

            document.body.classList.toggle(
                'sidebar-collapsed',
                collapsed
            );

            try {
                localStorage.setItem(storageKey, String(collapsed));
            } catch {
                // Storage may be unavailable in private browsing.
            }

            updateToggleState();
            placeToggle();
        });

        let navigationStarted = false;

        document.addEventListener('click', (event) => {
            const link = event.target.closest('.sidebar a[href]');

            if (
                !link ||
                event.defaultPrevented ||
                event.button !== 0 ||
                event.metaKey ||
                event.ctrlKey ||
                event.shiftKey ||
                event.altKey ||
                link.hasAttribute('download') ||
                (link.target && link.target !== '_self')
            ) {
                return;
            }

            const destination = new URL(link.href, window.location.href);

            if (destination.origin !== window.location.origin) return;

            if (
                destination.href === window.location.href ||
                (
                    destination.pathname === window.location.pathname &&
                    destination.search === window.location.search &&
                    destination.hash
                )
            ) {
                return;
            }

            event.preventDefault();

            if (navigationStarted) return;
            navigationStarted = true;

            if (
                window.matchMedia(
                    '(prefers-reduced-motion: reduce)'
                ).matches
            ) {
                window.location.assign(destination.href);
                return;
            }

            document.body.classList.add('page-leaving');

            window.setTimeout(() => {
                window.location.assign(destination.href);
            }, 180);
        });

        updateToggleState();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
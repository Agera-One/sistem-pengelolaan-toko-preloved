function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    const overlay = document.getElementById('sidebar-overlay');
    const openButton = document.getElementById('sidebar-open');
    const closeButton = document.getElementById('sidebar-close');
    const content = document.getElementById('app-content');

    const desktop = window.matchMedia('(min-width: 1024px)');

    let isOpen = false;

    function render() {
        sidebar.classList.toggle('-translate-x-full', !isOpen);
        overlay?.classList.toggle('hidden', !isOpen);
        document.body.classList.toggle('overflow-hidden', isOpen);
        openButton?.setAttribute('aria-expanded', String(isOpen));

        sidebar.inert = !desktop.matches && !isOpen;

        if (content) content.inert = isOpen;
    }

    function open() {
        isOpen = true;
        render();
        closeButton?.focus();
    }

    function close() {
        if (!isOpen) return;
        isOpen = false;
        render();
        openButton?.focus();
    }

    openButton?.addEventListener('click', open);
    closeButton?.addEventListener('click', close);
    overlay?.addEventListener('click', close);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') close();
    });

    desktop.addEventListener('change', () => {
        isOpen = false;
        render();
    });

    render();

    sidebar.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            const submenu = document.getElementById(button.getAttribute('aria-controls'));
            const chevron = button.querySelector('[data-sidebar-chevron]');

            button.setAttribute('aria-expanded', String(!expanded));
            submenu?.classList.toggle('hidden', expanded);
            chevron?.classList.toggle('rotate-180', !expanded);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
} else {
    initSidebar();
}

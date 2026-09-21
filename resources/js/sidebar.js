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
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
} else {
    initSidebar();
}

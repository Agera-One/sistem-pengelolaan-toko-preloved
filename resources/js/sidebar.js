/**
 * Sidebar: buka/tutup di HP dan tablet.
 * Di layar lebar (lg ke atas) sidebar selalu tampil, jadi JS tidak berbuat apa-apa.
 */

function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    const overlay = document.getElementById('sidebar-overlay');
    const openButton = document.getElementById('sidebar-open');
    const closeButton = document.getElementById('sidebar-close');
    const content = document.getElementById('app-content');

    // Sama dengan breakpoint "lg" Tailwind.
    const desktop = window.matchMedia('(min-width: 1024px)');

    let isOpen = false;

    function render() {
        sidebar.classList.toggle('-translate-x-full', !isOpen);
        overlay?.classList.toggle('hidden', !isOpen);
        document.body.classList.toggle('overflow-hidden', isOpen);
        openButton?.setAttribute('aria-expanded', String(isOpen));

        // Sidebar yang tertutup di HP tidak boleh bisa dijangkau dengan Tab.
        sidebar.inert = !desktop.matches && !isOpen;

        // Saat menu terbuka, fokus keyboard tertahan di dalam sidebar.
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

    // Jika layar diperbesar/diputar, kembalikan ke keadaan normal.
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

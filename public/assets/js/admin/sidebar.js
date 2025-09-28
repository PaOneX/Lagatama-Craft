function toggleAdminSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminSidebarBackdrop');
    if (!sidebar) return;
    sidebar.classList.toggle('open');
    backdrop?.classList.toggle('show');
}

document.addEventListener('click', (e) => {
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.querySelector('.admin-menu-toggle');
    if (!sidebar?.classList.contains('open')) return;
    if (sidebar.contains(e.target) || toggle?.contains(e.target)) return;
    toggleAdminSidebar();
});

// Fungsi toggle sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    sidebar.classList.toggle('open');
    mainContent.classList.toggle('shifted');
}

document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const currentPage = params.get('page') || 'dashboard';

    const links = document.querySelectorAll('.sidebar-menu a');

    links.forEach(link => {
        const linkParams = new URLSearchParams(link.search);
        const linkPage = linkParams.get('page');

        if (linkPage === currentPage) {
            link.classList.add('active');
        }
    });
});


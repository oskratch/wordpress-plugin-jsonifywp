document.addEventListener('DOMContentLoaded', function () {
    const itemsPerPage = window.jsonifywp_members_vars?.itemsPerPage || 5;
    const rows = Array.from(document.querySelectorAll('.row-items'));
    const totalItems = rows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    let currentPage = 1;

    function scrollToListTop() {
        const topPagination = document.getElementById('jsonifywp-pagination-top');
        if (topPagination) {
            const rect = topPagination.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const offset = rect.top + scrollTop - 120; // 120px offset
            window.scrollTo({ top: offset, behavior: 'smooth' });
        }
    }

    function showPage(page) {
        currentPage = page;
        rows.forEach((row, idx) => {
            if (idx >= (page - 1) * itemsPerPage && idx < page * itemsPerPage) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        document.querySelectorAll('.jsonifywp-pagination-row').forEach(row => {
            row.style.display = '';
        });

        createPagination();
    }

    function createPagination() {
        if (totalPages <= 1) return;
        document.querySelectorAll('.page-numbers').forEach(pagination => {
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                if (i === currentPage) {
                    const span = document.createElement('span');
                    span.className = 'page-numbers current';
                    span.setAttribute('aria-current', 'page');
                    span.textContent = i;
                    li.appendChild(span);
                } else {
                    const a = document.createElement('a');
                    a.className = 'page-numbers';
                    a.href = '#';
                    a.textContent = i;
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        showPage(i);
                        scrollToListTop();
                    });
                    li.appendChild(a);
                }
                pagination.appendChild(li);
            }
        });
    }

    showPage(1);
});
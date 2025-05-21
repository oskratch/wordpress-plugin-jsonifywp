document.addEventListener('DOMContentLoaded', function () {
    const itemsPerPage = window.jsonifywp_members_vars?.itemsPerPage || 5;
    const rows = Array.from(document.querySelectorAll('.row-items')).filter((row, idx) => idx !== 0);
    const totalItems = rows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    function showPage(page) {
        rows.forEach((row, idx) => {
            if (idx >= (page - 1) * itemsPerPage && idx < page * itemsPerPage) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        // Always show the pagination row (the first .row)
        const paginationRow = document.querySelectorAll('.row')[0];
        if (paginationRow) {
            paginationRow.style.display = '';
        }
    }

    function createPagination() {
        if (totalPages <= 1) return;
        const pagination = document.querySelector('.page-numbers');
        if (!pagination) return;
        pagination.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            if (i === 1) {
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
                    // Update pagination UI
                    const pagItems = pagination.querySelectorAll('li');
                    pagItems.forEach((item, idx) => {
                        const link = item.querySelector('a');
                        const span = item.querySelector('span');
                        if (idx + 1 === i) {
                            if (link) {
                                const newSpan = document.createElement('span');
                                newSpan.className = 'page-numbers current';
                                newSpan.setAttribute('aria-current', 'page');
                                newSpan.textContent = i;
                                item.replaceChild(newSpan, link);
                            }
                        } else {
                            if (span) {
                                const newA = document.createElement('a');
                                newA.className = 'page-numbers';
                                newA.href = '#';
                                newA.textContent = idx + 1;
                                newA.addEventListener('click', (ev) => {
                                    ev.preventDefault();
                                    showPage(idx + 1);
                                    createPagination();
                                });
                                item.replaceChild(newA, span);
                            }
                        }
                    });
                });
                li.appendChild(a);
            }
            pagination.appendChild(li);
        }
    }

    showPage(1);
    createPagination();
});
document.addEventListener('DOMContentLoaded', function() {
    const paginationContainers = document.querySelectorAll('.jsonifywp-pagination-row .page-numbers');
    const totalPages = window.jsonifywp_publications_vars?.totalPages || 1;
    const limit = window.jsonifywp_publications_vars?.limit || 10;
    
    // Read the current page from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = parseInt(urlParams.get('jsonifywp_page')) || 1;
    
    if (totalPages <= 1) return;
    
    paginationContainers.forEach(function(container) {
        container.innerHTML = '';
        
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            
            if (i === currentPage) {
                const span = document.createElement('span');
                span.className = 'page-numbers current';
                span.setAttribute('aria-current', 'page');
                span.textContent = i;
                li.appendChild(span);
            } else {
                const baseUrl = new URL(window.location.href);
                baseUrl.searchParams.delete('jsonifywp_page');
                baseUrl.searchParams.delete('limit');
                baseUrl.searchParams.set('jsonifywp_page', i);
                baseUrl.searchParams.set('limit', limit);
                
                const link = document.createElement('a');
                link.className = 'page-numbers';
                link.href = baseUrl.toString();
                link.textContent = i;
                li.appendChild(link);
            }
            
            container.appendChild(li);
        }
    });
});
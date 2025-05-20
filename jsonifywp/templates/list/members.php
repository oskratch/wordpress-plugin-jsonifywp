<?php
// $json contains the decoded JSON
if (is_array($json)) {
    // Translation ready
    $labels = [
        'direct_phone'   => __('Phone', 'jsonifywp'),
        'extension'      => __('Extension', 'jsonifywp'),
        'office'         => __('Office', 'jsonifywp'),
    ];
    foreach ($json as $index => $item) {
        ?>
        <div class="row row-items">
            <div class="jws_team_item col-xl-12 col-lg-12 col-12">
                <div class="jws_team_inner">
                    <div class="jws_team_content">
                        <h4 class="team_title" style="margin-bottom:10px;"><?php echo esc_html($item['fullname']); ?></h4>                             
                        <?php if (!empty($item['office'])): ?>
                            <div class="event-time" style="display:inline-block; margin-right:10px;">
                                <i class="jws-icon-pin"></i>
                                <?php echo esc_html($labels['office']) . ': ' . esc_html($item['office']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['direct_phone'])): ?>
                            <div class="event-time" style="display:inline-block; margin-right:10px;">
                                <i class="jws-icon-mobile"></i>
                                <?php
                                    $phone = preg_replace('/\D+/', '', $item['direct_phone']);
                                    $tel_link = 'tel:' . $phone;
                                    echo esc_html($labels['direct_phone']) . ': ';
                                ?>
                                <a href="<?php echo esc_attr($tel_link); ?>">
                                    <?php echo esc_html($item['direct_phone']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['extension'])): ?>
                            <div class="event-time" style="display:inline-block;">
                                <i class="jws-icon-round-arrow-right"></i>
                                <?php echo esc_html($labels['extension']) . ': ' . esc_html($item['extension']); ?>
                            </div>
                        <?php endif; ?>    
                        <?php if (isset($item['employee_profile'])): ?>
                            <div style="margin-top:5px;">
                                <a class="elementor-button btn btn-naked btn-icon-shaped btn-has-label" href="<?php echo esc_url(add_query_arg(['jsonifywp_id' => $type_id, 'item' => $index], $item_obj->detail_page_url)); ?>">
                                    <span data-text="View all events" class="btn-txt"><?php _e('More info', 'jsonifywp'); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <hr style="margin-top:20px; margin-bottom:20px;">
            </div>
        </div>
        <?php
    }
}
?>
<div class="row" style="margin-top:30px;">
    <div class="jws_team_item col-xl-12 col-lg-12 col-12">
        <div class="jws-pagination-number">
            <ul class="page-numbers"></ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemsPerPage = 5;
        // Only select .jws_team_item inside .row except the first pagination row
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
                                        // Rebuild pagination for new active page
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
</script>
<?php
// List container
?>
<div class="jsonifywp-list">
    <ul class="jsonifywp-list-items">
        <?php
        // Render list items if $json['items'] is set and is an array
        if (is_array($json) && isset($json['items'])) {
            foreach ($json['items'] as $pub) {
                echo '<li class="jsonifywp-list-item">' . esc_html($pub) . '</li>';
            }
        }
        ?>
    </ul>
    <?php
    // Pagination
    if (is_array($json) && isset($json['total'], $json['limit'], $json['page'])) {
        $total = intval($json['total']);
        $limit = intval($json['limit']);
        $page = intval($json['page']);
        $pages = $limit > 0 ? ceil($total / $limit) : 1;

        if ($pages > 1) {
            echo '<div class="jsonifywp-pagination">';
            for ($i = 1; $i <= $pages; $i++) {
                // Build pagination URLs
                $base_url = remove_query_arg('page', $_SERVER['REQUEST_URI']);
                $url = add_query_arg('page', $i, $base_url);
                if ($i == $page) {
                    echo '<span class="page-numbers current" aria-current="page">' . $i . '</span> ';
                } else {
                    echo '<a href="' . esc_url($url) . '" class="page-numbers">' . $i . '</a> ';
                }
            }
            echo '</div>';
        }
    }
    ?>
</div>
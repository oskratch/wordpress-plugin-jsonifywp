<div class="row jsonifywp-pagination-row" id="jsonifywp-pagination-top" style="margin-bottom:60px;">
    <div class="jws_team_item col-xl-12 col-lg-12 col-12">
        <div class="jws-pagination-number">
            <ul class="page-numbers"></ul>
        </div>
    </div>
</div>
<?php
// $json contains the decoded JSON
if (is_array($json) && isset($json['items'])) {
    foreach ($json['items'] as $index => $pub) {
        ?>
        <div class="row row-items">
            <div class="jws_team_item col-xl-12 col-lg-12 col-12">
                <div class="jws_team_inner">
                    <div class="jws_team_content">
                        <p style="margin-bottom:10px;"><?php echo esc_html($pub); ?></p>
                    </div>
                </div>
                <hr style="margin-top:20px; margin-bottom:20px;">
            </div>
        </div>
        <?php
    }
}
?>
<div class="row jsonifywp-pagination-row" style="margin-top:40px;">
    <div class="jws_team_item col-xl-12 col-lg-12 col-12">
        <div class="jws-pagination-number">
            <ul class="page-numbers"></ul>
        </div>
    </div>
</div>
<?php
// Pagination logic for API-driven pagination
if (is_array($json) && isset($json['total'], $json['limit'], $json['page'])) {
    $total = intval($json['total']);
    $limit = intval($json['limit']);
    $page = intval($json['page']);
    $pages = $limit > 0 ? ceil($total / $limit) : 1;

    if ($pages > 1) {
        wp_enqueue_script(
            'jsonifywp-publications-js',
            plugins_url('assets/js/publications.js', __FILE__),
            array(),
            '1.0',
            true
        );
        wp_localize_script('jsonifywp-publications-js', 'jsonifywp_publications_vars', [
            'totalPages' => $pages,
            'currentPage' => $page,
            'limit' => $limit,
        ]);
    }
}
?>
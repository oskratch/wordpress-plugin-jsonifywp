<?php
// $json contains the decoded JSON
if (is_array($json)) {
    foreach ($json as $index => $item) {
        ?>
        <div class="jsonifywp-item">
            <?php foreach ($item as $key => $value): ?>
                <strong><?php echo esc_html($key); ?>:</strong> <?php echo esc_html(is_array($value) ? json_encode($value) : $value); ?><br>
            <?php endforeach; ?>
            <?php if (isset($item['employee_profile'])): ?>
                <a href="<?php echo esc_url(add_query_arg(['jsonifywp_id' => $type_id, 'item' => $index], $item_obj->detail_page_url)); ?>">
                    Veure detall
                </a>
            <?php endif; ?>
        </div>
        <hr>
        <?php
    }
}
?>
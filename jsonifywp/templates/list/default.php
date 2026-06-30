<?php
if (is_array($json)) {
    foreach ($json as $index => $item) {
        ?>
        <div class="jsonifywp-item">
            <?php foreach ($item as $key => $value): ?>
                <strong><?php echo esc_html($key); ?>:</strong> <?php echo esc_html(is_array($value) ? json_encode($value) : $value); ?><br>
            <?php endforeach; ?>
            <?php
            $detail_field = jsonifywp_get_detail_field($item_obj);
            if ($item_obj->detail_template !== 'none' && isset($item[$detail_field])):
            ?>
                <a href="<?php echo esc_url(add_query_arg(['jsonifywp_id' => $type_id, 'item' => $index], $item_obj->detail_page_url)); ?>">
                    <?php _e('View detail', 'jsonifywp'); ?>
                </a>
            <?php endif; ?>
        </div>
        <hr>
        <?php
    }
}
?>

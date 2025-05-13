<?php
// $json conté el JSON decodificat
if (is_array($json)) {
    foreach ($json as $item) {
        ?>
        <div class="jsonifywp-item">
            <?php foreach ($item as $key => $value): ?>
                <strong><?php echo esc_html($key); ?>:</strong> <?php echo esc_html($value); ?><br>
            <?php endforeach; ?>
        </div>
        <hr>
        <?php
    }
}
?>
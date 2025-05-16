<?php
// $json contains the decoded JSON from the detail API
if (is_array($json)) {
    foreach ($json as $key => $value) {
        ?>
        <strong><?php echo esc_html($key); ?>:</strong> <?php echo esc_html(is_array($value) ? json_encode($value) : $value); ?><br>
        <?php
    }
}
?>
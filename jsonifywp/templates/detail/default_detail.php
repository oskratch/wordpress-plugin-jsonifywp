<?php
// $json contains the decoded JSON from the detail API
$field_labels = jsonifywp_get_field_labels($item_obj);
if (is_array($json)) {
    foreach ($json as $key => $value) {
        ?>
        <strong><?php echo esc_html($field_labels[$key] ?? $key); ?>:</strong> <?php echo esc_html(is_array($value) ? json_encode($value) : $value); ?><br>
        <?php
    }
}
?>
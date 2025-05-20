<?php
// $json contains the decoded JSON from the detail API

if (is_array($json)) {
    // Translation ready
    $labels = [
        'fullname'       => __('Full Name', 'jsonifywp'),
        'direct_phone'   => __('Phone', 'jsonifywp'),
        'extension'      => __('Extension', 'jsonifywp'),
        'office'         => __('Office', 'jsonifywp'),
        'web'            => __('Website', 'jsonifywp'),
        'research_lines' => __('Research Lines', 'jsonifywp'),
        'research_description' => __('Research Description', 'jsonifywp'),
        'publications' => __('Selected and/or Featured Publications', 'jsonifywp'),
    ];

    // Show full name
    if (!empty($json['fullname'])) {
        echo '<h3 class="elementor-heading-title elementor-size-default" style="margin-bottom:5px;">' . esc_html($json['fullname']) . '</h3>';
    }

    echo '<ul style="padding-left: 15px;">';
    // Show fields in order with label
    if (!empty($json['direct_phone'])) {
        echo '<li>' . esc_html($labels['direct_phone']) . ': ' . esc_html($json['direct_phone']) . '</li>';
    }
    if (!empty($json['extension'])) {
        echo '<li>' . esc_html($labels['extension']) . ': ' . esc_html($json['extension']) . '</li>';
    }
    if (!empty($json['office'])) {
        echo '<li>' . esc_html($labels['office']) . ': ' . esc_html($json['office']) . '</li>';
    }
    if (!empty($json['web'])) {
        $web_url = esc_url($json['web']);
        echo '<li>' . esc_html($labels['web']) . ': <a href="' . $web_url . '" target="_blank" rel="noopener noreferrer">' . $web_url . '</a></li>';
    }
    echo '</ul>';

    // Research lines
    if (!empty($json['research_lines']) && is_array($json['research_lines'])) {
        echo '<h5 style="margin-top:20px; margin-bottom:5px;">' . esc_html($labels['research_lines']) . '</h5>';
        echo '<ul style="padding-left: 15px; margin-bottom:10px;">';
        foreach ($json['research_lines'] as $line) {
            // Allow basic HTML tags
            $line = nl2br(wp_kses($line, ['br' => [], 'p' => [], 'strong' => [], 'em' => [], 'b' => [], 'i' => []]));
            echo '<li>' . $line . '</li>';
        }
        echo '</ul>';
    }
    
    if (!empty($json['research_description'])) {
        // Allow basic HTML tags
        $desc = nl2br(wp_kses($json['research_description'], ['br' => [], 'p' => [], 'strong' => [], 'em' => [], 'b' => [], 'i' => []]));
        echo '<p>' . $desc . '</p>';
    }

    // Publications
    if (!empty($json['publications']) && is_array($json['publications'])) {
        echo '<h5 style="margin-top:20px; margin-bottom:5px;">' . esc_html($labels['publications']) . '</h5>';
        echo '<ul style="padding-left: 15px;">';
        foreach ($json['publications'] as $line) {
            // Allow basic HTML tags
            $line = nl2br(wp_kses($line, ['br' => [], 'p' => [], 'strong' => [], 'em' => [], 'b' => [], 'i' => []]));
            echo '<li>' . $line . '</li>';
        }
        echo '</ul>';
    }
}
?>
<?php
// Get translated "Back" label
$back_label = __('Back', 'jsonifywp');

// Get previous page URL (fallback to home if not set)
$prev_url = isset($_SERVER['HTTP_REFERER']) ? esc_url($_SERVER['HTTP_REFERER']) : home_url();

echo '<div class="jws_pagination jws_ajax" style="text-align: left;">';
echo '<a class="jws-load-more" style="margin-top:40px;" href="' . $prev_url . '">';
echo '<span>' . esc_html($back_label) . '</span>';
echo '</a>';
echo '</div>';
?>
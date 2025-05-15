<?php
// $json contains the decoded JSON from the detail API

if (is_array($json)) {
    // Translation ready
    $labels = [
        'fullname'       => __('Full Name', 'jsonifywp'),
        'direct_phone'   => __('Email', 'jsonifywp'),
        'extension'      => __('Extension', 'jsonifywp'),
        'office'         => __('Office', 'jsonifywp'),
        'web'            => __('Website', 'jsonifywp'),
        'research_lines' => __('Research Lines', 'jsonifywp'),
        'research_description' => __('Research Description', 'jsonifywp'),
        'publications' => __('Selected and/or Featured Publications', 'jsonifywp'),
    ];

    // Show full name
    if (!empty($json['fullname'])) {
        echo '<h3>' . esc_html($json['fullname']) . '</h3>';
    }

    echo '<ul>';
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
        echo '<li>' . esc_html($labels['web']) . ': ' . esc_html($json['web']) . '</li>';
    }
    echo '</ul>';

    // Research lines
    if (!empty($json['research_lines']) && is_array($json['research_lines'])) {
        echo '<h4>' . esc_html($labels['research_lines']) . '</h4>';
        echo '<ul>';
        foreach ($json['research_lines'] as $line) {
            echo '<li>' . esc_html($line) . '</li>';
        }
        echo '</ul>';
    }
    
    if (!empty($json['research_description'])) {
        echo '<p>' . esc_html($json['research_description']) . '</p>';
    }

    // Publications
    if (!empty($json['publications']) && is_array($json['publications'])) {
        echo '<h4>' . esc_html($labels['publications']) . '</h4>';
        echo '<ul>';
        foreach ($json['publications'] as $line) {
            echo '<li>' . esc_html($line) . '</li>';
        }
        echo '</ul>';
    }
}
?>
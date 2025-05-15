<?php
// $json conté el JSON decodificat de l'API de detall

if (is_array($json)) {
    // Traducció preparada
    $labels = [
        'fullname'       => __('Full Name', 'jsonifywp'),
        'direct_phone'   => __('Correu electrònic', 'jsonifywp'),
        'extension'      => __('Extensió', 'jsonifywp'),
        'office'         => __('Despatx', 'jsonifywp'),
        'web'            => __('Web', 'jsonifywp'),
        'research_lines' => __('Línies de recerca', 'jsonifywp'),
        'research_description' => __('Línies de recerca', 'jsonifywp'),
        'publications' => __('Publicacions Seleccionades i/o Característiques', 'jsonifywp'),
    ];

    // Mostra el nom complet
    if (!empty($json['fullname'])) {
        echo '<h3>' . esc_html($json['fullname']) . '</h3>';
    }

    echo '<ul>';
    // Mostra els camps segons l'ordre i etiqueta
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

    // Línies de recerca
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

    // Publicacions
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
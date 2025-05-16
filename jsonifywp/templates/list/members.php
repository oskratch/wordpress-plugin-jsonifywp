<?php
// $json contains the decoded JSON
if (is_array($json)) {
    // Translation ready
    $labels = [
        'direct_phone'   => __('Phone', 'jsonifywp'),
        'extension'      => __('Extension', 'jsonifywp'),
        'office'         => __('Office', 'jsonifywp'),
    ];
    foreach ($json as $index => $item) {
        ?>
        <div class="row">
            <div class="jws_team_item col-xl-12 col-lg-12 col-12">
                <div class="jws_team_inner">
                    <div class="jws_team_content">
                        <h4 class="team_title" style="margin-bottom:10px;"><?php echo esc_html($item['fullname']); ?></h4>                             
                        <?php if (!empty($item['office'])): ?>
                            <div class="event-time" style="display:inline-block; margin-right:10px;">
                                <i class="jws-icon-pin"></i>
                                <?php echo esc_html($labels['office']) . ': ' . esc_html($item['office']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['direct_phone'])): ?>
                            <div class="event-time" style="display:inline-block; margin-right:10px;">
                                <i class="jws-icon-mobile"></i>
                                <?php
                                    $phone = preg_replace('/\D+/', '', $item['direct_phone']);
                                    $tel_link = 'tel:' . $phone;
                                    echo esc_html($labels['direct_phone']) . ': ';
                                ?>
                                <a href="<?php echo esc_attr($tel_link); ?>">
                                    <?php echo esc_html($item['direct_phone']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['extension'])): ?>
                            <div class="event-time" style="display:inline-block;">
                                <i class="jws-icon-round-arrow-right"></i>
                                <?php echo esc_html($labels['extension']) . ': ' . esc_html($item['extension']); ?>
                            </div>
                        <?php endif; ?>    
                        <?php if (isset($item['employee_profile'])): ?>
                            <div style="margin-top:5px;">
                                <a class="elementor-button btn btn-naked btn-icon-shaped btn-has-label" href="<?php echo esc_url(add_query_arg(['jsonifywp_id' => $type_id, 'item' => $index], $item_obj->detail_page_url)); ?>">
                                    <span data-text="View all events" class="btn-txt"><?php _e('More info', 'jsonifywp'); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <hr style="margin-top:20px; margin-bottom:20px;">
        <?php
    }
}
?>
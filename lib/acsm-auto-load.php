<?php

if (!function_exists('acsm_auto_load')) {

    function acsm_auto_load() {
        if (!is_singular()) {
            return;
        }

        $post_id = get_queried_object_id();
        if (empty($post_id)) {
            return;
        }

        $meta_query = array(
            'relation' => 'AND',
            array(
                'key'     => 'acsm_activate_modal',
                'value'   => 1,
                'compare' => '='
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => 'acsm_selected_pages',
                    'value'   => $post_id,
                    'compare' => 'LIKE'
                ),
                array(
                    'key'     => 'acsm_selected_pages',
                    'value'   => '',
                    'compare' => '='
                )
            )
        );

        $query = new WP_Query(array(
            'post_type'  => 'acsm-simple-modal',
            'meta_query' => $meta_query
        ));

        if (!$query->have_posts()) {
            return;
        }

        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $modal_post_id = get_the_ID();
            $show_option = function_exists('get_field') ? get_field('acsm_show_option', $modal_post_id) : '';

            switch ($show_option) {
                case 'on_load':
                    $template = __DIR__ . '/../templates/modal-template-on-load.php';
                    break;
                case 'on_leave':
                    $template = __DIR__ . '/../templates/modal-template-on-leave.php';
                    break;
                default:
                    $template = __DIR__ . '/../templates/modal-template.php';
            }

            if (file_exists($template)) {
                include($template);
            } else {
                error_log("Missing modal template: " . $template);
            }
        }

        wp_reset_postdata();
        echo ob_get_clean();
    }

    add_action('wp_footer', 'acsm_auto_load', 100);
}

<?php

if (!function_exists('acsm_modal_sc')) {
    function acsm_modal_sc($atts, $content = null) {
        // Sanitize and set default attributes
        $atts = shortcode_atts(array(
            'modal_id'    => '',
            'label'       => 'Show',
            'class'       => 'c-btn',
            'modal_type'  => 'inline',
            'link_type'   => 'text',
            'link_image'  => '',
            'timber'      => 'false'
        ), $atts, 'ac_simple_modal');

        if (empty($atts['modal_id'])) {
            return '';
        }

        // Assign variables
        $modal_id   = esc_attr($atts['modal_id']);
        $label      = esc_html($atts['label']);
        $class      = esc_attr($atts['class']);
        $modal_type = esc_attr($atts['modal_type']);
        $link_type  = esc_attr($atts['link_type']);
        $link_image = esc_attr($atts['link_image']);
        $timber     = esc_attr($atts['timber']);

        $output = '';

        $modal_query = new WP_Query(array(
            'post_type' => 'acsm-simple-modal',
            'p'         => $modal_id,
        ));

        if ($modal_query->have_posts()) {
            while ($modal_query->have_posts()) {
                $modal_query->the_post();

                ob_start();

                if ($timber !== 'false') {
                    Timber::render('modal.twig', array(
                        'modal_id'   => $modal_id,
                        'label'      => $label,
                        'class'      => $class,
                        'modal_type' => $modal_type,
                        'link_type'  => $link_type,
                        'link_image' => $link_image,
                        'post'       => Timber::get_post(),
                    ));
                } else {
                    include(__DIR__ . "/../templates/modal-template-on-click.php");
                }

                $output .= ob_get_clean();
            }
        }

        wp_reset_postdata();
        return $output;
    }

    add_shortcode('ac_simple_modal', 'acsm_modal_sc');
}




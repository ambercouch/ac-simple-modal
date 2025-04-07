<?php

// Include the plugin-code.
require_once(  'acsm-cpt.php' );

require_once(  'acsm-acf.php' );


if (!function_exists('acsm_modal_sc'))
    {

        function acsm_modal_sc($atts, $content = null)
        {
            // Sanitize and set default attributes
            $atts = shortcode_atts(array(
                'modal_id' => '',
                'label' => 'Show',
                'class' => 'c-btn',
                'modal_type' => 'inline',
                'timber' => 'false'
            ), $atts, 'ac_simple_modal');

            // Return early if no modal ID is provided
            if (empty($atts['modal_id']))
            {
                return '';
            }

            // Assign variables
            $modal_id = esc_attr($atts['modal_id']);
            $label = esc_html($atts['label']);
            $class = esc_attr($atts['class']);
            $modal_type = esc_attr($atts['modal_type']);
            $timber = esc_attr($atts['timber']);

            // Prepare output
            $output = '';

            // Create a new query for the modal post
            $modal_query = new WP_Query(array(
                'post_type' => 'acsm-simple-modal',
                'p' => $modal_id,
            ));

            if ($modal_query->have_posts())
            {
                while ($modal_query->have_posts())
                {
                    $modal_query->the_post();

                    ob_start();

                    // Timber check (TIB site)
                    if ($timber != 'false')
                    {
                        $output .= "timber";
                        $output .= $timber;
                        Timber::render('modal.twig', array(
                            'modal_id' => $modal_id,
                            'label' => $label,
                            'class' => $class,
                            'modal_type' => $modal_type,
                            'post' => Timber::get_post(),
                        ));
                    } else
                    {
                        // Fallback for PCM or non-Timber installs
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


if (!function_exists('acsm_modal')) {

    function acsm_modal() {
        error_log('acsm_modal logged');
        global $post;

        if (empty($post->ID)) {
            return;
        }

        // Define meta queries to check modal activation and page selection
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
                    'value'   => $post->ID,
                    'compare' => 'LIKE'
                ),
                array(
                    'key'     => 'acsm_selected_pages',
                    'value'   => '',
                    'compare' => '='
                )
            )
        );

        // Query for modals with these specifications
        $query = new WP_Query(array(
            'post_type'  => 'acsm-simple-modal',
            'meta_query' => $meta_query
        ));

        if (!$query->have_posts()) {
            return;
        }

        $output = "";
        ob_start();

        while ($query->have_posts()) {
            $query->the_post();
            $modal_post_id = get_the_ID();
            $show_option = get_field('acsm_show_option', $modal_post_id);

            // Logging for debugging
            error_log("Modal post ID: " . $modal_post_id);
            error_log("Show option: " . $show_option);

            // Choose template
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

            $output .= ob_get_clean();
            ob_start();
        }

        wp_reset_postdata();
        echo $output;
    }

    add_action('wp_footer', 'acsm_modal', 100);
}


function acsm_enqueue_scripts(){

    wp_register_script('acsm_modaal', plugins_url('../assets/js/scripts-acsm.js', __FILE__), array('jquery'), '1.1', true);
    wp_enqueue_script('acsm_modaal');
}

add_action( 'wp_enqueue_scripts', 'acsm_enqueue_scripts' );

function acsm_enqueue_styles() {
    wp_register_style('acsm_modaal_styles', plugins_url('../assets/css/styles-acsm.css', __FILE__));
    wp_enqueue_style('acsm_modaal_styles');
}
add_action( 'get_footer', 'acsm_enqueue_styles' );

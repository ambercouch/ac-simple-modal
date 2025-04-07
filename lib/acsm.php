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
            'modal_type' => 'inline', // Ensure modal_type is set here
            'timber' => 'false'
        ), $atts, 'ac_simple_modal');

        // Return early if no modal ID is provided
        if (empty($atts['modal_id'])) {
            return '';
        }

        // Create a new query for the modal post
        $modal_query = new WP_Query(array(
            'post_type' => 'acsm-simple-modal',
            'p' => $atts['modal_id'],
        ));

        // Check if Timber is available
        //$timber = false;

        // Initialize output
        $output = '';

        if ($modal_query->have_posts()) {
            if ($timber == false) {
                while ($modal_query->have_posts()) {
                    $modal_query->the_post();

                    // Make attributes available to the template
                    $class = esc_attr($atts['class']);
                    $label = esc_html($atts['label']);
                    $modal_type = esc_attr($atts['modal_type']); // Make sure modal_type is available

                    // Start output buffering
                    ob_start();
                    include __DIR__ . "/../templates/modal-template-on-click.php";
                    $output .= ob_get_clean();
                }
            } else {
                $context = Timber::get_context();
                $context['posts'] = Timber::get_posts($modal_query);
                $context['class'] = $atts['class'];
                $context['label'] = $atts['label'];
                $context['modal_type'] = $atts['modal_type'];
                $templates = array('loop-template.twig');

                // Start output buffering
                ob_start();
                Timber::render($templates, $context);
                $output .= ob_get_clean();
            }
        }

        // Reset post data
        wp_reset_postdata();

        return $output;
    }

    add_shortcode('ac_simple_modal', 'acsm_modal_sc');


}

function acsm_modal() {
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
                'compare' => 'LIKE'  // Consider changing to 'IN' if storing IDs as an array
            ),
            array(
                'key'     => 'acsm_selected_pages',
                'value'   => '',  // Assuming this means the modal should appear on all pages
                'compare' => '='
            )
        )
    );

    // Query for modals with these specifications
    $query = new WP_Query(array(
        'post_type'      => 'acsm-simple-modal',
        'meta_query'     => $meta_query
    ));

    if (!$query->have_posts()) {
        return;
    }


    $output = "";
    ob_start();

    while ($query->have_posts()) {
        $query->the_post();
        $modal_post_id = get_the_ID(); // Gets the ID of the modal post
        $show_option = get_field('acsm_show_option', $modal_post_id); // Use the modal post ID

        // Logging for debugging
        error_log("Modal post ID: " . $modal_post_id);
        error_log("Show option: " . $show_option);


        // Setting $template based on $show_option
        switch ($show_option) {
            case 'on_load':
                $template = __DIR__ . "/../templates/modal-template-on-load.php";                    break;
            case 'on_leave':
                $template = __DIR__ . "/../templates/modal-template-on-leave.php";                    break;
            default:
                $template = __DIR__ . "/../templates/modal-template-on-load.php";            }

        include($template);

        $output .= ob_get_clean();  // Append the current buffer to $output and then clean it
        ob_start();  // Start a new buffer for the next iteration
    }
    wp_reset_postdata(); // Very important to reset post data after modifying the query

    echo $output;
}

add_action( 'wp_footer', 'acsm_modal', 100 );


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

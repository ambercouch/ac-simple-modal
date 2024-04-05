<?php

// Include the plugin-code.
require_once(  'acsm-cpt.php' );

require_once(  'acsm-acf.php' );

if (!function_exists('acsm_modal_sc'))
{

    function acsm_modal_sc($atts, $content)
    {

        extract(shortcode_atts(array(
            'modal_id' => '',
            'label' => 'Show',
            'class' => 'c-btn',
            'modal_type' => 'inline'
        ), $atts));

        $timber = false;

        global $wp_query;
        $post_id = $wp_query->post->ID;

        /*
         * Save the current query and create a new one
         */
        $temp_q = $wp_query;
        $wp_query = null;
        $wp_query = new WP_Query();

        $wp_query->query(array(
            'post_type' => 'acsm-simple-modal',
            'p' => $modal_id,

        ));

        $template = __DIR__ . "/../templates/modal-template-on-click.php";
        $output = "";


        if($timber === false){

            while (have_posts()):
                the_post();

                ob_start();
                ?>
                <?php include("$template"); ?>
                <?php
                $output .= ob_get_contents();
                ob_end_clean();
            endwhile;

        }else{
            $context = Timber::get_context();
            $context['posts'] = Timber::get_posts();
            $templates = array('loop-template.twig');
            ob_start();
            Timber::render( $templates, $context );
            $output .= ob_get_contents();
            ob_end_clean();
        }

        $wp_query = $temp_q;

        return $output;

    }

    add_shortcode('ac_simple_modal', 'acsm_modal_sc');

}

function acsm_modal() {

    $timber = false;

    global $wp_query;
    $post_id = $wp_query->post->ID;

    /*
     * Save the current query and create a new one
     */
    $temp_q = $wp_query;
    $wp_query = null;
    $wp_query = new WP_Query();

    $meta_query_active = array(
        'key' => 'acsm_activate_modal',
        'value' => 1
    );
    $meta_query_selected_pages = array(
        'key' => 'acsm_selected_pages',
        'value' => $post_id,
        'compare' => 'LIKE'
    );
    $meta_query_all_pages = array(
        'key' => 'acsm_selected_pages',
        'value' => ''
    );

    $meta_query =  array(
        'relation' => 'AND',
        $meta_query_active,
        array(
            'relation' => 'OR',
            $meta_query_all_pages,
            $meta_query_selected_pages
        )
    );

    $wp_query->query(array(
        'post_type' => 'acsm-simple-modal',
        'showposts' => 1,
        'meta_query' => $meta_query
    ));

    $template = __DIR__ . "/../templates/modal-template.php";
    $output = "";


    if($timber === false){

        while (have_posts()):
            the_post();
            $show_option = get_field('acsm_show_option', the_ID());


            // Setting $template based on $show_option
            switch ($show_option) {
                case 'on_click':
                    $template = __DIR__ . "/../templates/modal-template-on-click.php";                    break;
                case 'on_load':
                    $template = __DIR__ . "/../templates/modal-template-on-load.php";                    break;
                case 'on_leave':
                    $template = __DIR__ . "/../templates/modal-template-on-leave.php";                    break;
                default:
                    $template = __DIR__ . "/../templates/modal-template.php";            }

            error_log('$show_option');
            error_log(print_r($show_option, true));
            error_log('$template');
            error_log($template);
            ob_start();
            ?>
            <?php include("$template"); ?>
            <?php
            $output .= ob_get_contents();
            ob_end_clean();
        endwhile;

    }else{
        $context = Timber::get_context();
        $context['posts'] = Timber::get_posts();
        $templates = array('loop-template.twig');
        ob_start();
        Timber::render( $templates, $context );
        $output .= ob_get_contents();
        ob_end_clean();
    }

    echo $output;

}

//add_action( 'wp_footer', 'acsm_modal', 100 );


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

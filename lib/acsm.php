<?php

// Include the plugin-code.
require_once(  'acsm-cpt.php' );

function acsm_modal() {

    $timber = false;

    global $wp_query;
    $post_id = $wp_query->post->ID;

    $temp_q = $wp_query;

    $wp_query = null;
    $wp_query = new WP_Query();

    $compare = (get_field('hide_or_show') == 'hide') ? 'LIKE' : 'NOT LIKE';
    $wp_query->query(array(
        'post_type' => 'acsm-simple-modal',
        'showposts' => 1,
        'meta_query' => array(
            array(
                'key' => 'show_on_pages',
                'value' => $post_id,
                'compare' => $compare
            )
        )
    ));

    $template = __DIR__ . "/../templates/modal-template.php";
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

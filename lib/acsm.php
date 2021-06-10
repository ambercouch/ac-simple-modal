<?php

function acsm_modal() {

    $timber = false;

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

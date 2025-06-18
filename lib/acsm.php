<?php

// Include the plugin-code.
require_once(  'acsm-cpt.php' );

require_once(  'acsm-acf.php' );

require_once(  'acsm-sc.php' );

require_once(  'acsm-auto-load.php' );

require_once(  'acsm-custom-menu-item.php' );


function acsm_enqueue_scripts(){

    wp_register_script('acsm_modaal', plugins_url('../assets/js/scripts-acsm.js', __FILE__), array('jquery'), '1.1', true);
    wp_enqueue_script('acsm_modaal');
}

add_action( 'wp_enqueue_scripts', 'acsm_enqueue_scripts' );

function acsm_enqueue_styles() {
    wp_register_style('acsm_modaal_styles', plugins_url('../assets/css/styles-acsm.css', __FILE__));
    wp_enqueue_style('acsm_modaal_styles');
}
add_action( 'wp_footer', 'acsm_enqueue_styles' );

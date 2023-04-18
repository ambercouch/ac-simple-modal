<?php
function  acsm_cpt() {
//acsm modal
    $labels = array(
        'name' => _x('Simple Modal', 'post type general name'),
        'singular_name' => _x('Simple Modal', 'post type singular name'),
        'add_new' => _x('Add New', 'Modal'),
        'add_new_item' => __('Add New Modal'),
        'edit_item' => __('Edit Modal'),
        'new_item' => __('New Modal'),
        'all_items' => __('All Modals'),
        'view_item' => __('View Modal'),
        'search_items' => __('Search Modals'),
        'not_found' => __('No Modals found'),
        'not_found_in_trash' => __('No Modals found in the trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Simple Modal'
    );
    $args = array(
        'labels' => $labels,
        'menu_icon' => 'dashicons-slides',
        'description' => 'Simple modal popup windows',
        'public' => true,
        'menu_position' => 20,
        'supports' => array('title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'),
        'has_archive' => false,
        'publicly_queryable' => false
    );
    register_post_type('acsm-simple-modal', $args);

}

add_action('init', 'acsm_cpt');

<?php

/*
 *  add_action('after_setup_theme', 'ac_late_loader');
 *  add_action('plugins_loaded', 'ac_late_loader');
 *  AC : unsure pros/cons of each
 */

add_action('plugins_loaded', 'ac_late_loader');
    function ac_late_loader(){
    if( function_exists('acf_add_local_field_group') ):

        acf_add_local_field_group(array(
            'key' => 'group_acsm_display_settings',
            'title' => 'Display Settings',
            'fields' => array(
                array(
                    'key' => 'field_acsm_activate_modal',
                    'label' => 'Activate Modal',
                    'name' => 'acsm_activate_modal',
                    'type' => 'true_false',
                    'instructions' => 'Select "Yes" to activate and display the modal or "No" to disable and hide the modal',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => 'Activate',
                    'default_value' => 1,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_acsm_show_option',
                    'label' => 'When to Show Modal',
                    'name' => 'acsm_show_option',
                    'type' => 'select',
                    'instructions' => 'Select when you want to show the modal',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_acsm_activate_modal',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'choices' => array(
                        'on_click' => 'Show on link click',
                        'on_load' => 'Show on page load',
                        'on_leave' => 'Show on user leaves',
                    ),
                    'default_value' => 'on_load',
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_acsm_selected_pages',
                    'label' => 'Selected Pages',
                    'name' => 'acsm_selected_pages',
                    'type' => 'relationship',
                    'instructions' => 'Select the pages to show the modal or leave blank to show on all pages',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_acsm_activate_modal',
                                'operator' => '==',
                                'value' => '1',
                            ),
                            array(
                                'field' => 'field_acsm_show_option',
                                'operator' => '==',
                                'value' => 'on_load',
                            ),
                        )
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post',
                        1 => 'page',
                    ),
                    'taxonomy' => '',
                    'filters' => array(
                        0 => 'search',
                    ),
                    'elements' => '',
                    'min' => '',
                    'max' => '',
                    'return_format' => 'id',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'acsm-simple-modal',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));

    endif;
}

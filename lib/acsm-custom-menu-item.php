<?php


if (!function_exists('acsm_add_modal_to_menu'))
{
    add_filter('wp_nav_menu_objects', 'acsm_add_modal_to_menu', 10, 2);

    function acsm_add_modal_to_menu($items, $args) {
        foreach ($items as &$item) {
            if ($item->object === 'acsm-simple-modal') {
                $modal_id = $item->object_id;

                // Replace the URL to point to modal
                $item->url = '#modal' . $modal_id;

                // Add base classes
                $item->classes[] = 'c-acsm-menu-link';
                $item->classes[] = 'is-link-type-text';

                // Load modal content early
                $modal_post = get_post($modal_id);
                $content = $modal_post ? $modal_post->post_content : '';

                // Check if it contains an iframe
                $is_video = stripos($content, '<iframe') !== false;
                $item->acsm_modal_type = $is_video ? 'video' : 'inline';

                // Store modal ID for later printing in wp_footer
                $item->acsm_modal_id = $modal_id;
            }
        }

        // Store for later modal output
        global $wp_nav_menu_items;
        $wp_nav_menu_items = $items;

        return $items;
    }

}

add_filter('nav_menu_link_attributes', 'acsm_add_modal_menu_link_attributes', 10, 3);

function acsm_add_modal_menu_link_attributes($atts, $item, $args) {
    if (!empty($item->acsm_modal_id)) {
        $atts['data-modal-opener'] = 'modal' . esc_attr($item->acsm_modal_id);
        $atts['data-modal-type'] = esc_attr($item->acsm_modal_type ?? 'inline');
    }

    return $atts;
}


if ( ! function_exists( 'acsm_output_modals_from_menu' ) ) {
    add_action( 'wp_footer', 'acsm_output_modals_from_menu' );

    function acsm_output_modals_from_menu() {
        // 1) grab the primary menu items directly
        $locations = get_nav_menu_locations();
        if ( empty( $locations['primary'] ) ) {
            return;
        }
        $menu_obj  = wp_get_nav_menu_object( $locations['primary'] );
        if ( ! $menu_obj ) {
            return;
        }
        $items = wp_get_nav_menu_items( $menu_obj->term_id );
        if ( empty( $items ) ) {
            return;
        }

        // 2) loop through and find any acsm-simple-modal items
        $printed = [];
        foreach ( $items as $item ) {
            if ( $item->object === 'acsm-simple-modal' && ! in_array( $item->object_id, $printed, true ) ) {
                $modal_id  = intval( $item->object_id );
                $modal_post = get_post( $modal_id );
                if ( ! $modal_post ) {
                    continue;
                }

                // detect video vs inline
                $content = $modal_post->post_content;
                $is_video = stripos( $content, '<iframe' ) !== false;

                // update the menu‐item’s type so nav_menu_link_attributes() can pass the right data
                $item->acsm_modal_type = $is_video ? 'video' : 'inline';

                // 3) print the hidden modal container
                ?>
              <div id="modal<?php echo esc_attr( $modal_post->ID ); ?>"
                   data-modal="modal<?php echo esc_attr( $modal_post->ID ); ?>"
                   data-modal-type="<?php echo esc_attr( $item->acsm_modal_type ); ?>"
                   style="display:none;"
                   class="c-acsm__modal is-on-click">
                <div class="c-acsm__content">
                    <?php echo apply_filters( 'the_content', $modal_post->post_content ); ?>
                </div>
              </div>
                <?php

                $printed[] = $modal_id;
            }
        }
    }
}


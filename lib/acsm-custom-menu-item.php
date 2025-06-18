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


if (!function_exists('acsm_output_modals_from_menu'))
{
    add_action('wp_footer', 'acsm_output_modals_from_menu');

    function acsm_output_modals_from_menu() {
        global $wp_nav_menu_items;

        if (empty($wp_nav_menu_items)) {
            return;
        }

        $printed_ids = [];

        foreach ($wp_nav_menu_items as &$item) {
            if (!empty($item->acsm_modal_id) && !in_array($item->acsm_modal_id, $printed_ids, true)) {
                $modal_id = intval($item->acsm_modal_id);

                $modal_query = new WP_Query(array(
                    'post_type' => 'acsm-simple-modal',
                    'p'         => $modal_id,
                ));

                if ($modal_query->have_posts()) {
                    while ($modal_query->have_posts()) {
                        $modal_query->the_post();

                        $content = get_the_content();

                        // Detect if it's a video
                        $is_video = stripos($content, '<iframe') !== false;

                        // Update modal type for link attributes
                        $item->acsm_modal_type = $is_video ? 'video' : 'inline';

                        // Output modal (no changes needed here)
                        ?>
                        <div id="modal<?php echo esc_attr(get_the_ID()); ?>"
                             data-modal="modal<?php echo esc_attr(get_the_ID()); ?>"
                             style="display:none;"
                             class="c-acsm__modal is-on-click">
                            <div class="c-acsm__content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <?php

                        $printed_ids[] = $modal_id;
                    }

                    wp_reset_postdata();
                }
            }
        }
    }
}

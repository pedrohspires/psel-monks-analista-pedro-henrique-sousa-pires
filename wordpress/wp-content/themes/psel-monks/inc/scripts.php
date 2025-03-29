<?php

#region SECTION SCRIPTS ---------------------------------------------------------------------------------
function save_section_order_meta($post_id)
{
    if (!isset($_POST['section_order_nonce']) || !wp_verify_nonce($_POST['section_order_nonce'], 'save_section_order')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['section_order'])) {
        update_post_meta($post_id, '_section_order', intval($_POST['section_order']));
    }
}
add_action('save_post', 'save_section_order_meta');

function set_default_section_order($post_id, $post, $update)
{
    if ($post->post_type !== 'sections') {
        return;
    }

    if ($update) {
        return;
    }

    $existing_order = get_post_meta($post_id, '_section_order', true);
    if (!empty($existing_order)) {
        return;
    }

    $last_order = new WP_Query(array(
        'post_type'      => 'sections',
        'posts_per_page' => 1,
        'meta_key'       => '_section_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'fields'         => 'ids',
    ));

    $new_order = 1;
    if ($last_order->have_posts()) {
        $last_id = $last_order->posts[0];
        $last_order_value = get_post_meta($last_id, '_section_order', true);
        $new_order = intval($last_order_value) + 1;
    }


    update_post_meta($post_id, '_section_order', $new_order);
}
add_action('save_post', 'set_default_section_order', 10, 3);

function modify_sections_admin_order($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') === 'sections') {
        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key'     => '_section_order',
                'compare' => 'EXISTS',
            ),
            array(
                'key'     => '_section_order',
                'compare' => 'NOT EXISTS',
            ),
        ));
        $query->set('orderby', array(
            'meta_value_num' => 'ASC',
            'date'           => 'DESC',
        ));
    }
}
add_action('pre_get_posts', 'modify_sections_admin_order');

function add_section_order_column($columns)
{
    $columns['section_order'] = 'Ordem';
    return $columns;
}
add_filter('manage_sections_posts_columns', 'add_section_order_column');

function show_section_order_column($column, $post_id)
{
    if ($column === 'section_order') {
        $order = get_post_meta($post_id, '_section_order', true);
        echo esc_html($order ? $order : '-');
    }
}
add_action('manage_sections_posts_custom_column', 'show_section_order_column', 10, 2);

function add_section_order_to_rest_api()
{
    register_rest_field('sections', 'section_order', array(
        'get_callback'    => function ($post) {
            return get_post_meta($post['id'], '_section_order', true);
        },
        'update_callback' => function ($value, $post) {
            update_post_meta($post->ID, '_section_order', intval($value));
        },
        'schema'          => array(
            'type'        => 'integer',
            'description' => 'Ordem da seção',
            'context'     => array('view', 'edit')
        )
    ));
}
add_action('rest_api_init', 'add_section_order_to_rest_api');

function order_sections_rest_api($args, $request)
{
    $args['meta_key'] = '_section_order';
    $args['orderby']  = 'meta_value_num';
    $args['order']    = 'ASC';

    return $args;
}

add_filter('rest_sections_query', 'order_sections_rest_api', 10, 2);

#endregion


#region REST API SCRIPTS --------------------------------------------------------------------------------
function add_card_image_to_rest_api()
{
    register_rest_field('cards', 'card_image_url', array(
        'get_callback'    => function ($post) {
            $image_url = get_post_meta($post['id'], '_card_image_url', true);
            if ($image_url) {
                return esc_url($image_url);
            } else {
                return '';
            }
        },
        'schema'          => array(
            'type'        => 'string',
            'description' => 'URL da imagem do card',
            'context'     => array('view', 'edit')
        )
    ));
}
add_action('rest_api_init', 'add_card_image_to_rest_api');

#endregion


#region CARDS SCRIPTS --------------------------------------------------------------------------------
function save_card_section_relation($post_id)
{
    if (isset($_POST['section_relation'])) {
        update_post_meta($post_id, 'section_relation', $_POST['section_relation']);
    }
}
add_action('save_post', 'save_card_section_relation');

function save_card_custom_fields($post_id)
{
    if (isset($_POST['card_button_text'])) {
        update_post_meta($post_id, 'card_button_text', sanitize_text_field($_POST['card_button_text']));
    }
}
add_action('save_post', 'save_card_custom_fields');

add_filter('use_block_editor_for_post', '__return_false');

function add_cards_to_sections_api()
{
    register_rest_field('sections', 'cards', array(
        'get_callback'    => 'get_section_cards',
        'update_callback' => null,
        'schema'          => null,
    ));
}

add_action('rest_api_init', 'add_cards_to_sections_api');

function get_section_cards($object)
{
    $section_id = $object['id'];

    $cards = get_posts(array(
        'post_type'   => 'cards',
        'meta_query'  => array(
            array(
                'key'   => 'section_relation',
                'value' => $section_id,
                'compare' => '='
            )
        ),
        'numberposts' => -1
    ));

    if (!$cards) {
        return [];
    }

    $formatted_cards = [];
    foreach ($cards as $card) {
        $thumbnail_url = get_the_post_thumbnail_url($card->ID, 'full');

        $image_url = get_post_meta($card->ID, '_card_image_url', true);
        if (!$image_url) {
            $image_url = $thumbnail_url;
        }

        $formatted_cards[] = array(
            'id'           => $card->ID,
            'title'        => get_the_title($card->ID),
            'content'      => apply_filters('the_content', $card->post_content),
            'image_url'    => $image_url,
            'button_text'  => get_post_meta($card->ID, 'card_button_text', true),
        );
    }

    return $formatted_cards;
}

#endregion
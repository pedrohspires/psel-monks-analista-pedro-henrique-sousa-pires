<?php

function custom_post_type_sections()
{
    $labels = array(
        'name'               => 'Seções',
        'singular_name'      => 'Seção',
        'menu_name'          => 'Seções',
        'add_new'            => 'Adicionar Nova',
        'add_new_item'       => 'Adicionar Nova Seção',
        'edit_item'          => 'Editar Seção',
        'new_item'           => 'Nova Seção',
        'view_item'          => 'Ver Seção',
        'search_items'       => 'Buscar Seções',
        'not_found'          => 'Nenhuma seção encontrada',
        'not_found_in_trash' => 'Nenhuma seção encontrada na lixeira'
    );

    $args = array(
        'label'              => 'sections',
        'description'        => 'Cadastro de seções e seus cards',
        'labels'             => $labels,
        'supports'           => array('title', 'editor', 'thumbnail', 'wp-blocks'),
        'hierarchical'       => false,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-layout',
        'show_in_admin_bar'  => true,
        'show_in_nav_menus'  => true,
        'can_export'         => true,
        'has_archive'        => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type'    => 'post',
        'show_in_rest'       => true,
    );

    register_post_type('sections', $args);
}
add_action('init', 'custom_post_type_sections');

function add_section_order_meta_box()
{
    add_meta_box(
        'section_order_meta_box',
        'Ordem da Seção',
        'section_order_meta_box_callback',
        'sections',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_section_order_meta_box');

function section_order_meta_box_callback($post)
{
    wp_nonce_field('save_section_order', 'section_order_nonce');

    $value = get_post_meta($post->ID, '_section_order', true);
    echo '<label for="section_order">Defina a ordem de exibição:</label>';
    echo '<input type="number" id="section_order" name="section_order" value="' . esc_attr($value) . '" min="1" style="width:100%;">';
}

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

function custom_post_type_cards()
{
    $labels = array(
        'name'               => 'Cards',
        'singular_name'      => 'Card',
        'menu_name'          => 'Cards',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Card',
        'edit_item'          => 'Editar Card',
        'new_item'           => 'Novo Card',
        'view_item'          => 'Ver Card',
        'search_items'       => 'Buscar Cards',
        'not_found'          => 'Nenhum card encontrado',
        'not_found_in_trash' => 'Nenhum card encontrado na lixeira'
    );

    $args = array(
        'label'              => 'cards',
        'description'        => 'Cadastro de cards dentro de uma seção',
        'labels'             => $labels,
        'supports'           => array('title', 'editor', 'thumbnail', 'wp-blocks'),
        'hierarchical'       => false,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-grid-view',
        'show_in_admin_bar'  => true,
        'show_in_nav_menus'  => true,
        'can_export'         => true,
        'has_archive'        => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type'    => 'post',
        'show_in_rest'       => true,
    );

    register_post_type('cards', $args);
}
add_action('init', 'custom_post_type_cards');

function add_card_section_metabox()
{
    add_meta_box(
        'card_section_metabox',
        'Seção Relacionada',
        'card_section_metabox_callback',
        'cards',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_card_section_metabox');

function card_section_metabox_callback($post)
{
    $selected_section = get_post_meta($post->ID, 'section_relation', true);
    $sections = get_posts(array('post_type' => 'sections', 'numberposts' => -1));

    echo '<select name="section_relation">';
    echo '<option value="">Selecione uma Seção</option>';
    foreach ($sections as $section) {
        echo '<option value="' . $section->ID . '" ' . selected($selected_section, $section->ID, false) . '>' . $section->post_title . '</option>';
    }
    echo '</select>';
}

function save_card_section_relation($post_id)
{
    if (isset($_POST['section_relation'])) {
        update_post_meta($post_id, 'section_relation', $_POST['section_relation']);
    }
}
add_action('save_post', 'save_card_section_relation');

function add_card_custom_fields()
{
    add_meta_box(
        'card_custom_fields',
        'Detalhes do Card',
        'card_custom_fields_callback',
        'cards',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_card_custom_fields');

function card_custom_fields_callback($post)
{
    $card_button_text = get_post_meta($post->ID, 'card_button_text', true);
    echo '<label>Texto do Botão:</label>';
    echo '<input type="text" name="card_button_text" value="' . esc_attr($card_button_text) . '" style="width:100%;">';
}

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
        $formatted_cards[] = array(
            'id'        => $card->ID,
            'title'     => get_the_title($card->ID),
            'content'   => apply_filters('the_content', $card->post_content),
            'thumbnail' => get_the_post_thumbnail_url($card->ID, 'full'),
            'button_text' => get_post_meta($card->ID, 'card_button_text', true),
        );
    }

    return $formatted_cards;
}

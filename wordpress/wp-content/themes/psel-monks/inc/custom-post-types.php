<?php

function custom_post_type_cards()
{
    $labels = array(
        'name' => 'Cards',
        'singular_name' => 'Card',
        'menu_name' => 'Cards',
        'add_new' => 'Adicionar Novo',
        'add_new_item' => 'Adicionar Novo Card',
        'edit_item' => 'Editar Card',
        'new_item' => 'Novo Card',
        'view_item' => 'Ver Card',
        'search_items' => 'Buscar Cards',
        'not_found' => 'Nenhum card encontrado',
        'not_found_in_trash' => 'Nenhum card encontrado na lixeira'
    );

    register_post_type('cards', array(
        'label' => 'cards',
        'description' => 'Cadastro de cards dentro de uma seção',
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'wp-blocks'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-grid-view',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
    ));
}
add_action('init', 'custom_post_type_cards');


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

    register_post_type('sections', array(
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
    ));
}
add_action('init', 'custom_post_type_sections');


#region CONTACT POST TYPE
function register_custom_form_fields()
{
    $labels = array(
        'name'               => 'Contatos',
        'singular_name'      => 'Contato',
        'menu_name'          => 'Contatos',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Contato',
        'edit_item'          => 'Editar Contato',
        'new_item'           => 'Novo Contato',
        'view_item'          => 'Ver Contato',
        'search_items'       => 'Buscar Contatos',
        'not_found'          => 'Nenhum contato encontrado',
        'not_found_in_trash' => 'Nenhum contato encontrado na lixeira'
    );

    register_post_type('contact', array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'contact',
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'edit_posts'
        ),
        'map_meta_cap' => true,
    ));

    register_meta('contact', 'name', array(
        'type'         => 'string',
        'description'  => 'Nome',
        'single'       => true,
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
    ));

    register_meta('contact', 'phone_number', array(
        'type'         => 'string',
        'description'  => 'Número de telefone',
        'single'       => true,
        'show_in_rest' => true,
    ));

    register_meta('contact', 'email', array(
        'type'         => 'string',
        'description'  => 'Email',
        'single'       => true,
        'show_in_rest' => true,
    ));

    register_meta('contact', 'subject', array(
        'type'         => 'string',
        'description'  => 'Assunto',
        'single'       => true,
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
    ));
}
add_action('init', 'register_custom_form_fields');

function get_phone_number($phone)
{
    $clean_phone = preg_replace('/\D/', '', $phone);

    if (preg_match('/^\d{10,11}$/', $clean_phone)) {
        return $clean_phone;
    }

    return '';
}

function custom_rest_permissions()
{
    register_rest_route('wp/v2', '/contact', array(
        'methods' => 'POST',
        'callback' => 'create_contact_post',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'custom_rest_permissions');

function create_contact_post(WP_REST_Request $request)
{
    $errors = new WP_Error();

    if (!$request['name'])
        $errors->add('invalid_name', 'Informe seu nome.');

    if (!$request['phone_number'])
        $errors->add('invalid_phone', 'Informe seu número de telefone.');
    else {
        $phone_data = get_phone_number($request['phone_number']);

        if (!$phone_data)
            $errors->add('invalid_phone', 'O telefone deve conter no mínimo 10 e no máximo 11 dígitos.');

        $request['phone_number'] = $phone_data;
    }

    if (!$request['subject'])
        $errors->add('invalid_subject', 'Informe seu número de telefone.');

    if (isset($request['email']) && !is_email($request['email']))
        $errors->add('invalid_email', 'O e-mail informado é inválido.');

    if (!isset($request['result']))
        $errors->add('invalid_result', 'Informe o resultado da verificação de segurança.');

    $resultSum = $request['numA'] + $request['numB'];
    if ($resultSum != $request['result'])
        $errors->add('invalid_result', 'Resultado incorreto.');

    if ($errors->has_errors())
        return format_wp_error($errors);

    $post_data = array(
        'post_title'   => sanitize_text_field($request->get_param('name')),
        'post_content' => sanitize_textarea_field($request->get_param('subject')),
        'post_status'  => 'draft',
        'post_type'    => 'contact',
        'post_author'  => 1,
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, 'phone_number', sanitize_text_field($request->get_param('phone_number')));
    update_post_meta($post_id, 'email', sanitize_email($request->get_param('email')));
    update_post_meta($post_id, 'subject', sanitize_textarea_field($request->get_param('subject')));

    return new WP_REST_Response('Contato criado com sucesso', 200);
}

function format_wp_error(WP_Error $wp_error)
{
    $errors = [];

    foreach ($wp_error->get_error_codes() as $code) {
        $messages = $wp_error->get_error_messages($code);

        foreach ($messages as $message) {
            $errors[] = [
                'code'    => $code,
                'message' => $message
            ];
        }
    }

    return new WP_REST_Response([
        'code'   => 'multiple_errors',
        'message' => 'Vários erros encontrados.',
        'errors'  => $errors
    ], 400);
}

#endregion
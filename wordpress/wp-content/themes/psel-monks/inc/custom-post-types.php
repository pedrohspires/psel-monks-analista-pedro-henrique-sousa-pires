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

    $args = array(
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
    );

    register_post_type('cards', $args);
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

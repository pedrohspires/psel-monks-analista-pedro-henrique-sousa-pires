<?php
add_action('rest_api_init', function () {
    register_rest_route('psel-monks/v1', '/mensagem/', array(
        'methods'  => 'GET',
        'callback' => function () {
            return new WP_REST_Response(array('mensagem' => 'WordPress rodando com Docker! 🚀'), 200);
        },
    ));
});

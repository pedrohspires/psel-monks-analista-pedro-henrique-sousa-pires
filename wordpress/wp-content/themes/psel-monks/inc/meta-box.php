<?php

#region SECTION METABOXES -------------------------------------------------------------------------------
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

#endregion


#region CARDS METABOXES ---------------------------------------------------------------------------------
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

#endregion

#region IMAGES METABOXES --------------------------------------------------------------------------------
function add_card_image_metabox()
{
    add_meta_box(
        'card_image',
        'Imagem do Card',
        'render_card_image_metabox',
        'cards',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_card_image_metabox');

function render_card_image_metabox($post)
{
    $image_url = get_post_meta($post->ID, '_card_image_url', true);
?>
    <div class="card-image-metabox">
        <label for="card_image_url">Imagem do Card</label><br>
        <input type="text" name="card_image_url" id="card_image_url" value="<?php echo esc_url($image_url); ?>" style="width: 100%;" />
        <button class="button" id="upload_image_button">Selecionar Imagem</button>
    </div>
    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;
            $('#upload_image_button').click(function(e) {
                e.preventDefault();

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Escolher Imagem',
                    button: {
                        text: 'Selecionar Imagem'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#card_image_url').val(attachment.url);
                });
                mediaUploader.open();
            });
        });
    </script>
<?php
}

function save_card_image_metabox($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (!current_user_can('edit_post', $post_id)) return $post_id;

    if (isset($_POST['card_image_url'])) {
        error_log('card_image_url: ' . $_POST['card_image_url']);
        update_post_meta($post_id, '_card_image_url', sanitize_text_field($_POST['card_image_url']));
    }

    return $post_id;
}
add_action('save_post', 'save_card_image_metabox');

#endregion
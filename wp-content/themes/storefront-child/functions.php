<?php

add_action('woocommerce_product_options_general_product_data', 'woo_add_custom_fields');
function woo_add_custom_fields($post_id) {
    echo '<div class="options_group">';
    woocommerce_wp_select([
        'id'      => '_select',
        'label'   => 'Тип продукта',
        'options' => [
            'one'   => __('rare', 'woocommerce'),
            'two'   => __('frequent', 'woocommerce'),
            'three' => __('unusual', 'woocommerce'),
        ],
    ]);

    woocommerce_wp_text_input([
        'id'                => '_number_field',
        'label'             => __('Дата создания продукта', 'woocommerce'),
        'placeholder'       => 'Выберите дату создания продукта',
        'type'              => 'datetime-local',
    ]);

    image_uploader_field([
        'name' => 'uploader_custom',
        'value' => get_post_meta($post_id, 'uploader_custom', true),
    ]);

    echo '</div>';
}

function woo_custom_fields_save($post_id)
{
    $woocommerce_select = $_POST['_select'];
    if (!empty($woocommerce_select)) {
        update_post_meta($post_id, '_select', esc_attr($woocommerce_select));
    }

    $woocommerce_input = $_POST['_number_field'];
    if (!empty($woocommerce_input)) {
        update_post_meta($post_id, '_number_field', esc_attr($woocommerce_input));
    }

    add_action('save_post', 'update_create_product_date');
    function update_create_product_date($post_id){
        if (!wp_is_post_revision($post_id)){
            $my_args = [
                'ID' => $post_id,
                'post_date' => get_post_meta($post_id, '_number_field', true),
            ];
            remove_action('save_post', 'update_create_product_date');
            wp_update_post($my_args);
            update_post_meta($post_id, 'uploader_custom', absint($_POST[ 'uploader_custom' ]));
            add_action('save_post', 'update_create_product_date');
        }
    }
}
add_action('woocommerce_process_product_meta', 'woo_custom_fields_save', 10);

add_action('admin_enqueue_scripts', 'include_upload_script');
function include_upload_script() {
    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('myuploadscript', get_stylesheet_directory_uri() . '/admin.js', ['jquery'], null, false);
}

function image_uploader_field($args) {
    $value = get_post_meta(get_the_ID(), 'uploader_custom', true);
    $default = get_stylesheet_directory_uri() . '/placeholder.png';

    if($value && ($image_attributes = wp_get_attachment_image_src($value, [150, 110]))) {
        $src = $image_attributes[0];
    }
    else {
        $src = $default;
    }
    echo '
	<div>
		<img data-src="' . $default . '" src="' . $src . '" width="150" />
		<div>
			<input type="hidden" name="' . $args['name'] . '" id="' . $args['name'] . '" value="' . $value . '" />
			<button type="submit" class="upload_image_button button">Загрузить</button>
			<button type="submit" class="remove_image_button button">×</button>
		</div>
	</div>
	';
}


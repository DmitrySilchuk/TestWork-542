<?php

const UNCATEGORIZED = 15;

add_action('woocommerce_product_options_general_product_data', 'woo_add_custom_fields');
function woo_add_custom_fields($post_id) {

    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false
    ]);
    $options = [];

    foreach($terms as $term) {
        $options[$term->term_id] = $term->name;
    }
    ob_start(); ?>
    <div class="options_group">
        <?php
        woocommerce_wp_select([
            'id'      => '_select',
            'class'   => 'js_select_category',
            'label'   => 'Product type',
            'options' => $options
        ]);

        woocommerce_wp_text_input([
            'id'                => '_number_field',
            'label'             => __('Product create date', 'woocommerce'),
            'class'             => 'js_created_date',
            'placeholder'       => 'Change product create date',
            'type'              => 'datetime-local',
        ]);

        ?>
        <div class="js_upload_image">
            <?php
            image_uploader_field([
                'name' => 'uploader_custom',
                'value' => get_post_meta($post_id, 'uploader_custom', true),
            ]);
            ?>
        </div>
        <div>
            <div>
                <button class="js_clean_field button">Clean field</button>
            </div>
        </div>
        <div>
            <div>
                <button class="js_submit_field button">Update</button>
            </div>
        </div>
	</div>
    <?php
    echo ob_get_clean();
}

add_action('woocommerce_process_product_meta', 'woo_custom_fields_save', 10);
function woo_custom_fields_save($post_id)
{
    $woocommerce_select = !empty($_POST['_select']) ? $_POST['_select'] : UNCATEGORIZED;
    if (!empty($woocommerce_select)) {
        update_post_meta($post_id, '_select', esc_attr($woocommerce_select));
    }

    $woocommerce_input = !empty($_POST['_number_field']) ? $_POST['_number_field'] : date('Y-m-d H:i:s');
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

            $terms = get_the_terms($post_id, 'product_cat');
            $termsIds = [];
            foreach ($terms as $term) {
                $termsIds[] = $term->term_id;
            }
            $postCategory = !empty($_POST['_select']) ? $_POST['_select'] : UNCATEGORIZED;
            $categories = array_diff($termsIds, [$postCategory]);

            wp_remove_object_terms($post_id, $categories, 'product_cat' );
            wp_set_post_terms($post_id, $postCategory, 'product_cat', true);
            add_action('save_post', 'update_create_product_date');
        }
    }
}

add_action('admin_enqueue_scripts', 'include_upload_script');
function include_upload_script() {
    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('upload_script', get_stylesheet_directory_uri() . '/admin.js', ['jquery'], null, false);
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
    ob_start(); ?>
	<div>
		<img data-src="<?= $default ?>" src="<?= $src ?>" width="150" />
		<div>
			<input type="hidden" name="<?= $args['name'] ?>" id="<?= $args['name'] ?>" value="<?= $value ?>" />
			<button type="submit" class="upload_image_button button">Upload</button>
			<button type="submit" class="remove_image_button button">×</button>
		</div>
	</div>
    <?php
}

add_filter('woocommerce_product_get_image', function () {
    $value = get_post_meta(get_the_ID(), 'uploader_custom', true);
    $default = get_stylesheet_directory_uri() . '/placeholder.png';

    if($value && ($image_attributes = wp_get_attachment_image_src($value, [150, 110]))) {
        $src = $image_attributes[0];
    }
    else {
        $src = $default;
    }
    return "<img src='$src'>";
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('child_style', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), 2.0);
    wp_enqueue_script('child_script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery'), 2.0, true);
    wp_localize_script('jquery', 'child_localize', [
        'url' => admin_url('admin-ajax.php')
    ]);
});

add_action('wp_ajax_add_product', 'add_product');
add_action('wp_ajax_nopriv_add_product', 'add_product');
function add_product () {
    $product_name = trim(htmlspecialchars($_POST['child-product-name']));
    $product_price = htmlspecialchars($_POST['child-product-price']);
    $product_datetime = htmlspecialchars($_POST['child-product-datetime']);
    $product_category_id = htmlspecialchars($_POST['child-product-select']);
    $product_slug = str_replace(' ', '_', strtolower($product_name));

    $product_image = wp_handle_upload($_FILES['file'], ['test_form' => false]);

    cut_image($product_image['url']);

    $product = new WC_Product_Simple();
    $product->set_name( $product_name );
    $product->set_slug( $product_slug );
    $product->set_regular_price( $product_price );
    $product->set_date_created( $product_datetime );
    $product->set_category_ids([ $product_category_id ]);
    $product->save();

    $product_image = attachment_url_to_postid($product_image['url']);

    update_post_meta($product->get_id(), '_select', intval($product_category_id));
    update_post_meta($product->get_id(), '_number_field', esc_attr($product_datetime));
    update_post_meta($product->get_id(), 'uploader_custom', $product_image);

    wp_die();
}

function cut_image($image_url) {
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents( $image_url );
    $filename = basename( $image_url );
    if ( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    }
    else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }
    file_put_contents( $file, $image_data );
    $wp_fileType = wp_check_filetype( $filename, null );
    $attachment = array(
        'post_mime_type' => $wp_fileType['type'],
        'post_title' => sanitize_file_name( $filename ),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
}
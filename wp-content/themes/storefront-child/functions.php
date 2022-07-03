<?php


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


    $product = new WC_Product_Simple();
    $product->set_name( $product_name );
    $product->set_slug( $product_slug );
    $product->set_regular_price( $product_price );
    $product->set_date_created( $product_datetime );
    $product->set_category_ids([ $product_category_id ]);
    $product->save();

    echo get_permalink( $product->get_id() );
    wp_die();
}

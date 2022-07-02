
<?php
get_header();
/*
Template Name: Create
*/


add_action('wp_ajax_add_product', 'add_product');
add_action('wp_ajax_nopriv_add_product', 'add_product');
function add_product () {
    $product = new WC_Product_Simple();

    $product->set_name( 'Wizard Hat' ); // product title

    $product->set_slug( 'medium-size-wizard-hat-in-new-york' );

    $product->set_regular_price( 500.00 ); // in current shop currency

    $product->set_short_description( '<p>Here it is... A WIZARD HAT!</p><p>Only here and now.</p>' );
// you can also add a full product description
// $product->set_description( 'long description here...' );

    $product->set_image_id( 90 );

// let's suppose that our 'Accessories' category has ID = 19
    $product->set_category_ids( array( 19 ) );
// you can also use $product->set_tag_ids() for tags, brands etc

    $product->save();
    wp_die();
}
?>


<button></button>
<?php
get_footer();
?>
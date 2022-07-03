
<?php
get_header();
/*
Template Name: Create
*/

?>
<header class="woocommerce-products-header">
    <h1 class="woocommerce-products-header__title page-title">
        <?php the_title(); ?>
    </h1>

</header>
<div class="content-area form-create">
    <div id="child-product-form">
        <div class="child-product-message"></div>
        <?php
        woocommerce_form_field('child-product-name', [
            'type' => 'text',
            'placeholder' => 'Enter product name',
            'label' => 'Product name',
            'required' => true
        ]);
        woocommerce_form_field('child-product-price', [
            'type' => 'number',
            'placeholder' => 'Enter price',
            'label' => 'Price',
            'required' => true,
            'class' => ['woocommerce-form-row', 'woocommerce-form-row--first', 'form-row', 'form-row-first']
        ]);
        woocommerce_form_field('child-product-datetime', [
            'type' => 'datetime-local',
            'placeholder' => 'Enter date and time of publish',
            'label' => 'Date and time',
            'required' => true,
            'class' => ['woocommerce-form-row', 'woocommerce-form-row--last', 'form-row', 'form-row-last']
        ]);

        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false
        ]);
        $options = [];

        foreach($terms as $term) {
            $options[$term->term_id] = $term->name;
        }

        woocommerce_form_field('child-product-select', [
            'type' => 'select',
            'placeholder' => 'Enter date and time of publish',
            'label' => 'Select category',
            'options' => $options
        ]);  ?>
        <div class="clear"></div>
        <div class="child-submit-wrap">
            <button type="submit" class="woocommerce-Button button" id="child-product-submit">
                Create product
            </button>
            <div class="child-loader child-loader--disable">
                <div class="child-loader__item"></div>
                <div class="child-loader__item"></div>
                <div class="child-loader__item"></div>
            </div>
        </div>

    </div>
    <div>

    </div>

</div>

<?php
get_footer();
?>
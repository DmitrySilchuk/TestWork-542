jQuery(function($){
    $('.upload_image_button').click(function( event ){
        event.preventDefault();
        const button = $(this);
        const customUploader = wp.media({
            title: 'Выберите изображение',
            library : {
                type : 'image'
            },
            button: {
                text: 'Выбрать изображение'
            },
            multiple: false
        });

        customUploader.on('select', function() {
            const image = customUploader.state().get('selection').first().toJSON();
            button.parent().prev().attr( 'src', image.url );
            button.prev().val( image.id );
        });

        customUploader.open();
    });

    $('.remove_image_button').click(function(event) {
        event.preventDefault();
        const src = $(this).parent().prev().data('src');
        $(this).parent().prev().attr('src', src);
        $(this).prev().prev().val('');
    });

    $('.js_clean_field').click(function(event) {
        event.preventDefault();
        $('.js_select_category').val('');
        $('.js_created_date').val('');
        $('.remove_image_button').click();
    });

    $('.js_submit_field').click(function(event) {
        event.preventDefault();
        $('#publish').click();
    });
});
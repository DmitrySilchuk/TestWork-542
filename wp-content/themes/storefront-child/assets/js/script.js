($ => {
    let btn_submit = $('#child-product-submit');

    let notification_message = $('.child-product-message');
    btn_submit.on('click',() => {

        let input_name = $('#child-product-name').val(),
            input_price = $('#child-product-price').val(),
            input_datetime = $('#child-product-datetime').val(),
            input_select = $('#child-product-select').val(),
            input_image = $('#child-product-image').prop('files')[0];

        if(input_name
            && input_price
            && input_datetime
        ) {
            let form_data = new FormData();
            form_data.append('action', 'add_product');
            form_data.append('child-product-name', input_name);
            form_data.append('child-product-price', input_price);
            form_data.append('child-product-datetime', input_datetime);
            form_data.append('child-product-select', input_select);
            form_data.append('file', input_image);

            console.log(Array.from(form_data));

            $.ajax({
                url: child_localize.url,

                type: "POST",
                dataType: "script",

                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                beforeSend: function () {
                    btn_submit.attr('disabled', true);
                    $('.child-loader').removeClass('child-loader--disable');
                },
                success: function () {
                    try {
                        notification_message.removeClass('woocommerce-error');
                        notification_message.addClass('woocommerce-message');
                        notification_message.html('Продукт успешно создан.');

                        notification_message.fadeIn();
                        btn_submit.attr('disabled', false);

                        $('#child-product-name').val('');
                        $('#child-product-price').val('');
                        $('#child-product-datetime').val('');
                        $('#child-product-image').val('');
                        $('#child-product-select').val(child_localize.default_category);

                    } catch (e) {
                        console.log(e);
                    }
                    $('.child-loader').addClass('child-loader--disable');
                }
            });
        }
        else {
            notification_message.removeClass('woocommerce-message');
            notification_message.addClass('woocommerce-error');
            notification_message.html('Не все поля заполнены или не правильно введены');
            notification_message.fadeIn();
        }
    });
})(jQuery);
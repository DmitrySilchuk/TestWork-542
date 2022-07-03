($ => {
    let btn_submit = $('#child-product-submit');

    let notification_message = $('.child-product-message');
    btn_submit.on('click', () => {
        let input_name = $('#child-product-name').val();
        let input_price = $('#child-product-price').val();
        let input_datetime = $('#child-product-datetime').val();
        let input_select = $('#child-product-select').val();
        console.log(input_select);
        if(input_name
            && input_price
            && input_datetime
            && input_select
        ) {
            $.ajax({
                'url': child_localize.url,
                'method': 'post',
                'data': {
                    'action': 'add_product',
                    'child-product-name' : input_name,
                    'child-product-price' : input_price,
                    'child-product-datetime' : input_datetime,
                    'child-product-select' : input_select
                },
                beforeSend: function () {
                    btn_submit.attr('disabled', true);
                    $('.child-loader').removeClass('child-loader--disable');
                },
                success: function (data) {
                    try {
                        notification_message.removeClass('woocommerce-error');
                        notification_message.addClass('woocommerce-message');
                        notification_message.html(
                            `Продукт успешно создан. <a class="showcoupon" href='${data}'>Перейти к продукту</a>`
                        );

                        notification_message.fadeIn();
                        btn_submit.attr('disabled', false);
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
            notification_message.text('Не все поля заполнены или не правильно введены');
            notification_message.fadeIn();
        }
    });
})(jQuery);
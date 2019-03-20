$(document).ready(function () {

    var sum = true;

    var refresh_data = function () {
        $.ajax({
            type: 'POST',
            url: '/currency/get',
            dataType: 'json',
            success: function (data) {
                for (var key in data) {
                    var sell = data[key].sell;
                    var buy = data[key].buy;
                    var sell_selector = $('#' + data[key].id + '-sell');
                    var buy_selector = $('#' + data[key].id + '-buy');
                    if ( parseFloat(sell).toFixed(2) < sell_selector.html())  {
                        sell_selector.addClass('text-danger');
                        sell_selector.removeClass('text-success');
                        sell_selector.html(parseFloat(sell).toFixed(2));
                    } else if ( parseFloat(sell).toFixed(2) > sell_selector.html()){
                        sell_selector.addClass('text-success');
                        sell_selector.removeClass('text-danger');
                        sell_selector.html(parseFloat(sell).toFixed(2));
                    }
                    if ( parseFloat(buy).toFixed(2) < buy_selector.html()) {
                        buy_selector.addClass('text-danger');
                        buy_selector.removeClass('text-success');
                        buy_selector.html(parseFloat(buy).toFixed(2));
                    } else if ( parseFloat(buy).toFixed(2) > buy_selector.html()) {
                        buy_selector.addClass('text-success');
                        buy_selector.removeClass('text-danger');
                        buy_selector.html(parseFloat(buy).toFixed(2));
                    }
                }
            }
        });
        if (sum) {
            refresh_sum();
        } else {
            refresh_amount();
        }
    };

    var timer = setInterval(refresh_data, 2000);

    var refresh_sum = function() {
        var from = $('select#exchangeform-currency_id_from option:selected').val();
        var to = $('select#exchangeform-currency_id_to option:selected').val();
        var amount = $('#exchangeform-amount').val();
        if (from !== to && amount) {
            $.ajax({
                type: 'POST',
                url: '/currency/calculate?from=' + from + '&to=' + to + '&amount=' + amount,
                dataType: 'json',
                success: function(data) {
                    $('#exchangeform-sum').val(data).change();
                }
            });
        }
    };

    var refresh_amount = function() {
        var from = $('select#exchangeform-currency_id_from option:selected').val();
        var to = $('select#exchangeform-currency_id_to option:selected').val();
        var sum = $('#exchangeform-sum').val();
        if (from !== to && sum) {
            $.ajax({
                type: 'POST',
                url: '/currency/calculate?from=' + from + '&to=' + to + '&sum=' + sum,
                dataType: 'json',
                success: function(data) {
                    $('#exchangeform-amount').val(data).change();
                }
            });
        }
    };

    var data_fields = function () {
        $('.confirmation').removeClass('collapse');
        $('#client_wallet, #client_card').hide();
        $('.exchange-button').remove();
        $('.confirmation, #client_email').show('slow');
        if ($('#exchangeform-currency_id_from option:selected').val() !== '0') {
            $('#exchange-form').yiiActiveForm('add', {
                id: '#exchangeform-client_card',
                name: 'client_card',
                container: '.field-exchangeform-client_card',
                input: '#exchangeform-client_card',
                error: '.help-block-error',
                validateOnChange: true,
                validate:  function (attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {message: "Your card number cannot be blank."});
                    yii.validation.string(value, messages, {message: "",
                        min: 19,
                        tooShort: "Your card number must be at least 16 digits."
                    });
                }

            });
            $('#client_wallet, #client_card').show('slow');
        } else {
            $('#exchange-form').yiiActiveForm('remove', '#exchangeform-client_card');
            $('#client_wallet').show('slow');
        }
    };

    $('#revert, #swap').on('click', function (e) {
        e.preventDefault();
        var selected_from = $('#exchangeform-currency_id_from option:selected').val();
        var selected_to = $('#exchangeform-currency_id_to option:selected').val();
        var result = $('#exchangeform-sum').val();
        if ($('#revert, #swap').attr('disabled') !== 'disabled' && selected_from && selected_to) {
            $('#revert, #swap').attr('disabled', true);
            setTimeout(function() {
                $('#revert, #swap').removeAttr('disabled', false);
                },2000);
            if ($('#exchangeform-amount').val()) {
                $('#exchangeform-amount').val(result);
            }
            $('#exchangeform-currency_id_from').val(selected_to).change();
            $('#exchangeform-currency_id_to').val(selected_from).change();
            if ($('.confirmation').hasClass('collapse') === false) {
                data_fields();
            }
            $('#exchange-form').yiiActiveForm('validate');
        }
    });

    $('#refresh-captcha').on('click', function(e){
        e.preventDefault();
        $('#my-captcha-image').yiiCaptcha('refresh');
    });

    $('#exchangeform-currency_id_from').on('change', function () {
        $('#exchangeform-currency_id_from option[value=""]').remove();
        $('select#exchangeform-currency_id_to option:disabled').prop('disabled', false);
        var selected_from = $('#exchangeform-currency_id_from option:selected').val();
        if (selected_from !== '0') {
            $('#exchangeform-currency_id_to').val(0).change();
            for (var i = 1; i < 10; i++) {
                $('#exchangeform-currency_id_to option[value=' + i + ']').prop('disabled', true);
            }
        } else {
            $('#exchangeform-currency_id_to option[value=' + selected_from + ']').prop('disabled', true);
        }
        refresh_sum();
    });

    $('#exchangeform-currency_id_to').on('change', function () {
        $('#exchangeform-currency_id_to option[value=""]').remove();
        $('select#exchangeform-currency_id_from option:disabled').prop('disabled', false);
        var selected_to = $('#exchangeform-currency_id_to option:selected').val();
        if (selected_to !== '0') {
            $('#exchangeform-currency_id_from').val(0).change();
            for (var i = 1; i < 10; i++) {
                $('#exchangeform-currency_id_from option[value=' + i + ']').prop('disabled', true);
            }
        } else {
            $('#exchangeform-currency_id_from option[value=' + selected_to + ']').prop('disabled', true);
        }
    });

    $('#exchangeform-amount').on('focus input', function() {
        refresh_sum();
        sum = true;
    });

    $('#exchangeform-sum').on('input', function() {
        refresh_amount();
        sum = false;
    });

    $('#exchange-button').on('click', function () {
        $("#exchange-form").yiiActiveForm('validateAttribute', 'exchangeform-amount');
        $("#exchange-form").yiiActiveForm('validateAttribute', 'exchangeform-sum');
        $("#exchange-form").yiiActiveForm('validateAttribute', 'exchangeform-currency_id_from');
        $("#exchange-form").yiiActiveForm('validateAttribute', 'exchangeform-currency_id_to');
        console.log($('#exchange-form').find('.field-exchangeform-amount, .field-exchangeform-sum .has-error').length === 0 );
        if ($('#exchangeform-currency_id_from option:selected').val() && $('#exchangeform-currency_id_to option:selected').val() && $('#exchangeform-amount').val() && $('#exchangeform-sum').val()) {
            if ($('#exchange-form').find('.field-exchangeform-sum.has-error, .field-exchangeform-amount.has-error').length === 0 ) {
                data_fields();
            }
        }
    });

    if ($('#exchangeform-currency_id_from option:selected').val() && $('#exchangeform-currency_id_to option:selected').val()) {
        $('#exchangeform-currency_id_from').change();
        $('#exchangeform-currency_id_to').change();
    }
});

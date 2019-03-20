$(document).ready(function () {

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
    };

    var timer = setInterval(refresh_data, 3000);
});
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

function confirmOrder()
{
    var key = $('#key').val();
    $.ajax({
        type: "POST",
        url: "/order/confirm-order",
        data: {key: key},
        success: function(){
        }
    });
}
$('#revert').attr('disabled', true);
//$('#currencies').fadeOut(1000);

$('#confirm-button').on('click', function () {
    confirmOrder();
    $(this).html('Confirmed');
    $(this).fadeOut('slow');
    $('.confirmed').fadeIn('slow');
    $('#timer').fadeOut('slow');
});

$('#clock').countdown(new Date().getTime() + 1000 * 60 * parseInt($('#valid_time').val()), function(event) {
    $(this).html(event.strftime('%M:%S'));
});

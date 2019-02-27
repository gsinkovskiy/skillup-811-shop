$(function() {
    $('form').on('input', '.js-recalc-cost', function(event) {
        var row = $(this).closest('tr');
        var price = row.find('.js-price').val();
        var quantity = row.find('.js-quantity').val();

        row.find('.js-cost').val(Math.round(price * quantity * 100) / 100);
    });
});

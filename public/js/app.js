var addToCartButtons, cartInHeader, cartItems;

addToCartButtons = $('.js-add-to-cart');
cartInHeader = $('#cart-in-header');
cartItems = $('#cartItems');

addToCartButtons.on('click', function(event) {
    event.preventDefault();
    mask();

    $.get(event.currentTarget.href, function (data) {
        cartInHeader.html(data);
        unmask();
    });
});

$('body').on('click', '.js-remove-item', function(event) {
    event.preventDefault();

    if (confirm('Действительно удалить?')) {
        mask();

        $.get(event.currentTarget.href, function (data) {
            cartItems.html(data);
            unmask();
        });
    }
});

$('body').on('input', '.js-item-quantity', function(event) {
    var target = $(event.currentTarget);

    $.post(target.data('href'), {'quantity': target.val()}, function (data) {
        cartItems.html(data);
    })
});

function mask() {
    $('body').append('<div class="lmask"></div>');
}

function unmask() {
    $('.lmask').remove();
}

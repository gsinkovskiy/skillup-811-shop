var addToCartButtons, cartInHeader;

addToCartButtons = $('.js-add-to-cart');
cartInHeader = $('#cart-in-header');

addToCartButtons.on('click', function(event) {
    event.preventDefault();
    mask();

    $.get(event.target.href, function (data) {
        cartInHeader.html(data);
        unmask();
    });
});

function mask() {
    $('body').append('<div class="lmask"></div>');
}

function unmask() {
    $('.lmask').remove();
}

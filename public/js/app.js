var addToCartButtons, cartInHeader;

addToCartButtons = $('.js-add-to-cart');
cartInHeader = $('#cart-in-header');

addToCartButtons.on('click', function(event) {
    event.preventDefault();

    console.log('Кнопка нажата.');

    $.get(event.target.href, function (data) {
        cartInHeader.html(data);
    });
});


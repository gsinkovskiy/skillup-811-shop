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
            cartInHeader.load(cartInHeader.data('refresh-url'));
            unmask();
        });
    }
});

$('body').on('input', '.js-item-quantity', function(event) {
    var target = $(event.currentTarget);

    $.post(target.data('href'), {'quantity': target.val()}, function (data) {
        cartItems.html(data);
        cartInHeader.load(cartInHeader.data('refresh-url'));
    })
});

function mask() {
    $('body').append('<div class="lmask"></div>');
}

function unmask() {
    $('.lmask').remove();
}

var headerSearchForm = $('#header-search-form');
var headerSearchResults = $('#header-search-results');

headerSearchForm.find('input')
    .on('input', function() {
        var query = this.value;

        if (query.length >= 2) {
            headerSearchForm.submit();
        } else {
            headerSearchResults.html('');
        }
    })
    .on('blur', function() {
        setTimeout(function() {
            headerSearchResults.html('');
        }, 200);
    });

headerSearchForm.on('submit', function(event) {
    event.preventDefault();

    $.get(headerSearchForm.attr('action'), headerSearchForm.serialize(), function(data) {
        headerSearchResults.html(data);
    })
});

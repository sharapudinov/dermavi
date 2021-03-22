$(document).ready(function () {
    $(document).on('submit', '.js-diamond-orderitem-form', function (e) {
        let data = $(this).serialize();

        e.preventDefault();

        $.post($(this).attr('action'), data)
            .done(function (response) {});
    });

    $(document).on('click', '.js-add-service', function (e) {
        e.preventDefault();

        const $link = $(this);
        let data = {text: $link.data('text'), logo: $link.data('logo')};

        $.post($link.attr('href'), data, function (response) {
            console.log(response);
        });
    });
});
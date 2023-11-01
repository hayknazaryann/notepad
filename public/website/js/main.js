$(document)
    .ready(function () {
    })
    .on('click', '#load-more', function (e) {
        e.preventDefault();

        var elm = $(this),
            url = elm.attr('href'),
            model = elm.attr('data-model'),
            id = elm.attr('data-id'),
            method = elm.attr('data-method'),
            item = elm.attr('data-item'),
            content = elm.attr('data-content'),
            limit = elm.attr('data-limit'),
            offset = $('.' + item).length,
            data = {
                model: model,
                id: id,
                method: method,
                view: item + 's',
                offset: offset,
                limit: limit
            };


        $.ajax({
            url: url,
            datatype: "html",
            type: "post",
            data: data,
            beforeSend: function () {
            }
        })
            .done(function (response) {
                $('.' + content).append(response);
                elm.parent().remove();
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    })

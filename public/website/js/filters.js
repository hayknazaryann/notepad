$(document)
    .on('change', '.filters select', function () {
        loadItems();
    })
    .on('input', '.filters input', debounce(function (e) {
        loadItems();
    }, 500))
    .on('click', '.pagination-arrow', function (e) {
        var elm = $(this), page = elm.attr('data-page'),
            pageInput = $('input#page'), currentPage = pageInput.val();

        if (page === 'next') {
            currentPage = parseInt(currentPage) + 1;
        } else if (page === 'prev') {
            currentPage = parseInt(currentPage) - 1;
        }

        currentPage = currentPage < 1 ? 1 : currentPage;
        pageInput.val(currentPage);

        loadItems();
    });


function fetchSearchData(form) {
    let queryParameters = form.serializeArray().filter( item => item.value),
        params = convertObjectToURI(queryParameters);
    window.history.pushState(null, null, `?${params}`);
    return queryParameters;
}

function convertObjectToURI(serializedArray) {
    return Object.entries(serializedArray).map(([key, val]) => {
        if (val.name !== '_token') {
            return `${val.name}=${val.value}`;
        }
    }).join('&');
}

function loadItems() {
    var form = $('#filters-form'),
        url = form.attr('action'),
        data = fetchSearchData(form);

    $.ajax({
        url: url,
        method: 'get',
        data: data,
        dataType: 'json',
        beforeSend: function () {
            $('.note-title, .note-group, .grid-body').addClass('loading');
        }
    }).done(function (response) {
        if (response.success === true) {
            $('.note-title, .note-group, .grid-body').addClass('remove');
            $(`#grid-items`).html(response.view);
        }
    });
}

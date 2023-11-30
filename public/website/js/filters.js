$(document)
    .on('change', '.filters select', function () {
        setPage();
        loadItems();
    })
    .on('input', '.filters input', debounce(function (e) {
        loadItems();
    }, 500))
    .on('click', '#next', function () {
        var pageInput = $('input#page'),
            currentPage = pageInput.val(),
            newPage = parseInt(currentPage) + 1;
        pageInput.val(newPage)
        loadItems();
    })
    .on('click', '#prev', function () {
        var pageInput = $('input#page'),
            currentPage = pageInput.val(),
            newPage = parseInt(currentPage) - 1;
        pageInput.val(newPage)
        loadItems();
    });


function fetchSearchData(form) {
    let queryParameters = form.serializeArray().filter( item => item.value);
    const params = convertObjectToURI(queryParameters);
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

function setPage(page = 1) {
    $('#page').val(page);
}

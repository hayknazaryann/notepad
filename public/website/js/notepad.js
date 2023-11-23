window.jsPDF = window.jspdf.jsPDF;
$(document).ready(function () {
    $('select#group').select2({
        placeholder: 'Search by group',
        allowClear: true
    });
    $('select#pageSize').select2({
        placeholder: 'Limit',
    });
    const gridItems = document.getElementById('grid-items');
    new Sortable(gridItems, {
        draggable: ".grid-item",
        animation: 350,
        swap: true, // Enable swap plugin
        swapClass: 'highlight',
        chosenClass: "sortable-chosen",
        dragClass: "sortable-drag",
        onEnd: function (evt) {
            var oldIndex = evt.oldIndex,
                newIndex = evt.newIndex,
                currentItem = evt.item,
                swapItem = evt.swapItem;

            if (oldIndex !== newIndex) {
                $.ajax({
                    url: gridItems.getAttribute('data-url'),
                    method: 'post',
                    dataType: 'json',
                    data: {
                        key: currentItem.getAttribute('data-key'),
                        swapKey: swapItem.getAttribute('data-key')
                    }
                }).done(function (response) {

                }).fail(function (error) {
                    failResponse(error)
                })
            }
        }
    });
})
    .on('click', '#new-note', function (e) {
        e.preventDefault();
        const elm = $(this), url = elm.attr('href');
        loadView(url, 100);
    })
    .on('click', '#import', function () {
        document.getElementById('import-file').click();
    })
    .on('change', 'input#import-file', function (e) {
        const elm = $(this),
            form = elm.closest('form');

        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serializeWithFiles(),
            contentType: false,
            processData: false,
            dataType: 'json',
        }).done(function (response) {
            $('textarea#note').val(response.data.content)
        }).fail(function (error) {
            failResponse(error);
        })
    })
    .on('click', '#save', function (e) {
        e.preventDefault();
        storeNote(null, true);
    })
    .on('click', '.save-and-download', function (e) {
        const extension = $(this).attr('data-extension');
        storeNote(extension, true);
    })
    .on('click', '.view-note', function (e) {
        e.preventDefault();
        const elm = $(this), url = elm.attr('href');
        loadView(url, 50);
    })
    .on('click', '.edit-note', function (e) {
        e.preventDefault();
        const elm = $(this), url = elm.attr('href');
        loadView(url, 100);
    })
    .on('click', '.delete-item', function (e) {
        e.preventDefault();
        const elm = $(this)
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteNote(elm.attr('href'), elm.closest('.grid-item'))
            }
        })
    })
    .on('click', '.download-note', function (e) {
        e.preventDefault();
        const elm = $(this), url = elm.attr('href');

        $.ajax({
            url: url,
            method: 'post',
            dataType: 'json',
        }).done(function (response) {
            if (response.success === true) {
                download(response.data.title, response.data.extension, response.data.text);
            }
        }).fail(function (error) {
            failResponse(error)
        })
    });


function loadView(url, sheetHeight) {
    $.ajax({
        url: url,
        method: 'get',
        contentType: false,
        processData: false,
        dataType: 'json',
    }).done(function (response) {
        if (response.success === true) {
            $('#sheet main.body').html(response.data.view);
            $('#open-sheet').trigger('click');
            setSheetHeight(sheetHeight);
            $('select#group-tag').select2({
                placeholder: 'Write or choose group',
                tags: true,
            });
        }
    }).fail(function (error) {

    })
}

function storeNote(extension = null, newItem = false) {
    var noteForm = $('#note-form'),
        data = noteForm.serializeWithFiles(),
        url = noteForm.attr('action');

    if (extension !== null) {
        data.set('extension', extension);
    }

    $.ajax({
        url: url,
        method: 'post',
        data: data,
        contentType: false,
        processData: false,
        dataType: 'json',
    }).done(function (response) {
        if (response.success === true) {
            if (extension) {
                download(response.data.title, response.data.extension, response.data.text);
            }

            loadItems();
            $('.close-sheet').trigger('click');
            responseMsg('Success!', response.msg, 'success');
        }
    }).fail(function (error) {
        failResponse(error, noteForm)
    })
}

function deleteNote(url, row) {
    $.ajax({
        url: url,
        method: 'post',
        data: {
            _method:'DELETE'
        },
        dataType: 'json',
    }).done(function (response) {
        if (response.success === true) {
            loadItems();
            responseMsg('Deleted!', 'Your note has been deleted.', 'success');
        }
    }).fail(function (error) {

    })
}

function download(filename, extension, text) {
    var element = document.createElement('a');

    if (extension === 'pdf') {
        var doc = new jsPDF({
            orientation: "p"
        });
        var splitTitle = doc.splitTextToSize(text, 180);
        doc.text(15, 20, splitTitle);
        doc.save(filename + '.' + extension);
    } else if (extension === 'docx' || extension === 'doc') {
        var fName = filename + '.' + extension;
        generateDocx(text, fName)
    } else if (extension === 'txt') {
        const dataType = 'data:text/plain;charset=utf-8,'
        element.setAttribute('href', dataType + encodeURIComponent(text));
        element.setAttribute('download', filename + '.' + extension);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    } else {
        failResponse({status: 400, msg: 'Wrong extension !'})
    }
}

function generateDocx(content, filename) {
    const doc = new docx.Document({
        sections: [
            {
                properties: {},
                children: [
                    new docx.Paragraph({
                        children: [
                            new docx.TextRun(content),
                        ]
                    })
                ]
            }
        ]
    });

    docx.Packer.toBlob(doc).then((blob) => {
        saveAs(blob, filename);
    });
}

function responseMsg(title, msg, type) {
    Swal.fire(
        title,
        msg,
        type
    );
}

function failResponse(error, form = null) {
    const statusCode = error.status;
    if (statusCode === 400) {
        responseMsg('Error!', error.msg, 'success');
    } else if (statusCode === 422) {
        const errors = error.responseJSON;
        form.find('input[type="text"],textarea').removeClass('is-invalid');
        if (form) {
            form.find('input[type="text"],textarea').each(function (index, input) {
                $(input).removeClass('is-invalid');
                var inputName = $(input).attr('name');
                if (inputName in errors.errors) {
                    $(input).addClass('is-invalid');
                }
            });
        } else {
            responseMsg('Error !', errors.message, 'error')
        }
    }
}

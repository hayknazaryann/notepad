var noteForm = $('#note-form'), noteInput = $('textarea#note'), createUrl;
$(document).ready(function () {

})
    .on('click', '#new-note', function () {
        $('textarea#note').val('');
        noteForm.attr('data-type', 'create')
            .attr('action', 'notepad/store');
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
        storeNote();
    })
    .on('click', '.save-and-download', function (e) {
        const extension = $(this).attr('data-extension');
        storeNote(extension);
    })
    .on('click', '.view-note', function (e) {
        e.preventDefault();
        const elm = $(this), url = elm.attr('href');
        createUrl = noteForm.attr('action');
        $.ajax({
            url: url,
            method: 'get',
            contentType: false,
            processData: false,
            dataType: 'json',
        }).done(function (response) {
            if (response.success === true) {
                noteInput.val(response.data.note.text);
                noteForm.attr('action',response.data.url).attr('data-type', 'update');
            }
        }).fail(function (error) {

        })
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
                deleteNote(elm.attr('href'), elm.closest('.note-item'))
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


function storeNote(extension = null) {
    var data = noteForm.serializeWithFiles(),
        url = noteForm.attr('action');

    if (noteForm.attr('data-type') === 'update') {
        data.set('_method', 'PUT');
    }

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
            if (noteForm.attr('data-type') === 'create') {
                noteInput.val('');
                $('.note-items').prepend(response.data.note);
                $('input#note-title').val(response.data.title);
            }

            if (extension) {
                download(response.data.title, response.data.extension, response.data.text);
            }
            responseMsg('Success!', response.msg, 'success');
        }
    }).fail(function (error) {
        failResponse(error)
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
            row.remove();
            responseMsg('Deleted!', 'Your note has been deleted.', 'success');
            noteForm.attr('action', response.data.url).attr('data-type', 'create');
            noteInput.val('');
        }
    }).fail(function (error) {

    })
}

function downloadNote(url) {
    $.ajax({
        url: url,
        method: 'post',
        dataType: 'json',
    }).done(function (response) {
        if (response.success === true) {

        }
    }).fail(function (error) {

    })
}

function download(filename, extension, text) {
    var element = document.createElement('a');

    if (extension === 'pdf') {
        var doc = new jsPDF();
        doc.text(20, 20, text);
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

function failResponse(error) {
    const statusCode = error.status;

    if (statusCode === 400) {
        responseMsg('Error!', error.msg, 'success');
    } else if (statusCode === 422) {
        const errors = error.responseJSON;
        responseMsg('Error !', errors.message, 'error')
    }
}

window.jsPDF = window.jspdf.jsPDF;
$(document).ready(function () {
    initSelect();
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
        loadView(url, 90);
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
        loadView(url, 90);
    })
    .on('click', '.edit-note', function (e) {
        e.preventDefault();
        const elm = $(this), url = elm.attr('href');
        loadView(url, 90);
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
    })
    .on('click', '#unlock-note', function (e) {
        e.preventDefault();
        unlock();
    })
    .on('click', '.give-access', function (e) {
        e.preventDefault();
        const elm = $(this), url = elm.attr('href');
        loadView(url, 90);
    })
    .on('click', '#add-user', function () {
        var accessForm = $('#access-form'),
            data = accessForm.serializeWithFiles(),
            url = accessForm.attr('action');

        $.ajax({
            url: url,
            method: 'post',
            data: data,
            contentType: false,
            processData: false,
            dataType: 'json',
        }).done(function (response) {
            if (response.success === true) {
                noteUsers(response.url);
                responseMsg('Success!', response.msg, 'success');
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
            initSelect();
        }
    }).fail(function (error) {

    });
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

function unlock() {
    var notePasswordForm = $('#note-password-form'),
        data = notePasswordForm.serializeWithFiles(),
        url = notePasswordForm.attr('action');

    $.ajax({
        url: url,
        method: 'post',
        data: data,
        contentType: false,
        processData: false,
        dataType: 'json',
    }).done(function (response) {
        $('#sheet main.body').html(response.data.view);
        initSelect();
    }).fail(function (error) {
        failResponse(error, notePasswordForm)
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
            responseMsg('Deleted!', 'Your note has been deleted.', 'success');
            loadItems();
        }
    }).fail(function (error) {

    })
}

function noteUsers(url) {
    $.ajax({
        url: url,
        method: 'get',
        contentType: false,
        processData: false,
        dataType: 'json',
    }).done(function (response) {
        if (response.success === true) {
            $('.access-list').html(response.data.view)
        }
    }).fail(function (error) {

    });
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

function autocompleteSelect(elm, tag = false) {
    const autocompleteInput = elm;
    const autocompleteUrl = autocompleteInput.data('url');
    if(autocompleteInput.attr('data-value')) {
        autocompleteInput.html(`<option value="${autocompleteInput.attr('data-value')}">${autocompleteInput.attr('data-value')}</option>`)
    }
    autocompleteInput.select2({
        placeholder: elm.attr('aria-label'),
        dropdownParent: '.card',
        allowClear: true,
        tag: tag,
        ajax: {
            url: autocompleteUrl,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                let page = params.current_page ? (params.current_page + 1) : 1;
                return {
                    q: params.term, // search term
                    page: page
                };
            },
            processResults: function (data, params) {
                params.current_page = data.current_page;
                return {
                    results:  $.map(data.data, function (item) {
                        return {
                            text: item.ip,
                            id: item.ip
                        }
                    }),
                    pagination: {
                        more: (params.current_page * 30) < data.total
                    }
                };
            },
            cache: true
        }
    });
}

function initSelect () {
    $('select.select2').each(function () {
        const elm = $(this),
              placeholder = elm.attr('aria-label'),
              tags = elm.hasClass('select2-tag');

        elm.select2({
            placeholder: placeholder,
            tags: tags,
            allowClear: true
        });
    })
}

function initEditor(selector) {
    tinymce.init({
        selector: selector,
        promotion: false,
        plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        mobile: {
            plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap quickbars emoticons'
        },
        menu: {
            tc: {
                title: 'Comments',
                items: 'addcomment showcomments deleteallconversations'
            }
        },
        menubar: 'file edit view insert format tools table tc help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
        autosave_ask_before_unload: true,
        templates: [
            { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
            { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
            { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
        ],
        skin: (window.matchMedia("(prefers-color-scheme: dark)").matches ? "oxide-dark" : ""),
        content_css: (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : ""),
        height: 480,
    });
}

function responseMsg(title, msg, type) {
    var toastMixin = Swal.mixin({
        toast: true,
        icon: type,
        title: title,
        animation: false,
        position: 'top-right',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    toastMixin.fire({
        animation: true,
        title: msg
    });

}

function failResponse(error, form = null) {
    const statusCode = error.status;
    if (statusCode === 400) {
        responseMsg('Error!', error.msg, 'error');
    } else if (statusCode === 404) {
        responseMsg('Error!', error.msg, 'error');
    } else if (statusCode === 422) {
        const errors = error.responseJSON;
        if (form) {
            form.find('input[type="text"], input[type="password"], textarea').each(function (index, input) {
                $(input).removeClass('is-invalid');
                var inputName = $(input).attr('name');
                if (inputName in errors.errors) {
                    $(input).addClass('is-invalid');
                }
            });
        }
        responseMsg('Error !', errors.message, 'error')
    }
}

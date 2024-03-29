function createAnnouncement() {
    const url = window.location.origin + '/admin/announcements/create'

    $.ajax({
        type: 'GET',
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            const types = response.types;
            $('.custom-file-label').html(''); //empty the html in the file input
            $('#announcementForm').trigger("reset") //reset all the input values
            $('#announcementTypeDropDwn').empty() //reset dropdown button
            $(".selectedText").empty(); //reset div Selected file
            $("#formMethod").empty(); //empty form method div
            $('#announcementTypeDropDwn').append($("<option selected/>").val("").text('Choose...'))
            $.each(types, function () {
                // Populate Drop Dowm
                $('#announcementTypeDropDwn').append($("<option />").val(this.id).text(this.name))
            })
            let actionURL = window.location.origin + '/admin/announcements';
            let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#announcementForm').attr('action', actionURL) //set the method of the form
            $('#announcementModal').modal('show')
            $('#announcementModalHeader').text('Publish Announcement')
            $('.btnTxt').text('Store') //set the text of the submit btn
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function deleteAnnouncement(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/announcements/' + id)
    $('#confirmationMessage').text('Do you really want to delete this announcement? This process cannot be undone.')
}

$(document).ready(function () {

    $('#announcement').addClass('active')

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });

    //multiple files to show
    $('.custom-file input').change(function (e) {
        var files = [];
        for (var i = 0; i < $(this)[0].files.length; i++) {
            files.push($(this)[0].files[i].name);
        }

        $(this).next('.custom-file-label').html(files.join(', '));
        $(".selectedText").empty();
        var head = '<p>Selected Pictures: </p>';
        var files = '<span>' + files.join(', ') + '</span>'
        $(".selectedText").append(head, files);
    });

    // Validation
    $("form[name='announcementForm']").validate({
        // Specify validation rules
        rules: {
            type_id: {
                required: true,
                minStrict: 0,
                number: true
            },
            title: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
            description: {
                required: true,
                minlength: 10,
                maxlength: 63206,
            },
            'picture_list[]': {
                // required: function () {
                //     return $('#method').val() == 'POST'
                // },
                extension: "jpeg|jpg|png",
                filesize: 10485760, //10mb in bytes
            },
        },
        messages: {
            type_id: {
                required: 'Please select one of the announcement types',
                digits: 'Invalid Input',
                minStrict: 'Please select one of the announcement types',
            },
            'picture_list[]': {
                extension: "Invalid file type! Document must be in jpeg|jpg|png format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#announcementForm").attr('action')
            let formMethod = $('#method').val()
            let formData = new FormData(form)


            $.ajax({
                type: 'POST',
                url: formAction,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#announcementModal').modal('hide') //hide the modal

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.title + '</td>'
                    col2 = '<td><a href="' + window.location.origin + '/admin/announcement-types/' + data.type_id + '">' + data.announcement_type + '</a></td>'
                    col3 = '<td>' + data.announcement_pictures_count + '</td>'
                    col4 = '<td>' + data.likes_count + '</td>'
                    col5 = '<td>' + data.comments_count + '</td>'
                    col6 = '<td>' + data.updated_at + '</td>'

                    viewBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + window.location.origin + '/admin/announcements/' + data.id + '"><i class="fas fa-eye"></i>' +
                        '</a></li>'

                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteAnnouncement(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>'

                    col7 = '<td><ul class="list-inline m-0">' + viewBtn + deleteBtn + '</td></ul>'
                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (formMethod == 'POST') {
                        var currentPage = table.page();
                        table.row.add([col0, col1, col2, col3, col4, col5, col6, col7]).draw()

                        selectedRow = 0
                        var index = table.row(selectedRow).index(),
                            rowCount = table.data().length - 1,
                            insertedRow = table.row(rowCount).data(),
                            tempRow

                        for (var i = rowCount; i > index; i--) {
                            tempRow = table.row(i - 1).data()
                            table.row(i).data(tempRow)
                            table.row(i - 1).data(insertedRow)
                        }

                        //refresh the page
                        table.page(currentPage).draw(false)

                        // increment announcementCount
                        $("#announcementsCount").text(parseInt($("#announcementsCount").text()) + 1);

                        $("#thisDayCount").text(parseInt($("#thisDayCount").text()) + 1);
                        $("#thisMonthCount").text(parseInt($("#thisMonthCount").text()) + 1);
                        $("#thisYearCount").text(parseInt($("#thisYearCount").text()) + 1);


                    } else {
                        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7]).draw(false);
                    }
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#btnFormSubmit').attr("disabled", false); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //show the fa loading icon from submit btn
                }
            });

        }
    })

    // Validation for report form
    $("form[name='reportForm']").validate({
        // Specify validation rules
        rules: {
            date_start: {
                required: true,
            },
            date_end: {
                required: true,
                greaterThan: "#date_start"
            },
            sort_column: {
                required: true,
            },
            sort_option: {
                required: true,
            },
        },
        messages: {
            date_end: {
                greaterThan: "Date end must be greater than selected date start"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();

            var url = `${window.location.origin}/admin/announcements/report/${date_start}/${date_end}/${sort_column}/${sort_option}`;
            window.open(url, '_blank');
        }
    });


    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()
        let ajaxDelURL = $("#modalDeleteForm").attr('action')

        $.ajax({
            type: 'DELETE',
            url: ajaxDelURL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#btnDelete').attr("disabled", true); //disabled button
                $('.btnDeleteTxt').text('Deleting') //set the text of the submit btn
                $('.btnDeleteLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn
            },
            success: function (response) {
                toastr.success(response.message);

                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement termCount
                $("#announcementsCount").text(parseInt($("#announcementsCount").text()) - 1);
            },
            error: function (xhr) {
                var error = JSON.parse(xhr.responseText);

                // show error message from helper.js
                ajaxErrorMessage(error);
            },
            complete: function () {
                $('#btnDelete').attr("disabled", false); //enable button
                $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
                $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn

                $('#confirmationDeleteModal').modal('hide') //hide
                $('#modalDeleteForm').trigger("reset"); //reset all the values
            }
        });
    })
})

function sendRespond(id) {
    $('#feedbackRespondModal').modal('show');
    $('#respondForm').trigger("reset"); //reset all the values
    $('#respondForm').attr('action', window.location.origin + '/admin/feedbacks/respond/' + id);
}

$(document).ready(function () {
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

    $('#TypeNavCollapse').addClass('active')
    $('#collapseType').collapse()
    $('#feedbackType').addClass('active')

    $("form[name='feedbackRespondForm']").validate({
        // Specify validation rules
        rules: {
            admin_respond: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#respondForm").attr('action')
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
                    $('.btnTxt').text('Responding') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.submitted_by + '</td>'
                    col2 = `<td class="text-center"> <div class="Stars"
                            style="--rating: ${data.rating}; --star-size:50px"
                            aria-label="Rating of this product is ${data.rating} out of 5."></div>
                            <p>${data.rating}/5</p></td>`;
                    col3 = '<td>' + data.message + '</td>'
                    col4 = '<td>' + data.admin_respond + '</td>'
                    col5 = '<td>' + data.status + '</td>'
                    col6 = '<td>' + data.created_at + '</td>'
                    btnStatus = data.status !== 'Pending' ? 'disabled' : ''
                    col7 = '<td>' +
                        '<ul class="list-inline m-0">' +
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" type="button" onclick="sendRespond(' + data.id + ')" data-toggle="tooltip" data-placement="top" title="Respond"' + btnStatus + '>' +
                        'Responded' +
                        '</button>' +
                        '</li>' +
                        '</ul>'
                    '</td>'

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (data.type_id === 0) {
                        addCol = '<td>' + data.custom_type + '</td>'
                        table.row('.selected').data([col0, col1, addCol, col2, col3, col4, col5, col6, col7]).draw(false);
                    } else {
                        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7]).draw(false);
                    }

                    // add or minus some cards
                    $("#pendingFeedbackCount").text(parseInt($("#pendingFeedbackCount").text()) - 1);
                    $("#notedFeedbackCount").text(parseInt($("#notedFeedbackCount").text()) + 1);

                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#feedbackRespondModal').modal('hide') //hide the modal

                    $('#btnFormSubmit').attr("disabled", false); //disabled login
                    $('.btnTxt').text('Respond') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //show the fa loading icon from submit btn
                }
            });
        }
    });

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
            polarity_option: {
                required: true,
            },
            status_option: {
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
            let status_option = $('#status_option').val();
            let type_id = $('#type_id').val();

            var url = `${window.location.origin}/admin/feedback-types/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${status_option}/${type_id}`;
            window.open(url, '_blank');
        }
    });


})

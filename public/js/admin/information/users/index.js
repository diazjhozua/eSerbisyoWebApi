var selectedBtnEnable;
var selectedBtnDisable;
var selectedBtnVerify;

function replaceData(data) {
    if (data.file_path) {
        col1 = '<td> <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%;" src="' + data.file_path + '" class="rounded" alt="' + data.name + ' image"></td>'
    } else {
        col1 = '<td> <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%;" src="https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg" class="rounded" alt="' + data.name + ' image"></td>'
    }

    col2 = '<td>' + data.first_name + ' ' + data.last_name + '</td>'
    col3 = '<td>' + data.email + '</td>'
    col4 = '<td>' + data.role + '</td>'
    col5 = '<td>' + data.verification_status + '</td>'
    col6 = '<td>' + data.status + '</td>'
    col7 = '<td>' + data.created_at + '</td>'
    verifiedBtn = ''

    if (data.is_verified != 1 && data.latest_user_verification != null) {
        verifiedBtn =
            '<li class="list-inline-item mb-1">' +
            '<button class="btn btn-info btn-sm" onclick="viewVerificationRequest(' + data.latest_user_verification.id + ',this' + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
            '<span class="btnText btnVerify">Review Request</span>' +
            '<i class="btnVerifyIcon fas fa-money-check ml-1"></i>' +
            '<i class="btnVerifyLoadingIcon fa fa-spinner fa-spin" hidden></i>' +
            '</button>' +
            '</li>'
    }

    if (data.status == 'Enable Access') {
        changeStatusBtn =
            '<li class="list-inline-item mb-1">' +
            '<button class="btn btn-danger btn-sm" onclick="disable(' + data.id + ',this' + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
            '<span class="btnText btnDisableTxt">Restrict</span>' +
            '<i class="btnDisableIcon fas fa-user-slash ml-1"></i>' +
            '<i class="btnDisableLoadingIcon fa fa-spinner fa-spin" hidden></i>' +
            '</button>' +
            '</li>'

    } else {
        changeStatusBtn =
            '<li class="list-inline-item mb-1">' +
            '<button class="btn btn-success btn-sm" onclick="enable(' + data.id + ',this' + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
            '<span class="btnText btnEnableTxt">Enable</span>' +
            '<i class="btnEnableIcon fas fa-user-check ml-1"></i>' +
            '<i class="btnEnableLoadingIcon fa fa-spinner fa-spin" hidden></i>' +
            '</button>' +
            '</li>'
    }
    col8 = '<td><ul class="list-inline m-0">' + verifiedBtn + changeStatusBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTable').DataTable();

    table.row('.selected').data([col1, col2, col3, col4, col5, col6, col7, col8]).draw(true);

}

function enable(userID, button) {
    selectedBtnEnable = $(button)
    selectedBtnEnable.removeClass('activeEnable')
    selectedBtnEnable.addClass('activeEnable')

    let actionURL = window.location.origin + '/admin/users/changeStatus/' + userID
    $('#changeStatusForm').attr('action', actionURL) //set the method of the form
    $('#changeStatusForm').trigger("reset")

    // For disable modal
    $("#iconBoxLogo").attr('class', 'fas fa-check-circle') //change font awesome icon to disable
    // change color
    $(".modal-confirm .icon-box").css("border", "3px solid #42ba96");
    $(".modal-confirm .icon-box i").css("color", "#42ba96");

    $('#status').val('Enable')

    $('#confirmationMessage').text('Do you really want to enable this user? Once the user is enable, that specific user will revert back his user functionality')
    $('#txtAreaAdminMessageGuideText').text('Input here the reason why the user is enable. Please specify the user what to avoid getting disable by administrator.')
    $('.btnChangeStatusTxt').text('Enable');
    $('.btnChangeStatus').removeClass('btn-danger').addClass('btn-success');

    $('#changeStatusModal').modal('show') //show bootstrap modal
}

function disable(userID, button) {
    selectedBtnDisable = $(button)
    selectedBtnDisable.removeClass('activeDisable')
    selectedBtnDisable.addClass('activeDisable')

    let actionURL = window.location.origin + '/admin/users/changeStatus/' + userID
    $('#changeStatusForm').attr('action', actionURL) //set the method of the form
    $('#changeStatusForm').trigger("reset")
    // For disable modal
    $("#iconBoxLogo").attr('class', 'fas fa-ban') //change font awesome icon to disable
    // change color
    $(".modal-confirm .icon-box").css("border", "3px solid #f15e5e");
    $(".modal-confirm .icon-box i").css("color", "#f15e5e");

    $('#status').val('Disable')

    $('#confirmationMessage').text('Do you really want to restrict this user? Once the user is restrict, that specific user have only limited view functionality')
    $('#txtAreaAdminMessageGuideText').text('Input here the reason why the user is restrict. Please specify the user what to do make his/her account enable again')
    $('.btnChangeStatusTxt').text('Restrict');
    $('.btnChangeStatus').removeClass('btn-success').addClass('btn-danger');

    $('#changeStatusModal').modal('show') //show bootstrap modal
}

$(document).ready(function () {

    $('#user').addClass('active')

    // Initialize Year picker in form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd", // Notice the Extra space at the beginning
    });

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Validation
    $("form[name='changeStatusForm']").validate({
        // Specify validation rules
        rules: {
            admin_status_message: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
        },


        submitHandler: function (form, event) {
            event.preventDefault()
            // $('.selected').addClass('changingStatus');

            let ajaxURL = $("#changeStatusForm").attr('action')
            let formData = new FormData(form)

            $.ajax({
                type: 'POST',
                url: ajaxURL,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#btnChangeStatusFormSubmit').attr("disabled", true); //disabled login
                    $('.btnChangeStatusTxt').text($('#status').val() == 'Enable' ? 'Enabling' : 'Disabling'); //set the text of the submit btn
                    $('.btnChangeStatusLoadingIcon').prop("hidden", false); //show the fa loading icon from submit btn

                    if ($('#status').val() == 'Enable') {
                        //enable btn animation
                        selectedBtnEnable.attr("disabled", true);
                        selectedBtnEnable.children(".btnEnableTxt").text('Enabling');
                        selectedBtnEnable.children(".btnEnableIcon").prop("hidden", true);
                        selectedBtnEnable.children(".btnEnableLoadingIcon").prop("hidden", false);
                    } else {
                        //disable btn animation
                        selectedBtnDisable.attr("disabled", true);
                        selectedBtnDisable.children(".btnDisableTxt").text('Disabling');
                        selectedBtnDisable.children(".btnDisableIcon").prop("hidden", true);
                        selectedBtnDisable.children(".btnDisableLoadingIcon").prop("hidden", false);
                    }
                },
                success: function (response) {
                    toastr.success(response.message);

                    let data = response.data;
                    // increment/decrement restricted count card
                    if (data.status == 'Enable Access') {
                        $("#restrictedCount").text(parseInt($("#restrictedCount").text()) - 1);
                    } else {
                        $("#restrictedCount").text(parseInt($("#restrictedCount").text()) + 1);
                    }

                    // replace data
                    replaceData(data);
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#changeStatusModal').modal('hide') //hide the modal

                    $('.selected').removeClass('changingStatus');
                    $('#btnChangeStatusFormSubmit').attr("disabled", false); //enable the button
                    $('.btnChangeStatusTxt').text($('#status') == 'Enable' ? 'Enable' : 'Disable') //set the text of the submit btn
                    $('.btnChangeStatusLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
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
            filter: {
                required: true,
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
            let filter = $('#filter').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();

            var url = `${window.location.origin}/admin/users/report/${date_start}/${date_end}/${filter}/${sort_column}/${sort_option}`;
            window.open(url, '_blank');
        }
    });


})

const CERTIFICATE_NAV = $('#certificate');

// TABLE
const TBL_REQUIREMENT = $('#dataTableRequirement');
const TBL_APPROVED_REQUEST = $('#dataTableApprovedRequest');


// ADD REQUIREMENT MODAL
const MODAL_ADDREQ = $('#certRequirementFormModal');
const FORM_ADDREQ = $('#certRequirementForm');
const INPUT_CERT_ID = $('#inputCertID');
const SELECT_REQ_ID = $('#dropDwnRequirement');
const BTN_SUBMIT_ADDREQ = $('#btnCertReqFormSubmit');
const BTN_TXT_ADDREQ = $('#btnCertReqFormTxt');
const BTN_LOADING_ADDREQ = $('.certReqFormLoadingIcon');

// DELETE MODAL VARIABLE
const DELETE_MODAL = $('#confirmationDeleteModal');
const DELETE_FORM = $('#modalDeleteForm');
const CONFIRMATION_MESSAGE = $('#confirmationMessage');
const BTN_DELETE = $('#btnDelete');
const BTN_DELETE_TXT = $('.btnDeleteTxt');
const BTN_DELETE_LOADING = $('.btnDeleteLoadingIcon');


// CERTIFICATE FORM/INPUT/BUTTON FIELDS INITIALIZATION
const FORM_CERTIFICATE = $('#certificateForm');
const INPUT_CERTIFICATE_NAME = $('#inputName');
const INPUT_CERTIFICATE_PRICE = $('#inputPrice');
const SELECT_STATUS = $('#selectStatus');
const SELECT_RECEIVED_OPTION = $('#selectReceivedOption');
const BTN_CANCEL_EDIT_CERTIFICATE = $('#cancelEditBtn');
const BTN_CERTIFICATE_FORM_SUBMIT = $('.btnCertificateFormSubmit');
const BTN_TEXT_CERTIFICATE_FORM = $('.btnCertificateFormTxt');
const BTN_LOADING_CERTIFICATE_FORM = $('.btnCertificateFormLoadingIcon');


// DISABLE INPUT FIELD REGARDING TO THE CERTIFICATE FORM
function disableAllInputsAccess(booleanStatus) {
    INPUT_CERTIFICATE_NAME.prop('disabled', booleanStatus);
    INPUT_CERTIFICATE_PRICE.prop('disabled', booleanStatus);
    SELECT_STATUS.prop('disabled', booleanStatus);
    SELECT_RECEIVED_OPTION.prop('disabled', booleanStatus);
}


function deleteRequirement(certID, reqID) {
    $('#confirmationDeleteModal').modal('show');
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/certificates/' + certID + '/' + reqID);
    $('#confirmationMessage').text('Do you really want to delete this requirement from this certificate? This process cannot be undone.');
}

// cancel editing function
function cancelEditing() {
    BTN_CERTIFICATE_FORM_SUBMIT.prop('hidden', true);
    BTN_CANCEL_EDIT_CERTIFICATE.prop('hidden', true);

    //disable all the fields;
    disableAllInputsAccess(true);
}


function populateAllFields(data, statusList, receivedOptionList) {

    //empty dropdown list
    SELECT_STATUS.empty();
    SELECT_RECEIVED_OPTION.empty();

    // populate fields of complaint data
    INPUT_CERTIFICATE_NAME.val(data.name);
    INPUT_CERTIFICATE_PRICE.val(data.price);

    SELECT_STATUS.append($("<option selected/>").val(data.status).text(data.status))
    SELECT_RECEIVED_OPTION.append($("<option selected/>").val(data.is_open_delivery).text(data.is_open_delivery == 1 ? 'Available for delivery and walkin' : 'Walkin Only'))

    // status dropdown list
    if (statusList != null) {
        $.each(statusList, function () {
            // Populate Drop Dowm
            if (this.type != data.status) {
                // Populate Position Drop Dowm
                SELECT_STATUS.append($("<option />").val(this.type).text(this.type))
            }
        })
    }

    // receivied dropdown list
    if (receivedOptionList != null) {
        $.each(receivedOptionList, function () {
            // Populate Drop Dowm
            if (this.id != data.is_open_delivery) {
                // Populate Position Drop Dowm
                SELECT_RECEIVED_OPTION.append($("<option />").val(this.id).text(this.type))
            }
        })
    }
}

function editCertificate(id) {
    url = window.location.origin + '/admin/certificates/' + id + '/edit'
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

            toastr.info('The fields can now be edited');

            const data = response.data; //missing report data
            const statusList = response.status;

            const receivedOptionList = response.receivedOption;

            // populate and show the current picture of missing person data
            BTN_CERTIFICATE_FORM_SUBMIT.prop('hidden', false);
            BTN_CANCEL_EDIT_CERTIFICATE.prop('hidden', false);
            BTN_TEXT_CERTIFICATE_FORM.text('Update') //set the text of the submit btn

            // populate all input fields func
            populateAllFields(data, statusList, receivedOptionList);

            // enable all the fields;
            disableAllInputsAccess(false);

        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function addRequirement(certID) {
    url = window.location.origin + '/admin/certificates/' + certID + '/add-requirement';
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

            toastr.info('The fields can now be edited');

            const data = response.data; // list of requirements available to be added in that certificate
            MODAL_ADDREQ.modal('show');

            // populate dropdown
            if (data != null) {
                $.each(data, function () {
                    // Populate Drop Dowm
                    SELECT_REQ_ID.append($("<option />").val(this.id).text(this.name))
                });
            };

            BTN_TXT_ADDREQ.text('Store');

        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}


$(document).ready(function () {
    CERTIFICATE_NAV.addClass('active');

    // Initialize Year picker in form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd", // Notice the Extra space at the beginning
    });

    // initialize dataTable
    TBL_REQUIREMENT.DataTable({
        scrollY: "200px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });


    TBL_REQUIREMENT.wrap('<div class="scrooll_div"></div >');

    TBL_APPROVED_REQUEST.dataTable({
        "aaSorting": []
        // Your other options here...
    });

    // Set class row selected when any button was click in the selected
    TBL_REQUIREMENT.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            TBL_REQUIREMENT.DataTable().$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
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
            event.preventDefault();

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();
            let certificate_id = $('#certificate_id').val();

            var url = `${window.location.origin}/admin/certificates/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${certificate_id}`;
            window.open(url, '_blank');
        }
    });

    FORM_CERTIFICATE.validate({
        // Specify validation rules
        rules: {
            inputName: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
            inputPrice: {
                required: true,
                number: true,
                min: 1,
                max: 4000,
            },
            selectStatus: {
                required: true,
            },
            selectReceivedOption: {
                required: true,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            let formAction = FORM_CERTIFICATE.attr('action')
            let formData = new FormData();

            formData.append('_method', 'PUT');
            formData.append('name', INPUT_CERTIFICATE_NAME.val());
            formData.append('price', INPUT_CERTIFICATE_PRICE.val());
            formData.append('status', SELECT_STATUS.val());
            formData.append('is_open_delivery', SELECT_RECEIVED_OPTION.val());

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
                    BTN_CERTIFICATE_FORM_SUBMIT.attr("disabled", true); //disabled login
                    BTN_TEXT_CERTIFICATE_FORM.text('Updating'); //set the text of the submit btn
                    BTN_LOADING_CERTIFICATE_FORM.prop("hidden", false); //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    const data = response.data

                    //hide image select and button submit
                    cancelEditing();

                    //populate all input fields func
                    populateAllFields(data, null, null);

                    //disable all the fields;
                    disableAllInputsAccess(true);

                    //scroll to top
                    $("html, body").animate({ scrollTop: 0 }, 1000);
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    BTN_CERTIFICATE_FORM_SUBMIT.attr("disabled", false); //enable the button
                    BTN_TEXT_CERTIFICATE_FORM.text('Update') //set the text of the submit btn
                    BTN_LOADING_CERTIFICATE_FORM.prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });

        }
    });

    FORM_ADDREQ.validate({
        // Specify validation rules
        rules: {
            requirement_id: {
                required: true,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            let formAction = FORM_ADDREQ.attr('action')
            let formData = new FormData(form);
            const reqID = SELECT_REQ_ID.val();

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
                    BTN_SUBMIT_ADDREQ.attr("disabled", true); //disabled login
                    BTN_TXT_ADDREQ.text('Storing'); //set the text of the submit btn
                    BTN_LOADING_ADDREQ.prop("hidden", false); //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    MODAL_ADDREQ.modal('hide');
                    const data = response.data;
                    const requirements = response.data.requirements;
                    requirements.forEach(requirement => {
                        if (requirement.id == reqID) {
                            col0 = '<td>' + requirement.id + '</td>'
                            col1 = '<td>' + requirement.name + '</td>'
                            deleteBtn =
                                '<li class="list-inline-item mb-1">' +
                                '<button class="btn btn-danger btn-sm"  onclick="deleteRequirement(\'' + data.id
                                + '\',\'' + requirement.id + '\') " type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                                '<i class="fas fa-trash-alt"></i>' +
                                '</button>' +
                                '</li>';

                            col2 = '<td><ul class="list-inline m-0">' + deleteBtn + '</td></ul>';

                            var table = TBL_REQUIREMENT.DataTable();

                            var currentPage = table.page();
                            table.row.add([col0, col1, col2]).draw()

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

                            // increment typeCount
                            $("#requirementsCount").text(parseInt($("#requirementsCount").text()) + 1);

                        }
                    });
                    // window.location.reload(true);
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    BTN_SUBMIT_ADDREQ.attr("disabled", false); //enable the button
                    BTN_TXT_ADDREQ.text('Store') //set the text of the submit btn
                    BTN_LOADING_ADDREQ.prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });

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

                var table = TBL_REQUIREMENT.DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                $("#requirementsCount").text(parseInt($("#requirementsCount").text()) - 1);
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


});


const canvas = document.querySelector("canvas");
var clearButton = wrapper.querySelector("[data-action=clear]");
var changeColorButton = wrapper.querySelector("[data-action=change-color]");
var undoButton = wrapper.querySelector("[data-action=undo]");

var signaturePad = new SignaturePad(canvas, {
    // It's Necessary to use an opaque color when saving image as JPEG;
    // this option can be omitted if only saving as PNG or SVG
    backgroundColor: 'rgb(247, 247, 247)'
});
signaturePad.minWidth = 2;
signaturePad.maxWidth = 2;
signaturePad.penColor = "rgb(66, 133, 244)";


clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
});

undoButton.addEventListener("click", function (event) {
    var data = signaturePad.toData();

    if (data) {
        data.pop(); // remove the last dot or line
        signaturePad.fromData(data);
    }
});

changeColorButton.addEventListener("click", function (event) {
    var r = Math.round(Math.random() * 255);
    var g = Math.round(Math.random() * 255);
    var b = Math.round(Math.random() * 255);
    var color = "rgb(" + r + "," + g + "," + b + ")";

    signaturePad.penColor = color;
});


const COMPLAINT_NAV = $('#complaint');
const COMPLAINT_ID = window.location.href.match(/complaints\/(\d+)/);
var COMPLAINANT_ID;
var DEFENDANT_ID;
var isDeletingComplaint;
var isDeletingComplainant;
var isDeletingDefendant;

// TABLE
const COMPLAINANT_TBL = $('#dataTableComplainant');
const DEFENDANT_TBL = $('#dataTableDefendant');

// COMPLAINT INFORMATION INITIALIZATION
const CURRENT_TYPE = $('#currentComplaintType');
const CURRENT_CONTACT_NAME = $('#currentContactName');
const CURRENT_CONTACT_ID = $('#currentContactID');
const CURRENT_CONTACT_ROLE = $('#currentContactRole');
const CURRENT_ADMIN_MESSAGE = $('#currentAdminMessage');
const CURRENT_STATUS = $('#currentStatus');
const CURRENT_CREATED_AT = $('#currentCreatedAt');
const CURRENT_UPDATED = $('#currentUpdatedAt');

// DELETE MODAL VARIABLE
const DELETE_MODAL = $('#confirmationDeleteModal');
const DELETE_FORM = $('#modalDeleteForm');
const CONFIRMATION_MESSAGE = $('#confirmationMessage');
const BTN_DELETE = $('#btnDelete');
const BTN_DELETE_TXT = $('.btnDeleteTxt');
const BTN_DELETE_LOADING = $('.btnDeleteLoadingIcon');

// COMPLAINT FORM/INPUT/BUTTON FIELDS INITIALIZATION
const COMPLAINT_FORM = $('#complaintForm');
const COMPLAINT_FORM_METHOD = $('#complaintFormMethod');
const INPUT_TYPE_ID = $('#type_id');
const INPUT_CUSTOM_TYPE = $('#custom_type');
const INPUT_CONTACT_USER_ID = $('#contact_user_id');
const INPUT_EMAIL = $('#email');
const INPUT_PHONE_NO = $('#phone_no');
const INPUT_REASON = $('#reason');
const INPUT_ACTION = $('#action');
const COMPLAINT_CANCEL_BTN = $('#cancelEditBtn');
const COMPLAINT_BTN = $('.btnComplaintFormSubmit');
const COMPLAINT_BTN_TEXT = $('.btnComplaintFormTxt');
const COMPLAINT_BTN_LOADING = $('.btnComplaintFormLoadingIcon');

// COMPLAINANT FORM/INPUT/BUTTON FIELDS INITIALIZATION
const COMPLAINANT_MODAL = $('#complainantFormModal');
const COMPLAINANT_FORM = $('#complainantForm');
const COMPLAINANT_FORM_HEADER = $('#complainantFormModalHeader');
const COMPLAINANT_FORM_METHOD = $('#complainantFormMethod');
const INPUT_COMPLAINANT_NAME = $('#inputComplainantName');
const COMPLAINANT_BTN = $('.btnComplainantFormSubmit');
const COMPLAINANT_BTN_TEXT = $('.btnComplainantFormBtnTxt');
const COMPLAINANT_BTN_LOADING = $('.btnComplainantFormLoadingIcon');


// DEFENDANT FORM/INPUT/BUTTON FIELDS INITIALIZATION
const DEFENDANT_MODAL = $('#defendantFormModal');
const DEFENDANT_FORM = $('#defendantForm');
const DEFENDANT_FORM_HEADER = $('#defendantFormModalHeader');
const DEFENDANT_FORM_METHOD = $('#defendantFormMethod');
const INPUT_DEFENDANT_NAME = $('#inputDefendantName');
const DEFENDANT_BTN = $('.btnDefendantFormSubmit');
const DEFENDANT_BTN_TEXT = $('.btnDefendantFormBtnTxt');
const DEFENDANT_BTN_LOADING = $('.btnDefendantFormLoadingIcon');


//change status func
function changeStatusComplaint(id) {
    $('#changeStatusFormModal').modal('show')
    let actionURL = window.location.origin + '/admin/complaints/change-status/' + id;
    $('#changeStatusForm').attr('action', actionURL) //set the method of the form
    $('#changeStatusForm').trigger("reset"); //reset all the values
}

function addComplainant() {
    let inputMethod = '<input type="hidden" id="complainantMethod" name="_method" value="POST">';
    let actionURL = window.location.origin + '/admin/complainants';
    // Clears the canvas
    signaturePad.clear();

    modifyComplainantModal('Add Complainant', 'Add', inputMethod, actionURL, null)
}

function addDefendant() {
    let inputMethod = '<input type="hidden" id="defendantMethod" name="_method" value="POST">';
    let actionURL = window.location.origin + '/admin/defendants';
    // Clears the canvas
    signaturePad.clear();

    modifyDefendantModal('Add Defendant', 'Add', inputMethod, actionURL, null)
}


function editDefendant(id) {
    url = window.location.origin + '/admin/defendants/' + id + '/edit'
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
            toastr.success(response.message);
            toastr.info('The fields can now be edited');
            const data = response.data;
            DEFENDANT_ID = data.id;
            let inputMethod = '<input type="hidden" id="defendantMethod" name="_method" value="PUT">';
            let actionURL = window.location.origin + '/admin/defendants/' + data.id;

            modifyDefendantModal('Edit Defendant', 'Update', inputMethod, actionURL, data)
        },
        error: function (xhr, status, error) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        },
        complete: function () {

        }
    });
}

function editComplainant(id) {
    url = window.location.origin + '/admin/complainants/' + id + '/edit'
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
            toastr.success(response.message);
            toastr.info('The fields can now be edited');
            const data = response.data;
            COMPLAINANT_ID = data.id;
            console.log(data);
            let inputMethod = '<input type="hidden" id="complainantMethod" name="_method" value="PUT">';
            let actionURL = window.location.origin + '/admin/complainants/' + data.id;

            modifyComplainantModal('Edit Complainant', 'Update', inputMethod, actionURL, data)
        },
        error: function (xhr, status, error) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        },
        complete: function () {

        }
    });
}

function modifyComplainantModal(title, btnText, inputMethod, actionURL, data) {
    COMPLAINANT_MODAL.modal('show'); //show the modal
    COMPLAINANT_FORM.trigger("reset"); //reset all the values
    COMPLAINANT_FORM.attr('action', actionURL); //set the method of the form
    COMPLAINANT_FORM_HEADER.text(title); //set the header of the
    COMPLAINANT_FORM_METHOD.empty();
    COMPLAINANT_FORM_METHOD.append(inputMethod); // append formMethod

    COMPLAINANT_BTN_TEXT.text(btnText); //set the text of the submit btn

    if (data != null) {
        INPUT_COMPLAINANT_NAME.val(data.name); // set name
        signaturePad.fromDataURL(data.signature_src);
    }
}

function modifyDefendantModal(title, btnText, inputMethod, actionURL, data) {
    DEFENDANT_MODAL.modal('show'); //show the modal
    DEFENDANT_FORM.trigger("reset"); //reset all the values
    DEFENDANT_FORM.attr('action', actionURL); //set the method of the form
    DEFENDANT_FORM_HEADER.text(title); //set the header of the
    DEFENDANT_FORM_METHOD.empty();
    DEFENDANT_FORM_METHOD.append(inputMethod); // append formMethod

    DEFENDANT_BTN_TEXT.text(btnText); //set the text of the submit btn

    if (data != null) {
        INPUT_DEFENDANT_NAME.val(data.name); // set name
    }
}

function addOrReplaceToTable(table, colList, addOrReplace) {
    if (addOrReplace == 'Replace') {
        // if replacing the table row
        table.row('.selected').data(colList).draw(false);
    } else {
        // if adding new table row
        var currentPage = table.page();
        table.row.add(colList).draw()

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
    }
}

function addOrReplaceComplainant(data, addOrReplace) {

    console.log(`Table signature: ${data.file_path}`);
    col0 = '<td>' + data.name + '</td>';
    var image = new Image();
    image.src = data.file_path;
    image.style = "height:100px; width: 100%;";
    image.className = 'rounded';
    image.alt = data.name + ' signature';

    col1 =
        '<td>' +
        image.outerHTML +
        '</td>'

    editBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="editComplainant(' + data.id + ') " type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
        '<i class="fas fa-edit"></i>' +
        '</button>' +
        '</li>'

    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="deleteComplainant(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<i class="fas fa-trash-alt"></i>' +
        '</button>' +
        '</li>'

    col2 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = COMPLAINANT_TBL.DataTable();
    var colList = [col0, col1, col2];

    addOrReplaceToTable(table, colList, addOrReplace)
}

function addOrReplaceDefendant(data, addOrReplace) {

    col0 = '<td>' + data.name + '</td>';

    editBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="editComplainant(' + data.id + ') " type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
        '<i class="fas fa-edit"></i>' +
        '</button>' +
        '</li>'

    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="deleteComplainant(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<i class="fas fa-trash-alt"></i>' +
        '</button>' +
        '</li>'

    col1 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = DEFENDANT_TBL.DataTable();
    var colList = [col0, col1];

    addOrReplaceToTable(table, colList, addOrReplace)
}

// DISABLE INPUT FIELD REGARDING TO THE COMPLAINT FORM
function disableAllInputsAccess(booleanStatus) {
    INPUT_TYPE_ID.prop('disabled', booleanStatus);
    INPUT_CUSTOM_TYPE.prop('disabled', booleanStatus);
    INPUT_CONTACT_USER_ID.prop('disabled', booleanStatus);
    INPUT_EMAIL.prop('disabled', booleanStatus);
    INPUT_PHONE_NO.prop('disabled', booleanStatus);
    INPUT_REASON.prop('disabled', booleanStatus);
    INPUT_ACTION.prop('disabled', booleanStatus);
}

function populateAllFields(data, complaintTypes) {

    console.log(data);
    // set text for tags (not input field)
    if (data.custom_type != null) {
        $('#CURRENT_TYPE').text(data.custom_type);
    } else {
        $('#CURRENT_TYPE').text(data.complaint_type);
    }

    CURRENT_CREATED_AT.text(data.created_at);
    CURRENT_UPDATED.text(data.updated_at);
    CURRENT_ADMIN_MESSAGE.text(data.admin_message);

    //change the contact id span
    CURRENT_CONTACT_NAME.text(data.contact_name);
    CURRENT_CONTACT_ID.text(data.contact_id);
    CURRENT_CONTACT_ROLE.text(data.contact_role);

    //empty dropdown list
    INPUT_TYPE_ID.empty();

    // populate fields of complaint data
    INPUT_CONTACT_USER_ID.val(data.contact_id);
    INPUT_EMAIL.val(data.email);
    INPUT_PHONE_NO.val(data.phone_no);
    INPUT_REASON.text(data.reason);
    INPUT_ACTION.text(data.action);

    //remove all bg class in currentStatus id
    CURRENT_STATUS.removeClass('bg-info').removeClass('bg-dark').removeClass('bg-success').removeClass('bg-danger');

    // add class and text of currentStatus id
    switch (data.status) {
        case 'Pending':
            CURRENT_STATUS.addClass('bg-info').text(data.status);
            break;
        case 'Approved':
            CURRENT_STATUS.addClass('bg-dark').text(data.status);
            break;
        case 'Resolved':
            CURRENT_STATUS.addClass('bg-success').text(data.status);
            break;
        case 'Denied':
            CURRENT_STATUS.addClass('bg-danger').text(data.status);
            break;
    }

    //report_type dropdown list
    if (complaintTypes != null) {
        $.each(complaintTypes, function () {
            // Populate Drop Dowm
            if (this.type != data.complaint_type) {
                // Populate Position Drop Dowm
                INPUT_TYPE_ID.append($("<option />").val(this.id).text(this.name))
            }
        })
    }
}
//delete function
function deleteComplaint(id) {
    isDeletingComplaint = true;
    isDeletingComplainant = false;
    isDeletingDefendant = false;
    DELETE_MODAL.modal('show')
    DELETE_FORM.attr('action', '/admin/complaints/' + id)
    CONFIRMATION_MESSAGE.text('Do you really want to delete this missing item complaint data? This process cannot be undone. All of the complainants & defendants related to this complaint will be deleted')
}

function deleteDefendant(id) {
    isDeletingComplaint = false;
    isDeletingComplainant = false;
    isDeletingDefendant = true;
    DELETE_MODAL.modal('show')
    DELETE_FORM.attr('action', '/admin/defendants/' + id)
    CONFIRMATION_MESSAGE.text('Do you really want to delete this defendant data? This process cannot be undone.')
}

function deleteComplainant(id) {
    isDeletingComplaint = false;
    isDeletingComplainant = true;
    isDeletingDefendant = false;
    DELETE_MODAL.modal('show')
    DELETE_FORM.attr('action', '/admin/complainants/' + id)
    CONFIRMATION_MESSAGE.text('Do you really want to delete this complainant data? This process cannot be undone.')
}


function editComplaint(id) {
    url = window.location.origin + '/admin/complaints/' + id + '/edit'
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

            toastr.success(response.message);
            toastr.info('The fields can now be edited');
            const data = response.data; //missing report data
            const complaintTypes = response.complaintTypes;

            let actionURL = window.location.origin + '/admin/complaints/' + data.id;
            let inputMethod = '<input type="hidden" id="complaintMethod" name="_method" value="PUT">';

            COMPLAINT_FORM_METHOD.empty();
            COMPLAINT_FORM_METHOD.append(inputMethod) // append formMethod
            COMPLAINT_FORM.attr('action', actionURL) //set the method of the form

            // populate and show the current picture of missing person data
            COMPLAINT_BTN.prop('hidden', false);
            COMPLAINT_CANCEL_BTN.prop('hidden', false);
            COMPLAINT_BTN_TEXT.text('Update') //set the text of the submit btn

            //populate all input fields func
            populateAllFields(data, complaintTypes);

            //enable all the fields;
            disableAllInputsAccess(false);


            //scroll to editContainer div
            $('html, body').animate({
                scrollTop: COMPLAINT_BTN.offset().top
            }, 2000);
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

// cancel editing function
function cancelEditing() {
    COMPLAINT_BTN.prop('hidden', true);
    COMPLAINT_CANCEL_BTN.prop('hidden', true);

    //disable all the fields;
    disableAllInputsAccess(true);
}

$(document).ready(function () {
    COMPLAINT_NAV.addClass('active');

    // initialize dataTable
    COMPLAINANT_TBL.DataTable({
        scrollY: "480px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    COMPLAINANT_TBL.wrap('<div class="scrooll_div"></div >');

    DEFENDANT_TBL.DataTable({
        scrollY: "480px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    DEFENDANT_TBL.wrap('<div class="scrooll_div"></div >');

    $('.scrooll_div').doubleScroll({
        resetOnWindowResize: true
    });

    // Set class row selected when any button was click in the selected
    DEFENDANT_TBL.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            DEFENDANT_TBL.DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    COMPLAINANT_TBL.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            COMPLAINANT_TBL.DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    COMPLAINANT_FORM.validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            let signature = signaturePad.toDataURL("image/jpeg");

            if (signaturePad.isEmpty()) {
                toastr.error('Please fill up the signature');
            } else {
                let formAction = COMPLAINANT_FORM.attr('action')
                let formMethod = $('#complainantMethod').val()
                let formData = new FormData();


                formData.append('_method', formMethod);

                if (formMethod == 'PUT') {
                    formData.append('id', COMPLAINANT_ID);
                }
                formData.append('complaint_id', COMPLAINT_ID[1]);
                formData.append('name', INPUT_COMPLAINANT_NAME.val());
                formData.append('signature', signature.replace("data:image/jpeg;base64,", ""));

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
                        COMPLAINANT_BTN.attr("disabled", true); //disabled login
                        COMPLAINANT_BTN_TEXT.text(formMethod == 'POST' ? 'Adding' : 'Updating'); //set the text of the submit btn
                        COMPLAINANT_BTN_LOADING.prop("hidden", false); //show the fa loading icon from submit btn
                    },
                    success: function (response) {
                        toastr.success(response.message);
                        const data = response.data
                        console.log(data);
                        COMPLAINANT_MODAL.modal('hide') //hide the modal

                        addOrReplaceComplainant(data, $('#complainantMethod').val() == 'POST' ? 'Add' : 'Replace')
                    },
                    error: function (xhr) {
                        var error = JSON.parse(xhr.responseText);

                        // show error message from helper.js
                        ajaxErrorMessage(error);
                    },
                    complete: function () {
                        COMPLAINANT_BTN.attr("disabled", false); //enable the button
                        COMPLAINANT_BTN_TEXT.text($('#complainantMethod').val() == 'POST' ? 'Add' : 'Replace') //set the text of the submit btn
                        COMPLAINANT_BTN_LOADING.prop("hidden", true) //hide the fa loading icon from submit btn
                    }
                });
            }
        }
    });

    DEFENDANT_FORM.validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            let formAction = DEFENDANT_FORM.attr('action')
            let formMethod = $('#defendantMethod').val()
            let formData = new FormData();

            formData.append('_method', formMethod);

            if (formMethod == 'PUT') {
                formData.append('id', DEFENDANT_ID);
            }
            formData.append('complaint_id', COMPLAINT_ID[1]);
            formData.append('name', INPUT_DEFENDANT_NAME.val());

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
                    DEFENDANT_BTN.attr("disabled", true); //disabled login
                    DEFENDANT_BTN_TEXT.text(formMethod == 'POST' ? 'Adding' : 'Updating'); //set the text of the submit btn
                    DEFENDANT_BTN_LOADING.prop("hidden", false); //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    const data = response.data
                    console.log(data);
                    DEFENDANT_MODAL.modal('hide') //hide the modal

                    addOrReplaceDefendant(data, $('#defendantMethod').val() == 'POST' ? 'Add' : 'Replace')
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    DEFENDANT_BTN.attr("disabled", false); //enable the button
                    DEFENDANT_BTN_TEXT.text($('#defendantMethod').val() == 'POST' ? 'Add' : 'Replace') //set the text of the submit btn
                    DEFENDANT_BTN_LOADING.prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    });




    // COMPLAINANT FORM VALIDATE
    COMPLAINT_FORM.validate({
        // Specify validation rules
        rules: {
            type_id: {
                required: true,
            },
            contact_user_id: {
                required: true,
                number: true,
                min: 1,
            },
            email: {
                required: true,
                email: true,
                maxlength: 150,
            },
            phone_no: {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11,
            },
            reason: {
                required: true,
                minlength: 4,
                maxlength: 500,
            },
            action: {
                required: true,
                minlength: 4,
                maxlength: 500,
            }

        },
        submitHandler: function (form, event) {
            event.preventDefault();

            let formAction = COMPLAINT_FORM.attr('action')

            var formData = new FormData();

            if (INPUT_TYPE_ID.val() != null) {
                formData.append('type_id', INPUT_TYPE_ID.val());
            } else {
                formData.append('custom_type', INPUT_CUSTOM_TYPE.val());
            }

            formData.append('_method', 'PUT');
            formData.append('contact_user_id', INPUT_CONTACT_USER_ID.val());
            formData.append('email', INPUT_EMAIL.val());
            formData.append('phone_no', INPUT_PHONE_NO.val());
            formData.append('reason', INPUT_REASON.val());
            formData.append('action', INPUT_ACTION.val());

            $.ajax({
                type: 'POST',
                url: formAction,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    COMPLAINT_BTN.attr("disabled", true); //disabled login
                    COMPLAINT_BTN_TEXT.text('Updating') //set the text of the submit btn
                    COMPLAINT_BTN_LOADING.prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    const data = response.data

                    //hide image select and button submit
                    cancelEditing();

                    //populate all input fields func
                    populateAllFields(data, null);

                    //disable all the fields;
                    disableAllInputsAccess(true);

                    //scroll to top
                    $("html, body").animate({ scrollTop: 0 }, 1000);
                },
                error: function (xhr, status, error) {
                    var err = JSON.parse(xhr.responseText);

                    if (err.message != null) {
                        toastr.error(result.message)

                    } else {
                        $.each(err.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                complete: function () {
                    COMPLAINT_BTN.attr("disabled", false);
                    COMPLAINT_BTN_TEXT.text('Update');
                    COMPLAINT_BTN_LOADING.prop("hidden", true);
                }
            });



        }
    });

    $("form[name='changeStatusForm']").validate({
        // Specify validation rules
        rules: {
            status: {
                required: true,
            },
            admin_message: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#changeStatusForm").attr('action')
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
                    $('.btnChangeStatusFormSubmit').attr("disabled", true); //disabled login
                    $('.btnChangeStatusFormTxt').text('Updating') //set the text of the submit btn
                    $('.btnChangeStatusFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);

                    const data = response.data

                    //populate all input fields func
                    populateAllFields(data, null, null, null);

                    $('#changeStatusFormModal').modal('hide')
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('.btnChangeStatusFormSubmit').attr("disabled", false); //enable the button
                    $('.btnChangeStatusFormTxt').text('Update') //set the text of the submit btn
                    $('.btnChangeStatusFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    });



    // Delete Modal Form
    DELETE_FORM.submit(function (e) {
        e.preventDefault()
        let ajaxDelURL = DELETE_FORM.attr('action')

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
                BTN_DELETE.attr("disabled", true); //disabled button
                BTN_DELETE_TXT.text('Deleting') //set the text of the submit btn
                BTN_DELETE_LOADING.prop("hidden", false) //show the fa loading icon from delete btn
            },
            success: function (response) {
                toastr.success(response.message);

                if (isDeletingComplaint) {
                    window.setTimeout(function () {
                        window.location.replace(window.location.origin + '/admin/complaints');
                    }, 2000);
                }

                if (isDeletingDefendant) {
                    var table = DEFENDANT_TBL.DataTable();
                    $('.selected').fadeOut(800, function () {
                        table.row('.selected').remove().draw();
                    });
                    // decrement typeCount
                    $("#defendantsCount").text(parseInt($("#defendantsCount").text()) - 1);
                }

                if (isDeletingComplainant) {
                    var table = COMPLAINANT_TBL.DataTable();
                    $('.selected').fadeOut(800, function () {
                        table.row('.selected').remove().draw();
                    });
                    // decrement typeCount
                    $("#complainantsCount").text(parseInt($("#complainantsCount").text()) - 1);
                }
            },
            error: function (xhr) {
                var error = JSON.parse(xhr.responseText);

                // show error message from helper.js
                ajaxErrorMessage(error);
            },
            complete: function () {
                BTN_DELETE.attr("disabled", false); //enable button
                BTN_DELETE_TXT.text('Delete') //set the text of the delete btn
                BTN_DELETE_LOADING.prop("hidden", true) //hide the fa loading icon from submit btn

                DELETE_MODAL.modal('hide') //hide
                DELETE_FORM.trigger("reset"); //reset all the values
            }
        });
    });


})

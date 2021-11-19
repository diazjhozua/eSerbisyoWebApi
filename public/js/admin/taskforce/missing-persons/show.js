

// change all the input fields enable/disable (accepts boolean param)
function disableAllInputsAccess(booleanStatus) {
    $("#name").prop('disabled', booleanStatus);
    $("#report_type").prop('disabled', booleanStatus);
    $("#unique_sign").prop('disabled', booleanStatus);
    $("#age").prop('disabled', booleanStatus);
    $("#height").prop('disabled', booleanStatus);
    $("#height_unit").prop('disabled', booleanStatus);
    $("#weight").prop('disabled', booleanStatus);
    $("#weight_unit").prop('disabled', booleanStatus);
    $("#eyes").prop('disabled', booleanStatus);
    $("#hair").prop('disabled', booleanStatus);
    $("#contact_information").prop('disabled', booleanStatus);
    $("#last_seen").prop('disabled', booleanStatus);
    $("#important_information").prop('disabled', booleanStatus);
}

function populateAllFields(data, reportTypes, heightUnits, weightUnits) {

    // set text for tags (not input field)
    $('#currentReportType').text(data.report_type);
    $('#currentName').text(data.name);
    $('#currentCreatedAt').text(data.created_at);
    $('#currentUpdatedAt').text(data.updated_at);
    $('#currentImage').prop('src', data.picture_src); //add the src attribute
    $("#currentImage").prop("alt", data.name + ' picture'); //add the alt text

    //empty dropdown list
    $('#report_type').empty();
    $('#height_unit').empty();
    $('#weight_unit').empty();

    console.log(data.picture_src);
    $('.custom-file-label').html(data.picture_name); // set picture name
    $('#imgCurrentPicture').prop('src', data.picture_src); //add the src attribute
    $("#imgCurrentPicture").prop("alt", data.name + ' picture'); //add the alt text

    // populate fields of missing person data
    $('#name').val(data.name);
    $('#unique_sign').val(data.unique_sign);
    $('#age').val(data.age);
    $('#height').val(data.height);
    $('#weight').val(data.weight);
    $('#eyes').val(data.eyes);
    $('#hair').val(data.hair);
    $('#contact_information').val(data.contact_information);
    $('#last_seen').val(data.last_seen);
    $('#important_information').val(data.important_information);

    //remove all bg class in currentStatus id
    $("#currentStatus").removeClass('bg-info').removeClass('bg-dark').removeClass('bg-success').removeClass('bg-danger');

    // add class and text of currentStatus id
    switch (data.status) {
        case 'Pending':
            $("#currentStatus").addClass('bg-info').text(data.status);
            break;
        case 'Approved':
            $("#currentStatus").addClass('bg-dark').text(data.status);
            break;
        case 'Resolved':
            $("#currentStatus").addClass('bg-success').text(data.status);
            break;
        case 'Denied':
            $("#currentStatus").addClass('bg-danger').text(data.status);
            break;
    }

    //report_type dropdown list
    $('#report_type').append($("<option selected/>").val(data.report_type).text(data.report_type))

    if (reportTypes != null) {
        $.each(reportTypes, function () {
            // Populate Drop Dowm
            if (this.type != data.report_type) {
                // Populate Position Drop Dowm
                $('#report_type').append($("<option />").val(this.type).text(this.type))
            }
        })
    }

    //height unit dropdown list
    $('#height_unit').append($("<option selected/>").val(data.height_unit).text(data.height_unit))

    if (heightUnits != null) {
        $.each(heightUnits, function () {
            // Populate Drop Dowm
            if (this.unit != data.height_unit) {
                // Populate Position Drop Dowm
                $('#report_type').append($("<option />").val(this.unit).text(this.unit))
            }
        })
    }

    // weight unit
    $('#weight_unit').append($("<option selected/>").val(data.weight_unit).text(data.weight_unit))

    if (weightUnits != null) {
        $.each(weightUnits, function () {
            // Populate Drop Dowm
            if (this.unit != data.weight_unit) {
                // Populate Position Drop Dowm
                $('#weight_unit').append($("<option />").val(this.unit).text(this.unit))
            }
        })
    }
}

// edit report function
function editReport(id) {
    console.log(id);
    url = window.location.origin + '/admin/missing-persons/' + id + '/edit'
    doAjax(url, 'GET').then((response) => {
        const data = response.data; //missing report data
        const reportTypes = response.reportTypes;
        const heightUnits = response.heightUnits;
        const weightUnits = response.weightUnits;

        let actionURL = window.location.origin + '/admin/missing-persons/' + data.id;
        let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">';

        $("#formMethod").empty();
        $("#formMethod").append(inputMethod) // append formMethod
        $('#reportForm').attr('action', actionURL) //set the method of the form

        // populate and show the current picture of missing person data
        $("#editContainer").show("slow"); // show the current picture div
        $('.btnFormTxt').text('Update') //set the text of the submit btn

        //populate all input fields func
        populateAllFields(data, reportTypes, heightUnits, weightUnits);

        //enable all the fields;
        disableAllInputsAccess(false);

        //scroll to editContainer div
        $('html, body').animate({
            scrollTop: $("#editContainer").offset().top
        }, 2000);

    })
}

// cancel editing function
function cancelEditing() {
    $("#editContainer").hide("slow"); // remove the current picture div
    $('.custom-file-label').html('');
    $('#imgCurrentPicture').prop('src', ''); //remove the src attribute
    $("#imgCurrentPicture").prop("alt", ''); //remove the alt text
    $('.btnFormTxt').text('') //set the text of the submit btn

    //disable all the fields;
    disableAllInputsAccess(true);
}

//delete function
function deleteReport(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/missing-persons/' + id)
    $('#confirmationMessage').text('Do you really want to delete this missing person report data? This process cannot be undone. All of the comments related to this report will be deleted')
}

//change status func
function changeStatusReport(id) {
    $('#changeStatusFormModal').modal('show')
    let actionURL = window.location.origin + '/admin/missing-persons/change-status/' + id;
    $('#changeStatusForm').attr('action', actionURL) //set the method of the form
    $('#changeStatusForm').trigger("reset"); //reset all the values
}


$(document).ready(function () {

    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });

    $('#dataTableComment').DataTable({
        scrollY: "1000px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    $('#dataTableComment').wrap('<div id="scrooll_div"></div >');
    $('#scrooll_div').doubleScroll({
        resetOnWindowResize: true
    });


    $("form[name='reportForm']").validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 100,
            },
            height: {
                required: true,
                number: true,
                min: 1,
                max: 200,
            },
            height_unit: {
                required: true,
            },
            weight: {
                required: true,
                number: true,
                min: 1,
                max: 500,
            },
            weight_unit: {
                required: true,
            },
            age: {
                required: true,
                number: true,
                min: 1,
                max: 200,
            },
            eyes: {
                minlength: 3,
                maxlength: 50,
            },
            hair: {
                minlength: 3,
                maxlength: 50,
            },
            unique_sign: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            unique_sign: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            important_information: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            last_seen: {
                required: true,
                minlength: 3,
                maxlength: 60,
            },
            contact_information: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            report_type: {
                required: true,
            },
            picture: {
                required: false,
                extension: "jpeg|jpg|png",
                filesize: 10485760, //10mb in bytes
            },
        },
        messages: {
            picture: {
                extension: "Invalid file type! picture must be in jpeg|jpg|png format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let formAction = $("#reportForm").attr('action')
            let formData = new FormData(form)

            $('.btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnFormTxt').text('Updating') //set the text of the submit btn
            $('.btnFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success) {
                    const data = response.data

                    //hide image select and button submit
                    cancelEditing();

                    //populate all input fields func
                    populateAllFields(data, null, null, null);

                    //disable all the fields;
                    disableAllInputsAccess(false);

                    //scroll to top
                    $("html, body").animate({ scrollTop: 0 }, 1000);
                }

                $('.btnFormSubmit').attr("disabled", false); //enable the button
                $('.btnFormTxt').text('') //set the text of the submit btn
                $('.btnFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            })
        }
    });

    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()
        let formAction = $("#modalDeleteForm").attr('action')

        $('#btnDelete').attr("disabled", true); //disabled button
        $('.btnDeleteTxt').text('Deleting') //set the text of the submit btn
        $('.btnDeleteLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn

        doAjax(formAction, 'DELETE').then((response) => {

            if (response.success) {
                window.setTimeout(function () {
                    window.location.replace(window.location.origin + '/admin/missing-persons');
                }, 2000);
            }

            $('#btnDelete').attr("disabled", false); //enable button
            $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
            $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
        })

        $('#confirmationDeleteModal').modal('hide') //hide
        $('#modalDeleteForm').trigger("reset"); //reset all the values
    })

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

            $('.btnChangeStatusFormSubmit').attr("disabled", true); //disabled login
            $('.btnChangeStatusFormTxt').text('Updating') //set the text of the submit btn
            $('.btnChangeStatusFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success) {
                    const data = response.data

                    //populate all input fields func
                    populateAllFields(data, null, null, null);

                    $('#changeStatusFormModal').modal('hide')
                }



                $('.btnChangeStatusFormSubmit').attr("disabled", false); //enable the button
                $('.btnChangeStatusFormTxt').text('Update') //set the text of the submit btn
                $('.btnChangeStatusFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            })


        }
    });



})

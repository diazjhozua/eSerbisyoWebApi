const NAV_ORDER = $('#orders_nav');
const TBL_FORMS = $('#certificateFormTable');

const BTN_PICKUP_STATUS = $('#btnChangePickupStatus');
const BTN_PICKUP_DATE = $('#btnChangePickupDate');
const BTN_ADMIN_MESSAGE = $('#btnChangeAdminMessage');
const BTN_APPLICATION_STATUS = $('#btnChangeApplicationStatus');
const BTN_ORDER_STATUS = $('#btnChangeOrderStatus');
const BTN_DELIVERY_PAYMENT = $('#btnDeliveryPayment');
const BTN_RETURN_ITEM = $('#btnReturnItem');

const INPUT_SEL_PICKUP_STATUS = $('#inputSelPickupStatus');
const INPUT_PICKUP_DATE = $('#inputPickupDate');
const INPUT_ADMIN_MESSAGE = $('#inputAdminMessage');
const INPUT_SEL_APPLICATION_STATUS = $('#inputSelApplicationStatus');
const INPUT_SEL_ORDER_STATUS = $('#inputSelOrderStatus');
const INPUT_SEL_DELIVERY_PAYMENT = $('#inputDeliveryPayment');
const INPUT_RETURN_ITEM = $('#inputReturnItem');
const INPUT_ORDER_ID = $('#inputOrderID');


const MODAL_CERTIFICATE = $('#certificateFormModal');
const MODAL_HEADER_CERTIFICATE = $('#certificateFormModalHeader');
const FORM_CERTIFICATE = $('#certificateForm');

const INPUT_FIRST_NAME = $('#inputFirstName');
const INPUT_MIDDLE_NAME = $('#inputMiddleName');
const INPUT_LAST_NAME = $('#inputLastName');
const INPUT_ADDRESS = $('#inputAddress');
const INPUT_SEL_CIVIL = $('#inputSelectCivil');
const INPUT_BIRTHDAY = $('#inputBirthday');
const INPUT_CITIZENSHIP = $('#inputCitizenship');
const INPUT_BIRTHPLACE = $('#inputBirthplace');
const INPUT_PURPOSE = $('#inputPurpose');
const INPUT_BUSINESS_NAME = $('#inputBusinessName');
const INPUT_PROFESSION = $('#inputProfession');
const INPUT_HEIGHT = $('#inputHeight');
const INPUT_WEIGHT = $('#inputWeight');
const INPUT_SEL_SEX = $('#inputSelectSex');
const INPUT_SEL_CEDULA = $('#inputSelectCedula');
const INPUT_TIN = $('#inputTin');
const INPUT_ICR = $('#inputIcr');
const INPUT_BASIC_TAX = $('#inputBasicTax');
const INPUT_ADDITIONAL_TAX = $('#inputAdditionalTax');
const INPUT_GROSS_PROCEEDING = $('#inputGrossProceeding');
const INPUT_GROSS_PROFESSION = $('#inputGrossProfession');
const INPUT_REAL_PROPERTY = $('#inputRealProperty');
const INPUT_INTEREST = $('#inputInterest');
const INPUT_CONTACT_NO = $('#inputContactNo');
const INPUT_CONTACT_PERSON = $('#inputContactPerson');
const INPUT_CONTACT_PERSON_NO = $('#inputContactPersonNo');
const INPUT_CONTACT_PERSON_RELATION = $('#inputContactPersonRelation');

const BTN_CERT_SUBMIT = $('#btnCertFormSubmit');
const BTN_CERT_LOADING = $('#btnCertFormLoadingIcon');
const BTN_CERT_TXT = $('#btnCertFormTxt');

const CONT_INFO = $('.infoContainer');
const CONT_CLEARANCE_INDIGENCY = $('.clearanceIndigencyContainer');
const CONT_CEDULA_IDENTIFICATION = $('.cedulaIdentificationContainer');
const CONT_CEDULA = $('.cedulaContainer');
const CONT_BUSINESS = $('.businessContainer');
const CONT_IDENTIFICATION = $('.identificationContainer');

var pickup_date = null;
var delivery_payment_status = null;
var application_status = null;
var pick_up_type = null;
var order_status = null;
var admin_message = null;
var return_item = null;

var certificateFormID = null;
var certificateID = null;

const isDate = (date) => {
    return (new Date(date) !== "Invalid Date") && !isNaN(new Date(date));
}

function editForm(certFormID) {
    url = window.location.origin + `/admin/certificateForms/${certFormID}/edit`;
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
            const data = response.data;

            certificateFormID = data.id;
            certificateID = data.certificate_id;

            CONT_INFO.hide();
            CONT_CLEARANCE_INDIGENCY.hide();
            CONT_CEDULA.hide();
            CONT_BUSINESS.hide();
            CONT_IDENTIFICATION.hide();
            CONT_CEDULA_IDENTIFICATION.hide();

            INPUT_FIRST_NAME.val(data.first_name);
            INPUT_MIDDLE_NAME.val(data.middle_name);
            INPUT_LAST_NAME.val(data.last_name);
            INPUT_ADDRESS.val(data.address);

            if (data.certificate_id != 5) {
                INPUT_SEL_CIVIL.val(data.civil_status);
                INPUT_BIRTHDAY.val(data.birthday);
                INPUT_CITIZENSHIP.val(data.citizenship);
            }

            if (data.certificate_id == 2 || data.certificate_id == 4) {
                INPUT_BIRTHPLACE.val(data.birthplace);
            }

            switch (data.certificate_id) {
                case 1:
                    // Barangay Indigency
                    CONT_INFO.show();
                    CONT_CLEARANCE_INDIGENCY.show();
                    INPUT_PURPOSE.val(data.purpose);
                    curentCertificate = 'Barangay Indigency';
                    modalHeader = 'Edit Barangay Indigency';
                    break;
                case 2:
                    // Barangay Cedula
                    CONT_INFO.show();
                    CONT_CEDULA.show();
                    CONT_CEDULA_IDENTIFICATION.show();

                    INPUT_PROFESSION.val(data.profession);
                    INPUT_HEIGHT.val(data.height);
                    INPUT_WEIGHT.val(data.weight);
                    INPUT_SEL_SEX.val(data.sex);
                    INPUT_SEL_CEDULA.val(data.cedula_type);
                    INPUT_TIN.val(data.tin_no);
                    INPUT_ICR.val(data.icr_no);
                    INPUT_BASIC_TAX.val(data.basic_tax);
                    INPUT_ADDITIONAL_TAX.val(data.additional_tax);
                    INPUT_GROSS_PROCEEDING.val(data.gross_receipt_preceding);
                    INPUT_GROSS_PROFESSION.val(data.gross_receipt_profession);
                    INPUT_REAL_PROPERTY.val(data.real_property);
                    INPUT_INTEREST.val(data.interest);

                    curentCertificate = 'Barangay Cedula';
                    modalHeader = 'Edit Barangay Cedula';
                    break;
                case 3:
                    // Barangay Clearance
                    CONT_INFO.show();
                    CONT_CLEARANCE_INDIGENCY.show();
                    INPUT_PURPOSE.val(data.purpose);
                    curentCertificate = 'Barangay Clearance';
                    modalHeader = 'Edit Barangay Clearance';
                    break;
                case 4:
                    // Barangay ID
                    CONT_INFO.show();
                    CONT_IDENTIFICATION.show();
                    CONT_CEDULA_IDENTIFICATION.show();

                    INPUT_CONTACT_NO.val(data.contact_no);
                    INPUT_CONTACT_PERSON.val(data.contact_person);
                    INPUT_CONTACT_PERSON_NO.val(data.contact_person_no);
                    INPUT_CONTACT_PERSON_RELATION.val(data.contact_person_relation);

                    curentCertificate = 'Barangay ID';
                    modalHeader = 'Edit Barangay ID';
                    break;
                case 5:
                    // Barangay Business Permit
                    CONT_BUSINESS.show();
                    INPUT_BUSINESS_NAME.val(data.business_name);
                    curentCertificate = 'Barangay Business Permit';
                    modalHeader = 'Edit Barangay Business Permit';
                    break;
            }

            MODAL_CERTIFICATE.modal('show');
            MODAL_HEADER_CERTIFICATE.text(modalHeader);
            BTN_CERT_TXT.text('Update');
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function validate() {
    if (INPUT_SEL_APPLICATION_STATUS.val() == "Approved") {
        if (!isDate(INPUT_PICKUP_DATE.val()) || INPUT_PICKUP_DATE.val() == null) {
            toastr.error('Incorrect Date format');
            return false
        } else {
            pickup_date = INPUT_PICKUP_DATE.val();
        }
    }

    if (INPUT_ADMIN_MESSAGE.val().trim() == '') {
        toastr.error('Please input message');
        return false;
    } else {
        admin_message = INPUT_ADMIN_MESSAGE.val();
    }

    return true;
}


function editApplicationStatus() {
    var formUrl = window.location.origin + `/admin/orders/updateApplicationStatus/${INPUT_ORDER_ID.val()}`;
    console.log(`formUrl`, formUrl);

    var formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('application_status', application_status);

    if (pickup_date != null) {
        formData.append('pickup_date', pickup_date);
    }

    formData.append('admin_message', admin_message);

    $.ajax({
        type: 'POST',
        url: formUrl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        beforeSend: function () {
            toastr.info('Submitting');
            $('#btnApplicationFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text('Updating') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
        },
        success: function (response) {
            toastr.success(response.message);
            pickup_date = null;
            application_status = null;
            pick_up_type = null;
            order_status = null;
            admin_message = null;

            window.location.reload(true);
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);

            if (err.message != null) {
                toastr.error(err.message)

            } else {
                $.each(err.errors, function (key, value) {
                    toastr.error(value)
                });
            }
        },
        complete: function () {
            toastr.info('Request Complete');
            $('#btnApplicationFormSubmit').attr("disabled", false);
            $('.btnTxt').text('Update') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
        }
    });
}

function editOrderStatus() {

    var formUrl = window.location.origin + `/admin/orders/${INPUT_ORDER_ID.val()}`;

    console.log(`formUrl`, formUrl);

    var formData = new FormData();
    formData.append('_method', 'PUT');
    if (order_status != null) {
        formData.append('order_status', order_status);
    } else if (delivery_payment_status != null) {
        formData.append('delivery_payment_status', delivery_payment_status);
    } else if (return_item != null) {
        formData.append('is_returned', return_item);
    }

    $.ajax({
        type: 'POST',
        url: formUrl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        beforeSend: function () {
            toastr.info('Submitting');
        },
        success: function (response) {
            toastr.success(response.message);
            pickup_date = null;
            application_status = null;
            pick_up_type = null;
            order_status = null;
            admin_message = null;

            window.location.reload(true);
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);

            if (err.message != null) {
                toastr.error(err.message)

            } else {
                $.each(err.errors, function (key, value) {
                    toastr.error(value)
                });
            }
        },
        complete: function () {
            toastr.info('Request Complete');
        }
    });
}

$(document).ready(function () {
    NAV_ORDER.addClass('active');

    // Initialize Year picker in form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd", // Notice the Extra space at the beginning
    });

    TBL_FORMS.DataTable({
        scrollY: "700px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    TBL_FORMS.wrap('<div id="scrooll_div"></div >');
    $('#scrooll_div').doubleScroll({
        resetOnWindowResize: true
    });



    BTN_APPLICATION_STATUS.click(function () {
        applicationStat = ['Approved', 'Denied'];

        if (!applicationStat.includes(INPUT_SEL_APPLICATION_STATUS.val())) {
            toastr.error('Please select application status');
        } else {

            $('.pickupDateForm').hide();
            $('.adminRespondForm').hide();
            if (INPUT_SEL_APPLICATION_STATUS.val() == "Approved") {
                $('.adminRespondLabel').text("Add message to the request");
                $('.pickupDateForm').show();
                $('.adminRespondForm').show();
                $('#btnApplicationFormSubmit').show();
            } else if (INPUT_SEL_APPLICATION_STATUS.val() == "Denied") {
                $('.adminRespondLabel').text("Add reason why it is declined");
                $('.adminRespondForm').show();
                $('#btnApplicationFormSubmit').show();
            }
            application_status = INPUT_SEL_APPLICATION_STATUS.val();

        }
    });

    $('#btnApplicationFormSubmit').click(function () {
        if (validate()) {
            editApplicationStatus();
        }
    });

    BTN_ORDER_STATUS.click(function () {
        orderStat = ['Received', 'DNR', 'On-Going'];

        if (!orderStat.includes(INPUT_SEL_ORDER_STATUS.val())) {
            toastr.warning('Please select order status value is incorrect');
        } else {
            order_status = INPUT_SEL_ORDER_STATUS.val();
            editOrderStatus();
        }
    });

    BTN_DELIVERY_PAYMENT.click(function () {
        deliveryPayStat = ['Pending', 'Received'];

        if (!deliveryPayStat.includes(INPUT_SEL_DELIVERY_PAYMENT.val())) {
            toastr.warning('Please select biker payment status value ');
        } else {
            delivery_payment_status = INPUT_SEL_DELIVERY_PAYMENT.val();
            editOrderStatus();
        }
    });

    BTN_RETURN_ITEM.click(function () {
        returnedItem = ['No', 'Yes'];

        if (!returnedItem.includes(INPUT_RETURN_ITEM.val())) {
            toastr.warning('Please select returned item value is incorrect.');
        } else {
            return_item = INPUT_RETURN_ITEM.val();
            editOrderStatus();
        }
    });

    // CERTIFICATE FORM
    FORM_CERTIFICATE.validate({
        // Specify validation rules
        rules: {
            firstName: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            middleName: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            lastName: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            address: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },

            // For all of the certificates except business Permit
            civilStatus: {
                required: function () {
                    return certificateID != 5
                },
            },
            birthday: {
                required: function () {
                    return certificateID != 5
                },
            },
            citizenship: {
                required: function () {
                    return certificateID != 5 && certificateID != 4
                },
                minlength: 4,
                maxlength: 150,
            },
            birthplace: {
                required: function () {
                    return certificateID == 2 || certificateID == 4
                },
                minlength: 4,
                maxlength: 150,
            },

            // for brangay indigency and clearance
            purpose: {
                required: function () {
                    return certificateID == 1 || certificateID == 3
                },
                minlength: 4,
                maxlength: 150,
            },

            // for business clearance
            businessName: {
                required: function () {
                    return certificateID == 5
                },
                minlength: 4,
                maxlength: 150,
            },

            // for cedula
            profession: {
                required: function () {
                    return certificateID == 2
                },
                minlength: 4,
                maxlength: 150,
            },
            height: {
                required: function () {
                    return certificateID == 2
                },
                number: true,
                min: 1,
                max: 200,
            },
            weight: {
                required: function () {
                    return certificateID == 2
                },
                number: true,
                min: 1,
                max: 500,
            },
            sex: {
                required: function () {
                    return certificateID == 2
                },
            },
            cedula: {
                required: function () {
                    return certificateID == 2
                },
            },
            tin: {
                required: false,
                number: true,
                min: 1,
            },
            icr: {
                required: false,
                number: true,
                min: 1,
            },
            basicTax: {
                required: true,
                number: true,
                min: 1,
                max: 5,
            },
            additionalTax: {
                required: false,
                number: true,
                min: 0,
                max: 5000,
            },
            grossProceeding: {
                required: false,
                number: true,
                min: 0,
                max: 5000,
            },
            grossProfession: {
                required: false,
                number: true,
                min: 0,
                max: 5000,
            },
            realProperty: {
                required: false,
                number: true,
                min: 0,
                max: 5000,
            },
            interest: {
                required: false,
                number: true,
                min: 0,
                max: 1,
            },

            // For Business ID
            contactNo: {
                required: function () {
                    return certificateID == 4
                },
                number: true,
                minlength: 11,
                maxlength: 11,
            },
            contactPerson: {
                required: function () {
                    return certificateID == 4
                },
                minlength: 4,
                maxlength: 150,
            },
            contactPersonNo: {
                required: function () {
                    return certificateID == 4
                },
                number: true,
                minlength: 11,
                maxlength: 11,
            },
            contactPersonRelation: {
                required: function () {
                    return certificateID == 4
                },
                minlength: 4,
                maxlength: 150,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            var url = window.location.origin + `/admin/certificateForms/${certificateFormID}/`;

            var formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('certificate_id', certificateID);
            formData.append('first_name', INPUT_FIRST_NAME.val());
            formData.append('middle_name', INPUT_MIDDLE_NAME.val());
            formData.append('last_name', INPUT_LAST_NAME.val());
            formData.append('address', INPUT_ADDRESS.val());

            if (certificateID != 5 && certificateID != 4) {
                formData.append('civil_status', INPUT_SEL_CIVIL.val());
                formData.append('birthday', INPUT_BIRTHDAY.val());
                formData.append('citizenship', INPUT_CITIZENSHIP.val());
            }

            if (certificateID == 4) {
                formData.append('civil_status', INPUT_SEL_CIVIL.val());
                formData.append('birthday', INPUT_BIRTHDAY.val());
            }

            if (certificateID == 2 || certificateID == 4) {
                formData.append('birthplace', INPUT_BIRTHPLACE.val());
            }

            switch (certificateID) {
                case 1:
                    // Barangay Indigency
                    formData.append('purpose', INPUT_PURPOSE.val());
                    break;
                case 2:
                    // Barangay Cedula
                    formData.append('profession', INPUT_PROFESSION.val());
                    formData.append('height', INPUT_HEIGHT.val());
                    formData.append('weight', INPUT_WEIGHT.val());
                    formData.append('sex', INPUT_SEL_SEX.val());
                    formData.append('cedula_type', INPUT_SEL_CEDULA.val());
                    formData.append('tin_no', INPUT_TIN.val());
                    formData.append('icr_no', INPUT_ICR.val());
                    formData.append('basic_tax', INPUT_BASIC_TAX.val());
                    formData.append('additional_tax', INPUT_ADDITIONAL_TAX.val());
                    formData.append('gross_receipt_preceding', INPUT_GROSS_PROCEEDING.val());
                    formData.append('gross_receipt_profession', INPUT_GROSS_PROFESSION.val());
                    formData.append('real_property', INPUT_REAL_PROPERTY.val());
                    formData.append('interest', INPUT_INTEREST.val());

                    break;
                case 3:
                    // Barangay Clearance
                    formData.append('purpose', INPUT_PURPOSE.val());
                    break;
                case 4:
                    // Barangay ID
                    formData.append('contact_no', INPUT_CONTACT_NO.val());
                    formData.append('contact_person', INPUT_CONTACT_PERSON.val());
                    formData.append('contact_person_no', INPUT_CONTACT_PERSON_NO.val());
                    formData.append('contact_person_relation', INPUT_CONTACT_PERSON_RELATION.val());
                    break;
                case 5:
                    // Barangay Business Permit
                    formData.append('business_name', INPUT_BUSINESS_NAME.val());
                    break;
            }

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    toastr.info(`Updating the form`);
                },
                success: function (response) {
                    toastr.success(`${response.message}`);

                    window.setTimeout(function () {
                        window.location.reload();
                    }, 1000);

                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    toastr.info(`Form is finished updating. This page will reload shortly`);
                    MODAL_CERTIFICATE.modal('hide');
                }
            });

        }
    });


});

const NAV_ORDER = $('#orders_nav');

const TBL_CERTIFICATE = $('#dataTable');
const FORM_ORDER = $('#orderForm');
const INPUT_ORDER_NAME = $('#inputOrderName');
const INPUT_ORDER_EMAIL = $('#inputOrderEmail');
const INPUT_ORDER_PHONE = $('#inputOrderPhone');
const INPUT_ORDER_LOCATION = $('#inputOrderLocation');
const BTN_ORDER_SUBMIT = $('#btnOrderFormSubmit');
const BTN_ORDER_LOADING = $('#btnOrderFormLoadingIcon');
const BTN_ORDER_TXT = $('#btnOrderFormTxt');

const SPN_TOTAL_PRICE = $('#totalPrice');

const INPUT_SEL_CERTIFICATE = $('#inputSelCertificate');
const BTN_ADD_CERTIFICATE = $('#btnAddCertificate');

const SPN_ORDERS_COUNT = $('#ordersCount');

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

var totalPrice = 0;
var currentCertID = 0;
var curentCertificate = '';
var currentCertPrice = 0;
var currentAction = '';
var tempCertiList = [];

var deleteTempID;
var editTempID;

function deleteCertificate(tempID) {
    deleteTempID = tempID;
    $('#confirmationDeleteModal').modal('show')
    $('#confirmationMessage').text('Do you really want to delete this certificate order data? This process cannot be undone.')
}

function editCertificate(tempID) {
    editCertificateObj = tempCertiList.filter(certificate => certificate.temp_id == tempID);
    console.log(editCertificateObj);
    editTempID = editCertificateObj[0].temp_id;

    currentAction = 'Edit';

    CONT_INFO.hide();
    CONT_CLEARANCE_INDIGENCY.hide();
    CONT_CEDULA.hide();
    CONT_BUSINESS.hide();
    CONT_IDENTIFICATION.hide();
    CONT_CEDULA_IDENTIFICATION.hide();

    currentCertID = parseInt(editCertificateObj[0].certificate_id);
    currentCertPrice = parseFloat(editCertificateObj[0].certificate_price);

    INPUT_FIRST_NAME.val(editCertificateObj[0].first_name);
    INPUT_MIDDLE_NAME.val(editCertificateObj[0].middle_name);
    INPUT_LAST_NAME.val(editCertificateObj[0].last_name);
    INPUT_ADDRESS.val(editCertificateObj[0].address);

    if (currentCertID != 5) {
        INPUT_SEL_CIVIL.val(editCertificateObj[0].civil_status);
        INPUT_BIRTHDAY.val(editCertificateObj[0].birthday);
        INPUT_CITIZENSHIP.val(editCertificateObj[0].citizenship);
    }

    if (currentCertID == 2 || currentCertID == 4) {
        INPUT_BIRTHPLACE.val(editCertificateObj[0].birthplace);
    }

    switch (currentCertID) {
        case 1:
            // Barangay Indigency
            CONT_INFO.show();
            CONT_CLEARANCE_INDIGENCY.show();
            INPUT_PURPOSE.val(editCertificateObj[0].purpose);
            curentCertificate = 'Barangay Indigency';
            modalHeader = 'Edit Barangay Indigency';
            break;
        case 2:
            // Barangay Cedula
            CONT_INFO.show();
            CONT_CEDULA.show();
            CONT_CEDULA_IDENTIFICATION.show();

            INPUT_PROFESSION.val(editCertificateObj[0].profession);
            INPUT_HEIGHT.val(editCertificateObj[0].height);
            INPUT_WEIGHT.val(editCertificateObj[0].weight);
            INPUT_SEL_SEX.val(editCertificateObj[0].sex);
            INPUT_SEL_CEDULA.val(editCertificateObj[0].cedula_type);
            INPUT_TIN.val(editCertificateObj[0].tin_no);
            INPUT_ICR.val(editCertificateObj[0].icr_no);
            INPUT_BASIC_TAX.val(editCertificateObj[0].basic_tax);
            INPUT_ADDITIONAL_TAX.val(editCertificateObj[0].additional_tax);
            INPUT_GROSS_PROCEEDING.val(editCertificateObj[0].gross_receipt_preceding);
            INPUT_GROSS_PROFESSION.val(editCertificateObj[0].gross_receipt_profession);
            INPUT_REAL_PROPERTY.val(editCertificateObj[0].real_property);
            INPUT_INTEREST.val(editCertificateObj[0].interest);

            curentCertificate = 'Barangay Cedula';
            modalHeader = 'Edit Barangay Cedula';
            break;
        case 3:
            // Barangay Clearance
            CONT_INFO.show();
            CONT_CLEARANCE_INDIGENCY.show();
            INPUT_PURPOSE.val(editCertificateObj[0].purpose);
            curentCertificate = 'Barangay Clearance';
            modalHeader = 'Edit Barangay Clearance';
            break;
        case 4:
            // Barangay ID
            CONT_INFO.show();
            CONT_IDENTIFICATION.show();
            CONT_CEDULA_IDENTIFICATION.show();

            INPUT_CONTACT_NO.val(editCertificateObj[0].contact_no);
            INPUT_CONTACT_PERSON.val(editCertificateObj[0].contact_person);
            INPUT_CONTACT_PERSON_NO.val(editCertificateObj[0].contact_person_no);
            INPUT_CONTACT_PERSON_RELATION.val(editCertificateObj[0].contact_person_relation);

            curentCertificate = 'Barangay ID';
            modalHeader = 'Edit Barangay ID';
            break;
        case 5:
            // Barangay Business Permit
            CONT_BUSINESS.show();
            INPUT_BUSINESS_NAME.val(editCertificateObj[0].business_name);
            curentCertificate = 'Barangay Business Permit';
            modalHeader = 'Edit Barangay Business Permit';
            break;
    }

    MODAL_CERTIFICATE.modal('show');
    MODAL_HEADER_CERTIFICATE.text(modalHeader);
    BTN_CERT_TXT.text('Update');
}


$(document).ready(function () {
    NAV_ORDER.addClass('active');

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

    // if button add certificate was clicked
    BTN_ADD_CERTIFICATE.click(function () {
        if (INPUT_SEL_CERTIFICATE.val() != 'empty') {

            currentAction = 'Add';

            CONT_INFO.hide();
            CONT_CLEARANCE_INDIGENCY.hide();
            CONT_CEDULA_IDENTIFICATION.hide();
            CONT_CEDULA.hide();
            CONT_BUSINESS.hide();
            CONT_IDENTIFICATION.hide();

            let certValue = INPUT_SEL_CERTIFICATE.val().split("|");

            const isFound = tempCertiList.some(element => {
                if (element.certificate_id === parseInt(certValue[0])) {
                    return true;
                }
            });

            if (isFound) {
                toastr.warning('You have already selected this type of document');
            } else {
                currentCertID = parseInt(certValue[0]);
                currentCertPrice = parseFloat(certValue[1]);

                switch (currentCertID) {
                    case 1:
                        // Barangay Indigency
                        CONT_INFO.show();
                        CONT_CLEARANCE_INDIGENCY.show();
                        curentCertificate = 'Barangay Indigency';
                        modalHeader = 'Generate Barangay Indigency';
                        break;
                    case 2:
                        // Barangay Cedula
                        CONT_INFO.show();
                        CONT_CEDULA.show();
                        CONT_CEDULA_IDENTIFICATION.show();
                        curentCertificate = 'Barangay Cedula';
                        modalHeader = 'Generate Barangay Cedula';
                        break;
                    case 3:
                        // Barangay Clearance
                        CONT_INFO.show();
                        CONT_CLEARANCE_INDIGENCY.show();
                        curentCertificate = 'Barangay Clearance';
                        modalHeader = 'Generate Barangay Clearance';
                        break;
                    case 4:
                        // Barangay ID
                        CONT_INFO.show();
                        CONT_IDENTIFICATION.show();
                        CONT_CEDULA_IDENTIFICATION.show();
                        curentCertificate = 'Barangay ID';
                        modalHeader = 'Generate Barangay ID';
                        break;
                    case 5:
                        // Barangay Business Permit
                        CONT_BUSINESS.show();
                        curentCertificate = 'Barangay Business Permit';
                        modalHeader = 'Generate Barangay Business Permit';
                        break;
                }

                MODAL_CERTIFICATE.modal('show');
                MODAL_HEADER_CERTIFICATE.text(modalHeader);
                BTN_CERT_TXT.text('Add');
            }

        } else {
            toastr.error('Please select a certificate!')
        }
    });
    // ------------

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
                    return currentCertID != 5
                },
            },
            birthday: {
                required: function () {
                    return currentCertID != 5
                },
            },
            citizenship: {
                required: function () {
                    return currentCertID != 5 && currentCertID != 4
                },
                minlength: 4,
                maxlength: 150,
            },
            birthplace: {
                required: function () {
                    return currentCertID == 2 || currentCertID == 4
                },
                minlength: 4,
                maxlength: 150,
            },

            // for brangay indigency and clearance
            purpose: {
                required: function () {
                    return currentCertID == 1 || currentCertID == 3
                },
                minlength: 4,
                maxlength: 150,
            },

            // for business clearance
            businessName: {
                required: function () {
                    return currentCertID == 5
                },
                minlength: 4,
                maxlength: 150,
            },

            // for cedula
            profession: {
                required: function () {
                    return currentCertID == 2
                },
                minlength: 4,
                maxlength: 150,
            },
            height: {
                required: function () {
                    return currentCertID == 2
                },
                number: true,
                min: 1,
                max: 200,
            },
            weight: {
                required: function () {
                    return currentCertID == 2
                },
                number: true,
                min: 1,
                max: 500,
            },
            sex: {
                required: function () {
                    return currentCertID == 2
                },
            },
            cedula: {
                required: function () {
                    return currentCertID == 2
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
            },
            additionalTax: {
                required: false,
                number: true,
                min: 1,
            },
            grossProceeding: {
                required: false,
                number: true,
                min: 1,
            },
            grossProfession: {
                required: false,
                number: true,
                min: 1,
            },
            realProperty: {
                required: false,
                number: true,
                min: 1,
            },
            interest: {
                required: false,
                number: true,
                min: 0,
            },

            // For Business ID
            contactNo: {
                required: function () {
                    return currentCertID == 4
                },
                number: true,
                minlength: 11,
                maxlength: 11,
            },
            contactPerson: {
                required: function () {
                    return currentCertID == 4
                },
                minlength: 4,
                maxlength: 150,
            },
            contactPersonNo: {
                required: function () {
                    return currentCertID == 4
                },
                number: true,
                minlength: 11,
                maxlength: 11,
            },
            contactPersonRelation: {
                required: function () {
                    return currentCertID == 4
                },
                minlength: 4,
                maxlength: 150,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            let tempID = Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);

            let certObj = {
                temp_id: tempID,
                certificate_id: currentCertID,
                certificate_price: currentCertPrice,
                first_name: INPUT_FIRST_NAME.val(),
                middle_name: INPUT_MIDDLE_NAME.val(),
                last_name: INPUT_LAST_NAME.val(),
                address: INPUT_ADDRESS.val()
            }


            col0 = '<td>' + curentCertificate + '</td>';
            col1 = '<td>' + currentCertPrice + '</td>';

            col2 = '<td>' +
                `<p><span class="font-weight-bold">First Name: </span>${INPUT_FIRST_NAME.val()}</p></span>` +
                `<p><span class="font-weight-bold">Middle Name: </span>${INPUT_MIDDLE_NAME.val()}</p></span>` +
                `<p><span class="font-weight-bold">Last Name: </span>${INPUT_LAST_NAME.val()}</p></span>` +
                `<p><span class="font-weight-bold">Last Name: </span>${INPUT_ADDRESS.val()}</p></span>`;

            if (currentCertID != 5) {
                col2 = col2 +
                    `<p><span class="font-weight-bold">Civil Status: </span>${INPUT_SEL_CIVIL.val()}</p></span>` +
                    `<p><span class="font-weight-bold">Birthday: </span>${INPUT_BIRTHDAY.val()}</p></span>` +
                    `<p><span class="font-weight-bold">Citizenship: </span>${INPUT_CITIZENSHIP.val()}</p></span>`;

                certObj['civil_status'] = INPUT_SEL_CIVIL.val();
                certObj['birthday'] = INPUT_BIRTHDAY.val();
                certObj['citizenship'] = INPUT_CITIZENSHIP.val();
            }

            if (currentCertID == 2 || currentCertID == 4) {
                col2 = col2 +
                    `<p><span class="font-weight-bold">Birthplace: </span>${INPUT_BIRTHPLACE.val()}</p></span>`;

                certObj['birthplace'] = INPUT_BIRTHPLACE.val();
            }


            switch (currentCertID) {
                case 1:
                    // Barangay Indigency
                    col2 = col2 +
                        `<p><span class="font-weight-bold">Purpose: </span>${INPUT_PURPOSE.val()}</p></span>` +
                        `</td>`;

                    certObj['purpose'] = INPUT_PURPOSE.val();
                    break;
                case 2:
                    // Barangay Cedula
                    col2 = col2 +
                        `<p><span class="font-weight-bold">TIN: </span>${INPUT_TIN.val()}</p></span>` +
                        `<p><span class="font-weight-bold">ICR: </span>${INPUT_ICR.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Profession: </span>${INPUT_PROFESSION.val()} (kg)</p></span>` +
                        `<p><span class="font-weight-bold">Weight: </span>${INPUT_WEIGHT.val()} (kg)</p></span>` +
                        `<p><span class="font-weight-bold">Height: </span>${INPUT_HEIGHT.val()} (ft)</p></span>` +
                        `<p><span class="font-weight-bold">Sex: </span>${INPUT_SEL_SEX.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Cedula Type: </span>${INPUT_SEL_CEDULA.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Basic Community Tax: </span> ₱ ${INPUT_BASIC_TAX.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Additional Community Tax: </span> ₱ ${INPUT_ADDITIONAL_TAX.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Earnings derived from business during the preceding: </span> ₱ ${INPUT_GROSS_PROCEEDING.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Earnings derived from exercise of profession: </span> ₱ ${INPUT_GROSS_PROFESSION.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Income from real property: </span> ₱ ${INPUT_REAL_PROPERTY.val()}</p></span>` +
                        `</td>`;

                    certObj['tin_no'] = INPUT_TIN.val() == null ? null : INPUT_TIN.val();
                    certObj['icr_no'] = INPUT_ICR.val() == null ? null : INPUT_ICR.val();
                    certObj['profession'] = INPUT_PROFESSION.val();
                    certObj['weight'] = INPUT_WEIGHT.val();
                    certObj['height'] = INPUT_HEIGHT.val();
                    certObj['sex'] = INPUT_SEL_SEX.val();
                    certObj['cedula_type'] = INPUT_SEL_CEDULA.val();
                    certObj['basic_tax'] = INPUT_BASIC_TAX.val();
                    certObj['additional_tax'] = INPUT_ADDITIONAL_TAX.val() == null ? null : INPUT_ADDITIONAL_TAX.val();
                    certObj['gross_receipt_preceding'] = INPUT_GROSS_PROCEEDING.val() == null ? null : INPUT_GROSS_PROCEEDING.val();
                    certObj['gross_receipt_profession'] = INPUT_GROSS_PROFESSION.val() == null ? null : INPUT_GROSS_PROFESSION.val();
                    certObj['real_property'] = INPUT_REAL_PROPERTY.val() == null ? null : INPUT_REAL_PROPERTY.val();
                    certObj['interest'] = INPUT_INTEREST.val() == null ? null : INPUT_INTEREST.val();

                    break;
                case 3:
                    // Barangay Clearance
                    col2 = col2 +
                        `<p><span class="font-weight-bold">Purpose: </span>${INPUT_PURPOSE.val()}</p></span>` +
                        `</td>`;

                    certObj['purpose'] = INPUT_PURPOSE.val();
                    break;
                case 4:
                    // Barangay ID
                    col2 = col2 +
                        `<p><span class="font-weight-bold">Contact No: </span>${INPUT_CONTACT_NO.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Contact Person: </span>${INPUT_CONTACT_PERSON.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Contact Person No: </span>${INPUT_CONTACT_PERSON_NO.val()}</p></span>` +
                        `<p><span class="font-weight-bold">Contact Person Relation: </span>${INPUT_CONTACT_PERSON_RELATION.val()}</p></span>` +
                        `</td>`;

                    certObj['contact_no'] = INPUT_CONTACT_NO.val();
                    certObj['contact_person'] = INPUT_CONTACT_PERSON.val();
                    certObj['contact_person_no'] = INPUT_CONTACT_PERSON_NO.val();
                    certObj['contact_person_relation'] = INPUT_CONTACT_PERSON_RELATION.val();

                    break;
                case 5:
                    // Barangay Business Permit
                    col2 = col2 +
                        `<p><span class="font-weight-bold">Business Name: </span>${INPUT_BUSINESS_NAME.val()}</p></span>` +
                        `</td>`;

                    certObj['business_name'] = INPUT_BUSINESS_NAME.val();

                    break;
            }

            editBtn =
                '<li class="list-inline-item mb-1">' +
                `<button class="btn btn-primary btn-sm" onclick="editCertificate('${tempID}')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">` +
                '<i class="fas fa-edit"></i>' +
                '</button>' +
                '</li>';
            deleteBtn =
                '<li class="list-inline-item mb-1">' +
                `<button class="btn btn-danger btn-sm" onclick="deleteCertificate('${tempID}')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">` +
                '<i class="fas fa-trash-alt"></i>' +
                '</button>' +
                '</li>';

            col3 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

            var table = TBL_CERTIFICATE.DataTable();

            if (currentAction == 'Add') {
                var currentPage = table.page();
                table.row.add([col0, col1, col2, col3]).draw()

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
                table.page(currentPage).draw(false);

            } else {
                table.row('.selected').data([col0, col1, col2, col3]).draw(false);

                // means it is editing
                deletingCertificateObj = tempCertiList.filter(certificate => certificate.temp_id == editTempID);
                tempCertiList = tempCertiList.filter(certificate => certificate.temp_id != editTempID);
            }

            totalPrice = totalPrice + currentCertPrice;
            console.log(certObj);
            tempCertiList.push(certObj);

            toastr.success('Certificate Form Added');

            MODAL_CERTIFICATE.modal('hide');
            SPN_TOTAL_PRICE.text(totalPrice);
            SPN_ORDERS_COUNT.text(tempCertiList.length);
        }
    });

    // Order Form
    FORM_ORDER.validate({
        // Specify validation rules
        rules: {
            inputOrderName: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            inputOrderEmail: {
                required: true,
                email: true,
                maxlength: 200,
            },
            inputOrderPhone: {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11,
            },
            inputOrderLocation: {
                required: true,
                minlength: 4,
                maxlength: 500,
            }
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            if (tempCertiList.length == 0) {
                toastr.error('Please add atleast one certificate form to proceed');
            } else {
                var certificate_forms = tempCertiList;

                // Create a new formData for submitting the order to the ajax request
                var formData = new FormData();
                formData.append('name', INPUT_ORDER_NAME.val());
                formData.append('email', INPUT_ORDER_EMAIL.val());
                formData.append('phone_no', INPUT_ORDER_PHONE.val());
                formData.append('location_address', INPUT_ORDER_LOCATION.val());

                for (var i = 0; i < certificate_forms.length; i++) {
                    formData.append('certificate_forms[]', JSON.stringify(certificate_forms[i]));
                }

                // loading start
                BTN_ORDER_SUBMIT.attr("disabled", true); //disabled login
                BTN_ORDER_TXT.text('Submitting'); //set the text of the submit btn
                BTN_ORDER_LOADING.prop("hidden", false); //show the fa loading icon from submit btn

                let isDuplicateOrder;
                var valueArr = certificate_forms.map(function (item) { return item.certificate_id });
                isDuplicateOrder = valueArr.some(function (item, idx) {
                    return valueArr.indexOf(item) != idx;
                });

                if (isDuplicateOrder) {
                    toastr.error('Duplicate entry of certificate');
                } else {
                    let formAction = $("#orderForm").attr('action')
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

                        success: function (response) {
                            toastr.success(response.message);

                            window.setTimeout(function () {
                                window.location.replace(`${window.location.origin}/admin/orders/${response.data.id}`);
                            }, 2000);

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
                            BTN_ORDER_SUBMIT.attr("disabled", false);
                            BTN_ORDER_TXT.text('Submit');
                            BTN_ORDER_LOADING.prop("hidden", true);
                        }
                    });
                }
            }
        }
    });

    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()

        deletingCertificateObj = tempCertiList.filter(certificate => certificate.temp_id == deleteTempID);
        console.log(deletingCertificateObj);

        tempCertiList = tempCertiList.filter(certificate => certificate.temp_id != deleteTempID);

        totalPrice = totalPrice - deletingCertificateObj[0].certificate_price;
        SPN_TOTAL_PRICE.text(totalPrice);
        SPN_ORDERS_COUNT.text(tempCertiList.length);

        toastr.success('Order Deleted Successfuly');

        var table = $('#dataTable').DataTable();
        $('.selected').fadeOut(800, function () {
            table.row('.selected').remove().draw();
        });

        $('#confirmationDeleteModal').modal('hide') //hide


    })
});



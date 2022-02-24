
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

// initialize complainant form
const complainantFormModal = $('#complainantFormModal');
const complainantFormModalHeader = $('#complainantFormModalHeader');
const complainantForm = $('#complainantForm');
const complainantFormMethod = $("#complainantFormMethod");

// complainant form button
const btnComplainantFormSubmit = $('.btnComplainantFormSubmit');
const btnComplainantFormLoadingIcon = $('.btnComplainantFormLoadingIcon');
const btnComplainantFormBtnTxt = $('.btnComplainantFormBtnTxt');

// initialize defendant form
const defendantFormModal = $('#defendantFormModal');
const defendantFormModalHeader = $('#defendantFormModalHeader');
const defendantForm = $('#defendantForm');
const defendantFormMethod = $("#defendantFormMethod");
// defendant form button
const btnDefendantFormSubmit = $('.btnDefendantFormSubmit');
const btnDefendantFormLoadingIcon = $('.btnDefendantFormLoadingIcon');
const btnDefendantFormBtnTxt = $('.btnDefendantFormBtnTxt');

// DataTable
var complainantDataTbl = $('#dataTableComplainant');
var defendantDataTbl = $('#dataTableDefendant');

// defendant and complainant form input fields
const inputDefendantName = $('#inputDefendantName');
const inputComplainantName = $('#inputComplainantName');

// Current Row When Selected in Data Table;
var tableSelected;

// initialize delete form
const deleteFormModal = $('#confirmationDeleteModal');
const deleteForm = $('#modalDeleteForm');
const btnDeleteFormSubmit = $('#btnDelete');
const btnDeleteFormTxt = $('.btnDeleteTxt');
const btnDeleteFormLoadingIcon = $('.btnDeleteLoadingIcon');

// initialize report form
const reportForm = $('#reportForm');
const inputContactID = $('#contact_user_id');
const inputEmail = $('#email');
const inputPhone = $('#phone_no');
const inputSelectType = $('#type_id');
const inputTxtReason = $('#reason');
const inputTxtAction = $('#action');

const btnReportFormSubmit = $('.btnReportFormSubmit');
const btnReportFormTxt = $('.btnReportFormTxt');
const btnReportFormLoadingIcon = $('.btnReportFormLoadingIcon');

function modifyComplainantModal(title, btnText, inputMethod, actionURL, complainantName, signature) {
    complainantFormModal.modal('show') //show the modal
    complainantFormModalHeader.text(title) //set the header of the
    btnComplainantFormBtnTxt.text(btnText) //set the text of the submit btn
    complainantForm.trigger("reset"); //reset all the values
    complainantFormMethod.empty();
    complainantFormMethod.append(inputMethod) // append formMethod
    complainantForm.attr('action', actionURL) //set the method of the form

    inputComplainantName.val(complainantName); // set name

    if (signature != null) {
        signaturePad.fromDataURL(signature);
    }

}

function modifyDefendantModal(title, btnText, inputMethod, actionURL, defendantName) {
    defendantFormModal.modal('show') //show the modal
    defendantFormModalHeader.text(title) //set the header of the
    btnDefendantFormBtnTxt.text(btnText) //set the text of the submit btn
    defendantForm.trigger("reset"); //reset all the values
    defendantFormMethod.empty();
    defendantFormMethod.append(inputMethod) // append formMethod
    defendantForm.attr('action', actionURL) //set the method of the form

    inputDefendantName.val(defendantName); // set name

}

function addComplainant() {
    let inputMethod = '<input type="hidden" id="complainantMethod" name="_method" value="POST">';
    // Clears the canvas
    signaturePad.clear();

    modifyComplainantModal('Add Complainant', 'Add', inputMethod, null, null, null)
}

function editComplainant(complainantName, signature) {
    let inputMethod = '<input type="hidden" id="complainantMethod" name="_method" value="PUT">';
    modifyComplainantModal('Edit Complainant', 'Update', inputMethod, null, complainantName, signature)
}

function addDefendant() {
    let inputMethod = '<input type="hidden" id="defendantMethod" name="_method" value="POST">';
    modifyDefendantModal('Add Defendant', 'Add', inputMethod, null, null)
}

function editDefendant(defendantName) {
    let inputMethod = '<input type="hidden" id="defendantMethod" name="_method" value="PUT">';
    modifyDefendantModal('Edit Defendant', 'Update', inputMethod, null, defendantName);
}

function deleteDefendant() {
    $('#confirmationDeleteModal').modal('show')
    tableSelected = 'Defendant';
    $('#modalDeleteForm').attr('action', null)
    $('#confirmationMessage').text('Do you really want to delete this defendant data? This process cannot be undone.')
}

function deleteComplainant() {
    $('#confirmationDeleteModal').modal('show')
    tableSelected = 'Complainant';
    $('#modalDeleteForm').attr('action', null)
    $('#confirmationMessage').text('Do you really want to delete this complainant data? This process cannot be undone.')
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

function addOrReplaceDefendant(defendantName, addOrReplace) {
    col0 = '<td>' + defendantName + '</td>';

    editBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="editDefendant(\'' + defendantName + '\') " type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
        '<i class="fas fa-edit"></i>' +
        '</button>' +
        '</li>'

    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="deleteDefendant()" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<i class="fas fa-trash-alt"></i>' +
        '</button>' +
        '</li>'

    col1 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    let table = $('#dataTableDefendant').DataTable();
    let colList = [col0, col1];

    addOrReplaceToTable(table, colList, addOrReplace)
}

function addOrReplaceComplainant(complainantName, signature, addOrReplace) {

    console.log(`Table signature: ${signature}`);
    col0 = '<td>' + complainantName + '</td>';
    var image = new Image();
    image.src = signature;
    image.style = "height:100px; width: 100%;";
    image.className = 'rounded';
    image.alt = complainantName + ' signature';

    col1 =
        '<td>' +
        image.outerHTML +
        '</td>'

    editBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="editComplainant(\'' + complainantName
        + '\',\'' + signature + '\') " type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
        '<i class="fas fa-edit"></i>' +
        '</button>' +
        '</li>'

    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="deleteComplainant()" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<i class="fas fa-trash-alt"></i>' +
        '</button>' +
        '</li>'

    col2 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTableComplainant').DataTable();
    var colList = [col0, col1, col2];

    addOrReplaceToTable(table, colList, addOrReplace)
}



$(document).ready(function () {
    $('#complaint').addClass('active')

    // initialize dataTable
    complainantDataTbl.DataTable({
        scrollY: "480px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    complainantDataTbl.wrap('<div class="scrooll_div"></div >');

    defendantDataTbl.DataTable({
        scrollY: "480px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    defendantDataTbl.wrap('<div class="scrooll_div"></div >');

    $('.scrooll_div').doubleScroll({
        resetOnWindowResize: true
    });

    // Set class row selected when any button was click in the selected
    defendantDataTbl.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            defendantDataTbl.DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    complainantDataTbl.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            defendantDataTbl.DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })


    //Defendant Form Validation
    defendantForm.validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            let formMethod = $('#defendantMethod').val()

            btnDefendantFormSubmit.attr("disabled", true); //disabled login
            btnDefendantFormBtnTxt.text(formMethod == 'POST' ? 'Adding' : 'Updating'); //set the text of the submit btn
            btnDefendantFormLoadingIcon.prop("hidden", false); //show the fa loading icon from submit btn

            setTimeout(function () {
                btnDefendantFormSubmit.attr("disabled", false);
                btnDefendantFormBtnTxt.text(formMethod == 'POST' ? 'Add' : 'Update');
                btnDefendantFormLoadingIcon.prop("hidden", true);
            }, 1000);

            defendantFormModal.modal('hide');
            toastr.success(formMethod == 'POST' ? 'Defendant Added' : 'Defendant Updated');

            // get the name in the defendant form
            let defendantName = inputDefendantName.val();

            addOrReplaceDefendant(defendantName, formMethod == 'POST' ? 'Add' : 'Replace')
        }
    });

    //Defendant Form Validation
    complainantForm.validate({
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
                let formMethod = $('#complainantMethod').val()

                btnComplainantFormSubmit.attr("disabled", true); //disabled login
                btnComplainantFormBtnTxt.text(formMethod == 'POST' ? 'Adding' : 'Updating'); //set the text of the submit btn
                btnComplainantFormLoadingIcon.prop("hidden", false); //show the fa loading icon from submit btn

                setTimeout(function () {
                    btnComplainantFormSubmit.attr("disabled", false);
                    btnComplainantFormBtnTxt.text(formMethod == 'POST' ? 'Add' : 'Update');
                    btnComplainantFormLoadingIcon.prop("hidden", true);
                }, 1000);


                // get the name and image in the complainant form
                let complainantName = inputComplainantName.val();

                console.log(`Signature : ${signature}`)

                addOrReplaceComplainant(complainantName, signature, formMethod == 'POST' ? 'Add' : 'Replace')

                complainantFormModal.modal('hide');
                toastr.success(formMethod == 'POST' ? 'Complainant Added' : 'Complainant Updated');

            }
        }
    });

    reportForm.validate({
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
                maxlength: 30,
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

            var complainant_list = [];
            var defendant_list = [];

            let table;
            let data;


            // get the values for each row in defendant table
            table = defendantDataTbl.dataTable().DataTable();
            data = table.rows().data();
            data.each(function (value, index) {
                let td = document.createElement('td');

                td.innerHTML = value[0]; //first td in each row (name)

                defendantObj = {
                    "name": td.textContent,
                };

                defendant_list.push(defendantObj);
            });

            // get the values for each row in complainant table
            table = complainantDataTbl.dataTable().DataTable();
            data = table.rows().data();
            data.each(function (value, index) {
                let td = document.createElement('td');
                let td1 = document.createElement('td');

                td.innerHTML = value[0]; //first td in each row (name)

                td1.innerHTML = value[1]; //second td in each row (signature)

                var childs = td1.childNodes; //get the image document from td1
                console.log();
                //
                complainantObj = {
                    "name": td.textContent,
                    "signature": childs[0].src.replace("data:image/jpeg;base64,", ""),
                };

                complainant_list.push(complainantObj);
            });

            // Create a new formData for submitting the report to the ajax request

            var formData = new FormData();
            formData.append('type_id', inputSelectType.val());
            formData.append('contact_user_id', inputContactID.val());
            formData.append('email', inputEmail.val());
            formData.append('phone_no', inputPhone.val());
            formData.append('reason', inputTxtReason.val());
            formData.append('action', inputTxtAction.val());

            for (var i = 0; i < complainant_list.length; i++) {

                formData.append(`complainant_list[${i}][name]`, complainant_list[i]["name"]);
                formData.append(`complainant_list[${i}][signature]`, complainant_list[i]["signature"]);
            }

            for (var i = 0; i < defendant_list.length; i++) {
                formData.append(`defendant_list[${i}][name]`, defendant_list[i]["name"]);
            }

            let formAction = $("#reportForm").attr('action')

            // loading start
            btnReportFormSubmit.attr("disabled", true); //disabled login
            btnReportFormTxt.text('Submitting'); //set the text of the submit btn
            btnReportFormLoadingIcon.prop("hidden", false); //show the fa loading icon from submit btn

            let isDuplicateDefendant;
            var valueArr = defendant_list.map(function (item) { return item.name });
            isDuplicateDefendant = valueArr.some(function (item, idx) {
                return valueArr.indexOf(item) != idx;
            });

            let isDuplicateComplainant;
            var valueArr = complainant_list.map(function (item) { return item.name });
            isDuplicateComplainant = valueArr.some(function (item, idx) {
                console.log(valueArr.indexOf(item) != idx)
                return valueArr.indexOf(item) != idx;
            });

            if (isDuplicateDefendant) {
                toastr.error('Duplicate entry name of defendant list');
            } else if (isDuplicateComplainant) {
                toastr.error('Duplicate entry name of complainant list');
            } else {
                // Ajax Request for creating of document
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
                        toastr.success('Complaint successfully added');

                        if (response.success) {
                            window.setTimeout(function () {
                                window.location.replace(window.location.origin + '/admin/complaints');
                            }, 2000);
                        }

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
                        btnReportFormSubmit.attr("disabled", false);
                        btnReportFormTxt.text('Submit');
                        btnReportFormLoadingIcon.prop("hidden", true);
                    }
                });
            }



        }
    });

    // Delete Modal Form
    deleteForm.submit(function (e) {
        e.preventDefault()

        btnDeleteFormSubmit.attr("disabled", true); //disabled button
        btnDeleteFormTxt.text('Deleting') //set the text of the submit btn
        btnDeleteFormLoadingIcon.prop("hidden", false) //show the fa loading icon from delete btn

        setTimeout(function () {
            btnDeleteFormSubmit.attr("disabled", false);
            btnDeleteFormTxt.text('Deleting');
            btnDeleteFormLoadingIcon.prop("hidden", true);
        }, 1000);

        // remove the selected row
        $('.selected').fadeOut(800, function () {
            if (tableSelected == 'Defendant') {
                defendantDataTbl.row('.selected').remove().draw();
            } else {
                complainantDataTbl.row('.selected').remove().draw();
            }
        });

        deleteFormModal.modal('hide') //hide
        deleteForm.trigger("reset"); //reset all the values
        toastr.success(tableSelected == 'Defendant' ? 'Defendant Deleted' : 'Complainant Deleted');
    })


})


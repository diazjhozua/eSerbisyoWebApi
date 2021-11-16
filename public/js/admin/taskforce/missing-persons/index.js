function createReport() {
    let actionURL = '/admin/complaint-types/'
    let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'
    $('#reportFormModal').modal('show') //show the modal
    $('#reportFormModalHeader').text('Publish Missing Report') //set the header of the
    $('.btnFormTxt').text('Store') //set the text of the submit btn
    $('#reportForm').trigger("reset"); //reset all the values
    $("#formMethod").empty();
    $("#formMethod").append(inputMethod) // append formMethod
    $('#reportForm').attr('action', actionURL) //set the method of the form
}

$(document).ready(function () {

    $('#missingPerson').addClass('active')

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
                required: function () {
                    return $('#method').val() == 'POST'
                },
                extension: "jpeg|jpg|png",
                filesize: 10485760, //10mb in bytes
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            // let formAction = $("#reportForm").attr('action')
            // let formData = new FormData(form)

            // $('.btnRespondReport').attr("disabled", true); //disabled login
            // $('.btnRespondReportTxt').text($('#statusSelect').val() == 'Noted' ? 'Noting' : 'Invalidating') //set the text of the submit btn
            // $('.btnRespondReportLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            // doAjax(formAction, 'POST', formData).then((response) => {
            //     if (response.success) {
            //         const data = response.data
            //         addOrReplace(data, 'Replace')

            //         let reportDate = new Date(data.created_at);
            //         let currentDate = new Date();

            //         if (reportDate.getFullYear() == currentDate.getFullYear() && currentDate.getMonth() == reportDate.getMonth()) {
            //             //means the date is in this current month
            //             $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) - 1);

            //             switch (data.status) {
            //                 case 'Noted':
            //                     $("#thisMonthNotedCount").text(parseInt($("#thisMonthNotedCount").text()) + 1);
            //                     break;
            //                 case 'Invalid':
            //                     $("#thisMonthInvalidCount").text(parseInt($("#thisMonthInvalidCount").text()) + 1);
            //                     break;
            //             }
            //         }
            //     }

            //     $('.btnRespondReport').attr("disabled", false); //enable the button
            //     $('.btnRespondReportTxt').text('Respond') //set the text of the submit btn
            //     $('.btnRespondReportLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            // })


        }
    });

})

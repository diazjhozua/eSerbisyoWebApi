
function demote(id) {
    let actionURL = window.location.origin + '/admin/bikers/demote/' + id

    $('#changeRoleForm').attr('action', actionURL) //set the method of the form
    $('#changeRoleForm').trigger("reset")

    $("#iconBoxLogo").attr('class', 'fas fa-level-down-alt') //change font awesome icon to disable
    // change color
    $(".modal-confirm .icon-box").css("border", "3px solid #f15e5e");
    $(".modal-confirm .icon-box i").css("color", "#f15e5e");

    $('#confirmationMessage').text('Do you really want to restrict this staff? Once the information staff is demoted, that specific user would not have any access in this admin dashboard')
    $('.btnChangeRoleTxt').text('Demote');
    $('.btnChangeRole').removeClass('btn-success').addClass('btn-danger');

    $('#changeRoleModal').modal('show') //show bootstrap modal
}


$(document).ready(function () {
    $('#bikerCollapse').addClass('active');
    $('#collapseBiker').collapse();
    $('#biker_nav').addClass('active');

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    $("#changeRoleForm").submit(function (e) {
        e.preventDefault()
        $('.selected').addClass('demoting');
        let ajaxURL = $("#changeRoleForm").attr('action')

        // Change Role Ajax Request
        $.ajax({
            type: 'PUT',
            url: ajaxURL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#btnChangeRoleFormSubmit').attr("disabled", true); //disabled button
                $('.btnChangeRoleTxt').text('Demoting') //set the text of the submit btn
                $('.btnChangeRoleLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn

            },
            success: function (response) {
                toastr.success(response.message);

                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement bikerCount
                $("#bikerCount").text(parseInt($("#bikerCount").text()) - 1);
            },
            error: function (xhr) {
                var error = JSON.parse(xhr.responseText);

                // show error message from helper.js
                ajaxErrorMessage(error);
            },
            complete: function () {
                $('#btnChangeRoleFormSubmit').attr("disabled", false)
                $('.btnChangeRoleTxt').text('Demote')
                $('.btnChangeRoleLoadingIcon').prop("hidden", true)

                $('#changeRoleModal').modal('hide') //hide
                $('#changeRoleForm').trigger("reset"); //reset all the values
            }
        });
    })
})

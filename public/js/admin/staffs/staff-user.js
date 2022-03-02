var formData = new FormData();

function promote(id) {

    $('#positionSelectContainer').hide();

    let actionURL = window.location.origin + '/admin/staffs/promote-users/' + id

    $('#changeRoleForm').attr('action', actionURL) //set the method of the form
    $('#changeRoleForm').trigger("reset")

    $("#iconBoxLogo").attr('class', 'fas fa-level-up-alt') //change font awesome icon to disable
    // change color
    $(".modal-confirm .icon-box").css("border", "3px solid #42ba96");
    $(".modal-confirm .icon-box i").css("color", "#42ba96");

    $('#confirmationMessage').text('Do you really want to promote this user? Once the user is promoted to staff, that specific user would have access in the admin dashboard system')
    $('.btnChangeRoleTxt').text('Promote');
    $('.btnChangeRole').removeClass('btn-success').addClass('btn-success');

    $('#changeRoleModal').modal('show') //show bootstrap modal

}

function promoteAnyUser(id) {
    $('#positionSelectContainer').show();

    let actionURL = window.location.origin + '/admin/staffs/promote/' + id

    $('#changeRoleForm').attr('action', actionURL) //set the method of the form
    $('#changeRoleForm').trigger("reset")

    $("#iconBoxLogo").attr('class', 'fas fa-level-up-alt') //change font awesome icon to disable
    // change color
    $(".modal-confirm .icon-box").css("border", "3px solid #42ba96");
    $(".modal-confirm .icon-box i").css("color", "#42ba96");

    $('#confirmationMessage').text('Do you really want to promote this user? Once the user is promoted to any type of admin or staff, that specific user would have access in accordance to their respective role.')
    $('.btnChangeRoleTxt').text('Promote');
    $('.btnChangeRole').removeClass('btn-success').addClass('btn-success');

    $('#changeRoleModal').modal('show') //show bootstrap modal
}

$(document).ready(function () {
    var selectedButton;
    $('.btnPromote').click(function () {
        selectedButton = $(this)

    });

    $('#OfficialAdminCollapse').addClass('active')
    $('#collapseOfficialAdmin').collapse()
    $('#promoteUserItem').addClass('active')

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Change Role Form

    $("#changeRoleForm").submit(function (e) {
        e.preventDefault()
        $('.selected').addClass('promoting');
        let ajaxURL = $("#changeRoleForm").attr('action');

        formData.append('user_role_id', $('#inputSelPosID').val());
        formData.append('_method', 'PUT');

        // Change Role Ajax Request
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
                $('#btnChangeRoleFormSubmit').attr("disabled", true); //disabled button
                $('.btnChangeRoleTxt').text('Promoting') //set the text of the submit btn
                $('.btnChangeRoleLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn

            },
            success: function (response) {
                toastr.success(response.message);

                var table = $('#dataTable').DataTable();
                $('.promoting').fadeOut(800, function () {
                    table.row('.promoting').remove().draw();
                });

                // decrement userCount
                $("#userCount").text(parseInt($("#userCount").text()) - 1);

                // increment staffCount
                $("#staffCount").text(parseInt($("#staffCount").text()) + 1);
            },
            error: function (xhr) {
                var error = JSON.parse(xhr.responseText);

                // show error message from helper.js
                ajaxErrorMessage(error);
            },
            complete: function () {
                $('#btnChangeRoleFormSubmit').attr("disabled", false)
                $('.btnChangeRoleTxt').text('Promote')
                $('.btnChangeRoleLoadingIcon').prop("hidden", true)

                $('#changeRoleModal').modal('hide') //hide
                $('#changeRoleForm').trigger("reset"); //reset all the values
            }
        });
    })

})

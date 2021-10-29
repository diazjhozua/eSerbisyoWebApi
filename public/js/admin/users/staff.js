function demote(id) {
    let actionURL = '/admin/staffs/demoteStaff/' + id
    console.log(id)
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
    $('#OfficialAdminCollapse').addClass('active')
    $('#collapseOfficialAdmin').collapse()
    $('#demoteStaffItem').addClass('active')

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Delete Modal Form
    $("#changeRoleForm").submit(function (e) {
        e.preventDefault()
        let formAction = $("#changeRoleForm").attr('action')

        $('#btnChangeRoleFormSubmit').attr("disabled", true); //disabled button
        $('.btnChangeRoleTxt').text('Demoting') //set the text of the submit btn
        $('.btnChangeRoleLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn

        doAjax(formAction, 'PUT').then((response) => {
            if (response.success) {
                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement userCount
                $("#userCount").text(parseInt($("#userCount").text()) - 1);
            }

            $('#btnChangeRoleFormSubmit').attr("disabled", false)
            $('.btnChangeRoleTxt').text('Promote')
            $('.btnChangeRoleLoadingIcon').prop("hidden", true)
        })
        $('#changeRoleModal').modal('hide') //hide
        $('#changeRoleForm').trigger("reset"); //reset all the values
    })

})

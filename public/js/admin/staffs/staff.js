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
    var selectedButton;
    $('.btnDemote').click(function () {
        selectedButton = $(this)
    });

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
        $('.selected').addClass('demoting');
        let formAction = $("#changeRoleForm").attr('action')

        $('#btnChangeRoleFormSubmit').attr("disabled", true); //disabled button
        $('.btnChangeRoleTxt').text('Demoting') //set the text of the submit btn
        $('.btnChangeRoleLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn

        selectedButton.attr("disabled", true);
        selectedButton.children(".btnDemoteTxt").text('Demoting')
        selectedButton.children(".btnDemoteIcon").prop("hidden", true)
        selectedButton.children(".btnDemoteLoadingIcon").prop("hidden", false)


        doAjax(formAction, 'PUT').then((response) => {
            if (response.success) {
                var table = $('#dataTable').DataTable();
                $('.demoting').fadeOut(800, function () {
                    table.row('.demoting').remove().draw();
                });
                // decrement staffCount
                $("#staffCount").text(parseInt($("#staffCount").text()) - 1);
            }

            $('#btnChangeRoleFormSubmit').attr("disabled", false)
            $('.btnChangeRoleTxt').text('Demote')
            $('.btnChangeRoleLoadingIcon').prop("hidden", true)
        })
        $('#changeRoleModal').modal('hide') //hide
        $('#changeRoleForm').trigger("reset"); //reset all the values
    })

})

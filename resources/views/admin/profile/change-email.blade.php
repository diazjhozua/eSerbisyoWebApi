<!-- Create/Edit Document Modal -->
<div class="modal fade" id="changeEmailFormModal" tabindex="-1" role="dialog" aria-labelledby="changeEmailFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="changeEmail">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="changeEmailFormModalHeader" class="modal-title">Change Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="changeEmailForm" id="changeEmailForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <input type="hidden" name="_method" value="PUT">

                <div class="modal-body">

                    <div class="alert alert-info mb-2" style="font-size: 15px" role="alert">
                        Please make sure that the new email should be working and you have access to that account.
                        Your email can be used to reset your password without logging in.
                    </div>

                    <div class="form-group">
                        {{-- Current Email Field --}}
                        <label>Current Email</label>
                        <input type="email" class="form-control" id="current_email" name="current_email"/>
                    </div>

                    <div class="form-group">
                        {{-- New Email Field --}}
                        <label>New Email</label>
                        <input type="email" class="form-control" id="new_email" name="new_email"/>
                    </div>

                    <div class="form-group">
                        {{-- Confirm New Email Field --}}
                        <label>Confirm Email</label>
                        <input type="email" class="form-control" id="new_confirm_email" name="new_confirm_email"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnChangeEmailFormSubmit" type="submit" class="btn btn-primary">
                        <i class="loadingIcon fa fa-spinner fa-spin" id="btnChangeEmailLoading" hidden></i>
                        <span class="btnTxt" id="btnChangeEmailTxt">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

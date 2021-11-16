<!-- Create/Edit Document Modal -->
<div class="modal fade" id="changePasswordFormModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="changePassword">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="changePasswordFormModalHeader" class="modal-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="changePasswordForm" id="changePasswordForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <input type="hidden" name="_method" value="PUT">

                <div class="modal-body">

                    <div class="form-group">
                        {{-- Current Password Field --}}
                        <label>Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password"/>
                    </div>

                    <div class="form-group">
                        {{-- New Password Field --}}
                        <label>New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password"/>
                    </div>

                    <div class="form-group">
                        {{-- Confirm New Password Field --}}
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" id="new_confirm_password" name="new_confirm_password"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnChangePasswordFormSubmit" type="submit" class="btn btn-primary">
                        <i class="loadingIcon fa fa-spinner fa-spin" id="btnChangePasswordLoading" hidden></i>
                        <span class="btnTxt" id="btnChangePasswordTxt">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

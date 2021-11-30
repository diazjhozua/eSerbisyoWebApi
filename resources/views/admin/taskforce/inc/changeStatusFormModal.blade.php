<!-- Change Status of  Missing Person Report Modal -->
<div class="modal fade bd-example" id="changeStatusFormModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="announcement">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="reportFormModalHeader" class="modal-title">Change Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="changeStatusForm" id="changeStatusForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <input type="hidden" name="_method" value="PUT">

                <div class="modal-body">


                    <div class="row mt-3 mb-2 mt-lg-0">

                        <div class="col">
                            {{-- Weight Unit --}}
                            <label for="status">Status</label>
                            <select class="custom-select" id="statusSelect" name="status">
                                <option value="" selected>Choose...</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Denied">Denied</option>
                            </select>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="admin_message" class="form-label">Respond Message</label>
                        <textarea class="form-control" name="admin_message" id="admin_message" rows="5"></textarea>
                        <div class="form-text">Input here your message (The user who created this report will receive this message through email)</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnChangeStatusFormSubmit">
                        <i class="btnChangeStatusFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnChangeStatusFormTxt">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

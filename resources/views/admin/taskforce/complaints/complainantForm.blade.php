<!-- Create/Edit Announcement Modal -->
<div class="modal fade" id="complainantFormModal" tabindex="-1" role="dialog" aria-labelledby="complainantFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="announcementPicture">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="complainantFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="complainantForm" id="complainantForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="complainantFormMethod"></div>
                <input type="hidden" name="complaint_id" id="complaint_id">

                <div class="modal-body">
                    {{-- Complainant Name --}}
                    <div class="form-group">
                        <label for="name">Complainant Name</label>
                        <input type="text" class="form-control" name="name" id="inputComplainantName">
                    </div>

                    {{-- Complainant Signature --}}
                    <div class="form-group">
                        <label for="signature">Complainant Signature</label>
                        <div id="signature-pad" class="signature-pad text-center">
                            <div class="signature-pad--body">
                                <canvas></canvas>
                            </div>
                            <div class="signature-pad--footer">

                            <div class="description text-muted mb-2">Sign above</div>

                                <div class="signature-pad--actions">
                                    <div>
                                        <button type="button" class="btn btn-info btn-sm mr-2" data-action="clear">Clear</button>
                                        <button type="button" class="btn btn-warning btn-sm mr-2" data-action="change-color">Change color</button>
                                        <button type="button" class="btn btn-dark btn-sm mr-2" data-action="undo">Undo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnComplainantFormSubmit">
                        <i class="btnComplainantFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnComplainantFormBtnTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create/Edit Announcement Modal -->
<div class="modal fade" id="defendantFormModal" tabindex="-1" role="dialog" aria-labelledby="defendantFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="announcementPicture">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="defendantFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="defendantForm" id="defendantForm" action="" method="POST" novalidate>

                <div id="defendantFormMethod"></div>
                <input type="hidden" name="complaint_id" id="complaint_id">

                <div class="modal-body">
                    {{-- Defendant Name --}}
                    <div class="form-group">
                        <label for="name">Defendant Name</label>
                        <input type="text" class="form-control" name="name" id="inputDefendantName">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnDefendantFormSubmit">
                        <i class="btnDefendantFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnDefendantFormBtnTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

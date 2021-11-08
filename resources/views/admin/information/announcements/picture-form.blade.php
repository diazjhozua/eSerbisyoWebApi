<!-- Create/Edit Announcement Modal -->
<div class="modal fade" id="announcementPictureModal" tabindex="-1" role="dialog" aria-labelledby="announcementPictureModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="announcementPicture">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="announcementPictureModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="announcementPictureForm" id="announcementPictureForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formPictureMethod"></div>

                <input type="hidden" name="announcement_id" id="announcement_id">

                <div class="row m-2">
                    <div class="col-sm-8">
                        {{-- Picture Upload --}}
                        <div class="form-group">
                            <label>Select picture</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="picture" name="picture">
                                <label class="custom-file-label" for="picture">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4" id="currentPictureDiv">
                            <small class="form-text text-muted">Current Picture</small>
                        <img id="imgCurrentPicture" style="height:100px; max-height: 100px; max-width:100px; width: 100px;" src="" class="rounded" alt="Mr. Moises Huel image">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnFormSubmit">
                        <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

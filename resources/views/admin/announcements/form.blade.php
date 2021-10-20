<!-- Create/Edit Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-labelledby="announcementModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="announcement">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="announcementModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="announcementForm" id="announcementForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">

                    {{-- Title --}}
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>

                    {{-- Announcement Type Select Option --}}
                    <div class="form-group">
                        <label for="announcementTypeDropDwn">Announcement Type</label>
                        <select class="custom-select" name="type_id" id="announcementTypeDropDwn">
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                    </div>

                    {{-- Multiple File Upload Announcement Pictures --}}
                    <div id="fileUploadContainer">
                        <div class="form-group valign">
                            <label>Select pictures (No Video File)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="picture_list[]" multiple>
                                <label class="custom-file-label" for="pdf">Choose pictures</label>
                            </div>
                            {{-- This will show the selected pictures --}}
                            <div class="selectedText">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnFormSubmit" type="submit" class="btn btn-primary btnFormSubmit">
                        <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

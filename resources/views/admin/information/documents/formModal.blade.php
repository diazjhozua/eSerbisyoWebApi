<!-- Create/Edit Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="documentModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="documentForm" id="documentForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">
                    {{-- Document Type Select Option --}}
                    <div class="form-group">
                        <label for="documentTypeDropDwn">Document Type</label>
                        <select class="custom-select" name="type_id" id="documentTypeDropDwn">
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" autocomplete="off" id="year" name="year" placeholder="Select year">
                    </div>

                    <div class="form-group">
                        <label>Select document</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="pdf" name="pdf">
                            <label class="custom-file-label" for="pdf">Choose file</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button id="btnFormSubmit" type="submit" class="btn btn-primary">
                        <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

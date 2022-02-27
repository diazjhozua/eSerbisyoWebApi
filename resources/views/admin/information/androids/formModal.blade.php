<!-- Create/Edit Announcement Modal -->
<div class="modal fade" id="androidFormModal" tabindex="-1" role="dialog" aria-labelledby="androidFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="android">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="androidFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="androidForm" id="androidForm" action="" method="POST" enctype="multipart/form-data" novalidate>
                <div id="formMethod"></div>

                <div class="modal-body">

                    {{-- Version --}}
                    <div class="form-group">
                        <label for="version">Version Name</label>
                        <input type="text" class="form-control" name="version" id="version">
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                    </div>

                    {{-- Url Upload --}}
                    <div class="form-group">
                        <label for="url">File Link</label>
                        <input type="text" class="form-control" name="url" id="url">
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

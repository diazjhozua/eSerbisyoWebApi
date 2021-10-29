<!-- Create/Edit Ordinance Modal -->
<div class="modal fade" id="ordinanceModal" tabindex="-1" role="dialog" aria-labelledby="ordinanceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="ordinance">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="ordinanceModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="ordinanceForm" id="ordinanceForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formMethod"></div>

                <input type="hidden" id="changeStatusMethod" name="_method" value="PUT">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="ordinance_no" class="form-label">Ordinance No.</label>
                        <input type="text" class="form-control" id="ordinance_no" name="ordinance_no">
                    </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <textarea class="form-control" name="title" id="title" rows="3"></textarea>
                        <div class="form-text">This field would be converted to uppercase</div>
                    </div>

                    {{-- Ordinance Type Select Option --}}
                    <div class="form-group">
                        <label for="ordinanceTypeDropDwn">Type</label>
                        <select class="custom-select" name="type_id" id="ordinanceTypeDropDwn">
                        </select>
                    </div>

                    <div class="form-group date">
                        <label for="date_approved" class="form-label">Date Approved.</label>
                        <input type="text" class="form-control" autocomplete="off" id="date_approved" name="date_approved" placeholder="Select date">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Select ordinance</label>
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

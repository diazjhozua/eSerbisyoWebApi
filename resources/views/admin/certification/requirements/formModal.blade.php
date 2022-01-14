<!-- Create/Edit Modal -->
<div class="modal fade" id="requirementFormModal" tabindex="-1" role="dialog" aria-labelledby="requirementFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="requirementFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="requirementForm" id="requirementForm" action="" method="POST">
                <div id="formMethod"></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label id="nameLabel" for="name" class="form-label">Requirement Name</label>
                        <input type="text" class="form-control" id="requirementName" name="name">
                        <div class="form-text">The inputted data must be unique</div>
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

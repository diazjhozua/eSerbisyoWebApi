<!-- Create/Edit Modal -->
<div class="modal fade" id="certRequirementFormModal" tabindex="-1" role="dialog" aria-labelledby="certRequirementFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="certRequirementFormModalHeader" class="modal-title">Add Requirement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="certRequirementForm" id="certRequirementForm" action="{{ route('admin.storeRequirement') }}" method="POST">

                <div class="modal-body">
                    <input type="hidden" name="certificate_id" id="inputCertID" value="{{ $certificate->id }}"/>

                    {{-- Requirement Select Option --}}
                    <div class="form-group">
                        <label for="dropDwnRequirement">Requirement</label>
                        <select class="custom-select" name="requirement_id" id="dropDwnRequirement">
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnCertReqFormSubmit" type="submit" class="btn btn-primary">
                        <i class="certReqFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span id="btnCertReqFormTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

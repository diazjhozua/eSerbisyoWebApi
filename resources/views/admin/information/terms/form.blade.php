<!-- Create/Edit Term Modal -->
<div class="modal fade" id="termFormModal" tabindex="-1" role="dialog" aria-labelledby="termFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="term">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="termFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="termForm" id="termForm" action="" method="POST" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="description">Term</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <div class="form-text">You can name the term by determining who is the barangay captain of that specific term (Ex. Celso Dioko Term). Term name should be unique</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col">
                            <div class="form-group">
                                <label for="project_end" class="form-label">Year Start</label>
                                <input type="text" class="form-control year" autocomplete="off" id="year_start" name="year_start" placeholder="Select year">
                            </div>
                        </div>
                        <div class="col">
                            <label for="project_end" class="form-label">Year End</label>
                            <input type="text" class="form-control year" autocomplete="off" id="year_end" name="year_end" placeholder="Select year">
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

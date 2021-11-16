<!-- Create/Edit Term Modal -->
<div class="modal fade" id="positionFormModal" tabindex="-1" role="dialog" aria-labelledby="positionFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="term">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="positionFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="positionForm" id="positionForm" action="" method="POST" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">

                    <div class="row g-3">
                        <div class="col-sm">
                                <div class="form-group">
                                    <label for="ranking">Rank</label>
                                    <input type="number" class="form-control" id="ranking" name="ranking" placeholder="Input ID">
                                    <small class="form-text text-muted">Position Rank</small>
                                </div>
                            </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="id">Position</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the position name">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="job_description">Job Description</label>
                        <textarea class="form-control" id="job_description" rows="3" name="job_description"></textarea>
                        <div class="form-text">Input here what the job description of the position</div>
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

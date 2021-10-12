<!-- Create/Edit Project Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="project">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="projectModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="projectForm" id="projectForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                    {{-- Project Type Select Option --}}
                    <div class="form-group">
                        <label for="projectTypeDropDwn">Type</label>
                        <select class="custom-select" name="type_id" id="projectTypeDropDwn">
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cost" class="form-label">Project Cost</label>
                        <input type="number" class="form-control" min="0" id="cost" name="cost" placeholder="Input project cost">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col">
                            <label for="project_start" class="form-label">Project Start</label>
                            <input type="text" autocomplete="off" class="form-control datepicker" class="form-control" id="project_start" name="project_start" placeholder="Select Start Date" aria-label="Select Start Date">
                        </div>
                        <div class="col">
                            <label for="project_end" class="form-label">Project End</label>
                            <input type="text" autocomplete="off" class="form-control datepicker" id="project_end" name="project_end" placeholder="Select End Date" aria-label="Select End Date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location">
                    </div>

                    <div class="form-group">
                        <label>Select project</label>
                        <div class="custom-file">
                            <input type="file"  class="custom-file-input" id="pdf" name="pdf">
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

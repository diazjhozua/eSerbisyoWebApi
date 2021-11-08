<!-- Report Select Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="reportForm" id="reportForm" action="{{ route('admin.projects.report') }}" method="POST" novalidate>
                <div class="modal-body">
                    <div class="row g-3 mb-3">

                        {{-- Start Date --}}
                        <div class="col">
                            <label for="date_start" class="form-label">Date Start</label>
                            <input type="text" autocomplete="off" class="form-control datepicker" class="form-control" id="date_start" name="date_start" placeholder="Select Start Date" aria-label="Select Start Date">
                        </div>

                        {{-- End Date --}}
                        <div class="col">
                            <label for="date_end" class="form-label">Date End</label>
                            <input type="text" autocomplete="off" class="form-control datepicker" id="date_end" name="date_end" placeholder="Select End Date" aria-label="Select End Date">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            {{-- Sort by column option --}}
                            <label for="project_start" class="form-label">Sort by:</label>
                            <div class="input-group mb-3">
                                <select class="custom-select" id="sort_column" name="sort_column">
                                    <option value="id" selected>ID</option>
                                    <option value="name">Name</option>
                                    <option value="description">Description</option>
                                    <option value="cost">Cost</option>
                                    <option value="project_start">Project Start</option>
                                    <option value="project_end">Project End</option>
                                    <option value="location">Location</option>
                                    <option value="pdf_name">PDF Name</option>
                                    <option value="created_at">Created At</option>
                                    <option value="updated_at">Updated At</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            {{-- Sort by option --}}
                            <label for="project_start" class="form-label">Sort option</label>
                            <div class="input-group mb-3">
                                <select class="custom-select" id="sort_option" name="sort_option">
                                    <option value="ASC" selected>Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnReportFormSubmit">
                        <i class="btnReportFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnReportFormTxt">Generate</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

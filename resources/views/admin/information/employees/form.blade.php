<!-- Create/Edit Document Modal -->
<div class="modal fade" id="employeeFormModal" tabindex="-1" role="dialog" aria-labelledby="employeeFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="employee">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="employeeFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="employeeForm" id="employeeForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">
                    {{-- Name Field --}}
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name"/>
                    </div>

                    {{-- Position Select Option --}}
                    <div class="form-group">
                        <label for="employeePositionDropDwn">Position</label>
                        <select class="custom-select" name="position_id" id="employeePositionDropDwn">
                        </select>
                    </div>

                    {{-- Term Select Option --}}
                    <div class="form-group">
                        <label for="employeeTermDropDwn">Term</label>
                        <select class="custom-select" name="term_id" id="employeeTermDropDwn">
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            {{-- Picture Upload --}}
                            <div class="form-group">
                                <label>Select picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="picture" name="picture">
                                    <label class="custom-file-label" for="picture">Choose file</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4" id="currentPictureDiv">
                             <small class="form-text text-muted">Current Picture</small>
                            <img id="imgCurrentPicture" style="height:100px; max-height: 100px; max-width:100px; width: 100px;" src="" class="rounded" alt="Mr. Moises Huel image">
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

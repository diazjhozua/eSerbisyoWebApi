<!-- Create/Edit Document Modal -->
<div class="modal fade" id="editUserFormModal" tabindex="-1" role="dialog" aria-labelledby="editUserFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="editUser">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="editUserFormModalHeader" class="modal-title">Edit your user info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="editUserForm" id="editUserForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <input type="hidden" name="_method" value="PUT">

                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                {{-- First Name Field --}}
                                <label>First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                {{-- Middle Name Field --}}
                                <label>Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name"/>
                            </div>
                        </div>
                    </div>

                    {{-- Last Name Field --}}
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"/>
                    </div>

                    {{-- Address Field --}}
                    <div class="form-group">
                        <label for="description">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3"></textarea>
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

                        {{-- Current Picture --}}
                        <div class="col-sm-4" id="currentPictureDiv">
                             <small class="form-text text-muted">Current Picture</small>
                            <img id="imgCurrentPicture" style="height:100px; max-height: 100px; max-width:100px; width: 100px;" src="" class="" alt="">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnEditUserFormSubmit" type="submit" class="btn btn-primary">
                        <i class="loadingIcon fa fa-spinner fa-spin" id="btnEditUserLoading" hidden></i>
                        <span class="btnTxt" id="btnEditUserTxt">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

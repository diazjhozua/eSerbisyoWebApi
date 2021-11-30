<!-- Create/Edit Missing Person Report Modal -->
<div class="modal fade bd-example-modal-lg" id="reportFormModal" tabindex="-1" role="dialog" aria-labelledby="reportFormModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="announcement">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="reportFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="reportForm" id="reportForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-8">
                            {{-- Name --}}
                            <div class="form-group">
                                <label for="title">Missing Person Name</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            {{-- Report Type --}}
                            <label for="report_type">Type</label>
                            <select class="custom-select" id="report_type" name="report_type">
                                {{-- <option selected>Choose unit</option>
                                <option value="Missing">Missing</option>
                                <option value="Found">Found</option> --}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_user_id">Contact (USER ID) - (The specified user_id will receive notification within the application)</label>
                        <input type="number" class="form-control" name="contact_user_id" id="contact_user_id">
                    </div>


                    <div class="row mt-3 mt-lg-0">
                        <div class="col-sm-8">
                            {{-- Unique Sign --}}
                            <div class="form-group">
                                <label for="unique_sign">Unique Sign</label>
                                <input type="text" class="form-control" name="unique_sign" id="unique_sign">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            {{-- Age --}}
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" class="form-control" name="age" id="age">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3 mt-lg-0">
                        <div class="col-sm-8">
                            {{-- Height --}}
                            <div class="form-group">
                                <label for="title">Height</label>
                                <input type="number" step="any" class="form-control" name="height" id="height">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            {{-- Height Unit --}}
                            <label for="height_unit">Unit</label>
                            <select class="custom-select" id="height_unit" name="height_unit">
                                {{-- <option selected>Choose unit</option>
                                <option value="feet(ft)">feet(ft)</option>
                                <option value="centimeter(cm)">centimeter(cm)</option> --}}
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3 mt-lg-0">
                        <div class="col-sm-8">
                            {{-- Weight --}}
                            <div class="form-group">
                                <label for="weight">Weight</label>
                                <input type="number" step="any" class="form-control" name="weight" id="weight">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            {{-- Weight Unit --}}
                            <label for="weight_unit">Unit</label>
                            <select class="custom-select" id="weight_unit" name="weight_unit">
                                {{-- <option selected>Choose unit</option>
                                <option value="kilogram(kg)">kilogram(kg)</option>
                                <option value="pound(lbs)">pound(lbs)</option> --}}
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3 mt-lg-0">
                        <div class="col-sm-6">
                            {{-- Eye Color --}}
                            <div class="form-group">
                                <label for="eyes">Eye Color</label>
                                <input type="text" class="form-control" name="eyes" id="eyes">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            {{-- Hair Color --}}
                            <div class="form-group">
                                <label for="hair">Hair Color</label>
                                <input type="text" class="form-control" name="hair" id="hair">
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information --}}
                    <div class="row mt-3 mt-lg-0">
                        <div class="col-sm-6">
                            {{-- Email --}}
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            {{-- Phone No --}}
                            <div class="form-group">
                                <label for="phone_no">Phone No</label>
                                <input type="text" class="form-control" name="phone_no" id="phone_no">
                            </div>
                        </div>
                    </div>

                    {{-- Last seen --}}
                    <div class="form-group">
                        <label for="last_seen">Last seen</label>
                        <textarea class="form-control" name="last_seen" id="last_seen" rows="1"></textarea>
                    </div>

                    {{-- Important Information --}}
                    <div class="form-group">
                        <label for="important_information">Important Information</label>
                        <textarea class="form-control" name="important_information" id="important_information" rows="5"></textarea>
                    </div>

                    {{-- Missing Person Picture --}}
                    <div class="row mt-3 mt-lg-0">
                        <div class="col-sm-8">
                            {{-- Picture Upload --}}
                            <div class="form-group">
                                <label>Select picture (JPG/PNG)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="picture" name="picture">
                                    <label class="custom-file-label" for="picture">Choose file</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4" id="currentPictureDiv">
                             <small class="form-text text-muted">Current Picture</small>
                            <img id="imgCurrentPicture" style="height:100px; max-height: 100px; max-width:100px; width: 100px;" src="" class="rounded" alt="">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnFormSubmit">
                        <i class="btnFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnFormTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

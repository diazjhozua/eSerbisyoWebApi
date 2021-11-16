<style>
    .modal-request {
        color: #636363;
        width: 500px;
    }
    .modal-request .modal-content {
        padding: 20px;
        border-radius: 5px;
        border: none;
        text-align: center;
        font-size: 14px;
    }

    .picture-box img {
        text-align: center;
        width: 200px;
        height: 150px;
        border-radius: 50%;
        margin-bottom: 0px;
    }

    .modal-title {
        color: black
    }

    .userProfile {
        margin: 10px;
        text-align: justify;
        color: black;
    }

    .userProfile > * {
    margin: 10px 0;
    }

    .type {
        font c
    }

</style>

<div id="reviewRequestModal" class="modal fade" tabindex='-1'>
    <div class="modal-dialog modal-request">
        <div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title">Verification Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
			</div>
            <form id="verifyRequestForm" name="verifyRequestForm" action="">
                <div class="modal-body" >
                    <div class="picture-box">
                        <img id="profilePicture" src="" alt="Ezekiel Lacbayen image">
                    </div>

				    <h4 id="profileHead" class="modal-title mt-1">Ezekiel Lacbayen Profile</h4>
                    <hr>

                    <div class="userProfile">
                        <div class="row">
                            <div class="col type">
                                First Name:
                            </div>

                            <div id="firstName" class="col description">
                                {{-- First Name --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Middle Name:
                            </div>

                            <div id="middleName" class="col">
                                {{-- Middle Name --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Last Name:
                            </div>

                            <div id="lastName" class="col">
                                {{-- Last Name --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Purok:
                            </div>

                            <div id="purok" class="col">
                                {{-- Purok --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Address:
                            </div>

                            <div id="address" class="col text-left">
                                {{-- Address --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Credentials Sent:
                            </div>

                            <div id="credentials" class="col text-left">
                                {{-- Credentials --}}
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="_method" value="PUT">

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Verification Status</label>
                        </div>
                        <select class="custom-select" id="reviewRequestInputSelect" name="status">
                            <option selected>Choose...</option>
                            <option value="Denied">Denied</option>
                            <option value="Approved">Approved</option>
                        </select>
                    </div>


                    {{-- Admin Message --}}
                    <div class="form-group">
                        <label for="description">Message</label>
                        <textarea class="form-control" name="admin_message" id="txtAreaAdminMessage" rows="3"></textarea>
                        <div class="form-text text-justify">Insert here the message on why the user verification request is approved or denied</div>
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="btnVerifyRequestFormSubmit" type="submit" class="btn btn-primary btnVerifyRequest">
                        <i class="btnVerifyRequestLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnVerifyRequestTxt">Submit</span>
                    </button>
                </div>
            </form>
		</div>
    </div>
</div>

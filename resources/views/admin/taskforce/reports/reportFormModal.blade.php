<style>
    .modal-request {
        color: #636363;
        width: 500px;
    }
    .modal-request .userInfo {
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

<div id="reportFormModal" class="modal fade" tabindex='-1'>
    <div class="modal-dialog modal-request">
        <div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title">Report Information (#12)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
			</div>
            <form id="reportForm" name="respondReportForm" action="">

                <div class="modal-body">
                    <label>Submitted by:</label>
                    <div class="userInfo">
                        <div class="picture-box">
                            <img id="profilePicture" src="" alt="Ezekiel Lacbayen image">
                        </div>

                        <h4 id="profileHead" class="modal-title mt-1">Ezekiel Lacbayen (#21)</h4>
                    </div>

                    <hr>

                    <div class="userProfile">
                        <div class="row">
                            <div class="col type">
                                Type:
                            </div>

                            <div id="type" class="col text-left">
                                {{-- Report Type --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Urgency Classification
                            </div>

                            <div id="urgency_classification" class="col text-left">
                                {{-- Urgent Classification --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Location Address:
                            </div>

                            <div id="location_address" class="col text-left">
                                {{-- Location Address --}}
                                37156 Paucek Junction
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Landmark:
                            </div>

                            <div id="landmark" class="col text-left">
                                {{-- Landmark --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Description:
                            </div>

                            <div id="description" class="col text-left">
                                {{-- Address --}}

                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Pictures Sent:
                            </div>

                            <div id="picture" class="col text-left">
                                {{-- Pictures --}}
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="_method" value="PUT">

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Status</label>
                        </div>
                        <select class="custom-select" id="statusSelect" name="status">

                        </select>
                    </div>


                    {{-- Admin Message --}}
                    <div class="form-group">
                        <label for="description">Admin Message</label>
                        <textarea class="form-control" name="admin_message" id="txtAreaAdminMessage" rows="3"></textarea>

                        <div id="message_caption" class="form-text text-justify">Insert here the message on why the user verification request is approved or denied</div>
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btnRespondReport">
                        <i class="btnRespondReportLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnRespondReportTxt">Respond</span>
                    </button>
                </div>
            </form>
		</div>
    </div>
</div>

<!-- Respond Modal -->
<div class="modal fade" id="orderReportRespondModal" tabindex="-1" role="dialog" aria-labelledby="orderReportRespondModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Respond Order Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="orderReportRespondForm" id="orderReportForm" action="" method="POST">
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_respond" class="form-label">Respond Message</label>
                        <textarea class="form-control" name="admin_respond" id="admin_respond" rows="3"></textarea>
                        <div class="form-text">Input here your message</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="btnFormSubmit" class="btn btn-primary">
                        <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnTxt">Respond</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

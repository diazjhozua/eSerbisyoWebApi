<style>
    .modal-confirm {
        color: #636363;
        width: 400px;
    }
    .modal-confirm .modal-content {
        padding: 20px;
        border-radius: 5px;
        border: none;
        text-align: center;
        font-size: 14px;
    }
    .modal-confirm .modal-header {
        border-bottom: none;
        position: relative;
    }
    .modal-confirm h4 {
        text-align: center;
        font-size: 26px;
        margin: 30px 0 -10px;
    }
    .modal-confirm .close {
        position: absolute;
        top: -5px;
        right: -2px;
    }
    .modal-confirm .modal-body {
        color: #999;
    }
    .modal-confirm .modal-footer {
        border: none;
        text-align: center;
        border-radius: 5px;
        font-size: 13px;
        padding: 10px 15px 25px;
    }
    .modal-confirm .modal-footer a {
        color: #999;
    }
    .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        z-index: 9;
        text-align: center;
        /* border: 3px solid #f15e5e; */
    }
    .modal-confirm .icon-box i {
        /* color: #f15e5e; */
        font-size: 46px;
        display: inline-block;
        margin-top: 13px;
    }
    .modal-confirm .btn, .modal-confirm .btn:active {
        color: #fff;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.4s;
        line-height: normal;
        min-width: 120px;
        border: none;
        min-height: 40px;
        border-radius: 3px;
        margin: 0 5px;
    }

    .trigger-btn {
        display: inline-block;
        margin: 100px auto;
    }
</style>

<!-- Modal HTML -->
<div id="changeRoleModal" class="modal fade" tabindex='-1'>
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header flex-column">
				<div class="icon-box">
					<i id="iconBoxLogo"></i>
				</div>
				<h4 class="modal-title w-100">Are you sure?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>

            <form id="changeRoleForm" name="changeRoleForm" method="POST">
                <div class="modal-body" >
                    <p class="text-justify" id="confirmationMessage"></p>

                    <div class="form-group" id="positionSelectContainer" style="display:none;">
                        <div class="text-left">
                            <label for="inputSelPosID" class="form-label">Position:</label>
                        </div>
                        <div class="input-group mb-3">
                            <select class="custom-select" id="inputSelPosID" name="inputSelPosID">
                                <option value="2" selected>Information Admin</option>
                                <option value="3">Certification Admin</option>
                                <option value="4">Taskforce Admin</option>
                                <option value="5">Information Staff</option>
                                <option value="6">Certification Staff</option>
                                <option value="7">Taskforce Staff</option>
                                <option value="1">Super Admin</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="btnChangeRoleFormSubmit" type="submit" class="btn btn-primary btnChangeRole">
                        <i class="btnChangeRoleLoadingIcon fa fa-spinner fa-spin" hidden></i>
                        <span class="btnChangeRoleTxt"></span>
                    </button>
                </div>
            </form>
		</div>
	</div>
</div>

<!-- Barangay Clearance Form Modal -->
<div class="modal fade bd-example-modal-lg" id="certificateFormModal" tabindex="-1" role="dialog" aria-labelledby="certificateFormModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="announcement">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="certificateFormModalHeader" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form name="certificateForm" id="certificateForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                <div id="formMethod"></div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            {{-- First Name --}}
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" name="firstName" id="inputFirstName">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            {{-- Middle Name --}}
                            <div class="form-group">
                                <label for="middleName">Middle Name</label>
                                <input type="text" class="form-control" name="middleName" id="inputMiddleName">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            {{-- Last Name --}}
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" name="lastName" id="inputLastName">
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="form-group">
                    <label for="title">Address</label>
                        <input type="text" class="form-control" name="address" id="inputAddress">
                    </div>

                    <div class="infoContainer" style="display:none;">
                        <div class="row">
                            <div class="col-sm-4">
                                {{-- Civil Status --}}
                                <div class="form-group">
                                    <label for="civil_status">Civil Status</label>
                                    <select class="custom-select" id="inputSelectCivil" name="civilStatus">
                                        <option value="" selected>Choose...</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                {{-- Birthday --}}
                                <div class="form-group">
                                    <label for="birthday" class="form-label">Birthday</label>
                                    <input type="text" autocomplete="off" class="form-control datepicker" class="form-control" id="inputBirthday" name="birthday" placeholder="Select Birthday" aria-label="Select Birthday">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                {{-- Citizenship --}}
                                <div class="form-group">
                                    <label for="businessName">Citizenship</label>
                                    <input type="text" class="form-control" name="citizenship" id="inputCitizenship">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group cedulaIdentificationContainer" style="display:none;">
                        {{-- Birthplace --}}
                        <label for="birthplace">Birthplace</label>
                        <textarea class="form-control" name="birthplace" id="inputBirthplace" rows="1"></textarea>
                    </div>

                    <div class="form-group clearanceIndigencyContainer" style="display:none;">
                        {{-- Purpose --}}
                        <label for="purpose">Purpose</label>
                        <textarea class="form-control" name="purpose" id="inputPurpose" rows="1"></textarea>
                    </div>

                    <div class="form-group businessContainer" style="display:none;">
                        {{-- Business Name --}}
                        <div class="form-group">
                            <label for="businessName">Business Name</label>
                            <input type="text" class="form-control" name="businessName" id="inputBusinessName">
                        </div>
                    </div>

                    <div class="cedulaContainer" style="display:none;">
                        <div class="row">
                            <div class="col-sm-4">
                                {{-- Profession --}}
                                <div class="form-group">
                                    <label for="profession">Profession</label>
                                    <input type="text" class="form-control" name="profession" id="inputProfession">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                {{-- Height (ft) --}}
                                <div class="form-group">
                                    <label for="height">Height (ft)</label>
                                    <input type="number" step="any" class="form-control" name="height" value="0" id="inputHeight">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                {{-- Weight (kg) --}}
                                <div class="form-group">
                                    <label for="weight">Weight (kg)</label>
                                    <input type="number" step="any" class="form-control" name="weight" value="0" id="inputWeight">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                {{-- Sex --}}
                                <div class="form-group">
                                    <label for="sex">Sex</label>
                                    <select class="custom-select" id="inputSelectSex" name="sex">
                                        <option selected>Choose...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                {{-- Cedula --}}
                                <div class="form-group">
                                    <label for="cedula">Cedula</label>
                                    <select class="custom-select" id="inputSelectCedula" name="cedula">
                                        <option selected>Choose...</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Corporation">Corporation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                {{-- TIN (If any) --}}
                                <div class="form-group">
                                    <label for="tin">TIN (If any)</label>
                                    <input type="number" step="any" class="form-control" name="tin" id="inputTin">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                {{-- ICR NO. (If an alien) --}}
                                <div class="form-group">
                                    <label for="icr">ICR NO. (If an alien)</label>
                                    <input type="number" step="any" class="form-control" name="icr" id="inputIcr">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                {{-- Basic Community Tax --}}
                                <div class="form-group">
                                    <label for="basicTax">Basic Community Tax (₱5.00) Voluntary or Exempted (₱1.00)</label>
                                    <input type="number" step="any" class="form-control" name="basicTax" value="0" id="inputBasicTax">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                {{-- Additional Community Tax --}}
                                <div class="form-group">
                                    <label for="additionalTax">Additional Community Tax (tax not to exceed ₱5,000.00)</label>
                                    <input type="number" step="any" class="form-control" name="additionalTax" value="0" id="inputAdditionalTax">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                {{-- GROSS RECEIPTS OR EARNINGS DERIVED FROM BUSINESS DURING THE PRECEEDING --}}
                                <div class="form-group">
                                    <label for="grossProceeding">Earnings Derived from Business</label>
                                    <input type="number" step="any" class="form-control mt-4" name="grossProceeding" value="0" id="inputGrossProceeding">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                {{-- SALARIES OR GROSS RECEIPT OR EARNINGS DERIVED FROM EXERCISE OF PROFESSION --}}
                                <div class="form-group">
                                    <label for="grossProfession">Earnings Derived from Exercise of Profession</label>
                                    <input type="number" step="any" class="form-control" name="grossProfession" value="0" id="inputGrossProfession">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                {{-- Real Property --}}
                                <div class="form-group">
                                    <label for="realProperty">Income from real property (₱1.00 for every ₱1,000)</label>
                                    <input type="number" step="any" class="form-control" name="realProperty" value="0" id="inputRealProperty">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                {{-- Interest --}}
                                <div class="form-group">
                                    <label for="interest">Interest</label>
                                    <input type="number" step="any" class="form-control mt-4" name="interest" value="0" id="inputInterest">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="identificationContainer" style="display:none;">
                        <div class="row">
                            <div class="col-sm-6">
                                {{-- Contact No. --}}
                                <div class="form-group">
                                    <label for="contactNo">Contact No.</label>
                                    <input type="number" step="any" class="form-control" name="contactNo" id="inputContactNo">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                {{-- Contact Person --}}
                                <div class="form-group">
                                    <label for="contactPerson">Contact Person</label>
                                    <input type="text" class="form-control" name="contactPerson" id="inputContactPerson">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {{-- Contact Person No. --}}
                                <div class="form-group">
                                    <label for="contactPersonNo">Contact Person No.</label>
                                    <input type="number" step="any" class="form-control" name="contactPersonNo" id="inputContactPersonNo">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                {{-- Contact Person Relation --}}
                                <div class="form-group">
                                    <label for="contactPersonRelation">Contact Person Relation</label>
                                    <input type="text" class="form-control" name="contactPersonRelation" id="inputContactPersonRelation">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnCertFormSubmit">
                        <i class="fa fa-spinner fa-spin" id="btnCertFormLoadingIcon" hidden></i>
                        <span id="btnCertFormTxt"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

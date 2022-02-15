<!-- Report Select Modal -->
<div class="modal fade" id="verifyApplicationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Verify Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>
                    To make this request approve you must check each requirements submitted by the residents and approved or denied if the submitted forms and information
                    is valid or not.
                </p>

                @if ($order->application_status == "Pending")
                    <div class="bikerContent">
                        <h6 class="mt-3 text-left">Submitted Requirements</h6>

                        <div class="userProfile">
                            <div class="row">
                                <div class="col type">
                                    Requirement:
                                </div>

                                <div class="col description">

                                    @foreach ($passedRequirements as $passedRequirement)
                                        <p class="font-weight-bold p-0 m-0 text-left">
                                            <a href="{{route('admin.viewRequirement', ['fileName' => $passedRequirement['file_name']]) }}" target="_blank">
                                                {{ $passedRequirement['name'] }}
                                            </a>
                                        </p>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col type">
                                    Requirement Status:
                                </div>

                                <div class="col description">
                                    @if ($isCompleteRequirements)
                                        <p class="font-weight-bold p-0 m-0 text-left text-success">
                                            Complete
                                        </p>
                                    @else
                                        <p class="font-weight-bold p-0 m-0 text-left text-warning">
                                            Incomplete
                                        </p>
                                    @endif

                                    @foreach ($noRequirements as $requirement)
                                        <p class="font-weight-bold p-0 m-0 text-left text-danger">
                                            {{ $requirement['name'] }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- APPLICATION STATUS --}}
                    <label>Application Status</label>
                    <div class="input-group mb-3">
                        <select class="custom-select" id="inputSelApplicationStatus">
                            <option value="" selected>Select</option>
                            @forelse ($applicationType as $application)
                                <option value="{{ $application->type }}">{{ $application->type }} </option>
                            @empty
                                <option value="">Error! Please refresh the page</option>
                            @endforelse
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnChangeApplicationStatus">Change</button>
                        </div>
                    </div>

                    <div class="form-group pickupDateForm"  style="display:none;">
                        {{-- Pickup date for not walkin --}}
                        <label>Pickup Date</label>
                        <div class="input-group">
                            <input type="text" autocomplete="off" class="form-control datepicker" class="form-control" id="inputPickupDate"
                                name="inputPickupDate" value="{{ $order->pickup_date }}" aria-label="Select Pickup Date">
                        </div>
                    </div>

                    {{-- Message to the order request --}}
                    <div class="form-group adminRespondForm"  style="display:none;">
                        <label class="adminRespondLabel" for="inputAdminMessage">Message to the order request</label>
                        <textarea class="form-control" name="inputAdminMessage" id="inputAdminMessage" rows="2" >
                            {{ $order->admin_message }}
                        </textarea>
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button id="btnApplicationFormSubmit" class="btn btn-primary" style="display:none;">
                    <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                    <span class="btnTxt">Update</span>
                </button>
            </div>
        </div>
    </div>
</div>

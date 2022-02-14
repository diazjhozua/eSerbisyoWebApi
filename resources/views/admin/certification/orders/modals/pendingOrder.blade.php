<!-- Report Select Modal -->
<div class="modal fade" id="pendingOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pending Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>
                    These are the list of pending order that needs to approved by certification admin or staff.
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Order #</th>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingOrders as $pendingOrder)
                        <tr>
                            <th scope="row">Order #{{ $pendingOrder->id }}</th>
                            <td>
                                @if ($pendingOrder->ordered_by != null)
                                    (#{{$pendingOrder->ordered_by}}){{ $pendingOrder->contact->getFullNameAttribute() }}
                                @else
                                    {{ $pendingOrder->name }}
                                @endif
                            </td>
                        </tr>
                        @empty
                            <p>No Data</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

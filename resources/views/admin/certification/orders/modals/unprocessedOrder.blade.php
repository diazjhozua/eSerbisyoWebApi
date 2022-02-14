<!-- Report Select Modal -->
<div class="modal fade" id="unprocessedOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Unprocessed Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>
                    These are the list of order that marked as Receive by the biker but not yet processed the payment. Unprocessed means the specific biker does not give the payment of the
                    certificate to the barangay office.
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Order #</th>
                            <th scope="col">Biker Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($unprocessedOrders as $unprocessedOrder)
                        <tr>
                            <th scope="row">Order #{{ $unprocessedOrder->id }}</th>
                            <td>
                                (#{{$unprocessedOrder->delivered_by}}){{ $unprocessedOrder->biker->getFullNameAttribute() }}
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

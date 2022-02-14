<!-- Report Select Modal -->
<div class="modal fade" id="returnableOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Returnable Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>
                    These are the list of order that marked as DNR (Did Not Receive) by the biker and still not returning the item.
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Order #</th>
                            <th scope="col">Biker Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returnableOrders as $returnableOrder)
                        <tr>
                            <th scope="row">Order #{{ $returnableOrder->id }}</th>
                            <td>
                                (#{{$returnableOrder->delivered_by}}){{ $returnableOrder->biker->getFullNameAttribute() }}
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

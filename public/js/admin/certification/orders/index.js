
function deleteOrder(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', `${window.location.origin}/admin/orders/${id}`)
    $('#confirmationMessage').text('Do you really want to delete this order? This process cannot be undone.')
}

$(document).ready(function () {
    $('#orders_nav').addClass('active');

    Pusher.logToConsole = true;

    var pusher = new Pusher('246912fa37725a18907b', {
        cluster: 'ap1',
        authEndpoint: '/broadcasting/auth',
        forceTLS: true,
        auth: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    });

    var channel = pusher.subscribe('private-order-channel');

    channel.bind('order-channel', function (data) {
        let order = data.order;
        toastr.warning('User ' + order.name + ' an order submitted a order request. Please repond to the specific order.')
        col0 = '<td> Order #' + order.id + '</td>';
        col1 = '<td>' + '(#' + order.ordered_by + ')' + order.name + '</td>';
        col2 = '<td>' + order.location_address + '</td>';
        col3 = '<td>' + order.certificate_forms_count + '</td>';
        col4 = '<td> ₱' + order.total_price + '</td>';
        col5 = '<td> ₱' + order.delivery_fee + '</td>';
        col6 = '<td> Not been set by the administrator </td>';

        col7 =
            '<td>' +
            '<p>Application: <br>' +
            '<strong>' + order.application_status + '</strong>' +
            '</p>' +
            '<p>Order Type: <br>' +
            '<strong>' + order.pick_up_type + '</strong>' +
            '</p>' +
            '<p>Order Status: <br>' +
            '<strong>' + order.order_status + '</strong>' +
            '</p>' +
            '</td>';

        const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        let orderDate = new Date(order.created_at);
        col8 = '<td>' + month[orderDate.getMonth()] + " " + orderDate.getDay() + ", " + orderDate.getFullYear() + '</td>';

        viewBtn =
            '<li class="list-inline-item mb-1">' +
            '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + window.location.origin + '/admin/orders/' + order.id + '"><i class="fas fa-eye"></i>' +
            '</a></li>'

        deleteBtn =
            '<li class="list-inline-item mb-1">' +
            '<button class="btn btn-danger btn-sm" onclick="deleteOrder(' + order.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
            '<i class="fas fa-trash-alt"></i>' +
            '</button>' +
            '</li>'

        col9 = '<td><ul class="list-inline m-0">' + viewBtn + deleteBtn + '</td></ul>'
        // Get table reference - note: dataTable() not DataTable()
        var table = $('#dataTable').DataTable();

        var currentPage = table.page();
        table.row.add([col0, col1, col2, col3, col4, col5, col6, col7, col8, col9]).draw()

        selectedRow = 0
        var index = table.row(selectedRow).index(),
            rowCount = table.data().length - 1,
            insertedRow = table.row(rowCount).data(),
            tempRow

        for (var i = rowCount; i > index; i--) {
            tempRow = table.row(i - 1).data()
            table.row(i).data(tempRow)
            table.row(i - 1).data(insertedRow)
        }

        //refresh the page
        table.page(currentPage).draw(false)
    });

    channel.bind('pusher:subscription_succeeded', function (members) {
        toastr.info('Broadcast Server Connected. You may able receive a new report from a resident realtime')
    });

    // Initialize Year picker in form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd", // Notice the Extra space at the beginning
    });

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()
        let ajaxDelURL = $("#modalDeleteForm").attr('action');

        $.ajax({
            type: 'DELETE',
            url: ajaxDelURL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#btnDelete').attr("disabled", true); //disabled button
                $('.btnDeleteTxt').text('Deleting') //set the text of the submit btn
                $('.btnDeleteLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn
            },
            success: function (response) {
                toastr.success(response.message);

                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement empCount;
                $("#ordersCount").text(parseInt($("#ordersCount").text()) - 1);
            },
            error: function (xhr) {
                var error = JSON.parse(xhr.responseText);

                // show error message from helper.js
                ajaxErrorMessage(error);
            },
            complete: function () {
                $('#btnDelete').attr("disabled", false); //enable button
                $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
                $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn

                $('#confirmationDeleteModal').modal('hide') //hide
                $('#modalDeleteForm').trigger("reset"); //reset all the values
            }
        });
    })

    // Validation for report form
    $("form[name='reportForm']").validate({
        // Specify validation rules
        rules: {
            date_start: {
                required: true,
            },
            date_end: {
                required: true,
                greaterThan: "#date_start"
            },
            sort_column: {
                required: true,
            },
            sort_option: {
                required: true,
            },
            pick_up_type: {
                required: true,
            },
            order_status: {
                required: true,
            },
            application_status: {
                required: true,
            },
        },
        messages: {
            date_end: {
                greaterThan: "Date end must be greater than selected date start"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault();

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();
            let pick_up_type = $('#pick_up_type').val();
            let order_status = $('#order_status').val();
            let application_status = $('#application_status').val();

            var url = `${window.location.origin}/admin/orders/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${pick_up_type}/${order_status}/${application_status}`;
            window.open(url, '_blank');
        }
    });

})

<script type="text/javascript">
$(document).ready( function(){
    //Date range as a button
    $('#subscriptions_filter_date_range').daterangepicker(
        dateRangeSettings,
        function (start, end) {
            $('#ledger_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            subscriptions_table.ajax.reload();
        }
    );
    
    subscriptions_table = $('#subscriptions_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[0, 'desc']], 
        "ajax": {
            "url": "/sells/subscriptions",
            "data": function ( d ) {
                var start = $('#subscriptions_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var end = $('#subscriptions_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                d.start_date = start;
                d.end_date = end;
                @if(!empty($contact_id))
                    d.contact_id = {{$contact_id}};
                @endif
            }
        },
        columnDefs: [ {
            "targets": 9,
            "orderable": false,
            "searchable": false
        } ],
        columns: [
            { data: 'transaction_date', name: 'transaction_date'  },
            { data: 'subscription_no', name: 'subscription_no'},
            { data: 'name', name: 'contacts.name'},
            { data: 'business_location', name: 'bl.name'},
            { data: 'recur_interval', name: 'recur_interval'},
            { data: 'recur_repetitions', name: 'recur_repetitions'},
            { data: 'subscription_invoices', searchable: false, orderable: false},
            { data: 'last_generated', searchable: false, orderable: false},
            { data: 'upcoming_invoice', searchable: false, orderable: false},
            { data: 'action', name: 'action'}
        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#subscriptions_table'));
        }
    });
});

$(document).on( 'click', 'a.toggle_recurring_invoice', function(e){
    e.preventDefault();
    $.ajax({
        method: "GET",
        url: $(this).attr('href'),
        dataType: "json",
        success: function(data){
            if(data.success == true){   
                toastr.success(data.msg);
                subscriptions_table.ajax.reload();
            } else {
                toastr.error(data.msg);
            }
        }
    });
});

</script>
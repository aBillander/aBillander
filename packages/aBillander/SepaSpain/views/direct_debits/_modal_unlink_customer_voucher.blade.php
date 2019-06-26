@section('modals')

@parent

<div class="modal fade" id="CustomerOrderUnlink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="CustomerOrderUnlinkLabel">{{ l('Unlink Voucher') }}: <span id="customer_order_id"></span></h4>
            </div>
                {!! Form::open(array('id' => 'CustomerOrderUnlink_action')) !!}
                {{-- !! Form::hidden('_method', 'DELETE') !! --}}
            <div class="modal-body">
                <div class="row">

                {{-- !! Form::hidden('payment_id', $payment->id) !! --}}

                     <div class="form-group col-lg-1 col-md-1 col-sm-1">
                     </div>

                     <div class="form-group col-lg-10 col-md-10 col-sm-10">
                        {!! l('You are going to unlink a Voucher from Bank Order: <b>:boid</b>. Are you sure?', ['boid' => $directdebit->document_reference]) !!}
                     </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger')) !!}
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>

@stop
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.unlink-customer-voucher', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

              $('#customer_order_id').text(reference);

            $('#dataConfirmModal .modal-body').text(message);
            $('#CustomerOrderUnlink_action').attr('action', href);
            $('#CustomerOrderUnlink').modal({show: true});
            return false;
        });
    });
</script>

@stop
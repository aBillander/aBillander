@section('modals')

@parent

<div class="modal fade" id="outOfStockModal" tabindex="-1" role="dialog" aria-labelledby="outOfStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="outOfStockModalLabel"></h4>
            </div>
            <div class="modal-body">{{ AbiConfiguration::get('ABCC_OUT_OF_STOCK_TEXT') }}</div>
            <div class="modal-body modal-body-text"></div>

            <div class="modal-body alert-warning" id="allow-add-to-cart">
            <p>{{l('Add to Cart anyway', 'abcc/layouts')}}</p>

                <div xclass="form-group pull-left">
                  <div class="input-group" style="width: 81px;">

                    <input id="input-product-quantity-modal" class="form-control input-sm col-xs-2" data-id="" type="text" size="5" maxlength="5" style="xwidth: auto;" value="1" onclick="this.select()" >

                    <span class="input-group-btn">
                      <button id="update-product-quantity-modal" class="btn btn-sm btn-info" data-id="" type="button" title="{{l('Add to Cart', 'abcc/layouts')}}">
                          <span id="add-icon-modal" class="fa fa-cart-plus"></span>
                      </button>
                    </span>

                  </div>
                </div>
            </div>

        <div id="msg-success-modal" class="alert alert-success alert-block" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
        </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-success pull-right" data-dismiss="modal">{{l('Back', [], 'layouts')}}</button>

{{--
                {!! Form::open(array('class' => 'pull-right', 'id' => 'action')) !!}
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger')) !!}
                {!! Form::close() !!}
--}}
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.show-out-of-stock', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            var product_id = $(this).attr('data-product_id');
            var orders = $(this).attr('data-orders');

            $('#outOfStockModalLabel').text(title);
            $('#outOfStockModal .modal-body-text').text(message);
            $('#action').attr('action', href);

            //   Orders are allowed if orders == 'allow'
            if ( orders == 'allow' )
            {
                //
                var el  = $('#add-icon-modal');
                var elp = $('#add-icon-modal').parent();

                $('#input-product-quantity-modal').val(1);
                $('#input-product-quantity-modal').attr('data-id', product_id);
                $('#update-product-quantity-modal').attr('data-id', product_id);

                el.removeClass('fa-check');
                el.addClass('fa-cart-plus');
                elp.removeClass('btn-success');
                elp.addClass('btn-info');
                elp.prop('disabled', false);

                $('#msg-success-modal').hide();

                $('#allow-add-to-cart').show();
            } else {
                //
                $('#input-product-quantity-modal').val('');
                $('#input-product-quantity-modal').attr('data-id', '');
                $('#update-product-quantity-modal').attr('data-id', '');
                $('#allow-add-to-cart').hide();
            }


            $('#outOfStockModal').modal({show: true});
            return false;
        });


        $(document).on('click', '#update-product-quantity-modal', function(evnt) {
            var id       = $(this).attr('data-id');
            var quantity = $(this).parent().prev( "#input-product-quantity-modal" ).val();
            var el  = $('#add-icon-modal');
            var elp = $('#add-icon-modal').parent();

            el.removeClass('fa-cart-plus');
            el.addClass('fa-spinner');

            addProductToCart(id, quantity);

            el.removeClass('fa-spinner');
            el.addClass('fa-check');
            elp.removeClass('btn-info');
            elp.addClass('btn-success');
            elp.prop('disabled', true);

            $('#msg-success-modal').show();

            return false;

        });
        

        $(document).on('keydown','#input-product-quantity-modal', function(evnt){
            var id       = $(this).attr('data-id');
            var quantity = $(this).val();
            var el  = $('#add-icon-modal');
            var elp = $('#add-icon-modal').parent();
      
          if (evnt.keyCode == 13) {
            // console.log("put function call here");
            evnt.preventDefault();

            addProductToCart(id, quantity);

            el.removeClass('fa-spinner');
            el.addClass('fa-check');
            elp.removeClass('btn-info');
            elp.addClass('btn-success');
            elp.prop('disabled', true);

            $('#msg-success-modal').show();

            return false;
          }

        });


    });
</script>

@endsection
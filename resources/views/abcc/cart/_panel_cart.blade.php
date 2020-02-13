<div id="panel_cart"> 

<div class="panel panel-primary" id="panel_cart">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('') }}{{ l('Cart Items') }}

        <span class="badge pull-right" style="background-color: #3a87ad; color: #ffffff;" title="{{ $cart->currency->name }}">{{ $cart->currency->iso_code }}</span> &nbsp; 

         @if($cart->customer->sales_equalization)
            <span id="sales_equalization_badge" class="badge pull-right" style="xbackground-color: #3a87ad; margin-right: 18px;" title="{{ l('Equalization Tax') }}"> {{ l('SE') }} </span>
         @endif

@if( Auth::user()->canMinOrderValue() > 0.0 )

            <span id="" class="badge pull-right alert-danger" style="xbackground-color: #3a87ad; margin-right: 18px;" title="{{ l('Cart amount should be more than: :amount (Products Value)', ['amount' =>  abi_money( Auth::user()->canMinOrderValue(), $cart->currency )]) }}"><i class="fa fa-warning"></i> 
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-content="{{ l('Cart amount should be more than: :amount (Products Value)', ['amount' =>  abi_money( Auth::user()->canMinOrderValue(), $cart->currency )]) }}">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a>
            </span>

@endif

      </h3>
   </div>

   <div id="header_data" style="">


   <div class="panel-body">

        <div id="msg-success" class="alert alert-success alert-block" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span id="msg-success-counter" class="badge"></span>
          <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
        </div>

        <div id="msg-success-delete" class="alert alert-success alert-block" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span id="msg-success-delete-counter" class="badge"></span>
          <strong>{!!  l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
        </div>


<div id="msg-success-update" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-update-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

        <div id="panel_cart_lines" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
          
        {{--  @ include('abcc.cart._panel_cart_lines.blade.php') --}}

        </div>

    </div><!-- div class="panel-body" -->

               <!-- div class="panel-footer text-right">
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; Guardar
                  </button>
               </div -->

<!-- Order header ENDS -->

   </div>

   <div class="panel-footer text-right">       </div>

</div>


    

<!-- div id="panel_cart_total" class="">
  
    @ include('abcc.cart._panel_cart_total')

</div -->


</div>


@include('abcc.catalogue._modal_view_product')

@include('abcc.cart._modal_cart_line_delete')

@include('abcc.cart._modal_confirm_submit')


@section('scripts')  @parent 

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">


    function sortableCartLines() {

      // Sortable :: http://codingpassiveincome.com/jquery-ui-sortable-tutorial-save-positions-with-ajax-php-mysql
      // See: https://stackoverflow.com/questions/24858549/jquery-sortable-not-functioning-when-ajax-loaded
      $('.sortable').sortable({
          cursor: "move",
          update:function( event, ui )
          {
              $(this).children().each(function(index) {
                  if ( $(this).attr('data-sort-order') != ((index+1)*10) ) {
                      $(this).attr('data-sort-order', (index+1)*10).addClass('updated');
                  }
              });

              saveNewPositions();
          }
      });

    }

    function saveNewPositions() {
        var positions = [];
        var token = "{{ csrf_token() }}";

        $('.updated').each(function() {
            positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
            $(this).removeClass('updated');
        });

        $.ajax({
            url: "{{ route('abcc.cart.sortlines') }}",
            headers : {'X-CSRF-TOKEN' : token},
            method: 'POST',
            dataType: 'json',
            data: {
                positions: positions
            },
            success: function (response) {
                console.log(response);
            }
        });
    }


</script>

@endsection


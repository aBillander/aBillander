
{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="inventory" name="tab_name" id="tab_name">

   <div class="panel-body">

<!-- Inventory -->

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-stock_control">
                     {!! Form::label('stock_control', l('Stock Control?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('stock_control', '1', true, ['id' => 'stock_control_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('stock_control', '0', false, ['id' => 'stock_control_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('mrp_type') ? 'has-error' : '' }}"">
                      {!! Form::label('mrp_type', l('MRP type'), ['class' => 'control-label']) !!}
                      {!! Form::select('mrp_type', $product_mrptypeList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('mrp_type', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reorder_point') ? 'has-error' : '' }}">
                     {{ l('Reorder point') }}
                     {!! Form::text('reorder_point', null, array('class' => 'form-control', 'id' => 'reorder_point')) !!}
                     {!! $errors->first('reorder_point', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('maximum_stock') ? 'has-error' : '' }}">
                     {{ l('Maximum stock') }}
                     {!! Form::text('maximum_stock', null, array('class' => 'form-control', 'id' => 'maximum_stock')) !!}
                     {!! $errors->first('maximum_stock', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>


<div id="panel_stock_summary" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}

{{--  @ include('products._panel_stock_summary') --}}

</div>



@if ( \App\Configuration::isTrue('ENABLE_LOTS') )

        <div class="row" style="margin-bottom: 15px;">
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                 </div>
        </div>

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-lot_tracking">
                     {!! Form::label('lot_tracking', l('Lot tracking?'), ['class' => 'control-label']) !!}
                             <a href="javascript:void(0);" data-toggle="popover" data-placement="top"  data-container="body" 
                                                data-content="{{ l('Use Lot and Expiry Date tracking for this Product.') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                             </a>
@if( $product->quantity_onhand == 0.0 )
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('lot_tracking', '1', true, ['id' => 'lot_tracking_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('lot_tracking', '0', false, ['id' => 'lot_tracking_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
@else
                     <div class="form-control">

                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('lot_tracking_info', '1', true, ['id' => 'lot_tracking_info_on']) !!}
                            @if( $product->lot_tracking > 0 )
                              {{ l('Yes', [], 'layouts') }}
                            @else
                              {{ l('No', [], 'layouts') }}
                            @endif
                         </label>
                       </div>

                     </div>
@endif
                   </div>

@if ( $product->lot_tracking )
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('expiry_time') ? 'has-error' : '' }}">
                     {{ l('Expiry Time') }}
                             <a href="javascript:void(0);" data-toggle="popover" 
                                          data-placement="top" data-container="body" data-html="true" 
                                          data-content="{{ l('Number of Days before expiry. Examples:<br /><ul><li>5 or 5d -> 5 days</li><li>8m -> 8 months</li><li>2y -> 2 years</li></ul>') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                             </a>
                     {!! Form::text('expiry_time', null, array('class' => 'form-control', 'id' => 'expiry_time')) !!}
                     {!! $errors->first('expiry_time', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('lot_number_generator') ? 'has-error' : '' }}">
                    {{ l('Lot Generator') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top"  data-container="body" 
                                      data-content="{{ l('Select the function to calculate Lot Numbers.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                    {!! Form::select('lot_number_generator', \App\Lot::getGeneratorList(), null, array('class' => 'form-control', 'id' => 'lot_number_generator')) !!}
                    {!! $errors->first('lot_number_generator', '<span class="help-block">:message</span>') !!}
                 </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('lot_policy') ? 'has-error' : '' }}">
                    {{ l('Lot Policy') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top"  data-container="body" 
                                      data-content="{{ l('Automatic Lot allocation to Documents will be done on this basis.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                    {!! Form::select('lot_policy', \App\Lot::getLotPolicyList(), null, array('class' => 'form-control', 'id' => 'lot_policy')) !!}
                    {!! $errors->first('lot_policy', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">

                      <br /><a class="btn xbtn-sm btn-warning" href="{{ URL::to('products/' . $product->id . '/lotuntracking') }}" title="{{l('Deactivate Lot tracking')}}" xonclick="return false;"><i class="fa fa-cube"></i> {{l('Deactivate Lot tracking')}}</a>

                  </div>

@else
        @if( $product->quantity_onhand > 0.0 )

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">

                      <br /><a class="btn xbtn-sm btn-lightblue activate-lot-tracking" href="{{ URL::to('products/' . $product->id . '/lottracking') }}" title="{{l('Activate Lot tracking')}}" onclick="return false;"><i class="fa fa-cubes"></i> {{l('Activate Lot tracking')}}</a>

                  </div>

        @endif
@endif
        </div>

@endif



@if (\App\Configuration::isTrue('ENABLE_CUSTOMER_CENTER') )
{{--
@php
    $out_of_stockList = [
          'hide'    => l('Hide Product'),
          'deny'    => l('Deny Orders'),
          'allow'   => l('Allow Orders'),
          'default' => l('Default Configuration'),
    ];
@endphp
--}}
        <div class="row" style="margin-bottom: 15px;">
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                 </div>
        </div>

        <div class="row">
                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('out_of_stock') ? 'has-error' : '' }}">
                    {!! Form::label('out_of_stock', l('When out of stock'), ['class' => 'control-label']) !!}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Applies to Customer Center only.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                    {!! Form::select('out_of_stock', $out_of_stockList, null, array('class' => 'form-control', 'id' => 'out_of_stock')) !!}
                    {!! $errors->first('out_of_stock', '<span class="help-block">:message</span>') !!}
                 </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('out_of_stock_text') ? 'has-error' : '' }}">
                     {!! Form::label('out_of_stock_text', l('Display text when out of stock'), ['class' => 'control-label']) !!}
                     {!! Form::textarea('out_of_stock_text', null, array('class' => 'form-control', 'id' => 'out_of_stock_text', 'rows' => '3')) !!}
                     {!! $errors->first('out_of_stock_text', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

@endif

        <div class="row">
        </div>

<!-- Inventory ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>


    {!! Form::close() !!}



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {

        var tab = window.location.hash.replace('#','');

        if (tab=='inventory') getStockSummary();
      
   });
    

    function getStockSummary()
    {
           var panel = $("#panel_stock_summary");
           var url = '{{ route( 'products.stocksummary', [$product->id] ) }}';

           panel.addClass('loading');

      $.ajax({
        type: "GET",
        url: url,
        data: {
        }
      }).done(function(data){
        panel.html(data);
        panel.removeClass('loading');

                $("[data-toggle=popover]").popover();

  $('#available').text( Number($('#quantity_onhand').val()) + Number($('#quantity_onorder').val()) - Number($('#quantity_allocated').val()) );
  
      });
                 
    }

    // See: https://stackoverflow.com/questions/20705905/bootstrap-3-jquery-event-for-active-tab-change
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      if (target == '#tab1default')
      {
          getStockSummary();
      }
      /*
      if ($(target).is(':empty')) {
        $.ajax({
          type: "GET",
          url: "/article/",
          error: function(data){
            alert("There was a problem");
          },
          success: function(data){
            $(target).html(data);
          }
      })
     }
     */
    });


</script>
@endsection



@section('styles')    @parent

  <style type="text/css">
      .xxpopover{
          width:800px !important;
      }
  </style>

@endsection
 
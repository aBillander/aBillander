
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

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('reorder_point') ? 'has-error' : '' }}">
                     {{ l('Reorder point') }}
                     {!! Form::text('reorder_point', null, array('class' => 'form-control', 'id' => 'reorder_point')) !!}
                     {!! $errors->first('reorder_point', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('maximum_stock') ? 'has-error' : '' }}">
                     {{ l('Maximum stock') }}
                     {!! Form::text('maximum_stock', null, array('class' => 'form-control', 'id' => 'maximum_stock')) !!}
                     {!! $errors->first('maximum_stock', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>


<div id="panel_stock_summary" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}

{{--  @ include('products._panel_stock_summary') --}}

</div>

@if (\App\Configuration::isTrue('ENABLE_CUSTOMER_CENTER') )

@php
    $out_of_stockList = [
          'hide'    => l('Hide Product'),
          'deny'    => l('Deny Orders'),
          'allow'   => l('Allow Orders'),
          'default' => l('Default Configuration'),
    ];
@endphp

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
    
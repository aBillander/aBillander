@extends('layouts.master')

@section('title') {{ l('Stock Movements - Create') }} @parent @endsection


@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('New Stock Movement') }}</h3></div>
            <div class="panel-body" id="stock_movement">

                @include('errors.list')

				{!! Form::open(array('route' => 'stockmovements.store')) !!}
    
<div class="row">
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date', l('Date')) !!}
        {!! Form::text('date', $date, array('id' => 'date', 'xreadonly' => 'xreadonly', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('time', l('Time')) !!}

        <div class="bfh-timepicker" data-name= "time" data-time="{{ $time }}"></div>

    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('warehouse_id', l('Warehouse')) !!}
        {!! Form::select('warehouse_id', $warehouseList, \App\Configuration::get('DEF_WAREHOUSE'), array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('movement_type_id', l('Movement type')) !!}
        {!! Form::select('movement_type_id', array('0' => l('-- Please, select --', [], 'layouts')) + $movement_typeList, $movement_type_id, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('document_reference', l('Document')) !!}
        {!! Form::text('document_reference', $document_reference, array('class' => 'form-control')) !!}
    </div>
</div>
    
<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('reference', l('Product Reference')) !!}
        {!! Form::text('reference', null, array('id' => 'reference', 'class' => 'form-control', 'onfocus' => 'this.blur()')) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('product_query', l('Product Name')) !!}
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                          data-content="{{ l('Search by Product Reference or Name') }}">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a>
        <div class="input-group">
          {!! Form::hidden('product_id', '', array('id' => 'product_id')) !!}
          {!! Form::hidden('combination_id', '0', array('id' => 'combination_id')) !!}

          {!! Form::text('product_query', null, array('id' => 'product_query', 'autocomplete' => 'off', 'class' => 'form-control', 'onclick' => 'this.select()')) !!}

           <!-- span class="input-group-btn">
              <button class="btn btn-primary" type="submit" onclick="return false;">
                 <i class="fa fa-search"></i>
              </button>
           </span -->
        </div>
    </div>

    <!-- input type="hidden" name="_token" value="{{ csrf_token() }}" / -->

    <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div_product_options" style="display:none">
        {!! Form::label('options', l('Product Options')) !!}
        <div id="product_options"> &nbsp; 
                {{-- !! Form::hidden('combination_id', null, array('id' => 'combination_id')) !! --}}
        </div>
    </div>

</div>

<div class="row">
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('quantity', l('Quantity')) !!}
        {!! Form::text('quantity', null, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('price_currency', l('Price')) !!}
        {!! Form::text('price_currency', null, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('currency_id', l('Currency')) !!}
        {!! Form::select('currency_id', $currencyList, $currency_id, array('class' => 'form-control')) !!}
        {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('conversion_rate', l('Exchange rate', 'currencies')) !!} 
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                            data-content="{{ l('This rate is to be defined according to your Company\'s default currency. For example, if the default currency is the Euro, and this currency is Dollar, type "1.31", since 1€ usually is worth $1.31 (at the time of this writing). Use the converter here for help: http://www.xe.com/ucc/.', 'currencies') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('conversion_rate', null, array('class' => 'form-control')) !!}
    </div>
</div>
        
<div class="row">
      <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
         {{ l('Notes', [], 'layouts') }}
         {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
         {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
      </div>
</div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('stockmovements') }}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
                  <input type="hidden" id="nextAction" name="nextAction" value="" />
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#nextAction').val('saveAndContinue');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Continue', [], 'layouts')}}
                  </button>
               </div>

				{!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')    @parent


{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
</script>


{{-- Time Picker :: https://github.com/winmarkltd/BootstrapFormHelpers --}}

<script>
  
  @include('stock_movements.TimePicker.bootstrap-formhelpers_js')

  // Localization
 
  var BFHTimePickerDelimiter = ':';

  var BFHTimePickerModes = {
    'am': 'AM',
    'pm': 'PM'
  };

</script>


{{-- AutoComplete :: https://jqueryui.com/autocomplete/--}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

// http://stackoverflow.com/questions/21627170/laravel-tokenmismatchexception-in-ajax-request
// var _globalObj = {{ json_encode(array('_token'=> csrf_token())) }};

  $(function() {
    $( "#product_query" ).autocomplete({
      source: "{{ route('products.ajax.nameLookup') }}",
      minLength: 1,
      appendTo : "#stock_movement",
      select: function( event, ui ) {
      //  alert( ui.item ?
      //    "Selected: " + ui.item.value + " aka " + ui.item.id :
      //    "Nothing selected, input was " + this.value );

        $( "#product_query" ).val(ui.item.name);
        $( "#reference" ).val( ui.item.reference );
        $( "#product_id" ).val( ui.item.id );

        // Product has combinations?
        $("#product_options").addClass('loading');

           var token = "{{ csrf_token() }}";        // See also: http://words.weareloring.com/development/laravel/laravel-4-csrf-tokens-when-using-jquerys-ajax/
           $.ajax({
              type: 'POST',
              url: "{{ route('products.ajax.optionsLookup') }}",
              dataType: 'html',
              data: "product_id="+ui.item.id+"&_token="+token,
              success: function(data) {
                 $("#product_options").html(data);
                 $("#product_options").removeClass('loading');

                 if ( data == '' ) 
                 {
                      $( "#div_product_options" ).hide();
                      $( "#quantity" ).focus();
                 } else {
                      $( "#div_product_options" ).show();
                      $( "#product_query" ).blur();
                      // ...and/or set focus on first select field
                 }
              }
           });
          
          return false;
      }
    })
    // http://stackoverflow.com/questions/9887032/how-to-highlight-input-words-in-autocomplete-jquery-ui
    .data("ui-autocomplete")._renderItem = function (ul, item) {
//        var newText = String(item.value).replace(
//                new RegExp(this.term, "gi"),
//                "<span class='ui-state-highlight' style='color: #dd4814;'><strong>$&</strong></span>");

        return $("<li></li>")
//            .data("item.autocomplete", item)
//            .append("<a>" + newText + "</a>")
            .append( '<div>[' + item.id + '] [' + item.reference + '] ' + item.name + "</div>" )
            .appendTo(ul);
    };
  });
</script>


{{-- Combinations --}}

<script>

  $(document).on('change', '.option_select', function(e){
    e.preventDefault();
    findCombination();
  });


// search the combinations' case of attributes and update displaying of availability, prices, ecotax, and image
function findCombination(firstTime)
{
  //create a temporary 'choice' array containing the choices of the customer
  var product_id = $( "#product_id" ).val();
  var token = "{{ csrf_token() }}";
  var choice = [], opt;
  var pload = '', i = 0;
  all_choices = true;

  $('#options select').each(function(){
    opt = $(this).val();
    if( opt == 0 ) {
      all_choices = false; return;
    }
    choice.push(parseInt(opt));
    pload += '&group['+i+']='+opt;
    i++;
  });
  if (!all_choices) return;
  // pload = pload.substr(1);


  $("#reference").val( '' );
  $("#reference").addClass('loading');

  // Ajax call!
           $.ajax({
              type: 'POST',
              url: "{{ route('products.ajax.combinationLookup') }}",
//              dataType: 'html',
              data: "product_id="+product_id+pload+"&_token="+token,
              success: function(data) {
                 var obj = JSON.parse(data);

                 $("#combination_id").val( obj.id );
                 $("#reference").val( obj.reference );
                 $("#reference").removeClass('loading');
              }
           });

/*
      if (typeof(firstTime) != 'undefined' && firstTime)
        refreshProductImages(0);
      else
        refreshProductImages(combinations[combination]['idCombination']);
      //leave the function because combination has been found
      return;
*/
}

</script>

@endsection

@section('styles')    @parent

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">


{{-- Time Picker :: https://github.com/winmarkltd/BootstrapFormHelpers --}}

<style>
  
  @include('stock_movements.TimePicker.bootstrap-formhelpers_css')

</style>


{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">


<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

@endsection
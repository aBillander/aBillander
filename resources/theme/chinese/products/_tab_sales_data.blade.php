
<div id="xpanel_sales"> 

{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="sales" name="tab_name" id="tab_name">

<div class="panel xpanel-info">
   <!-- div class="panel-heading">
      <h3 class="panel-title">{{ l('Sales') }}</h3>
   </div -->
   <div class="panel-body">

<!-- Sales Prices -->

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('cost_price') ? 'has-error' : '' }}">
                     {{ l('Cost Price') }}
                     {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off', 
                                      'onfocus' => 'this.blur()', 'onclick' => 'this.select()', 'onkeyup' => 'new_cost_price()', 'onchange' => 'new_cost_price()')) !!}
                     {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('margin') ? 'has-error' : '' }}">
                     {{ l('Margin (%)') }} 
                         <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ AbiConfiguration::get('MARGIN_METHOD') == 'CST' ?
                                        l('Margin calculation is based on Cost Price', [], 'layouts') :
                                        l('Margin calculation is based on Sales Price', [], 'layouts') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                         </a>
                     {!! Form::text('margin', null, array('class' => 'form-control', 'id' => 'margin', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()', 'onkeyup' => 'new_margin()', 'onchange' => 'new_margin()')) !!}
                     {!! $errors->first('margin', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price') ? 'has-error' : '' }}">
                     {{ l('Customer Price') }}
                     {!! Form::text('price', null, array('class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()', 'onkeyup' => 'new_price()', 'onchange' => 'new_price()')) !!}
                     {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
                  </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('tax_id') ? 'has-error' : '' }}">
                    {{ l('Tax') }}
                    {!! Form::select('tax_id', array('0' => l('-- Please, select --', [], 'layouts')) + $taxList, null, array('class' => 'form-control', 'id' => 'tax_id',
                                      'onchange' => 'new_tax()')) !!}
                    {!! $errors->first('tax_id', '<span class="help-block">:message</span>') !!}
                 </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price_tax_inc') ? 'has-error' : '' }}">
                     {{ l('Customer Price (with Tax)') }}
                     {!! Form::text('price_tax_inc', null, array('class' => 'form-control', 'id' => 'price_tax_inc', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()', 'onkeyup' => 'new_price_tax_inc()', 'onchange' => 'new_price_tax_inc()')) !!}
                     {!! $errors->first('price_tax_inc', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                      <strong>{{ l('Price input method') }}</strong> : {{ AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') ?
                                                        l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                                        l('Prices are entered exclusive of tax', [], 'appmultilang') }}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('recommended_retail_price') ? 'has-error' : '' }}">
                     {{ l('Recommended Retail Price') }}
                     {!! Form::text('recommended_retail_price', null, array('class' => 'form-control', 'id' => 'recommended_retail_price')) !!}
                     {!! $errors->first('recommended_retail_price', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('available_for_sale_date') ? 'has-error' : '' }}">
                     {{ l('Available for sale') }}
                     {!! Form::text('available_for_sale_date_form', null, array('class' => 'form-control', 'id' => 'available_for_sale_date_form')) !!}
                     {!! $errors->first('available_for_sale_date', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>

<!-- Sales Prices ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}

<!-- Price List -->

<div id="panel_sales_detail" style="padding-left: 15px; padding-right: 15px; padding-bottom: 20px;">

    <div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Price Lists') }}</span> <span style="color: #cccccc;">/</span> {{ $product->name }}
        </h3>        
    </div>

    <div id="div_aBook">
       <div class="table-responsive">

    @if ($pricelists->count())
    <table id="aBook" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{l('Price List Name')}}</th>
                <th class="text-left">{{l('Currency')}}</th>
                <th class="text-left">{{l('Sales Price')}}</th>
                <th class="text-left">{{l('Discount (%)')}}</th>
                <th class="text-left">{{l('Margin (%)')}}</th>
                <th class="text-left">{{l('Price with Tax')}} </th>
                <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody>

            <tr style="color: #3a87ad; background-color: #d9edf7;">
                <td> </td>
                <td>{{ l('Base Price') }}</td>
                <td>{{ AbiContext::getContext()->currency->name }}</td>
                <td>{{ $product->as_price('price') }}</td>
                <td> - </td>
                <td>{{ $product->as_percentable( \App\Helpers\Calculator::margin( $product->cost_price, $product->price ) ) }}</td>
                <td>{{ $product->as_priceable( $product->price*(1.0+($product->tax->percent/100.0)) ) }}</td>
                <td class="text-right"> </td>
            </tr>
            

            @foreach ($pricelists as $pricelist)
                @php 
                      $theprice = $product->getPriceByList($pricelist);
                      $line_price = ( ( ($pricelist->type == 'price') AND $pricelist->price_is_tax_inc ) 
                              ? $theprice->amount/(1.0+($product->tax->percent/100.0))
                              : $theprice->amount 
                                    ); 
                @endphp
            <tr>
                <td>{{ $pricelist->id }}</td>
                <td>{{ $pricelist->name }}<br />
                    <span class="label label-success">{{ $pricelist->getType() }}</span>
                    @if ($pricelist->type != 'price')
                      <span class="label label-default">{{ $pricelist->as_percent('amount') }}%</span>
                    @endif
                    @if ( $pricelist->price_is_tax_inc )
                        <br />
                        <span class="label label-info">{{ l('Tax Included', [], 'pricelists') }}</span>
                    @endif
                    </td>
                <td>{{ $pricelist->currency->name }}</td>
                <td>{{ $product->as_priceable($line_price) }}</td>
                <td>{{ $product->as_percentable( \App\Helpers\Calculator::discount( $product->price, $line_price, $pricelist->currency ) ) }}</td>
                <td>{{ $product->as_percentable( \App\Helpers\Calculator::margin( $product->cost_price, $line_price, $pricelist->currency ) ) }}</td>
                <td>
                @if ( $pricelist->currency->id == intval(AbiConfiguration::get('DEF_CURRENCY')) )
                  {{ $product->as_priceable( $line_price*(1.0+($product->tax->percent/100.0)) ) }}
                @endif
                </td>
                <td class="text-right">
                    <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('pricelistlines/' . $theprice->price_list_line_id . '/edit?back_route=' . urlencode('products/' . $product->id . '/edit#sales')) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
                </td>
            </tr>
            
            @endforeach

        </tbody>
    </table>
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    @endif

       </div>
    </div>



</div>
<!-- Price List ENDS -->

</div>

@section('scripts')     @parent
 
@include('products.js._calculator_js')


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}


<script type="text/javascript">

  $(function() {
    $( "#available_for_sale_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

$(document).ready(function() {
   new_cost_price();
});

</script>

@endsection



{{-- *************************************** --}}



@section('styles')    @parent


{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>


@endsection


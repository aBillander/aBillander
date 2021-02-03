
{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="main_data" name="tab_name" id="tab_name">
{{--
                {!! Form::hidden('product_type',     'simple',      array('id' => 'product_type')) !!}
                {!! Form::hidden('procurement_type', 'manufacture', array('id' => 'procurement_type')) !!}
--}}

<div class="panel panel-primary" id="panel_main_data">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Main Data') }}</h3>
   </div>
   <div class="panel-body">

<!-- Main Data -->
@php
    $foo = $product->product_type == 'combinable' ? [ 'onfocus' => 'this.blur()' ] : [] ;
@endphp

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
                     {{ l('Reference') }}
                     {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference') + $foo) !!}
                     {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                     {{ l('Product Name') }}
                     {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                     {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>
{{--
                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('measure_unit_id', $product_measure_unitList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>
--}}
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Measure Unit') }}
                    <div class="input-group">
                        <div class="form-control">{{ $product->measureunit->name }}</div>
                      <span class="input-group-btn">
                        <a href="{{ route('products.measureunits.index', [$product->id]) }}" class="btn btn-lightblue" title="{{ l('Change Main Measure Unit') }}"><i class="fa fa-wrench"></i></a>
                      </span>
                    </div>
                 </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('ean13') ? 'has-error' : '' }}">
                     {{ l('Ean13') }}
                     {!! Form::text('ean13', null, array('class' => 'form-control', 'id' => 'ean13') + $foo) !!}
                     {!! $errors->first('ean13', '<span class="help-block">:message</span>') !!}
                  </div>
                  
                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                     {{ l('Product type') }}
                     <div class="form-control">{{ \App\Product::getTypeName($product->product_type) }}</div>
                     {{-- !! Form::text('product_type_name', \App\Product::getTypeName($product->product_type), array('class' => 'form-control', 'onfocus' => 'this.blur()')) !! --}}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('procurement_type') ? 'has-error' : '' }}"">
                      {{ l('Procurement type') }}
                      {!! Form::select('procurement_type', $product_procurementtypeList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('procurement_type', '<span class="help-block">:message</span>') !!}
                  </div>

                   <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-phantom_assembly">
                     {!! Form::label('phantom_assembly', l('Phantom Assembly?'), ['class' => 'control-label']) !!}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('A phantom assembly is a logical (rather than functional) grouping of materials.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('phantom_assembly', '1', false, ['id' => 'phantom_assembly_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('phantom_assembly', '0', true, ['id' => 'phantom_assembly_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">

   <div class="panel-footer text-right" style="border-right: 1px solid #e95420;
border-bottom: 1px solid #e95420;
border-left:1px solid #e95420;">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;$('#tab_name_main_data').val('main_data');this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

                  </div>

        </div>

        <div class="row">
                  <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('description') ? 'has-error' : '' }}">
                     {{ l('Description') }}
                     {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'rows' => '3')) !!}
                     {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                  </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('category_id') ? 'has-error' : '' }}">
                  <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    {{ l('Category') }}
                    {!! Form::select('category_id', array('0' => l('-- Please, select --', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
                  </div>
                  </div>
                  <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    {{ l('Position') }}
                             <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                                data-content="{{ l('Use multiples of 10. Use other values to interpolate.') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                             </a>
                    {!! Form::text('position', null, array('class' => 'form-control')) !!}
                  </div>
                  </div>
                 </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('location') ? 'has-error' : '' }}">
                     {{ l('Location') }}
                     {!! Form::text('location', null, array('class' => 'form-control', 'id' => 'location')) !!}
                     {!! $errors->first('location', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('width') ? 'has-error' : '' }}">
                     {{ l('Width') }} (<span class="text-success">{{ optional($length_unit)->sign }}</span>)
                     {!! Form::text('width', null, array('class' => 'form-control', 'id' => 'width')) !!}
                     {!! $errors->first('width', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('height') ? 'has-error' : '' }}">
                     {{ l('Height') }} (<span class="text-success">{{ optional($length_unit)->sign }}</span>)
                     {!! Form::text('height', null, array('class' => 'form-control', 'id' => 'height')) !!}
                     {!! $errors->first('height', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('depth') ? 'has-error' : '' }}">
                     {{ l('Depth') }} (<span class="text-success">{{ optional($length_unit)->sign }}</span>)
                     {!! Form::text('depth', null, array('class' => 'form-control', 'id' => 'depth')) !!}
                     {!! $errors->first('depth', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('volume') ? 'has-error' : '' }}">
                     {{ l('Volume') }} (<span class="text-success">{{ optional($volume_unit)->sign }}</span>)
  <div class="input-group">
                     {!! Form::text('volume', null, array('class' => 'form-control', 'id' => 'volume')) !!}
    <span class="input-group-btn">
      <button class="btn btn-success" type="button" onclick="makeVolume();" title="{{ l('Calculate volume') }}"><i class="fa fa-calculator"></i></button>
    </span>
  </div>
                     {!! $errors->first('volume', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('weight') ? 'has-error' : '' }}">
                     {{ l('Weight') }} (<span class="text-success">{{ optional($weight_unit)->sign }}</span>)
                     {!! Form::text('weight', null, array('class' => 'form-control', 'id' => 'weight')) !!}
                     {!! $errors->first('weight', '<span class="help-block">:message</span>') !!}
                  </div>

@if ( \App\Configuration::isTrue('ENABLE_ECOTAXES') )
                 <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('ecotax_id') ? 'has-error' : '' }}">
                    {{ l('Eco-Tax') }}
                    {!! Form::select('ecotax_id', array('' => l('-- Please, select --', [], 'layouts')) + $ecotaxList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('ecotax_id', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                  </div>
@endif
        </div>

        <div class="row">
{{--        
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('warranty_period') ? 'has-error' : '' }}">
                     {{ l('Warranty period') }}
                     {!! Form::text('warranty_period', null, array('class' => 'form-control', 'id' => 'warranty_period')) !!}
                     {!! $errors->first('warranty_period', '<span class="help-block">:message</span>') !!}
                  </div>
--}}
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity_decimal_places') ? 'has-error' : '' }}">
                      {{ l('Quantity decimals') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('The quantity of the product is expressed with these number of decimals.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                      {!! Form::select('quantity_decimal_places', array('0' => '0', '1' => '1', '2' => '2', '3' => '3' ), null, array('class' => 'form-control')) !!}
                      {!! $errors->first('quantity_decimal_places', '<span class="help-block">:message</span>') !!}
                  </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
                     {!! Form::label('blocked', l('Blocked?', [], 'layouts'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('blocked', '1', false, ['id' => 'blocked_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('blocked', '0', true, ['id' => 'blocked_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
                     {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('active', '1', true, ['id' => 'active_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('active', '0', false, ['id' => 'active_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('new_since_date_form') ? 'has-error' : '' }}">
                     {{ l('New since Date') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('The period to consider the Product as "New" begins from this Date.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('new_since_date_form', null, array('class' => 'form-control', 'id' => 'new_since_date_form') + $foo) !!}
                     {!! $errors->first('new_since_date_form', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

<!--  Main Data ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;$('#tab_name_main_data').val('main_data');this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}




@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection


@section('scripts')    @parent


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#new_since_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  // More Stuff
  function makeVolume()
  {
        var volume = '';

        if ( $.isNumeric( $("#width").val() ) && $.isNumeric( $("#height").val() ) && $.isNumeric( $("#depth").val() ) )
        {
            volume = ( $("#width").val() * $("#height").val() * $("#depth").val() ) / {{ $volume_conversion }};
        }

        $("#volume").val(volume);
  }
  
</script>

@endsection
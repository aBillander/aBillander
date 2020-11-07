
{{-- Search Stuff --}}
<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => $model_path.'.index', 'method' => 'GET', 'id' => 'process')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('date_from_form', l('From', 'layouts')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('date_to_form', l('To', 'layouts')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('status', l('Status')) !!}
    {!! Form::select('status', array('' => l('All', [], 'layouts')) + $statusList, null, array('class' => 'form-control')) !!}
</div>


@if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') )
            <div class="form-group col-lg-2 col-md-2 col-sm-2">
                {!! Form::label('manufacturing_status', l('Manufacturing Status')) !!}
                {!! Form::select('manufacturing_status', ['' => l('All', [], 'layouts')] + $manufacturing_statusList, null, array('class' => 'form-control')) !!}
            </div>
@endif


@if ( \App\Configuration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
            <div class="form-group col-lg-2 col-md-2 col-sm-2">
                {!! Form::label('is_wooc', l('WooCommerce'), ['class' => 'control-label']) !!}
                {!! Form::select('is_wooc', ['' => l('All', [], 'layouts')] + $wooc_statusList, null, array('class' => 'form-control')) !!}
            </div>
{{--
            <div class=" form-group col-lg-2 col-md-2 col-sm-2" id="div-is_wooc">
                 {!! Form::label('is_wooc', l('WooCommerce'), ['class' => 'control-label']) !!}
                 <div>
                   <div class="radio-inline">
                     <label>
                       {!! Form::radio('is_wooc', '1', false, ['id' => 'is_wooc_on']) !!}
                       {!! l('WooCommerce Orders only') !!}
                     </label>
                   </div>
                   <div class="radio-inline">
                     <label>
                       {!! Form::radio('is_wooc', '0', true, ['id' => 'is_wooc_all']) !!}
                       {!! l('All', [], 'layouts') !!}
                     </label>
                   </div>
                 </div>
            </div>
--}}
@endif


<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('autocustomer_name', l('Customer')) !!}
    {!! Form::text('autocustomer_name', null, array('class' => 'form-control', 'id' => 'autocustomer_name')) !!}

    {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
</div>


{{--
<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('warehouse_id', l('Warehouse')) !!}
    {!! Form::select('warehouse_id', array('0' => l('All', [], 'layouts')) + $warehouseList, null, array('class' => 'form-control')) !!}
</div>
--}}


<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('price_amount', l('Total Amount')) !!}
                              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" xdata-container="body" 
                                        data-content="{{ l('With or without Taxes') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                              </a>
    {!! Form::text('price_amount', null, array('class' => 'form-control', 'id' => 'price_amount')) !!}
</div>



<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route($model_path.'.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>

{{-- Search Stuff - ENDS --}}

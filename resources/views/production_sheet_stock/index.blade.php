@extends('layouts.master')

@section('title') {{ l('Production Sheet - Stock Analysis') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn xbtn-sm btn-success  hide " type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ route('productionsheets.show', [$sheet->id]) }}" class="btn xbtn-sm btn-default" title="{{ l('Back to Production Sheet') }}"><i class="fa fa-mail-reply"></i> {{ l('Back', 'layouts') }}</a>

    </div>
    <h2>
        <a class="btn btn-sm alert-warning" href="{{ route('productionsheets.index') }}" title="{{l('Back to Production Sheets')}}"><i class="fa fa-th"></i></a> 

        <span style="color: #cccccc;">/</span> 

                  <span class="lead well well-sm">

                    <a href="{{ URL::to('productionsheets') }}">{{ l('Production Sheet') }}</a> <span style="color: #cccccc;">::</span> {{ abi_date_form_short($sheet->due_date) }}

                 <a href="{{ route('productionsheets.show', [$sheet->id]) }}" class="btn btn-xs btn-default" title="{{ l('Back to Production Sheet') }}"><i class="fa fa-mail-reply"></i></a>

                 </span>


         <span style="color: #cccccc;">/</span> 
                  {{ l('Stock Analysis') }} 
                   &nbsp; 
    </h2>        
</div>



{{--
<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => ['productionsheet.productionorders', $sheet->id], 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('work_center_id', l('Work Center')) !!}
    {!! Form::select('work_center_id', array('' => l('All', [], 'layouts')) + $work_centerList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('category_id', l('Category')) !!}
    {!! Form::select('category_id', array('' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('productionsheet.productionorders', l('Reset', [], 'layouts'), [$sheet->id], array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>
--}}

@foreach ($products as $product)


    <h3>
        <span class="lead well well-sm alert-warning"><a href="{{ URL::to('products/' . $product->id . '/edit') }}#inventory" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $product->reference }}</a></span>  {{ $product->name }} &nbsp;
        <span class="badge" style="background-color: #3a87ad;" title="{{ optional($product->measureunit)->name }}"> &nbsp; {{ optional($product->measureunit)->sign }} &nbsp; </span>
    </h3>


<div class="container">
    <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="panel panel-info">

              <div class="panel-heading">
                <h3 class="panel-title">{{ l('Stock Summary') }} 
               </h3>
              </div>
              <div class="panel-body">

<div id="div_stocksummary">
   <div class="table-responsive">

<table id="stocksummary" class="table table-hover">
    <thead>
        <tr>
            <th>{{l('Stock on hand')}}</th>
            <th>{{l('Total Allocated Stock')}}</th>
            <th>{{l('Production Sheet Allocated')}}</th>
            <th>{{l('Available Stock')}}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $product->as_quantity('quantity_onhand') }}</td>
            <td>{{ $product->as_quantity('quantity_allocated') }}</td>
            <td>{{ $product->as_quantityable($product->lines->sum('quantity') + $product->slip_lines->sum('quantity')) }}</td>
            <td>{{ $product->as_quantityable($product->quantity_available) }}</td>
        </tr>

    </tbody>
</table>

   </div>
</div>


@if( \App\Configuration::isTrue('ENABLE_LOTS') && $product->lot_tracking )

<div id="div_productlots">
<span class="label label-success pull-right" id="product_lot_policy">{{ $product->lot_policy }}</span>
<h3 class="text-primary">
	{{l('Available Product Lots')}}
</h3>

   <div class="table-responsive">

@if( $product->availableLots->count() )
<table id="productlots" class="table table-hover">
    <thead>
        <tr>
			<!-- th class="text-left">{{l('ID', [], 'layouts')}}</th -->
            <th>{{l('Lot Number')}}</th>
            <!-- th>{{l('Warehouse')}}</th -->
            <th class="text-right">{{l('Quantity')}}
              <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('A positive value means stock increases.') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a -->
            </th>
            <th class="text-right">{{l('Allocated Quantity')}}</th>
            <!-- th>{{l('Measure Unit')}}</th -->
            <th>{{l('Manufacture Date')}}</th>
            <th>{{l('Expiry Date')}}</th>
            <th class="text-center">{{-- l('Blocked', [], 'layouts') --}}</th>
            <!-- th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-center">{{l('Attachments')}}</th -->
			<th> </th>
        </tr>
    </thead>
    <tbody>

@foreach( $product->availableLots as $lot )
        <tr>
      <!-- td>{{ $lot->id }}</td -->
      <td title="{{ $lot->id }}">
      	<a href="{{ route( 'lot.stockmovements', [$lot->id] ) }}" title="{{ l('Lot Stock Movements', 'lots') }}" target="_stockmovements">
      			{{ $lot->reference }}
      	</a>

      </td>
      <!-- td>{{ $lot->warehouse->alias_name ?? '-' }}</td -->
      <td class="text-right">{{ $lot->as_quantity('quantity') }}</td>
      <td class="text-right 

@if( ($lot_allocated_qty = $lot->allocatedQuantity()) > 0.0 )
    @if( $lot_allocated_qty < $lot->quantity )
        alert-warning
    @elseif ( $lot_allocated_qty > $lot->quantity )
        btn-info
    @else
        alert-danger
    @endif
@endif
      ">{{ $lot->as_quantityable( $lot_allocated_qty ) }}</td>
      <!-- td>{{ optional($lot->measureunit)->sign }}</td -->
      <td>{{ abi_date_short( $lot->manufactured_at ) }}</td>
      <td>{{ abi_date_short( $lot->expiry_at ) }}</td>

            <td class="text-center">@if ($lot->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>

            <!-- td class="text-center">
                @if ($lot->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $lot->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>


            <td class="text-center">
                @if ($lot->attachments->count()>0)
                      <a class="btn btn-xs btn-blue" href="{{ URL::to('lots/' . $lot->id . '/edit') }}"  title="{{l('Show', [], 'layouts')}}"><i class="fa fa-copy"></i></a>
                @endif</td -->

            <td class="text-right button-pad">
{{--
                @if (  is_null($lot->deleted_at))
                <a class="btn btn-sm alert-info" href="{{ route( 'lot.stockmovements', [$lot->id] ) }}" title="{{ l('Lot Stock Movements') }}" target="_stockmovements"><i class="fa fa-outdent"></i></a>
                       
                <a class="btn btn-sm btn-info" href="{{ route( 'stockmovements.index', ['search_status' => 1, 'lot_id' => $lot->id, 'lot_reference' => $lot->reference] ) }}" title="{{ l('Stock Movements') }}" target="_stockmovements"><i class="fa fa-outdent"></i></a>
                       
                <a class="btn btn-sm btn-warning " href="{{ URL::to('lots/' . $lot->id . '/edit') }}"  title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class=" hide btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('lots/' . $lot->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Lots') }} ::  ({{$lot->id}}) {{ $lot->reference }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('lots/' . $lot->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('lots/' . $lot->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
--}}
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

@endif


              </div><!-- div class="panel-body" ENDS -->

            </div>
            </div><!-- div class="col-lg-6 col-md-6 col-sm-6" -->




            <div class="col-lg-6 col-md-6 col-sm-6">

                <div class="row">

                    @include('production_sheet_stock._block_orders', ['block_title' => l('Customer Orders'), 'panel_class' => 'panel-success', 'block_lines' => $product->lines])
                    
                </div>

                <div class="row">

                    @include('production_sheet_stock._block_orders', ['block_title' => l('Customer Shipping Slips'), 'panel_class' => 'panel-warning', 'block_lines' => $product->slip_lines])
                    
                </div>

            </div><!-- div class="col-lg-6 col-md-6 col-sm-6" -->



    </div><!-- div class="row" ENDS -->
</div>


@endforeach


@include('layouts/back_to_top_button')

@endsection
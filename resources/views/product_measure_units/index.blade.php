@extends('layouts.master')

@section('title') {{ l('Measure Units', 'layouts') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('products/'.$product->id.'/measureunits/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ route('products.edit', [$product->id]) }}" class="btn btn-sm btn-default" title="{{ l('Back to:') }} {{ $product->name }}"><i class="fa fa-mail-reply"></i> {{ l('Back to Product') }}</a> 
    </div>
    <h2>
        <a href="{{ route('products.edit', [$product->id]) }}">{{ l('Products') }}</a> <span style="color: #cccccc;">::</span> <a href="{{ route('products.edit', [$product->id]) }}">{{ l('Measure Units', 'layouts') }}</a> <span style="color: #cccccc;">/</span> {{ $product->name }} <span class="lead well well-sm alert-info" xstyle="color: #e95420;
text-decoration: none;"> {{ $product->measureunit->name }} </span>
    </h2>        
</div>

<div id="div_taxes">
   <div class="table-responsive">

@if ($product->productmeasureunits->count() > 1 )
<table id="taxes" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Name')}}</th>
            <th>{{l('Conversion rate')}}
                     <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                data-content="{{ l('Conversion rates are calculated from one unit of your main Measura Unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, the Conversion rate will be "6" (since a pack of six bottles will contain six bottles).') }}">
                            <i class="fa fa-question-circle abi-help"></i>
                     </a>
            </th>
            <th> </th>
            <th class="text-center">{{l('Active', 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($product->productmeasureunits as $line)
                @if ( 1 || $product->measure_unit_id != $line->measureunit->id )
                    @continue
                @endif
		<tr class="info">
			<td>{{ $line->id }}</td>
            <td>{{ $line->measureunit->name }}</td>
            <td>{{ $line->conversion_rate }}</td>
            <td class="text-right">
                @if ( $product->measure_unit_id != $line->measureunit->id )
                    1 {{ $line->measureunit->name }} = {{ $line->as_percent('conversion_rate') }} x {{ $product->measureunit->name }}
                @endif
            </td>
            <td class="text-center">@if ($line->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<td class="text-right">
                @if (  is_null($line->deleted_at))
{{--
                <a class="btn btn-sm btn-warning" href="{{ URL::to('products/' . $product->id.'/measureunits/' . $line->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
--}}
                    @if ( $product->measure_unit_id != $line->measureunit->id )
                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    		href="{{ URL::to('products/' . $product->id.'/measureunits/' . $line->id ) }}" 
                    		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    		data-title="{{ l('Measure Units') }} :: ({{$line->id}}) {{ $line->measureunit->name }} " 
                    		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                    @endif
                @else
                <a class="btn btn-warning" href="{{ URL::to('measureunits/' . $line->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('measureunits/' . $line->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach

    @foreach ($product->productmeasureunits as $line)
                @if ( $product->measure_unit_id == $line->measureunit->id )
                    {{-- Skip default measure unit --}}
                    @continue
                @endif
        <tr>
            <td>{{ $line->id }}</td>
            <td>{{ $line->measureunit->name }}</td>
            <td>{{ $line->conversion_rate }}</td>
            <td class="text-right">
@php
    $digits = $line->conversion_rate > 1.0
            ? 2
            : 4;
@endphp
                @if ( $product->measure_unit_id != $line->measureunit->id )
                    1 {{ $line->measureunit->name }} = {{ $line->as_percent('conversion_rate', $digits) }} x {{ $product->measureunit->name }}
                @endif
            </td>
            <td class="text-center">@if ($line->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-right">
                @if (  is_null($line->deleted_at))
{{--
                <a class="btn btn-sm btn-warning" href="{{ URL::to('products/' . $product->id.'/measureunits/' . $line->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
--}}
                    @if ( $product->measure_unit_id != $line->measureunit->id )
                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                            href="{{ URL::to('products/' . $product->id.'/measureunits/' . $line->id ) }}" 
                            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                            data-title="{{ l('Measure Units') }} :: ({{$line->id}}) {{ $line->measureunit->name }} " 
                            onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                    @endif
                @else
                <a class="btn btn-warning" href="{{ URL::to('measureunits/' . $line->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('measureunits/' . $line->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
    @endforeach
	</tbody>
</table>



<div class="container" style= "margin-top: 20px;">
    <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="panel panel-info" >
              <div class="panel-heading" style="color: #ffffff;
background-color: #772953;
border-color: #772953;">
                <h3 class="panel-title"><i class="fa fa-wrench"></i> &nbsp; {{ l('Change Main Measure Unit') }}</h3>
              </div>


{!! Form::open(array('route' => ['product.measureunit.change', $product->id], 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">


                 <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Units') }}
                    {!! Form::select('measure_unit_id', $product_measure_unitList, $product->measure_unit_id, array('class' => 'form-control')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>

                  </div>
{{--
                  <div class="row">

                     <div class="form-group col-lg-12 text-center" xstyle="padding-top: 22px">
                          {!! Form::submit('Ver Listado', array('class' => 'btn btn-success')) !!}
                    </div>

                  </div>
--}}

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>

{!! Form::close() !!}

            </div>

            </div>

    </div><!-- div class="row" ENDS -->

</div>





@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection

@include('layouts/modal_delete')

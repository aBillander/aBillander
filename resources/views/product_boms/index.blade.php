@extends('layouts.master')

@section('title') {{ l('BOM') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

{!! Form::model(Request::all(), array('route' => 'productboms.index', 'method' => 'GET', 
"class"=>"navbar-form navbar-left", "role"=>"search", "style"=>"margin-top: 0px !important; margin-bottom: 0px !important;")) !!}
           
                      <div class="form-group">

           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                  data-content="{{ l('Use terms of three (3) characters or more', 'layouts') }}">
              <i class="fa fa-question-circle abi-help"></i>
           </a>
                        {!! Form::text('term', null, array('class' => 'form-control input-sm', "placeholder"=>l("Search terms", 'layouts'))) !!}
                      </div>

                <button class="btn btn-sm btn-default" xstyle="margin-right: 152px" type="submit" title="{{l('Search', [], 'layouts')}}">
                   <i class="fa fa-search"></i>
                   &nbsp; {{l('Search', [], 'layouts')}}
                </button>
     
{!! Form::close() !!}


        <a href="{{ URL::to('productboms/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>


                <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Bill of Materials (BOM)') }}
    </h2>        
</div>

<div id="div_productboms">
   <div class="table-responsive">

@if ($productboms->count())
<table id="productboms" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('BOM Alias') }}</th>
            <th>{{ l('BOM Name') }}</th>
            <!-- th>{{ l('Quantity') }}</th -->
            <th>{{ l('Measure Unit') }}</th>
            <th>{{ l('Materials') }}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-right"> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($productboms as $bom)
		<tr>
			<td>{{ $bom->id }}</td>
            <td>{{ $bom->alias }}</td>
            <td>{{ $bom->name }}</td>
            <!-- td>{{ $bom->as_quantity('quantity') }}</td -->
            <td>{{ optional($bom->measureunit)->name }}</td>
            <td class="text-center @if (!$bom->BOMlines->count()) {{ 'danger' }} @endif ">{{ $bom->BOMlines->count() }}</td>
            <td class="text-center">
                @if ($bom->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $bom->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>

			<td class="text-right">
                @if (  is_null($bom->deleted_at))
                <!-- a class="btn btn-sm btn-blue" href="{{ URL::to('productboms/' . $bom->id . '/exchange') }}" title="{{l('Show Conversion Rate history')}}"><i class="fa fa-bank"></i></a -->               
                <a class="btn btn-sm btn-warning" href="{{ route( 'productboms.edit', [$bom->id] ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('productboms/' . $bom->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('BOMs') }} :: ({{$bom->id}}) {{ $bom->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('productboms/' . $bom->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('productboms/' . $bom->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

{{ $productboms->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $productboms->total() ], 'layouts')}} </span></li></ul>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>

@endif

   </div><!-- div class="table-responsive" ENDS -->

</div>

@stop

@include('product_boms._modal_help')

@include('layouts/modal_delete')

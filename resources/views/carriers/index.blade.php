@extends('layouts.master')

@section('title') {{ l('Carriers') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('carriers/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Carriers') }}
    </h2>        
</div>

<div id="div_carriers">
   <div class="table-responsive">

@if ($carriers->count())
<table id="carriers" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Carrier name')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($carriers as $carrier)
		<tr>
			<td>{{ $carrier->id }}</td>
			<td>{{ $carrier->name }}</td>

            <td class="text-center">
                <a class="btn btn-sm toggle-item" id="item_{{ $carrier->id }}" 
                        href="{{ URL::to('carriers/' . $carrier->id ) }}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ l('Carriers') }} :: ({{$carrier->id}}) {{ $carrier->name }} " 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}">

                        @if ($carrier->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> 
                        @else <i class="fa fa-square-o" style="color: #df382c;"></i> 
                        @endif
                </a></td>

			<td class="text-right">
                @if (  is_null($carrier->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('carriers/' . $carrier->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('carriers/' . $carrier->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Carriers') }} :: ({{$carrier->id}}) {{ $carrier->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('carriers/' . $carrier->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('carriers/' . $carrier->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
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

@stop

@include('layouts/modal_delete')

@section('scripts')

@parent

<script type="text/javascript">
    $(document).ready(function () {
        $('.toggle-item').click(function (evnt) {

            var item_id = $(this).attr('id');
            var href = $(this).attr('href');

            evnt.preventDefault();
   
   // https://laracasts.com/discuss/channels/general-discussion/laravel-ajax-post-updating
   // http://stackoverflow.com/questions/27914559/patch-ajax-request-in-laravel

/* */

            $.ajax({
              type: 'PATCH',
              url: href,
              data: "item_id="+item_id+"&toggleStatus=1&_token={{ csrf_token() }}",  
              success: function(data) {

                    var status;
                    var obj = JSON.parse(data);

                    status = obj.active > 0 ? '<i class="fa fa-check-square" style="color: #38b44a;">' : '<i class="fa fa-square-o" style="color: #df382c;">' ;
                    $('#item_'+obj.item_id).html(status);
              }
            });

            // Seems type=POST with data+'&_method=PATCH' won't work!

/* */

            return false;
        });
    });
</script>

@endsection
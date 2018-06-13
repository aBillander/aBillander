
<div id="div_pricelists">
   <div class="table-responsive">

@if ($lines->count())
<table id="pricelists" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Reference')}}</th>
            <th>{{l('Product Name')}}</th>
            <th>{{l('Customer Price')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($lines as $line)
		<tr>
			<td>{{ $line->id }}</td>
            <td>{{ $line->product->reference }}</td>
            <td>{{ $line->product->name }}</td>
            <td>{{ $line->as_price('price') }}</td>

			<td class="text-right">
                @if (  is_null($line->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('pricelists/' . $list->id.'/pricelistlines/' . $line->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('pricelists/' . $list->id.'/pricelistlines/' . $line->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Price List Lines') }} :: ({{$line->id}}) {{{ $line->product->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('pricelistlines/' . $line->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('pricelistlines/' . $line->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
{!! $lines->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $lines->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

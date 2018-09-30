@php


use Queridiam\FSxConnector\Seccion;
use Queridiam\FSxConnector\Familia;
use Queridiam\FSxConnector\Articulo;

$items = Seccion::with('familias')->withCount('familias')->get();

$sec_total = $items->count();
$fam_total = 0;
foreach ($items as $item) {
    $fam_total += $item->familias_count;
}
$art_total = Articulo::count();

@endphp

<div id="catalogo_factusol" style="margin-top: 40px">


    <div xclass="page-header">
        <h2>
            <span style="color: #dd4814;">{{ l('Catálogo FactuSOL') }}</span>
        </h2>        
    </div>



<div id="div_items">
   <div class="table-responsive">

@if ($items->count())
<table id="items" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th></th>
            <th class="text-center">{{l('Artículos')}}</th>
            <th class="text-center">{{l('Subidos')}}</th>
            <th class="text-center">{{l('Pendiente')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($items as $item)
		<tr>
			<td>[{{ $item->CODSEC }}]</td>
			<td>{{ $item->DESSEC }}</td>
            <td class="text-center"> </td>
            <td class="text-center"> </td>

			<td class="text-right">
                <!-- a class="btn btn-sm btn-warning" href="" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-refresh"></i> &nbsp; {{l('Import', [], 'layouts')}}</a -->

			</td>
		</tr>

		@if ($item->familias->count())
		@foreach ($item->familias as $familia)

		<tr>
			<td><span class="treegrid-expander"></span>[{{ $familia->CODFAM }}]</td>
			<td><span class="treegrid-indent"></span><span class="treegrid-expander xglyphicon xglyphicon-plus"></span>{{ $familia->DESFAM }}</td>
            <td class="text-center">{{ $cf = $familia->articulos->count() }}</td>
            <td class="text-center">{{ $cfp = $familia->products->count() }}</td>
            <td class="text-center">{{ $cf - $cfp }}</td>

			<td class="text-right">
                <!-- a class="btn btn-sm btn-warning" href="" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-refresh"></i> &nbsp; {{l('Import', [], 'layouts')}}</a -->

			</td>
		</tr>

		@endforeach
		@endif
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


<p style="padding:0px; margin:10px 0px 10px 0px;">En total: &nbsp; <button class="btn btn-sm btn-default"><span class="badge">{{$sec_total}}</span> Seccion(es)</button> &nbsp; <button class="btn btn-sm btn-warning"><span class="badge">{{$fam_total}}</span> Familia(s)</button> &nbsp; <button class="btn btn-sm btn-success"><span class="badge">{{$art_total}}</span> Artículo(s)</button><!-- sup> *</sup --></p>

</div>



@section('styles') @parent

<style>

.treegrid-indent {
    width: 16px;
    height: 16px;
    display: inline-block;
    position: relative;
}

.treegrid-expander {
    width: 16px;
    height: 16px;
    display: inline-block;
    position: relative;
    xcursor: pointer;
}

</style>

@endsection


{{-- 
@section('scripts') @parent

<script type="text/javascript">

  $(function() {

  });
  
</script>

@endsection
 --}}


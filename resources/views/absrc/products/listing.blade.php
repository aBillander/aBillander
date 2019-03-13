
@section('styles')
@parent

table.table thead th a.asc  { background:transparent url('{{ asset('assets/default/img/icons/asc.gif')  }}') no-repeat 0 0.4em; padding-left: 0.9em;  }
table.table thead th a.desc { background:transparent url('{{ asset('assets/default/img/icons/desc.gif') }}') no-repeat 0 0.5em; padding-left: 0.9em;  }

@stop

@if (!$products->count())

<div class="alert alert-error">No hay registros</div>

@else


<table class="table table-striped table-borderd table-hover">

	<thead>
		<tr>
			<th>Referencia</th>
			<th><a class="asc" href="/siwapp/web/customers?sort%5B0%5D=due_amount&amp;sort%5B1%5D=asc">Nombre del Producto</a></th>
			<th>Cantidad</th>
			<th>Precio Venta</th>
			<th>Impuesto</th>
			<th>Activo</th>
			<th>Opciones</th>
		</tr>
	</thead>

	<tbody>
	@foreach ($products as $product)
		<tr>
			<td>{{ $product->reference }}</td>
			<td>{{ $product->name }}</td>
			<td>{{ $product->quantity }}</td>
			<td>{{ $product->price }}</td>
			<td>
				<!-- {{ Form::select('tax_id', $taxList, $product->tax_id) }} -->
				{{ $taxList[$product->tax_id] }}
			</td>
			<td>{{ $product->active }}</td>
			<td>
				<div class="options btn-group">
					<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i> Opciones</a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{ URL::to('admin/products', array($product->id, 'edit')) }}">
								<i class="icon-pencil"></i> Editar
							</a>
						</li>
						<li>
							
							<script language="javascript">
								function product_submit_{{ $product->id }}() {
									var result = confirm('Se borrará permanentemente. ¿Desea continuar?');
									if (result) $("#product_delete_{{ $product->id }}").submit();
									// return false;
								};
							</script>
							{{ Form::open(array('id' => 'product_delete_'.$product->id, 
												'method' => 'DELETE', 
												'route' => array('admin.products.destroy', $product->id)
												)) }}
							{{ Form::close() }}
							<a id="product_delete_trigger_{{ $product->id }}" href="" onClick="javascript:product_submit_{{ $product->id }}(); return false;">
								<i class="icon-trash"></i> Borrar
							</a>
						</li>
					</ul>
				</div>
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

{{ $products->links() }}

@endif
@extends('layouts.master')

@section('title')
@parent
Productos
@endsection

@section('content')

<div class="headerbar">
	<h1>Productos</h1>

	<div class="pull-right">
		<a class="btn btn-primary" href="{{ URL::to('admin/products/create') }}"><i class="icon-plus icon-white"></i> Nuevo</a>
	</div>
	
	<div class="pull-right">
		<!-- div class="btn-group">
			<a class="btn disabled" title="First" href="#">
			<a class="btn disabled" title="Prev" href="#">
			<a class="btn disabled" title="Next" href="#">
			<a class="btn disabled" title="Last" href="#">
		</div -->
	</div>
	
	<div class="pull-right">
	
		<ul class="nav nav-pills index-options">
					
			<li xstyle="background-color: #2C2C2C; background-image: -moz-linear-gradient(center top , #333333, #222222);">

					<!-- ?php $this->layout->load_view('filter/jquery_filter'); ? -->
<script type="text/javascript">
	
	var min_filter_length = 3;
	var ajax_load = "<img src='{{ asset('assets/default/img/ajax-loader.gif') }}' alt='loading...' /> Cargando...";
	
	function productsIndexFiltered()
	{
			var filter = $('#filter').val().length;
			if ( (filter > 0) && (filter<min_filter_length) ) return;
			$('#filter_results').html(ajax_load);
			$.ajax({
				url: '{{ URL::to('admin/products/indexFiltered') }}',
				type: 'POST',
				data: { filter_query: $('#filter').val() },
				cache: false,
				success: function(data) {
					$('#filter_results').html(data + '<br /><div id="validation-errors" class="errors_form"></div>');
					if (data.success == false)
					{
						var arr = data.error;
						$.each(arr, function(index, value)
						{
							if (value.length != 0)
							{
								$("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
							}
						});
						$("#validation-errors").show();

					}
					else // (data.error)
					{
						console.log('worked');
						/* var errores = "";
                        for(datos in data.errors){
                            errores += "<small class='error'>" + data.errors[datos] + "</small>";
                        }
                        $(".errors_form").html(errores);
						alert(data+' cosa');  */
					}
				},
				error: function(xhr, textStatus, thrownError) {
					alert('xhr.status = '+xhr.status);
					alert('thrownError = '+thrownError);
				}
			});
			// return false;
	};

	$(document).ready(function() {
		$('#filter').keyup(function() {
			productsIndexFiltered();
		});
	});
</script>
					<form class="navbar-search pull-left">
						<input type="text" xclass="search-query" style="margin-top: 3px;" id="filter" placeholder="Filtrar Productos">
					</form>
			</li>
		</ul>
	</div>

</div>  
            <!-- en este los errores del formulario> 
            <div id="validation-errors" class="errors_form"></div -->


<!-- ?php echo $this->layout->load_view('layout/alerts'); ? -->

<!-- Success-Messages -->
@if ($message = Session::get('message_info'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<!-- h4>Success</h4 -->
	{{ $message }}<!-- Record successfully created -->
</div>
@endif


<div id="filter_results">

@include('admin.products.listing')

</div>

@endsection


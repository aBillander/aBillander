<div class="box box-widget">
	<div class="box-header with-border">

	@if(!empty($categories))
		<select class="select2" id="product_category" style="width:45% !important">

			<option value="all">@lang('lang_v1.all_category')</option>

			@foreach($categories as $category)
				<option value="{{$category['id']}}">{{$category['name']}}</option>
			@endforeach

			@foreach($categories as $category)
				@if(!empty($category['sub_categories']))
					<optgroup label="{{$category['name']}}">
						@foreach($category['sub_categories'] as $sc)
							<i class="fa fa-minus"></i> <option value="{{$sc['id']}}">{{$sc['name']}}</option>
						@endforeach
					</optgroup>
				@endif
			@endforeach
		</select>
	@endif

	@if(!empty($brands))
		&nbsp;
		{!! Form::select('size', $brands, null, ['id' => 'product_brand', 'class' => 'select2', 'name' => null, 'style' => 'width:45% !important']) !!}
		
	@endif

	

	<div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	</div>

	<!-- /.box-tools -->
	</div>
	<!-- /.box-header -->
	<input type="hidden" id="suggestion_page" value="1">
	<div class="box-body">
	<div class="row">
		<div class="col-md-12">
			<div class="eq-height-row" id="product_list_body"></div>
		</div>
		<div class="col-md-12 text-center" id="suggestion_page_loader" style="display: none;">
			<i class="fa fa-spinner fa-spin fa-2x"></i>
		</div>
	</div>
	</div>
	<!-- /.box-body -->
</div>
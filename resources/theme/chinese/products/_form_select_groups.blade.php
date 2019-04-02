@if ( $groups->count() )

<div class="row">
	<div class="col-md-10 col-md-offset-1" style="margin-top: 10px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('To create Combinations, select Option Groups first') }} </h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($product, array('route' => array('products.combine', $product->id))) !!}


        <div class="row">
				<div class="form-group col-md-8">
					{!! Form::label('groups', l('Option Groups')) !!}
					{!! Form::select('groups[]', $groups, null, ['id' => 'groups', 'class' => 'form-control', 'multiple']) !!}
				</div>


				<div class="form-group col-md-4">
					{!! Form::label('create_button', ' ') !!}
					{!! Form::submit(l('Create Combinations'), array('class' => 'btn btn-success form-control')) !!}
				</div>
        </div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@else

<div class="row">
	<div class="col-md-10 col-md-offset-1" style="margin-top: 10px">
		<div class="panel panel-danger">
			<div class="panel-heading"><h3 class="panel-title">{{ l('To create Combinations, you must create Option Groups and Options first') }} </h3></div>
			<!-- div class="panel-body">


			</div -->
		</div>
	</div>
</div>

@endif
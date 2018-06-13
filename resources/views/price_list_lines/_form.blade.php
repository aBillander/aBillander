
<div class="row">

         {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}

      <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price') ? 'has-error' : '' }}">
         {{ l('Customer Price') }}
         {!! Form::text('price', null, array('class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off', 
                          'onclick' => 'this.select()')) !!}
         {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
      </div>

</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('pricelists.pricelistlines.index', l('Cancel', [], 'layouts'), array($list->id), array('class' => 'btn btn-warning')) !!}



@section('scripts')  @parent 

    <script type="text/javascript">


    </script>

@endsection

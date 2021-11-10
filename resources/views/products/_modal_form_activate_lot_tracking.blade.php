

                {!! Form::open(['route' => 'product.lottracking.activate']) !!}

                {!! Form::hidden('lottracking_product_id', $product->id) !!}
        		{!! Form::hidden('lottracking_measure_unit_id', null, array('id' => 'lottracking_measure_unit_id')) !!}
                {!! Form::hidden('lottracking_quantity', $product->quantity_onhand) !!}

            <div class="modal-body">

            	<div class="alert alert-dismissible alert-info">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong></strong> {{ l('A Lot will be created with the quantity in stock.') }}
				</div>
				<br />
            
                
                <div class="row">

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('lottracking_reference') ? 'has-error' : '' }}" id="div-lot_reference">
                     {{ l('Lot Number', 'lots') }}
                     {!! Form::text('lottracking_reference', null, array('class' => 'form-control', 'id' => 'lottracking_reference')) !!}
                     {!! $errors->first('lottracking_reference', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('lottracking_manufactured_at_form') ? 'has-error' : '' }}">
                     {{ l('Manufacture Date', 'lots') }}
                     {!! Form::text('lottracking_manufactured_at_form', null, array('class' => 'form-control', 'id' => 'lottracking_manufactured_at_form')) !!}
                     {!! $errors->first('lottracking_manufactured_at_form', '<span class="help-block">:message</span>') !!}
                  </div>


                 <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('lottracking_expiry_at_form') ? 'has-error' : '' }}">
                     {{ l('Expiry Date', 'lots') }}
                     {!! Form::text('lottracking_expiry_at_form', null, array('class' => 'form-control', 'id' => 'lottracking_expiry_at_form')) !!}
                     {!! $errors->first('lottracking_expiry_at_form', '<span class="help-block">:message</span>') !!}
                  </div>

                </div>

		<div class="row">
         
         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('lottracking_warehouse_id') ? 'has-error' : '' }}">
            {{ l('Warehouse') }}
            {!! Form::select('lottracking_warehouse_id', $warehouseList, null, array('class' => 'form-control', 'id' => 'lottracking_warehouse_id')) !!}
            {!! $errors->first('lottracking_warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>

		    <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('lottracking_notes') ? 'has-error' : '' }}">
		       {!! Form::label('lottracking_notes', l('Notes', [], 'layouts')) !!}
		       {!! Form::textarea('lottracking_notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
		       {!! $errors->first('lottracking_notes', '<span class="help-block">:message</span>') !!}
		    </div>
		</div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger',  'onclick' => 'this.disabled=true;this.form.submit();')) !!}
            </div>
                {!! Form::close() !!}



@section('scripts')  @parent 


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">


    $(document).ready(function() {

    });




</script>


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>


  $(function() {
    $( "#lottracking_manufactured_at_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });


  $(function() {
    $( "#lottracking_expiry_at_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

</script>

@endsection


@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 20000 !important; }
</style>


@endsection

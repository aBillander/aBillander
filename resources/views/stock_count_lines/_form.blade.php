
<div class="row">

         {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}
         {!! Form::hidden('combination_id', null, array('id' => 'combination_id')) !!}

       <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('date') ? 'has-error' : '' }}">
             {{ l('Date') }}
             {!! Form::text('date_form', old('date_form'), array('class' => 'form-control', 'id' => 'date_form', 'autocomplete' => 'off')) !!}
             {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
       </div>

      <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity') ? 'has-error' : '' }}">
         {{ l('Quantity') }}
         {!! Form::text('quantity', null, array('class' => 'form-control', 'id' => 'quantity', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
         {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}

         {{ Form::hidden('quantity_decimal_places', null, array('id' => 'quantity_decimal_places')) }}

      </div>

      <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('cost_price') ? 'has-error' : '' }}">
         {{ l('Cost Price') }}
         {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off', 
                          'onclick' => 'this.select()')) !!}
         {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
      </div>

</div>

	{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
	{!! link_to_route('stockcounts.stockcountlines.index', l('Cancel', [], 'layouts'), array($list->id), array('class' => 'btn btn-warning')) !!}





@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection


@section('scripts')  @parent 


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script type="text/javascript">

    $(document).ready(function() {

        $('#date_form').val('{{ abi_date_form_short( 'now' ) }}');

    });


  $(function() {
    $( "#date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection

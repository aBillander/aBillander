
<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('name', l('Measure Unit name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('sign', l('Symbol')) !!}
    {!! Form::text('sign', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('type', l('Type')) !!}
    {!! Form::select('type', $measureunit_typeList, null, array('class' => 'form-control')) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('decimalPlaces', l('Decimal places')) !!}
    {!! Form::text('decimalPlaces', null, array('class' => 'form-control', 'id' => 'decimalPlaces')) !!}
</div>

<div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-active">
 {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('active', '1', true, ['id' => 'active_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('active', '0', false, ['id' => 'active_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>
</div>

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('measureunits.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}



@if ( !isset($measureunit) )

  @section('scripts')    @parent

      <script type="text/javascript">

          $(document).ready(function() {
             $("#decimalPlaces").val( 0 );
          });

      </script> 

  @endsection

@endif
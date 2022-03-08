
               <div class="panel-body">

      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('template_id') ? 'has-error' : '' }}">
            {{ l('Template') }}
            {!! Form::select('template_id', $templateList, null, array('class' => 'form-control', 'id' => 'template_id')) !!}
            {!! $errors->first('template_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('sequence_id', $sequenceList, old('sequence_id'), array('class' => 'form-control', 'id' => 'sequence_id')) !!}
            {!! $errors->first('sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
            {{ l('Reference / Project') }}
            {!! Form::text('reference', old('reference'), array('class' => 'form-control', 'id' => 'reference')) !!}
            {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('document_date') ? 'has-error' : '' }}">
               {{ l('Document Date') }}
               {!! Form::text('document_date_form', old('document_date_form'), array('class' => 'form-control', 'id' => 'document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('document_date', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="col-lg-2 col-md-2 col-sm-2 {{ $errors->has('delivery_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Delivery Date') }}
               {!! Form::text('delivery_date_form', old('delivery_date_form'), array('class' => 'form-control', 'id' => 'delivery_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('delivery_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

      <!-- /div>
      <div class="row" -->
      </div>
      <div class="row">
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            {{ l('Warehouse') }}
            {!! Form::select('warehouse_id', ['0' => l('-- Please, select --', [], 'layouts')] + $warehouseList, old('warehouse_id'), array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_counterpart_id') ? 'has-error' : '' }}">
            {{ l('Warehouse Counterpart') }}
            {!! Form::select('warehouse_counterpart_id', ['0' => l('-- Please, select --', [], 'layouts')] + $warehouseList, old('warehouse_counterpart_id'), array('class' => 'form-control', 'id' => 'warehouse_counterpart_id')) !!}
            {!! $errors->first('warehouse_counterpart_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipping_method_id') ? 'has-error' : '' }}">
            {{ l('Shipping Method') }}
            {!! Form::select('shipping_method_id', ['' => l('-- Please, select --', [], 'layouts')] + $shipping_methodList, old('shipping_method_id'), array('class' => 'form-control', 'id' => 'shipping_method_id')) !!}
            {!! $errors->first('shipping_method_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_conditions') ? 'has-error' : '' }}">
            {{ l('Shipping Conditions') }}
            {!! Form::textarea('shipping_conditions', old('shipping_conditions'), array('class' => 'form-control', 'id' => 'shipping_conditions', 'rows' => '1')) !!}
            {!! $errors->first('shipping_conditions', '<span class="help-block">:message</span>') !!}
         </div>

      </div>

      <div class="row">
        
         <div class=" hide form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('carrier_id') ? 'has-error' : '' }}">
            {{ l('Carrier') }}
            <!-- div class="form-control" id="carrier_id">{ { optional($document->carrier)->name } }</div -->
            {!! Form::select('carrier_id', ['' => l('-- Please, select --', [], 'layouts'), '-1' => l('-- From Shipping Method')] + $carrierList, null, array('class' => 'form-control', 'id' => 'carrier_id')) !!}
            {!! $errors->first('carrier_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipment_service_type_tag') ? 'has-error' : '' }}">
            {{ l('Shipment Service Type Tag') }}
            {!! Form::text('shipment_service_type_tag', null, array('class' => 'form-control', 'id' => 'shipment_service_type_tag')) !!}
            {!! $errors->first('shipment_service_type_tag', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('number_of_packages') ? 'has-error' : '' }}">
            {{ l('Number of Packages') }}
            {!! Form::text('number_of_packages', null, array('class' => 'form-control', 'id' => 'number_of_packages')) !!}
            {!! $errors->first('number_of_packages', '<span class="help-block">:message</span>') !!}
         </div>
         
        <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('weight') ? 'has-error' : '' }}">
           {{ l('Weight') }}
           {!! Form::text('weight', null, array('class' => 'form-control', 'id' => 'weight')) !!}
           {!! $errors->first('weight', '<span class="help-block">:message</span>') !!}
        </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('volume') ? 'has-error' : '' }}">
           {{ l('Volume') }}
           {!! Form::text('volume', null, array('class' => 'form-control', 'id' => 'volume')) !!}
           {!! $errors->first('volume', '<span class="help-block">:message</span>') !!}
        </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('tracking_number') ? 'has-error' : '' }}">
            {{ l('Tracking Number') }}
            {!! Form::text('tracking_number', null, array('class' => 'form-control', 'id' => 'tracking_number')) !!}
            {!! $errors->first('tracking_number', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('notes') ? 'has-error' : '' }}" xstyle="margin-top: 20px;">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', old('notes'), array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('notes_to_counterpart') ? 'has-error' : '' }}">
            {{ l('Notes to Counterpart') }}
            {!! Form::textarea('notes_to_counterpart', old('notes_to_counterpart'), array('class' => 'form-control', 'id' => 'notes_to_counterpart', 'rows' => '2')) !!}
            {{ $errors->first('notes_to_counterpart', '<span class="help-block">:message</span>') }}
         </div>

      </div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
                  <input type="hidden" id="nextAction" name="nextAction" value="" />
                  <button class="btn btn-info hidden" type="submit" onclick="this.disabled=true;$('#nextAction').val('saveAndConfirm');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Confirm', [], 'layouts')}}
                  </button>
               </div>


@section('styles')    @parent

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>


{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

          $('#document_date_form').val('{{ abi_date_form_short( 'now' ) }}');

          $('#template_id').val({{ intval(AbiConfiguration::get('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE'))}});

          $('#sequence_id').val({{ intval(AbiConfiguration::get('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE'))}});

        });

    </script>


{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#delivery_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection
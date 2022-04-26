


<div class="panel-body" id="div_lines">
   <div class="table-responsive">

@if (optional($settlement->commissionsettlementlines)->count())
<table id="lines" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Date')}}</th>
      <th>{{l('Document')}}</th>
      <th>{{l('Status', 'layouts')}}</th>
			<th>{{l('Customer')}}</th>

			<th>{{l('Commissionable')}}</th>
			<th>{{l('Settlement')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($settlement->commissionsettlementlines as $line)
		<tr>
			<td>{{ $line->id }}</td>
      <td>{{ abi_date_short($line->document_date) }}</td>
			<td>
          <a href="{{ route('absrc.invoice.pdf',  ['invoiceKey' => optional($line->commissionable)->secure_key]) }}" title="{{l('Show', [], 'layouts')}}" target="_blank">
                        @if ( $line->document_reference )
                            {{ $line->document_reference}}
                        @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft', 'layouts') }}</span>
                        @endif
                        <span class="btn btn-sm btn-grey" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-file-pdf-o"></i></span>
          </a></td>
      <td>{{ $line->commissionable->status_name }}</td>
			<td>
          <a href="{{ URL::to('absrc/customers/' . optional(optional($line->commissionable)->customer)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $line->commissionable->customer->name_regular ?? '' }}</a></td>
			
      <td>{{ $line->as_money_amount('document_commissionable_amount') }}</td>
      <td>{{ $line->as_money_amount('commission') }}</td>

			<td class="text-right">

			</td>
		</tr>
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
</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">

  <h3>
    {{l('Total Commissionable')}}: {{ $settlement->as_money('total_commissionable') }}
  </h3>
  <h3>
    {{l('Total Settlement')}}: {{ $settlement->as_money('total_settlement') }}
  </h3>

  <!-- a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a -->
  <!-- button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-plus"></i>
     &nbsp; {{ l('Add Production Order') }}
  </button -->

  <a class=" hide btn xbtn btn-info create-production-order" title="{{l('Set as Paid')}}"><i class="fa fa-money"></i> &nbsp;{{l('Set as Paid')}}</a>
</div>


@include('commission_settlements._modal_unlink_document')


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {

});

</script>

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
</script>

@endsection

@section('styles')    @parent

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

@endsection

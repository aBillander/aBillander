{{-- Production Sheet :: Production Summary --}}

@extends('production_sheets.reports.production_orders_MS1.master')


@section('content')

	@include('production_sheets.reports.production_orders_MS1.manufacturing_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

/* 
	See: https://stackoverflow.com/questions/35110591/how-to-avoid-splitting-an-html-table-across-pages
*/
table.print-friendly tbody tr td, table.print-friendly thead tr th {
    page-break-inside: avoid !important;
	break-inside: avoid !important;
	widows: 0;
}
	
{{--

	@include('production_sheets.reports.production_sheets.invoice_css')

--}}

@endsection



{{-- *************************************** --}}


{{--
@section('scripts') @parent

<script>

  $(function() {

  });
  
</script>

@endsection
--}}

@extends('layouts.master')

@section('title') {{ l('Modelo 347') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

{!! Form::open(array('route' => 'jennifer.reports.index347', 'style' => 'display: inline-block;')) !!}

    <input type="hidden" value="{{ $mod347_year }}" name="mod347_year" id="mod347_year">

    <input type="hidden" value="B" name="mod347_clave" id="mod347_clave">

                  <button class="btn btn-sm btn-lightblue" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; 347 Clientes
                  </button>

{!! Form::close() !!}

        <a href="{{ route('jennifer.reports.mod347', [$mod347_year]) }}" class="btn btn-sm btn-grey" style="margin-left: 22px;"
                title="Comprobación 347 Proveedores y Clientes completo"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Comprobación Acumulados 347') }} <span style="color: #cccccc;">::</span> Proveedores <span class="lead well well-sm alert-warning"> {{ l('Año') }} {{ $mod347_year }} </span> 
    </h2>        
</div>


<div id="div_suppliers">
   <div class="table-responsive">

@if ($suppliers->count())
<table id="suppliers" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('Clave')}}</th>
      <th>{{l('N.I.F.')}}</th>
      <th>{{l('Nombre Fiscal')}} / <br />{{l('Nombre Comercial')}}</th>
			<th>{{l('Código Postal')}}</th>
			<th>{{l('Municipio')}}</th>
			<th>{{l('Importe')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($suppliers as $supplier)
		<tr>
      <td>{{ 'B' }}</td>
      <td>{{ $supplier->identification }}</td>
      <td><a href="{{ URL::to('suppliers/' . $supplier->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $supplier->name_fiscal }}</a>
        <br />{{ $supplier->name_commercial }}</td>
      <td>{{ $supplier->address->postcode }}</td>
      <td>{{ $supplier->address->city }}</td>
      <td>{{ \App\Currency::viewMoney( $supplier->yearly_sales ) }}</td>
                
      <td class="text-right button-pad">
                   <a class=" hide  btn btn-sm btn-blue"    href="{{ route('jennifer.reports.mod347.email', [$mod347_year, $supplier->id]) }}" title="{{l('Send by eMail', [], 'layouts')}}" onclick="this.disabled=true;"><i class="fa fa-envelope"></i></a>

        <a href="{{ route('jennifer.reports.mod347.supplier', [$mod347_year, $supplier->id]) }}" class="btn btn-sm btn-grey" title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i></a>
{{--
          <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                  xhref="{{ URL::to('suppliers/' . $supplier->id) . '/mail' }}" 
                  href="{{ URL::to('mail') }}" 
                  data-to_name = "{{ $supplier->address->firstname }} {{ $supplier->address->lastname }}" 
                  data-to_email = "{{ $supplier->address->email }}" 
                  data-from_name = "{{ abi_mail_from_name() }}" 
                  data-from_email = "{{ abi_mail_from_address() }}" 
                  onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>
--}}
      </td>
                
		</tr>
	@endforeach
	</tbody>
</table>

<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $suppliers->count() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@include('layouts/back_to_top_button')

@endsection

{{--
@include('jennifer.347.modal_mail')
--}}
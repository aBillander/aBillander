@extends('layouts.master')

@section('title') {{ l('Modelo 347') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

{!! Form::open(array('route' => 'jennifer.reports.index347', 'style' => 'display: inline-block;')) !!}

    <input type="hidden" value="{{ $mod347_year }}" name="mod347_year" id="mod347_year">

    <input type="hidden" value="A" name="mod347_clave" id="mod347_clave">

                  <button class="btn btn-sm btn-lightblue" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; 347 Proveedores
                  </button>

{!! Form::close() !!}

        <a href="{{ route('jennifer.reports.mod347', [$mod347_year]) }}" class="btn btn-sm btn-grey" style="margin-left: 22px;"
                title="Comprobación 347 Proveedores y Clientes completo"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Comprobación Acumulados 347') }} <span style="color: #cccccc;">::</span> Clientes <span class="lead well well-sm alert-warning"> {{ l('Año') }} {{ $mod347_year }} </span> 
    </h2>        
</div>


<div id="div_customers">
   <div class="table-responsive">

@if ($customers->count())
<table id="customers" class="table table-hover">
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
	@foreach ($customers as $customer)
		<tr>
      <td>{{ 'B' }}</td>
      <td>{{ $customer->identification }}</td>
      <td><a href="{{ URL::to('customers/' . $customer->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $customer->name_fiscal }}</a>
        <br />{{ $customer->name_commercial }}</td>
      <td>{{ $customer->address->postcode }}</td>
      <td>{{ $customer->address->city }}</td>
      <td>{{ \App\Currency::viewMoney( $customer->yearly_sales ) }}</td>
                
      <td class="text-right button-pad">
                   <a class="btn btn-sm btn-blue"    href="{{ route('jennifer.reports.mod347.email', [$mod347_year, $customer->id]) }}" title="{{l('Send by eMail', [], 'layouts')}}" onclick="this.disabled=true;"><i class="fa fa-envelope"></i></a>

        <a href="{{ route('jennifer.reports.mod347.customer', [$mod347_year, $customer->id]) }}" class="btn btn-sm btn-grey" title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i></a>
{{--
          <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                  xhref="{{ URL::to('customers/' . $customer->id) . '/mail' }}" 
                  href="{{ URL::to('mail') }}" 
                  data-to_name = "{{ $customer->address->firstname }} {{ $customer->address->lastname }}" 
                  data-to_email = "{{ $customer->address->email }}" 
                  data-from_name = "{{ abi_mail_from_name() }}" 
                  data-from_email = "{{ abi_mail_from_address() }}" 
                  onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>
--}}
      </td>
                
		</tr>
	@endforeach
	</tbody>
</table>

<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customers->count() ], 'layouts')}} </span></li></ul>
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
@extends('layouts.master')

@section('title') Usuarios :: @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{{ URL::to('users/create') }}}" class="btn btn-sm btn-success" title=" Añadir Nuevo "><i class="fa fa-plus"></i> Nuevo</a>
        <!-- a href="" onclick="return false;" data-toggle="modal" data-target="#modal_create_customer" class="btn btn-sm btn-success" title=" Añadir Nuevo Cliente "><i class="fa fa-plus"></i> Nuevo</a -->
        <!-- @if (Input::get('onlyTrashed'))
        	<a class="btn btn-default" href="{{ URL::to('customers') }}">{{ Lang::get('customers.general.show_curent') }}</a>
        @else
        	<a class="btn btn-default" href="{{ URL::to('customers?onlyTrashed=true') }}">{{ Lang::get('customers.general.show_deleted') }}</a>
        @endif  -->        
    </div>
    <h2>
        Usuarios
    </h2>        
</div>

<div id="div_users">
   <div class="table-responsive">

@if ($users->count())
<table id="users" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">ID</th>
            <th class="text-left">Usuario</th>
            <th class="text-left">Nombre + Apellido</th>
            <th class="text-left">Email</th>
            <th class="text-left">Inicio en</th>
            <th class="text-center">Activo</th>
            <th class="text-center">Es Admin</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->username }}</td>
            <td>{{{ $user->getFullName() }}}</td>
            <td>{{{ $user->email }}}</td>
            <td>{{ $user->route_home }}</td>

            <td class="text-center">@if ($user->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-center">@if ($user->is_admin) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
    
            <td class="text-right">
                @if (  is_null($user->deleted_at))
                <a class="btn btn-sm btn-success" href="{{ URL::to('users/' . $user->id) }}" title=" Ver "><i class="fa fa-eye"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('users/' . $user->id . '/edit') }}" title=" Modificar "><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-user" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('users/' . $user->id ) }}" 
                		data-content="Esta acción NO podrá deshacerse." 
                		data-title="Tarifas. Realmente desea eliminar:  ({{$user->id}}) {{ $user->username }}" 
                		onClick="return false;" title=" Eliminar "><i class="fa fa-trash-o"></i></a>

							{{-- Form::open(array('method' => 'DELETE', 'route' => array('users.destroy', $user->id))) }}
							{{ Form::submit('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-sm btn-danger delete-user', 'onClick' => 'return confirm(\'You are going to PERMANENTLY delete a record. Are you sure?\');')) }}
							{{ Form::close() --}}
                @else
                <a class="btn btn-warning" href="{{ URL::to('users/' . $user->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('users/' . $user->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    No se han encontrado registros.
</div>
@endif

   </div>
</div>

@include('layouts/modal')


@stop
@section('scripts')

<script type="text/javascript">
    $(document).ready(function () {
        $('.delete-user').click(function (evnt) {
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            $('#myModalLabel').text(title);
            $('#dataConfirmModal .modal-body').text(message);
            $('#action').attr('action', href);
            $('#dataConfirmModal').modal({show: true});
            return false;
        });
    });
</script>

@stop
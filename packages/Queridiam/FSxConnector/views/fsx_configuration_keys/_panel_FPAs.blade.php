


   <div class="row">
      
      <div class="col-lg-7 col-md-7 col-sm-7">




<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="javascript:void(0);" class="btn btn-sm btn-success create-item" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        <span style="color: #dd4814;">{{ l('Formas de Pago en FactuSOL') }}</span> <!-- span style="color: #cccccc;">/</span> { { $order->name } } -->
    </h2>        
</div>

{{-- abi_r($fsolpaymethods) --}}

<div id="div_fpas">
   <div class="table-responsive">

@if (count($fsolpaymethods))
<table id="fpas" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('Código')}}</th>
			<th>{{l('Descripción')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($fsolpaymethods as $cod => $fsolpaymethod)
		<tr>
			<td>{{ $cod }}</td>
			<td>{{ $fsolpaymethod }}</td>

            <td class="text-center">

                <a class="btn btn-sm btn-warning edit-item" data-cod="{{$cod}}" data-desc="{{$fsolpaymethod}}" href="{{ route('fpas.update', [$cod]) }}" title="{{l('Edit', [], 'layouts')}}" onClick="return false;"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{route('fpas.destroy', [$cod] ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Formas de Pago en FactuSOL') }} :: ({{$cod}}) {{ $fsolpaymethod }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

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
</div>



      </div>
      
      <div class="col-lg-5 col-md-5 col-sm-5">

            <div class="panel panel-info" id="panel_main_data" style="margin-top: 40px;">
               <div class="panel-heading">
                  <h3 class="panel-title" id="fpa-form-title"></h3>
               </div>

                   

               <form id="fpa-form" method="POST" action="{{ route('fpas.store') }}"  role="form">
               	{{ csrf_field() }}

               <div class="panel-body">

               <!-- input name="parent_id" value="{ { $parentId } }" type="hidden" -->
                
        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('cod') ? 'has-error' : '' }}">
                     {{ l('Código') }}
                     {!! Form::text('cod', null, array('class' => 'form-control', 'id' => 'cod')) !!}
                     {!! $errors->first('cod', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('desc') ? 'has-error' : '' }}">
                     {{ l('Descripción') }}
                     {!! Form::text('desc', null, array('class' => 'form-control', 'id' => 'desc')) !!}
                     {!! $errors->first('desc', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>


               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>

               </form>




            </div>

      </div>

   </div>


@include('layouts/modal_delete')


@section('scripts')    @parent

<script type="text/javascript">

    $(document).ready(function() {


          $(document).on('click', '.create-item', function(evnt) {

              var href = '{{ route('fpas.store') }}';

              empty_fpa_form();
              $("#fpa-form").attr('action', href);
              $('#cod').focus();

              return false;

          });


          $(document).on('click', '.edit-item', function(evnt) {

              // What to do? Let's see:
              var href = $(this).attr('href');
              $('#fpa-form-title').text("{{l('Edit', [], 'layouts')}}");
              $("#fpa-form").attr('action', href);
              $('#cod').val( $(this).attr('data-cod') );
              $('#desc').val( $(this).attr('data-desc') );
              $('#desc').focus();

              return false;

          });


          empty_fpa_form();

    });       // $(document).ready(function() {    ENDS


    function empty_fpa_form() {

              $('#cod').val('');
              $('#desc').val('');

              $('#fpa-form-title').text("{{l('Add New', [], 'layouts')}}");
    }
  
</script>

@endsection
@extends('layouts.master')

@section('title') {{ l('Lots - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-1" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Lot') }} :: ({{$lot->id}}) &nbsp; <strong>{{$lot->reference}}</strong></h3></div>
			<div class="panel-body">
				{!! Form::model($lot, array('method' => 'PATCH', 'route' => array('lots.update', $lot->id))) !!}

					@include('lots._form_edit')

				{!! Form::close() !!}
			</div>
		</div>
	</div>

	<div class="col-md-4" style="margin-top: 50px">
		<div class="panel panel-warning">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Lot Attachments') }} :: ({{$lot->id}}) &nbsp; <strong>{{$lot->reference}}</strong></h3></div>
			<div class="panel-body">
				{{-- !! Form::model($lot, array('method' => 'PATCH', 'route' => array('lots.update', $lot->id))) !!}
				
									@include('lots._form_edit')
				
								{!! Form::close() !! --}}

            <ul class="list-group">
              <!-- li class="list-group-item" style="color: #333333;background-color: #e7e7e7;border-color: #cccccc;">
                <h4>{{ l('Attachments', 'layouts') }}</h4>
              </li -->

              @foreach( $lot->attachments as $attachment )
                  <li class="list-group-item">
@php
$label = $attachment->name ?: $attachment->filename;
$label_short = strlen($label) > 40 ? substr($label, 0, 40)."&hellip;" : $label;
@endphp
                      <a href="{{ route( 'lots.attachment.show', [$lot->id, $attachment->id] ) }}" title="{{l('View Document', 'layouts')}}">

                          {{ $label_short }}

                      </a> 
                      <span class="pull-right">
                        <a class="btn btn-xs alert-danger delete-item" data-html="false" data-toggle="modal" 
                        data-id="{{$attachment->id}}" 
                        href="{{ route( 'lots.attachment.destroy', [$lot->id, $attachment->id] ) }}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '('.$attachment->id.') '.$label }}" 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                      </span>

                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" data-container="body" 
                                    data-content="{!! $label !!}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
                    
                  </li>
              @endforeach

                  <li class="list-group-item">
                          
                      {!! Form::open(array('route' => ['lots.attachment.store', $lot->id], 'title' => l('Upload an Attach Files', 'layouts'), 'class' => '', 'id' => 'add-attachment-action', 'files' => true)) !!}

                      <input type="hidden" value="App\Models\Lot"     name="model_class"     id="model_class">
                      <input type="hidden" value="{{ $lot->id }}"                 name="model_id"        id="model_id">
                      <input type="hidden" value="{{ $lot->reference ?: $lot->id }}" name="model_reference" id="model_reference">


            <div class="input-group {{ $errors->has('attachment_file') ? 'has-error' : '' }}" style="margin-top: 10px; margin-bottom: 10px;">
                <label class="input-group-btn">
                    <span class="btn btn-blue btn-sm">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="attachment_file" id="attachment_file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control input-sm" readonly>
            </div>

            {{ l('Description') }}
            {!! Form::text('attachment_name', null, array('class' => 'form-control input-sm', 'style' => 'margin-top: 10px; margin-bottom: 10px;', 'id' => 'attachment_name')) !!}

    <div class="text-center">
                      {!! Form::submit(l('Upload File', 'layouts'), array('class' => 'btn btn-sm alert-success')) !!}
    </div>
                      {!! Form::close() !!}

                  </li>
            </ul>

	{!! link_to_route('lots.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
	
			</div>
		</div>
	</div>
</div>


@include('model_attachments/_form_attachments')

@endsection


@include('lots._modal_lot_split')
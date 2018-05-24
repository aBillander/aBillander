@extends('layouts.master')

@section('title') {{ l('Combinations - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
          <h3 class="panel-title">Producto: ({{$product->id}}) {{$product->name}}</h3>
          <h3 class="panel-title" style="margin-top:12px;">{{ l('Edit Combination') }}: ({{$combination->id}}) {{$combination->name()}}</h3>
      </div>
			<div class="panel-body"> 

        @include('errors.list')

				{!! Form::model($combination, array('method' => 'PATCH', 'route' => array('combinations.update', $combination->id))) !!}

          <div class="row">
              <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('reference') ? 'has-error' : '' }}">
                  {!! Form::label('reference', l('Reference')) !!}
                  {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
                  {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
              </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('ean13') ? 'has-error' : '' }}">
                  {!! Form::label('ean13', l('Ean13')) !!}
                  {!! Form::text('ean13', null, array('class' => 'form-control', 'id' => 'ean13')) !!}
                  {!! $errors->first('ean13', '<span class="help-block">:message</span>') !!}
              </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('supplier_reference') ? 'has-error' : '' }}">
                 {!! Form::label('ean13', l('Supplier Reference')) !!}
                 {!! Form::text('supplier_reference', null, array('class' => 'form-control', 'id' => 'supplier_reference')) !!}
                 {!! $errors->first('supplier_reference', '<span class="help-block">:message</span>') !!}
              </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-is_default">
               {!! Form::label('is_default', l('Default?'), ['class' => 'control-label']) !!}
               <div>
                 <div class="radio-inline">
                   <label>
                     {!! Form::radio('is_default', '1', false, ['id' => 'is_default_on']) !!}
                     {!! l('Yes', [], 'layouts') !!}
                   </label>
                 </div>
                 <div class="radio-inline">
                   <label>
                     {!! Form::radio('is_default', '0', true, ['id' => 'is_default_off']) !!}
                     {!! l('No', [], 'layouts') !!}
                   </label>
                 </div>
               </div>
              </div>

          </div>

          <div class="row">
              <div class="form-group col-lg-3 col-md-3 col-sm-3">
                  {!! Form::label('location', l('Location')) !!}
                  {!! Form::text('location', null, array('class' => 'form-control')) !!}
              </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-blocked">
               {!! Form::label('blocked', l('Blocked?', [], 'layouts'), ['class' => 'control-label']) !!}
               <div>
                 <div class="radio-inline">
                   <label>
                     {!! Form::radio('blocked', '1', false, ['id' => 'blocked_on']) !!}
                     {!! l('Yes', [], 'layouts') !!}
                   </label>
                 </div>
                 <div class="radio-inline">
                   <label>
                     {!! Form::radio('blocked', '0', true, ['id' => 'blocked_off']) !!}
                     {!! l('No', [], 'layouts') !!}
                   </label>
                 </div>
               </div>
              </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-active">
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

               <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web">
                 {!! Form::label('publish_to_web', l('Publish to web?'), ['class' => 'control-label']) !!}
                 <div>
                   <div class="radio-inline">
                     <label>
                       {!! Form::radio('publish_to_web', '1', true, ['id' => 'publish_to_web_on']) !!}
                       {!! l('Yes', [], 'layouts') !!}
                     </label>
                   </div>
                   <div class="radio-inline">
                     <label>
                       {!! Form::radio('publish_to_web', '0', false, ['id' => 'publish_to_web_off']) !!}
                       {!! l('No', [], 'layouts') !!}
                     </label>
                   </div>
                 </div>
               </div>

          </div>



        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                      {{ l('Notes', [], 'layouts') }}
                      {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
                  </div>
        </div>

        {!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
        {!! link_to( ('products/' . $product->id . '/edit#combinations'), l('Cancel', [], 'layouts'), array('class' => 'btn btn-warning')) !!}
	

        {!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop
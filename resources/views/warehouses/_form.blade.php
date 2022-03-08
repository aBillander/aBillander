
<div class="panel-body">

	@include('addresses._form_fields_model_related')

    <div class="row">
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
</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">
  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('warehouses') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button>
</div>

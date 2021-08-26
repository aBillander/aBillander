
    {!! Form::model($document, array('method' => 'PATCH', 'route' => array('productionorders.update', $document->id), 'id' => 'update_production_order', 'name' => 'update_production_order', 'class' => 'form')) !!}


<!-- Order header -->

{!! Form::hidden('document_id', $document->id, array('id' => 'document_id')) !!}

               <div class="panel-body">

      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
               {{ l('Provenience') }}
               <div class="form-control"><span class="text-success">{{ $document->created_via }}</span></div>
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2  hide ">
{{--
            {{ l('Due Date') }}
            <div class="form-control">{{abi_date_short($document->due_date)}}</div>
--}}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('due_date') ? 'has-error' : '' }}">
               {{ l('Due Date') }}
               {!! Form::text('due_date_form', null, array('class' => 'form-control', 'id' => 'due_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="col-lg-2 col-md-2 col-sm-2 {{ $errors->has('finish_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Finish Date') }}
               {!! Form::text('finish_date_form', null, array('class' => 'form-control', 'id' => 'finish_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('finish_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
            {{ l('Reference / Project') }}
            {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
            {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('planned_quantity') ? 'has-error' : '' }}">
            {{ l('Planned Quantity') }}
            {!! Form::text('planned_quantity', null, array('class' => 'form-control', 'id' => 'planned_quantity')) !!}
            {!! $errors->first('planned_quantity', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('finished_quantity') ? 'has-error' : '' }}">
            {{ l('Finished Quantity') }}
            {!! Form::text('finished_quantity', null, array('class' => 'form-control', 'id' => 'down_payment')) !!}
            {!! $errors->first('finished_quantity', '<span class="help-block">:message</span>') !!}
         </div>

      </div>

      <div class="row">
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('work_center_id') ? 'has-error' : '' }}">
            {{ l('Work Center') }}
            {!! Form::select('work_center_id', $work_centerList, null, array('class' => 'form-control', 'id' => 'work_center_id')) !!}
            {!! $errors->first('work_center_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            {{ l('Warehouse') }}
            {!! Form::select('warehouse_id', $warehouseList, null, array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>

      </div>

      <div class="row">
      </div>

      <div class="row">

         <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}" xstyle="margin-top: 20px;">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

      </div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">

                  <input type="hidden" id="nextAction" name="nextAction" value="" />

@if ($document->status=='draft' )
@php
  $hidden = $document->lines->count() == 0 ? 'hidden' : ''; 
@endphp
                  <button class="btn btn-success {{ $hidden }} " type="submit" onclick="this.disabled=true;$('#nextAction').val('saveAndConfirm');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Confirm', [], 'layouts')}}
                  </button>
@endif

                  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();" title="{{l('Back to Documents')}}">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>

                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#nextAction').val('saveAndContinue');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Continue', [], 'layouts')}}
                  </button>
               </div>

<!-- Order header ENDS -->


    {!! Form::close() !!}
    
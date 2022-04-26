
<div class="panel-body">

	@include('addresses._form_fields_salesrep')

	<div class="row">
	          <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('alias') ? 'has-error' : '' }}">
	            {{ l('Alias', [],'addresses') }}
	            {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
	            {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
	          </div>
	            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('identification') ? 'has-error' : '' !!}">
	              {{ l('Identification') }}
	              {!! Form::text('identification', null, array('class' => 'form-control', 'id' => 'identification')) !!}
	              {!! $errors->first('identification', '<span class="help-block">:message</span>') !!}
	            </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('sales_rep_type') ? 'has-error' : '' }}">
                      {{ l('Sales Rep type') }}
                      {!! Form::select('sales_rep_type', \App\Models\SalesRep::getTypeList(), null, array('class' => 'form-control')) !!}
                     {!! $errors->first('sales_rep_type', '<span class="help-block">:message</span>') !!}
                  </div>

	          <div class="form-group col-lg-2 col-md-2 col-sm-2">
	            {{-- Poor man offset --}}
	          </div>

            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('reference_external') ? 'has-error' : '' !!}">
              {!! Form::label('reference_external', l('External Reference'), ['class' => 'control-label']) !!}
              {!! Form::text('reference_external', null, array('class' => 'form-control', 'id' => 'reference_external')) !!}
              {!! $errors->first('reference_external', '<span class="help-block">:message</span>') !!}
            </div>

               <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
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

	<div class="row">
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('commission_percent') ? 'has-error' : '' }}">
	            {{ l('Commission (%)') }}
	            {!! Form::text('commission_percent', null, array('class' => 'form-control', 'id' => 'commission_percent')) !!}
	            {!! $errors->first('commission_percent', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('max_discount_allowed') ? 'has-error' : '' }}">
	            {{ l('Max. Discount allowed (%)') }}
	            {!! Form::text('max_discount_allowed', null, array('class' => 'form-control', 'id' => 'max_discount_allowed')) !!}
	            {!! $errors->first('max_discount_allowed', '<span class="help-block">:message</span>') !!}
	          </div>
	          <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('pitw') ? 'has-error' : '' }}">
	            {{ l('Withholdings (%)') }}
	            {!! Form::text('pitw', null, array('class' => 'form-control', 'id' => 'pitw')) !!}
	            {!! $errors->first('pitw', '<span class="help-block">:message</span>') !!}
	          </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('accounting_id') ? 'has-error' : '' }}">
                     {{ l('Accounting ID') }}
                     {!! Form::text('accounting_id', null, array('class' => 'form-control', 'id' => 'accounting_id')) !!}
                    {!! $errors->first('accounting_id', '<span class="help-block">:message</span>') !!}
                  </div>
	</div>
	<div class="row">
	        <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
	          {{ l('Notes', [], 'layouts') }}
	          {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
	          {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
	        </div>
	</div>

</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">
  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('salesreps') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button>
</div>

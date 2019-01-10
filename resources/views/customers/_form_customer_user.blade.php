
      <div class="panel-body">

<div class="row">
<!--div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('name', l('User name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div -->
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('firstname', l('Name', 'customerusers')) !!}
    {!! Form::text('firstname', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('lastname', l('Surname', 'customerusers')) !!}
    {!! Form::text('lastname', null, array('class' => 'form-control')) !!}
</div><br />
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('email', l('Email', 'customerusers')) !!}
    {!! Form::text('email', null, array('placeholder' => l('your@email.com'), 'class' => 'form-control', 'required' => 'required')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('password', l('Password', 'customerusers')) !!}
    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control',  "autocomplete" => "off")) !!}
</div>
</div>

<div class="row">
<!-- div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('language_id', l('Language')) !!}
    {!! Form::select('language_id', $languageList=[], null, array('class' => 'form-control')) !!}
</div -->

           <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-active">
             {!! Form::label('active', l('Allow Customer Center access?', 'customerusers'), ['class' => 'control-label']) !!}
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

<div class="row" style="border-top: 1px solid #dddddd; padding-top: 15px;">

      <div class="form-group col-lg-3 col-md-3 col-sm-3">
          {!! Form::label('enable_quotations', l('Enable Quotations', 'customerusers'), ['class' => 'control-label']) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_quotations', '1', NULL, ['id' => 'enable_quotations_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_quotations', '0', NULL, ['id' => 'enable_quotations_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_quotations', '-1', NULL, ['id' => 'enable_quotations_default']) !!}
                   {!! l('Default', [], 'layouts') !!}
                 </label>
               </div>
             </div>
      </div>

      <div class="form-group col-lg-3 col-md-3 col-sm-3">
          {!! Form::label('enable_min_order', l('Enable minimum Order', 'customerusers')) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_min_order', '1', NULL, ['id' => 'enable_min_order_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_min_order', '0', NULL, ['id' => 'enable_min_order_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_min_order', '-1', NULL, ['id' => 'enable_min_order_default']) !!}
                   {!! l('Default', [], 'layouts') !!}
                 </label>
               </div>
             </div>
      </div>

      <div class="form-group col-lg-3 col-md-3 col-sm-3">
          {!! Form::label('min_order_value', l('Minimum Order Value', 'customerusers')) !!}
          {!! Form::text('min_order_value', null, array('class' => 'form-control')) !!}
      </div>

</div>

      </div><!-- div class="panel-body" ENDS -->

{{-- !! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-info text-right')) !! --}}
{{-- !! link_to_route('users.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !! --}}

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

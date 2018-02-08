
<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('name', l('User name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('password', l('Password')) !!}
    {!! Form::password('password', '', array('class' => 'form-control', 'required' => 'required')) !!}
</div><br />
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('firstname', l('Name')) !!}
    {!! Form::text('firstname', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('lastname', l('Surname')) !!}
    {!! Form::text('lastname', null, array('class' => 'form-control')) !!}
</div><br />
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('email', l('Email')) !!}
    {!! Form::text('email', null, array('placeholder' => l('your@email.com'), 'class' => 'form-control', 'required' => 'required')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('home_page', l('User home page')) !!}
    {!! Form::text('home_page', null, array('placeholder' => '/', 'class' => 'form-control')) !!}
</div><br />
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('language_id', l('Language')) !!}
    {!! Form::select('language_id', $languageList, null, array('class' => 'form-control')) !!}
</div>

           <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-is_admin">
             {!! Form::label('is_admin', l('Is Administrator?'), ['class' => 'control-label']) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('is_admin', '1', false, ['id' => 'is_admin_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('is_admin', '0', true, ['id' => 'is_admin_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>

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

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('users.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}

<div id="panel_customeruser">     

<div class="panel panel-primary">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Customer Center Access') }}</h3>
   </div>


        {!! Form::model($customer->user, array('xmethod' => 'PATCH', 'url' => route('abcc.account.update') )) !!}
        {{-- <input type="hidden" value="{{$customer->id}}" name="customer_id" id="customer_id"> --}}

 
      <div class="panel-body">

<div class="row">
<!--div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('name', l('User name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div -->
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('firstname', l('Name')) !!}
    {{-- {!! Form::text('firstname', null, array('class' => 'form-control')) !!} --}}
    <div class="form-control">{{ $customer->user->firstname }}</div>
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('lastname', l('Surname')) !!}
    {{-- {!! Form::text('lastname', null, array('class' => 'form-control')) !!} --}}
    <div class="form-control">{{ $customer->user->lastname }}</div>
</div>
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('email', l('Email')) !!}
    {{-- {!! Form::text('email', null, array('placeholder' => l('your@email.com'), 'class' => 'form-control', 'required' => 'required')) !!} --}}
    <div class="form-control">{{ $customer->user->email }}</div>
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('password', l('Password')) !!}
    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control',  "autocomplete" => "off")) !!}
</div>
</div>


{{-- !! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-info text-right')) !! --}}
{{-- !! link_to_route('users.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !! --}}


      </div>
      
       <div class="panel-footer text-right">
          <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
             <i class="fa fa-hdd-o"></i>
             &nbsp; {{ l('Save', [], 'layouts') }}
          </button>
       </div>

        {!! Form::close() !!}

</div><!-- div class="panel panel-info" -->

               
</div>

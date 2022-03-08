
         <div class="panel-body">

  <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 {!! $errors->has('name_fiscal') ? 'has-error' : '' !!}">
              {{ l('Fiscal Name', 'layouts') }}
              {!! Form::text('name_fiscal', null, array('class' => 'form-control', 'id' => 'name_fiscal')) !!}
              {!! $errors->first('name_fiscal', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('identification') ? 'has-error' : '' !!}">
              {{ l('Identification', 'layouts') }}
              {!! Form::text('identification', null, array('class' => 'form-control', 'id' => 'identification')) !!}
              {!! $errors->first('identification', '<span class="help-block">:message</span>') !!}
            </div>
        <div class="col-md-4">
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email" class="control-label">{{ l('Email', 'layouts') }}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
  </div>

@include('auth._form_address_fields')


  <!-- div class="row hidden">
        <div class="col-md-4">
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email" class="control-label">{{ l('Email', 'layouts') }}</label>
                {{-- !! Form::text('address[email]', null, array('class' => 'form-control', 'id' => 'email')) !! --}}
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
      <div class="col-md-3">
          <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
              <label for="password" class="control-label">{{ l('Password', [], 'layouts') }}</label>
              {{-- !! Form::text('address[phone]', null, array('class' => 'form-control', 'id' => 'phone')) !! --}}
              <input id="password" type="password" class="form-control" name="password" required>
              {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
          </div>
      </div>
      <div class="col-md-3">
          <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
              <label for="password-confirm" class="control-label">{{ l('Confirm Password', [], 'layouts') }}</label>
              {{-- !! Form::text('address[phone_mobile]', null, array('class' => 'form-control', 'id' => 'phone_mobile')) !! --}}
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
              {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
          </div>
      </div>
  </div -->

        <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
              {{ l('Notes', [], 'layouts') }}
              {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
              {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

         </div><!-- div class="panel-body" -->

         <div class="panel-footer text-right">
            <a class="btn btn-link" data-dismiss="modal" href="{!! URL::to('customers') !!}">{{l('Cancel', [], 'layouts')}}</a>
            <button class="btn btn-info" type="submit" onclick="this.form.submit();">
               <i class="fa fa-floppy-o"></i>
               &nbsp; {{l('Save', [], 'layouts')}}
            </button>
            <input type="hidden" id="nextAction" name="nextAction" value="" />
            <!-- button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#nextAction').val('completeCustomerData');this.form.submit();">
               <i class="fa fa-hdd-o"></i>
               &nbsp; {{l('Save & Complete', [], 'layouts')}}
            </button -->
         </div>


@section('scripts')  @parent 

    <script type="text/javascript">

        // Hide Alias field
        $('#alias_field').hide();

        // Hide Notes field
        $('#notes_field').hide();

        // Hide Address Email field
        // $('#address[email]').hide();

    </script>

@endsection

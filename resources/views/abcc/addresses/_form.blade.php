
<ul class="nav nav-tabs lead" style="font-size: 16px; margin-top: 10px;">
  <li class="active"><a href="#main_data" data-toggle="tab">{{ l('Address', [],'addresses') }}</a></li>
  <li><a href="#contact_data" data-toggle="tab">{{ l('Contact', [],'addresses') }}</a></li>
  <!-- li><a href="#extra_data" data-toggle="tab">{{ l('Other', [],'addresses') }}</a></li -->
</ul>

<div id="myTabContent" class="tab-content" style="padding-top: 20px;">
  <div class="tab-pane fade active in" id="main_data">

        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('alias') ? 'has-error' : '' }}">
                      {{ l('Alias', [],'addresses') }}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                              data-content="{{ l('Short name to appear in dropdown selectors.','addresses') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
                      {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
                      {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('name_commercial') ? 'has-error' : '' }}">
                      {{ l('Name', [],'addresses') }}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                              data-content="{!! l('Example: <i>Company Main Warehouse</i>.','addresses') !!}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
                      {!! Form::text('name_commercial', null, array('class' => 'form-control', 'id' => 'name_commercial')) !!}
                      {!! $errors->first('name_commercial', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('address1') ? 'has-error' : '' }}">
                      {{ l('Address (street, square, road...)', [],'addresses') }}
                      {!! Form::text('address1', null, array('class' => 'form-control', 'id' => 'address1')) !!}
                      {!! $errors->first('address1', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('address2') ? 'has-error' : '' }}">
                      {{ l('Address 2 (quarter, building...)', [],'addresses') }}
                      {!! Form::text('address2', null, array('class' => 'form-control', 'id' => 'address2')) !!}
                      {!! $errors->first('address2', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('postcode') ? 'has-error' : '' }}">
                      {{ l('Postal code', [],'addresses') }}
                      {!! Form::text('postcode', null, array('class' => 'form-control', 'id' => 'postcode')) !!}
                      {!! $errors->first('postcode', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('city') ? 'has-error' : '' }}">
                      {{  l('City', [],'addresses') }}
                      {!! Form::text('city', null, array('class' => 'form-control', 'id' => 'city')) !!}
                      {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('state_id') ? 'has-error' : '' }}">
                    {{ l('State', [],'addresses') }}
                    {!! Form::select('state_id', array('0' => l('-- Please, select --', [], 'layouts')) + ( isset($stateList) ? $stateList : [] ), null, array('class' => 'form-control', 'id' => 'state_id')) !!}
                    {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('country_id') ? 'has-error' : '' }}">
                    {{ l('Country', [],'addresses') }}
                    {!! Form::select('country_id', array('0' => l('-- Please, select --', [], 'layouts')) + $countryList, null, array('class' => 'form-control', 'id' => 'country_id')) !!}
                    {!! $errors->first('country_id', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>

        <!-- div class="row">
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
            <div class="well well-sm">
               <b>Contacto</b>
            </div>
            </div>
        </div -->

  </div>
  <div class="tab-pane fade in" id="contact_data">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                    {{ l('Contact name', [],'addresses') }}
                    {!! Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname')) !!}
                    {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                    {{ l('Contact surname', [],'addresses') }}
                    {!! Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname')) !!}
                    {!! $errors->first('lastname', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    {{ l('Email', [],'addresses') }}
                    {!! Form::text('email', null, array('class' => 'form-control', 'id' => 'email')) !!}
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                    {{ l('Phone (regular)', [],'addresses') }}
                    {!! Form::text('phone', null, array('class' => 'form-control', 'id' => 'phone')) !!}
                    {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('phone_mobile') ? 'has-error' : '' }}">
                    {{ l('Phone (mobile)', [],'addresses') }}
                    {!! Form::text('phone_mobile', null, array('class' => 'form-control', 'id' => 'phone_mobile')) !!}
                    {!! $errors->first('phone_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group {{ $errors->has('fax') ? 'has-error' : '' }}">
                    {{ l('Fax', [],'addresses') }}
                    {!! Form::text('fax', null, array('class' => 'form-control', 'id' => 'fax')) !!}
                    {!! $errors->first('fax', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

  </div>
  <div class="tab-pane fade" id="extra_data">

        <!-- div class="row">
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
            <div class="well well-sm">
               <b>Otros</b>
            </div>
            </div>
        </div -->

        <div class="row">
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
                <div class="form-group {{ $errors->has('longitude') ? 'has-error' : '' }}">
                      {{ l('Longitude') }}
                     {!! Form::text('longitude', null, array('class' => 'form-control', 'id' => 'longitude')) !!}
                     {!! $errors->first('longitude', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
                <div class="form-group {{ $errors->has('latitude') ? 'has-error' : '' }}">
                      {{ l('Latitude') }}
                     {!! Form::text('latitude', null, array('class' => 'form-control', 'id' => 'latitude')) !!}
                     {!! $errors->first('latitude', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-3">
                <div class="form-group {{ $errors->has('webshop_id') ? 'has-error' : '' }}">
                      {{ l('Webshop ID') }}
                     {!! Form::text('webshop_id', null, array('class' => 'form-control', 'id' => 'webshop_id')) !!}
                     {!! $errors->first('webshop_id', '<span class="help-block">:message</span>') !!}
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
        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

  </div>

               <!-- div class="panel-footer text-right">
                  <input type="hidden" value="" name="tab_name" id="tab_name">
                  <button class="btn xbtn-sm btn-lightblue" xstyle="background-color: #008cba;" type="submit" onclick="this.disabled=true;$('#tab_name').val('addressbook');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; Guardar
                  </button>
               </div -->

               </div><!-- div id="myTabContent" -->

		{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
        {!! link_to_route('abcc.customer.addresses.index', l('Cancel', [], 'layouts'), [], array('class' => 'btn btn-warning')) !!}


@section('scripts')  @parent 

    <script type="text/javascript">
        $('select[name="country_id"]').change(function () {
            var countryID = $(this).val();
          var stateID = {{ null !== old('state_id') ? old('state_id') : 
                ( isset($address->state_id) ? $address->state_id : 0 ) }};
            
            $.get('{{ url('/') }}/countries/' + countryID + '/getstates', function (states) {
                

                $('select[name="state_id"]').empty();
                $('select[name="state_id"]').append('<option value=0>{{ l('-- Please, select --', [], 'layouts') }}</option>');
                $.each(states, function (key, value) {
                    $('select[name="state_id"]').append('<option value=' + value.id + '>' + value.name + '</option>');
                });
                
            if ( stateID > 0 ) {
              $('select[name="state_id"]').val(stateID);
            }

            });
        });

        // Select default country
        if ( !($('select[name="country_id"]').val() > 0) ) {
          var def_countryID = {{ AbiConfiguration::get('DEF_COUNTRY') }};

          $('select[name="country_id"]').val(def_countryID);
        }

        $('select[name="country_id"]').change();

    </script>

@endsection

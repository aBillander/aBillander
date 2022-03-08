<div class="row">
    <div class="col-md-3">
        <!-- div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
            <label for="logo">{{ Lang::get('customers.label.logo') }}</label>
            <p class="small">{{ Lang::get('customers.help.logo') }}</p>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 200px; height: 80px;">
                    @if ( isset($customer)  )
                    @if (  File::exists($customer->image_path_thumbnail) )
                    {{ HTML::image($customer->image_path_thumbnail, $customer->image_name) }}
                    @endif
                    @else                        
                    <img class="img-responsive" src="http://placehold.it/200x80" alt="...">
                    @endif
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 80px;">
                </div>
                <div>
                    <span class="btn btn-default btn-file"><span class="fileinput-new">{{ Lang::get('general.select_image') }}</span>
                        <span class="fileinput-exists">{{ Lang::get('general.change') }}</span>
                        <input type="file" name="logo" id="logo"></span>
                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">{{ Lang::get('general.remove') }}</a>
                </div>
            </div>
            {{ $errors->first('logo', '<span class="help-block">:message</span>') }}
        </div -->
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-1">
                <!-- name_1 -->
                <div class="form-group">
                    <label class="control-label">Empresa</label>
                </div>
                <!-- ./ address_1 -->
            </div>
            <div class="col-md-6">
            <!-- name -->
            <div class="form-group {{ $errors->has('name_fiscal') ? 'has-error' : '' }}">
                <label class="control-label" for="name_fiscal">Nombre</label>
                <input class="form-control" type="text" name="name_fiscal" id="name_fiscal" placeholder="Nombre fiscal" value="{{ Request::old('name_fiscal', isset($customer) ? $customer->name_fiscal : null) }}" />
                {{ $errors->first('name_fiscal', '<span class="help-block">:message</span>') }}
            </div>
            <!-- ./ name -->
            </div>
            <div class="col-md-4">
            <!-- name -->
            <div class="form-group {{ $errors->has('identification') ? 'has-error' : '' }}">
                <label class="control-label" for="identification">DNI / CIF</label>
                <input class="form-control" type="text" name="identification" id="identification" placeholder="" value="{{ Request::old('identification', isset($customer) ? $customer->identification : null) }}" />
                {{ $errors->first('identification', '<span class="help-block">:message</span>') }}
            </div>
            <!-- ./ name -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-1">
                <!-- name_1 -->
                <div class="form-group">
                    <label class="control-label"> </label>
                </div>
                <!-- ./ address_1 -->
            </div>

            <div class="col-md-6">
                <!-- name -->
                <div class="form-group {{ $errors->has('name_commercial') ? 'has-error' : '' }}">
                    <label class="control-label" for="name_commercial">Nombre Comercial</label>
                    <input class="form-control" type="text" name="name_commercial" id="name_commercial" placeholder="" value="{{ Request::old('name_commercial', isset($customer) ? $customer->name_commercial : null) }}" />
                    {{ $errors->first('name_commercial', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ name -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- address_1 -->
                <div class="form-group {{ $errors->has('address1') ? 'has-error' : '' }}">
                    <label class="control-label" for="address1">Dirección</label>
                    <input class="form-control" type="text" name="address1" id="address1" placeholder="" value="{{ Request::old('address1', isset($customer) ? $customer->address1 : null) }}" />
                    {{ $errors->first('address1', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ address_1 -->
            </div>
            <div class="col-md-6">
                <!-- address_2 -->
                <div class="form-group {{ $errors->has('address2') ? 'has-error' : '' }}">
                    <label class="control-label" for="address2">Dirección (cont.)</label>
                    <input class="form-control" type="text" name="address2" id="address2" placeholder="" value="{{ Request::old('address2', isset($customer) ? $customer->address2 : null) }}" />
                    {{ $errors->first('address2', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ address_2 -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <!-- city -->
                <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                    <label class="control-label" for="city">Ciudad</label>
                    <input class="form-control" type="text" name="city" id="city" placeholder="" value="{{ Request::old('city', isset($customer) ? $customer->city : null) }}" />
                    {{ $errors->first('city', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ city -->  
            </div>
            <div class="col-md-2">
                <!-- postcode -->
                <div class="form-group {{ $errors->has('postcode') ? 'has-error' : '' }}">
                    <label class="control-label" for="postcode">Código Postal</label>
                    <input class="form-control" type="text" name="postcode" id="postcode" placeholder="" value="{{ Request::old('postcode', isset($customer) ? $customer->postcode : null) }}" />
                    {{ $errors->first('postcode', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ postcode -->
            </div>
            <div class="col-md-3">
                <!-- state -->
                <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                    <label class="control-label" for="state">Provincia</label>
                    <input class="form-control" type="text" name="state" id="state" placeholder="" value="{{ Request::old('state', isset($customer) ? $customer->state : null) }}" />
                    {{ $errors->first('state', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ state -->  
            </div>
            <div class="col-md-4">
                {{--
                <!-- country -->
                <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }}">
                    <label class="control-label" for="country_id">{{ Lang::get('general.country') }}</label>
                    @if (isset($customer))
                    <select class="form-control" name="country_id" id="country_id">
                        @foreach ($countries as $country)
                        <option {{ $customer->country_id == $country->id ? 'selected="selected"' : null }} value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>      
                    @else
                    <select class="form-control" name="country_id" id="country_id">
                        @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    @endif      
                    {{ $errors->first('country_id', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ country --> 
                --}}
                <!-- country --> 
                <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                    <label class="control-label" for="country">País</label>
                    <input class="form-control" type="text" name="country" id="country" placeholder="" value="{{ Request::old('country', isset($customer) ? $customer->country : null) }}" />
                    {{ $errors->first('country', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ country --> 
            </div>
        </div>        

        <div class="row">
            <div class="col-md-1">
                <!-- name_1 -->
                <div class="form-group">
                    <label class="control-label">Contacto</label>
                </div>
                <!-- ./ address_1 -->
            </div>
            <div class="col-md-5">
                <!-- name_1 -->
                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                    <label class="control-label" for="firstname">Nombre</label>
                    <input class="form-control" type="text" name="firstname" id="firstname" placeholder="" value="{{ Request::old('firstname', isset($customer) ? $customer->firstname : null) }}" />
                    {{ $errors->first('firstname', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ address_1 -->
            </div>
            <div class="col-md-6">
                <!-- lastname_2 -->
                <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                    <label class="control-label" for="lastname">Apellidos</label>
                    <input class="form-control" type="text" name="lastname" id="lastname" placeholder="" value="{{ Request::old('lastname', isset($customer) ? $customer->lastname : null) }}" />
                    {{ $errors->first('lastname', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ address_2 -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- phone -->
                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                    <label class="control-label" for="phone">Teléfono (fijo)</label>
                    <input class="form-control" type="text" name="phone" id="phone" placeholder="" value="{{ Request::old('phone', isset($customer) ? $customer->phone : null) }}" />
                    {{ $errors->first('phone', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ phone -->
            </div>
            <div class="col-md-4">
                <!-- mobile -->
                <div class="form-group {{ $errors->has('phone_mobile') ? 'has-error' : '' }}">
                    <label class="control-label" for="phone_mobile">Teléfono (móvil)</label>
                    <input class="form-control" type="text" name="phone_mobile" id="phone_mobile" placeholder="" value="{{ Request::old('phone_mobile', isset($customer) ? $customer->phone_mobile : null) }}" />
                    {{ $errors->first('phone_mobile', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ mobile -->
            </div>
            <div class="col-md-4">
                <!-- fax -->
                <div class="form-group {{ $errors->has('fax') ? 'has-error' : '' }}">
                    <label class="control-label" for="fax">Fax</label>
                    <input class="form-control" type="text" name="fax" id="fax" placeholder="" value="{{ Request::old('fax', isset($customer) ? $customer->fax : null) }}" />
                    {{ $errors->first('fax', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ fax -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- email -->
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label class="control-label" for="email">Correo Electónico</label>
                    <input class="form-control" type="text" name="email" id="customer_email" placeholder="" value="{{ Request::old('email', isset($customer) ? $customer->email : null) }}" />
                    {{ $errors->first('email', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ email -->
            </div>
            <div class="col-md-6">
                <!-- web -->
                <div class="form-group {{ $errors->has('website') ? 'has-error' : '' }}">
                    <label class="control-label" for="website">Sitio Web</label>
                    <input class="form-control" type="text" name="website" id="website" placeholder="http://www." value="{{ Request::old('website', isset($customer) ? $customer->website : null) }}" />
                    {{ $errors->first('website', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ web -->
            </div>
        </div>

        <div class="form-group" {{ $errors->has('notes') ? 'has-error' : '' }}">
            <label class="control-label" for="notes">Notas</label>
            {{-- Form::textarea('notes', null, array('id' => 'notes', 'class' => 'form-control', 'rows' => 4)) --}}
            <textarea id="notes" class="form-control" xcols="50" name="notes" rows="4" placeholder="">{{ Request::old('notes', isset($customer) ? $customer->notes : null) }}</textarea>
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- outstanding_amount_allowed -->
                <div class="form-group {{ $errors->has('outstanding_amount_allowed') ? 'has-error' : '' }}">
                    <label class="control-label" for="website">Riesgo Máximo</label>
                    <input class="form-control" type="text" name="outstanding_amount_allowed" id="outstanding_amount_allowed" placeholder="" value="{{ Request::old('outstanding_amount_allowed', isset($customer) ? $customer->outstanding_amount_allowed : Configuration::get('DEF_OUTSTANDING_AMOUNT')) }}" />
                    {{ $errors->first('outstanding_amount_allowed', '<span class="help-block">:message</span>') }}
                </div>
                <!-- ./ outstanding_amount_allowed -->
            </div>
            <div class="col-md-4">
                <!-- accept_einvoice -->
                <div class="form-group">
                    <label class="control-label" for="accept_einvoice"><br />¿Acepta Factura Electrónica?</label>
                    <input type="hidden" name="accept_einvoice" value="0">
                    {{ Form::checkbox('accept_einvoice', 1, (bool) Request::old('accept_einvoice', isset($customer) ? $customer->accept_einvoice : 0)) }}
                </div>
                <!-- ./ accept_einvoice -->
            </div>
            <div class="col-md-4">
                <!-- active -->
                <div class="form-group">
                    <label class="control-label" for="active"><br />Activo</label>
                    <input type="hidden" name="active" value="0">
                    {{ Form::checkbox('active', 1, (bool) Request::old('active', isset($customer) ? $customer->active : 1)) }}
                </div>
                <!-- ./ active -->
            </div>
        </div>

        <!-- form actions -->
        <div class="form-group">
            <div class="controls pull-right">
                <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('customers') }}">Cancelar</a>
                <button type="submit" id="save-customer" class="btn btn-success btn-lg">Guardar</button>
            </div>
        </div>
        <!-- ./ form actions --> 
    </div>
</div>
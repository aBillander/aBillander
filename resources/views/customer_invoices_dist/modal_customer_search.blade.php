
   <div class="modal" id="modal_customer_search">
      <div class="modal-dialog">
         <div class="modal-content">

{!! Form::open(array('url' => 'customerinvoices', 'id' => 'search_customer_invoice', 'name' => 'search_customer_invoice', 'class' => 'form')) !!}
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">{{l('Customer Search')}}</h4>
            </div>

            <div class="modal-body">

               <div id="modal-status-placeholder"></div>
               
               <fieldset>
               <div class="form-group">
                  <label class="col-sm-3 control-label text-right">{{l('Customer')}}:</label>
                  <!-- input class="form-control" type="text" name="customer_id" value="1" / -->
                  <div class="col-sm-9">
                    {!! Form::text('customer_name', null, array('id' => 'customer_name', 'class' => 'form-control customer-lookup', 'autocomplete' => 'off', 'placeholder' => l('Type a Name...'))) !!}
                  </div>
                  <input type="hidden" name="customer_id" id="customer_id" value=""/>
               </div>
               <!-- div class="form-group">
                  Fecha Factura:
                  <input class="form-control datepicker" type="text" id="fecha_alta" name="fecha_alta" value="" size="10" autocomplete="off"/>
               </div -->

    <div class="form-group" style="padding-top: 15px;">
    </div>

    <div class="form-group">
      <div class="col-sm-4" style="padding-top: 10px;">
         <label class="control-label">{{l('Also search the Commercial Name')}}:</label>
      </div>
      <div class="col-sm-8">
        <div class="radio">
          <label>
            <input name="name_commercial" id="name_commercial_on" value="1" type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="name_commercial" id="name_commercial_off" value="0" checked="checked" type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block"> </span>
      </div>
    </div>

                <!-- input class="form-control" type="text" name="recurring1" id="recurring1" value="1" / -->

                <!-- div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('fi.frequency') }}</label>
                    <div class="col-sm-9">
                        <label class="radio">
                            {{ Form::radio('xrecurring', '0', true) }}
                            {{ trans('fi.one_time') }}
                        </label>
                        <label class="radio">
                            {{ Form::radio('xrecurring', '1') }}
                            {{ trans('fi.recurring') }}
                        </label>
                    </div>
                </div -->
            </fieldset>
            </div>
            <div class="modal-footer">
               <a target="_top" class="btn btn-link" href="{{ URL::to('customers/create') }}">{{l('New Customer')}}</a>
               <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <input type="hidden" name="submitCustomer_id" value="submitCustomer_id"/>
               <!-- button id="invoice-create-confirm" class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                  <i class="fa fa-play"></i>
                  &nbsp; {{l('Select', [], 'layouts')}}
               </button -->
               <button id="invoice-create-confirm" class="btn btn-sm btn-info" type="submit">
                  <i class="fa fa-play"></i>
                  &nbsp; {{l('Select', [], 'layouts')}}
               </button>
            </div>
{!! Form::close() !!}

         </div>
      </div>
   </div>

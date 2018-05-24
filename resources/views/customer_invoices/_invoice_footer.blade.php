
      <div class="row">

         <input type="hidden" id="nextAction" name="nextAction" value="" />

         <div class="col-lg-6 col-md-6 col-sm-6">
            <!-- button class="btn btn-sm btn-warning" type="button" onclick="window.location.href='{$fsc->url()}';">
               <i class="fa fa-refresh"></i>
               &nbsp; Reset
            </button -->
            <!-- button type="button" class="btn btn-sm btn-primary xdisabled" data-toggle="tooltip" data-placement="top" title="" data-original-title=" Desactivado hasta que se guarde el documento " xonclick="$('#modal_guardar').modal('show');">
               <i class="fa fa-print"></i>
               &nbsp; {{l('Print', [], 'layouts')}}
            </button>
            <button class="btn btn-sm btn-primary disabled" type="button" onclick="$('#modal_guardar').modal('show');">
               <i class="fa fa-send"></i>
               &nbsp; {{l('Send', [], 'layouts')}}
            </button -->
            @if ( isset($invoice->id) )
            <!-- a class="btn btn-sm btn-success" href="{{ URL::to('customerinvoices/' . $invoice->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i>
               &nbsp; {{l('Show', [], 'layouts')}}
            </a -->

            <button class="btn btn-sm btn-success" type="button" onclick="this.disabled=false;$('#nextAction').val('showInvoice');this.form.submit();">
               <i class="fa fa-eye"></i>
               &nbsp; {{l('Show', [], 'layouts')}}
            </button>
            @endif
         </div>
         <div class="col-lg-6 col-md-6 col-sm-6 text-right">
            <!-- button class="btn btn-sm btn-info" type="button" onclick="$('#modal_guardar').modal('show');">
               <i class="fa fa-file"></i>
               &nbsp; Guardar Borrador
            </button -->
            <button class="btn btn-sm btn-primary" type="button" onclick="this.disabled=true;$('#nextAction').val('completeInvoice');this.form.submit();">
               <i class="fa fa-download"></i>
               &nbsp; {{l('Save & Complete', [], 'layouts')}}
            </button>
            <button class="btn btn-sm btn-primary" type="button" onclick="this.disabled=true;this.form.submit();">
               <i class="fa fa-hdd-o"></i>
               &nbsp; {{l('Save', [], 'layouts')}}
            </button>
            
            <input type="hidden" id="save_as" name="save_as" value="draft" />
            <!-- button class="btn btn-sm btn-info" type="button" onclick="this.disabled=true;$('#save_as').val('invoice');this.form.submit();">
               <i class="fa fa-hdd-o"></i>
               &nbsp; {{l('Invoice')}}
            </button -->
            
            <!-- input type="hidden" id="finish" name="finish" value="" />
            <button class="btn btn-sm btn-black" type="button" onclick="process_button_invoice(this);">
               <i class="fa fa-lock"></i>
               &nbsp; {{l('Finish Up')}}
            </button -->

            @if ($invoice->status == 'draft')
            <a class="btn btn-sm btn-info save-invoice" data-html="false" data-toggle="modal" 
                     href="javascript:void(0)" 
                     data-content="{{l('This Invoice will be saved with a Number for its Sequence, and may not be modified later on. Are you sure?')}}" 
                     data-title="{{ l('Customer Invoices') }} :: ({{$invoice->id}}) {{ $invoice->id }} " 
                     data-id="{{ $invoice->id }}" 
                     onClick="return false;"><i class="fa fa-file-text"></i> 
                     &nbsp; {{l('Invoice This')}}</a>
            @endif

         </div>
      </div>
      <div class="row">

         <div class="form-group col-lg-12 col-md-12 col-sm-12 {{{ $errors->has('notes') ? 'has-error' : '' }}}" style="margin-top: 20px;">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

      </div>

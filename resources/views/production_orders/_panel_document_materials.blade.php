



{!! Form::open( ['method' => 'POST', 'id' => 'form-quantity-selector'] ) !!}

{!! Form::hidden('document_id', $document->id, array('id' => 'document_id')) !!}


               <!-- br><h4  class="text-info" xclass="panel-title">
                   {{l('Issue Materials')}}
               </h4><br -->
              <div xclass="page-header">
                  <div class="pull-right  hide " style="padding-right: 90px;">

                      <a href="{{ URL::to('productionorders/create') }}" class="btn btn-sm btn-grey" 
                              title="{{l('Add Lots to Lines')}}"><i class="fa fa-window-restore"></i> &nbsp;{{l('Add Lots')}}</a>

                  </div>
                  <h3>
                      <span style="color: #dd4814;">{{l('Issue Materials')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3><br>        
              </div>


    <div id="div_document_availability_details">
       <div class="table-responsive">

    <table id="document_lines_availability" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">{{l('Line #')}}</th>
                        <th class="text-left">{{l('Reference')}}</th>
               			<th class="text-left">{{l('Description')}}</th>

                        <th class="text-right">{{l('On hand')}}</th>
                        <!-- th class="text-right">{{l('On order')}}</th>
                        <th class="text-right">{{l('Allocated')}}</th>
                        <th class="text-right">{{l('Available')}}</th -->

                        <th class="text-right">{{l('Planned Quantity')}}</th>

                        <th class="text-right">{{l('Real Quantity')}}</th>

                        <th class="text-center">{{l('Warehouse')}}</th>

                        <th class="text-right button-pad">
                           &nbsp; 
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Introduccir la Cantidad TOTAL consumida en cada Línea.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>


                  <a class="btn btn-sm btn-info xbtn-pressure xbtn-sensitive xlines_quick_form" title="{{l('Full quantity')}}" sxtyle="opacity: 0.65;" style="visibility: hidden" onclick="getDocumentMaterials(0)"><i class="fa fa-th"></i> </a>

                  <a class="  hidden  btn btn-sm btn-info xbtn-grey xbtn-sensitive xcreate-document-service" title="{{l('Quantity on-hand only')}}" xstyle="visibility: hidden" xstyle="background-color: #2bbbad;" onclick="getDocumentMaterials(1)"><i class="fa fa-th-large"></i> </a>


                        </th>
                      </tr>
                    </thead>

        <tbody>

    @if ($document->lines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

        @foreach ($document->lines->where('type', 'product') as $line)
            <tr>
                <td title="{{$line->id }}">{{$line->line_sort_order }} 
                  @if ( $line->product->isPack() )
                    <i class="fa fa-gift btn-xs alert-warning" title="{{l('This Product is of Type "Grouped"', 'products')}}"></i>
                  @endif
                </td>
                <td>
                <a href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->reference }}</a></td>
                <td>
                {{ $line->name }}</td>

                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_onhand    ) }}</td>
{{--
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_onorder   ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_allocated ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_available ) }}</td>
--}}
                <td class="text-right">{{ $line->as_quantity('required_quantity') }}</td>

                <td class="text-right">{{ $line->as_quantity('real_quantity') }}</td>

                <td class="text-center">{{-- Almacén para sacar el Producto --}}

                    {!! Form::select('dispatch_warehouse['. $line->id .']', $warehouseList, $line->warehouse_id, array('class' => 'form-control input-sm', 'id' => 'dispatch_warehouse['. $line->id .']')) !!}
                </td>

                <td class="text-right active">

<input name="dispatch[{{ $line->id }}]" class="form-control input-sm" type="text" size="3" xmaxlength="5" style="min-width: 0;
    xwidth: auto;
    display: inline;" value="{{ $line->as_quantityable($line->quantity_onhand) }}" onclick="this.select()">
    
                </td>

            </tr>
            
        @endforeach

    @else
    <tr><td colspan="9">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td></tr>
    @endif

        </tbody>
    </table>

       </div>
    </div>


    @if ($document->lines->count())

              <div class="panel-body well" style="margin-top: 30px">
{{--    

                  <h3>
                      <span style="color: #dd4814;">{{l('Finish Order')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3>

<div class="row">
Poner campos de cabecera: producto, cantidad, fecha terminación, almacén, lote!!! O quizá desacoplar la entrada de producto terminado de la salida de materias primas ???<br/><br/>http://localhost/thehub/public/productionorders/43757/edit<br/><br/>

         <div class="col-lg-4 col-md-4 col-sm-4 {{ $errors->has('shippingslip_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Date') }}
               {!! Form::text('shippingslip_date_form', abi_date_form_short( 'now' ), array('class' => 'form-control', 'id' => 'shippingslip_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('shippingslip_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('shippingslip_template_id') ? 'has-error' : '' }}">
            {{ l('Template') }}
            {!! Form::select('shippingslip_template_id', [], null, array('class' => 'form-control', 'id' => 'shippingslip_template_id')) !!}
            {!! $errors->first('shippingslip_template_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('shippingslip_sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('shippingslip_sequence_id', [], old('shippingslip_sequence_id'), array('class' => 'form-control', 'id' => 'shippingslip_sequence_id')) !!}
            {!! $errors->first('shippingslip_sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>

<div class="row">

         <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-backorder">
           {!! Form::label('backorder', l('Create Back-Order?'), ['class' => 'control-label']) !!}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('A new Customer Order will be created if Quantity on-hand is less than Order Quantity.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
           <div>
             <div class="radio-inline">
               <label>
                 {!! Form::radio('backorder', '1', \App\Configuration::isTrue('ALLOW_CUSTOMER_BACKORDERS'), ['id' => 'backorder_on']) !!}
                 {!! l('Yes', [], 'layouts') !!}
               </label>
             </div>
             <div class="radio-inline">
               <label>
                 {!! Form::radio('backorder', '0', \App\Configuration::isFalse('ALLOW_CUSTOMER_BACKORDERS'), ['id' => 'backorder_off']) !!}
                 {!! l('No', [], 'layouts') !!}
               </label>
             </div>
           </div>
         </div>

</div>
--}}

                  <div class="xpanel-footer pull-right" style="margin-top: 10px">

                        <a class="btn btn-info" href="javascript:void(0);" title="{{l('Confirm', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-quantity-selector').attr('action', '{{ route( 'productionorders.setmaterials', [$document->id] )}}');$('#form-quantity-selector').submit();return false;"><i class="fa fa-cubes"></i> &nbsp;{{l('Update', 'layouts')}}</a>
                  
                  </div>

                  </div>

    @endif

{!! Form::close() !!}


    <!-- div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Lines') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} - - >
        </h3>        
    </div -->

    <div id="div_document_lines">
       <div class="table-responsive">

    <table id="document_lines" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left" style="width: 60px"></th>
               <th class="text-left">{{l('Reference')}}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Drag to Sort.', 'layouts') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a></th>
               <th class="text-left">{{l('Description')}}</th>
               <th class="text-right" xxwidth="90">{{l('Quantity')}}</th>

               <th class="text-right">{{l('Price')}}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Unit final Price after Discount and Taxes.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a></th>
               <th class="text-right" width="90">{{l('Disc. %')}}</th>

               <th class="text-right" xwidth="90">{{l('Total')}}</th> 
               <th class="text-right" xwidth="90">{{l('With Tax')}}</th> 
               {{-- quantity * (price - discount) Con tax o no depende de la configuración de meter precios con impuesto incluido --}}
               <!-- th class="text-right" xwidth="115">{{l('Tax')}}</th -->

               <!-- th class="text-right">{ {l ('Line Total')} }</th --> {{-- amount * tax --}}

               <th class="text-left" style="width:1px; white-space: nowrap;"></th>
               <th class="text-left" xwidth="115">{{l('Notes', 'layouts')}}</th>
                <th class="text-right button-pad"> 
                      @if ( $document->editable )
{{--
                  <a class="btn btn-sm btn-magick xbtn-pressure xbtn-sensitive lines_quick_form" title="{{l('Quick Add Lines')}}"><i class="fa fa-plus"></i> <i class="fa fa-superpowers"></i> </a>
--}}
                  <a class="btn btn-sm btn-success create-document-product" title="{{l('Add Product')}}"><i class="fa fa-plus"></i> <i class="fa fa-shopping-basket"></i> </a>
{{--
                  <a class="btn btn-sm btn-success create-document-service" title="{{l('Add Service')}}" style="background-color: #2bbbad;"><i class="fa fa-plus"></i> <i class="fa fa-handshake-o"></i> </a>
--}}

                <div class="btn-group">
                    <a href="#" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Service')}}" style="background-color: #2bbbad;"> <i class="fa fa-handshake-o"></i> &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right" style="overflow: visible">
                      <li><a class="create-document-service"><i class="fa fa-handshake-o"></i> {{l('Add Service')}}</a></li>
                      <li><a class="create-document-comment"><i class="fa fa-file-text-o"></i> {{l('Add Text Line')}}</a></li>
                      <!-- li class="divider"></li -->
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>
{{--
<div class="btn-group" xstyle="width:98%">
  <a href="#" class="btn btn-sm btn-success create-document-product"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
  <a href="#" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
  <ul class="dropdown-menu  pull-right">
    <li><a class="create-document-product"       href="#">{{l('Product')}}</a></li>
    <li><a class="create-document-service"       href="#">{{l('Service')}}</a></li>
    <li><a class="create-document-discount-line" href="#">{{l('Discount Line')}}</a></li>
    <!-- li><a class="create-document-text-line"     href="#">{{l('Text Line')}}</a></li -->

    <!-- li class="divider"></li>
    <li><a href="#">Separated link</a></li -->
  </ul>
</div>
--}}
                      @else

                      @endif

                </th>
            </tr>
        </thead>
        <tbody class="sortable">

    @if ($document->lines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($document->lines as $line)
            <tr data-id="{{ $line->id }}" data-sort-order="{{ $line->line_sort_order }}">
                <td>[{{ $line->id }}] {{$line->line_sort_order }}</td>
@if($line->line_type=='comment')
                <td class="text-right">{{-- $line->reference --}}</td>
                <td class="active" colspan=3><strong>{{ $line->name }}</strong></td>
                <!-- td class="text-right"> </td>
                <td class="text-right"> </td -->
                <td class="text-right"> </td>
                <td class="text-right"> </td>
                <td class="text-right"> </td>
                <!-- td class="text-right">{{ $line->as_priceable($line->total_tax_incl - $line->total_tax_excl) }}</td -->
                <td class="text-center"> </td>
@else
                <td><a href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->reference }}</a></td>
                <td>
                @if($line->line_type == 'shipping')
                  <i class="fa fa-truck abi-help" title="{{l('Shipping Cost')}}"></i> 
                @endif
                {{ $line->name }}</td>
                <td class="text-right">{{ $line->as_quantity('quantity') }}
                        @if ($line->package_measure_unit_id != $line->measure_unit_id && $line->pmu_label != '')
                            <p class="text-right text-info">
                                {{ optional($line->packagemeasureunit)->name }}

                                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                                    xdata-trigger="focus"
                                    data-html="true" 
                                    data-content="{!! $line->pmu_label !!}">
                                    <i class="fa fa-question-circle abi-help" style="color: #9a00cd;"></i>
                                 </a>
                            </p>
                        @endif
                        @if ($line->extra_quantity > 0.0 && $line->extra_quantity_label != '')
                            <p class="text-right text-info">
                                +{{ $line->as_quantity('extra_quantity') }}{{ l(' extra') }}

                                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                                    xdata-trigger="focus"
                                    data-html="true" 
                                    data-content="{{ $line->extra_quantity_label }}">
                                    <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                                 </a>
                            </p>
                        @endif
                </td>
                <td class="text-right">{{ $line->as_price('unit_supplier_final_price') }}</td>
                <td class="text-right">{{ $line->as_percent('discount_percent') }}</td>
                <td class="text-right">{{ $line->as_price('total_tax_excl') }}</td>
                <td class="text-right">{{ $line->as_price('total_tax_incl') }}</td>
                <!-- td class="text-right">{{ $line->as_priceable($line->total_tax_incl - $line->total_tax_excl) }}</td -->
                <td class="text-center">
                @if($line->sales_equalization)
                  <i class="fa fa-tag" style="color: #38b44a" title="{{l('Equalization Tax')}}"></i> 
                @endif
                </td>
@endif
                <td class="text-center">
                @if ($line->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="left" 
                            data-content="{{ $line->notes }}">
                        <i class="fa fa-paperclip"></i> {{-- l('View', [], 'layouts') --}}
                    </button>
                 </a>
                @endif</td>
                <td class="text-right button-pad">
                    
@if ( AbiConfiguration::isTrue('ENABLE_LOTS') && ($line->line_type == 'product') && ($line->product->lot_tracking > 0) )
@php
  $color = $line->pending > 0 ? 'alert-danger' : 'btn-grey';
  $msg   = $line->pending > 0 ? ' ('.$line->measureunit->quantityable( $line->pending ).')' : '';
@endphp
                    
                    <a class="btn btn-sm {{ $color }} lotable-document-line" data-id="{{$line->id}}" 
                      data-title="{{ '['.$line->reference.'] '.$line->name }}" 
                      data-quantity_label="{{ $line->measureunit->quantityable($line->quantity) .' ('.$line->measureunit->name.')'}}" 
                      data-type="{{$line->line_type}}" title="{{l('Add Lots to Line')}}" onClick="return false;"><i class="fa fa-window-restore"></i>{{ $msg }}</a>
                    
@endif
                      @if ( $document->editable )
                    <!-- a class="btn btn-sm btn-info" title="{{l('XXXXXS', [], 'layouts')}}" onClick="loadsupplierdocumentlines();"><i class="fa fa-pencil"></i></a -->
                    
                      @if ( !$line->locked || AbiConfiguration::isTrue('ENABLE_CRAZY_IVAN') )

                    <a class="btn btn-sm btn-warning edit-document-line" data-id="{{$line->id}}" data-type="{{$line->line_type}}" title="{{l('Edit', [], 'layouts')}}" onClick="return false;"><i class="fa fa-pencil"></i></a>
                    
                    <a class="btn btn-sm btn-danger delete-document-line" data-id="{{$line->id}}" title="{{l('Delete', [], 'layouts')}}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '('.$line->id.') ['.$line->reference.'] '.$line->name }}" 
                        onClick="return false;"><i class="fa fa-trash-o"></i></a>

                      @endif

                      @else
                      
                      @endif
                    
                      @if ( 0 && $line->product_id )

                    <a class="btn btn-sm btn-blue show-supplier-consumption" data-id="{{$line->product_id}}" title="{{l('Show Supplier consumption')}}" onClick="return false;"><i class="fa fa-dropbox"></i></a>

                      @endif

                </td>
            </tr>
            
            @endforeach

            @php
                $max_line_sort_order = $line->line_sort_order;
            @endphp

    @else
    <tr><td colspan="10">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td>
    <td></td></tr>
    @endif

        </tbody>
    </table>

    <input type="hidden" name="next_line_sort_order" id="next_line_sort_order" value="{{ ($line->line_sort_order ?? 0) + 10 }}">

       </div>
    </div>


{{-- ******************************************************************************* --}}


<div id="msg-success-update" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-update-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<div id="panel_document_total" class="">
  
    @include($view_path.'._panel_document_total')

</div>


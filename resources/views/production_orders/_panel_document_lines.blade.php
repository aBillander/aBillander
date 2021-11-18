
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
               <th class="text-right">{{l('Planned Quantity')}}</th>
               <th class="text-right">{{l('Real Quantity')}}</th>

               <th class="text-right"> </th>
               <th class="text-right">{{l('Warehouse')}}</th>

                <th class="text-right button-pad"> 
                      @if ( 0 && $document->editable )

                  <a class="btn btn-sm btn-magick xbtn-pressure xbtn-sensitive lines_quick_form  hide " title="{{l('Quick Add Lines')}}"><i class="fa fa-plus"></i> <i class="fa fa-superpowers"></i> </a>

                  <a class="btn btn-sm btn-success create-document-product" title="{{l('Add Product')}}"><i class="fa fa-plus"></i> <i class="fa fa-shopping-basket"></i> </a>
{{--
                  <a class="btn btn-sm btn-success create-document-service" title="{{l('Add Service')}}" style="background-color: #2bbbad;"><i class="fa fa-plus"></i> <i class="fa fa-handshake-o"></i> </a>
--}}

                <div class="btn-group  hide ">
                    <a href="#" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Service')}}" style="background-color: #2bbbad;"> <i class="fa fa-handshake-o"></i> &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right" style="overflow: visible">
                      <li><a class="create-document-service"><i class="fa fa-handshake-o"></i> {{l('Add Service')}}</a></li>
                      <li><a class="create-document-comment"><i class="fa fa-file-text-o"></i> {{l('Add Text Line')}}</a></li>
                      <li class="divider"></li>
                      <li><a class="create-document-shipping"><i class="fa fa-truck"></i> {{l('Add Shipping Cost')}}</a></li>
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
@if($line->type=='comment')
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
                <td>{{ $line->type }} <a href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->reference }}</a></td>
                <td>
                @if($line->type == 'shipping')
                  <i class="fa fa-truck abi-help" title="{{l('Shipping Cost')}}"></i> 
                @endif
                {{ $line->name }}

@if ( $document->status == 'finished' )
@if ( \App\Configuration::isTrue('ENABLE_LOTS') && ($line->type == 'product') && ($line->product->lot_tracking > 0) )

    @include('production_orders._chunck_line_lots')

@endif
@endif

                </td>
                <td class="text-right">{{ $line->as_quantity('required_quantity') }}
                </td>
                <td class="text-right">{{ $line->as_quantity('real_quantity') }}
                </td>
                <td class="text-left">
                    <span class="badge" style="background-color: #3a87ad;" title="{{ optional($line->measureunit)->name }}"> &nbsp; {{ optional($line->measureunit)->sign }} &nbsp; </span>
                </td>
                <td class="text-right" title="{{ $line->warehouse->alias_name ?? '-' }}">{{ $line->warehouse->alias ?? '-' }}
                </td>
@endif

                <td class="text-right button-pad">
                      @if ( 0 && $document->editable )
                    <!-- a class="btn btn-sm btn-info" title="{{l('XXXXXS', [], 'layouts')}}" onClick="loadcustomerdocumentlines();"><i class="fa fa-pencil"></i></a -->

@if ( \App\Configuration::isTrue('ENABLE_LOTS') && ($line->type == 'product') && ($line->product->lot_tracking > 0) )
@php
  $color = $line->pending > 0 ? 'alert-danger' : 'btn-grey';
  $msg   = $line->pending > 0 ? ' ('.$line->measureunit->quantityable( $line->pending ).')' : '';
@endphp
                    
                    <a class="btn btn-sm {{ $color }} add-lots-to-line" data-id="{{$line->id}}" 
                      data-title="{{ '['.$line->reference.'] '.$line->name }}" 
                      data-quantity_label="{{ $line->measureunit->quantityable($line->quantity) .' '.$line->measureunit->name}}" 
                      data-type="{{$line->type}}" title="{{l('Add Lots to Line')}}" onClick="return false;"><i class="fa fa-window-restore"></i>{{ $msg }}</a>

@endif
                    
                    <a class="btn btn-sm btn-warning edit-document-line" data-id="{{$line->id}}" data-type="{{$line->type}}" title="{{l('Edit', [], 'layouts')}}" onClick="return false;"><i class="fa fa-pencil"></i></a>
                    
                    <a class="btn btn-sm btn-danger delete-document-line" data-id="{{$line->id}}" title="{{l('Delete', [], 'layouts')}}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '('.$line->id.') ['.$line->reference.'] '.$line->name }}" 
                        onClick="return false;"><i class="fa fa-trash-o"></i></a>

                      @else
                      
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
  
    @include('production_orders._panel_document_line_tools')

</div>


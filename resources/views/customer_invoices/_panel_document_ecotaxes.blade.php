
               
              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Ecotaxes')}}</span> 
                       
                  </h3><br>        
              </div>



    <div id="div_document_ecotaxes">
       <div class="table-responsive">

    <table id="document_total_breakdown" class="table table-hover">
        <thead>
            <tr>
              <th class="text-left">{{l('ID', [], 'layouts')}}</th>
              <th>{{l('Ecotax Name')}}</th>
              <th>{{l('Ecotax Amount')}}</th>
              <th>{{l('Ecotax Document Amount')}}</th>
            </tr>
        </thead>


      <tbody>
@php

$document->loadLineEcotaxes();

@endphp

@foreach( $documentecotaxes = $document->documentecotaxes() as $documentecotax )
        <tr>
          <td>{{ $documentecotax['ecotax_id'] }}</td>
          <td>{{ $documentecotax['ecotax_name'] }}</td>
          <td>{{ $document->as_priceable($documentecotax['ecotax_amount']) }}</td>
          <td>{{ $document->as_priceable($documentecotax['net_amount']) }}</td>

{{--
          <td>{{ $alltax->as_percentable( $alltax->percent ) }}</td>
          <td>{{ $alltax->as_priceable($total['net_amount']) }}</td>
          <!-- td>{{ '' }}</td -->
          <td>{{ $alltax->as_priceable($iva->taxable_base) }}</td>
          <td>{{ $alltax->as_priceable($iva->total_line_tax) }}</td>
          <td>{{ optional($re)->as_percent('percent') ?? '' }}</td>
          <td>{{ optional($re)->as_price('taxable_base') ?? '' }}</td>
          <td>{{ optional($re)->as_price('total_line_tax') ?? '' }}</td>
--}}
        </tr>
@endforeach

        <tr>
          <td> </td>
          <td> </td>
          <td class="text-right"><strong>{{l('Total')}}:</strong></td>
          <td><strong>{{ $document->as_priceable($documentecotaxes->sum('net_amount')) }}</strong></td>
        </tr>

    </table>

       </div>
    </div>

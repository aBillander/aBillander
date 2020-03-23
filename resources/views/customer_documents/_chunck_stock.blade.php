

                  <h4>
                      <span style="color: #dd4814;">{{ l('Availability') }}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h4>

<div id="div_customer_rules">



   <div class="table-responsive">


<table id="customer_rules" class="table table-hover">
    <thead>
                        <th class="text-right">{{l('On hand')}}</th>
                        <th class="text-right">{{l('On order')}}</th>
                        <th class="text-right">{{l('Allocated')}}</th>
                        <th class="text-right">{{l('Available')}}</th>
    </thead>
    <tbody>
            <tr>
                <td class="text-right {{ $product->quantity_onhand > 0 ? '' : 'alert-danger' }}">
                    {{ $product->as_quantityable( $product->quantity_onhand    ) }}</td>
                <td class="text-right">{{ $product->as_quantityable( $product->quantity_onorder   ) }}</td>
                <td class="text-right">{{ $product->as_quantityable( $product->quantity_allocated ) }}</td>
                <td class="text-right {{ $product->quantity_available > 0 ? '' : 'alert-danger' }}">
                    {{ $product->as_quantityable( $product->quantity_available ) }}</td>
            </tr>
    </tbody>
</table>


   </div><!-- div class="table-responsive" ENDS -->


</div><!-- div id="div_customer_rules" ENDS -->




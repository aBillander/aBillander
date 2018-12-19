
	<div class="col-md-10 col-md-offset-1" xstyle="margin-top: 50px">
		<div class="panel panel-primary" id="panel_invoice_profit">
            <div class="panel-heading">
               <h3 class="panel-title">
    	             {{l('Profitability Analysis')}}
               </h3>
            </div>

               <div class="well well-sm" style="background-color: #d9edf7; border-color: #bce8f1; color: #3a87ad;">
	               <b>{{l('Invoice')}}</b>: {{$invoice->document_reference}} <br>
	               <b>{{l('Customer')}}</b>: <a href="{$fsc->factura->cliente_url()}">{{ $customer->name_fiscal }}</a><br>
	               @if ($customer->salesrep)
                 <b>{{l('Agente')}}</b>: <a href="{$fsc->agente->url()}">{{ $customer->salesrep->alias }}</a><br>
                 @endif
               </div>

            <div class="panel-body">
               <b>{{l('Cost-benefit per line')}}</b><br><br>
               <span id="profit_details">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th class="text-left">{{l('Qty.')}}</th>
                        <th class="text-left">{{l('Reference')}}</th>
                        <th class="text-left">{{l('Price')}}</th>
                        <th class="text-left">{{l('Disc. %')}}</th>
                        <th class="text-left">{{l('Net')}}</th>
                        <th class="text-right">{{l('Cost')}}</th>
                        <th class="text-right">{{l('Margin 1 (%)')}}</th>
                        <th class="text-right">{{l('Commission (%)')}}</th>
                        <th class="text-right">{{l('Margin 2 (%)')}}</th>
                      </tr>
                    </thead>

                    <tbody id="profit_detail_lines">
                    </tbody>

                  </table>
               </span><br>

               <b>{{l('Order Cost-Benefit Analysis')}}</b><br><br>
               <span id="profit">
                    <table class="table table-condensed">
                      <thead>
                        <tr>
                          <th class="text-left">{{l('Price')}}</th>
                          <th class="text-left">{{l('Disc. %')}}</th>
                          <th class="text-left">{{l('Net')}}</th>
                          <th class="text-right">{{l('Cost')}}</th>
                          <th class="text-right">{{l('Margin 1 (%)')}}</th>
                          <th class="text-right">{{l('Commission')}}</th>
                          <th class="text-right">{{l('Margin 2 (%)')}}</th>
                        </tr>
                      </thead>

                      <tbody id="profit_detail">
                      </tbody>

                    </table>
               </span><br>
               <br>

               <b>{{l('Margin')}}</b>: 
                    {{ \App\Configuration::get('MARGIN_METHOD') == 'CST' ?
                          l('Margin calculation is based on Cost Price', [], 'layouts') :
                          l('Margin calculation is based on Sales Price', [], 'layouts') }}
               <br>
            </div>

		</div>
	</div>

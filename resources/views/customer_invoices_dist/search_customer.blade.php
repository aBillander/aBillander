
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <a href="{{ URL::to('customerinvoices/create') }}" class="btn btn btn-success" 
                        title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>  
                <a href="{{ URL::to('customerinvoices') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{l('Back to Customer Invoices')}}</a>
            </div>

              <h2><a href="{{ URL::to('customerinvoices') }}">{{l('Customer Invoices')}}</a> <span style="color: #cccccc;">/</span> {{l('New Invoice')}}</h2>
           
        </div>
    </div>
</div> 

   @include('customer_invoices.modal_customer_search')

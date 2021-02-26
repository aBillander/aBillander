
<div class="row">
    <div class="form-group col-lg-1 col-md-1 col-sm-1">
      {{-- Poor Man offset! --}}
    </div>
              
    <div class="form-group col-lg-10 col-md-10 col-sm-10">

        <div class="alert alert-dismissible alert-warning">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h4>{!! l('Warning', [], 'layouts') !!}: </h4>
          <p>{{ l('An Advance to Supplier must be associated with a Purchase Order.') }}.</p>
        </div>


    </div>
</div>


  <a href="{{ URL::to('supplierorders') }}" class="btn xbtn-sm btn-success"><i class="fa fa-mail-forward"></i> &nbsp;{{l('Go to Supplier Orders')}}</a>
	{!! link_to_route('supplier.downpayments.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}

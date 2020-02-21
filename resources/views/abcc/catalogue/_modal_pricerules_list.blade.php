

	 <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h4 class="modal-title">{{ l('Show Special Prices') }} :: <label class="label label-default">{{ optional($product)->reference }}</label> {{ optional($product)->name }} - <label class="label alert-success">{{ l('Regular Price (per unit)') }}: {{ rtrim($customer_price->getPrice(), '0') }}{{ $currency->sign }}</label>
                   <a href="javascript:void(0);" data-toggle="popover" data-placement="bottom" data-html="true" data-container="body" 
                          data-content="{{ l('Prices are exclusive of Tax', 'abcc/catalogue') }}
@if( \App\Configuration::isTrue('ENABLE_ECOTAXES') )
    <br />
    {!! l('Prices are inclusive of Ecotax', 'abcc/catalogue') !!}
@endif
                  ">
                      <i class="fa fa-question-circle abi-help"></i>
                   </a>
        </h4>
	 </div>

	 <div class="modal-body">


@if ($customer_rules->count())

      @include('abcc.catalogue._block_pricerules_price')

      @include('abcc.catalogue._block_pricerules_pack')

      @include('abcc.catalogue._block_pricerules_promo')


@else

  <div class="alert alert-warning alert-block">
      <i class="fa fa-warning"></i>
      {{l('No records found', [], 'layouts')}}
  </div>

@endif



	 </div><!-- div class="modal-body" ENDS -->

	<div class="modal-footer">

	   <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Back', [], 'layouts')}}</button>

	   <!-- button type="submit" class="btn btn-success" name="modal_edit_document_line_productSubmit" id="modal_edit_document_line_productSubmit">
	    <i class="fa fa-thumbs-up"></i>
	    &nbsp; {{l('Update', [], 'layouts')}}</button -->

	</div>

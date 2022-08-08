<!-- default value -->
@php
    $go_back_url = "action('SellPosController@index')";
    $transaction_sub_type = '';
    $view_suspended_sell_url = "action('SellController@index')".'?suspended=1';
    $pos_redirect_url = "action('SellPosController@create')";
@endphp

@if(!empty($pos_module_data))
    @foreach($pos_module_data as $key => $value)
        @php
            if(!empty($value['go_back_url'])) {
                $go_back_url = $value['go_back_url'];
            }

            if(!empty($value['transaction_sub_type'])) {
                $transaction_sub_type = $value['transaction_sub_type'];
                $view_suspended_sell_url .= '&transaction_sub_type='.$transaction_sub_type;
                $pos_redirect_url .= '?sub_type='.$transaction_sub_type;
            }
        @endphp
    @endforeach
@endif

<input type="hidden" name="transaction_sub_type" id="transaction_sub_type" value="{{$transaction_sub_type}}">

<div class="col-md-12 no-print pos-header">
  <input type="hidden" id="pos_redirect_url" value="{{$pos_redirect_url}}">
  <div class="row">
    <div class="col-md-6">
      <div class="m-6 mt-5" style="display: flex;">
        <p ><strong>{{ 'Wath, Ltd.' }}</strong>

          @if(!empty($transaction->location_id)) {{$transaction->location->name}} @endif &nbsp; 

          <span class="curr_datetime">{{ \Carbon\Carbon::now()->format(auth()->user()->language->date_format_full) }}</span> 

{{--
          <i class="fa fa-keyboard hover-q text-muted" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('pos::pos.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""></i>
--}}

        </p>
      </div>
    </div>
    <div class="col-md-6">
      <a href="{{ route('pos::dashboard') }}" title="{{ l('Go back to POS Dashboard') }}" class="btn btn-info btn-flat m-6 btn-xs m-5 pull-right">
        <strong><i class="fa fa-backward fa-lg"></i></strong>
      </a>

      <button type="button" id="close_register" title="{{ l('Close Cash Register') }}" class="btn btn-danger btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-container=".close_register_modal" 
          data-href="{ { action('CashRegisterController@getCloseRegister')} }">
            <strong><i class="fa fa-window-close fa-lg"></i></strong>
      </button>

      
      <button type="button" id="register_details" title="{{ l('Session details') }}" class="btn btn-success btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-container=".register_details_modal" 
          data-href="{ { action('CashRegisterController@getRegisterDetails')} }">
            <strong><i class="fa fa-briefcase fa-lg" aria-hidden="true"></i></strong>
      </button>

      <button title="{{ l('Calculator') }}" id="btnCalculator" type="button" class="btn btn-success btn-flat pull-right m-5 btn-xs mt-10 popover-default" data-toggle="popover" data-trigger="click" data-content='@include("pos::layouts.partials.calculator")' data-html="true" data-placement="bottom">
            <strong><i class="fa fa-calculator fa-lg" aria-hidden="true"></i></strong>
      </button>

      <button type="button" class="btn btn-danger btn-flat m-6 btn-xs m-5 pull-right" id="return_sale" title="{{ l('Sell Return') }}" data-toggle="popover" data-trigger="click" data-content='<div class="m-8"><input type="text" class="form-control" placeholder="{{ l('Order No.') }}" id="send_for_sell_return_invoice_no"></div><div class="w-100 text-center"><button type="button" class="btn btn-danger" id="send_for_sell_return">{{ l('Send') }}</button></div>' data-html="true" data-placement="bottom">
            <strong><i class="fas fa-undo fa-lg"></i></strong>
      </button>

      <button type="button" title="{{ l('Press F11 to go/exit Full Screen') }}" class="btn btn-primary btn-flat m-6 hidden-xs btn-xs m-5 pull-right" id="full_screen">
            <strong><i class="fa fa-window-maximize fa-lg"></i></strong>
      </button>

      <button type="button" id="view_suspended_sales" title="{{ l('View Suspended Sales') }}" class="btn bg-yellow btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-container=".view_modal" 
          data-href="{{"action('SellController@index')".'?suspended=1'}}">
            <strong><i class="fa fa-pause-circle fa-lg"></i></strong>
      </button>

      {{-- @if(empty($pos_settings['hide_product_suggestion']) && isMobile()) --}}
      @if(0 && empty($pos_settings['hide_product_suggestion']))
        <button type="button" title="{{ __('lang_v1.view_products') }}"   
          data-placement="bottom" class="btn btn-success btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-toggle="modal" data-target="#mobile_product_suggestion_modal">
            <strong><i class="fa fa-cubes fa-lg"></i></strong>
        </button>
      @endif

{{--
      @if(Module::has('Repair') && $transaction_sub_type != 'repair')
        @include('repair::layouts.partials.pos_header')
      @endif
--}}

        <button type="button" title="{{ l('Add Expense') }}"   
          data-placement="bottom" class="btn bg-purple btn-flat m-6 btn-xs m-5 btn-modal pull-right" id="add_expense">
            <strong><i class="fa fas fa-minus-circle"></i> {{ l('Add Expense') }}</strong>
        </button>


    </div>
    
  </div>
</div>

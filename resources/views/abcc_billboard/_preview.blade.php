

<div class="jumbotron">

@if (\App\Configuration::get('ABCC_BB_ACTIVE') != 'none')

      @if (\App\Configuration::get('ABCC_BB_ACTIVE') != 'text')
            <img src="{{ URL::to( abi_tenant_local_path( 'images_bb/' ) . \App\Configuration::get('ABCC_BB_IMAGE') ) }}" class="img-responsive center-block" style="border: 1px solid #dddddd;">
      @endif

      @if (\App\Configuration::get('ABCC_BB_ACTIVE') != 'image')
            <div class="text-center" style="margin-top: 12px;">
                {!! \App\Configuration::get('ABCC_BB_CAPTION') !!}
            </div>
      @endif

@else

<img src="{{URL::to('/assets/theme/images/Dashboard.jpg')}}" 
                    title=""
                    class="center-block"
                    style=" border: 1px solid #dddddd;
                            border-radius: 18px;
                            -moz-border-radius: 18px;
                            -khtml-border-radius: 18px;
                            -webkit-border-radius: 18px;">

@endif

</div>


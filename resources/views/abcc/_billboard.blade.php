
<div class="jumbotron container-fluid">

@if ( \App\Configuration::get('ABCC_BB_ACTIVE') && (\App\Configuration::get('ABCC_BB_ACTIVE') != 'none') )

      @if (\App\Configuration::get('ABCC_BB_ACTIVE') != 'text')
            <img src="{{ URL::to( abi_tenant_local_path( 'images_bb/' ) . \App\Configuration::get('ABCC_BB_IMAGE') ) }}" class="img-responsive center-block" style="border: 1px solid #dddddd;">
      @endif

      @if (\App\Configuration::get('ABCC_BB_ACTIVE') != 'image')
            <div class="text-center" style="margin-top: 12px;">
                {!! \App\Configuration::get('ABCC_BB_CAPTION') !!}
            </div>
      @endif

@else
  <!-- h1>Jumbotron</h1>
  <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
  <p><a class="btn btn-primary btn-lg">Learn more</a></p -->
<img src="{{URL::to('/assets/theme/images/Dashboard.jpg')}}" 
                    title=""
                    class="center-block"
                    style=" border: 1px solid #dddddd;
                            border-radius: 18px;
                            -moz-border-radius: 18px;
                            -khtml-border-radius: 18px;
                            -webkit-border-radius: 18px;">
{{-- HTML::image('img/picture.jpg', 'a picture', array('class' => 'thumb')) --}}

@endif

</div>

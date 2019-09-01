
   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">

<div class="page-header">
    <div class="pull-right hide" xstyle="padding-top: 4px;">

        <a href="{{ URL::to('productionsheets/'.$sheet->id.'/calculate') }}" class="btn btn-success"><i class="fa fa-cog"></i> {{ l('Update Sheet') }}</a>

        <a href="{{ URL::to('productionsheets') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Production Sheets') }}</a>
    </div>
@php

  $work_center_id = 1;
  $work_center =\App\WorkCenter::findOrFail($work_center_id);

@endphp
    <h3>
        <a href="#">{{ l('Documentos') }}</a> <span style="color: #cccccc;">::</span> {{ $work_center->name }}
    </h3>

        <a href="{{ route('productionsheet.summary.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id]) }}" class="btn btn-success" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Resumen') }}</a>

        <a href="{{ route('productionsheet.preassemblies.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id]) }}" class="btn btn-warning" target="_blank" style="margin-right: 32px; margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Semi-Elaborados') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'espelta']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Espelta') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'trigo']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Trigo') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'centeno']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Centeno') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'combi']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Combi') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'multigrano']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Multigrano') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'chapatayhogaza']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Chapatas y Hogazas') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'candeal']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Candeal') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'picoyreganats']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Picos y Regañás TS') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'picoyreganaesp']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Regañás Esp') }}</a>

@php

  $work_center_id = 2;
  $work_center =\App\WorkCenter::findOrFail($work_center_id);

@endphp
    <h3>
        <a href="#">{{ l('Documentos') }}</a> <span style="color: #cccccc;">::</span> {{ $work_center->name }}
    </h3>

<div class="row">

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'pansingluten']) }}" class="btn btn-info" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Panes sin Gluten') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'mollete']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Mollete') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'arroz']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Arroz') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'maiz']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Maíz') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'sarracenoh']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Sarraceno H') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'sarracenosem']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Sarraceno Semillas') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'sarraceno100']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Sarraceno 100%') }}</a>

</div>

<div class="row">

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'bizcocholimbrown']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Bizcochos Limón y Brownies') }}</a>

        <a href="{{ route('productionsheet.manufacturing.pdf', [$sheet->id, 'work_center_id' => 
  $work_center_id, 'key' => 'bizcocho']) }}" class="btn btn-custom" target="_blank" style="margin-top:16px;"><i class="fa fa-file-pdf-o"></i> {{ l('Bizcochos (Otros)') }}</a>

</div>

        @if ( 0 )
              <button type="button" class="btn btn-sm btn-danger" title="{{l('Need Update')}}">
                  <i class="fa fa-hand-stop-o"></i>
              </button>
        @endif  
</div>

      </div>

   </div>



@section('styles')    @parent

<style>
  /* 
  http://twitterbootstrap3buttons.w3masters.nl/?color=%232BA9E1
  https://bootsnipp.com/snippets/M3x9

  */
.btn-custom {
  color: #fff;
  background-color: #ff0084;
  border-color: #ff0084;
}
.btn-custom:hover,
.btn-custom:focus,
.btn-custom:active,
.btn-custom.active {
  background-color: #e60077;
  border-color: #cc006a;
}
.btn-custom.disabled:hover,
.btn-custom.disabled:focus,
.btn-custom.disabled:active,
.btn-custom.disabled.active,
.btn-custom[disabled]:hover,
.btn-custom[disabled]:focus,
.btn-custom[disabled]:active,
.btn-custom[disabled].active,
fieldset[disabled] .btn-custom:hover,
fieldset[disabled] .btn-custom:focus,
fieldset[disabled] .btn-custom:active,
fieldset[disabled] .btn-custom.active {
  background-color: #ff0084;
  border-color: #ff0084;
}


</style>

@endsection

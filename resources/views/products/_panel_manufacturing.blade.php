

<div id="panel_manufacturing">
@if ($bom)

<div class="panel panel-primary" id="panel_manufacturing_1">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Bill of Materials') }}</h3>
   </div>
   <div class="panel-body">

<table id="products" class="table table-hover">
    <thead>
        <tr>
            <th style="background-color: #d9edf7;">{{l('BOM Quantity')}}</th>
            <th style="border-bottom: 0px solid;"> &nbsp; </th>
            <th>{{ l('Alias') }}</th>
            <th>{{ l('BOM Name') }}</th>
            <th>{{ l('Quantity') }}</th>
            <th>{{ l('Measure Unit') }}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-right"> </th>
            <th style="border-bottom: 0px solid;"> &nbsp; </th>
    </tr>
  </thead>
  <tbody>
        <tr>
            <td style="background-color: #d9edf7;">{{ $product->bomitem()->quantity }}</td>
            <td style="border-top: 0px solid;"> &nbsp; </th>
            <td>{{ $bom->alias }}</td>
            <td>{{ $bom->name }}</td>
            <td>{{ $bom->quantity }}</td>
            <td>{{ $bom->measureunit->name ?? '' }}</td>
            <td class="text-center">
                @if ($bom->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $bom->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
           <td class="text-right" style="width:1px; white-space: nowrap;">

                <a class="btn btn-warning" href="{{ route( 'productboms.edit', [$bom->id] ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-danger detach-bom-item" title="{{l('Detach')}}" onClick="return false;"><i class="fa fa-unlink"></i></a>

            </td>
            <td style="border-top: 0px solid;"> &nbsp; </th>
        </tr>
    </tbody>
</table>



    <div class="page-header" style="border-bottom: 0px solid #eeeeee;">
        <h3>
            <span style="color: #dd4814;">{{ l('Materials') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $bom->name }} -->
        </h3>        
    </div>

    <div id="div_bom_lines">
       <div class="table-responsive">

    <table id="bom_lines" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('Position', [], 'layouts')}}</th>
                <th class="text-left">{{l('Product')}}</th>
                <th class="text-left">{{l('Quantity')}}</th>
                <th class="text-left">{{l('Measure Unit')}}</th>
                <th class="text-left">{{l('Scrap (%)')}}</th>
                <th class="text-left">{{l('Notes', [], 'layouts')}}</th>
            </tr>
        </thead>
        <tbody>

@if ($bom)

    @if ($bom->BOMlines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($bom->BOMlines as $line)
            <tr>
                <td>{{ $line->line_sort_order }}</td>
                <td>{{ '['.$line->product->reference.'] '.$line->product->name }}</td>
                <td>{{ $line->as_quantity('quantity') }}</td>
                <td>{{ optional($line->measureunit)->name }}</td>
                <td>{{ $line->as_percent('scrap') }}</td>
                <td class="text-center">
                @if ($line->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $line->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
            </tr>
            
            @endforeach

                <input type="hidden" name="next_line_sort_order" id="next_line_sort_order" value="{{ $line->line_sort_order + 10 }}">

    @else
    <tr><td colspan="7">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
        <input type="hidden" name="next_line_sort_order" id="next_line_sort_order" value="10">
    </div>
    </td></tr>
    @endif
    
@endif

        </tbody>
    </table>

       </div>
    </div>



          @include('products._panel_bom_tree')



   </div>

   <div class="panel-footer text-right">
      <!-- button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button -->
   </div>

</div>


@include('products._modal_bom_detach')


@else

<div class="panel panel-primary" id="panel_manufacturing_1">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Bill of Materials') }}</h3>
   </div>
   <div class="panel-body">

<!-- Manufacturing -->

<div class="row">
  <div class="col-md-10 col-md-offset-1" style="margin-top: 10px">

        <div class="row">
          <div class="form-group col-md-2">
          </div>

          <div class="form-group col-md-4">
            <button class="btn btn btn-info form-control select-bom-item" type="submit" xonclick="this.disabled=true;">
               <i class="fa fa-code-fork"></i>
               &nbsp; {{l('Select BOM')}}
            </button>
          </div>

          <div class="form-group col-md-4">
            <button class="btn btn btn-success form-control create-bom-item" type="submit" xonclick="this.disabled=true;">
               <i class="fa fa-sitemap"></i>
               &nbsp; {{l('Create BOM')}}
            </button>
          </div>

        </div>

  </div>
</div>

{{--
        <!-- div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web">
                     {!! Form::label('publish_to_web', l('Publish to web?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('publish_to_web', '1', true, ['id' => 'publish_to_web_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('publish_to_web', '0', false, ['id' => 'publish_to_web_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>

        <hr />

        <div class="row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('description_short') ? 'has-error' : '' }}">
                     {{ l('Short Description') }}
                     {!! Form::textarea('description_short', null, array('class' => 'form-control', 'id' => 'description_short', 'rows' => '3')) !!}
                     {!! $errors->first('description_short', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>

   </div -->

<!-- Manufacturing ENDS -- >
--}}

   </div>

   <div class="panel-footer text-right">
      <!-- button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button -->
   </div>

</div>

@include('products._modal_bom_select')

@include('products._modal_bom_create')

@endif



{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="manufacturing" name="tab_name" id="tab_name">


<div class="panel panel-primary" id="panel_manufacturing_2">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Manufacturing Route') }}</h3>
   </div>
   <div class="panel-body">

<!-- Manufacturing -->

        <div class="row">

             <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('work_center_id') ? 'has-error' : '' }}">
                {{ l('Work Center') }}
                {!! Form::select('work_center_id', array('' => l('-- Please, select --', [], 'layouts')) + $work_centerList, null, array('class' => 'form-control')) !!}
                {!! $errors->first('work_center_id', '<span class="help-block">:message</span>') !!}
             </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('manufacturing_batch_size') ? 'has-error' : '' }}">
                 {{ l('Manufacturing Batch Size') }}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                  data-content="{{ l('The quantity to be manufactured is calculated as a multiple of the Manufacturing Lot.') }}">
                              <i class="fa fa-question-circle abi-help"></i>
                       </a>
                 {!! Form::text('manufacturing_batch_size', null, array('class' => 'form-control', 'id' => 'manufacturing_batch_size')) !!}
                 {!! $errors->first('manufacturing_batch_size', '<span class="help-block">:message</span>') !!}
              </div>

             <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('tool_id') ? 'has-error' : '' }}">
                {{ l('Tool') }}
                {!! Form::select('tool_id', array('' => l('-- None --', [], 'layouts')) + $toolList, null, array('class' => 'form-control')) !!}
                {!! $errors->first('tool_id', '<span class="help-block">:message</span>') !!}
             </div>

        </div>

        <div class="row">

             <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('units_per_tray') ? 'has-error' : '' }}">
                {{ l('Units per Tray') }}
                {!! Form::text('units_per_tray', null, array('class' => 'form-control', 'id' => 'units_per_tray')) !!}
                {!! $errors->first('units_per_tray', '<span class="help-block">:message</span>') !!}
             </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('machine_capacity') ? 'has-error' : '' }}">
                 {{ l('Machine Capacity') }}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                  data-content="{{ l('Amount of Product this Machine can process in one load.') }}">
                              <i class="fa fa-question-circle abi-help"></i>
                       </a>
                 {!! Form::text('machine_capacity', null, array('class' => 'form-control', 'id' => 'machine_capacity')) !!}
                 {!! $errors->first('machine_capacity', '<span class="help-block">:message</span>') !!}
              </div>

        </div>

        <div class="row">

              <div class="form-group col-lg-9 col-md-9 col-sm-9{{ $errors->has('route_notes') ? 'has-error' : '' }}">
                 {{ l('Notes', [], 'layouts') }}
                 {!! Form::textarea('route_notes', null, array('class' => 'form-control', 'id' => 'route_notes', 'rows' => '2')) !!}
                 {!! $errors->first('route_notes', '<span class="help-block">:message</span>') !!}
              </div>

        </div>

<!-- Manufacturing ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}

</div>

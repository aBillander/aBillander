
    <!-- div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Lines') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} - - >
        </h3>        
    </div -->

    <div id="div_document_lines">
       <div class="table-responsive">

            <div class="alert alert-dismissible alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>ERROR: </strong> {!!  l($cannot_add_lines_msg, $cannot_add_lines_data, 'layouts') !!}
            </div>

       </div>
    </div>


{{-- ******************************************************************************* --}}




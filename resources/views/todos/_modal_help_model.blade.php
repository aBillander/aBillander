@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="myHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info modal-header-help">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal Title</h4>
      </div>
      <div class="modal-body">
        Modal Body
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-grey" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
      </div>
    </div>
  </div>
</div>

@endsection



@section('styles')    @parent

<style>

.modal-content {
  overflow:hidden;
}

/*
See: https://coreui.io/docs/components/buttons/ :: Brand buttons
*/
.btn-behance {
    color: #fff;
    background-color: #1769ff;
    border-color: #1769ff;
}

</style>

@endsection



{{-- Draggable --} }

@section('scripts')    @parent


{ {-- Draggable --} }

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

  // See: https://stackoverflow.com/questions/45062397/make-bootstrap-modal-draggable-and-keep-background-usable/45062993
  // https://jsfiddle.net/ntLpg6hw/11/

  $('#btn1').click(function() {
    // reset modal if it isn't visible
    if (!($('.modal.in').length)) {
      $('.modal-draggable').css({
        top: 0,
        right: 0
      });
    }

//    var containmentTop = $("#app").position().right; 
//    var containmentBottom = $("#app").position().bottom; 
    // $('#bar').draggable({axis: 'y', containment : [0,containmentTop,0,containmentBottom] });

    $('.modal-draggable').draggable({
      handle: ".modal-header",
      cursor: "move",
      containment: [-$("#app").width()/3,20,$("#app").width()*2.5/3,$("#app").height()-240],
      xscroll: false
    });

//    console.log($("#app").width());
//    console.log($("#app").length());
//    console.log($("#app").height());

    // Todo: Prevent modal to go outside the screen

  });


</script>

@endsection



@section('styles')    @parent

<style>

#myHelpModal {
  position: relative;
}

.modal-dialog-help {
  position: fixed;
  width: 60%;
  margin: 0;
  padding: 10px;
}

.modal-content .modal-header-help {
  color: #ffffff;
  background-color: #772953;
  border-color: #772953;
  opacity: 0.40;
}

.modal-header-helps {
  color: #ffffff;
  background-color: #2780e3;
  xbackground-color: #5bc0de;
  border-color: #772953;
  xopacity: 0.40;
}

</style>

@endsection


{ {-- --}}

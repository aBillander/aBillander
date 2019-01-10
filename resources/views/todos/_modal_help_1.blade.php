
<div id="myHelpModal" class="modal modal fade">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content modal-content">
            <div class="modal-header modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title modal-title">Settings</h4>  

            </div>
            <div class="modal-body modal-body">
              <div class="comment">
                /* load ide ui */
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="loadpage" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



@section('scripts')    @parent


{{-- Draggable --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

  // See: https://codepen.io/adamcjoiner/pen/PNbbbv
  $("#myHelpModal").draggable({
      handle: ".modal-header"
  });

</script>

@endsection



@section('styles')    @parent

<style>

.comment {
  opacity: .6;
  font-style: italic;
  position: absolute;
  left: 40%;
}
.modal
{
    overflow: hidden;
}
.modal-dialog{
    margin-right: 0;
    margin-left: 0;
}
.modal-header{
  height:30px;background-color:#444;
  color:#ddd;
}
.modal-title{
  margin-top:-10px;
  font-size:16px;
}
.modal-header .close{
  margin-top:-10px;
  color:#fff;
}
.modal-body{
  color:#888;
}
.modal-body p {
  text-align:center;
  padding-top:10px;
}

</style>

@endsection

@section('modals')

@parent

<div class="modal fade" id="imageViewModal" tabindex="-1" role="dialog" aria-labelledby="myModalViewImage" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalViewImage"></h4>
            </div>
            <div class="modal-body">
                <img id="image" src="" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; border: 1px solid #dddddd;">
            </div>
            <!-- div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
            </div -->
        </div>
    </div>
</div>

@stop
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('.view-image').click(function (evnt) {
            var href = $(this).attr('href');
//            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            $('#myModalViewImage').text(title);
//            $('#imageViewModal .modal-body').text(message);
            $('.modal-body #image').attr('src', href);
            $('#imageViewModal').modal({show: true});
            return false;
        });
    });
</script>

@stop
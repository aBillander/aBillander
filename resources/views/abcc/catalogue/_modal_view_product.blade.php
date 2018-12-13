@section('modals')

@parent

<div class="modal fade" id="imageViewModal" tabindex="-1" role="dialog" aria-labelledby="myModalViewImage" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalViewImage"></h4>
            </div>
            <div class="modal-body">

        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6">

                <img id="image" src="" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; border: 1px solid #dddddd;">
                <h5 class="text-center" id="imageCaption"></h5>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <div class="form-group" id="imageContent"></div>
            </div>
        </div>

            </div>
            <!-- div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
            </div -->
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
//        $('.view-image').click(function (evnt) {
        $('body').on('click', ".view-image", function(evnt) {
            var href = $(this).attr('href');
//            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            var caption = $(this).attr('data-caption');
            var content = $(this).attr('data-content');
            $('#myModalViewImage').text(title);
            $('#imageCaption').text(caption);
            $('#imageContent').html(content);
//            $('#imageViewModal .modal-body').text(message);
            $('.modal-body #image').attr('src', href);
            $('#imageViewModal').modal({show: true});
            return false;
        });
    });
</script>

@endsection
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
{{--
    https://bootsnipp.com/snippets/Q6nrr#comments
    https://bootsnipp.com/snippets/94006
    https://bootsnipp.com/snippets/KG9KW
    https://bootsnipp.com/snippets/K5VD
--}}
{{-- Multiple Images (Carousel) --}}
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

    <!-- Indicatodores -->
    <ol class="carousel-indicators" xstyle="background-color: black;" id="imageCarouselIds">
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox" id="imageCarousel">
    </div>


    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>

</div>
{{-- --}}

{{-- One Image
                <img id="image" src="" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; border: 1px solid #dddddd;">
                <h5 class="text-center" id="imageCaption"></h5>
--}}
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


        $('body').on('click', ".view-image-multiple", function(evnt) {
        // $('.view-image-multiple').click(function (evnt) {

               var secure_key = $(this).attr('data-id');
               var url = "{{ route('catalogue.product', [":id"]) }}";

               url = url.replace(":id", secure_key);

               $.get(url, {}, function(result){

                    console.log(result);

                    $('#myModalViewImage').text(result.title);
                    $('#imageContent').html(result.content);

                    // $('.modal-body #image').attr('src', href);
                    $('#imageCaption').text(result.caption);

                    // alert('OK');

                    $('#imageCarouselIds').html('');
                    var active = false;
                    for (var i = 0; i < result.nbr_images; i++) {
                        var flaf = '';
                        if ( !active && (i == 0) )
                        {
                            active = true;
                            flaf = 'active';
                        }
                        var li_html = '<li data-target="#carousel-example-generic" data-slide-to="'+i+'" class="'+flaf+'"></li>';
                        $('#imageCarouselIds').append(li_html);
                    }

                    $('#imageCarousel').html(result.carousel);
/*
                    if(result.show_controls)
                    {
                        $('#leftControl').removeClass('hide');
                        $('#rightControl').removeClass('hide');
                    }
                    else
                    {
                        $('#leftControl').addClass('hide');
                        $('#rightControl').addClass('hide');
                    }
*/

                      // Set the carousel options (re-start)
                      $('#carousel-example-generic').carousel({
                        pause: true,
                        interval: 4000,
                      });

                      // checkitem();
                      // Instead:
                      if (result.nbr_images > 1)
                      {
                            // Enable
                            $('#carousel-example-generic').children('.carousel-control').show();
                      } else {
                            // Disable
                            $('#carousel-example-generic').children('.left.carousel-control').hide();
                            $('#carousel-example-generic').children('.right.carousel-control').hide();
                      }

                     $("[data-toggle=popover]").popover();
                     
               }, 'JSON').done( function() { 

                    $('#imageViewModal').modal({show: true});

                });

            return false;
        });

        // https://stackoverflow.com/questions/20467666/bootstrap-carousel-hide-controls-on-first-and-last
        // $('#carousel-example-generic').on('slid', '', checkitem);  // on caroussel move
//        $('#carousel-example-generic').on('slid.bs.carousel', '', checkitem); // on carousel move (Bootstrap 3)

        // If running on the front page
        // checkitem();

        function checkitem()                        // check function
        {
            var $this = $('#carousel-example-generic');
            $this.children('.carousel-control').show();
            if($('.carousel-inner .item:first').hasClass('active')) {
                $this.children('.left.carousel-control').hide();
//                $this.children('.right.carousel-control').show();
            }   // else 
            if($('.carousel-inner .item:last').hasClass('active')) {
//                $this.children('.left.carousel-control').show();
                $this.children('.right.carousel-control').hide();
            } else {
//                $this.children('.carousel-control').show();
            } 
        }

    });
</script>

@endsection
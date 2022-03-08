@section('modals')

@parent

<div class="modal fade" id="modalCartlineDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalCartlineDeleteLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <input type="hidden" id="delete_line_id">

                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                <button type="submit" class="btn btn-danger" name="btn-update" id="modalCartlineDeleteSubmit" xonclick="this.disabled=true;">
                    <i class="fa fa-thumbs-up"></i>
                    &nbsp; {{l('Confirm', [], 'layouts')}}</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.delete-cart-line', function(evnt) { 
            var id = $(this).attr('data-id');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

            $('#delete_line_id').val(id);
            $('#modalCartlineDeleteLabel').text(title);
            $('#modalCartlineDelete .modal-body').text(message);
            $('#modalCartlineDelete').modal({show: true});
            return false;
        });
    });

        $("#modalCartlineDeleteSubmit").click(function() {

 //         alert('etgwer');

            var id = $('#delete_line_id').val();
            var url = "{{ route('cart.deleteline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            var payload = {
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(reponse){

                      if(reponse.reload)
                      {
                        window.location.reload();
                        return false;
                      }
                    
                    loadCartlines();

                    $('#modalCartlineDelete').modal('toggle');
                    
                    showAlertDivWithDelay("#msg-success-delete");
    
                    console.log(reponse);
                }
            });

        });

</script>

@endsection
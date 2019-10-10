
<div id="panel_salesrepuser">     

    <div class="panel panel-info">

       <div class="panel-heading">
          <h3 class="panel-title">{{ l('Sales Representative Center Access') }}</h3>
       </div>

       <div class="panel-body">

            <div id="msg-salesrepuser-success" class="alert alert-success alert-block" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="msg-salesrepuser-success-counter" class="badge"></span>
                <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
            </div>

            <!-- Sales Rep -->

            <div class="content_salesrepusers"></div>

            <!-- Sales Rep ENDS -->

        </div><!-- div class="panel-body" -->

    </div><!-- div class="panel panel-info" -->

      
</div><!-- div id="panel_salesrepuser" -->


@include('sales_reps/_modal_salesrepuser_create')


@section('scripts')    @parent

<script type="text/javascript">

    $(document).ready(function () {

        $(document).on('click', '.create-salesrepuser', function (evnt) {

            var id = $(this).attr('data-id');

            // Initialize
            $('#salesrepuser_action').val('create');
            $('#salesrepuser_id').val(id);

            $('#salesrepuser_firstname').val('{{ $salesrep->firstname }}');
            $('#salesrepuser_lastname').val('{{ $salesrep->lastname }}');
            $('#salesrepuser_email').val('{{ $salesrep->email }}');
            $('#password').val('{{ \App\Configuration::get('ABSRC_DEFAULT_PASSWORD') }}');

            $('#salesrepuser_language_id').val('{{ \App\Configuration::get('DEF_LANGUAGE') }}');
            // $('select[name="salesrepuser_language_id"]').val( {{ intval(\App\Configuration::get('DEF_LANGUAGE')) }} );

            $("input[name='salesrepuser_active'][value='1']").prop('checked', true);
            $("input[name='allow_abcc_access'][value='-1']").prop('checked', true);
            $("input[name='notify_salesrep'][value='1']").prop('checked', true);

            // Open popup
            $('#salesrepuserModalTitle').html('{{ l('Create User') }} :: {{ $salesrep->name }}');
            $("#msg-salesrepuser-error").find("ul").empty();
            $("#msg-salesrepuser-error").css('display', 'none');

            $('#salesrepuserModal').modal({show: true});
            $("#salesrepuser_firstname").focus();


            return false;
        });


        $(document).on('click', '.update-salesrepuser', function (evnt) {

            var id = $(this).attr('data-id');

            var data = getSalesRepUserData(id);

            return false;
        });


    });

    $(window).on('hashchange', function () {
        page = window.location.hash.replace('#', '');
        if (page == 'salesrepusers') getSalesRepUsers();
    });


    function getSalesRepUsers() {

        $.ajax({
            url: '{{ route( 'salesrep.getusers', [$salesrep->id] ) }}',
            data: {}
        }).done(function (data) {
            $('.content_salesrepusers').html(data);
        });
    }


    function getSalesRepUserData(id) {

        var url = "{{ route('salesrepuser.getuser', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            data: {}
        }).done(function (data) {
            //console.log(data);
            user = data.user;

            // Initialize
            $('#salesrepuser_action').val('update');
            $('#salesrepuser_id').val(user.id);

            $('#salesrepuser_firstname').val(user.firstname);
            $('#salesrepuser_lastname').val(user.lastname);
            $('#salesrepuser_email').val(user.email);
            $('#password').val('');

            $('#salesrepuser_language_id').val(user.language_id);

            $("input[name='salesrepuser_active'][value='" + user.active + "']").prop('checked', true);
            $("input[name='allow_abcc_access'][value='" + user.allow_abcc_access + "']").prop('checked', true);
//            $("input[name='notify_salesrep'][value='"+user.+"']").prop('checked', true);

            $("#div-notify_salesrep").css('display', 'none');

            // Open popup
            $('#salesrepuserModalTitle').html('{{ l('Update User') }} :: {{ $salesrep->name_commercial }}');
            $("#msg-salesrepuser-error").find("ul").empty();
            $("#msg-salesrepuser-error").css('display', 'none');

            $('#salesrepuserModal').modal({show: true});
            $("#salesrepuser_firstname").focus();
        });

        // return false;
    }

</script>
@endsection


{!! HTML::script('assets/plugins/AutoComplete/jquery.autocomplete.js') !!}

<script type="text/javascript">

    $(document).ready(function(){

        $('#modal_customer_search').on('shown.bs.modal', function() {
            $("#customer_name").focus();
            // or: $('input:visible:first').focus();
        });

        $('#modal_customer_search').modal('show');

/*        // or: 
        $("#myModal").on("shown.bs.modal", function () { 
            alert('Hi');
        }).modal('show');
*/
    });


    $(function() {

        // $('#create-invoice').modal();
/*
        $('#modal_customer_search').on('shown.bs.modal', function() {
            $("#customer_name").focus();
        });
*/
        // https://github.com/devbridge/jQuery-Autocomplete
        $('#customer_name').devbridgeAutocomplete({
            lookupLimit: 20,
            minChars: 1,
            maxHeight: 300,
            serviceUrl: '{{ route('customers.ajax.nameLookup') }}',
            params: {name_commercial: function () { return $("input[name=name_commercial]:checked").val(); } },
            xonSearchStart: function (query) { 
     //       params: {span: function () { return $("input[name='recurring']").val(); } },
     //       params: {span: 69},  <- Si activo esto, setOptions({params: { q: qw } }) no asigna el parametro!!!
                var qwariane;
                // return query + '&zx=' + $("input[name='recurring']").val()
                // return $(this).devbridgeAutocomplete('setOptions', {params: {q: 'All'} });
                // alert($("input[name='recurring1']").val()+' culo');
                qwariane = $("#recurring1").val()+'-culo'; 
                // alert(qwariane);  
                // http://localhost/aBillander/public/customers/ajax/name_lookup?q=2etrhje+culo&query=c
                // Siguiente línea no va... ;-(   ???????????????  -> NO VA LA PRIMERA VEZ, PORQUE AUN EL OBJETO NO SE HA CREADO
                return $(this).devbridgeAutocomplete().setOptions({params: { q: qwariane, span: 69 } });
            },
            onSelect: function (suggestion) {
                // alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
                if(suggestion)
                {
                    document.search_customer_invoice.customer_id.value = suggestion.data;
                }
            }
        });
    });

    $('#invoice-create-confirm').click(function () {
        
        if ( !($("#customer_id").val() > 0) ) {
            alert( "{{ l('You should choose a Customer') }}" );
            $("#customer_name").focus();
            return false;
        }

        $(this).disabled=true;
//        $(this).form.submit();
        $(this).closest('form').submit();
    });

</script>

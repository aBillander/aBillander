<script type="text/javascript">

    $(document).ready(function() {

          $(document).on('click', '.lotable-document-line', function(evnt) {

              // What to do? Let's see:
              var line_type = $(this).attr('data-type');

              switch( line_type ) {
                  case 'product':
                      
                      lotableDocumentProductLine( $(this) );
                      break;

                  case 'service':
                  case 'shipping':
                      
                      // editDocumentServiceLine( $(this) );
                      break;

                  case 'comment':
                      
                      // editDocumentCommentLine( $(this) );
                      break;

                  default:
                      // Not good to reach this point
                      return false;
              } 

              return false;

          });
          

          function lotableDocumentProductLine( selector ) {

            // Load form first
              var line_id = $(selector).attr('data-id');
              var title = $(selector).attr('data-title');
              var quantity_label = $(selector).attr('data-quantity_label');
               var panel = $("#document_line_lots_form");
//               var url = "{{ route(\Str::singular($model_path).'lines.lots.index', ['line_id']) }}".replace('line_id', line_id);

               $('#line_lots_form_title').html(title);
               $('#line_lots_form_quantity').html(quantity_label);
               panel.html('');
               panel.addClass('loading');


              $('#modal_document_line_lots').modal({show: true});
              // $("#line_quantity").focus();

              getProductLineLotsData( line_id );

              return false;

          };


          function getProductLineLotsData( line_id ) {

               var panel = $("#document_line_lots_form");
               var url = "{{ route(\Str::singular($model_path).'lines.lots.index', ['line_id']) }}".replace('line_id', line_id);

               $.get(url, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();

	               // Start datepicker
				    $( "#lot_expiry_at_form" ).datepicker({
				      showOtherMonths: true,
				      selectOtherMonths: true,
				      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
				    });

				    $( "#lot_reference" ).focus();


               }, 'html');

               // alert('XX');

          }


        $("body").on('click', ".add-line-lot", function( event ) {

            var clicked = event.target;
            var panel = $("#document_line_lots_form");

            // alert(clicked.name); return;

            var line_id = $('#line_lotable_line_id').val();
            var url = "{{ route(\Str::singular($model_path).'lines.lots.store', ['line_id']) }}".replace('line_id', line_id);
            var token = "{{ csrf_token() }}";

            var store_mode = '';

//            if (clicked.name  == 'modal_document_line_productSubmitAsIs')
//                store_mode = 'asis';

			// Let's check quantity before submit:
			var max_qty = $('#line_lotable_pending').val();

			if ( (($('#lot_quantity').val() - max_qty) > 0) || ($('#lot_quantity').val() <= 0) )
			{
				$('#lot_quantity_error').parent().addClass('has-error');
				$('#lot_quantity_error').removeClass('hide');

				return false;
			}

			if ( $('#lot_expiry_at_form').val().trim().length < 8 )	// Poor man check, but maybe enough
			{
				$('#lot_expiry_at_form_error').parent().addClass('has-error');
				$('#lot_expiry_at_form_error').removeClass('hide');

				return false;
			}

            var payload = { 
                              line_id : line_id,
                              reference : $('#lot_reference').val(),
                              expiry_at_form : $('#lot_expiry_at_form').val(),
                              quantity : $('#lot_quantity').val()
                          };
              
            panel.html('');
            panel.addClass('loading');

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(response){

              		console.log(response);

              		getProductLineLotsData( line_id );

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

					// https://stackoverflow.com/questions/16149431/make-function-wait-until-element-exists
					var checkExist = setInterval(function() {
					   if ($('#lot-error-messages').length) {
					      console.log("Exists!");
					      clearInterval(checkExist);

		              		// Let's see if threre are errors
		              		if ( response.msg == 'KO' )
		              		{
		              			$('#lot-error-messages').html(response.error);
		              			$('#div-lot-error').removeClass('hide');
		              		}
					   }
					}, 100); // check every 100ms

					// ^-- For a better approach:
					// https://stackoverflow.com/questions/57391677/how-to-wait-until-an-element-exists-with-javascript


//                    if ( response.msg == 'OK' )
//                      showAlertDivWithDelay("#msg-success");
//                    else
//                      showAlertDivWithDelay("#msg-error");
                }
            });

        });




        $("body").on('click', ".remove-line-lot", function( event ) {

            var clicked = event.target;
            var panel = $("#document_line_lots_form");

              // What to do? Let's see:
              var lot_id = $(this).attr('data-lot_id');

            // alert(clicked.name); return;

            var line_id = $('#line_lotable_line_id').val();
            var url = "{{ route(\Str::singular($model_path).'lines.lots.destroy', ['line_id', 'lot_id']) }}".replace('line_id', line_id).replace('lot_id', lot_id);
            var token = "{{ csrf_token() }}";

            var store_mode = '';

//            if (clicked.name  == 'modal_document_line_productSubmitAsIs')
//                store_mode = 'asis';

            var payload = { 

                          };
              
            panel.html('');
            panel.addClass('loading');

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'DELETE',
                dataType : 'json',
                data : payload,

                success: function(response){

              		console.log(response);

              		// alert(line_id);

              		getProductLineLotsData( line_id );

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

//                    if ( response.msg == 'OK' )
//                      showAlertDivWithDelay("#msg-success");
//                    else
//                      showAlertDivWithDelay("#msg-error");
                }
            });

        });


        $('#modal_document_line_lots').on('hide.bs.modal', function() {

            var line_id = $("#line_lotable_line_id").val();

            // alert(line_id);

            loadDocumentlines();

        });



    });

</script>

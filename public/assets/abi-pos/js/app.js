$(document).ready(function() {
    getTotalUnreadNotifications();
    $('body').on('click', 'label', function(e) {
        var field_id = $(this).attr('for');
        if (field_id) {
            if ($('#' + field_id).hasClass('select2')) {
                $('#' + field_id).select2('open');
                return false;
            }
        }
    });
    fileinput_setting = {
        showUpload: false,
        showPreview: false,
        browseLabel: LANG.file_browse_label,
        removeLabel: LANG.remove,
    };
    $(document).ajaxStart(function() {
        Pace.restart();
    });

    __select2($('.select2'));

    // popover
    $('body').on('mouseover', '[data-toggle="popover"]', function() {
        if ($(this).hasClass('popover-default')) {
            return false;
        }
        $(this).popover('show');
    });

    //Date picker
    $('.start-date-picker').datepicker({
        autoclose: true,
        endDate: 'today',
    });
    $(document).on('click', '.btn-modal', function(e) {
        e.preventDefault();
        var container = $(this).data('container');

        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function(result) {
                $(container)
                    .html(result)
                    .modal('show');
            },
        });
    });

    $(document).on('submit', 'form#brand_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    $('div.brands_modal').modal('hide');
                    toastr.success(result.msg);
                    brands_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Brands table
    var brands_table = $('#brands_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/brands',
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
    });

    $(document).on('click', 'button.edit_brand_button', function() {
        $('div.brands_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            $('form#brand_edit_form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $('div.brands_modal').modal('hide');
                            toastr.success(result.msg);
                            brands_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_brand_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_brand,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            brands_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //Start: CRUD for tax Rate

    //Tax Rates table
    var tax_rates_table = $('#tax_rates_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/tax-rates',
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
    });

    $(document).on('submit', 'form#tax_rate_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    $('div.tax_rate_modal').modal('hide');
                    toastr.success(result.msg);
                    tax_rates_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.edit_tax_rate_button', function() {
        $('div.tax_rate_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            $('form#tax_rate_edit_form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $('div.tax_rate_modal').modal('hide');
                            toastr.success(result.msg);
                            tax_rates_table.ajax.reload();
                            tax_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_tax_rate_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_tax_rate,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            tax_rates_table.ajax.reload();
                            tax_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //End: CRUD for tax Rate

    //Start: CRUD for unit
    //Unit table
    var units_table = $('#unit_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/units',
        columnDefs: [
            {
                targets: 3,
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'actual_name', name: 'actual_name' },
            { data: 'short_name', name: 'short_name' },
            { data: 'allow_decimal', name: 'allow_decimal' },
            { data: 'action', name: 'action' },
        ],
    });

    $(document).on('submit', 'form#unit_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    $('div.unit_modal').modal('hide');
                    toastr.success(result.msg);
                    units_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.edit_unit_button', function() {
        $('div.unit_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            $('form#unit_edit_form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $('div.unit_modal').modal('hide');
                            toastr.success(result.msg);
                            units_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_unit_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_unit,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            units_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //Start: CRUD for Contacts
    //contacts table
    var contact_table_type = $('#contact_type').val();
    if (contact_table_type == 'supplier') {
        var columns = [
            { data: 'action', searchable: false, orderable: false },
            { data: 'contact_id', name: 'contact_id' },
            { data: 'supplier_business_name', name: 'supplier_business_name' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'tax_number', name: 'tax_number' },
            { data: 'pay_term', name: 'pay_term', searchable: false, orderable: false },
            { data: 'opening_balance', name: 'opening_balance', searchable: false },
            { data: 'balance', name: 'balance', searchable: false },
            { data: 'created_at', name: 'contacts.created_at' },
            { data: 'address', name: 'address', orderable: false },
            { data: 'mobile', name: 'mobile' },
            { data: 'due', searchable: false, orderable: false },
            { data: 'return_due', searchable: false, orderable: false },
            { data: 'custom_field1', name: 'custom_field1'},
            { data: 'custom_field2', name: 'custom_field2'},
            { data: 'custom_field3', name: 'custom_field3'},
            { data: 'custom_field4', name: 'custom_field4'},
            { data: 'custom_field5', name: 'custom_field5'},
            { data: 'custom_field6', name: 'custom_field6'},
            { data: 'custom_field7', name: 'custom_field7'},
            { data: 'custom_field8', name: 'custom_field8'},
            { data: 'custom_field9', name: 'custom_field9'},
            { data: 'custom_field10', name: 'custom_field10'},
        ];
    } else if (contact_table_type == 'customer') {
        var columns = [
            { data: 'action', searchable: false, orderable: false },
            { data: 'contact_id', name: 'contact_id' },
            { data: 'supplier_business_name', name: 'supplier_business_name' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'tax_number', name: 'tax_number' },
            { data: 'credit_limit', name: 'credit_limit' },
            { data: 'pay_term', name: 'pay_term', searchable: false, orderable: false },
            { data: 'opening_balance', name: 'opening_balance', searchable: false },
            { data: 'balance', name: 'balance', searchable: false },
            { data: 'created_at', name: 'contacts.created_at' }
        ];

        if ($('#rp_col').length) {
            columns.push({ data: 'total_rp', name: 'total_rp' });
        }
        Array.prototype.push.apply(columns, [{ data: 'customer_group', name: 'cg.name' },
            { data: 'address', name: 'address', orderable: false },
            { data: 'mobile', name: 'mobile' },
            { data: 'due', searchable: false, orderable: false },
            { data: 'return_due', searchable: false, orderable: false },
            { data: 'custom_field1', name: 'custom_field1'},
            { data: 'custom_field2', name: 'custom_field2'},
            { data: 'custom_field3', name: 'custom_field3'},
            { data: 'custom_field4', name: 'custom_field4'},
            { data: 'custom_field5', name: 'custom_field5'},
            { data: 'custom_field6', name: 'custom_field6'},
            { data: 'custom_field7', name: 'custom_field7'},
            { data: 'custom_field8', name: 'custom_field8'},
            { data: 'custom_field9', name: 'custom_field9'},
            { data: 'custom_field10', name: 'custom_field10'},
            ]);
    }
    
    contact_table = $('#contact_table').DataTable({
        processing: true,
        serverSide: true,
        scrollY:        "75vh",
        scrollX:        true,
        scrollCollapse: true,
        "ajax": {
            "url": "/contacts",
            "data": function ( d ) {
                d.type = $('#contact_type').val();
                d = __datatable_ajax_callback(d);

                if ($('#has_sell_due').length > 0 && $('#has_sell_due').is(':checked')) {
                    d.has_sell_due = true;
                }

                if ($('#has_sell_return').length > 0 && $('#has_sell_return').is(':checked')) {
                    d.has_sell_return = true;
                }

                if ($('#has_purchase_due').length > 0 && $('#has_purchase_due').is(':checked')) {
                    d.has_purchase_due = true;
                }

                if ($('#has_purchase_return').length > 0 && $('#has_purchase_return').is(':checked')) {
                    d.has_purchase_return = true;
                }

                if ($('#has_advance_balance').length > 0 && $('#has_advance_balance').is(':checked')) {
                    d.has_advance_balance = true;
                }

                if ($('#has_opening_balance').length > 0 && $('#has_opening_balance').is(':checked')) {
                    d.has_opening_balance = true;
                }

                if ($('#has_no_sell_from').length > 0) {
                    d.has_no_sell_from = $('#has_no_sell_from').val();
                }

                if ($('#assigned_to').length > 0) {
                    d.assigned_to = $('#assigned_to').val();
                }

                if ($('#cg_filter').length > 0) {
                    d.customer_group_id = $('#cg_filter').val();
                }

                if ($('#status_filter').length > 0) {
                    d.contact_status = $('#status_filter').val();
                }
            }
        },
        aaSorting: [[1, 'desc']],
        columns: columns,
        fnDrawCallback: function(oSettings) {
            __currency_convert_recursively($('#contact_table'));
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var total_due = 0;
            var total_return_due = 0;
            for (var r in data){
                total_due += $(data[r].due).data('orig-value') ? 
                parseFloat($(data[r].due).data('orig-value')) : 0;

                total_return_due += $(data[r].return_due).data('orig-value') ? 
                parseFloat($(data[r].return_due).data('orig-value')) : 0;
            }
            $('.footer_contact_due').html(__currency_trans_from_en(total_due));
            $('.footer_contact_return_due').html(__currency_trans_from_en(total_return_due));
        }
    });

    $(document).on('ifChanged', '#has_sell_due, #has_sell_return, \
    #has_purchase_due, #has_purchase_return, #has_advance_balance, #has_opening_balance', function(){
        contact_table.ajax.reload();
    });

    $(document).on('change', '#has_no_sell_from, #cg_filter, #status_filter, #assigned_to', function(){
        contact_table.ajax.reload();
    });

    //On display of add contact modal
    $('.contact_modal').on('shown.bs.modal', function(e) {
        $('input[type=radio][name="contact_type_radio"]').on('change', function() {
            if (this.value == 'individual') {
                $('div.individual').show();
                $('div.business').hide();
            } else if (this.value == 'business') {
                $('div.individual').hide();
                $('div.business').show();
            }
        });
        if ($('#is_customer_export').is(':checked')) {
            $('div.export_div').show();
        }
        $('#is_customer_export').on('change', function () {
            if ($(this).is(':checked')) {
                $('div.export_div').show();
            } else {
                $('div.export_div').hide();
            }
        });

        $('.more_btn').click(function(){
            $($(this).data('target')).toggleClass('hide');
        });
        $('div.lead_additional_div').hide();

        if ($('select#contact_type').val() == 'customer') {
            $('div.supplier_fields').hide();
            $('div.customer_fields').show();
        } else if ($('select#contact_type').val() == 'supplier') {
            $('div.supplier_fields').show();
            $('div.customer_fields').hide();
        }  else if ($('select#contact_type').val() == 'lead') {
            $('div.supplier_fields').hide();
            $('div.customer_fields').hide();
            $('div.opening_balance').hide();
            $('div.pay_term').hide();
            $('div.lead_additional_div').show();
            $('div.shipping_addr_div').hide();
        }

        $('select#contact_type').change(function() {
            var t = $(this).val();

            if (t == 'supplier') {
                $('div.supplier_fields').fadeIn();
                $('div.customer_fields').fadeOut();
            } else if (t == 'both') {
                $('div.supplier_fields').fadeIn();
                $('div.customer_fields').fadeIn();
            } else if (t == 'customer') {
                $('div.customer_fields').fadeIn();
                $('div.supplier_fields').fadeOut();
            } else if (t == 'lead') {
                $('div.customer_fields').fadeOut();
                $('div.supplier_fields').fadeOut();
                $('div.opening_balance').fadeOut();
                $('div.pay_term').fadeOut();
                $('div.lead_additional_div').fadeIn();
                $('div.shipping_addr_div').hide();
            }
        });

        $(".contact_modal").find('.select2').each( function(){
            $(this).select2();
        });

        $('form#contact_add_form, form#contact_edit_form')
            .submit(function(e) {
                e.preventDefault();
            })
            .validate({
                rules: {
                    contact_id: {
                        remote: {
                            url: '/contacts/check-contacts-id',
                            type: 'post',
                            data: {
                                contact_id: function() {
                                    return $('#contact_id').val();
                                },
                                hidden_id: function() {
                                    if ($('#hidden_id').length) {
                                        return $('#hidden_id').val();
                                    } else {
                                        return '';
                                    }
                                },
                            },
                        },
                    },
                },
                messages: {
                    contact_id: {
                        remote: LANG.contact_id_already_exists,
                    },
                },
                submitHandler: function(form) {
                    e.preventDefault();
                    $.ajax({
                        method: 'POST',
                        url: base_path + '/check-mobile',
                        dataType: 'json',
                        data: {
                            contact_id: function() {
                                return $('#hidden_id').val();
                            },
                            mobile_number: function() {
                                return $('#mobile').val();
                            },
                        },
                        beforeSend: function(xhr) {
                            __disable_submit_button($(form).find('button[type="submit"]'));
                        },
                        success: function(result) {
                            if (result.is_mobile_exists == true) {
                                swal({
                                    title: LANG.sure,
                                    text: result.msg,
                                    icon: 'warning',
                                    buttons: true,
                                    dangerMode: true
                                }).then(willContinue => {
                                    if (willContinue) {
                                        submitContactForm(form);
                                    } else {
                                        $('#mobile').select();
                                    }
                                });
                                
                            } else {
                                submitContactForm(form);
                            }
                        },
                    });
                },
            });

            $('#contact_add_form').trigger('contactFormvalidationAdded');
    });

    $(document).on('click', '.edit_contact_button', function(e) {
        e.preventDefault();
        $('div.contact_modal').load($(this).attr('href'), function() {
            $(this).modal('show');
        });
    });

    $(document).on('click', '.delete_contact_button', function(e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_contact,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).attr('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            contact_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //Start: CRUD for product variations
    //Variations table
    var variation_table = $('#variation_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/variation-templates',
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
    });
    $(document).on('click', '#add_variation_values', function() {
        var html =
            '<div class="form-group"><div class="col-sm-7 col-sm-offset-3"><input type="text" name="variation_values[]" class="form-control" required></div><div class="col-sm-2"><button type="button" class="btn btn-danger delete_variation_value">-</button></div></div>';
        $('#variation_values').append(html);
    });
    $(document).on('click', '.delete_variation_value', function() {
        $(this)
            .closest('.form-group')
            .remove();
    });
    $(document).on('submit', 'form#variation_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success === true) {
                    $('div.variation_modal').modal('hide');
                    toastr.success(result.msg);
                    variation_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.edit_variation_button', function() {
        $('div.variation_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            $('form#variation_edit_form').submit(function(e) {
                var form = $(this);
                e.preventDefault();
                var data = form.serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success === true) {
                            $('div.variation_modal').modal('hide');
                            toastr.success(result.msg);
                            variation_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_variation_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_variation,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            variation_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    var active = false;
    $(document).on('mousedown', '.drag-select', function(ev) {
        active = true;
        $('.active-cell').removeClass('active-cell'); // clear previous selection

        $(this).addClass('active-cell');
        cell_value = $(this)
            .find('input')
            .val();
    });
    $(document).on('mousemove', '.drag-select', function(ev) {
        if (active) {
            $(this).addClass('active-cell');
            $(this)
                .find('input')
                .val(cell_value);
        }
    });

    $(document).mouseup(function(ev) {
        active = false;
        if (
            !$(ev.target).hasClass('drag-select') &&
            !$(ev.target).hasClass('dpp') &&
            !$(ev.target).hasClass('dsp')
        ) {
            $('.active-cell').each(function() {
                $(this).removeClass('active-cell');
            });
        }
    });

    //End: CRUD for product variations
    $(document).on('change', '.toggler', function() {
        var parent_id = $(this).attr('data-toggle_id');
        if ($(this).is(':checked')) {
            $('#' + parent_id).removeClass('hide');
        } else {
            $('#' + parent_id).addClass('hide');
        }
    });
    //Start: CRUD for products
    $(document).on('change', '#category_id', function() {
        get_sub_categories();
    });
    $(document).on('change', '#unit_id', function() {
        get_sub_units();
    });
    if ($('.product_form').length && !$('.product_form').hasClass('create')) {
        show_product_type_form();
    }
    $('#type').change(function() {
        show_product_type_form();
    });

    $(document).on('click', '#add_variation', function() {
        var row_index = $('#variation_counter').val();
        var action = $(this).attr('data-action');
        $.ajax({
            method: 'POST',
            url: '/products/get_product_variation_row',
            data: { row_index: row_index, action: action },
            dataType: 'html',
            success: function(result) {
                if (result) {
                    $('#product_variation_form_part  > tbody').append(result);
                    $('#variation_counter').val(parseInt(row_index) + 1);
                    toggle_dsp_input();
                }
            },
        });
    });
    //End: CRUD for products

    //bussiness settings start

    if ($('form#bussiness_edit_form').length > 0) {
        $('form#bussiness_edit_form').validate({
            ignore: [],
        });

        // logo upload
        $('#business_logo').fileinput(fileinput_setting);

        //Purchase currency
        $('input#purchase_in_diff_currency').on('ifChecked', function(event) {
            $('div#settings_purchase_currency_div, div#settings_currency_exchange_div').removeClass(
                'hide'
            );
        });
        $('input#purchase_in_diff_currency').on('ifUnchecked', function(event) {
            $('div#settings_purchase_currency_div, div#settings_currency_exchange_div').addClass(
                'hide'
            );
        });

        //Product expiry
        $('input#enable_product_expiry').change(function() {
            if ($(this).is(':checked')) {
                $('select#expiry_type').attr('disabled', false);
                $('div#on_expiry_div').removeClass('hide');
            } else {
                $('select#expiry_type').attr('disabled', true);
                $('div#on_expiry_div').addClass('hide');
            }
        });

        $('select#on_product_expiry').change(function() {
            if ($(this).val() == 'stop_selling') {
                $('input#stop_selling_before').attr('disabled', false);
                $('input#stop_selling_before')
                    .focus()
                    .select();
            } else {
                $('input#stop_selling_before').attr('disabled', true);
            }
        });

        //enable_category
        $('input#enable_category').on('ifChecked', function(event) {
            $('div.enable_sub_category').removeClass('hide');
        });
        $('input#enable_category').on('ifUnchecked', function(event) {
            $('div.enable_sub_category').addClass('hide');
        });
    }
    //bussiness settings end

    $('#upload_document').fileinput(fileinput_setting);

    //user profile
    $('form#edit_user_profile_form').validate();
    $('form#edit_password_form').validate({
        rules: {
            current_password: {
                required: true,
                minlength: 5,
            },
            new_password: {
                required: true,
                minlength: 5,
            },
            confirm_password: {
                equalTo: '#new_password',
            },
        },
    });

    //Tax Rates table
    var tax_groups_table = $('#tax_groups_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/group-taxes',
        columnDefs: [
            {
                targets: [2, 3],
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'name', name: 'name' },
            { data: 'amount', name: 'amount' },
            { data: 'sub_taxes', name: 'sub_taxes' },
            { data: 'action', name: 'action' },
        ],
    });
    $('.tax_group_modal').on('shown.bs.modal', function() {
        $('.tax_group_modal')
            .find('.select2')
            .each(function() {
                __select2($(this));
            });
    });

    $(document).on('submit', 'form#tax_group_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    $('div.tax_group_modal').modal('hide');
                    toastr.success(result.msg);
                    tax_groups_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('submit', 'form#tax_group_edit_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            data: data,
            success: function(result) {
                if (result.success == true) {
                    $('div.tax_group_modal').modal('hide');
                    toastr.success(result.msg);
                    tax_groups_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.delete_tax_group_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_tax_group,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            tax_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //option-div
    $(document).on('click', '.option-div-group .option-div', function() {
        $(this)
            .closest('.option-div-group')
            .find('.option-div')
            .each(function() {
                $(this).removeClass('active');
            });
        $(this).addClass('active');
        $(this)
            .find('input:radio')
            .prop('checked', true)
            .change();
    });

    $(document).on('change', 'input[type=radio][name=scheme_type]', function() {
        $('#invoice_format_settings').removeClass('hide');
        show_invoice_preview();
    });
    $(document).on('change', '#prefix', function() {
        show_invoice_preview();
    });
    $(document).on('keyup', '#prefix', function() {
        show_invoice_preview();
    });
    $(document).on('keyup', '#start_number', function() {
        show_invoice_preview();
    });
    $(document).on('change', '#total_digits', function() {
        show_invoice_preview();
    });
    var invoice_table = $('#invoice_table').DataTable({
        processing: true,
        serverSide: true,
        bPaginate: false,
        buttons: [],
        ajax: '/invoice-schemes',
        columnDefs: [
            {
                targets: 4,
                orderable: false,
                searchable: false,
            },
        ],
    });
    $(document).on('submit', 'form#invoice_scheme_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    $('div.invoice_modal').modal('hide');
                    $('div.invoice_edit_modal').modal('hide');
                    toastr.success(result.msg);
                    invoice_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('click', 'button.set_default_invoice', function() {
        var href = $(this).data('href');
        var data = $(this).serialize();

        $.ajax({
            method: 'get',
            url: href,
            dataType: 'json',
            data: data,
            success: function(result) {
                if (result.success === true) {
                    toastr.success(result.msg);
                    invoice_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $('.invoice_edit_modal').on('shown.bs.modal', function() {
        show_invoice_preview();
    });
    $(document).on('click', 'button.delete_invoice_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.delete_invoice_confirm,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            invoice_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $('#add_barcode_settings_form').validate();
    $(document).on('change', '#is_continuous', function() {
        if ($(this).is(':checked')) {
            $('.stickers_per_sheet_div').addClass('hide');
            $('.paper_height_div').addClass('hide');
        } else {
            $('.stickers_per_sheet_div').removeClass('hide');
            $('.paper_height_div').removeClass('hide');
        }
    });

    $(document).on('change', '#expense_category_id', function() {
        get_expense_sub_categories();
    });

    //initialize iCheck
    $('input[type="checkbox"].input-icheck, input[type="radio"].input-icheck').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
    });
    $(document).on('ifChecked', '.check_all', function() {
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function() {
                $(this).iCheck('check');
            });
    });
    $(document).on('ifUnchecked', '.check_all', function() {
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function() {
                $(this).iCheck('uncheck');
            });
    });
    $('.check_all').each(function() {
        var length = 0;
        var checked_length = 0;
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function() {
                length += 1;
                if ($(this).iCheck('update')[0].checked) {
                    checked_length += 1;
                }
            });
        length = length - 1;
        if (checked_length != 0 && length == checked_length) {
            $(this).iCheck('check');
        }
    });

    //Business locations CRUD
    business_locations = $('#business_location_table').DataTable({
        processing: true,
        serverSide: true,
        bPaginate: false,
        buttons: [],
        ajax: '/business-location',
        columnDefs: [
            {
                targets: 11,
                orderable: false,
                searchable: false,
            },
        ],
    });
    $('.location_add_modal, .location_edit_modal').on('shown.bs.modal', function(e) {
        $('form#business_location_add_form')
            .submit(function(e) {
                e.preventDefault();
            })
            .validate({
                rules: {
                    location_id: {
                        remote: {
                            url: '/business-location/check-location-id',
                            type: 'post',
                            data: {
                                location_id: function() {
                                    return $('#location_id').val();
                                },
                                hidden_id: function() {
                                    if ($('#hidden_id').length) {
                                        return $('#hidden_id').val();
                                    } else {
                                        return '';
                                    }
                                },
                            },
                        },
                    },
                },
                messages: {
                    location_id: {
                        remote: LANG.location_id_already_exists,
                    },
                },
                submitHandler: function(form) {
                    e.preventDefault();
                    var data = $(form).serialize();

                    $.ajax({
                        method: 'POST',
                        url: $(form).attr('action'),
                        dataType: 'json',
                        data: data,
                        beforeSend: function(xhr) {
                            __disable_submit_button($(form).find('button[type="submit"]'));
                        },
                        success: function(result) {
                            if (result.success == true) {
                                $('div.location_add_modal').modal('hide');
                                $('div.location_edit_modal').modal('hide');
                                toastr.success(result.msg);
                                business_locations.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                },
            });

        $('form#business_location_add_form').find('#featured_products').select2({
            minimumInputLength: 2,
            allowClear: true,
            placeholder: '',
            ajax: {
                url: '/products/list?not_for_selling=true',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            var string = obj.name;
                            if (obj.type == 'variable') {
                                string += '-' + obj.variation;
                            }

                            string += ' (' + obj.sub_sku + ')';
                            return { id: obj.variation_id, text: string };
                        })
                    };
                },
            },
        })
    });

    if ($('#header_text').length) {
        init_tinymce('header_text');
    }

    if ($('#footer_text').length) {
        init_tinymce('footer_text');
    }

    //Start: CRUD for expense category
    //Expense category table
    var expense_cat_table = $('#expense_category_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/expense-categories',
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
    });
    $(document).on('submit', 'form#expense_category_add_form', function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function(result) {
                if (result.success === true) {
                    $('div.expense_category_modal').modal('hide');
                    toastr.success(result.msg);
                    expense_cat_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('click', 'button.delete_expense_category', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_expense_category,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            expense_cat_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //date filter for expense table
    if ($('#expense_date_range').length == 1) {
        $('#expense_date_range').daterangepicker(
            dateRangeSettings, 
            function(start, end) {
                $('#expense_date_range').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                );
                expense_table.ajax.reload();
            }
        );

        $('#expense_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $('#product_sr_date_filter').val('');
            expense_table.ajax.reload();
        });
    }

    //Expense table
    expense_table = $('#expense_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[1, 'desc']],
        ajax: {
            url: '/expenses',
            data: function(d) {
                d.expense_for = $('select#expense_for').val();
                d.contact_id = $('select#expense_contact_filter').val();
                d.location_id = $('select#location_id').val();
                d.expense_category_id = $('select#expense_category_id').val();
                d.expense_sub_category_id = $('select#expense_sub_category_id_filter').val();
                d.payment_status = $('select#expense_payment_status').val();
                d.start_date = $('input#expense_date_range')
                    .data('daterangepicker')
                    .startDate.format('YYYY-MM-DD');
                d.end_date = $('input#expense_date_range')
                    .data('daterangepicker')
                    .endDate.format('YYYY-MM-DD');
            },
        },
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'transaction_date', name: 'transaction_date' },
            { data: 'ref_no', name: 'ref_no' },
            { data: 'recur_details', name: 'recur_details', orderable: false, searchable: false },
            { data: 'category', name: 'ec.name' },
            { data: 'sub_category', name: 'esc.name' },
            { data: 'location_name', name: 'bl.name' },
            { data: 'payment_status', name: 'payment_status', orderable: false },
            { data: 'tax', name: 'tr.name' },
            { data: 'final_total', name: 'final_total' },
            { data: 'payment_due', name: 'payment_due' },
            { data: 'expense_for', name: 'expense_for' },
            { data: 'contact_name', name: 'c.name' },
            { data: 'additional_notes', name: 'additional_notes' },
            { data: 'added_by', name: 'usr.first_name'}
        ],
        fnDrawCallback: function(row, data, start, end, display) {
            var expense_total = sum_table_col($('#expense_table'), 'final-total');
            var total_due = sum_table_col($('#expense_table'), 'payment_due');

            $('.footer_expense_total').html(__currency_trans_from_en(expense_total));
            $('.footer_total_due').html(__currency_trans_from_en(total_due));

            $('.footer_payment_status_count').html(
                __sum_status_html($('#expense_table'), 'payment-status')
            );
        },
        createdRow: function(row, data, dataIndex) {
            $(row)
                .find('td:eq(4)')
                .attr('class', 'clickable_td');
        },
    });

    $('select#location_id, select#expense_for, select#expense_contact_filter, \
        select#expense_category_id, select#expense_payment_status, \
        select#expense_sub_category_id_filter').on(
        'change',
        function() {
            expense_table.ajax.reload();
        }
    );

    //Date picker
    $('#expense_transaction_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });

    $(document).on('click', 'a.delete_expense', function(e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_expense,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            expense_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $(document).on('change', '.payment_types_dropdown', function() {
        var payment_type = $(this).val();
        var to_show = null;
        $(this)
            .closest('.payment_row')
            .find('.payment_details_div')
            .each(function() {
                if ($(this).attr('data-type') == payment_type) {
                    to_show = $(this);
                } else {
                    if (!$(this).hasClass('hide')) {
                        $(this).addClass('hide');
                    }
                }
            });

        if (to_show && to_show.hasClass('hide')) {
            to_show.removeClass('hide');
            to_show
                .find('input')
                .filter(':visible:first')
                .focus();
        }

        if ($(this).closest('.payment_row').find('.enable_cash_denomination_for_payment_methods').length) {
            var payment_methods = JSON.parse($(this).closest('.payment_row').find('.enable_cash_denomination_for_payment_methods').val());
            if (payment_methods.indexOf(payment_type) >= 0) {
                $(this).closest('.payment_row').find('.cash_denomination_div').removeClass('hide');
            } else {
                $(this).closest('.payment_row').find('.cash_denomination_div').addClass('hide');
            }
        }
    });

    //Start: CRUD operation for printers

    //Add Printer
    if ($('form#add_printer_form').length == 1) {
        printer_connection_type_field($('select#connection_type').val());
        $('select#connection_type').change(function() {
            var ctype = $(this).val();
            printer_connection_type_field(ctype);
        });

        $('form#add_printer_form').validate();
    }

    //Business Location Receipt setting
    if ($('form#bl_receipt_setting_form').length == 1) {
        if ($('select#receipt_printer_type').val() == 'printer') {
            $('div#location_printer_div').removeClass('hide');
        } else {
            $('div#location_printer_div').addClass('hide');
        }

        $('select#receipt_printer_type').change(function() {
            var printer_type = $(this).val();
            if (printer_type == 'printer') {
                $('div#location_printer_div').removeClass('hide');
            } else {
                $('div#location_printer_div').addClass('hide');
            }
        });

        $('form#bl_receipt_setting_form').validate();
    }

    $(document).on('click', 'a.pay_purchase_due, a.pay_sale_due', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'html',
            success: function(result) {
                $('.pay_contact_due_modal')
                    .html(result)
                    .modal('show');
                __currency_convert_recursively($('.pay_contact_due_modal'));
                $('#paid_on').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format,
                    ignoreReadonly: true,
                });
                $('.pay_contact_due_modal')
                    .find('form#pay_contact_due_form')
                    .validate();
            },
        });
    });

    //Todays profit modal
    $('#view_todays_profit').click(function() {
        var loader = '<div class="text-center">' + __fa_awesome() + '</div>';
        $('#todays_profit').html(loader);
        $('#todays_profit_modal').modal('show');
    });
    $('#todays_profit_modal').on('shown.bs.modal', function() {
        var start = $('#modal_today').val();
        var end = start;
        var location_id = '';

        updateProfitLoss(start, end, location_id, $('#todays_profit'));
    });

    //Used for Purchase & Sell invoice.
    $(document).on('click', 'a.print-invoice', function(e) {
        e.preventDefault();
        var href = $(this).data('href');

        $.ajax({
            method: 'GET',
            url: href,
            dataType: 'json',
            success: function(result) {
                if (result.success == 1 && result.receipt.html_content != '') {
                    $('#receipt_section').html(result.receipt.html_content);
                    __currency_convert_recursively($('#receipt_section'));

                    var title = document.title;
                    if (typeof result.receipt.print_title != 'undefined') {
                        document.title = result.receipt.print_title;
                    }
                    if (typeof result.print_title != 'undefined') {
                        document.title = result.print_title;
                    }

                    __print_receipt('receipt_section');

                    setTimeout(function() {
                        document.title = title;
                    }, 1200);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Sales commission agent
    var sales_commission_agent_table = $('#sales_commission_agent_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/sales-commission-agents',
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'full_name' },
            { data: 'email' },
            { data: 'contact_no' },
            { data: 'address' },
            { data: 'cmmsn_percent' },
            { data: 'action' },
        ],
    });
    $('div.commission_agent_modal').on('shown.bs.modal', function(e) {
        $('form#sale_commission_agent_form')
            .submit(function(e) {
                e.preventDefault();
            })
            .validate({
                submitHandler: function(form) {
                    e.preventDefault();
                    var data = $(form).serialize();

                    $.ajax({
                        method: $(form).attr('method'),
                        url: $(form).attr('action'),
                        dataType: 'json',
                        data: data,
                        success: function(result) {
                            if (result.success == true) {
                                $('div.commission_agent_modal').modal('hide');
                                toastr.success(result.msg);
                                sales_commission_agent_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                },
            });
    });
    $(document).on('click', 'button.delete_commsn_agnt_button', function() {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();
                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            sales_commission_agent_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $('button#full_screen').click(function(e) {
        element = document.documentElement;
        if (screenfull.isEnabled) {
            screenfull.toggle(element);
        }
    });

    $(document).on('submit', 'form#customer_group_add_form', function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function(result) {
                if (result.success == true) {
                    $('div.customer_groups_modal').modal('hide');
                    toastr.success(result.msg);
                    customer_groups_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Customer Group table
    var customer_groups_table = $('#customer_groups_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/customer-group',
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
    });

    $(document).on('click', 'button.edit_customer_group_button', function() {
        $('div.customer_groups_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            $('form#customer_group_edit_form').submit(function(e) {
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('div.customer_groups_modal').modal('hide');
                            toastr.success(result.msg);
                            customer_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_customer_group_button', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_customer_group,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            customer_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //Delete Sale
    $(document).on('click', '.delete-sale', function(e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).attr('href');
                var is_suspended = $(this).hasClass('is_suspended');
                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            if (typeof sell_table !== 'undefined') {
                                sell_table.ajax.reload();
                            }
                            if (typeof pending_repair_table !== 'undefined') {
                                pending_repair_table.ajax.reload();
                            }
                            //Displays list of recent transactions
                            if (typeof get_recent_transactions !== 'undefined') {
                                get_recent_transactions('final', $('div#tab_final'));
                                get_recent_transactions('draft', $('div#tab_draft'));
                            }
                            if (is_suspended) {
                                $('.view_modal').modal('hide');
                            }
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    if ($('form#add_invoice_layout_form').length > 0) {
        $('select#design').change(function() {
            if ($(this).val() == 'columnize-taxes') {
                $('div#columnize-taxes').removeClass('hide');
                $('div#columnize-taxes')
                    .find('input')
                    .removeAttr('disabled', 'false');
            } else {
                $('div#columnize-taxes').addClass('hide');
                $('div#columnize-taxes')
                    .find('input')
                    .attr('disabled', 'true');
            }
        });
    }

    $(document).on('keyup', 'form#unit_add_form input#actual_name', function() {
        $('form#unit_add_form span#unit_name').text($(this).val());
    });
    $(document).on('keyup', 'form#unit_edit_form input#actual_name', function() {
        $('form#unit_edit_form span#unit_name').text($(this).val());
    });

    $('#user_dob').datepicker({
        autoclose: true
    });

    setInterval(function(){ getTotalUnreadNotifications() }, __new_notification_count_interval);

    discounts_table = $('#discounts_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: base_path + '/discount',
                    columnDefs: [
                        {
                            targets: [0, 8, 10],
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    aaSorting: [1, 'asc'],
                    columns: [
                        { data: 'row_select'},
                        { data: 'name', name: 'discounts.name' },
                        { data: 'starts_at', name: 'starts_at' },
                        { data: 'ends_at', name: 'ends_at' },
                        { data: 'discount_amount', name: 'discount_amount'},
                        { data: 'priority', name: 'priority' },
                        { data: 'brand', name: 'b.name' },
                        { data: 'category', name: 'c.name' },
                        { data: 'products' },
                        { data: 'location', name: 'l.name' },
                        { data: 'action', name: 'action' },
                    ],
                });


    types_of_service_table = $('#types_of_service_table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: base_path + '/types-of-service',
                        columnDefs: [
                            {
                                targets: [3],
                                orderable: false,
                                searchable: false,
                            },
                        ],
                        aaSorting: [1, 'asc'],
                        columns: [
                            { data: 'name', name: 'name' },
                            { data: 'description', name: 'description' },
                            { data: 'packing_charge', name: 'packing_charge' },
                            { data: 'action', name: 'action' },
                        ],
                        fnDrawCallback: function(oSettings) {
                            __currency_convert_recursively($('#types_of_service_table'));
                        },
                    });

    //Search Settings
    //Set all labels as select2 options
    label_objects = [];
    search_options = [{
        id: '',
        text: ''
    }];
    var i = 0;
    $('.pos-tab-container label').each( function(){
        label_objects.push($(this));
        var label_text = $(this).text().trim().replace(":", "").replace("*", "");
        search_options.push(
            {
                id: i,
                text: label_text
            }
        );
        i++;
    });

    $('.pos-tab-container h4').each( function(){
        label_objects.push($(this));
        var label_text = $(this).text().trim().replace(":", "").replace("*", "");
        search_options.push(
            {
                id: i,
                text: label_text
            }
        );
        i++;
    });

    $('#search_settings').select2({
        data: search_options,
        placeholder: LANG.search,
    });
    $('#search_settings').change( function(){
        //Get label position and add active class to the tab
        var label_index = $(this).val();
        var label = label_objects[label_index];
        $('.pos-tab-content.active').removeClass('active');
        var tab_content = label.closest('.pos-tab-content');
        tab_content.addClass('active');
        tab_index = $('.pos-tab-content').index(tab_content);
        $('.list-group-item.active').removeClass('active');
        $('.list-group-item').eq(tab_index).addClass('active');

        //Highlight the label for three seconds
        $([document.documentElement, document.body]).animate({
            scrollTop: label.offset().top - 100
        }, 500);
        label.css('background-color', 'yellow');
        setTimeout(function(){ 
            label.css('background-color', ''); 
        }, 3000);
    });
    $('#add_invoice_layout_form #design').change( function(){
        if ($(this).val() == 'slim') {
            $('#hide_price_div').removeClass('hide');
        } else {
            $('#hide_price_div').addClass('hide');
        }
    });

    $('#toggle_additional_expense').click( function() {
        $('#additional_expenses_div').toggle();
    });
});

$('.quick_add_product_modal').on('shown.bs.modal', function() {
    $('.quick_add_product_modal')
        .find('.select2')
        .each(function() {
            var $p = $(this).parent();
            $(this).select2({ dropdownParent: $p });
        });
    $('.quick_add_product_modal')
        .find('input[type="checkbox"].input-icheck')
        .each(function() {
            $(this).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });
        });
});


$('.discount_modal').on('shown.bs.modal', function() {
    $('.discount_modal')
        .find('.select2')
        .each(function() {
            var $p = $(this).parent();
            $(this).select2({ dropdownParent: $p });
        });
    $('.discount_modal')
        .find('input[type="checkbox"].input-icheck')
        .each(function() {
            $(this).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });
        });
    //Datetime picker
    $('.discount_modal .discount_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });
    $('form#discount_form').validate();
});

$(document).on('submit', 'form#discount_form', function(e) {
    e.preventDefault();
    var data = $(this).serialize();

    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        success: function(result) {
            if (result.success == true) {
                $('div.discount_modal').modal('hide');
                toastr.success(result.msg);
                discounts_table.ajax.reload();
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

$(document).on('click', 'button.delete_discount_button', function() {
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            var href = $(this).data('href');
            var data = $(this).serialize();

            $.ajax({
                method: 'DELETE',
                url: href,
                dataType: 'json',
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        discounts_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

function printer_connection_type_field(ctype) {
    if (ctype == 'network') {
        $('div#path_div').addClass('hide');
        $('div#ip_address_div, div#port_div').removeClass('hide');
    } else if (ctype == 'windows' || ctype == 'linux') {
        $('div#path_div').removeClass('hide');
        $('div#ip_address_div, div#port_div').addClass('hide');
    }
}

function show_invoice_preview() {
    if ($('input[type=radio][name=scheme_type]:checked').val() == 'blank') {
        var scheme_type = '';
    } else if ($('input[type=radio][name=scheme_type]:checked').val() == 'year') {
        var d = new Date();
        var this_year = d.getFullYear();
        var scheme_type = this_year + APP.INVOICE_SCHEME_SEPARATOR;
    }
    var prefix = $('#prefix').val()+scheme_type;
    var start_number = $('#start_number').val();
    var total_digits = $('#total_digits').val();
    var preview = prefix + pad_zero(start_number, total_digits);
    $('#preview_format').text('#' + preview);
}
function pad_zero(str, max) {
    str = str.toString();
    return str.length < max ? pad_zero('0' + str, max) : str;
}
function get_sub_categories() {
    var cat = $('#category_id').val();
    $.ajax({
        method: 'POST',
        url: '/products/get_sub_categories',
        dataType: 'html',
        data: { cat_id: cat },
        success: function(result) {
            if (result) {
                $('#sub_category_id').html(result);
            }
        },
    });
}
function get_sub_units() {
    //Add dropdown for sub units if sub unit field is visible
    if($('#sub_unit_ids').is(':visible')){
        var unit_id = $('#unit_id').val();
        $.ajax({
            method: 'GET',
            url: '/products/get_sub_units',
            dataType: 'html',
            data: { unit_id: unit_id },
            success: function(result) {
                if (result) {
                    $('#sub_unit_ids').html(result);
                }
            },
        });
    }
}
function show_product_type_form() {

    //Disable Stock management & Woocommmerce sync if type combo
    if($('#type').val() == 'combo'){
        $('#enable_stock').iCheck('uncheck');
        $('input[name="woocommerce_disable_sync"]').iCheck('check');
    }
    
    var action = $('#type').attr('data-action');
    var product_id = $('#type').attr('data-product_id');
    $.ajax({
        method: 'POST',
        url: '/products/product_form_part',
        dataType: 'html',
        data: { type: $('#type').val(), product_id: product_id, action: action },
        success: function(result) {
            if (result) {
                $('#product_form_part').html(result);
                toggle_dsp_input();
            }
        },
    });
}

$(document).on('click', 'table.ajax_view tbody tr', function(e) {
    if (
        !$(e.target).is('td.selectable_td input[type=checkbox]') &&
        !$(e.target).is('td.selectable_td') &&
        !$(e.target).is('td.clickable_td') &&
        !$(e.target).is('a') &&
        !$(e.target).is('button') &&
        !$(e.target).hasClass('label') &&
        !$(e.target).is('li') &&
        $(this).data('href') &&
        !$(e.target).is('i')
    ) {
        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function(result) {
                $('.view_modal')
                    .html(result)
                    .modal('show');
            },
        });
    }
});
$(document).on('click', 'td.clickable_td', function(e) {
    e.preventDefault();
    e.stopPropagation();
    if (e.target.tagName == 'SPAN' || e.target.tagName == 'TD' || e.target.tagName == 'I') {
        return false;
    }
    var link = $(this).find('a');
    if (link.length) {
        if (!link.hasClass('no-ajax')) {
            var href = link.attr('href');
            var container = $('.payment_modal');

            $.ajax({
                url: href,
                dataType: 'html',
                success: function(result) {
                    $(container)
                        .html(result)
                        .modal('show');
                    __currency_convert_recursively(container);
                },
            });
        }
    }
});

$(document).on('click', 'button.select-all', function() {
    var this_select = $(this)
        .closest('.form-group')
        .find('select');
    this_select.find('option').each(function() {
        $(this).prop('selected', 'selected');
    });
    this_select.trigger('change');
});
$(document).on('click', 'button.deselect-all', function() {
    var this_select = $(this)
        .closest('.form-group')
        .find('select');
    this_select.find('option').each(function() {
        $(this).prop('selected', '');
    });
    this_select.trigger('change');
});

$(document).on('change', 'input.row-select', function() {
    if (this.checked) {
        $(this)
            .closest('tr')
            .addClass('selected');
    } else {
        $(this)
            .closest('tr')
            .removeClass('selected');
    }
});

$(document).on('click', '#select-all-row', function(e) {
    var table_id = $(this).data('table-id');
    if (this.checked) {
        $('#' + table_id)
            .find('tbody')
            .find('input.row-select')
            .each(function() {
                if (!this.checked) {
                    $(this)
                        .prop('checked', true)
                        .change();
                }
            });
    } else {
        $('#' + table_id)
            .find('tbody')
            .find('input.row-select')
            .each(function() {
                if (this.checked) {
                    $(this)
                        .prop('checked', false)
                        .change();
                }
            });
    }
});

$(document).on('click', 'a.view_purchase_return_payment_modal', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var href = $(this).attr('href');
    var container = $('.payment_modal');

    $.ajax({
        url: href,
        dataType: 'html',
        success: function(result) {
            $(container)
                .html(result)
                .modal('show');
            __currency_convert_recursively(container);
        },
    });
});

$(document).on('click', 'a.view_invoice_url', function(e) {
    e.preventDefault();
    $('div.view_modal').load($(this).attr('href'), function() {
        $(this).modal('show');
    });
    return false;
});
$(document).on('click', '.load_more_notifications', function(e) {
    e.preventDefault();
    var this_link = $(this);
    this_link.text(LANG.loading + '...');
    this_link.attr('disabled', true);
    var page = parseInt($('input#notification_page').val()) + 1;
    var href = '/load-more-notifications?page=' + page;
    $.ajax({
        url: href,
        dataType: 'html',
        success: function(result) {
            if ($('li.no-notification').length == 0) {
                $('ul#notifications_list').append(result);
                // $(result).append(this_link.closest('li'));
            }

            this_link.text(LANG.load_more);
            this_link.removeAttr('disabled');
            $('input#notification_page').val(page);
        },
    });
    return false;
});

$(document).on('click', 'a.load_notifications', function(e) {
    e.preventDefault();
        $('li.load_more_li').addClass('hide');
        var this_link = $(this);
        var href = '/load-more-notifications?page=1';
        $('span.notifications_count').html(__fa_awesome());
        $.ajax({
            url: href,
            dataType: 'html',
            success: function(result) {
                $('li.notification-li').remove();
                $('ul#notifications_list').prepend(result);
                $('span.notifications_count').text('');
                $('li.load_more_li').removeClass('hide');
            },
        });
});

$(document).on('click', 'a.delete_purchase_return', function(e) {
    e.preventDefault();
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            var href = $(this).attr('href');
            var data = $(this).serialize();

            $.ajax({
                method: 'DELETE',
                url: href,
                dataType: 'json',
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        purchase_return_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

$(document).on('submit', 'form#types_of_service_form', function(e) {
    e.preventDefault();
    var form = $(this);
    var data = form.serialize();
    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        beforeSend: function(xhr) {
            __disable_submit_button(form.find('button[type="submit"]'));
        },
        success: function(result) {
            if (result.success == true) {
                $('div.type_of_service_modal').modal('hide');
                toastr.success(result.msg);
                types_of_service_table.ajax.reload();
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

$(document).on('click', 'button.delete_type_of_service', function(e) {
    e.preventDefault();
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            var href = $(this).data('href');
            var data = $(this).serialize();

            $.ajax({
                method: 'DELETE',
                url: href,
                dataType: 'json',
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        types_of_service_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

$(document).on('shown.bs.modal', '.view_modal', function(e){
    if ($('#shipping_documents_dropzone').length) {
       $(this).find("div#shipping_documents_dropzone").dropzone({
            url: $('#media_upload_url').val(),
            paramName: 'file',
            uploadMultiple: true,
            autoProcessQueue: false,
            addRemoveLinks: true,
            params: {
                'model_id': $('#model_id').val(),
                'model_type': $('#model_type').val(),
                'model_media_type': $('#model_media_type').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                if (response.success) {
                    toastr.success(response.msg);
                    $('div.view_modal').modal('hide');
                } else {
                    toastr.error(response.msg);
                }
            },
        });
    }
});

$(document).on('submit', 'form#edit_shipping_form', function(e){
    e.preventDefault();
    var form = $(this);
    var data = form.serialize();
    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        beforeSend: function(xhr) {
            __disable_submit_button(form.find('button[type="submit"]'));
        },
        success: function(result) {
            if (result.success == true) {
                var myDropzone = Dropzone.forElement("#shipping_documents_dropzone");
                myDropzone.processQueue();
                if (typeof(sell_table) != 'undefined') {
                    sell_table.ajax.reload();
                }

                if (typeof(purchase_order_table) != 'undefined') {
                    purchase_order_table.ajax.reload();
                }
            } else {
                toastr.error(result.msg);
            }

            $('.view_modal').modal('hide');
        },
    });
});

$(document).on('show.bs.modal', '.register_details_modal, .close_register_modal', function () {
    __currency_convert_recursively($(this));
});

function updateProfitLoss(start = null, end = null, location_id = null, selector = null) {
    if(start == null){
        var start = $('#profit_loss_date_filter')
                    .data('daterangepicker')
                    .startDate.format('YYYY-MM-DD');
    }
    if(end == null){
        var end = $('#profit_loss_date_filter')
                    .data('daterangepicker')
                    .endDate.format('YYYY-MM-DD');
    }
    if(location_id == null){
        var location_id = $('#profit_loss_location_filter').val();
    }
    var data = { start_date: start, end_date: end, location_id: location_id };
    selector = selector == null ? $('#pl_data_div') : selector;
    var loader = '<div class="text-center">' + __fa_awesome() + '</div>';
    selector.html(loader);
    $.ajax({
        method: 'GET',
        url: '/reports/profit-loss',
        dataType: 'html',
        data: data,
        success: function(html) {
            selector.html(html);
            __currency_convert_recursively(selector);
            updateStockBySellingPrice(data);
        },
    });

    $('.nav-tabs li.active').find('a[data-toggle="tab"]').trigger('shown.bs.tab');
}

function updateStockBySellingPrice (data) {
    if ($('#closing_stock_by_sp').length > 0) {
        $.ajax({
            method: 'GET',
            url: '/reports/get-stock-by-sell-price',
            dataType: 'json',
            data: data,
            success: function(result) {
                $('#closing_stock_by_sp').html(__currency_trans_from_en(result.closing_stock_by_sp), true);
                $('#opening_stock_by_sp').html(__currency_trans_from_en(result.opening_stock_by_sp), true);
            },
        });
    }
}

$(document).on('click', 'button.activate-deactivate-location', function(){
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            $.ajax({
                url: $(this).data('href'),
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        business_locations.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

function getTotalUnreadNotifications(){
    if ($('span.notifications_count').length) {
        var href = '/get-total-unread';
        $.ajax({
            url: href,
            dataType: 'json',
            global: false,
            success: function(data) {
                if (data.total_unread != 0 ) {
                    $('span.notifications_count').text(data.total_unread);
                }
                if (data.notification_html) {
                    $('.view_modal').html(data.notification_html);
                    $('.view_modal').modal('show');
                }
            },
        });
    }
}

$(document).on('shown.bs.modal', '.view_modal', function (e) {
    if ($(this).find('#email_body').length) {
        tinymce.init({
            selector: 'textarea#email_body',
        });
    }
});
$(document).on('hidden.bs.modal', '.view_modal', function (e) {
    if ($(this).find('#email_body').length) {
        tinymce.remove("textarea#email_body");
    }

    //check if modal opened then make it scrollable
    if($('.modal.in').length > 0)
    {
        $('body').addClass('modal-open');
    }
});
$(document).on('shown.bs.modal', '.quick_add_product_modal', function (e) {
    tinymce.init({
        selector: 'textarea#product_description',
    });
});
$(document).on('hidden.bs.modal', '.quick_add_product_modal', function (e) {
    tinymce.remove("textarea#product_description");
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 100 ) {
        $('.scrolltop:hidden').stop(true, true).fadeIn();
    } else {
        $('.scrolltop').stop(true, true).fadeOut();
    }
});
$(function(){$(".scroll").click(function(){$("html,body").animate({scrollTop:$(".thetop").offset().top},"1000");return false})})

$(document).on('click', 'a.update_contact_status', function(e){
    e.preventDefault();
    var href = $(this).attr('href');
    $.ajax({
        url: href,
        dataType: 'json',
        success: function(data) {
            if (data.success == true) {
                toastr.success(data.msg);
                contact_table.ajax.reload();
            } else {
                toastr.error(data.msg);
            }
        },
    });
});

$(document).on('shown.bs.modal', '.contact_modal', function(e) {
    $('.dob-date-picker').datepicker({
      autoclose: true,
      endDate: 'today',
    });
});

$(document).on('change', '#sms_service', function(e) {
    var sms_service = $(this).val();
    $('div.sms_service_settings').each( function(){
        if (sms_service == $(this).data('service')) {
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

$(document).on('click', 'a.show-notification-in-popup', function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        method: 'GET',
        url: url,
        dataType: 'html',
        success: function(result) {
            $('.view_modal').html(result);
            $('.view_modal').modal('show');
        },
    });
})

$(document).on('click', 'a.convert-draft', function(e){
    e.preventDefault();
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            window.location = $(this).attr('href');
        }
    });
});

$(document).on('click', '.delete-media', function () {
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            var url = $(this).data('href');
            var thumbnail = $(this).closest('.img-thumbnail');
            var tr = $(this).closest('tr');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        if (thumbnail) {
                            thumbnail.remove();
                        } else if (tr) {
                            tr.remove();
                        }   
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

$(document).on('change', 'input#expense_final_total, #add_expense_modal_form .payment-amount', function() {
    calculateExpensePaymentDue();
});

function calculateExpensePaymentDue() {
    var final_total = __read_number($('input#expense_final_total'));
    var payment_amount = __read_number($('#add_expense_modal_form input.payment-amount'));
    var payment_due = final_total - payment_amount;
    $('#expense_payment_due').text(__currency_trans_from_en(payment_due, true, false));
}

$(document).on('shown.bs.dropdown', '.btn-group', function(){
    if ($(this).closest('tbody').find('tr').length < 4) {
        $('.dataTables_scrollBody').addClass('of-visible');
    }
});
$(document).on('hidden.bs.dropdown', '.btn-group', function(){
    $('.dataTables_scrollBody').removeClass('of-visible');
})

function get_expense_sub_categories() {
    var cat = $('#expense_category_id').val();
    $.ajax({
        method: 'POST',
        url: '/get-expense-sub-categories',
        dataType: 'html',
        data: { cat_id: cat },
        success: function(result) {
            if (result) {
                $('#expense_sub_category_id').html(result);
            }
        },
    });
}

function submitContactForm(form) {
    var data = $(form).serialize();
    $.ajax({
        method: 'POST',
        url: $(form).attr('action'),
        dataType: 'json',
        data: data,
        success: function(result) {
            if (result.success == true) {
                $('div.contact_modal').modal('hide');
                toastr.success(result.msg);

                if (typeof(contact_table) != 'undefined') {
                    contact_table.ajax.reload();
                }

                var lead_view = urlSearchParam('lead_view');
                if (lead_view == 'kanban') {
                    initializeLeadKanbanBoard();
                } else if(lead_view == 'list_view' && typeof(leads_datatable) != 'undefined') {
                    leads_datatable.ajax.reload();
                }
                
            } else {
                toastr.error(result.msg);
            }
        },
    });
}

$(document).on('submit', 'form#pay_contact_due_form', function(e){
    var is_valid = true;
    var payment_type = $('#pay_contact_due_form .payment_types_dropdown').val();
    var denomination_for_payment_types = JSON.parse($('#pay_contact_due_form .enable_cash_denomination_for_payment_methods').val());
    if (denomination_for_payment_types.includes(payment_type) && $('#pay_contact_due_form .is_strict').length && $('#pay_contact_due_form .is_strict').val() === '1' ) {
        var payment_amount = __read_number($('#pay_contact_due_form .payment_amount'));
        var total_denomination = $('#pay_contact_due_form').find('input.denomination_total_amount').val();
        if (payment_amount != total_denomination ) {
            is_valid = false;
        }
    }

    $('#pay_contact_due_form').find('button[type="submit"]')
            .attr('disabled', false);

    if (!is_valid) {
        $('#pay_contact_due_form').find('.cash_denomination_error').removeClass('hide');
        e.preventDefault();
        return false;
    } else {
        $('#pay_contact_due_form').find('.cash_denomination_error').addClass('hide');
    }
    
})
jQuery(document).ready(function ($) {

    // Import library
    var SmartcatSupport = window.SmartcatSupport;

    var TicketEvents = {

        initialize: function () {
            $(document).on('dblclick', 'tr', TicketActions.viewTicket);
            $(document).on('click', '.edit_ticket_trigger', TicketActions.editTicket);

            $(document).on('submit', '.edit_ticket_form', { callback: TicketActions.refreshTicket }, TicketActions.ajaxSubmit );
            $(document).on('submit', '.comment_form', { callback: TicketActions.appendComment }, TicketActions.ajaxSubmit);

            SmartcatSupport.wp_ajax('list_support_tickets', null, function (response) {

                $('#support_ticket_tab_view').Tabular( {
                    noClose: 'ticket_list'
                } );

                $('#support_ticket_tab_view').Tabular('newTab', 'ticket_list', 'Tickets', response.html);

                $('#support_tickets_table').DataTable(
                    {
                        select: 'single',
                        columns: response.columns,
                        columnDefs: [{targets: 0, visible: false}]
                    }
                );

            });

        }

    }

    var TicketActions = {

        viewTicket: function () {
            var row = $('#support_tickets_table').DataTable().row(this).data();
            var ticket_id = row['id'];
            var ticket_subject = row['subject'];

            SmartcatSupport.wp_ajax('edit_support_ticket', {ticket_id: ticket_id}, function (response) {

                if( response.success ) {
                    var pane = $('#support_ticket_tab_view').Tabular(
                        'newTab', ticket_id, ticket_subject, '<div class="support_ticket">' + response.data + '</div>'
                    );

                    TicketActions.disableEditing( pane.find( '.edit_ticket_form' ) );

                    SmartcatSupport.wp_ajax('support_ticket_comments', {ticket_id: ticket_id}, function (response) {

                        if (response.success) {
                            pane.find('.ticket_detail').parent().append(response.data);

                            SmartcatSupport.tinyMCE( '.comment_form textarea' );
                        } else {
                            console.log(response);
                        }

                    });



                } else {
                    console.log( response.data );
                }

            });

        },

        editTicket: function ( e ) {
            e.preventDefault();

            var form = $(this).parents('.edit_ticket_form');

            form.find('.submit_button_wrapper').show();

            TicketActions.enableEditing( form );

            $(this).parent().hide();
        },

        refreshTicket: function ( form, data ) {
            var frame = form.parents( '.support_ticket' );

            form.parents( '.ticket_detail' ).replaceWith( data );

            TicketActions.disableEditing( frame.find( '.edit_ticket_form' ) );
        },

        ajaxSubmit: function ( e ) {
            e.preventDefault();

            var unlockDelay = 1000;
            var form = $(this);
            var status = form.find('.submit_button .status');
            var text = form.find('.submit_button .text');

            form.find('.submit_button').prop('disabled', true);
            status.removeClass('hidden check fail').addClass('spinner');
            text.text(text.data('wait'));

            SmartcatSupport.wp_ajax($(this).data('action'), $(this).serializeArray(), function (response) {
                console.log( response );

                form.find('.error_field').removeClass('error_field');
                form.find('.error_msg').remove();

                if (response.success) {

                    status.removeClass('spinner').addClass('check');
                    text.text(text.data('success'));

                    setTimeout( function () {
                        status.removeClass('check');
                        text.text(text.data('default'));
                        e.data.callback( form, response.data );
                    }, unlockDelay );

                } else {

                    status.removeClass('spinner').addClass('fail');
                    text.text(text.data('fail'));

                    // Match fields to error messages
                    $.each(response.data, function (key, value) {

                        var field = $(form).find('[name="' + key + '"]');
                        field.addClass('error_field');
                        field.parent().append('<span class="error_msg">' + value + '</span>');

                    });

                }

                // Unlock the form
                setTimeout( function () {
                    form.find('.submit_button').prop('disabled', false);
                }, unlockDelay );

            });
        },

        enableEditing: function (form) {
            form.find('.form_field').prop('disabled', false);

            tinymce.remove( '.ticket_editor textarea' );
            SmartcatSupport.tinyMCE('.ticket_editor textarea');
        },

        disableEditing: function (form) {
            SmartcatSupport.tinyMCE( '.ticket_editor textarea', false );
            $( form ).find( '.mce-panel' ).addClass( 'disabled' );

            form.find('.submit_button').parent().hide();
            form.parent().find('.edit_ticket_trigger').parent().show();
            form.find('.form_field').prop('disabled', true);


        },

        appendComment: function(form, data) {
            form.parents().find( '.comments' ).append( data );
            form.find('[name="comment_content"]').val('');
        }

    };

    TicketEvents.initialize();
});

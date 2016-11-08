jQuery( document ).ready( function( $ ) {

    var TicketEvents = {

        initialize: function() {

            $( document ).on( 'dblclick', 'tr', TicketActions.editTicket );

            $( document ).on( 'submit', '.edit_ticket_form', TicketActions.ajaxSubmit );
            $( document ).on( 'focus', '.form_field', TicketActions.showSaveButton );
            $( document ).on( 'click', '.reply_trigger', TicketActions.showReplyForm );

            $.SmartcatSupport().wp_ajax( 'list_support_tickets', null, function ( response ) {

                $( '#support_ticket_tab_view' ).Tabular();
                $( '#support_ticket_tab_view' ).Tabular( 'newTab', 'ticket_list', 'Tickets', response.html );

                $( '#support_tickets_table' ).DataTable(
                    {
                        select: 'single',
                        columns: response.columns,
                        columnDefs: [ { targets: 0, visible: false } ]
                    }
                );

            } );

        }

    }

    var TicketActions = {

        editTicket: function () {
            var row = $( '#support_tickets_table' ).DataTable().row( this ).data();
            var ticket_id = row['id'];
            var ticket_subject = row['subject'];

            $.SmartcatSupport().wp_ajax( 'edit_support_ticket', { ticket_id: ticket_id }, function ( response ) {
                $( '#support_ticket_tab_view' ).Tabular( 'newTab', ticket_id, ticket_subject, response.html );
            } );
        },

        showSaveButton: function () {
            $( '.status' ).empty();
            $( this ).parents( '.edit_ticket_form' ).find( '.submit_button' ).show();
        },

        showReplyForm: function ( e ) {
            $( '.comment_section' ).removeClass( 'hidden' ) && $( this ).parent().remove();

            e.preventDefault();
        },

        ajaxSubmit: function( e ) {
            var form = $( this );

            if ( form.attr( 'lock' ) !== true ) {

                // Prevent multiple submissions
                form.attr( 'lock', true );

                var status = form.find('.submit_button .status');
                var text = form.find('.submit_button .text');

                status.removeClass('hidden check fail').addClass('spinner');
                text.text(text.data('wait'));

                $.SmartcatSupport().wp_ajax('save_support_ticket', $(this).serializeArray(), function (response) {

                    form.find('.error_field').removeClass('error_field');
                    form.find('.error_msg').remove();

                    setTimeout(function () {

                        if (response.success) {

                            status.removeClass('spinner').addClass('check');
                            text.text(text.data('success'));

                        } else {

                            status.removeClass('spinner').addClass('fail');
                            text.text(text.data('fail'));

                            // Match fields to error messages
                            $.each(response.data, function (key, value) {

                                var field = $(form).find('[data-field_name="' + key + '"]');
                                field.addClass('error_field');
                                field.parent().append('<span class="error_msg">' + value + '</span>');

                            });

                        }

                        // Unlock the form
                        form.attr( 'lock', false );

                    }, 4000);

                });
            }

            e.preventDefault();
        }

    };

    TicketEvents.initialize();
} );

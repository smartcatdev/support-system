jQuery( document ).ready( function( $ ) {

    var TicketEvents = {

        initialize: function() {

            $( document ).on( 'dblclick', 'tr', TicketActions.editTicket );
            $( document ).on( 'submit', '.edit_ticket_form', TicketActions.saveTicket );
            $( document ).on( 'focus', '.form_field', TicketActions.showSaveButton );
            $( document ).on( 'focus', '.form_field', TicketActions.clearError );
            //$( document ).on( 'click', '.status', TicketActions.clearStatus );

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

        saveTicket: function ( e ) {
            e.preventDefault();

            var form = $( this );

            form.children('.submit_button').hide();
            form.children( '.status' ).html( '<div class="spinner"></div>' );

            $.SmartcatSupport().wp_ajax( 'save_support_ticket', $( this ).serializeArray(), function ( response ) {

                setTimeout( function () {

                    if( response.success ) {

                        $( '.spinner' ).removeClass( 'spinner' ).addClass( 'icon-checkmark' );
                        form.children( '.status' ).append( '<div>' + response.data + '</div>' );

                    } else {

                        $( '.spinner' ).remove();

                        $.each( response.data, function ( key, value ) {
                            var td = form.find( '[data-field_name="' + key + '"]' ).parent();

                            if( !td.children( '.error_msg' ).length ) {
                                td.append( '<span class="error_msg">' + value + '</span>' );
                            }

                        } );

                    }

                }, 2000 );

            } );

            setTimeout( function () {

                $( '.status' ).empty();

            }, 6000 );
        },

        showSaveButton: function () {
            $( '.status' ).empty();
            $( this ).parents( '.edit_ticket_form' ).find( '.submit_button' ).show();
        },

        clearError: function () {
            $( this ).siblings( '.error_msg' ).remove();
        }

    };

    $( function () {

        TicketEvents.initialize();

    });

} );

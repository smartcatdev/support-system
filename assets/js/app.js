jQuery( document ).ready( function( $ ) {

    var TicketEvents = {

        initialize: function() {

            $( document ).on( 'dblclick', 'tr', TicketActions.editTicket );
            $( document ).on( 'submit', '.edit_ticket_form', TicketActions.saveTicket );
            $( document ).on( 'focus', '.form_field', TicketActions.showSaveButton );

            $.SmartcatSupport().wp_ajax( 'list_support_tickets', null, function ( response ) {

                $( '#support_ticket_tab_view' ).Tabular();
                $( '#support_ticket_tab_view' ).Tabular( 'newTab', 'ticket_list', 'Tickets', response.html );

                $( '#support_tickets_table' ).DataTable(
                    {
                        select: 'single',
                        columnDefs: [
                            {
                                targets: 0,
                                visible: false
                            }
                        ]
                    }
                );

            } );
        }

    }

    var TicketActions = {

        editTicket: function ( ) {
            var row = $( '#support_tickets_table' ).DataTable().row( this ).data();
            var ticket_id = row[0];
            var ticket_subject = row[2];

            $.SmartcatSupport().wp_ajax( 'edit_support_ticket', { ticket_id: ticket_id }, function ( response ) {
                $( '#support_ticket_tab_view' ).Tabular( 'newTab', ticket_id, ticket_subject, response.html );
            } );
        },

        saveTicket: function ( e ) {
            e.preventDefault();

            $( this ).find( '.status' ).html( '<div class="spinner"></div>' );

            $.SmartcatSupport().wp_ajax( 'save_support_ticket', $( this ).serializeArray(), function ( response ) {

                setTimeout( function () {
                    $( '.spinner' ).delay( 20000 ).removeClass( 'spinner' ).addClass( 'icon-checkmark' );
                }, 2000 );

            } );
        },

        showSaveButton: function() {
            $( this ).parents( '.edit_ticket_form' ).find( '.submit_button' ).removeClass( 'hidden' );
            $( this ).parents( '.edit_ticket_form' ).find( '.status' ).empty();
        }

    };

    $( function () {

        TicketEvents.initialize();

    });

} );

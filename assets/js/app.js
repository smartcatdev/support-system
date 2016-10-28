jQuery( document ).ready( function( $ ) {

    var TicketEvents = {

        initialize: function() {

            $( document ).on( 'dblclick', 'tr', TicketActions.editTicket );
            $( document ).on( 'submit', '.edit_ticket_form', TicketActions.saveTicket );

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

            $.SmartcatSupport().wp_ajax( 'save_support_ticket', $( this ).serializeArray(), function ( response ) {
                // console.log( response );
            } );
        }

    };

    $( function () {

        TicketEvents.initialize();

    });

} );

jQuery( document ).ready( function( $ ) {



    var TicketEvents = {
        
        initialize: function() {

            $( document ).on( 'submit', '#select_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#new_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#edit_ticket_form', TicketActions.doAjax );

            $( document ).on( 'submit', '#list_tickets', TicketActions.ticketTable );
        }
        
    }
    
    var TicketActions = {
        
        doAjax: function( e ) {
            e.preventDefault();
     
            var data = $( this ).serializeArray();
            data.push( {
                name: 'action', 
                value: $( this ).attr( 'data-action' ) 
            } );


            $.post( SmartcatSupport.ajaxURL, $.param( data ), function( response ) {

                $('.entry-content').html( response.html );

            } );
        },
        
        ticketTable: function( e ) {
            e.preventDefault();

            $.post( SmartcatSupport.ajaxURL, { action: 'list_support_tickets' }, function( response ) {

                $( '.entry-content' ).html( response.html );

                var table = $( '#support_tickets_table' ).DataTable(
                    {
                        select: 'single'
                    }
                );

                table.on( 'select', function ( e, dt, type, indexes ) {
                    if ( type === 'row' ) {
                        var id = table.rows( indexes ).data()[0][0];

                        $.post( SmartcatSupport.ajaxURL, { action: 'edit_support_ticket', ticket_id: id }, function( response ) {
                            $( '.entry-content' ).html( response.html );
                        } );

                    } });

            } );
        }

    }

    $( function () {
        TicketEvents.initialize();
    });
   
} );

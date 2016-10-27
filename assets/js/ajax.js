jQuery( document ).ready( function( $ ) {



    var TicketEvents = {
        
        initialize : function() {
            
           // $( document ).on( 'submit', '#all_tickets',  TicketActions.tableData );
            $( document ).on( 'submit', '#select_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#new_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#view_tickets', TicketActions.doAjax );
            
            $( document ).on( 'submit', '#edit_ticket_form', TicketActions.doAjax );
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
                console.log( response );
                
                $('.entry-content').html( response.html );

            } );
            
        },
        
        // tableData: function( e ) {
        //     e.preventDefault();
        //
        //     $.post( SmartcatSupport.ajaxURL, { action: $( this ).attr( 'data-action' ) }, function( response ) {
        //         console.log( response );
        //
        //         $( '.entry-content' ).html( response.data );
        //         $( '.data_table' ).DataTable();
        //
        //     } );
        // }

    }

    $( function () {
        
        TicketEvents.initialize();

    });
   
} );

jQuery( document ).ready( function( $ ) {

    var TicketEvents = {
        
        initialize : function() {
            
            $( 'body' ).on( 'submit', '#support_ticket_form', TicketActions.doAjax );
            
        }
        
    }
    
    var TicketActions = {
        
        doAjax : function( e ) {

            e.preventDefault();
            
            var data = $( this ).serializeArray();
            data.push( {
                name: 'action', 
                value: $( this ).attr( 'data-action' ) 
            } );

            $.post( SmartcatSupport.ajaxURL, $.param( data ), function( response ) {
                
                console.log( response );
                
            } );
            
        }

    }

    $( function () {
        
        TicketEvents.initialize();

    }); 
    
} );

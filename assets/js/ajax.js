jQuery( document ).ready( function( $ ) {

    var TicketEvents = {
        
        initialize : function() {
            
            $( document ).on( 'submit', '#select_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#new_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#support_ticket_form', TicketActions.doAjax );
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
                
                $('.entry-content').html( response.data );
                
            } );
            
        }

    }

    $( function () {
        
        TicketEvents.initialize();

    }); 
    
} );

;( function( $ ) {

    $.SmartcatSupport = function() {

        var wp_ajax = function( action, data, callback ) {

            if( data !== null ) {
                if( Array.isArray( data ) ) {
                    data.push( { name: 'action', value: action } );
                } else {
                    data[ 'action' ] = action;
                }
            } else {
                data = { action: action }
            }

            $.post( SmartcatSupport.ajaxURL, $.param( data ), callback );
        }

        return {
            wp_ajax: wp_ajax
        }
    }

} )( jQuery );

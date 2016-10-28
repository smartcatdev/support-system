;( function( $ ) {
    $.fn.wp_ajax = function( action, data, callback ) {
        $.post(
            SmartcatSupport.ajaxURL,
            {
                action: data
            }

            param( data ),

            callback( response )
        );
    }
};
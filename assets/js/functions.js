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

        var tinyMCE = function ( selector, enabled = true ) {
            var args = {
                selector: selector,
                menubar: false,
                statusbar: false,
                plugins: 'wpautoresize',
                wp_autoresize_on: true,
                force_br_newlines: true,
                force_p_newlines: false
            };

            if (!enabled) {
                $.extend(args, {
                    toolbar: false,
                    statusbar: false,
                    readonly: 1
                });
            }

            tinymce.init(args);
        }

        return {
            wp_ajax: wp_ajax,
            tinyMCE: tinyMCE
        }
    }

} )( jQuery );

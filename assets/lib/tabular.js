;( function( $, window, document, undefined ) {

    "use strict";

    var pluginName = "Tabular",
        defaults = {
            //        propertyName: "value"
        };

    var backStack = [];

    // The actual plugin constructor
    function Plugin ( element, options ) {
        this.element = element;

        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend( Plugin.prototype, {
        init: function() {

            $( this.element ).html(
                '<div class="tabular_tab_bar">' +
                '<ul></ul>' +
                '</div>' +
                '<div class="tabular_content_pane"></div>'
            );

            $( document ).on( 'click', '.tabular_link', this.switchTab );
            $( document ).on( 'click', '.tabular_close', this.closeTab );
        },

        newTab: function( id, title, content ) {
            $('.tabular_card').hide();

            if( $( this.element ).find( '[data-card="' + id + '"]' ).length == 0 ) {

                $(this.element).find('.tabular_tab_bar > ul').append(
                    '<li class="tabular_tab" data-card="' + id + '">' +
                    '<a class="tabular_link" href="#">'+ title + '</a>' +
                    '<span class="tabular_close">&#10006</span>' +
                    '</li>'
                );

                $(this.element).find('.tabular_content_pane').append(
                    '<div class="tabular_card" id="tabular_card_' + id + '">' + content + '</div>'
                );
            } else {
                $( '#tabular_card_' + id ).show();
            }
        },

        closeTab: function () {

            var prev = $( this ).parent().prev();
            var next = $( this ).parent().next();

            if( !next.hasClass( 'tabular_tab' ) && prev.hasClass( 'tabular_tab' ) ) {
                $( '.tabular_card' ).hide();
                $( '#tabular_card_' + prev.data( 'card' ) ).show();
            }

            $( '#tabular_card_' + $( this ).parent().data( 'card' ) ).remove();
            $( '.tabular_tab_bar' ).find( '[data-card="' + $( this ).parent().data( 'card' ) + '"]' ).remove();
        },

        switchTab: function() {
            $( '.tabular_card' ).hide();
            $( '#tabular_card_' + $( this ).parent().data( 'card' ) ).show();
        }
    } );

    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        if (options === undefined || typeof options === 'object') {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
                }
            });
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                if (instance instanceof Plugin && typeof instance[options] === 'function') {
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                }
            });

            return returns !== undefined ? returns : this;
        }
    };

} )( jQuery, window, document );
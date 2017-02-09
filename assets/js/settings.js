"use strict";

jQuery( "document" ).ready( function ( $ ) {

    var settingsEvents = {
        initialize: function () {
            $( "body" ).on( "keyup", ".settings_form input[name=\"confirm_password\"]", settingsActions.validate_password );
            $( "body" ).on( "submit", ".settings_form", settingsActions.save_settings );
        }
    };

    var settingsActions = {
        validate_password: function () {
            var field = $( this );
            var password_field = $( ".settings_form input[name=\"new_password\"]" );

            field.siblings( ".error_msg" ).remove();
            field.removeClass( "error_field" );
            $( ".settings_form input[type=\"submit\"]" ).prop( "disabled", false);

            if( field.val() !== password_field.val() && field.val() !== "" ) {
                field.addClass( "error_field" );
                field.parent().append( "<span class=\"error_msg glyphicon glyphicon-exclamation-sign\"></span>" );
                field.parents( "form").find( "input[type=\"submit\"]" ).prop( "disabled", true );
            }
        },

        save_settings: function ( e ) {
            e.preventDefault();

            var form = $( ".settings_form" );

            form.find( ".error_field" ).removeClass( "error_field" );
            form.find(" .error_msg" ).remove();

            $.ajax( {
                type: "post",
                url: SupportSystem.ajaxUrl + "?action=support_save_settings",
                data: form.serializeArray(),
                success: function ( response ) {

                    if( response.success ) {
                        form.find( "p.status" ).text( response.data ).removeClass( "hidden" );
                    } else {
                        $.each( response.data, function ( key, value) {
                            var field = $( ".settings_form" ).find( "[name=\"" + key + "\"]" );
                            field.addClass( "error_field" );
                            field.parent().append( "<span class=\"error_msg\">" + value + "</span>" );
                        } );
                    }

                }
            } );
        }
    };

    $( function () {
        settingsEvents.initialize();
    } );

} );
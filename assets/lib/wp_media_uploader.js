/**
 *
 * wpMediaUploader v1.0 2016-11-05
 * Copyright (c) 2016 Smartcat
 *
 * Modified for use with Settings API
 */

( function( $) {

    $.wpMediaUploader = function( options ) {

        var settings = $.extend({

            target : '.support-uploader',
            uploaderTitle : 'Select or upload image',
            uploaderButton : 'Set image',
            multiple : false,
            buttonText : 'Upload image',
            buttonClass : '.support-upload',
            previewSize : '150px',
            modal : false,
            buttonStyle : {},

        }, options );

        $( settings.target ).each( function ( index, element ) {

            $( element ).parent().append('<div><br><img src="' + $( element ).val() + '" style="max-width: ' + settings.previewSize + '"/></div>');
            $( element ).parent().append('<a href="#" class="button ' + settings.buttonClass.replace('.', '') + '">' + settings.buttonText + '</a>');

            $(settings.buttonClass).css(settings.buttonStyle);

            $('body').on('click', settings.buttonClass, function (e) {

                e.preventDefault();

                var custom_uploader = wp.media({
                    title: settings.uploaderTitle,
                    button: {
                        text: settings.uploaderButton
                    },
                    multiple: settings.multiple
                })
                    .on('select', function () {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $( element ).parent().find('img').attr('src', attachment.url).show();
                        $( element ).parent().val(attachment.url);
                        $( element ).val(attachment.url);
                        if (settings.modal) {
                            $('.modal').css('overflowY', 'auto');
                        }
                    })
                    .open();
            });

        } );

    }
})(jQuery);

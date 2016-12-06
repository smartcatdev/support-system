jQuery(document).ready(function ($) {
    // $('.admin-control').change(function () {
    //     var field = $(this);
    //
    //     $.ajax({
    //         url: SupportSystem.ajaxURL,
    //         data: {
    //             action: 'support_update_meta',
    //             meta: field.attr('name'),
    //             value: field.val(),
    //             id: field.data('id')
    //         }
    //     })
    // })


    var toggleFlag = function () {
        var field = $(this);

        $.ajax({
            url: SupportSystem.ajaxURL,
            data: {
                action: 'support_update_meta',
                meta: field.attr('name'),
                value: field.hasClass('active') ? '' : 'on',
                id: field.data('id')
            },

            success: function () {
                if (field.hasClass('active')) {
                    field.removeClass('active');
                    $('#support_inline_' + field.data('id')).children('.flagged').text('');
                } else {
                    $('#support_inline_' + field.data('id')).children('.flagged').text('on');
                    field.addClass('active');
                }
            }
        });
    }

    $('.support_admin_toggle[name="flagged"]').on('click', toggleFlag);

    var $wp_inline_edit = inlineEditPost.edit;

    inlineEditPost.edit = function( id ) {

        $wp_inline_edit.apply( this, arguments );

        var $post_id = 0;
        if ( typeof( id ) == 'object' ) {
            $post_id = parseInt(this.getId(id));
        }

        if ( $post_id > 0 ) {
            $('#support_inline_' + $post_id).children().each(function () {
                var field = $('.form_field[name="' + $(this).attr('class') + '"]');

                if(field.attr('type') == 'checkbox') {
                    field.attr('checked', $(this).text() === 'on');
                } else {
                    field.val($(this).text());
                }


            });

        }

    };

});
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

    $('.support_admin_toggle').click(function () {
        var field = $(this);

        $.ajax({
            url: SupportSystem.ajaxURL,
            data: {
                action: 'support_update_meta',
                meta: field.attr('name'),
                value: field.hasClass('active') ? 0 : 1,
                id: field.data('id')
            },

            success: function() {
                if(field.hasClass('active')) {
                    field.removeClass('active');
                } else {
                    field.addClass('active');
                }
            }
        })
    })


    var $wp_inline_edit = inlineEditPost.edit;

    inlineEditPost.edit = function( id ) {

        $wp_inline_edit.apply( this, arguments );

        var $post_id = 0;
        if ( typeof( id ) == 'object' ) {
            $post_id = parseInt(this.getId(id));
        }

        if ( $post_id > 0 ) {
            $('#support_inline_' + $post_id).children().each(function () {
                $('.form_field[name="' + $(this).attr('class') + '"]').val($(this).text());
            });

        }

    };

});
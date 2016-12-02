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

    // we create a copy of the WP inline edit post function
    var $wp_inline_edit = inlineEditPost.edit;
    // and then we overwrite the function with our own code
    inlineEditPost.edit = function( id ) {

        // "call" the original WP edit function
        // we don't want to leave WordPress hanging
        $wp_inline_edit.apply( this, arguments );

        // now we take care of our business

        // get the post ID
        var $post_id = 0;
        if ( typeof( id ) == 'object' )
            $post_id = parseInt( this.getId( id ) );

        if ( $post_id > 0 ) {

            // define the edit row
            var $edit_row = $( '#edit-' + $post_id );

            // get the release date
            var $release_date = $( '#release_date-' + $post_id ).text();

            // populate the release date
            $edit_row.find( '.form_field' ).val( $release_date );

        }

    };


});
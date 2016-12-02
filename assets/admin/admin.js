jQuery(document).ready(function ($) {
    $('.admin-control').change(function () {
        var field = $(this);

        $.ajax({
            url: SupportSystem.ajaxURL,
            data: {
                action: 'support_update_meta',
                meta: field.attr('name'),
                value: field.val(),
                id: field.data('id')
            }
        })
    })

    $('.admin-toggle').click(function () {
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
});
jQuery(document).ready(function ($) {
    $('.admin-control').change(function () {
        var field = $(this);
console.log(SupportSystem.ajaxURL);
        $.ajax({
            url: SupportSystem.ajaxURL,
            data: {
                action: 'support_update_meta',
                meta: field.attr('name'),
                value: field.val(),
                id: field.data('post_id')
            }
        })
    })
});
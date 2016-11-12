;(function ($, app) {

    app.ajax = function (action, data, callback) {

        if (data !== null) {
            if (Array.isArray(data)) {
                data.push({name: 'action', value: action});
            } else {
                data['action'] = action;
            }
        } else {
            data = {action: action}
        }

        $.post(app.ajaxURL, $.param(data), callback);
    }

    app.tinymce = function (selector) {
        var args = {
            selector: selector,
            menubar: false,
            statusbar: false,
            plugins: 'wpautoresize',
            wp_autoresize_on: true,
            force_br_newlines: true,
            force_p_newlines: false
        };

        tinymce.init(args);
    },

    app.edit_comment = function (context) {
            var comment_id = context.parents('.comment').data('id');

            app.ajax('support_comment_edit', {comment_id: comment_id}, function (response) {
                context.parents('.comment').find('.content').html(response.data);
                app.tinymce('[name="comment_content"]');
            });
        },

    app.view_ticket = function (ticket) {
        app.ajax('view_support_ticket', {ticket_id: ticket.id}, function (response) {

            if (response.success) {
                var pane = $('#support_ticket_tab_view').Tabular(
                    'newTab', ticket.id, ticket.subject, '<div class="support_ticket">' + response.data + '</div>'
                );

                app.ajax('support_ticket_comments', {ticket_id: ticket.id}, function (response) {

                    if (response.success) {
                        pane.find('.ticket').parent().append(response.data);
                    }
                });
            }

        });

    },

    app.edit_ticket = function (context) {
        var ticket_id = context.parents('.ticket').data('id');



        app.ajax('edit_support_ticket', {ticket_id: ticket_id}, function (response) {
            console.log(response);

            if (response.success) {
                context.parents('.ticket').find('.details').replaceWith(response.data);
            }

        });
    },

    app.refresh_ticket = function (form, data) {
        form.parents('.ticket_detail').replaceWith(data);
    },

    app.append_comment = function (form, data) {
        form.parents().find('.comments').append(data);
    }

    app.replace_comment = function (form, data) {
        form.parents('.comment').replaceWith(data);
    },

    app.submit_form = function (e) {
        e.preventDefault();

        var unlockDelay = 1000;
        var form = $(this);
        var button = form.find('.submit_button');
        var status = form.find('.submit_button .status');
        var text = form.find('.submit_button .text');

        button.prop('disabled', true);
        status.removeClass('hidden check fail').addClass('spinner');
        text.text(text.data('wait'));

        app.ajax(form.data('action'), form.serializeArray(), function (response) {
            form.find('.error_field').removeClass('error_field');
            form.find('.error_msg').remove();

            if (response.success) {

                status.removeClass('spinner').addClass('check');
                text.text(text.data('success'));

                setTimeout(function () {
                    status.removeClass('check');
                    text.text(text.data('default'));
                    app[form.data('after')](form, response.data);
                }, unlockDelay);

            } else {

                status.removeClass('spinner').addClass('fail');
                text.text(text.data('fail'));

                // Match fields to error messages
                $.each(response.data, function (key, value) {

                    var field = $(form).find('[name="' + key + '"]');
                    field.addClass('error_field');
                    field.parent().append('<span class="error_msg">' + value + '</span>');

                });

            }

            setTimeout(function () {
                button.prop('disabled', false);
            }, unlockDelay);
        });
    }

})(jQuery, SupportSystem);
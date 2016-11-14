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
    },

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

    app.get_editor = function (context) {
        context = context.first();

        if(!context.data('saved_state')) {
            context.data('saved_state', context.find('.inner').html());
        }

        app.ajax(context.data('ajax_action'), {id: context.data('id')}, function (response) {
            if(response.success) {
                context.find('.inner').html(response.data);
                app.tinymce('[name="content"]');
            }
        });
    },

    app.cancel_editor = function(context) {
        context = context.first();

        if(context.data('saved_state')) {
            context.find('.inner').html(context.data('saved_state'));
        }
    },

    app.view_ticket = function (ticket) {
        app.ajax('support_view_ticket', {id: ticket.id}, function (response) {

            if (response.success) {
                var pane = $('#support_ticket_tab_view').Tabular(
                    'newTab', ticket.id, ticket.subject, '<div class="support_ticket">' + response.data + '</div>'
                );

                app.ajax('support_ticket_comments', {id: ticket.id}, function (response) {
                    if (response.success) {
                        pane.find('.ticket').parent().append(response.data);
                        app.tinymce('[name="content"]');
                    }
                });
            }

        });

    },

    app.refresh_ticket = function (context, data) {
        context.replaceWith(data);
    },

    app.append_comment = function (context, data) {
        context.find('.comments').append(data);
    },

    app.refresh_comment = function (context, data) {
        context.first().replaceWith(data);
    },

    app.delete_comment = function (context) {
        app.ajax('support_delete_comment', {id: context.data('id')}, function(response) {
            if(response.success) {
                context.first().remove();
           }
        });
    },

    app.submit_form = function (e) {
        e.preventDefault();

        var unlockDelay = 1000;
        var form = $(this);
        var button = form.find('.button.submit');
        var status = form.find('.button.submit .status');
        var text = form.find('.button.submit .text');

        button.prop('disabled', true);
        status.removeClass('hidden check fail').addClass('spinner');
        text.text(text.data('wait'));

        app.ajax(form.data('action'), form.serializeArray(), function (response) {
            console.log(response);

            form.find('.error_field').removeClass('error_field');
            form.find('.error_msg').remove();

            if (response.success) {

                status.removeClass('spinner').addClass('check');
                text.text(text.data('success'));

                setTimeout(function () {
                    status.removeClass('check');
                    text.text(text.data('default'));
                    app[form.data('after')](form.parents('.root'), response.data);
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
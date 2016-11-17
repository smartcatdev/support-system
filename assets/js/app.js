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

    app.create_ticket = function () {
            app.new_tab('create', 'Unsaved Ticket', function(element) {
                app.ajax('support_create_ticket', null, function (response) {
                    if (response.success) {
                        element.html('<div class="support_ticket">' + response.data + '</div>');
                    }
                });
            });

        },

    app.view_ticket = function (ticket) {
            app.new_tab(ticket.id, ticket.subject, function (element) {
                app.ajax('support_view_ticket', {id: ticket.id}, function (response) {
                    if (response.success) {
                        element.html('<div class="support_ticket">' + response.data + '</div>');

                        app.ajax('support_ticket_comments', {id: ticket.id}, function (response) {
                            if (response.success) {
                                element.find('.support_ticket').append(response.data);
                                app.tinymce('[name="content"]');
                            }

                        });
                    }
                });
            });
        },

    app.edit_ticket = function (context) {
        if (!context.data('saved_state')) {
            context.data('saved_state', context.find('.inner').html());
        }

        app.ajax('support_edit_ticket', {id: context.data('id')}, function (response) {

            if (response.success) {
                context.find('.inner').html(response.data);
                app.tinymce('[name="content"]');
            }
        });
    },

    app.edit_comment = function (context) {
        if (!context.data('saved_state')) {
            context.data('saved_state', context.find('.inner').html());
        }

        app.ajax('support_edit_comment', {id: context.data('id')}, function (response) {
            if (response.success) {
                context.find('.inner').html(response.data);
                app.tinymce('[name="content"]');
            }
        });
    },

    app.refresh_ticket = function (context, data) {
            context.replaceWith(data);
        },

    app.cancel_editor = function (context) {
        if (context.data('saved_state')) {
            context.find('.inner').html(context.data('saved_state'));
        }
    },

    app.append_comment = function (context, data) {
        context.parents().find('.comments').append(data);
    },

    app.refresh_comment = function (context, data) {
        context.first().replaceWith(data);
    },

    app.delete_comment = function (context) {
        app.ajax('support_delete_comment', {id: context.data('id')}, function (response) {
            if (response.success) {
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
                        app[form.data('after')](form.parents('.support_card').first(), response.data);
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
        },

    app.replace_table = function (context, data) {
            $('#support_tickets_table_wrapper').replaceWith(data);

            var cols = [];

            $('#support_tickets_table th').each(function () {
                cols.push({ data: $(this).data('id') });
            });

            $('#support_tickets_table').DataTable({
                responsive: true,
                columns: cols
            });
        },

    app.new_tab = function (id, label, callback) {
        var tabs = $('#support_system .tabs');
        var existing = false;

        tabs.find('.ui-tabs-nav').children('li').each(function (index) {
            if ($(this).data('id') === id) {
                existing = index;
            }
        });

        if (!existing) {
            var li = $(
                "<li>" +
                "<a href='#" + id + "'>" + label + "</a>" +
                "<span class='ui-icon ui-icon-close' role='presentation'>&#10006;</span>" +
                "</li>"
            );

            li.data('id', id);

            var panel = $($.parseHTML('<div id="' + id + '"></div>'));
            callback(panel);

            tabs.find('.ui-tabs-nav').append(li);
            tabs.append(panel);

            tabs.tabs('refresh');
            tabs.tabs('option', 'active', li.index());
        } else {
            tabs.tabs('option', 'active', existing);
        }
    }

})(jQuery, SupportSystem);
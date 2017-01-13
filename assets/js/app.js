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

        $.ajax({
            url: app.ajaxUrl,
            data: $.param(data),
            success: function (result,status,xhr) {
                callback(result);
            },
            error: function (result,status,xhr) {
                console.log('result: ' + result + ' status: ' + status + ' xhr:' + xhr);
            }
        });
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

    app.submit_form = function (e) {

        e.preventDefault();

        var form = $(this);

        app.ajax(form.data('action'), form.serializeArray(), function (response) {

            form.find('.error_field').removeClass('error_field');
            form.find('.error_msg').remove();

                form.parent().find('.spinner').remove();

                if (response.success) {
                    if (form.data('after') !== undefined) {
                        app[form.data('after')](response, form);
                    }
                } else {

                    // Match fields to error messages
                    $.each(response.data, function (key, value) {
                        var field = $(form).find('[name="' + key + '"]');
                        field.addClass('error_field');
                        field.parent().append('<span class="error_msg">' + value + '</span>');

                    });
                }
        });

        return;
    },

    app.new_tab = function (id, label, callback) {
            var tabs = $('#support_system .tabs');
            var existing = false;

            tabs.find('.ui-tabs-nav li').each(function (index) {
                if ($(this).data('id') === id) {
                    existing = index;
                }
            });

            if (!existing) {
                var li = $(
                    '<li>' +
                    '<a href="#' + id + '">' + label + '</a>' +
                    '<i class="ui-icon-close icon-cross"></i>' +
                    '</li>'
                );

                li.data('id', id);
                li.data('callback', callback);

                var panel = $($.parseHTML('<div id="' + id + '"></div>'));
                callback(panel);

                tabs.find('.ui-tabs-nav').append(li);
                tabs.append(panel);

                tabs.tabs('refresh');
                tabs.tabs('option', 'active', li.index());
            } else {
                tabs.tabs('option', 'active', existing);
            }
        },

    app.view_ticket = function (trigger_element) {
        var row = $('#support_tickets_table').DataTable().row(trigger_element.parents('tr')).data();

        app.new_tab(row.id, row.subject, function (tab) {
            app.ajax('support_view_ticket', {id: row.id}, function (response) {
                if (response.success) {
                    tab.html(response.data);

                    app.refresh_tickets();

                    app.ajax('support_ticket_comments', {id: row.id}, function (response) {
                        if (response.success) {
                            tab.find('.support_ticket').parent().append(response.data);
                            app.tinymce('[name="content"]');
                        }
                    });
                }
            });
        });
    },

    app.post_ticket_edit = function(response, form) {
        form.parent().html('<p class="message">' + form.data('message') + '</p>');

        $('#' + response.id).find('.support_ticket').replaceWith(response.data);

        app.refresh_tickets();
    },

    app.post_ticket_create = function (response, form) {
        form.parent().html('<p class="message">' + form.data('message') + '</p>');
        app.refresh_tickets();
    },

    app.post_comment_submit = function (response, form) {
        tinyMCE.activeEditor.setContent('');

        if(response.ticket_updated) {
            $('.ticket[data-id="' + response.ticket_id + '"]').parent().replaceWith(response.ticket);
            app.refresh_tickets();
        }

        form.parents('.comment_section').find('.comments').append(response.comment);
    },

    app.post_comment_update = function (response, form) {
        form.parents('.comment').replaceWith(response.data);
    },

    app.post_user_register = function () {
        location.reload();
    },

    app.edit_comment = function (trigger_element) {
        var comment = trigger_element.parents('.comment');
        var content =  comment.find('.content');

        content.hide();
        comment.find('.editor').show();
        app.tinymce('textarea[name="content"]');
    },

    app.cancel_comment_edit = function (trigger_element) {
        trigger_element.parents('form').find('.error_msg').remove();
        var comment = trigger_element.parents('.comment');
        var content = comment.find('.content');

        tinyMCE.activeEditor.remove();
        comment.find('.content').show();
        comment.find('.editor').hide();
    },

    app.refresh_comment = function (context, data) {
        context.first().replaceWith(data);
    },

    app.delete_comment = function (trigger_element) {
        var comment = trigger_element.parents('.comment');

        app.ajax('support_delete_comment', {comment_id: comment.data('id')}, function (response) {
            if (response.success) {
                comment.remove();
            }
        });
    },

    app.remove_filter = function() {
        app.set_session_obj('filter_active', true);

        $('#ticket_filter .form_field').each(function () {
            $(this).val('');
        });

        app.refresh_tickets();
    },

    app.filter_tickets = function () {
        if(app.get_session_obj('filter_active', false)) {
            app.remove_filter();
        } else {
            app.set_session_obj('filter_active', true);
            app.refresh_tickets();
        }
    },

    app.refresh_tickets = function () {
        if( $('#support_tickets_table').length > 0 ) {
            var data = $('#ticket_filter').serializeArray();

            $('#ticket_filter').find('.refresh').addClass('rotate');

            //Get the data from the last filter
            app.ajax('support_list_tickets', data, function (response) {
                $('#ticket_filter').find('.refresh').removeClass('rotate');
                $('#tickets_overview').replaceWith(response);

                app.init_table();
            });
        } else {
            app.ajax('support_list_tickets', null, function (response) {
                $('#tickets_overview').replaceWith(response);

                app.init_table();
            });
        }
    },

    app.resize = function () {
        $('#support_system .tabs').find('.ui-tabs-nav li').each(function (index) {
            $(this).width(window.innerWidth / 10);
        });
    },

    app.init_table = function () {
        var cols = [];

        $('#support_tickets_table th').each(function () {
            cols.push({ data: $(this).data('column_name') });
        });

        $('#support_tickets_table').DataTable({
//             responsive: true,
            columns: cols
        });
    },

    app.toggle_register_form = function () {
        $('#login_form').toggle();
        $('#register_form').toggle();
    },

    app.get_session_obj = function (key, default_value) {
        var data = default_value;

        try{
            data = JSON.parse(window.sessionStorage[ key ]);
        } catch (ex) {

        }

        return data;
    },

    app.set_session_obj = function (key, value) {
        try {
            window.sessionStorage[ key ] = JSON.stringify(value);
        } catch (ex) {
            window.sessionStorage[ key ] = [];
        }
    }

})(jQuery, SupportSystem);
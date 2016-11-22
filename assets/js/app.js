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

    app.submit_form = function (e) {
            e.preventDefault();

            var delay = 1000;
            var form = $(this);

            app.ajax(form.data('action'), form.serializeArray(), function (response) {
                console.log(response);

                form.find('.error_field').removeClass('error_field');
                form.find('.error_msg').remove();

                form.hide();
                form.parent().append('<div class="spinner"></div>');

                setTimeout(function () {
                    form.parent().find('.spinner').remove();

                    if (response.success) {
                        form.parent().append(response.data);
                        form.remove();

                       app.refresh_tickets();
                    } else {
                        form.show();

                        // Match fields to error messages
                        $.each(response.data, function (key, value) {
                            var field = $(form).find('[name="' + key + '"]');
                            field.addClass('error_field');
                            field.parent().append('<span class="error_msg">' + value + '</span>');

                        });
                    }
                }, delay);
            });

            return;
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
        },

    app.view_ticket = function (element) {
        var row = $('#support_tickets_table').DataTable().row(element.parents('tr')).data();

        app.new_tab(row.id, row.subject, function (element) {
            app.ajax('support_view_ticket', {id: row.id}, function (response) {
                if (response.success) {
                    element.html(response.data);

                    app.ajax('support_ticket_comments', {id: row.id}, function (response) {
                        if (response.success) {
                            console.log(response);
                            element.find('.support_ticket').append(response.data);
                            app.tinymce('[name="content"]');
                        }

                    });
                }
            });
        });
    },

    app.edit_ticket = function (context) {
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



    app.remove_filter = function() {
        set_session_obj('tickets_filter', []);

        $('#ticket_filter .form_field').each(function () {
            $(this).val('');
        });

        app.refresh_tickets();
    },

    app.filter_tickets = function (el) {
        if(el.data('enabled')) {
            el.data('enabled', false);
            el.css('color', 'black');

            app.remove_filter();
        } else {
            el.data('enabled', true);
            el.css('color', 'blue');

            // Save this fore future refreshes
            set_session_obj('tickets_filter', $('#ticket_filter').serializeArray());
            app.refresh_tickets();
        }
    },

    app.refresh_tickets = function () {
        if( $('#support_tickets_table').length > 0 ) {
            var data = get_session_obj('tickets_filter', []);

            $('#ticket_filter').find('.refresh').addClass('rotate');

            //Get the data from the last filter
            app.ajax('support_refresh_tickets', data, function (response) {
                if (response.success) {
                    $('#ticket_filter').find('.refresh').removeClass('rotate');
                    $('#support_tickets_table_wrapper').replaceWith(response.data);

                    init_table();
                }
            });
        } else {
            app.ajax('support_list_tickets', null, function (response) {
                $('#tickets_overview').replaceWith(response);

                init_table();
            });
        }
    }

    function init_table () {
        var cols = [];

        $('#support_tickets_table th').each(function () {
            cols.push({ data: $(this).data('column_name') });
        });

        $('#support_tickets_table').DataTable({
            responsive: true,
            columns: cols
        });
    }

    function get_session_obj(key, default_value) {
        var data = default_value;

        try{
            data = JSON.parse(window.sessionStorage[ key ]);
        } catch (ex) {

        }

        return data;
    }

    function set_session_obj(key, value) {
        try {
            window.sessionStorage[ key ] = JSON.stringify(value);
        } catch (ex) {
            window.sessionStorage[ key ] = [];
        }
    }

})(jQuery, SupportSystem);
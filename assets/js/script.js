jQuery(document).ready(function ($) {

    // Bind events
    $(document).on('dblclick', 'tr', function () {
        SupportSystem.view_ticket($('#support_tickets_table').DataTable().row(this).data());
    });

    $(document).on('click', '.status_bar .action', function (e) {
        e.preventDefault();
        SupportSystem[$(this).data('action')]($(this).parents('.root').data('ajax_action', $(this).data('ajax_action')));
    });

    $(document).on('click', '.button.cancel', function (e) {
        e.preventDefault();
        SupportSystem.cancel_editor($(this).parents('.root'));
    });

    $(document).on('submit', '#support_system form', SupportSystem.submit_form);


    // initalize table
    SupportSystem.ajax('support_list_tickets', null, function (response) {

        $('#support_ticket_tab_view').Tabular({
            noClose: 'ticket_list'
        });

        $('#support_ticket_tab_view').Tabular('newTab', 'ticket_list', 'Tickets', response.html);

        $('#support_tickets_table').DataTable(
            {
                select: 'single',
                columns: response.columns,
                columnDefs: [{targets: 0, visible: false}]
            }
        );

    });

});

jQuery(document).ready(function ($) {

    // Bind events
    $(document).on('dblclick', 'tr', function () {
        SupportSystem.view_ticket($('#support_tickets_table').DataTable().row(this).data());
    });

    $(document).on('click', '.status_bar .action', function () {
        SupportSystem[$(this).data('action')]($(this).parents('.root'));
    });

    $(document).on('submit', '#support_system form', SupportSystem.submit_form);


    // initalize table
    SupportSystem.ajax('list_support_tickets', null, function (response) {

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

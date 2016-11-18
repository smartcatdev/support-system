jQuery(document).ready(function ($) {

    // Bind events
    $(document).on('submit', '.support_form', SupportSystem.submit_form);
    $(document).on('submit', '#ticket_filter', SupportSystem.filter_table);






    $(document).on('click', '#support_system button.trigger', function() {
        SupportSystem[$(this).data('action')]($(this));
    });

    $(document).on('click', 'tr', function () {
console.log($('#support_tickets_table').DataTable().row(this).data());
        SupportSystem.view_ticket(
            {
                id: $('#support_tickets_table').DataTable().row(this).data()['id'],
                subject: $('#support_tickets_table').DataTable().row(this).data()['subject']
            }
        );
    });

    $(document).on('click', '.status_bar .action', function (e) {
        e.preventDefault();
        SupportSystem[$(this).data('action')](
            $(this).parents('.support_card').first()
        );
    });

    $(document).on('click', '.button.cancel', function (e) {
        e.preventDefault();
        SupportSystem.cancel_editor($(this).parents('.support_card').first());
    });

    var tabs = $('#support_system .tabs').tabs({
        beforeLoad: function( event, ui ) {
            if ( ui.tab.data( 'loaded' ) ) {
                event.preventDefault();

                if(ui.tab.index() == 0) {
                    SupportSystem.refresh_table();
                }

                return;
            }

            ui.jqXHR.success(function() {
                ui.tab.data( 'loaded', true );
            });
        },

        load: SupportSystem.refresh_table

    });

    tabs.on('click', '.ui-icon-close', function () {
        var tab = $( this ).closest( 'li' ).remove().attr( 'aria-controls' );
        $( '#' + tab ).remove();
        tabs.tabs( 'refresh' );
    });



});

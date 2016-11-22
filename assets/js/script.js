jQuery(document).ready(function ($) {

    // Bind events
    $(document).on('submit', '.support_form', SupportSystem.submit_form);

    $(document).on('click', '.trigger', function (e) {
        e.preventDefault();

        var element = $(this);
        SupportSystem[element.data('action')] (element);

        return;
    } );

    $(document).on('change', '#ticket_filter .form_field', function (e) {
        $('#ticket_filter').find('.filter').data('enabled', false).css('color', 'black');
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

                if(ui.tab.index() != 0) {
                    SupportSystem.refresh_tickets();
                }

                return;
            }

            ui.jqXHR.success(function() {
                ui.tab.data( 'loaded', true );
            });
        },

        load: SupportSystem.refresh_tickets

    });

    tabs.on('click', '.ui-icon-close', function () {
        var tab = $( this ).closest( 'li' ).remove().attr( 'aria-controls' );
        $( '#' + tab ).remove();
        tabs.tabs( 'refresh' );
    });



});

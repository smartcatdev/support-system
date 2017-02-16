jQuery(document).ready(function ($) {

    // Bind events
    $(document).on('submit', '.support_form', SupportSystem.submit_form);
    $(document).on('change', '.meta_form', SupportSystem.submit_form);
    $(window).resize(SupportSystem.resize);

    $(document).on('click', '.trigger', function (e) {
        e.preventDefault();

        SupportSystem[$(this).data('action')] ($(this));

        return;
    } );

    $(document).on('change', '#ticket_filter .form_field', function (e) {
        SupportSystem.set_session_obj('filter_active', false);
    });


    // var tabs = $('#support_system .tabs').tabs({
    //     beforeLoad: function( event, ui ) {
    //         if ( ui.tab.data( 'loaded' ) ) {
    //             event.preventDefault();
    //             return;
    //         }
    //     },
    //
    //     load: function (even, ui) {
    //         ui.tab.data( 'loaded', true );
    //         SupportSystem.init_table();
    //     },
    //
    //     create: function(event, ui) {
    //         ui.tab.width(window.innerWidth / 10);
    //     },
    //
    //     activate: function(event, ui) {
    //         ui.newTab.width(window.innerWidth / 10);
    //     }
    //
    // });
    //
    // tabs.on('click', '.ui-icon-close', function () {
    //     var tab = $( this ).closest( 'li' ).remove().attr( 'aria-controls' );
    //     $( '#' + tab ).remove();
    //     tabs.tabs( 'refresh' );
    // });
    
    
    
});



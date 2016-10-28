jQuery( document ).ready( function( $ ) {



    var TicketEvents = {

        initialize: function() {

            $( document ).on( 'submit', '#select_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#new_ticket',  TicketActions.doAjax );
            $( document ).on( 'submit', '#edit_ticket_form', TicketActions.doAjax );

            $( document ).on( 'submit', '#list_tickets', TicketActions.ticketTable );
        }

    }

    var TicketActions = {

        doAjax: function( e ) {
            e.preventDefault();

            var data = $( this ).serializeArray();
            data.push( {
                name: 'action',
                value: $( this ).attr( 'data-action' )
            } );


            $.post( SmartcatSupport.ajaxURL, $.param( data ), function( response ) {

                $('.entry-content').html( response.html );

            } );
        }

    }

    $( function () {
        TicketEvents.initialize();
    });

} );





jQuery( document ).ready( function ( $ ) {

    $( '#support_ticket_tab_view' ).Tabular();

//     $( '#list_card' ).show();
//     $( '#ticket_card' ).hide();
//
    $.post( SmartcatSupport.ajaxURL, { action: 'list_support_tickets' }, function( response ) {

        //$('#list_card').html( response.html );

        $( '#support_ticket_tab_view' ).Tabular( 'newTab', 'ticket_list', 'Tickets', response.html );

        var table = $( '#support_tickets_table' ).DataTable(
            {
                select: 'single',
                columnDefs: [
                    {
                        targets: 0,
                        visible: false
                    }
                ]
            }
        );


        $( document ).on( 'dblclick', 'tr', function () {

            var ticket_id = table.row( this ).data()[0];
            var ticket_title = table.row( this ).data()[2];

            $.post( SmartcatSupport.ajaxURL,
                {
                    action: 'edit_support_ticket',
                    ticket_id: table.row( this ).data()[0]
                },

                function( response ) {
                    $( '#support_ticket_tab_view' ).Tabular( 'newTab', ticket_id, ticket_title, response.html );
                }

            );

        } );



    } );


//
//             //$( '.support_card' ).hide();
//
//             $.post( SmartcatSupport.ajaxURL,
//                 {
//                     action: 'edit_support_ticket',
//                     ticket_id: table.row( this ).data()[0]
//                 }
// //
// //                 function( response ) {
// //                     //$( '#ticket_card' ).html( response.html );
// //                     //$( '#ticket_card' ).show();
// // console.log(table.row( this ).data()[0] );
// //                     //new_tab( table.row( this ).data()[0], table.row( this ).data()[2], response.html );
// //                 } );
// //         } )
// //
//     } );
//
//     $( '#tab_bar' ).on( 'click', '.tab', function () {
//         $( '.support_card' ).hide();
//         $( '#' + $( this ).data( 'card' ) ).show();
//     } );
//
//
//     function new_tab( id, title, html ) {
//
//         $( '#tab_bar ul' ).append(
//             '<li class="tab" data-card="card_' + id + '">' + title + '</li>'
//         );
//
//         $( '#support_ticket_cards' ).append(
//             '<div class="support_card" id="card_' + id + '">' + html + '</div>'
//         );
//
//         $( '#card_' + id ).show();
//
//     }

} );

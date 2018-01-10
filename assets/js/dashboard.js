/**
 * @summary Module for handling the dashboard UI.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";

    /**
     * @since 1.6.0
     */
    const module = {

        /**
         * @summary Initialize events and handlers.
         *
         * @since 1.6.0
         * @return void
         */
        init: function () {

            /**
             * @summary Adjust UI state when the store changes.
             */
            ucare.stores.toolbar.on('change', function (store) {
                const state = store.getState(),
                      $list = $('#the-tickets');

                if (state['bulk_action']) {
                    $list.addClass('has-bulk-action no-replace');

                // Uncheck all bulk selectors
                } else {
                    $list.removeClass('has-bulk-action no-replace');
                    $list.find('[name="bulk_item_selected"]')
                         .each(function (i, el) {

                             if (el.checked) {
                                 $(el).prop('checked', false).trigger('change');
                             }
                    });
                }
            });

            /**
             * @summary Remove deleted tickets from the list.
             */
            ucare.stores.tickets.on('delete', function (id) {
                const $list = $('#the-tickets');

                // Reload the ticket list if we are at the end of the list
                if ($list.find('.ticket').length === 1) {
                    $list.removeClass('no-replace');
                    App.load_tickets();

                    // Unset the bulk action
                    ucare.Actions.setToolbarToggle('bulk_action', '');

                // Remove a single item from the list
                } else {
                    $('#ticket-' + id).slideUp(function () {
                        $(this).remove();
                    });
                }
            });

            /**
             * @summary Toggle the toolbar ribbon display when the bulk selection changes.
             */
            ucare.stores.tickets.on('change', function (store) {
                const state   = store.getState(),
                      toolbar = ucare.stores.toolbar;

                if (state.selected.length > 0 && toolbar.getState().bulk_action === 'delete') {
                    const $apply = $('<button>', {
                        id: 'apply-bulk-delete',
                        class: 'btn btn-default',
                        text: ucare.l10n.strings.delete_selection.concat(' (', state.selected.length, ')')
                    })
                    .click(function () {
                        swal(ucare.l10n.strings.are_you_sure, {
                            icon: 'warning',
                            dangerMode: true,
                            buttons: [
                                ucare.l10n.strings.no,
                                ucare.l10n.strings.yes
                            ]
                        })
                        .then(function (confirm) {
                            if (confirm) {
                                module.bulkDeleteTickets();
                            }
                        });
                    });

                    const $ribbon = $('<div>', {
                        id: 'toolbar-ribbon',
                        class: 'container-fluid'
                    })
                    .append($('<div>', {
                        class: 'row',
                        html: $apply
                    }));

                    if ($('#toolbar-ribbon').length < 1) {
                        $('#the-toolbar').after($ribbon.hide());
                        $ribbon.slideDown();

                    } else {
                        $('#apply-bulk-delete').replaceWith($apply);
                    }

                } else {
                    $('#toolbar-ribbon').slideUp(function (){
                        $(this).remove();
                    });
                }

            });

            /**
             * @summary Toggle selected ticket state
             */
            $(document).on('change', '[name="bulk_item_selected"]', function () {
                module.toggleItemSelected($(this).prop('value'), this.checked);
            });

        },

        /**
         * @summary Toggle selected ticket.
         *
         * @param {int}  id
         * @param {bool} selected
         *
         * @since 1.6.0
         * @return {void}
         */
        toggleItemSelected: function (id, selected) {
            if (selected) {
                ucare.Actions.selectTicket(id);

            } else {
                ucare.Actions.deselectTicket(id);
            }
        },

        /**
         * @summary Delete all currently selected tickets.
         *
         * @since 1.6.0
         * @return void
         */
        bulkDeleteTickets: function () {
            ucare.stores.tickets.getState().selected.forEach(function (selected) {
                ucare.Actions.deleteTicket(selected);
            });
        },

    };


    // Initialize the module
    $(module.init);

})(jQuery, ucare);
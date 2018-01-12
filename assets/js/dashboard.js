/**
 * @summary Module for handling the dashboard UI.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";

    const store = ucare.store,

    /**
     * @since 1.6.0
     */
    module = {

        /**
         * @summary Initialize events and handlers.
         *
         * @since 1.6.0
         * @return void
         */
        init: function () {

            const $toolbar = $('#the-toolbar');

            /**
             * @summary Adjust UI state when the store changes.
             */
            store.subscribe(function () {
                const state = store.getState(),
                      $list = $('#the-tickets');

                if (state.toolbar.toggles['bulk_action_active']) {
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
            ucare.events.on('ticket_deleted', function (id) {
                const $list = $('#the-tickets');

                // Reload the ticket list if we are at the end of the list
                if ($list.find('.ticket').length === 1) {
                    $list.removeClass('no-replace');
                    App.load_tickets();

                    // Unset the bulk action
                    ucare.Actions.setToolbarToggle('bulk_action_active', false);

                // Remove a single item from the list
                } else {
                    $('#ticket-' + id).slideUp('fast', function () {
                        $(this).remove();
                    });
                }

                // TODO remove this when single ticket view is added
                $('#' + id +', [data-id="' + id + '"]').remove();
            });


            /**
             * @summary Update the ticket count for the bulk action apply button
             */
            store.subscribe(function () {
                const state = store.getState();

                // Only if the ribbon is visible
                if ($toolbar.find('#toolbar-ribbon').is(':visible')) {
                    const $apply = $toolbar.find('#apply-bulk-action');

                    // Store the original text
                    if (!$apply.data('text')) {
                        $apply.data('text', $apply.text());
                    }

                    const selected = state.tickets.selected,
                          count    = selected ? selected.length: 0;

                    var text = $apply.data('text');

                    if (count > 0) {
                        text = text.concat(' (', count, ')');
                    }

                    $apply.text(text).prop('disabled', !count > 0);
                }

            });


            /**
             * @summary Apply the selected bulk action to the ticket selection.
             */
            $('#apply-bulk-action').click(function () {
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
                        switch ($('#selected-bulk-action').val()) {
                            case 'delete':
                                module.bulkDeleteTickets();
                                break;
                        }
                    }
                });
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
            store.getState().tickets.selected.forEach(function (selected) {
                ucare.Actions.deleteTicket(selected);
            });
        },

    };


    // Initialize the module
    $(module.init);

})(jQuery, ucare);
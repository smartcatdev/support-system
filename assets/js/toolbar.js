/**
 * @summary Module for managing the application UI toolbar.
 *
 * @since 1.6.0
 */
;var Toolbar = (function($, ucare) {
    "use strict";

    $(function () {

        const $toolbar = $('#the-toolbar');

        /**
         * @summary Treat toolbar-toggle links like labels
         */
        $(document).on('click', 'a.toolbar-item-toggle', function (e) {
            const $toggle = $(this).find('input[type="checkbox"]'),
                  checked = !$toggle.prop('checked');

            // Manually toggle the hidden checkbox
            $toggle.prop('checked', checked).trigger('change');
            e.preventDefault();
        });


        /**
         * @summary Update the checked value in the store when the checkbox is changed
         */
        $(document).on('change', 'a.toolbar-item-toggle > input[type="checkbox"]', function () {
            ucare.Actions.setToolbarToggle($(this).attr('name'), this.checked);
        });


        /**
         * @summary Adjust UI state when the toolbar changes.
         */
        ucare.store.subscribe(function () {
            const state = ucare.store.getState();

            $('a.toolbar-item-toggle > input[type="checkbox"]')
                .each(function (i, el) {
                    const checked = !!state.toolbar.toggles[$(el).attr('name')];

                    $(el).prop('checked', checked);
                    $(el).parent()
                         .toggleClass('has-item-checked', checked);
            });

            // TODO make ribbon support multiple actions
            if (state.toolbar.toggles['bulk_action_active']) {
                $toolbar.find('#toolbar-ribbon').slideDown('fast');

            } else {
                $toolbar.find('#toolbar-ribbon').slideUp('fast');
            }
        });

    });

})(jQuery, ucare);

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
        $('a.toolbar-item-toggle').click(function () {
            const $toggle = $(this).find('input[type="checkbox"]'),
                  checked = !$toggle.prop('checked');

            // Manually toggle the hidden checkbox
            $toggle.prop('checked', checked);

            // Update the checked value in the store
            ucare.Actions.setToolbarToggle($toggle.attr('name'), checked ? $toggle.prop('value') : false);

            return false;
        });


        /**
         * @summary Adjust UI state when the toolbar changes.
         */
        ucare.store.subscribe(function () {
            const state = ucare.store.getState();

            $('a.toolbar-item-toggle')
                .find('input[type="checkbox"]')
                .each(function (i, el) {
                    const checked = !!state.toolbar[$(el).attr('name')];

                    $(el).prop('checked', checked)
                         .parents('a.toolbar-item-toggle')
                         .toggleClass('has-item-checked', checked);
            });

            // TODO make ribbon support multiple actions
            if (state.toolbar.bulk_action_active) {
                $toolbar.find('#toolbar-ribbon').slideDown('fast');

            } else {
                $toolbar.find('#toolbar-ribbon').slideUp('fast');
            }
        });

    });

})(jQuery, ucare);

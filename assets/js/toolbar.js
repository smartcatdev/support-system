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
                  checked = !$toggle.prop('checked'),
                  name    = $toggle.attr('name'),
                  value   = $toggle.prop('value');

            $('[name="' + $toggle.attr('name') + '"]').each(function (i, el) {
                $(el).prop('checked', false);
                $(el).parent().removeClass('has-item-checked');
            });

            $toggle.prop('checked', checked);
            $(this).toggleClass('has-item-checked', checked);

            ucare.Actions.setToolbarToggle(name, checked ? value : false);
            return false;
        });


        /**
         * @summary Adjust UI state when the toolbar changes.
         */
        ucare.stores.toolbar.on('change', function (store) {
            if (store.getState().bulk_action_active) {
                $toolbar.find('#toolbar-ribbon').slideDown();

            } else {
                $toolbar.find('#toolbar-ribbon').slideUp();
            }
        });

    });

})(jQuery, ucare);

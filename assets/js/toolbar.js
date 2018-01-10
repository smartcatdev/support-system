/**
 * @summary Module for managing the application UI toolbar.
 *
 * @since 1.6.0
 */
;var Toolbar = (function($, ucare) {
    "use strict";

    $(function () {

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

    });

})(jQuery, ucare);

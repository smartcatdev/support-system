/**
 * Module for general purpose UI scripting.
 *
 * @since 1.6.0
 */
;(function ($) {
    "use strict";

    $(function () {

        /**
         * @summary Go to the last page when back button is clicked.
         */
        $('.btn-back').click(function (e) {
            e.preventDefault();
            history.back();
        });

    });

})(jQuery);
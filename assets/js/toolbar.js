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

            ucare.Actions.setToolbarToggle(name, checked ? value : '');
            return false;
        });


        /**
         * @summary Toggle the toolbar ribbon display when the bulk selection changes.
         */
        ucare.stores.tickets.on('change', function (store) {
            const state   = store.getState(),
                  toolbar = ucare.stores.toolbar;

            if (state.selected.length > 0 && toolbar.getState().bulk_action === 'delete') {
                const $ribbon = $(
                    '<div id="toolbar-ribbon" class="container-fluid">\
                        <div class="row">\
                            <button class="btn btn-default">'
                             + ucare.l10n.strings.delete_selection + ' (' + state.selected.length + ')' +
                           '</button>\
                        </div>\
                    </div>'
                );

                if ($('#toolbar-ribbon').length < 1) {
                    $('#the-toolbar').after($ribbon.hide());
                    $ribbon.slideDown();

                } else {
                    $('#toolbar-ribbon').replaceWith($ribbon);
                }

            } else {
                $('#toolbar-ribbon').slideUp(function (){
                    $(this).remove();
                });
            }

        });

    });

})(jQuery, ucare);

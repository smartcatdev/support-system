/**
 * Module for manging the edit profile page.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";


    const $form = $('#edit-user-profile-form'),

          /**
           * @summary Module for managing the UI for the profile edit form.
           */
          module = {

              /**
               * @summary Holds the state of the current save operation.
               */
              saving_in_progress: false,

              /**
               * @summary Initialize DOM and event handlers.
               *
               * @since 1.6.0
               * @return {void}
               */
              init: function () {

                  $('#submit').click(function () {
                      module.clear_errors();
                      module.save();
                  });

                  $('.pw-input').on('keyup paste', function () {
                     module.check_password();
                  });

              },

              /**
               * @summary Save the users settings.
               *
               * @since 1.6.0
               */
              save: function () {

                  if (!module.saving_in_progress) {
                      module.saving_in_progress = true;

                      ucare.user.update({})
                      .fail(function (res) {

                      })
                      .done(function () {
                         module.saving_in_progress = false;
                      });

                  }

              },

              /**
               * @summary Disable the form save functionality.
               *
               * @since 1.6.0
               */
              disable: function () {

              },

              /**
               * @summary Revert the edit profile form back to its original state.
               *
               * @since 1.6.0
               */
              revert: function () {

              },

              /**
               * @summary Check the user's password as they type.
               *
               * @since 1.6.0
               */
              check_password: function () {

              },

              /**
               * @summary Clear all error messages.
               *
               * @since 1.6.0
               */
              clear_errors: function () {

              },

              /**
               * @summary Create an error message.
               *
               * @since 1.6.0
               */
              error: function () {

              }

          };


        // Initialize module
        $(module.init);

})(jQuery, ucare);
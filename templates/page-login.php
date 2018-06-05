<?php
/**
 * Template for the login page.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

?>

<?php ucare_get_header(); ?>

<style>
    .toggle-pw {
        cursor: pointer;
    }
</style>
<script>
    jQuery(document).ready(function ($) {
        /**
         * Toggle password visibility
         */
        $('.toggle-pw').click(function () {
            var $pw  = $(this).siblings('input'),
                type = $pw.prop('type') === 'text' ? 'password' : 'text';

            var isHidden = type === 'password';

            $pw.prop('type', type);
            $(this).find('.pw-hidden').toggle(isHidden);
            $(this).find('.pw-visible').toggle(!isHidden);
        });

        /**
         * Ensure passwords match when typing
         */
        $('#confirm-pw').on('keyup paste change', function () {
            var $feedback = $('#confirm-pw-feedback'),
                $submit   = $('#submit'),
                $this     = $(this);

            $submit.prop('disabled', true);

            $feedback
                .removeClass('glyphicon-exclamation-sign')
                .removeClass('glyphicon-ok')
                .removeClass('glyphicon-remove');

            if (!$this.val().length) {
                $feedback.addClass('glyphicon-exclamation-sign');
            } else if ($this.val() === $('#pw').val()) {
                $feedback.addClass('glyphicon-ok');
                $submit.prop('disabled', false);
            } else {
                $feedback.addClass('glyphicon-remove');
            }
        });
    });
</script>

<div id="support-login-bg" xmlns="http://www.w3.org/1999/html">

    <div id="support-login-page">

        <div id="support-login-wrapper">

            <?php if ( !empty( $_GET['password_reset_sent'] ) ) : ?>

                <div class="login-card">

                    <h2 class="login-title">
                        <?php _e( 'Password Reset', 'ucare' ); ?>
                    </h2>
                    <p><?php _e( 'Please check your email to reset your password', 'ucare' ); ?></p>

                </div>

            <?php elseif ( !empty( $_GET['reset_password'] ) ) : ?>

                <div class="login-card">

                    <?php if ( check_pw_reset_token( pluck( $_GET, 'token' ) ) ) : ?>

                        <h2 class="login-title">
                            <?php _e( 'Create a new password', 'ucare' ); ?>
                        </h2>
                        <div class="form-group"><?php _e( 'Please enter and confirm your new password', 'ucare' ); ?></div>

                        <form method="post">

                            <div class="form-group">

                                <div class="input-group">
                                    <input id="pw"
                                           type="password"
                                           class="form-control"
                                           placeholder="<?php _e( 'Password', 'ucare' ); ?>"
                                        />
                                    <span class="input-group-addon toggle-pw">
                                        <span class="glyphicon pw-hidden  glyphicon-eye-close"></span>
                                        <span class="glyphicon pw-visible glyphicon-eye-open" hidden></span>
                                    </span>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="input-group">
                                    <input id="confirm-pw"
                                           type="password"
                                           class="form-control"
                                           placeholder="<?php _e( 'Password', 'ucare' ); ?>"
                                        />
                                    <span class="input-group-addon toggle-pw">
                                        <span id="confirm-pw-feedback" class="glyphicon pw-hidden glyphicon-exclamation-sign"></span>
                                        <span class="glyphicon pw-visible glyphicon-eye-open" hidden></span>
                                    </span>
                                </div>

                            </div>

                            <div class="text-right">
                                <button id="submit" class="button" disabled><?php _e( 'Update', 'ucare' ); ?></button>
                            </div>

                        </form>

                    <?php else : ?>

                        <h2 class="login-title">
                            <?php _e( 'Oops...', 'ucare' ); ?>
                        </h2>
                        <p><?php _e( 'We are unable to verify your password reset request', 'ucare' ); ?></p>

                    <?php endif; ?>

                </div>

            <?php else : // Default to login form ?>

                <div class="login-card"><?php login_form(); ?></div>

            <?php endif; ?>

    </div>

</div>

<?php ucare_get_footer(); ?>

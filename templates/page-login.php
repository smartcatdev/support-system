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

                    <form>

                        <h2 class="login-title">
                            <?php _e( 'Create a new password', 'ucare' ); ?>
                        </h2>

                    </form>

                </div>

            <?php else : ?>
                <div class="login-card"><?php login_form(); ?></div>
            <?php endif; ?>
    </div>

</div>

<?php ucare_get_footer(); ?>

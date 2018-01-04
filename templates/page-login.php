<?php
/**
 * Template for the login page.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

$allow_registration = get_option( Options::ALLOW_SIGNUPS );

?>

<?php ucare_get_header(); ?>


<div id="support-login-bg" xmlns="http://www.w3.org/1999/html">

    <div id="support-login-page">

        <div id="support-login-wrapper">

            <div id="support-login-form" class="<?php echo $allow_registration ? 'has-registration' : ''; ?>">

                <?php if ( $allow_registration && get_var( 'register' ) ) : ?>

                    <?php get_template( 'login-register' ); ?>

                <?php elseif ( get_var( 'reset_password' ) ) : ?>

                    <a class="btn btn-default button-back" href="<?php esc_url_e( login_page_url() ); ?>">
                        <span class="glyphicon glyphicon-chevron-left button-icon"></span>
                        <span><?php _e( 'Login', 'ucare' ); ?></span>
                    </a>

                    <div id="message-area"></div>

                    <!-- reset-pw-form -->
                    <form id="reset-pw-form">

                        <div class="form-group">
                            <h4><?php _e( 'Reset Password', 'ucare' ); ?></h4>
                        </div>

                        <div class="form-group">

                            <input class="form-control"
                                   type="text"
                                   name="username"
                                   placeholder="<?php _e( 'Username or Email Address', 'ucare' ); ?>" />

                        </div>

                        <div class="bottom">

                            <input id="reset-password"
                                   type="submit"
                                   class="button button-primary"
                                   value="<?php _e( 'Reset', 'ucare' ); ?>" />

                        </div>

                    </form><!-- /reset-pw-form -->


                <?php else : ?>

                    <!-- login -->
                    <div id="login">

                        <img class="logo" src="<?php echo get_option( Options::LOGO ) ?>"/>

                        <?php if ( get_var( 'login' ) === 'empty' || get_var( 'login' ) === 'failed' ) : ?>

                            <div class="alert alert-error alert-dismissible fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php _e( 'Invalid username or password', 'ucare' ); ?>
                            </div>

                        <?php endif; ?>


                        <?php wp_login_form( array( 'form_id' => 'support_login', 'redirect' => support_page_url() ) ); ?>


                        <div class="clearfix"></div>

                        <div class="text-center pw-reset-link">

                            <a href="<?php echo esc_url( login_page_url( '?reset_password=true' ) ); ?>">
                                <?php _e( 'Forgot password?', 'ucare' ); ?>
                            </a>

                        </div>

                    </div><!-- /login -->


                    <?php $login_widget = get_option( Options::LOGIN_WIDGET_AREA ); ?>

                    <?php if ( !empty( $login_widget ) ) : ?>

                        <div id="login-widget-area" class="row"><?php echo stripslashes( $login_widget ); ?></div>

                    <?php endif; ?>


                <?php endif; ?>

            </div>

        </div>

    </div>

</div>


<?php ucare_get_footer(); ?>

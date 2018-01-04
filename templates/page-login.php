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


<?php $allow_registration = get_option( Options::ALLOW_SIGNUPS, Defaults::ALLOW_SIGNUPS ); ?>

<div id="support-login-bg" xmlns="http://www.w3.org/1999/html">

    <div id="support-login-page">

        <div id="support-login-wrapper">

            <div id="support-login-form" class="<?php echo $allow_registration ? 'has-registration' : ''; ?>">

                <?php if( !empty( $_REQUEST['reset_password'] ) ) : ?>

                    <a class="btn btn-default button-back" href="<?php echo esc_url( support_page_url() ); ?>">

                        <span class="glyphicon glyphicon-chevron-left button-icon"></span>

                        <span><?php _e( 'Back to login', 'ucare' ); ?></span>

                    </a>

                    <div id="reset-pw-alert"></div>

                    <form>

                        <div class="form-group">

                            <h4><?php _e( 'Reset Password', 'ucare' ); ?></h4>

                        </div>

                        <div class="form-group">

                            <input class="form-control" type="text" name="username" placeholder="<?php _e( 'Username or Email Address', 'ucare' ); ?>" />

                        </div>

                        <div class="bottom">

                            <input id="reset-password" type="submit" class="button button-primary" value="<?php _e( 'Reset', 'ucare' ); ?>" />

                        </div>

                        <?php wp_nonce_field( '_ajax_nonce' ); ?>

                    </form>

                <?php elseif ( get_option( Options::ALLOW_SIGNUPS, Defaults::ALLOW_SIGNUPS ) && !empty( $_REQUEST['register'] ) ) : ?>

                    <?php $form = include_once Plugin::plugin_dir( PLUGIN_ID ) . '/config/registration_form.php'; ?>

                    <div id="register">

                        <a id="login-back" class="btn btn-default button-back" href="<?php echo esc_url( support_page_url() ); ?>">

                            <span class="glyphicon glyphicon-chevron-left button-icon"></span>

                            <span><?php _e( 'Back to login', 'ucare' ); ?></span>

                        </a>

                        <form id="registration-form">

                            <?php foreach( $form->fields as $field ) : ?>

                                <div class="form-group">

                                    <label><?php echo $field->label; ?></label>

                                    <?php $field->render(); ?>

                                </div>

                            <?php endforeach; ?>

                            <input type="hidden" name="<?php echo $form->id; ?>" />

                            <?php do_action( 'ucare_after_registration_fields' ); ?>

                            <div class="text-right registration-submit">

                                <button id="registration-submit" type="submit" class="button button-primary">

                                    <?php _e( get_option( Options::REGISTER_BTN_TEXT, Defaults::REGISTER_BTN_TEXT ), 'ucare' ); ?>

                                </button>

                            </div>

                            <div class="terms">

                                <a href="<?php echo esc_url( get_option( Options::TERMS_URL, Defaults::TERMS_URL ) ); ?>">

                                    <?php _e( get_option( Options::LOGIN_DISCLAIMER, Defaults::LOGIN_DISCLAIMER ), PLUGIN_ID ); ?>

                                </a>

                            </div>

                        </form>

                    </div>

                <?php else : ?>

                    <div id="login">

                        <img class="logo" src="<?php echo get_option( Options::LOGO, Defaults::LOGO ) ?>"/>

                        <?php if( isset( $_REQUEST['login'] ) ) : ?>

                            <div class="alert alert-error alert-dismissible fade in">

                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                <?php _e( 'Invalid username or password', 'ucare' ); ?>

                            </div>

                        <?php endif; ?>

                        <?php wp_login_form( array( 'form_id' => 'support_login', 'redirect' => support_page_url() ) ); ?>

                        <div class="clearfix"></div>

                        <div class="text-center pw-reset-link">

                            <a href="<?php echo esc_url( support_page_url( '?reset_password=true' ) ); ?>"><?php _e( 'Forgot password?', 'ucare' ); ?></a>

                        </div>

                    </div>

                    <?php $login_widget = get_option( Options::LOGIN_WIDGET_AREA, Defaults::LOGIN_WIDGET_AREA ); ?>

                    <?php if( !empty( $login_widget ) ) : ?>

                        <div id="login-widget-area" class="row"><?php echo stripslashes( $login_widget ); ?></div>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>


<?php ucare_get_footer(); ?>

<?php

use ucare\Options;
use ucare\Plugin;

?>

<?php $signups = get_option( Options::ALLOW_SIGNUPS, \ucare\Defaults::ALLOW_SIGNUPS ); ?>

<div id="support-login-bg" xmlns="http://www.w3.org/1999/html">

    <div id="support-login-page">

        <div id="support-login-wrapper">

            <div id="support-login-form">

                <?php if( isset( $_REQUEST['reset_password'] ) ) : ?>

                    <a class="btn btn-default button-back" href="<?php echo \ucare\support_page_url(); ?>">

                        <span class="glyphicon glyphicon-chevron-left button-icon"></span>

                        <span><?php _e( 'Back', 'ucare' ); ?></span>

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

                <?php else : ?>

                <div id="login">

                    <img class="logo" src="<?php echo get_option( Options::LOGO, \ucare\Defaults::LOGO ) ?>"/>

                    <?php if( isset( $_REQUEST['login'] ) ) : ?>

                        <div class="alert alert-error alert-dismissible fade in">

                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                            <?php _e( 'Invalid username or password', 'ucare' ); ?>

                        </div>

                    <?php endif; ?>

                    <?php wp_login_form( array( 'redirect' => \ucare\support_page_url() ) ); ?>

                    <div class="clearfix"></div>

                    <div class="text-center">

                        <a href="<?php echo add_query_arg( 'reset_password', 'true', \ucare\support_page_url() ); ?>"><?php _e( 'Lost password?', 'ucare' ); ?></a>

                    </div>

                    <?php if( get_option( Options::ALLOW_SIGNUPS, \ucare\Defaults::ALLOW_SIGNUPS ) == 'on' ) : ?>

                        <button style="display: none" id="show-registration" type="button" class="button button-primary registration-toggle">

                            <?php echo get_option( Options::REGISTER_BTN_TEXT, \ucare\Defaults::REGISTER_BTN_TEXT ); ?>

                        </button>

                    <?php endif; ?>

                </div>

                    <?php if ( $signups ) : ?>

                        <?php $form = include_once Plugin::plugin_dir( \ucare\PLUGIN_ID ) . '/config/registration_form.php'; ?>

                        <div id="register" style="display: none">

                            <button id="login-back" class="btn btn-default registration-toggle button-back">

                                <span class="glyphicon glyphicon-chevron-left button-icon"></span><span><?php _e( 'Back', 'ucare' ); ?></span>

                            </button>

                            <form id="registration-form">

                                <?php foreach( $form->fields as $field ) : ?>

                                    <div class="form-group">

                                        <label><?php echo $field->label; ?></label>

                                        <?php $field->render(); ?>

                                    </div>

                                <?php endforeach; ?>

                                <input type="hidden" name="<?php echo $form->id; ?>" />

                                <?php do_action( 'support_after_registration_fields' ); ?>

                                <div class="terms">

                                    <a href="<?php echo esc_url( get_option( Options::TERMS_URL, \ucare\Defaults::TERMS_URL ) ); ?>">

                                        <?php _e( get_option( Options::LOGIN_DISCLAIMER, \ucare\Defaults::LOGIN_DISCLAIMER ), ucare\PLUGIN_ID ); ?>

                                    </a>

                                </div>

                                <div class="text-right registration-submit">

                                    <button id="registration-submit" type="submit" class="button button-primary">

                                        <?php _e( get_option( Options::REGISTER_BTN_TEXT, \ucare\Defaults::REGISTER_BTN_TEXT ), 'ucare' ); ?>

                                    </button>

                                </div>


                            </form>

                        </div>

                    <?php endif; ?>

                    <?php $login_widget = get_option( Options::LOGIN_WIDGET_AREA, \ucare\Defaults::LOGIN_WIDGET_AREA ); ?>

                    <?php if( !empty( $login_widget ) ) : ?>

                        <div id="login-widget-area" class="row"><?php echo stripslashes( $login_widget ); ?></div>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>

    </div>
    
</div>
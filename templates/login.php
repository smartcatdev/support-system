<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

?>

<?php $signups = get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ); ?>

<div id="support-login-bg" xmlns="http://www.w3.org/1999/html">

    <div id="support-login-page">

        <div id="support-login-wrapper">

            <div id="support-login-form">

                <?php if( isset( $_REQUEST['reset_password'] ) ) : ?>

                    <a class="btn btn-default button-back" href="<?php echo \SmartcatSupport\url(); ?>">

                        <span class="glyphicon glyphicon-chevron-left button-icon"></span>

                        <span><?php _e( 'Back', \SmartcatSupport\PLUGIN_ID ); ?></span>

                    </a>

                    <div id="reset-pw-alert"></div>

                    <form>

                        <div class="form-group">

                            <h4><?php _e( 'Reset Password', \SmartcatSupport\PLUGIN_ID ); ?></h4>

                        </div>

                        <div class="form-group">

                            <input class="form-control" type="text" name="username" placeholder="<?php _e( 'Username or Email Address', \SmartcatSupport\PLUGIN_ID ); ?>" />

                        </div>

                        <div class="bottom">

                            <input id="reset-password" type="submit" class="button button-primary" value="<?php _e( 'Reset', \SmartcatSupport\PLUGIN_ID ); ?>" />

                        </div>

                        <?php wp_nonce_field( '_ajax_nonce' ); ?>

                    </form>

                <?php else : ?>

                <div id="login">

                    <img class="logo" src="<?php echo get_option( Option::LOGO, Option\Defaults::LOGO ) ?>"/>

                    <?php if( isset( $_REQUEST['login'] ) ) : ?>

                        <div class="alert alert-error alert-dismissible fade in">

                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                            <?php _e( 'Invalid username or password', \SmartcatSupport\PLUGIN_ID ); ?>

                        </div>

                    <?php endif; ?>

                    <?php wp_login_form( array( 'redirect' => \SmartcatSupport\url() ) ); ?>

                    <div class="clearfix"></div>

                    <div class="text-center">

                        <a href="<?php echo add_query_arg( 'reset_password', 'true', \SmartcatSupport\url() ); ?>"><?php _e( 'Lost password?', \SmartcatSupport\PLUGIN_ID ); ?></a>

                    </div>

                    <?php if( get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ) == 'on' ) : ?>

                        <button style="display: none" id="show-registration" type="button" class="button button-primary registration-toggle">

                            <?php echo get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ); ?>

                        </button>

                    <?php endif; ?>

                </div>

                    <?php if ( $signups ) : ?>

                        <?php $form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/registration_form.php'; ?>

                        <div id="register" style="display: none">

                            <button id="login-back" class="btn btn-default registration-toggle button-back">

                                <span class="glyphicon glyphicon-chevron-left button-icon"></span><span><?php _e( 'Back', \SmartcatSupport\PLUGIN_ID ); ?></span>

                            </button>

                            <form id="registration-form">

                                <?php foreach( $form->fields as $field ) : ?>

                                    <div class="form-group">

                                        <label><?php echo $field->label; ?></label>

                                        <?php $field->render(); ?>

                                    </div>

                                <?php endforeach; ?>

                                <input type="hidden" name="<?php echo $form->id; ?>" />

                                <div class="terms">

                                    <a href="<?php echo esc_url( get_option( Option::TERMS_URL, Option\Defaults::TERMS_URL ) ); ?>">

                                        <?php _e( get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ), SmartcatSupport\PLUGIN_ID ); ?>

                                    </a>

                                </div>

                                <div class="text-right registration-submit">

                                    <button id="registration-submit" type="submit" class="button button-primary">

                                        <?php _e( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), \SmartcatSupport\PLUGIN_ID ); ?>

                                    </button>

                                </div>


                            </form>

                        </div>

                    <?php endif; ?>

                    <?php $login_widget = get_option( Option::LOGIN_WIDGET_AREA, Option\Defaults::LOGIN_WIDGET_AREA ); ?>

                    <?php if( !empty( $login_widget ) ) : ?>

                        <div id="login-widget-area" class="row"><?php echo stripslashes( $login_widget ); ?></div>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>

    </div>
    
</div>
<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

?>

<?php $signups = get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ); ?>

<div id="support-login-page">

    <div id="support-login-wrapper">
        
        <div id="support-login-form">

            <div id="login">

                <img class="logo" src="<?php echo get_option( Option::LOGO, Option\Defaults::LOGO ) ?>"/>

                <?php if( !empty( $_REQUEST['login'] ) ) : ?>

                    <div class="alert alert-danger fade in login-error-msg">

                        <?php _e( 'Invalid username or password', \SmartcatSupport\PLUGIN_ID ); ?>

                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                    </div>

                <?php endif; ?>

                <?php wp_login_form( array( 'redirect' => \SmartcatSupport\url() ) ); ?>

                <?php if( get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ) == 'on' ) : ?>

                    <button style="display: none" id="show-registration" type="button" class="button button-primary registration-toggle">

                        <?php echo get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ); ?>

                    </button>

                <?php endif; ?>

            </div>

            <?php if ( $signups ) : ?>

                <?php $form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/registration_form.php'; ?>

                <div id="register" style="display: none">

                    <button id="login-back" class="btn btn-default registration-toggle">

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

        </div>

        <div id="login-widget-area" class="row"><?php echo get_option( Option::LOGIN_WIDGET_AREA, Option::LOGIN_WIDGET_AREA ); ?></div>
        
    </div>

</div>
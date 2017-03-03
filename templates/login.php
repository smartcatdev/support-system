<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

?>

<?php $signups = get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ); ?>

<div id="support-login-page">

    <div id="support-login-wrapper">
        
        <div id="support-login-form">
            
            <img class="logo" src="<?php echo get_option( Option::LOGIN_LOGO, Option\Defaults::LOGIN_LOGO ) ?>"/>
            
            <div id="login">

                <?php wp_login_form(); ?>

                <?php if( get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ) == 'on' ) : ?>

                    <button style="display: none" id="show-registration" type="button" class="button button-primary registration-toggle">

                        <?php echo get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ); ?>

                    </button>

                <?php endif; ?>

            </div>

            <?php if ( $signups ) : ?>

                <?php $form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/registration_form.php'; ?>

                <div id="register" style="display: none">

                    <form id="registration-form">

                        <?php foreach( $form->fields as $field ) : ?>

                            <div class="form-group">

                                <label><?php echo $field->label; ?></label>

                                <?php $field->render(); ?>

                            </div>

                        <?php endforeach; ?>

                        <input type="hidden" name="<?php echo $form->id; ?>" />

                        <div class="text-center registration-submit">

                            <button type="button" class="button button-primary registration-toggle">

                                <?php _e( get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ), \SmartcatSupport\PLUGIN_ID ); ?>

                            </button>

                            <button id="registration-submit" type="submit" class="button button-primary">

                                <?php _e( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), \SmartcatSupport\PLUGIN_ID ); ?>

                            </button>

                        </div>

                    </form>

                </div>

            <?php endif; ?>

        </div>
        
    </div>

</div>

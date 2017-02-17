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

            </div>

            <?php if ( $signups ) : ?>

                <div id="register" style="display: none">

                    <form id="registration_form">

                        <?php Form::render_fields( include Plugin::plugin_dir( Plugin::ID ) . 'config/register_user_form.php' ); ?>

                        <p class="registration-submit">

                            <button type="button" class="button button-primary registration-toggle">
                                <?php _e( get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ), Plugin::ID ); ?>
                            </button>

                            <input class="button button-primary"
                                type="submit"
                                value="<?php _e( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), Plugin::ID ); ?>"/>

                        </p>

                    </form>

                </div>

            <?php endif; ?>

        </div>
        
    </div>

</div>

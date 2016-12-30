<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use const SmartcatSupport\PLUGIN_NAME;

?>

<?php $signups = get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ); ?>

<div id="support-login-page">

    <div id="support-login-wrapper">
        
        <div id="support-login-form">
            
            <img class="logo" src="<?php echo get_option( Option::LOGIN_LOGO, Option\Defaults::LOGIN_LOGO ) ?>"/>
            
            <div id="login_form">

                <?php wp_login_form(); ?>

                <?php if ( $signups ) : ?>

                    <button class="trigger" data-action="toggle_register_form">

                        <?php _e( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), PLUGIN_NAME ); ?>

                    </button>

                <?php endif; ?>

            </div>

            <?php if ( $signups ) : ?>

                <div id="register_form">

                    <form class="support_form"
                          data-action="support_register_user"
                          data-after="post_user_register">

                        <?php Form::render_fields( include Plugin::resource_dir( PLUGIN_NAME ) . 'config/register_user_form.php' ); ?>

                        <div class="button_wrapper">

                            <button class="trigger" data-action="toggle_register_form">

                                <?php _e( get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ), PLUGIN_NAME ); ?>

                            </button>

                            <input class="button"
                                type="submit"
                                value="<?php _e( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), PLUGIN_NAME ); ?>"/>

                        </div>

                    </form>

                </div>


            <?php endif; ?>
        </div>
        
        <div id="support-login-disclaimer">
            <?php _e( get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ), PLUGIN_NAME ); ?>
        </div>
        
    </div>



</div>
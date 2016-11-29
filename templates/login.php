<?php

use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\register_form;
use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<?php $signups = get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ); ?>

<div id="login_form">

    <?php wp_login_form(); ?>

    <?php if( $signups ) : ?>

        <button class="trigger" data-action="toggle_register_form">

            <?php _e( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), TEXT_DOMAIN ); ?>

        </button>

    <?php endif; ?>

</div>

<?php if( $signups ) : ?>

    <div id="register_form" class="hidden">

        <form class="support_form"
            data-action="support_register_user"
            data-after="post_user_register">

            <?php Form::form_fields( register_form() ); ?>

            <div class="button_wrapper">

                <button class="trigger" data-action="toggle_register_form">

                    <?php _e( get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ), TEXT_DOMAIN ); ?>

                </button>

                <input class="button"
                    type="submit"
                    value="<?php _e( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), TEXT_DOMAIN ); ?>"/>

            </div>

        </form>

    </div>

<?php endif; ?>
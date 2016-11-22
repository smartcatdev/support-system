<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<form class="comment_form"
    data-action="<?php echo $ajax_action; ?>"
    data-after="<?php echo $after; ?>">

    <?php Form::form_fields( $form ); ?>

    <?php wp_comment_form_unfiltered_html_nonce(); ?>

    <div class="button_wrapper">

        <button class="button cancel">

            <?php _e( get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ) ); ?>

        </button>

        <button class="button submit">

            <?php _e( get_option( Option::REPLY_BTN_TEXT, Option\Defaults::REPLY_BTN_TEXT ) ); ?>

        </button>

    </div>

</form>
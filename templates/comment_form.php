<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<form class="comment_form"
    data-action="<?php esc_attr_e( $action ); ?>"
    data-after="<?php esc_attr_e( $after ); ?>">

    <?php Form::form_fields( $form ); ?>

    <?php wp_comment_form_unfiltered_html_nonce(); ?>

    <div class="submit_button_wrapper">

        <button class="submit_button">

            <div class="status hidden"></div>

            <span class="text"
                  data-default="<?php _e( $submit_text['default'], TEXT_DOMAIN ); ?>"
                  data-success="<?php _e( $submit_text['success'], TEXT_DOMAIN ); ?>"
                  data-fail="<?php _e( $submit_text['fail'], TEXT_DOMAIN ); ?>"
                  data-wait="<?php _e( $submit_text['wait'], TEXT_DOMAIN ); ?>">

                  <?php _e( $submit_text['default'], TEXT_DOMAIN ); ?>

            </span>

        </button>

    </div>

</form>
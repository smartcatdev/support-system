<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div id="ticket_info_modal">

    <form class="support_form"
        data-message="<?php _e( get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ), TEXT_DOMAIN ); ?>"
        data-action="support_update_ticket"
        data-after="post_ticket_edit">

        <?php Form::render_fields( $form ); ?>

        <div class="button_wrapper">
            <input type="submit" value="<?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ), TEXT_DOMAIN ); ?>" class="button" />
        </div>

    </form>

</div>
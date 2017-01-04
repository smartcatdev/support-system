<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;

?>

<div id="ticket_info_modal">

    <form class="support_form"
        data-message="<?php _e( get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ), PLUGIN_ID ); ?>"
        data-action="support_create_ticket"
        data-after="post_ticket_create">

        <?php Form::render_fields( $form ); ?>

        <div class="button_wrapper">
            <input type="submit" value="<?php _e( get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ), PLUGIN_ID ); ?>" class="button" />
        </div>

    </form>

</div>
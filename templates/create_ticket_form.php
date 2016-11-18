<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\Form;

?>

<div id="ticket_info_modal">

    <form class="support_form"
        data-action="support_create_ticket">

        <?php Form::form_fields( $form ); ?>

        <input type="submit" value="<?php _e( get_option( Option::SAVE_TICKET_BTN_TEXT, Option\Defaults::SAVE_TICKET_BTN_TEXT ) ); ?>" class="button_submit" />

    </form>

</div>
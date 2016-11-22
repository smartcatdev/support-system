<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div id="ticket_info_modal">

    <form class="support_form"
        data-action="<?php echo $ajax_action; ?>"
        data-after="view_ticket">

        <?php Form::form_fields( $form ); ?>

        <input type="submit" value="<?php _e( $submit_btn_text, TEXT_DOMAIN ); ?>" class="button_submit" />

    </form>

</div>
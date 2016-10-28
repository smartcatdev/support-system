<?php

/**
 * Template for a single ticket's detail view.
 * 
 * @param string $status (Optional) The current status of the ticket.
 * @param string $ajax_action The action to call when the form is submitted.
 * @param Form   $ticket_form The form for the main ticket information.
 * @param Form   $info_form (Optional) The form with the ticket's meta 
 * @since 1.0.0
 * @author Eric Green <eric@smartcat.ca>
 */

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div>
    
    <form data-action="<?php esc_attr_e( $ajax_action ); ?>"

        <?php if( isset( $post )  ) : ?>

            data-ticket_id="<?php esc_attr_e( $post->ID ); ?>"

        <?php endif; ?>

          class="edit_ticket_form">


        <?php Form::form_fields( $form ); ?>

        <input type="hidden"
            class="hidden"
            name="ticket_id"
            value="<?php esc_attr_e( isset( $post ) ? $post->ID : '' ); ?>" />

        <input type="submit"
            value="<?php _e( 'Save Ticket', TEXT_DOMAIN ); ?>"
            class="hidden submit_button" />

        <div class="status"></div>
    
    </form>
        
</div>

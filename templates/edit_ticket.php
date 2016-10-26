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
    
    <form data-action="<?php esc_attr_e( $ajax_action ); ?>">
    
        <?php Form::form_fields( $ticket_form ); ?>
    
        <?php if( isset( $info_form ) ) : 
            
            Form::form_fields( $info_form );
        
        endif; ?>
        
        <input type="submit" 
            value="<?php _e( 'Save Ticket', TEXT_DOMAIN ); ?>" />
    
    </form>
        
</div>

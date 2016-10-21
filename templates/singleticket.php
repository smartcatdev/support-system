<?php use SmartcatSupport\form\Form; ?>
<?php use const SmartcatSupport\TEXT_DOMAIN; ?>

<div>
    <form id="support_ticket_form"
        method="POST"
        data-action="<?php esc_attr_e( $ajax_action ); ?>" >
    
        <?php Form::form_fields( $ticket_form ); ?>
    
        <?php if( $info_form != null ) : 
            
            Form::form_fields( $info_form );
        
        endif; ?>
        
        <input type="submit" 
            value="<?php _e( 'Save Ticket', TEXT_DOMAIN ); ?>" />
    
    </form>
</div>

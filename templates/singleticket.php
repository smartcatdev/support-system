<?php use SmartcatSupport\form\Form; ?>
<?php use const SmartcatSupport\TEXT_DOMAIN; ?>

<div>
    
    <?php if( $status != null ) : ?>
        
        <p>
            <?php esc_html_e( $status, TEXT_DOMAIN ); ?>
        </p>
    
    <?php endif; ?>
    
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

<?php 

namespace SmartcatSupport\ticket;

use const SmartcatSupport\TEXT_DOMAIN; 

?>


<table class="form-table">
    <tr>
        <th>
            <label for="date_opened">
                <?php _e( 'Date Opened', TEXT_DOMAIN ) ?>
            </label>
        </th>
        <td>
            <input type="date" 
               id="date_opened" 
               name="date_opened" 
               value="<?php esc_attr_e( $ticket_meta->get_date_opened() ) ?>" />

            <p class="description">
                <?php _e( 'When ticket was opened', TEXT_DOMAIN ) ?>
            </p>
        </td>
    </tr>
    <tr>    
        <th>
            <label for="assigned_to">
                <?php _e( 'Assigned To', TEXT_DOMAIN ) ?>
            </label>
        </th>
        <td>
            <select id="assigned_to" 
                name="assigned_to">
                
                    <?php foreach( TicketMeta::get_agents() as $id => $name ) : ?>
                
                        <option value="<?php esc_attr_e( $id ) ?>"
                                <?php esc_attr_e( $ticket_meta->get_assigned_to() == $id ? 'selected' : '' ) ?>>
                            <?php esc_html_e( $name ) ?>
                        </option>
                        
                    <?php endforeach; ?>

            </select>

            <p class="description">
                <?php _e( 'Agent the ticket is assigned to', TEXT_DOMAIN ) ?>
            </p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="custom_email_address">
                <?php _e( 'Customer Email', TEXT_DOMAIN ) ?>
            </label>
        </th>
        <td>
            <input type="email" 
                id="email_address" 
                name="email_address" 
                value="<?php esc_attr_e( $ticket_meta->get_email_address() ) ?>" />

            <p class="description">
                <?php _e( 'Customer email address', TEXT_DOMAIN ) ?>
            </p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="status">
                <?php _e( 'Status', TEXT_DOMAIN ) ?>
            </label>
        </th>
        <td>
            <select id="status" 
                name="status">
                
                <?php foreach( TicketMeta::get_statuses() as $key => $value ) : ?>
                    <option value="<?php esc_attr_e( $key ) ?>" 
                            <?php esc_attr_e( $ticket_meta->get_status() == $key ? 'selected' : '' ) ?>>
                        <?php _e( $value, TEXT_DOMAIN ) ?>
                    </option>
                <?php endforeach; ?>
               
            </select>

            <p class="description">
                <?php _e( 'Ticket support status', TEXT_DOMAIN ) ?>
            </p>
        </td>
    </tr>
</table>
<?php wp_nonce_field( $this->nonce_action, $this->id ) ?>
    
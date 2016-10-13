<?php 

namespace SmartcatSupport\Ticket;

use const SmartcatSupport\TEXT_DOMAIN; 
use SmartcatSupport\Ticket\Meta;
use SmartcatSupport\Enum\Role;

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
               value="<?php esc_attr_e( get_post_meta( $post->ID, 'date_opened', true ) ) ?>" />

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
                
                <option><?php _e( 'No Agent Assigned', TEXT_DOMAIN ) ?></option>
                
                <?php if( $this->users != null ) : ?>
                    <?php foreach( $this->users as $user ) : ?>
                
                        <option value="<?php esc_attr_e( $user->ID ) ?>"
                                <?php esc_attr_e( get_post_meta( $post->ID, Meta::ASSIGNED_TO, true ) == $user->ID ? 'selected' : '' ) ?>>
                            <?php esc_html_e( $user->display_name ) ?>
                        </option>
                        
                    <?php endforeach; ?>
                <?php endif; ?>

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
                value="<?php esc_attr_e( get_post_meta( $post->ID, Meta::EMAIL_ADDRESS, true ) ) ?>" />

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
                
                <?php foreach( Meta::STATUS_VALUES as $key => $value ) : ?>
                    <option value="<?php esc_attr_e( $key ) ?>" 
                            <?php esc_attr_e( get_post_meta( $post->ID, Meta::STATUS, true ) == $key ? 'selected' : '' ) ?>>
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

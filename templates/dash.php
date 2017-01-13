<?php

use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;


?>
<div id="support-dashboard-page">
    
    <div class="container">

        <div class="row">

            <div class="alignright">

                <?php if ( current_user_can( 'create_support_tickets' ) ) : ?>

                    <a href="<?php echo admin_url( 'admin-ajax.php' ) . '?action=support_new_ticket' ?>"  rel="modal:open" class="button button-primary">
                        
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <?php _e( get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ), PLUGIN_ID ); ?>

                    </a>

                <?php endif; ?>
                
                <div class="clear"></div>
                
            </div>
            
            

            <div id="support_system" class="">

                <div class="tabs">

                    <ul>

                        <li>

                            <a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=support_list_tickets">

                                <?php _e( 'Tickets', PLUGIN_ID ); ?>


                            </a>

                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>
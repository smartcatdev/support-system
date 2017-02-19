<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;


?>
<div id="support-dashboard-page">
    
    <div class="container">

        <div class="row">

            <div class="alignright">

                <?php if ( current_user_can( 'create_support_tickets' ) ) : ?>

                    <a href="<?php echo admin_url( 'admin-ajax.php' ) . '?action=support_new_ticket' ?>"  rel="modal:open" class="button button-primary">
                        
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <?php _e( get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ), \SmartcatSupport\PLUGIN_ID ); ?>

                    </a>

                <?php endif; ?>
                
                <div class="clear"></div>
                
            </div>

            <div id="tabs">

                <ul>

                    <li>
                        <a href="#tickets"><?php _e( 'Tickets', \SmartcatSupport\PLUGIN_ID ); ?></a>
                    </li>

                </ul>

                <div id="tickets">

                    <form id="ticket_filter">

                        <?php do_action( 'support_tickets_table_filters' ); ?>

                        <button type="button" class="trigger" id="filter-toggle">
                            <i class="filter icon-filter"></i><?php _e( 'Filter', \SmartcatSupport\PLUGIN_ID ); ?>
                        </button>

                        <button type="button" class="trigger" id="refresh-tickets">
                            <i class="refresh icon-loop2"></i><?php _e( 'Refresh', \SmartcatSupport\PLUGIN_ID ); ?>
                        </button>

                    </form>

                    <div id="tickets-container"></div>

                </div>

            </div>

        </div>

    </div>

</div>
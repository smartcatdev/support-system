<?php

use SmartcatSupport\descriptor\Option;

?>

<div id="support-dashboard-page">

    <div id="settings-modal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"><?php _e( 'Settings', \SmartcatSupport\PLUGIN_ID ); ?></h4>

                </div>

                <div class="message"></div>

                <div class="modal-body">

                    <?php include_once 'settings.php'; ?>

                </div>

                <div class="modal-footer">

                    <button id="save-settings" type="button" class="button button-submit">

                        <span class="glyphicon glyphicon-floppy-save button-icon"></span>

                        <span><?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ) ); ?></span>

                    </button>

                </div>

            </div>

        </div>

    </div>

    <div id="create-modal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"><?php _e( 'New Support Request', \SmartcatSupport\PLUGIN_ID ); ?></h4>

                </div>

                <div class="modal-body">

                    <?php include_once 'create_ticket.php'; ?>

                </div>

                <div class="modal-footer">

                    <button id="create-ticket" type="button" class="button button-submit">

                        <?php _e( get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ) ); ?>

                    </button>

                </div>

            </div>

        </div>

    </div>
    
    <div class="container">

        <?php $widget = current_user_can( 'manage_support_tickets' )
                ? stripslashes( get_option( Option::AGENT_WIDGET_AREA, Option\Defaults::AGENT_WIDGET_AREA ) )
                : stripslashes( get_option( Option::USER_WIDGET_AREA, Option\Defaults::USER_WIDGET_AREA ) ); ?>

        <?php if( !empty( $widget ) ) : ?>

            <div class="row">
                <div class="col-sm-12">
                    <?php echo $widget; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if( current_user_can( 'manage_support_tickets' ) ) : ?>
        <div class="row">
            <div id="statistics-container"></div>            
        </div>
        <?php endif; ?>
        
        <div class="row">
            
            <div class="text-right">

                <div class="clear"></div>

            </div>

            <div id="tabs">

                <ul class="nav nav-tabs">

                    <li class="tab active">
                        <a data-toggle="tab" href="#tickets"><?php _e( 'Tickets', \SmartcatSupport\PLUGIN_ID ); ?></a>
                    </li>

                </ul>

                <div class="tab-content">

                    <div id="tickets" class="tab-pane fade in active">

                        <?php include_once 'ticket_filter.php'; ?>

                        <div id="tickets-container" class="row"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

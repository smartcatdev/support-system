<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;


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

                        <?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ) ); ?>

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

        <div class="row">

            <div class="text-right">

                <?php if ( current_user_can( 'create_support_tickets' ) ) : ?>

                    <button class="button button-primary" data-toggle="modal" data-target="#create-modal">

                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <?php _e( get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ), \SmartcatSupport\PLUGIN_ID ); ?>

                    </button>

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
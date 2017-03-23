<?php

use SmartcatSupport\descriptor\Option;

$user = wp_get_current_user();

?>
<div id="navbar" class="background-secondary">

    <div class="container-fluid">

        <div class="row">

                <div class="row-table pull-left">

                    <div class="row-table-cell">

                        <a href="#date" class="background-secondary hover menu-item">

                            <span class="glyphicon-calendar glyphicon"></span>

                            <span id="sys-date"></span>

                        </a>

                        <span class="text-muted">|</span>

                        <a href="#time" class="background-secondary hover menu-item">

                            <span class="glyphicon-time glyphicon"></span>

                            <span id="sys-time"></span>

                        </a>

                    </div>

                </div>

                <div class="row-table pull-right">

                    <?php if ( current_user_can('create_support_tickets') ) : ?>

                        <div class="row-table-cell">

                            <button class="button button-primary" data-toggle="modal" data-target="#create-modal">

                                <span class="glyphicon glyphicon-plus-sign"></span>

                                <?php _e( get_option(Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT), \SmartcatSupport\PLUGIN_ID ); ?>

                            </button>

                        </div>

                    <?php endif; ?>

                    <div class="row-table-cell">
                        
                        <div class="dropdown-wrapper">
                            
                            <a href="#" 
                               class="dropdown-toggle" 
                               data-toggle="dropdown" 
                               role="button" 
                               aria-haspopup="true" 
                               aria-expanded="false">

                                <?php echo get_avatar( $user->ID, 46 ); ?>

                                <?php echo $user->user_firstname ?> <span class="caret"></span>

                            </a>
                            
                            <ul class="dropdown-menu">

                                <li>

                                    <a href="#" data-toggle="modal" data-target="#settings-modal">

                                        <span class="glyphicon glyphicon-cog"></span>

                                        <?php _e( 'Settings', \SmartcatSupport\PLUGIN_ID ); ?>

                                    </a>

                                </li>

                                <li role="separator" class="divider"></li>

                                <?php if( current_user_can( 'manage_options') ) : ?>

                                    <li>
                                        <a href="<?php echo admin_url(); ?>" class="alignright background-secondary hover menu-item">

                                            <span class="glyphicon glyphicon-user"></span>

                                            <?php _e( 'WordPress', \SmartcatSupport\PLUGIN_ID ); ?>

                                        </a>

                                    </li>

                                    <li role="separator" class="divider"></li>

                                <?php endif; ?>

                                <li>
                                    <a href="<?php echo wp_logout_url(); ?>" class="alignright background-secondary hover menu-item">

                                        <span class="glyphicon-log-out glyphicon"></span>

                                        <?php _e( 'Logout', \SmartcatSupport\PLUGIN_ID ); ?>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>


        </div>

    </div>

</div>

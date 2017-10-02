<?php

use ucare\Options;

$user = wp_get_current_user();

?>
<div id="navbar" class="background-secondary">

    <div class="container-fluid">

        <div class="row">

            <?php if ( get_option( Options::DISPLAY_BACK_BUTTON, \ucare\Defaults::DISPLAY_BACK_BUTTON ) == 'on' ) : ?>

                <div class="row-table pull-left">

                    <div class="row-table-cell">

                        <a class="brand-logo" href="<?php echo esc_url( home_url() ); ?>" title="<?php _e( 'Back to site', 'ucare' ); ?>">

                            <img height="40" src="<?php echo esc_url( get_option( Options::LOGO, \ucare\Defaults::LOGO ) ); ?>" />

                        </a>

                    </div>

                </div>

            <?php endif; ?>

            <div class="row-table pull-left clock">

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

            <div class="row-table pull-right actions">

                <?php if ( current_user_can('create_support_tickets') ) : ?>

                    <div class="row-table-cell">

                        <button class="button button-primary" data-toggle="modal" data-target="#create-modal">

                            <span class="glyphicon glyphicon-plus-sign button-icon"></span>

                            <span><?php _e( get_option(Options::CREATE_BTN_TEXT, \ucare\Defaults::CREATE_BTN_TEXT), 'ucare' ); ?></span>

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

                                    <?php _e( 'Settings', 'ucare' ); ?>

                                </a>

                            </li>

                            <li role="separator" class="divider"></li>

                            <?php if( current_user_can( 'manage_options' ) ) : ?>

                                <li>
                                    <a href="<?php echo admin_url( 'admin.php?page=ucare_support' ); ?>" class="alignright background-secondary hover menu-item">

                                        <span class="glyphicon glyphicon-th-large"></span>

                                        <?php _e( 'Support Admin', 'ucare' ); ?>

                                    </a>

                                </li>

                                <li role="separator" class="divider"></li>

                            <?php endif; ?>

                            <li>
                                <a href="<?php echo esc_url( home_url() ); ?>" class="alignright background-secondary hover menu-item">

                                    <span class="glyphicon glyphicon-globe  "></span>

                                    <?php _e( 'Back to Site', 'ucare' ); ?>

                                </a>

                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a href="<?php echo add_query_arg( 'redirect_to', urlencode( \ucare\support_page_url() ), wp_logout_url() ); ?>" class="alignright background-secondary hover menu-item">

                                    <span class="glyphicon-log-out glyphicon"></span>

                                    <?php _e( 'Logout', 'ucare' ); ?>

                                </a>

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

namespace ucare;

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

            <?php if ( get_option( Options::SHOW_CLOCK, Defaults::SHOW_CLOCK ) ) : ?>

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

            <?php endif; ?>

            <div class="row-table pull-right actions">

                <?php if ( current_user_can( 'edit_support_tickets' ) ) : ?>

                    <div class="row-table-cell">

                        <button class="button button-primary" data-toggle="modal" data-target="#create-modal">

                            <span class="glyphicon glyphicon-plus-sign button-icon"></span>

                            <span><?php _e( get_option( Options::CREATE_BTN_TEXT, \ucare\Defaults::CREATE_BTN_TEXT ), 'ucare' ); ?></span>

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


<?php if ( has_nav_menu( 'ucare_header_navbar' ) ) : ?>

    <?php

        $navbar = array(
            'menu'           => 'header',
            'theme_location' => 'ucare_header_navbar',
            'depth'          => 2,
            'menu_class'     => 'nav navbar-nav',
            'fallback_cb'    => 'ucare\BootstrapNavWalker::fallback',
            'walker'         => new BootstrapNavWalker()
        );

    ?>

    <nav class="navbar navbar-default" id="ucare-navigation-menu">

        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle pull-left" data-toggle="collapse" data-target="#nav-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="nav-menu">
                <?php wp_nav_menu( $navbar ); ?>
            </div>

        </div>

    </nav>

<?php endif; ?>

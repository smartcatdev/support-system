<?php
/**
 * Template for the main application navigation bar.
 *
 * @since 1.0.0
 * @package ucare
 */
namespace ucare;

?>

<div id="navbar" class="background-secondary">

    <div class="container-fluid">

        <div class="row">

            <?php if ( get_option( Options::DISPLAY_BACK_BUTTON ) ) : ?>

                <div class="row-table pull-left">

                    <div class="row-table-cell">

                        <a class="brand-logo" href="<?php echo esc_url( home_url() ); ?>" title="<?php _e( 'Back to site', 'ucare' ); ?>">
                            <img height="40" src="<?php echo esc_url( get_option( Options::LOGO ) ); ?>" />
                        </a>

                    </div>

                </div>

            <?php endif; ?>


            <?php if ( !is_support_page() ) : ?>

                <div class="row-table pull-left">

                    <div class="row-table-cell">

                        <a href="<?php esc_url_e( support_page_url() ); ?>" class="button button-primary back btn-back">
                            <span class="glyphicon glyphicon-share-alt"></span>
                            <span><?php _e( 'Help Desk', 'ucare' ); ?></span>
                        </a>

                    </div>

                </div>

            <?php endif; ?>


            <div class="row-table pull-right actions">

                <?php if ( !is_create_ticket_page() ) : ?>

                    <div class="row-table-cell">

                        <a class="button button-primary" href="<?php echo esc_url( create_page_url() ); ?>">

                            <span class="glyphicon glyphicon-plus-sign button-icon"></span>
                            <span>
                                <?php echo esc_html_e( get_option( Options::CREATE_BTN_TEXT ) ); ?>
                            </span>

                        </a>

                    </div>

                <?php endif; ?>

                <div class="row-table-cell">

                    <div class="dropdown-wrapper">

                        <a href="#"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           role="button"
                           aria-haspopup="true"
                           aria-expanded="false"><?php echo get_avatar( get_current_user_id(), 46 ); ?>
                            <?php esc_html_e( wp_get_current_user()->first_name ); ?> <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a href="<?php esc_url_e( edit_profile_page_url() ); ?>">
                                    <span class="glyphicon glyphicon-cog"></span><?php _e( 'Profile', 'ucare' ); ?>
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <?php if ( current_user_can( 'manage_options' ) ) : ?>

                                <li>
                                    <a href="<?php echo admin_url( 'admin.php?page=ucare_support' ); ?>" class="alignright background-secondary hover menu-item">
                                        <span class="glyphicon glyphicon-th-large"></span><?php _e( 'Support Admin', 'ucare' ); ?>
                                    </a>

                                </li>

                                <li role="separator" class="divider"></li>

                            <?php endif; ?>

                            <li>
                                <a href="<?php echo esc_url( home_url() ); ?>" class="alignright background-secondary hover menu-item">
                                    <span class="glyphicon glyphicon-globe"></span><?php _e( 'Back to Site', 'ucare' ); ?>
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li>
                                <a href="<?php esc_url_e( wp_logout_url( support_page_url() ) ); ?>" class="alignright background-secondary hover menu-item">
                                    <span class="glyphicon-log-out glyphicon"></span><?php _e( 'Logout', 'ucare' ); ?>
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

    <?php $navbar = array(
        'menu'           => 'header',
        'theme_location' => 'ucare_header_navbar',
        'depth'          => 2,
        'menu_class'     => 'nav navbar-nav',
        'fallback_cb'    => 'ucare\BootstrapNavWalker::fallback',
        'walker'         => new BootstrapNavWalker()
    ); ?>

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

<?php
/**
 * Plugin Name: Smartcat Theme Showcase
 * Author: Smartcat
 * Version: 1.0.0
 *
 * @since 1.0.0
 * @package scts
 */
namespace {

    /**
     * Initialize the themes showcase
     *
     * @since 1.0.0
     * @return void
     */
    function scts_init() {
        if ( in_array( __FILE__, wp_get_active_and_valid_plugins() ) ) {
            /**
             *
             * Use Home URL if running in plugin mode
             */
            define( 'SCTS_SOURCE_URL', home_url(), true );

            /**
             * Define plugin mode as active
             */
            define( 'SCTS_PLUGIN_MODE', true, true );

            /**
             *
             * Load plugin functionality
             */
            add_action( 'plugins_loaded', 'scts\plugin_mode_init' );

        } else {
            /**
             *
             * Use remote data source in embedded mode
             */
            define( 'SCTS_SOURCE_URL', 'https://smartcatdesign.net', true );
        }

        if ( !defined( 'SCTS_MENU_POSITION' ) ) {
            define( 'SCTS_MENU_POSITION', 51, true );
        }

        add_action( 'admin_menu', 'scts\add_admin_menu_page' );
    }

    if ( defined( 'ABSPATH' ) ) {
        scts_init(); // Check environment early
    }

}

namespace scts {

    /**
     * Initialize plugin mode
     *
     * @action plugins_loaded
     *
     * @since 1.0.0
     * @return void
     */
    function plugin_mode_init() {
        add_action( 'init', 'scts\register_theme_post_type' );
        add_action( 'rest_api_init', 'scts\register_rest_fields' );
        add_action( 'save_post_theme', 'scts\save_theme_meta_box' );
        add_action( 'admin_post_scts_refresh', 'scts\handle_refresh' );
    }

    /**
     * Register the themes post type.
     *
     * @action init
     *
     * @since 1.0.0
     * @return void
     */
    function register_theme_post_type() {
        $labels = array(
            'name'                  => _x( 'Themes', 'Post Type General Name' ),
            'singular_name'         => _x( 'Themes', 'Post Type Singular Name' ),
            'menu_name'             => __( 'Theme Showcase' ),
            'name_admin_bar'        => __( 'Theme' ),
            'archives'              => __( 'Theme Archives' ),
            'parent_item_colon'     => __( 'Parent Theme:' ),
            'all_items'             => __( 'All Themes' ),
            'add_new_item'          => __( 'Add New Theme' ),
            'add_new'               => __( 'Add New' ),
            'new_item'              => __( 'New Theme' ),
            'edit_item'             => __( 'Edit Theme' ),
            'update_item'           => __( 'Update Theme' ),
            'view_item'             => __( 'View Theme' ),
            'search_items'          => __( 'Search Themes' ),
            'not_found'             => __( 'Theme Not found' ),
            'not_found_in_trash'    => __( 'Theme Not found in Trash' ),
            'featured_image'        => __( 'Featured Image' ),
            'set_featured_image'    => __( 'Set featured image' ),
            'remove_featured_image' => __( 'Remove featured image' ),
            'use_featured_image'    => __( 'Use as featured image' ),
            'insert_into_item'      => __( 'Insert into theme' ),
            'uploaded_to_this_item' => __( 'Uploaded to this theme' ),
            'items_list'            => __( 'Themes list' ),
            'items_list_navigation' => __( 'Themes list navigation' ),
            'filter_items_list'     => __( 'Filter themes list' )
        );
        $args = array(
            'labels'               => $labels,
            'description'          => __( 'Remotely managed theme showcase' ),
            'supports'             => array( 'editor', 'title', 'thumbnail' ),
            'hierarchical'         => false,
            'public'               => false,
            'show_ui'              => true,
            'show_in_menu'         => true,
            'menu_icon'            => 'dashicons-art',
            'show_in_admin_bar'    => false,
            'show_in_nav_menus'    => false,
            'can_export'           => true,
            'has_archive'          => false,
            'exclude_from_search'  => true,
            'publicly_queryable'   => false,
            'capability_type'      => 'post',
            'feeds'                => null,
            'show_in_rest'         => true,
            'rest_base'            => 'themes',
            'register_meta_box_cb' => 'scts\add_theme_meta_box'
        );
        register_post_type( 'theme', $args );
    }

    /**
     * Register custom rest API fields
     *
     * @action rest_api_init
     *
     * @since 1.0.0
     * @return void
     */
    function register_rest_fields() {
        register_rest_field( 'theme', 'shop_url', array(
            'get_callback' => function( $post ) {
                return (string) get_post_meta( $post['id'], 'scts_shop_url', true );
            },
            'schema' => array( 'type' => 'string' ),
        ) );
        register_rest_field( 'theme', 'preview_url', array(
            'get_callback' => function( $post ) {
                return (string) get_post_meta( $post['id'], 'scts_preview_url', true );
            },
            'schema' => array( 'type' => 'string' ),
        ) );
        register_rest_field( 'theme', 'is_wp_org', array(
            'get_callback' => function( $post ) {
                return (bool) get_post_meta( $post['id'], 'scts_wp_org', true );
            },
            'schema' => array( 'type' => 'boolean' ),
        ) );
        register_rest_field( 'theme', 'theme_author', array(
            'get_callback' => function( $post ) {
                return (string) get_post_meta( $post['id'], 'scts_theme_author', true );
            },
            'schema' => array( 'type' => 'string' ),
        ) );
        register_rest_field( 'theme', 'screenshot_url', array(
            'get_callback' => function( $post ) {
                return (string) esc_url( get_the_post_thumbnail_url( $post['id'] ) );
            },
            'schema' => array( 'type' => 'string' ),
        ) );
    }

    /**
     * Add theme info metabox
     *
     * @since 1.0.0
     * @return void
     */
    function add_theme_meta_box() {
        add_meta_box( 'scts-theme', __( 'Theme Details' ), 'scts\theme_meta_box' );
    }

    /**
     * Fetch theme info from the REST API
     *
     * @since 1.0.0
     * @return array
     */
    function fetch_theme_info() {
        $response = wp_remote_get( SCTS_SOURCE_URL . '/wp-json/wp/v2/themes' );

        if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return array();
        }
        $themes = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( !empty( $themes ) ) {
            set_transient( 'scts_cache', $themes, 3600 * 24 );
        }
        return $themes;
    }

    /**
     * Get all available themes
     *
     * @since 1.0.0
     * @return array
     */
    function get_themes() {
        $cached = get_transient( 'scts_cache' );

        if ( empty( $cached ) ) {
            return fetch_theme_info();
        }
        return $cached;
    }

    /**
     * Prepare theme selection
     *
     * @since 1.0.0
     * @return array
     */
    function get_prepared_themes() {
        $current = wp_get_theme(); // Get the active theme
        $templates = get_themes();

        foreach ( $templates as &$theme ) {
            $template = wp_get_theme( $theme['slug'] ); // Get installed template

            $theme['installed'] = $template->exists();
            $theme['is_active'] = $template->get_template() === $current->get_template();

            $actions = array(
                'activate'  => null,
                'install'   => null,
                'customize' => null,
                'more_info' => null
            );

            $can_customize = current_user_can( 'customize' ) && current_user_can( 'edit_theme_options' );
            $can_activate  = current_user_can( 'switch_themes' );

            if ( $theme['installed'] && $can_customize ) {
                $args = array(
                    'return' => urlencode( esc_url_raw( remove_query_arg( wp_removable_query_args(), wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) )
                );
                $actions['customize'] = add_query_arg( $args, wp_customize_url( $theme['slug'] ) );

            } else if ( current_user_can( 'install_themes' ) && !empty( $theme['is_wp_org'] ) ) {
                $actions['install'] = admin_url( 'theme-install.php?theme=' . $theme['slug'] );

            }

            if ( isset( $theme['shop_url'] ) ) {
                $actions['more_info'] = $theme['shop_url'];
            }

            if ( $theme['installed'] && !$theme['is_active'] && $can_customize && $can_activate ) {
                $actions['activate'] = wp_nonce_url( admin_url( 'themes.php?action=activate&stylesheet=' . urlencode( $theme['slug'] ) ), 'switch-theme_' . $theme['slug'] );
            }
            $theme['actions'] = $actions;
        }

        return $templates;
    }

    /**
     * Check to see if running in plugin mode
     *
     * @since 1.0.0
     * @return bool
     */
    function is_plugin() {
        return defined( 'SCTS_PLUGIN_MODE' ) && SCTS_PLUGIN_MODE;
    }

    /**
     * Add themes menu page
     *
     * @global $menu
     *
     * @action admin_menu
     *
     * @since 1.0.0
     * @return void
     */
    function add_admin_menu_page() {
        global $menu;

        if ( is_plugin() ) {
            $hook = add_submenu_page(
                'edit.php?post_type=theme',
                __( 'Preview' ),
                __( 'Preview' ),
                'edit_posts',
                'showcase-preview',
                'scts\themes_page'
            );
        } else {
            $menu[ SCTS_MENU_POSITION ] = array(
                0 => '',
                1 => 'read',
                2 => 'separator' . SCTS_MENU_POSITION,
                3 => '',
                4 => 'wp-menu-separator'
            );
            $hook = add_menu_page(
                __( 'Featured Themes' ),
                __( 'Featured Themes' ),
                'install_themes',
                'featured-themes',
                'scts\themes_page',
                'dashicons-art',
                SCTS_MENU_POSITION
            );
        }

        add_action("load-$hook", function () {
            wp_enqueue_script( 'wp-util' );
            add_action( 'admin_footer', 'scts\theme_page_js_templates' );
            add_action( 'admin_footer', 'scts\theme_page_inline_styles' );
            add_action( 'admin_footer', 'scts\theme_page_inline_scripts' );
        });
    }

    /**
     * Handle refresh button
     *
     * @action admin_post_scts_refresh
     *
     * @since 1.0.0
     * @return void
     */
    function handle_refresh() {
        if ( is_plugin() ) {
            delete_transient( 'scts_cache' );
        }

        wp_safe_redirect( wp_get_referer() );
    }

    /**
     * Save the theme details meta box
     *
     * @action save_post_theme
     *
     * @param int $post_id
     *
     * @since 1.0.0
     * @return void
     */
    function save_theme_meta_box( $post_id ) {
        if ( !isset( $_POST['scts_nonce'] ) || !wp_verify_nonce( $_POST['scts_nonce'], 'scts_save_meta_box' ) ) {
            return;
        }

        update_post_meta( $post_id, 'scts_wp_org', !empty( $_POST['scts_wp_org'] ) );
        update_post_meta( $post_id, 'scts_preview_url', esc_url_raw( $_POST['scts_preview_url'] ) );
        update_post_meta( $post_id, 'scts_shop_url', esc_url_raw( $_POST['scts_shop_url'] ) );
        update_post_meta( $post_id, 'scts_theme_author', sanitize_text_field( $_POST['scts_theme_author'] ) );
    }

    /**
     * Output the theme meta box
     *
     * @param \WP_Post $post
     *
     * @since 1.0.0
     * @return void
     */
    function theme_meta_box( $post ) { ?>
        <table class="form-table">
            <tr class="widefat">
                <td>
                    <label for="scts-preview-url"><?php _e( 'Preview URL' ); ?></label>
                </td>
                <td>
                    <input type="url"
                           name="scts_preview_url"
                           id="scts-preview-url"
                           class="regular-text"
                           value="<?php echo esc_url( get_post_meta( $post->ID, 'scts_preview_url', true ) ); ?>"/>
                </td>
            </tr>
            <tr class="widefat">
                <td>
                    <label for="scts-shop-url"><?php _e( 'Shop URL' ); ?></label>
                </td>
                <td>
                    <input type="url"
                           name="scts_shop_url"
                           id="scts-shop-url"
                           class="regular-text"
                           value="<?php echo esc_url( get_post_meta( $post->ID, 'scts_shop_url', true ) ); ?>"/>
                </td>
            </tr>
            <tr class="widefat">
                <td>
                    <label for="scts-theme-author"><?php _e( 'Author' ); ?></label>
                </td>
                <td>
                    <input type="text"
                           name="scts_theme_author"
                           id="scts-theme-author"
                           class="regular-text"
                           value="<?php esc_html_e( get_post_meta( $post->ID, 'scts_theme_author', true ) ); ?>"/>
                </td>
            </tr>
            <tr class="widefat">
                <td>
                    <label for="scts-is-wp-org"><?php _e( 'WordPress.org' ); ?></label>
                </td>
                <td>
                    <label>
                        <input type="checkbox"
                               name="scts_wp_org"
                               id="scts-wp-org"
                               class="regular-text"
                            <?php checked( true, !!get_post_meta( $post->ID, 'scts_wp_org', true ) ); ?> />
                        <?php _e( 'Is this theme on WordPress.org?' ); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php wp_nonce_field( 'scts_save_meta_box', 'scts_nonce' ); ?>
    <?php }

    /**
     * Output themes page
     *
     * @since 1.0.0
     * @return void
     */
    function themes_page() { $themes = get_prepared_themes(); ?>
        <div class="wrap themes-php">
            <h1 class="wp-heading-inline">
                <?php _e( 'Featured Themes' ); ?> <span class="title-count theme-count"><?php esc_html_e( count( $themes ) ); ?></span>
            </h1>
            <div id="scts-search-wrap">
                <form class="search-form" action="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>">
                    <input placeholder="<?php _e( 'Search themes...' ); ?>"
                           type="search"
                           name="search"
                           id="wp-filter-search-input"
                           class="wp-filter-search">
                </form>
                <?php if ( is_plugin() ) : ?>
                    <a id="refresh" class="button" href="<?php echo wp_nonce_url( admin_url( 'admin-post.php?action=scts_refresh' ), 'scts_refresh', 'nonce' ); ?>">
                        <span class="dashicons dashicons-image-rotate"></span>
                    </a>
                <?php endif; ?>
            </div>
            <div class="theme-browser rendered">
                <div class="themes wp-clearfix">

                    <?php foreach ( $themes as $theme ) : ?>

                        <div class="theme <?php esc_html_e( $theme['is_active'] ? 'active' : '' ); ?>"
                             data-theme="<?php echo htmlspecialchars( json_encode( $theme ) ); ?>">

                            <div class="theme-screenshot">
                                <img src="<?php echo esc_url( $theme['screenshot_url'] ); ?>" alt="" />
                            </div>
                            <span class="more-details">
                                <?php _e( 'Details & Preview' ); ?>
                            </span>
                            <div class="theme-id-container">
                                <h2 class="theme-name" id="<?php esc_attr_e( $theme['slug'] ); ?>-name">
                                    <?php if ( $theme['is_active'] ) : ?>
                                        <span><?php _e( 'Active:' ); ?></span>
                                    <?php endif; ?>
                                    <?php esc_html_e( $theme['title']['rendered'] ); ?>
                                </h2>
                                <div class="theme-actions">

                                    <?php if ( $theme['is_active'] && !empty( $theme['actions']['customize'] ) ) : ?>

                                        <a class="button button-primary customize load-customize hide-if-no-customize"
                                           href="<?php echo esc_url( $theme['actions']['customize'] ); ?>">
                                            <?php _e( 'Customize' ); ?>
                                        </a>

                                    <?php elseif ( $theme['installed'] && !empty( $theme['actions']['activate'] ) ) : ?>

                                        <a class="button button-primary activate"
                                           href="<?php echo esc_url( $theme['actions']['activate'] ); ?>">
                                            <?php _e( 'Activate' ); ?>
                                        </a>

                                        <?php if ( !empty( $theme['actions']['customize'] ) ) : ?>

                                            <a class="button load-customize hide-if-no-customize"
                                               href="<?php echo esc_url( $theme['actions']['customize'] ); ?>">
                                                <?php _e( 'Live Preview' ); ?>
                                            </a>

                                        <?php endif; ?>

                                    <?php elseif ( !empty( $theme['actions']['install'] ) ) : ?>

                                        <a class="button button-primary install" href="<?php echo esc_url( $theme['actions']['install'] ); ?>">
                                            <?php _e( 'Install' ); ?>
                                        </a>
                                        <a class="button load-preview hide-if-no-customize" href="#">
                                            <?php _e( 'Preview' ); ?>
                                        </a>

                                    <?php elseif ( !empty( $theme['actions']['more_info'] ) ) : ?>

                                        <a class="button button-primary install" target="_blank" href="<?php echo esc_url( $theme['actions']['more_info'] ); ?>">
                                            <?php _e( 'More Info' ); ?>
                                        </a>
                                        <a class="button load-preview hide-if-no-customize" href="#">
                                            <?php _e( 'Preview' ); ?>
                                        </a>

                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                    <div class="clear"></div>
                </div>
            </div>
        </div>
    <?php }

    /**
     * Output Underscore templates
     *
     * @action admin_footer
     *
     * @since 1.0.0
     * @return void
     */
    function theme_page_js_templates() { ?>
        <script id="tmpl-theme-preview" type="text/template">
            <div class="theme-install-overlay wp-full-overlay no-navigation expanded">
                <div class="wp-full-overlay-sidebar">
                    <div class="wp-full-overlay-header">
                        <button class="close-full-overlay"><span class="screen-reader-text"><?php _e( 'Close' ); ?></span></button>
                        <# if ( data.actions.more_info ) { #>
                        <a class="button button-primary" href="{{ data.actions.more_info }}"><?php _e( 'Theme Homepage' ); ?></a>
                        <# } #>
                        <# if ( data.is_active && data.actions.customize ) { #>
                        <a class="button button customize" href="{{ data.actions.customize }}"><?php _e( 'Customize' ); ?></a>
                        <# } else if ( data.installed && data.actions.activate ) { #>
                        <a href="{{ data.actions.activate }}" class="button button activate"><?php _e( 'Activate' ); ?></a>
                        <# } else if ( data.actions.install ) { #>
                        <a href="{{ data.actions.install }}" class="button button install"><?php _e( 'Install' ); ?></a>
                        <# } #>
                    </div>
                    <div class="wp-full-overlay-sidebar-content">
                        <div class="install-theme-info">
                            <h3 class="theme-name">{{ data.title.rendered }}</h3>
                            <span class="theme-by"><?php printf( __( 'By %s' ), '{{ data.theme_author }}' ); ?></span>
                            <img class="theme-screenshot" src="{{ data.screenshot_url }}" alt="" />
                            <div class="theme-details">
                                <div class="theme-description">{{{ data.content.rendered }}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="wp-full-overlay-footer">
                        <button type="button" class="collapse-sidebar button" aria-expanded="true" aria-label="<?php esc_attr_e( 'Collapse Sidebar' ); ?>">
                            <span class="collapse-sidebar-arrow"></span>
                            <span class="collapse-sidebar-label"><?php _e( 'Collapse' ); ?></span>
                        </button>
                    </div>
                </div>
                <div class="wp-full-overlay-main">
                    <iframe src="{{ data.preview_url }}" title="<?php esc_attr_e( 'Preview' ); ?>"></iframe>
                </div>
            </div>
        </script>
    <?php }

    /**
     * Output inline scripts
     *
     * @action admin_footer
     *
     * @since 1.0.0
     * @return void
     */
    function theme_page_inline_scripts() { ?>
        <script>
            jQuery(document).ready(function ($) {
                $('#wp-filter-search-input').on('change keyup paste', _.debounce(function () {
                    $('.search-form').submit();
                }, 300));

                const preview = wp.template('theme-preview');

                $('body').on('click', '.theme', function (e) {
                    if ($(e.target).is('.button') && !$(e.target).is('.load-preview')) {
                        return true; // Ignore action links
                    }

                    const $preview = $(preview($(this).data('theme')));

                    $preview.find('.close-full-overlay').on('click', function () {
                        $preview.fadeOut('fast', function () {
                            $preview.remove();
                        });
                    });

                    $preview.find('.collapse-sidebar').click(function () {
                        $preview.toggleClass('expanded');
                        $preview.toggleClass('collapsed');
                    });

                    $preview.find('iframe').on('load', function () {
                        $preview.addClass('iframe-ready')
                    });

                    $('body').append($preview.fadeIn('fast'));
                });
            });
        </script>
    <?php }

    /**
     * Output inline styles
     *
     * @action admin_footer
     *
     * @since 1.0.0
     * @return void
     */
    function theme_page_inline_styles() { ?>
        <style>
            h1.wp-heading-inline {
                margin-bottom: 15px;
            }

            #scts-search-wrap {
                display: inline-flex;
                justify-content: space-between;
            }

            #scts-search-wrap #wp-filter-search-input {
                top: 0;
                left: 0;
            }

            #scts-search-wrap #refresh {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 31px;
                width: 40px;
                margin-left: 5px;
            }
        </style>
    <?php }

}

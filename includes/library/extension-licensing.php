<?php

/**
 * Provides common framework for managing extension updates and a license management page.
 *
 * @version 1.0.0
 */
if( !class_exists( 'SC_License_Manager' ) ) :

    class SC_License_Manager {

        private $id;
        private $extensions = array();

        private $page_args;
        private $page_type;

        private $hook = '';

        /**
         * SC_License_Manager constructor.
         *
         * @param string $id        The extension's unique ID.
         * @param string $page_type The type of page for the license management page.
         * @param array  $page_args Arguments to pass to add_{type}_page().
         * @since 1.0.0
         */
        public function __construct( $id, $page_type = 'options', $page_args = array() ) {

            $this->id = $id;
            $this->page_type = $page_type;
            $this->page_args = $page_args;

            $this->init();
        }

        /**
         * Add hooks an initialize extension registration.
         *
         * @since 1.0.0
         */
        private function init() {

            $this->schedule_cron();

            add_action( 'admin_menu', array( $this, 'add_license_page' ), 100 );
            add_action( 'admin_init', array( $this, 'register_settings' ) );
            add_action( 'admin_init', array( $this, 'activate_license' ) );
            add_action( 'admin_init', array( $this, 'deactivate_license' ) );
            add_action( 'admin_notices', array( $this, 'expired_license_notifications' ) );
            add_action( 'admin_notices', array( $this, 'inactive_license_notifications' ) );
            add_action( 'admin_print_styles', array( $this, 'print_styles' ) );

            add_action( "{$this->id}_extensions_license_check", array( $this, 'check_licenses' ) );

            // Extensions hook onto this to register their licenses
            do_action( "{$this->id}_register_extensions", $this );

        }

        private function is_license_page() {
            return $this->hook == get_current_screen()->id;
        }

        /**
         * Setup a cron that will deactivate licenses of expired plugins.
         *
         * @since 1.0.0
         */
        public function schedule_cron() {
            if ( !wp_next_scheduled( "{$this->id}_extensions_license_check" ) ) {
                wp_schedule_event( time(), 'daily', "{$this->id}_extensions_license_check" );
            }
        }

        /**
         * Cron job will only be cleared if there are no extensions registered.
         *
         * @since 1.0.0
         */
        public function clear_cron() {
            if( empty( $this->extensions ) ) {
                wp_clear_scheduled_hook( "{$this->id}_extensions_license_check" );
            }
        }

        /**
         * Display any notifications for expired licenses.
         *
         * @since 1.0.0
         */
        public function expired_license_notifications() {

            if( !$this->is_license_page() ) {

                $notices = get_option( "{$this->id}-extension-notices", array() );

                foreach( $notices as $ext ) {

                    // Make sure the extension is still installed
                    if( array_key_exists( $ext, $this->extensions ) ) { ?>

                        <div class="notice notice-warning is-dismissible">
                            <p>
                                <?php _e( 'Your license for ' . $this->extensions[ $ext ]['item'] . ' has expired. Please renew it at ' ); ?>
                                <a href="<?php esc_url( $this->extensions[ $ext ]['url'] ); ?>"><?php echo esc_url( $this->extensions[ $ext ]['url'] ); ?></a>
                            </p>
                        </div>

                    <?php }

                }

            }

        }

        /**
         * Display any notifications for inactive licenses.
         *
         * @since 1.0.0
         */
        public function inactive_license_notifications() {

            if( !$this->is_license_page() ) {

                $notices = get_option( "{$this->id}-extension-notices", array() );

                foreach( $this->extensions as $id => $ext ) {

                    // Make sure the license hasn't been marked as expired
                    if( get_option( $this->extensions[ $id ]['options']['status'] ) !== 'valid' && !array_key_exists( $id, $notices ) ) { ?>

                        <div class="notice notice-warning is-dismissible">
                            <p>
                                <?php _e( '<strong>' . $this->extensions[ $id ]['item'] . '</strong> is active but license has not been activated!' ); ?>
                                <a href="<?php menu_page_url( $this->page_args['menu_slug'] ); ?>"><?php _e( 'Activate now.' ); ?></a>
                            </p>
                        </div>

                    <?php }

                }

            }

        }

        /**
         * Deactivates expired licenses and sets the flag to notify the admin.
         *
         * @since 1.0.0
         */
        public function check_licenses() {

            $notices = get_option( "{$this->id}-extension-notices", array() );

            foreach ( $this->extensions as $id => $extension ) {

                $license_data = $this->get_license_data( $id );

                if( $license_data ) {

                    if( $license_data['license'] !== 'valid' ) {

                        delete_option( $extension['options']['status'] );
                        delete_option( $extension['options']['expiration'] );

                        if( !in_array( $id, $notices ) ) {
                            $notices[] = $id;
                        }

                    } else {

                        // Refresh the expiration date
                        update_option( $extension['options']['expiration'], $license_data['expires'] );

                    }

                }

            }

            update_option( "{$this->id}-extension-notices", $notices );

        }

        /**
         * Add an extension license to be managed.
         *
         * @param string $id          The ID of the extension.
         * @param string $store_url   The URL of the EDD Marketplace.
         * @param string $plugin_file The file of the extension plugin.
         * @param array  $option_keys {
         *
         *      The option keys where the license data will be stored.
         *
         *      string $license    The option to use to store the license key.
         *      string $status     The option to use to store the license status.
         *      string $expiration The option to use to store the license expiration date.
         * }
         * @param array  $edd_args EDD updater arguments. @see \EDD_SL_Plugin_Updater
         * @since 1.0.0
         */
        public function add_license( $id, $store_url, $plugin_file, array $option_keys, array $edd_args ) {

            if( !array_key_exists( $id, $this->extensions ) ) {

                $edd_args['license'] = trim( get_option( $option_keys['license'] ) );

                $this->extensions[ $id ] = array(
                    'updater' => new EDD_SL_Plugin_Updater( $store_url, $plugin_file, $edd_args ),
                    'options' => $option_keys,
                    'url'     => $store_url,
                    'item'    => $edd_args['item_name']
                );

            }

        }

        /**
         * Adds the menu page to the WordPress admin if there is at least 1 extension registered.
         *
         * @since 1.0.0
         */
        public function add_license_page() {

            if( !empty( $this->extensions ) ) {
                $this->hook = call_user_func_array( "add_{$this->page_type}_page", $this->parse_page_args() );
            }

        }

        /**
         * Registers license options with the Settings API and configures their sanitize callback.
         *
         * @since 1.0.0
         */
        public function register_settings() {

            foreach( $this->extensions as $extension ) {

                $args = array(
                    'type'              => 'string',
                    'sanitize_callback' => function ( $new ) use ( $extension ) {

                        if ( $new != get_option( $extension['options']['license'] ) ) {
                            delete_option( $extension['options']['status'] );
                            delete_option( $extension['options']['expiration'] );
                        }

                        return $new;

                    }
                );

                register_setting( "{$this->id}_extensions", $extension['options']['license'], $args );
            }

        }

        /**
         * Converts and organizes the associative $page_args array to a numerically indexed array for use with
         * add_{type}_page().
         *
         * @since 1.0.0
         * @return array The converted array.
         */
        private function parse_page_args() {

            $page_args = array();

            if( $this->page_type == 'submenu' ) {
                $page_args[] = $this->page_args['parent_slug'];
            }

            $common_args = array(
                'page_title',
                'menu_title',
                'capability',
                'menu_slug'
            );

            foreach( $common_args as $arg ) {
                if( isset( $this->page_args[ $arg ] ) ) {
                    $page_args[] = $this->page_args[ $arg ];
                }
            }

            $page_args[] = array( $this, 'do_license_page' );

            if( $this->page_type == 'menu_page' ) {
                $page_args[] = !empty( $this->page_args['icon_url'] ) ? $this->page_args['icon_url'] : '';
                $page_args[] = !empty( $this->page_args['position'] ) ? $this->page_args['position'] : null;
            }

            return $page_args;

        }

        /**
         * Handler for extension activation requests.
         *
         * @since 1.0.0
         */
        public function activate_license() {

            if ( isset( $_POST["{$this->id}_activate_license"] ) &&
                check_admin_referer( "{$this->id}_license_activation", $_POST["{$this->id}_activate_license"] . '_activation_nonce' ) ) {

                if ( !array_key_exists( $_POST["{$this->id}_activate_license"], $this->extensions ) ) {
                    return;
                }

                $extension = $this->extensions[ $_POST["{$this->id}_activate_license"] ];

                $api_params = array(
                    'edd_action' => 'activate_license',
                    'license'    => get_option( $extension['options']['license'] ),
                    'item_name'  => urlencode( $extension['item'] ),
                    'url'        => home_url()
                );

                $request = array(
                    'timeout'   => 15,
                    'sslverify' => false,
                    'body'      => $api_params
                );

                $response = wp_remote_post( $extension['url'], $request );

                if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

                    if ( is_wp_error( $response ) ) {
                        $message = $response->get_error_message();
                    } else {
                        $message = __( 'An error occurred, please try again.' );
                    }

                } else {

                    $license_data = json_decode( wp_remote_retrieve_body( $response ), true );

                    if ( false === $license_data['success'] ) {

                        switch( $license_data['error'] ) {

                            case 'expired' :
                                $message = sprintf(
                                    __( 'Your license key expired on %s.' ),
                                    date_i18n( get_option( 'date_format' ), strtotime( $license_data['expires'], current_time( 'timestamp' ) ) )
                                );

                                break;

                            case 'revoked' :
                                $message = __( 'Your license key has been disabled.' );

                                break;

                            case 'missing' :
                                $message = __( 'Invalid license.' );

                                break;

                            case 'invalid' :
                            case 'site_inactive' :
                                $message = __( 'Your license is not active for this URL.' );

                                break;

                            case 'item_name_mismatch' :
                                $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), $extension['item'] );

                                break;

                            case 'no_activations_left':
                                $message = __( 'Your license key has reached its activation limit.' );

                                break;

                            default :
                                $message = __( 'An error occurred, please try again.' );

                                break;
                        }

                    }

                    if( $license_data['license'] === 'valid' ) {

                        update_option( $extension['options']['status'], $license_data['license'] );
                        update_option( $extension['options']['expiration'], $license_data['expires'] );

                        $this->clear_expiration_notice( $_POST["{$this->id}_activate_license"] );

                    }

                }

                if ( !empty( $message ) ) {
                    add_settings_error( "{$this->id}_extensions", 'activation-error', $message );
                }

            }
        }

        /**
         * Handler for deactivation requests.
         *
         * @since 1.0.0
         */
        public function deactivate_license() {

            if ( isset(  $_POST["{$this->id}_deactivate_license"] ) &&
                check_admin_referer( "{$this->id}_license_deactivation", $_POST["{$this->id}_deactivate_license"] . '_deactivation_nonce' ) ) {

                if ( !array_key_exists( $_POST["{$this->id}_deactivate_license"], $this->extensions ) ) {
                    return;
                }

                $extension = $this->extensions[ $_POST["{$this->id}_deactivate_license"] ];

                $api_params = array(
                    'edd_action' => 'deactivate_license',
                    'license'    => get_option( $extension['options']['license'] ),
                    'item_name'  => urlencode( $extension['item'] ),
                    'url'        => home_url()
                );

                $request = array(
                    'timeout'   => 15,
                    'sslverify' => false,
                    'body'      => $api_params
                );

                $response = wp_remote_post( $extension['url'], $request );

                if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

                    if ( is_wp_error( $response ) ) {
                        $message = $response->get_error_message();
                    } else {
                        $message = __( 'An error occurred, please try again.' );
                    }

                    add_settings_error( "{$this->id}_extensions", 'deactivation-error', $message );

                } else {

                    $license_data = json_decode( wp_remote_retrieve_body( $response ), true );

                    if( $license_data['license'] == 'deactivated' ) {
                        delete_option( $extension['options']['status'] );
                        delete_option( $extension['options']['expiration'] );
                    }

                }

            }

        }

        /**
         * Retrieves the license data for an extension.
         *
         * @param string                   $id The ID of the extension.
         * @return array|bool|mixed|object
         * @since 1.0.0
         */
        public function get_license_data( $id ) {

            if( array_key_exists( $id, $this->extensions ) ) {

                $extension = $this->extensions[ $id ];

                $license = trim( get_option( $extension['options']['license'] ) );

                $api_params = array(
                    'edd_action'  => 'check_license',
                    'license'     => $license,
                    'item_name'   => urlencode( $extension['item'] ),
                    'url'         => home_url()
                );

                $request = array(
                    'timeout'   => 15,
                    'sslverify' => false,
                    'body'      => $api_params
                );

                $response = wp_remote_post( $extension['url'], $request );

                if ( is_wp_error( $response ) ) {
                    return false;
                }

                return json_decode( wp_remote_retrieve_body( $response ), true );
            }

            return false;

        }

        /**
         * Clears the expiration notification of an extension.
         *
         * @param string $id The ID of the extension.
         * @since 1.0.0
         */
        public function clear_expiration_notice( $id ) {

            $notices = get_option( "{$this->id}-extension-notices", array() );

            if( in_array( $id, $notices ) ) {
                unset( $notices[ array_search( $id, $notices ) ] );
            }

            update_option( "{$this->id}-extension-notices", $notices );

        }

        /**
         * Outputs the license management page.
         *
         * @since 1.0.0
         */
        public function do_license_page() {

            $count = 0;
            settings_errors( "{$this->id}_extensions" );  ?>

            <div class="wrap <?php esc_attr_e( "{$this->id}-licenses" ); ?> license-activation-page">

                <h2><?php _e( $this->page_args['page_title'] ); ?></h2>

                <form method="post" action="options.php">

                    <?php foreach ( $this->extensions as $id => $extension ) :

                        $this->do_license_field( $id, $extension );
                        $count++;

                        if ( $count == 3 ) :
                            $count = 0;
                            echo '<div class="clear"></div>';
                        endif;

                    endforeach;

                    settings_fields( "{$this->id}_extensions" ); ?>

                    <div class="clear"></div>

                    <?php submit_button(); ?>

                </form>

            </div>

        <?php }

        /**
         * Outputs a license management field for an extension.
         *
         * @param string $id        The ID of the extension.
         * @param array  $extension The extension data.
         * @since 1.0.0
         */
        public function do_license_field( $id, $extension ) {

            $key    = get_option( $extension['options']['license'] );
            $exp    = get_option( $extension['options']['expiration'] );
            $status = get_option( $extension['options']['status'] );

            ?>

            <div class="license-activation">

                <div class="inner">

                <h3><?php echo $extension['item']; ?></h3>

                <p>

                    <input class="license-key"
                           type="text"
                           name="<?php esc_attr_e( $extension['options']['license'] ); ?>"
                           value="<?php esc_attr_e( $key ); ?>" />

                    <?php if( !empty( $key ) ) : ?>

                        <?php if( $status === 'valid' ) :  ?>

                            <button class="button button-secondary"
                                    type="submit"
                                    name="<?php esc_attr_e( "{$this->id}_deactivate_license" ); ?>"
                                    value="<?php esc_attr_e( $id ); ?>"><?php _e( 'Deactivate License' ); ?></button>

                            <?php wp_nonce_field( "{$this->id}_license_deactivation", "{$id}_deactivation_nonce" ); ?>

                        <?php else : ?>

                            <button class="button button-secondary"
                                    type="submit"
                                    name="<?php esc_attr_e( "{$this->id}_activate_license" ); ?>"
                                    value="<?php esc_attr_e( $id ); ?>"><?php _e( 'Activate License' ); ?></button>


                            <?php wp_nonce_field( "{$this->id}_license_activation", "{$id}_activation_nonce" ); ?>

                        <?php endif; ?>

                    <?php else : ?>

                        <span class="description"><?php _e( 'Please enter your license key' ); ?></span>

                    <?php endif; ?>

                </p>

                <?php if( $exp ) : ?>

                    <div class="license-expiration">

                        <?php if( $exp !== 'lifetime' ) : ?>

                            <?php echo __( 'Your license key expires on ' ) . date_i18n( get_option( 'date_format' ), strtotime( $exp, current_time( 'timestamp' ) ) ); ?>

                        <?php else : ?>

                            <?php _e( 'This is a lifetime licence' ); ?>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

                </div>

            </div>


        <?php }


	    /**
	     * Print default styles for license page.
         *
         * @since 1.0.0
	     */
        public function print_styles() {

            if( $this->is_license_page() ) : ?>

                <style id="<?php esc_attr_e( "{$this->id}-license-management-styles" ); ?>">

                    .license-activation-page .submit {
                        margin-top: 0;
                    }

                    .license-activation {
                        background: #fff;
                        border: 1px solid #ddd;
                        margin: 10px 0;
                    }

                    .license-activation h3 {
                        margin: 0;
                        padding: 10px;
                        background: #f9f9f9;
                        border-bottom: 1px solid #ddd;
                    }

                    .license-activation p {
                        margin: 10px;
                    }

                    .license-activation .description {
                        margin-top: 5px;
                        display: block;
                    }

                    .license-activation input {
                        width: 100%;
                    }

                    .license-activation .button {
                        margin: 5px 0 !important;
                        width: 100%;
                    }

                    .license-activation .license-expiration {
                        padding: 10px 10px 20px 10px;
                        border-top: 1px solid #ddd;
                    }

                    @media( min-width: 600px ) {

                        .license-activation .button {
                            width: inherit;
                        }

                    }

                    @media( min-width: 783px ) {

                        .license-activation {
                            width: 30%;
                            float: left;
                            margin-right: 10px;
                        }

                    }

                </style>

            <?php endif;

        }

    }


endif;


/**
 * Allows plugins to use their own update API.
 *
 * @author Easy Digital Downloads
 * @version 1.6.12
 */
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) :

    class EDD_SL_Plugin_Updater {

        private $api_url     = '';
        private $api_data    = array();
        private $name        = '';
        private $slug        = '';
        private $version     = '';
        private $wp_override = false;
        private $cache_key   = '';

        /**
         * Class constructor.
         *
         * @uses plugin_basename()
         * @uses hook()
         *
         * @param string  $_api_url     The URL pointing to the custom API endpoint.
         * @param string  $_plugin_file Path to the plugin file.
         * @param array   $_api_data    Optional data to send with API calls.
         */
        public function __construct( $_api_url, $_plugin_file, $_api_data = null ) {

            global $edd_plugin_data;

            $this->api_url     = trailingslashit( $_api_url );
            $this->api_data    = $_api_data;
            $this->name        = plugin_basename( $_plugin_file );
            $this->slug        = basename( $_plugin_file, '.php' );
            $this->version     = $_api_data['version'];
            $this->wp_override = isset( $_api_data['wp_override'] ) ? (bool) $_api_data['wp_override'] : false;
            $this->beta        = ! empty( $this->api_data['beta'] ) ? true : false;
            $this->cache_key   = md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) );

            $edd_plugin_data[ $this->slug ] = $this->api_data;

            // Set up hooks.
            $this->init();

        }

        /**
         * Set up WordPress filters to hook into WP's update process.
         *
         * @uses add_filter()
         *
         * @return void
         */
        public function init() {

            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
            add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
            remove_action( 'after_plugin_row_' . $this->name, 'wp_plugin_update_row', 10 );
            add_action( 'after_plugin_row_' . $this->name, array( $this, 'show_update_notification' ), 10, 2 );
            add_action( 'admin_init', array( $this, 'show_changelog' ) );

        }

        /**
         * Check for Updates at the defined API endpoint and modify the update array.
         *
         * This function dives into the update API just when WordPress creates its update array,
         * then adds a custom API call and injects the custom plugin data retrieved from the API.
         * It is reassembled from parts of the native WordPress plugin update code.
         * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
         *
         * @uses api_request()
         *
         * @param array   $_transient_data Update array build by WordPress.
         * @return array Modified update array with custom plugin data.
         */
        public function check_update( $_transient_data ) {

            global $pagenow;

            if ( ! is_object( $_transient_data ) ) {
                $_transient_data = new stdClass;
            }

            if ( 'plugins.php' == $pagenow && is_multisite() ) {
                return $_transient_data;
            }

            if ( ! empty( $_transient_data->response ) && ! empty( $_transient_data->response[ $this->name ] ) && false === $this->wp_override ) {
                return $_transient_data;
            }

            $version_info = $this->get_cached_version_info();

            if ( false === $version_info ) {
                $version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug, 'beta' => $this->beta ) );

                $this->set_version_info_cache( $version_info );

            }

            if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->new_version ) ) {

                if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {

                    $_transient_data->response[ $this->name ] = $version_info;

                }

                $_transient_data->last_checked           = current_time( 'timestamp' );
                $_transient_data->checked[ $this->name ] = $this->version;

            }

            return $_transient_data;
        }

        /**
         * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
         *
         * @param string  $file
         * @param array   $plugin
         */
        public function show_update_notification( $file, $plugin ) {

            if ( is_network_admin() ) {
                return;
            }

            if( ! current_user_can( 'update_plugins' ) ) {
                return;
            }

            if( ! is_multisite() ) {
                return;
            }

            if ( $this->name != $file ) {
                return;
            }

            // Remove our filter on the site transient
            remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 10 );

            $update_cache = get_site_transient( 'update_plugins' );

            $update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();

            if ( empty( $update_cache->response ) || empty( $update_cache->response[ $this->name ] ) ) {

                $version_info = $this->get_cached_version_info();

                if ( false === $version_info ) {
                    $version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug, 'beta' => $this->beta ) );

                    $this->set_version_info_cache( $version_info );
                }

                if ( ! is_object( $version_info ) ) {
                    return;
                }

                if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {

                    $update_cache->response[ $this->name ] = $version_info;

                }

                $update_cache->last_checked = current_time( 'timestamp' );
                $update_cache->checked[ $this->name ] = $this->version;

                set_site_transient( 'update_plugins', $update_cache );

            } else {

                $version_info = $update_cache->response[ $this->name ];

            }

            // Restore our filter
            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );

            if ( ! empty( $update_cache->response[ $this->name ] ) && version_compare( $this->version, $version_info->new_version, '<' ) ) {

                // build a plugin list row, with update notification
                $wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
                # <tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange">
                echo '<tr class="plugin-update-tr" id="' . $this->slug . '-update" data-slug="' . $this->slug . '" data-plugin="' . $this->slug . '/' . $file . '">';
                echo '<td colspan="3" class="plugin-update colspanchange">';
                echo '<div class="update-message notice inline notice-warning notice-alt">';

                $changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911' );

                if ( empty( $version_info->download_link ) ) {
                    printf(
                        __( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s.', 'easy-digital-downloads' ),
                        esc_html( $version_info->name ),
                        '<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
                        esc_html( $version_info->new_version ),
                        '</a>'
                    );
                } else {
                    printf(
                        __( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s or %5$supdate now%6$s.', 'easy-digital-downloads' ),
                        esc_html( $version_info->name ),
                        '<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
                        esc_html( $version_info->new_version ),
                        '</a>',
                        '<a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) ) .'">',
                        '</a>'
                    );
                }

                do_action( "in_plugin_update_message-{$file}", $plugin, $version_info );

                echo '</div></td></tr>';
            }
        }

        /**
         * Updates information on the "View version x.x details" page with custom data.
         *
         * @uses api_request()
         *
         * @param mixed   $_data
         * @param string  $_action
         * @param object  $_args
         * @return object $_data
         */
        public function plugins_api_filter( $_data, $_action = '', $_args = null ) {

            if ( $_action != 'plugin_information' ) {

                return $_data;

            }

            if ( ! isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) {

                return $_data;

            }

            $to_send = array(
                'slug'   => $this->slug,
                'is_ssl' => is_ssl(),
                'fields' => array(
                    'banners' => array(),
                    'reviews' => false
                )
            );

            $cache_key = 'edd_api_request_' . md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) );

            // Get the transient where we store the api request for this plugin for 24 hours
            $edd_api_request_transient = $this->get_cached_version_info( $cache_key );

            //If we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
            if ( empty( $edd_api_request_transient ) ) {

                $api_response = $this->api_request( 'plugin_information', $to_send );

                // Expires in 3 hours
                $this->set_version_info_cache( $api_response, $cache_key );

                if ( false !== $api_response ) {
                    $_data = $api_response;
                }

            } else {
                $_data = $edd_api_request_transient;
            }

            // Convert sections into an associative array, since we're getting an object, but Core expects an array.
            if ( isset( $_data->sections ) && ! is_array( $_data->sections ) ) {
                $new_sections = array();
                foreach ( $_data->sections as $key => $value ) {
                    $new_sections[ $key ] = $value;
                }

                $_data->sections = $new_sections;
            }

            // Convert banners into an associative array, since we're getting an object, but Core expects an array.
            if ( isset( $_data->banners ) && ! is_array( $_data->banners ) ) {
                $new_banners = array();
                foreach ( $_data->banners as $key => $value ) {
                    $new_banners[ $key ] = $value;
                }

                $_data->banners = $new_banners;
            }

            return $_data;
        }

        /**
         * Disable SSL verification in order to prevent download update failures
         *
         * @param array   $args
         * @param string  $url
         * @return object $array
         */
        public function http_request_args( $args, $url ) {
            // If it is an https request and we are performing a package download, disable ssl verification
            if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
                $args['sslverify'] = false;
            }
            return $args;
        }

        /**
         * Calls the API and, if successfull, returns the object delivered by the API.
         *
         * @uses get_bloginfo()
         * @uses wp_remote_post()
         * @uses is_wp_error()
         *
         * @param string  $_action The requested action.
         * @param array   $_data   Parameters for the API action.
         * @return false|object
         */
        private function api_request( $_action, $_data ) {

            global $wp_version;

            $data = array_merge( $this->api_data, $_data );

            if ( $data['slug'] != $this->slug ) {
                return;
            }

            if( $this->api_url == trailingslashit (home_url() ) ) {
                return false; // Don't allow a plugin to ping itself
            }

            $api_params = array(
                'edd_action' => 'get_version',
                'license'    => ! empty( $data['license'] ) ? $data['license'] : '',
                'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
                'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
                'version'    => isset( $data['version'] ) ? $data['version'] : false,
                'slug'       => $data['slug'],
                'author'     => $data['author'],
                'url'        => home_url(),
                'beta'       => ! empty( $data['beta'] ),
            );

            $request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

            if ( ! is_wp_error( $request ) ) {
                $request = json_decode( wp_remote_retrieve_body( $request ) );
            }

            if ( $request && isset( $request->sections ) ) {
                $request->sections = maybe_unserialize( $request->sections );
            } else {
                $request = false;
            }

            if ( $request && isset( $request->banners ) ) {
                $request->banners = maybe_unserialize( $request->banners );
            }

            if( ! empty( $request->sections ) ) {
                foreach( $request->sections as $key => $section ) {
                    $request->$key = (array) $section;
                }
            }

            return $request;
        }

        public function show_changelog() {

            global $edd_plugin_data;

            if( empty( $_REQUEST['edd_sl_action'] ) || 'view_plugin_changelog' != $_REQUEST['edd_sl_action'] ) {
                return;
            }

            if( empty( $_REQUEST['plugin'] ) ) {
                return;
            }

            if( empty( $_REQUEST['slug'] ) ) {
                return;
            }

            if( ! current_user_can( 'update_plugins' ) ) {
                wp_die( __( 'You do not have permission to install plugin updates', 'easy-digital-downloads' ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );
            }

            $data         = $edd_plugin_data[ $_REQUEST['slug'] ];
            $beta         = ! empty( $data['beta'] ) ? true : false;
            $cache_key    = md5( 'edd_plugin_' . sanitize_key( $_REQUEST['plugin'] ) . '_' . $beta . '_version_info' );
            $version_info = $this->get_cached_version_info( $cache_key );

            if( false === $version_info ) {

                $api_params = array(
                    'edd_action' => 'get_version',
                    'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
                    'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
                    'slug'       => $_REQUEST['slug'],
                    'author'     => $data['author'],
                    'url'        => home_url(),
                    'beta'       => ! empty( $data['beta'] )
                );

                $request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

                if ( ! is_wp_error( $request ) ) {
                    $version_info = json_decode( wp_remote_retrieve_body( $request ) );
                }


                if ( ! empty( $version_info ) && isset( $version_info->sections ) ) {
                    $version_info->sections = maybe_unserialize( $version_info->sections );
                } else {
                    $version_info = false;
                }

                if( ! empty( $version_info ) ) {
                    foreach( $version_info->sections as $key => $section ) {
                        $version_info->$key = (array) $section;
                    }
                }

                $this->set_version_info_cache( $version_info, $cache_key );

            }

            if( ! empty( $version_info ) && isset( $version_info->sections['changelog'] ) ) {
                echo '<div style="background:#fff;padding:10px;">' . $version_info->sections['changelog'] . '</div>';
            }

            exit;
        }

        public function get_cached_version_info( $cache_key = '' ) {

            if( empty( $cache_key ) ) {
                $cache_key = $this->cache_key;
            }

            $cache = get_option( $cache_key );

            if( empty( $cache['timeout'] ) || current_time( 'timestamp' ) > $cache['timeout'] ) {
                return false; // Cache is expired
            }

            return json_decode( $cache['value'] );

        }

        public function set_version_info_cache( $value = '', $cache_key = '' ) {

            if( empty( $cache_key ) ) {
                $cache_key = $this->cache_key;
            }

            $data = array(
                'timeout' => strtotime( '+3 hours', current_time( 'timestamp' ) ),
                'value'   => json_encode( $value )
            );

            update_option( $cache_key, $data );

        }

    }

endif;
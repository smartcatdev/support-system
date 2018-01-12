<?php
/**
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

/**
 * Provides common framework for managing extension updates and a license management page.
 *
 * @version 1.0.0
 */
class LicenseManager {

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
                'updater' => new \EDD_SL_Plugin_Updater( $store_url, $plugin_file, $edd_args ),
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

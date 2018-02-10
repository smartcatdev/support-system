<?php
/**
 *
 * @since 1.6.1
 * @package ucare
 */
namespace ucare;


/**
 * Manage extension licenses.
 *
 * Class LicenseManager
 *
 * @package ucare
 */
final class LicenseManager {

    use Singleton;
    use Data;

    /**
     * Initialize the object instance.
     *
     * @return void
     * @since  1.4.2
     * @access protected
     */
    protected function initialize() {
        $this->add_hooks();
        $this->schedule_cron();

        // All done
        do_action( 'ucare_register_extensions', $this );
    }

    /**
     * Add hooks and filters.
     *
     * @since 1.6.1
     * @return void
     */
    private function add_hooks() {
        add_action( 'ucare_extensions_license_check', array( $this, 'check_licenses' ) );
    }

    /**
     * Add an extension license to be managed.
     *
     * @param string $id          The ID of the extension.
     * @param string $store_url   The URL of the EDD Marketplace.
     * @param string $plugin_file The file of the extension plugin.
     * @param array  $options     {
     *      The option keys where the license data will be stored.
     *
     *      string $license    The option to use to store the license key.
     *      string $status     The option to use to store the license status.
     *      string $expiration The option to use to store the license expiration date.
     * }
     * @param array  $edd_args    EDD updater arguments. @see \EDD_SL_Plugin_Updater
     *
     * @since 1.6.1
     * @return bool
     */
    public function add_license( $id, $store_url, $plugin_file, array $options, array $edd_args ) {
        if ( $this->get( $id ) ) {
            return false;
        }

        $edd_args['license'] = trim( get_option( $options['license'] ) );

        $data = array(
            'edd_updater' => new \EDD_SL_Plugin_Updater( $store_url, $plugin_file, $edd_args ),
            'store_url'   => $store_url,
            'options'     => $options,
            'item_name'   => $edd_args['item_name'],
            'plugin_file' => $plugin_file
        );

        $this->set( $id, $data );
        $this->register_license_settings( $options['license'],  $options['status'], $options['expiration'] );

        return true; // S'all good
    }

    /**
     * Register license settings keys with the Settings API
     *
     * @param $license
     * @param $status
     * @param $expiry
     *
     * @since 1.6.1
     * @return void
     */
    public function register_license_settings( $license, $status, $expiry ) {
        register_setting( 'ucare_extensions', $status, array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        register_setting( 'ucare_extensions', $expiry, array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        register_setting( 'ucare_extensions', $license, array(
            'type' => 'string',
            'sanitize_callback' => function ( $new ) use ( $license, $status, $expiry ) {
                if ( $new != get_option( $license ) ) {
                    delete_option( $status );
                    delete_option( $expiry );
                }

                return sanitize_title( $new );
            }
        ) );
    }

    /**
     * Activate a product license.
     *
     * @param string $id
     * @param string $key
     *
     * @since 1.6.1
     * @return bool|\WP_Error
     */
    public function activate_license( $id, $key = '' ) {
        $data = $this->get( $id, false );

        if ( !$data ) {
            return false;
        }

        if ( !empty( $key ) ) {
            update_option( $data['options']['license'], trim( $key ) ); // Cache the license used to activate
        }

        $license = $this->make_request( $id, 'activate_license' );

        if ( !$license || is_wp_error( $license ) ) {
            return $license;
        }

        if ( $license['success'] ) {
            update_option( $data['options']['status'],     pluck( $license, 'license' ) );
            update_option( $data['options']['expiration'], pluck( $license, 'expires' ) );

            $this->clear_expired_license( $id );

        } else {
            $message = __( 'An error has occurred. Please try again later.' );

            switch( $license['error'] ) {
                case 'expired' :
                    $message = sprintf(
                        __( 'Your license key expired on %s.' ),
                        date_i18n( get_option( 'date_format' ), strtotime( $license['expires'], current_time( 'timestamp' ) ) )
                    );

                    break;

                case 'revoked':
                    $message = __( 'Your license key has been disabled.' );
                    break;

                case 'missing':
                    $message = __( 'Invalid license.' );
                    break;

                case 'invalid':
                case 'site_inactive':
                    $message = __( 'Your license is not active for this URL.' );
                    break;

                case 'item_name_mismatch':
                    $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), $data['item_name'] );
                    break;

                case 'no_activations_left':
                    $message = __( 'Your license key has reached its activation limit.' );
                    break;
            }

            return new \WP_Error( $license['error'], $message );
        }

        return true;
    }

    /**
     * Deactivate a product license.
     *
     * @param string $id
     *
     * @since 1.6.1
     * @return bool|\WP_Error
     */
    public function deactivate_license( $id ) {
        $license = $this->make_request( $id, 'deactivate_license' );

        if ( !$license || is_wp_error( $license ) ) {
            return $license;
        }

        if ( $license['license'] !== 'deactivated' ) {
            return false;
        }

        $data = $this->get( $id );

        delete_option( $data['options']['status'] );
        delete_option( $data['options']['expiration'] );

        return true;
    }

    /**
     * Get licensing data for a product.
     *
     * @param string $id
     *
     * @since 1.6.1
     * @return array|false|\WP_Error
     */
    public function get_license( $id ) {
        $data = $this->get( $id );

        if ( !$data ) {
            return false;
        }

        return $this->make_request( $id, 'check_license' );
    }

    /**
     * Schedule license check cron jobs.
     *
     * @since 1.6.1
     * @return void
     */
    public function schedule_cron() {
        if ( !wp_next_scheduled( 'ucare_extensions_license_check' ) ) {
            wp_schedule_event( time(), 'daily', 'ucare_extensions_license_check' );
        }
    }

    /**
     * Cleanup Cron jobs and other misc data.
     *
     * @since 1.6.1
     * @return void
     */
    public function cleanup() {
        wp_clear_scheduled_hook( 'ucare_extensions_license_check' );
    }

    /**
     * Get a list of expired licenses.
     *
     * @since 1.6.1
     * @return array
     */
    public function get_expired_licenses() {
        return get_option( 'ucare_expired_licenses', array() );
    }

    /**
     * Get all registered extensions.
     *
     * @since 1.6.1
     * @return array
     */
    public function get_extensions() {
        return $this->data;
    }

    /**
     * Check and re-cache license expiration dates.
     *
     * @action ucare_extensions_license_check
     *
     * @since 1.6.1
     * @return void
     */
    public function check_licenses() {
        $expired = get_option( 'ucare_expired_licenses', array() );

        foreach ( $this->data as $id => $data ) {
            $license = $this->get_license( $id );

            if ( !$license || is_wp_error( $license ) ) {
                continue; // Skip failed requests
            }

            update_option( $data['options']['expiration'], $license['expires'] );

            if ( $license['license'] == 'valid' ) {
                continue; // Skip valid licenses
            }

            if ( !in_array( $id, $expired ) ) {
                $expired[] = $id;
            } else {
                unset( $expired[ array_search( $id, $expired ) ] );
            }
        }

        update_option( 'ucare_expired_licenses', $expired );
    }

    /**
     * Clear an expired license.
     *
     * @param string $id
     *
     * @since 1.6.1
     * @return void
     */
    private function clear_expired_license( $id ) {
        $expired = get_option( 'ucare_expired_licenses', array() );

        if ( !in_array( $id, $expired ) ) {
            return;
        }

        unset( $expired[ array_search( $id, $expired ) ] );

        update_option( 'ucare_expired_licenses', $expired );
    }

    /**
     * Make a request to an EDD API.
     *
     * @param string       $id
     * @param string       $action
     * @param string|array $overrides
     *
     * @since 1.6.1
     * @return mixed|\WP_Error
     */
    private function make_request( $id, $action, $overrides = '' ) {
        $data = $this->get( $id, false );

        if ( !$data ) {
            return false;
        }

        $api = array(
            'edd_action' => $action,
            'license'    => trim( get_option( $data['options']['license'] ) ),
            'item_name'  => urlencode( $data['item_name'] ),
            'url'        => home_url()
        );

        $request = array(
            'timeout'   => 15,
            'sslverify' => false,
            'body'      => wp_parse_args( $overrides, $api )
        );

        $response = wp_remote_post( $data['store_url'], $request );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return new \WP_Error( 'error', __( 'An error has occurred. Please try again later.', 'ucare' ) );
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

}

<?php

namespace ucare;


function init_extension_licensing() {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    foreach( $plugin->get_activations() as $id => $edd_options ) {

        $sanitize = function ( $new ) use ( $edd_options ) {
            $old = get_option( $edd_options['license_option'] );

            if( $old && $old != $new ) {
                delete_option( $edd_options['license_option'] );
            }

            return $new;
        };

        register_setting( 'ucare_extension_licenses', $edd_options['license_option'], $sanitize );

        $edd_updater = new \EDD_SL_Plugin_Updater(
            $edd_options['store_url'],
            $edd_options['support_file'],
            array(
                'version'   => $edd_options['version'],
                'license'   => get_option( $edd_options['license_option'] ),
                'item_name' => $edd_options['item_name'],
                'author'    => $edd_options['author'],
                'beta'      => $edd_options['beta']
            )
        );

    }

}

add_action( 'admin_init', 'ucare\init_extension_licensing' );


function activate_license() {

    if ( isset( $_POST['ucare_activate_extension_license'] ) &&
            check_admin_referer( 'ucare_extension_activation', 'ucare_extension_nonce' ) ) {

        $plugin = Plugin::get_plugin( PLUGIN_ID );
        $activations = $plugin->get_activations();

        if ( !isset( $activations[ $_POST['ucare_activate_extension_license'] ] ) ) {
            return;
        } else {
            $activation = $activations[ $_POST['ucare_activate_extension_license'] ];
        }

        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => get_option( $activation['license_option'] ),
            'item_name'  => urlencode( $activation['item_name'] ),
            'url'        => home_url()
        );

        $request = array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params );
        $response = wp_remote_post( $activation['store_url'], $request );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.', 'ucare' );
            }

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( false === $license_data['success'] ) {

                switch( $license_data['error'] ) {

                    case 'expired' :
                        $message = sprintf(
                            __( 'Your license key expired on %s.', 'ucare' ),
                            date_i18n( get_option( 'date_format' ), strtotime( $license_data['expires'], current_time( 'timestamp' ) ) )
                        );

                        break;

                    case 'revoked' :
                        $message = __( 'Your license key has been disabled.', 'ucare' );

                        break;

                    case 'missing' :
                        $message = __( 'Invalid license.', 'ucare' );

                        break;

                    case 'invalid' :
                    case 'site_inactive' :
                        $message = __( 'Your license is not active for this URL.', 'ucare' );

                        break;

                    case 'item_name_mismatch' :
                        $message = sprintf(
                            __( 'This appears to be an invalid license key for %s.', 'ucare' ),
                            $activation['plugin_info']['item_name']
                        );

                        break;

                    case 'no_activations_left':
                        $message = __( 'Your license key has reached its activation limit.', 'ucare' );

                        break;

                    default :
                        $message = __( 'An error occurred, please try again.', 'ucare' );

                        break;
                }

            }

            update_option( $activation['status_option'], $license_data['license'] );
            update_option( $activation['expire_option'], $license_data['expires'] );

        }

        if ( !empty( $message ) ) {
            add_settings_error( 'ucare_extension_license', 'activation-error', $message );
        }

    }

}

add_action( 'admin_init', 'ucare\activate_license' );


function deactivate_license() {

    if ( isset( $_POST['ucare_deactivate_extension_license'] ) &&
        check_admin_referer( 'ucare_extension_deactivation', 'ucare_extension_nonce' ) ) {

        $plugin = Plugin::get_plugin( PLUGIN_ID );
        $activations = $plugin->get_activations();

        if ( !isset( $activations[ $_POST['ucare_deactivate_extension_license'] ] ) ) {
            return;
        } else {
            $activation = $activations[ $_POST['ucare_deactivate_extension_license'] ];
        }

        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license'    => get_option( $activation['license_option'] ),
            'item_name'  => urlencode( $activation['item_name'] ),
            'url'        => home_url()
        );

        $request = array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params );
        $response = wp_remote_post( $activation['store_url'], $request );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.', 'ucare' );
            }

            add_settings_error( 'ucare_extension_license', 'deactivation-error', $message );

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ), true );

            if( $license_data['license'] == 'deactivated' ) {
                delete_option( $activation['status_option'] );
                delete_option( $activation['expire_option'] );
            }

        }

    }

}

add_action( 'admin_init', 'ucare\deactivate_license' );


function get_license( $id ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );
    $activations = $plugin->get_activations();

    if( array_key_exists( $id, $activations ) ) {

        $activation = $activations[ $id ];
        $license = trim( get_option( $activation['license_option'] ) );

        $api_params = array(
            'edd_action'  => 'check_license',
            'license'     => $license,
            'item_name'   => urlencode( $activation['item_name'] ),
            'url'         => home_url()
        );

        $request = array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params );
        $response = wp_remote_post( $activation['store_url'], $request );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );

    }

}

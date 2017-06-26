<?php

namespace ucare;

$plugin = Plugin::get_plugin( PLUGIN_ID );
$activations = $plugin->get_activations();

settings_errors( 'ucare_extension_license' );

?>

<div class="wrap">

    <h2><?php _e( 'Add-on Licenses', 'ucare' ); ?></h2>

        <form method="post" action="options.php">

            <?php settings_fields( 'ucare_extension_licenses' ); ?>

            <?php foreach ( $activations as $id => $activation ) : ?>

                <div class="ucare-extension-activation">

                    <h3><?php echo $activation['item_name']; ?></h3>

                    <p>

                        <input class="ucare-license-key"
                               type="text"
                               name="<?php echo $activation['license_option']; ?>"
                               value="<?php esc_attr_e( get_option( $activation['license_option'] ) ); ?>" />

                        <?php if( !empty( get_option( $activation['license_option'] ) ) ) : ?>

                            <?php if( get_option( $activation['status_option'] ) === 'valid' ) :  ?>

                                <button class="button button-secondary"
                                        type="submit"
                                        name="ucare_deactivate_extension_license"
                                        value="<?php esc_attr_e( $id ); ?>"><?php _e( 'Deactivate License', 'ucare' ); ?></button>

                                <?php wp_nonce_field( 'ucare_extension_deactivation', $id . '_deactivation_nonce' ); ?>

                            <?php else : ?>

                                <button class="button button-secondary"
                                        type="submit"
                                        name="ucare_activate_extension_license"
                                        value="<?php esc_attr_e( $id ); ?>"><?php _e( 'Activate License', 'ucare' ); ?></button>


                                <?php wp_nonce_field( 'ucare_extension_activation', $id . '_activation_nonce' ); ?>

                            <?php endif; ?>

                        <?php else : ?>

                            <span class="description"><?php _e( 'Please enter your license key', 'ucare' ); ?></span>

                        <?php endif; ?>

                    </p>

                    <?php $expires = get_option( $activation['expire_option'] ); ?>

                    <?php if( $expires ) : ?>

                        <div class="license-expiration">

                            <?php echo __( 'Your license key expires on ', 'ucare' ) . date_i18n( get_option( 'date_format' ), strtotime( $expires, current_time( 'timestamp' ) ) ); ?>

                        </div>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <div class="clear"></div>

            <?php submit_button(); ?>

        </form>

</div>

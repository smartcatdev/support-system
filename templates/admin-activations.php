<?php

namespace ucare;

$plugin = Plugin::get_plugin( PLUGIN_ID );
$activations = $plugin->get_activations();

?>

<div class="wrap">

    <h2><?php _e( 'Add-on Licenses', 'ucare' ); ?></h2>

        <form method="post" action="options.php">

            <?php settings_fields( 'ucare_extension_licenses' ); ?>

            <?php foreach ( $activations as $id => $activation ) : ?>

                <div class="extension-activation">

                    <h3><?php echo $activation['plugin_info']['item_name']; ?></h3>

                    <input class="ucare_extension_key"
                           type="text"
                           name="<?php echo $activation['license_option']; ?>"
                           value="<?php esc_attr_e( $activation['plugin_info']['license'] ); ?>" />

                    <?php if( !empty( $activation['plugin_info']['license'] ) ) : ?>

                        <button class="button button-secondary"
                                type="submit"
                                name="deactivate_extension_license"
                                value="<?php esc_attr_e( $id ); ?>"><?php _e( 'Deactivate License', 'ucare' ); ?></button>

                        <?php wp_nonce_field( 'ucare_extension_deactivation', 'ucare_extension_nonce' ); ?>

                    <?php else : ?>

                        <button class="button button-secondary"
                                type="submit"
                                name="activate_extension_license"
                                value="<?php esc_attr_e( $id ); ?>"><?php _e( 'Activate License', 'ucare' ); ?></button>


                        <?php wp_nonce_field( 'ucare_extension_activation', 'ucare_extension_nonce' ); ?>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <?php submit_button(); ?>

        </form>

</div>

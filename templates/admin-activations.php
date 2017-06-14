<?php

namespace ucare;

$license = get_option( 'edd_sample_license_key' );
$status  = get_option( 'edd_sample_license_status' );

?>

<div class="wrap">

    <h2><?php _e( 'Add-on Licenses', 'ucare' ); ?></h2>

    <form method="post" action="options.php">

<!--        --><?php //settings_fields('edd_sample_license'); ?>

        <table class="form-table">

            <tbody>

                <tr valign="top">
                    <th scope="row" valign="top"><?php _e('License Key'); ?></th>
                    <td>
                        <input type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                    </td>
                </tr>

                <?php if ( false !== $license ) : ?>

                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Activate License'); ?>
                        </th>
                        <td>

                            <?php if( $status !== false && $status == 'valid' ) : ?>

                                <span style="color:green;"><?php _e('active'); ?></span>
                                <?php wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
                                <input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>

                            <?php else :

                                wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
                                <input type="submit" class="button-secondary" name="edd_license_activate" value="<?php _e('Activate License'); ?>"/>

                            <?php endif;  ?>

                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

        <?php submit_button(); ?>

    </form>
</div>

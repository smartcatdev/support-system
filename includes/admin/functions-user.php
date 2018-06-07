<?php
/**
 * Functions for managing WordPress users.
 *
 * @package ucare
 * @since 1.6.0
 */
namespace ucare;

// Output user TOS date
add_action( 'edit_user_profile', 'ucare\edit_user_tos_date' );

// Filter support user caps
add_filter( 'user_has_cap', 'ucare\revoke_user_media_perms', 10, 4 );


/**
 * Filter support user capabilities.
 *
 * @action user_has_cap
 *
 * @param $all
 * @param $caps
 * @param $args
 * @param $user
 *
 * @since 1.6.0
 * @return array
 */
function revoke_user_media_perms( $all, $caps, $args, $user ) {
    if ( $args[0] !== 'upload_files' ) {
        return $all;
    }

    if ( array_key_exists( 'use_support', $all ) && array_key_exists( 'manage_site_media', $all ) ) {
        $all['upload_files'] = false;
    }

    return $all;
}

/**
 * Output the date when the user accepted the TOS agreement
 *
 * @action edit_user_profile
 *
 * @param \WP_User $user
 *
 * @since 1.7.0
 * @return void
 */
function edit_user_tos_date( $user ) {
    if ( !get_option( Options::ENFORCE_TOS ) ) {
        return;
    }
    $time = get_user_meta( $user->ID, 'ucare_tos_accepted', true ); ?>
    <table class="form-table">
        <tr>
            <th>
                <label for="ucare-tos"><?php _e( 'TOS Agreement Date', 'ucare' ); ?></label>
            </th>
            <td>
                <input type="text"
                       class="regular-text"
                       readonly="readonly"
                       id="ucare-tos"
                       value="<?php esc_attr_e( date_i18n( get_option( 'date_format' ), $time ) ); ?>"
                    />
                <p class="description">
                    <?php _e( 'The date when the support system TOS was accepted', 'text-domain' ); ?>
                </p>
            </td>
        </tr>
    </table>
<?php }
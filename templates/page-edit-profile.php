<?php
/**
 * Template for the page where users can edit their profile info.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

$user = wp_get_current_user();

?>

<?php get_header(); ?>

    <div class="container">

        <h1><?php the_title(); ?></h1>

        <div id="message-area"></div>

        <div class="row">

            <form id="edit-user-profile-form">

                <div class="form-group">

                    <label for="first-name">
                        <?php _e( 'First Name', 'ucare' ); ?>
                    </label>

                    <input id="first-name"
                           name="first_name"
                           type="text"
                           class="form-control required"
                           value="<?php esc_html_e( $user->first_name ); ?>" />

                </div>

                <div class="form-group">

                    <label for="last-name">
                        <?php _e( 'Last Name', 'ucare' ); ?>
                    </label>

                    <input id="last-name"
                           name="last_name"
                           type="text"
                           class="form-control required"
                           value="<?php esc_html_e( $user->last_name ); ?>" />

                </div>

                <hr>

                <div class="form-group">

                    <label for="new-password">
                        <?php _e( 'New Password', 'ucare' ); ?>
                    </label>

                    <input id="new-password"
                           name="password"
                           type="password"
                           class="form-control" />

                </div>

                <div class="form-group has-feedback">

                    <label for="confirm-password">
                        <?php _e( 'Confirm Password', 'ucare' ); ?>
                    </label>

                    <input id="confirm-password"
                           type="password"
                           class="form-control" />

                </div>

                <br>

                <div class="form-group text-right">

                    <button id="save-settings" type="button" class="button button-submit">
                        <span class="glyphicon glyphicon-floppy-save button-icon"></span><span><?php _e( 'Save', 'ucare' ); ?></span>
                    </button>

                </div>

            </form>

        </div>

    </div>

<?php get_footer(); ?>

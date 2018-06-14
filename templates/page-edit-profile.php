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

<?php ucare_get_header(); ?>

    <div class="container">

        <h1><?php the_title(); ?></h1>

        <div id="message-area">

            <?php if ( get_var( 'updated', false ) ) : ?>

                <div class="alert alert-success alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php _e( 'Profile successfully updated', 'ucare' ); ?>
                </div>

            <?php endif; ?>

        </div>

        <div class="row">

            <form id="edit-profile-form">

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

                    <input id="password"
                           name="password"
                           type="password"
                           class="form-control pw-input" />

                </div>

                <div class="form-group has-feedback">

                    <label for="confirm-password">
                        <?php _e( 'Confirm Password', 'ucare' ); ?>
                    </label>

                    <input id="confirm"
                           type="password"
                           class="form-control pw-input" />

                    <span class="glyphicon glyphicon-ok form-control-feedback hidden"></span>

                </div>

                <br>

                <div id="edit-profile-controls" class="form-group">

                     <div class="text-right controls">

                        <button id="submit" class="button button-submit">
                            <span class="glyphicon glyphicon-floppy-save button-icon"></span> <?php _e( 'Save', 'ucare' ); ?>
                        </button>

                    </div>

                    <?php if ( wp_supports_gdpr() ) : ?>

                        <?php $export_request = get_user_request_data( 'export_personal_data' ); ?>
                        <?php $remove_request = get_user_request_data( 'remove_personal_data' ); ?>

                        <div class="text-left controls">

                            <?php if ( empty( $export_request ) ) : ?>

                                <button id="request-data-export" class="button button-submit" data-action="export_personal_data">
                                    <span class="glyphicon glyphicon-export button-icon"></span> <?php _e( 'Request My Data', 'ucare' ); ?>
                                </button>

                            <?php endif; ?>

                            <?php if ( empty( $remove_request ) ) : ?>

                                <button id="request-data-erase" class="button button-submit" data-action="remove_personal_data">
                                    <span class="glyphicon glyphicon-trash button-icon"></span> <?php _e( 'Delete My Data', 'ucare' ); ?>
                                </button>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </form>

        </div>

    </div>

<?php ucare_get_footer(); ?>

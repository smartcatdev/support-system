<?php
/**
 * Template for the registration form.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

?>

<!-- register -->
<div id="register">

    <a class="btn btn-default button-back" href="<?php esc_url_e( login_page_url() ); ?>">

        <span class="glyphicon glyphicon-chevron-left button-icon"></span>

        <span><?php _e( 'Login', 'ucare' ); ?></span>

    </a>

    <div id="message-area"></div>

    <form id="registration-form">

        <div class="form-group">

            <label for="first-name">
                <?php _e( 'First Name', 'ucare' ); ?>
            </label>

            <input type="text"
                   id="first-name"
                   name="first_name"
                   class="form-control" required />

        </div>

        <div class="form-group">

            <label for="last-name">
                <?php _e( 'Last Name', 'ucare' ); ?>
            </label>

            <input type="text"
                   id="last-name"
                   name="last_name"
                   class="form-control" required />

        </div>

        <div class="form-group">

            <label for="email-address">
                <?php _e( 'Email Address', 'ucare' ); ?>
            </label>

            <input type="email"
                   id="email-address"
                   name="email"
                   class="form-control" required />

        </div>


        <?php do_action( 'ucare_after_registration_fields' ); ?>


        <div class="text-right registration-submit">

            <button id="registration-submit" type="submit" class="button button-primary">
                <?php echo stripslashes( get_option( Options::REGISTER_BTN_TEXT ) ); ?>
            </button>

        </div>

        <div class="terms">

            <a href="<?php esc_url_e( get_option( Options::TERMS_URL ) ); ?>">
                <?php echo stripslashes( get_option( Options::LOGIN_DISCLAIMER ) ); ?>
            </a>

        </div>

    </form>

</div><!-- /register -->
<?php
/**
 * Template for the registration form.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

?>

<div id="ucare-login">

    <div id="ucare-login-notice" style="display: none">
        <div class="inner"></div> <button class="dismiss"></button>
    </div>

    <?php if ( !is_user_logged_in() ) : ?>

        <form id="login-step-email" class="ucare-login-screen ucare-flex-col" data-step="email">

            <h2 class="login-title"><?php esc_html_e( $login_title ); ?></h2>

            <p class="login-subtitle">
                <?php esc_html_e( $login_subtext ); ?>
            </p>

            <p class="ucare-flex-row">
                <input type="text"
                       name="email"
                       id="login-email"
                       required="required"
                       placeholder="<?php _e( 'Email Address', 'ucare' ); ?>"
                    />
                <button class="button login-submit"><?php _e( 'Continue', 'ucare' ); ?></button>
            </p>

        </form>

        <?php if ( get_option( Options::ENFORCE_TOS ) ) : ?>

            <form id="login-step-terms" class="ucare-login-screen" style="display: none" data-step="tos">

                <h2 class="login-title"><?php esc_html_e( $tos_title ); ?></h2>

                <div class="tos-content">

                    <p><?php esc_html_e( get_option( Options::TOS_POLICY ) ); ?></p>

                    <p class="ucare-flex-row hcenter">
                        <button id="terms-accept"  class="button terms" value="accept"  type="submit"><?php _e( 'Accept', 'ucare' ); ?></button>
                        <button id="terms-decline" class="button terms" value="decline" type="submit"><?php _e( 'Decline', 'ucare' ); ?></button>
                    </p>

                </div>

            </form>

        <?php endif; ?>

        <?php if ( get_option( Options::ALLOW_SIGNUPS ) ) : ?>

            <form id="login-step-register" class="ucare-login-screen" style="display: none" data-step="register">

                <h2 class="login-title"><?php esc_html_e( $registration_title ); ?></h2>

                <p><?php esc_html_e( $registration_subtext ); ?></p>

                <p>
                    <label for="login-first-name"><?php _e( 'First Name', 'ucare' ); ?></label>
                    <input id="login-first-name"
                           type="text"
                           required="required"
                           name="first_name"
                        />
                </p>

                <p>
                    <label for="login-last-name"><?php _e( 'Last Name', 'ucare' ); ?></label>
                    <input id="login-last-name"
                           type="text"
                           required="required"
                           name="last_name"
                        />
                </p>

                <p class="text-right">
                    <button class="button login-submit" type="submit"><?php _e( 'Continue', 'ucare' ); ?></button>
                </p>

            </form>

        <?php endif; ?>

        <form id="login-step-password" class="ucare-login-screen" style="display: none" data-step="password">

            <h2 class="login-title"><?php _e( 'Password', 'ucare' ); ?></h2>

            <p>

                <span class="ucare-flex-row vcenter">
                    <input id="login-password"
                           type="password"
                           name="pwd"
                           placeholder="<?php _e( 'Password', 'ucare' ); ?>"
                        />
                    <button class="button login-submit"><?php _e( 'Continue', 'ucare' ); ?></button>
                </span>

                <label class="ucare-flex-row vcenter login-remember">
                    <input id="login-rememberme"
                           name="rememberme"
                           type="checkbox"
                        /><?php _e( 'Keep me signed in', 'ucare' ); ?>
                </label>

            </p>

        </form>

    <?php do_action( 'ucare_login_form' ); ?>

    <?php else : ?>

        <h2 class="login-title"><?php esc_html_e( $login_title ); ?></h2>
        <p class="login-subtitle"><?php esc_html_e( $login_subtext ); ?></p>

        <p>
            <a class="button" href="<?php echo esc_url( support_page_url() ); ?>"><?php _e( 'Get Support', 'ucare' ); ?></a>
        </p>

    <?php endif; ?>

</div>
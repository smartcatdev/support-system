<?php
/**
 * Functions for handling the login flow
 *
 * @since 1.7.0
 * @package ucare
 */
namespace ucare;

/**
 * Output the login form
 *
 * @since 1.7.0
 * @return void
 */
function login_form() {
    wp_enqueue_script( 'ucare-login' );
    wp_enqueue_style( 'ucare-login' );
    ?>
    <div id="ucare-login">

        <div id="ucare-login-notice" style="display: none">
            <div class="inner">
            </div>
            <div class="dismiss-wrap">
                <span class="dismiss"></span>
            </div>
        </div>

        <?php if ( !is_user_logged_in() ) : ?>

            <form id="login-step-email" class="ucare-login-screen ucare-flex-col" data-step="email">
                <h2 class="login-title"><?php _e( 'Get Support', 'ucare' ); ?></h2>
                <p class="login-subtitle">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In posuere arcu sed rutrum dignissim. Nulla a euismod erat. Nullam orci nulla, faucibus a lobortis ac, luctus et tortor. Donec vitae dictum elit, sed facilisis erat.
                </p>
                <div class="ucare-flex-row vcenter">
                    <input type="email"
                           name="email"
                           id="login-email"
                           required="required"
                           placeholder="<?php _e( 'Email Address', 'ucare' ); ?>"
                    />
                    <button class="button login-submit"><?php _e( 'Continue', 'ucare' ); ?></button>
                </div>
                <input value="email"
                       name="step"
                       type="hidden"
                    />
            </form>
            <form id="login-step-terms" class="ucare-login-screen" style="display: none" data-step="terms">
                <h2 class="login-title"><?php _e( 'Terms of Service', 'ucare' ); ?></h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In posuere arcu sed rutrum dignissim. Nulla a euismod erat. Nullam orci nulla, faucibus a lobortis ac, luctus et tortor. Donec vitae dictum elit, sed facilisis erat.
                </p>
                <p class="ucare-flex-row hcenter">
                    <button id="terms-accept"  class="button terms" value="accept"  type="submit"><?php _e( 'Accept', 'ucare' ); ?></button>
                    <button id="terms-decline" class="button terms" value="decline" type="submit"><?php _e( 'Decline', 'ucare' ); ?></button>
                </p>
                <input value="email"
                       name="step"
                       type="hidden"
                />
            </form>
            <form id="login-step-profile" class="ucare-login-screen" style="display: none" data-step="profile">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In posuere arcu sed rutrum dignissim. Nulla a euismod erat. Nullam orci nulla, faucibus a lobortis ac, luctus et tortor. Donec vitae dictum elit, sed facilisis erat.
                </p>
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
                    <button class="button login-submit"><?php _e( 'Continue', 'ucare' ); ?></button>
                </p>
                <input value="profile"
                       name="step"
                       type="hidden"
                    />
            </form>
            <form id="login-step-password" class="ucare-login-screen" style="display: none" data-step="password">
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
                               name="remember"
                               type="checkbox"
                        /><?php _e( 'Keep me signed in', 'ucare' ); ?>
                    </label>
                </p>
                <input value="password"
                       name="step"
                       type="hidden"
                    />
            </form>

        <?php else : ?>

            <h2 class="login-title"><?php _e( 'Get Support', 'ucare' ); ?></h2>
            <p class="login-subtitle">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. In posuere arcu sed rutrum dignissim. Nulla a euismod erat. Nullam orci nulla, faucibus a lobortis ac, luctus et tortor. Donec vitae dictum elit, sed facilisis erat.
            </p>
            <a class="button" href="<?php echo esc_url( support_page_url() ); ?>"><?php _e( 'Get Support', 'ucare' ); ?></a>

        <?php endif; ?>

    </div><?php
}
add_shortcode( 'ucare-login', 'ucare\login_form' );
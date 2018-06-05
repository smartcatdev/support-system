<?php
/**
 * @deprecated
 */
namespace ucare;


$allow_registration = get_option( Options::ALLOW_SIGNUPS, Defaults::ALLOW_SIGNUPS );

$form_args = array(
    'form_id'            => 'support_login',
    'redirect'           => support_page_url(),
    'label_password'     => $label_password,
    'label_username'     => $label_username,
    'label_remember'     => $label_remember,
    'label_log_in'       => $label_log_in,
    'id_username'        => $id_username,
    'id_password'        => $id_password,
    'id_remember'        => $id_remember,
    'id_submit'          => $id_submit,
    'value_username'     => $value_username,
    'value_remember'     => sanitize_boolean( $value_remember ),
    'register_link_text' => $register_link_text,
    'show_register_link' => sanitize_boolean( $show_register_link )
);

?>

<div id="<?php esc_attr_e( $form_id ); ?>"
     class="support-login-wrapper <?php esc_attr_e( $form_class ); ?> <?php echo $allow_registration ? 'has-registration' : ''; ?>">

    <?php if ( !is_user_logged_in() ) : ?>
        
        <h3><?php echo esc_attr( $form_title ); ?></h3>
    
        <?php wp_login_form( $form_args ); ?>

        <?php if ( sanitize_boolean( $show_pw_reset_link ) ) : ?>

            <a href="<?php esc_url_e( login_page_url( '?reset_password=true' ) ); ?>">
                <?php esc_html_e( $pw_reset_link_text ); ?>
            </a>

        <?php endif; ?>

    <?php else : ?>

        <a href="<?php echo esc_url( support_page_url() ); ?>">
            <?php esc_html_e( $logged_in_link_text ); ?>
        </a>

    <?php endif; ?>

</div>

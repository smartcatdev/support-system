<?php
/**
 * Functions for managing the email template post type.
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


// Add metaboxes
add_action( 'add_meta_boxes_email_template', 'ucare\add_email_template_meta_boxes' );

// Save metabox
add_action( 'save_post','ucare\save_email_template_metabox', 10, 2 );


/**
 * Add email template meta boxes.
 *
 * @action add_email_template_meta_boxes
 *
 * @since 1.6.0
 * @return void
 */
function add_email_template_meta_boxes() {
    add_meta_box( 'ucare-styles', __( 'Stylesheet', 'ucare' ), fqn( 'do_email_template_metabox' ) );
}


/**
 * Output stylesheet metabox.
 *
 * @since 1.6.0
 * @return void
 */
function do_email_template_metabox() { ?>
    <textarea rows="25"
              style="width: 100%"
              name="template_styles"><?php esc_html_e( get_metadata( 'styles' ) ); ?></textarea>
    <?php wp_nonce_field( 'save_template_styles', 'styles_nonce' ); ?>
<?php }


/**
 * Save the email template metabox.
 *
 * @action save_post
 *
 * @param $post_id
 * @param $post
 *
 * @since 1.6.0
 * @return void
 */
function save_email_template_metabox( $post_id, $post ) {
    if ( $post->post_type !== 'email_template' ) {
        return;
    }

    if ( ucare_check_nonce( 'save_template_styles', 'styles_nonce', false ) ) {
        update_post_meta( $post_id, 'styles', $_POST['template_styles'] );
    }
}
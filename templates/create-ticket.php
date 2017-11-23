<?php
/**
 * Page template for creating new support tickets.
 *
 * @since 1.5.0
 * @package ucare
 */
namespace ucare;

$draft = get_user_draft_ticket();

?>

<?php get_header(); ?>

    <div class="container">

        <h1><?php the_title(); ?></h1>

        <div class="row">

            <form id="create-ticket-form" data-id="<?php esc_attr_e( $draft->ID ); ?>">

                <div class="form-group">

                    <label>
                        <input type="checkbox"> <?php _e( 'Set ticket author', 'ucare' ); ?>
                    </label>

                </div>

                <div class="form-group">

                    <?php render_create_ticket_users_dropdown( $draft ); ?>

                </div>

                <div class="form-group">

                    <?php render_create_ticket_categories_dropdown( $draft ); ?>

                </div>

                <div class="form-group">

                    <?php render_create_ticket_products_dropdown( $draft ); ?>

                </div>

                <div class="input-group">

                    <span class="input-group-addon" id="basic-addon1">

                    </span>

                    <input type="text"
                           class="form-control"
                           name="meta[receipt_id]"
                           placeholder="<?php _e( 'Receipt #', 'ucare' ); ?>"
                           value="<?php esc_attr_e( get_post_meta( $draft->ID, 'receipt_id', true ) ); ?>">

                </div>

                <div class="input-group">

                    <span class="input-group-addon" id="basic-addon1">

                    </span>

                    <input type="text"
                           class="form-control"
                           name="title"
                           value="<?php esc_html_e( $draft->post_title ); ?>"
                           placeholder="<?php _e( 'Subject', 'ucare' ); ?>">

                </div>

                <div class="form-group">

                    <textarea class="form-control"
                              placeholder="<?php _e( 'Description', 'ucare' ); ?>"
                              name="content"><?php echo wp_kses_post( $draft->post_content ); ?></textarea>

                </div>

                <div class="form-group text-right">
                    <button class="button button-default"><?php _e( 'Create Ticket', 'ucare' ); ?></button>
                </div>

                <!-- User draft ticket -->
                <input type="hidden" name="status" value="publish">

            </form>

            <form id="dropzone">


                <!-- User draft ticket ID -->
                <input type="hidden" name="id" value="<?php esc_attr_e( $draft->ID ); ?>">

            </form>

        </div>

    </div>

<?php get_footer(); ?>

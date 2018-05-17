<?php
/**
 * Page template for creating new support tickets.
 *
 * @since 1.5.0
 * @package ucare
 */
namespace ucare;

$draft = get_user_draft_ticket( true );

?>

<?php ucare_get_header(); ?>

    <div class="container">

        <?php do_action( 'ucare_before_title' ); ?>

        <h1><?php the_title(); ?></h1>

        <?php do_action( 'ucare_after_title' ); ?>

        <div id="message-area"></div>

        <?php do_action( 'ucare_after_messages' ); ?>

        <div class="row">

            <?php while ( $draft->have_posts() ) : $draft->the_post(); ?>

                <form id="create-ticket-form" data-id="<?php esc_attr_e( get_the_ID() ); ?>">

                    <?php if ( get_option( Options::CATEGORIES_ENABLED ) ) : ?>

                        <div id="ticket-category" class="form-group">

                            <label for="category">
                                <?php _e( 'Category', 'ucare' ); ?>
                            </label>

                            <?php render_create_ticket_categories_dropdown( get_post() ); ?>

                        </div>

                        <?php do_action( 'ucare_general_fields' ); ?>

                        <hr>

                    <?php endif; ?>

                    <?php if ( ucare_is_ecommerce_enabled() ) : $mode = ucare_ecommerce_mode(); ?>

                        <?php if ( in_array( $mode, array( 'woo', 'edd' ) ) ) : ?>

                            <div id="product" class="form-group">

                                <label for="product">
                                    <?php _e( 'Product', 'ucare' ); ?>
                                </label>

                                <?php render_create_ticket_products_dropdown( get_post() ); ?>

                            </div>

                        <?php endif; ?>

                        <div id="receipt-number" class="form-group">

                            <label for="receipt_id">
                                <?php esc_html_e( get_option( Options::RECEIPT_ID_LABEL ) ); ?>
                            </label>

                            <input type="text"
                                   id="receipt_id"
                                   class="form-control"
                                   name="meta[receipt_id]"
                                   value="<?php esc_attr_e( get_post_meta( get_the_ID(), 'receipt_id', true ) ); ?>" />

                        </div>

                        <?php do_action( 'ucare_ecommerce_fields' ); ?>

                        <hr>

                    <?php endif; ?>


                    <div id="title" class="form-group">

                        <label for="subject"><?php _e( 'Subject', 'ucare' ); ?></label>

                        <input id="subject"
                               type="text"
                               class="form-control"
                               name="title"
                               value="<?php esc_html_e( get_the_title() ); ?>" />

                    </div>

                    <div id="content" class="form-group">

                        <label for="description"><?php _e( 'Description', 'ucare' ); ?></label>

                        <textarea id="description"
                                  rows="6"
                                  class="form-control"
                                  name="content"><?php echo wp_kses_post( get_post()->post_content ); ?></textarea>

                    </div>


                    <?php if ( ucare_is_support_agent() ) : ?>

                        <div id="assign-author">

                            <div class="form-group">

                                <label>
                                    <input type="checkbox" id="set-author"
                                        <?php checked( true, get_the_author() !== get_current_user_id() ); ?>>

                                    <?php _e( 'Set ticket author', 'ucare' ); ?>
                                </label>

                            </div>

                            <div id="author-select" class="form-group"
                                <?php echo get_the_author() == get_current_user_id() ? 'style="display: none"' : ''; ?>>

                                <?php render_create_ticket_users_dropdown( get_post() ); ?>

                            </div>

                            <input id="current-user"
                                   type="hidden"
                                   name="author"
                                   value="<?php esc_attr_e( get_current_user_id() ); ?>"
                                <?php disabled( true, get_the_author() == get_current_user_id() ); ?>>

                        </div>

                    <?php endif; ?>

                    <?php do_action( 'ucare_ticket_fields' ); ?>

                </form>

                <hr>

                <?php do_action( 'ucare_before_dropzone' ); ?>

                <div id="dropzone">

                    <div>
                        <label><?php _e( 'Attach Files', 'ucare' ); ?></label>
                    </div>

                    <form id="ticket-media" class="dropzone" method="post" enctype="multipart/form-data">

                        <!-- User draft ticket -->
                        <input type="hidden" name="post" value="<?php esc_attr_e( get_the_ID() ); ?>">
                        <input type="hidden" name="support_ticket_media" value="true">

                    </form>

                </div>

                <?php do_action( 'ucare_after_dropzone' ); ?>

                <br>

                <?php do_action( 'ucare_before_submit' ); ?>

                <div class="form-group text-right">

                    <input id="submit"
                           type="button"
                           class="button button-default"
                           value="<?php _e( 'Create Ticket', 'ucare' ); ?>" />

                </div>

                <?php do_action( 'ucare_create_ticket_page' ); ?>

            <?php endwhile; ?>

            <?php wp_reset_postdata(); ?>

        </div>

    </div>

<?php ucare_get_footer(); ?>

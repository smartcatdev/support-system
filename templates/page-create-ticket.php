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

        <div id="message-area"></div>

        <div class="row">

            <form id="create-ticket-form" data-id="<?php esc_attr_e( $draft->ID ); ?>">


                <?php if ( get_option( Options::CATEGORIES_ENABLED ) ) : ?>

                    <div id="ticket-category" class="form-group">

                        <label for="category">
                            <?php _e( 'Category', 'ucare' ); ?>
                        </label>

                        <?php render_create_ticket_categories_dropdown( $draft ); ?>

                    </div>

                    <hr>

                <?php endif; ?>


                <?php if ( ucare_is_ecommerce_enabled() ) : $mode = ucare_ecommerce_mode(); ?>


                    <?php if ( in_array( $mode, array( 'woo', 'edd' ) ) ) : ?>

                        <div id="product" class="form-group">

                            <label for="product">
                                <?php _e( 'Product', 'ucare' ); ?>
                            </label>

                            <?php render_create_ticket_products_dropdown( $draft ); ?>

                        </div>

                    <?php endif; ?>


                    <div id="receipt-number" class="form-group">

                        <label for="receipt_id"><?php _e( 'Receipt #', 'ucare' ); ?></label>

                        <input type="text"
                               id="receipt_id"
                               class="form-control"
                               name="meta[receipt_id]"
                               value="<?php esc_attr_e( get_post_meta( $draft->ID, 'receipt_id', true ) ); ?>" />

                    </div>

                    <hr>


                <?php endif; ?>


                <div id="title" class="form-group">

                    <label for="subject"><?php _e( 'Subject', 'ucare' ); ?></label>

                    <input id="subject"
                           type="text"
                           class="form-control"
                           name="title"
                           value="<?php esc_html_e( $draft->post_title ); ?>" />

                </div>

                <div id="content" class="form-group">

                    <label for="description"><?php _e( 'Description', 'ucare' ); ?></label>

                    <textarea id="description"
                              rows="6"
                              class="form-control"
                              name="content"><?php echo wp_kses_post( $draft->post_content ); ?></textarea>

                </div>


                <?php if ( ucare_is_support_agent() ) : ?>

                    <div id="assign-author">

                        <div class="form-group">

                            <label>
                                <input type="checkbox" id="set-author"
                                    <?php checked( true, (int) $draft->post_author !== get_current_user_id() ); ?>>

                                <?php _e( 'Set ticket author', 'ucare' ); ?>
                            </label>

                        </div>

                        <div id="author-select" class="form-group"
                            <?php echo $draft->post_author == get_current_user_id() ? 'style="display: none"' : ''; ?>>

                            <?php render_create_ticket_users_dropdown( $draft ); ?>

                        </div>

                        <input id="current-user"
                               type="hidden"
                               name="author"
                               value="<?php esc_attr_e( get_current_user_id() ); ?>"
                            <?php disabled( true, $draft->post_author == get_current_user_id() ); ?>>

                    </div>

                <?php endif; ?>

            </form>

            <hr>

            <div id="dropzone">

                <div>
                    <label><?php _e( 'Attach Files', 'ucare' ); ?></label>
                </div>

                <form id="ticket-media" class="dropzone" method="post" enctype="multipart/form-data">

                    <!-- User draft ticket -->
                    <input type="hidden" name="post" value="<?php esc_attr_e( $draft->ID ); ?>">
                    <input type="hidden" name="support_ticket_media" value="true">

                </form>

            </div>

            <br>

            <div class="form-group text-right">
                <button id="submit" class="button button-default"><?php _e( 'Create Ticket', 'ucare' ); ?></button>
            </div>

        </div>

    </div>

<?php get_footer(); ?>

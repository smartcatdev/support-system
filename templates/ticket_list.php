<?php

namespace ucare;

?>

<?php if ( empty( $query->posts ) ) : ?>

    <div class="row">

        <p class="text-center text-muted"><?php _e( get_option( Options::EMPTY_TABLE_MSG, \ucare\Defaults::EMPTY_TABLE_MSG ), 'ucare' ); ?></p>

    </div>

<?php else : ?>

    <div id="the-tickets" class="ticket-list col-sm-12">

        <div class="list-group">

            <?php foreach( $query->posts as $post ) : ?>

                <?php $status = get_post_meta( $post->ID, 'status', true ); ?>
                <?php $statuses = \ucare\util\statuses(); ?>

                <div id="ticket-<?php esc_attr_e( $post->ID ); ?>" class="list-group-item ticket <?php esc_attr_e( $status ); ?>">

                    <div class="media">

                        <div class="media-left item-status">

                            <div class="status-wrapper">

                                <a href="#" data-toggle="tooltip" title="<?php ticket_status( $post->ID ); ?>">
                                    <span class="ticket-status <?php esc_attr_e( get_metadata( 'status', true, $post->ID ) ); ?>"></span>
                                </a>

                            </div>

                            <input type="checkbox" name="bulk_item_selected" value="<?php esc_attr_e( $post->ID ); ?>" />

                        </div>

                        <div class="media-body">

                            <div>

                                <?php $products = \ucare\util\products(); ?>
                                <?php $product = get_post_meta( $post->ID, 'product', true ); ?>

                                <a class="open-ticket" data-id="<?php echo $post->ID; ?>">

                                    <h4 class="ticket-title"><?php echo $post->post_title; ?></h4>

                                </a>

                                <?php $terms = get_the_terms( $post, 'ticket_category' ); ?>

                                <?php if( !empty( $terms ) ) : ?>

                                    <span class="tag category"><?php echo $terms[0]->name; ?></span>

                                <?php endif; ?>

                                <?php if( array_key_exists( $product, $products ) ) : ?>

                                    <span class="tag"><?php echo $products[ $product ]; ?></span>

                                <?php endif; ?>

                                <div class="text-muted">

                                    #<?php echo $post->ID; ?> opened by <?php echo get_the_author_meta( 'display_name', $post->post_author ); ?>

                                    <a class="ticket-email" href="#"><?php echo \ucare\util\author_email( $post ); ?></a>

                                </div>

                            </div>

                        </div>

                        <div class="media-right">

                            <div class="indicators pull-right">

                                <?php $assigned_to = get_post_meta( $post->ID, 'agent', true ); ?>

                                <?php if( !empty( $assigned_to ) ) : ?>

                                    <div class="indicator">

                                        <?php echo get_avatar( get_user_by( 'id', $assigned_to ), 28, '', '', array( 'class' => 'img-circle' ) ); ?>

                                    </div>

                                <?php endif; ?>

                                <?php if( get_post_meta( $post->ID, 'stale', true ) ) : ?>

                                    <div class="indicator">

                                        <span class="glyphicon glyphicon-time"></span>

                                    </div>

                                <?php endif; ?>

                                <?php $attachments = count( get_attached_media( 'image', $post->ID ) ); ?>

                                <?php if( $attachments > 0 ) : ?>

                                    <div class="indicator">

                                        <span class="glyphicon glyphicon-paperclip"></span>

                                    </div>

                                <?php endif; ?>

                                <?php if( $post->comment_count > 0 ) : ?>

                                    <div class="indicator">

                                        <span class="glyphicon glyphicon-comment comment-icon"></span>

                                    </div>

                                <?php endif; ?>

                                <?php if( current_user_can( 'manage_support_tickets' ) ) : ?>

                                    <div class="indicator">

                                        <span data-id="<?php echo $post->ID; ?>" class="<?php echo get_post_meta( $post->ID, 'flagged', true ) === 'on' ? 'active' : ''; ?> text-muted glyphicon glyphicon-flag flagged"></span>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

            <?php if( $query->max_num_pages > 0 ) : ?>

                <div class="list-group-item bottom text-right">

                    <ul class="pagination">

                        <?php for( $ctr = 0; $ctr < $query->max_num_pages; $ctr++ ): ?>

                            <?php $page = $ctr + 1; ?>

                            <li class="<?php echo ( $query->query['paged'] == $page ? 'active' : '' ); ?>">

                                <a class="page" href="#" data-id="<?php echo $page; ?>"><?php echo $page; ?></a>

                            </li>

                        <?php endfor; ?>

                    </ul>

                </div>

            <?php endif; ?>

        </div>

    </div>

<?php endif; ?>
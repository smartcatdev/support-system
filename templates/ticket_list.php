<?php

use ucare\descriptor\Option;

?>

<?php if ( empty( $query->posts ) ) : ?>

    <div class="row">

        <p class="text-center text-muted"><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ), 'ucare' ); ?></p>

    </div>

<?php else : ?>

    <div class="ticket-list col-sm-12">

        <div class="list-group">

            <?php foreach( $query->posts as $post ) : ?>

                <?php $status = get_post_meta( $post->ID, 'status', true ); ?>
                <?php $statuses = \ucare\util\statuses(); ?>

                <div class="list-group-item ticket <?php echo $status; ?>">

                    <div class="media">

                        <div class="media-left">

                            <div class="status-wrapper">

                                <span class="ticket-status <?php echo $status; ?>"></span>

                                <?php if( array_key_exists( $status, $statuses ) ) : ?>

                                    <span class="status-tooltip"><?php _e( $statuses[ $status ], 'ucare' ); ?></span>

                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="media-body">

                            <div>

                                <?php $products = \ucare\util\products(); ?>
                                <?php $product = get_post_meta( $post->ID, 'product', true ); ?>

                                <a class="open-ticket" data-id="<?php echo $post->ID; ?>">

                                    <h4 class="ticket-title"><?php echo $post->post_title; ?></h4>

                                </a>

                                <?php if( array_key_exists( $product, $products ) ) : ?>

                                    <span class="product"><?php echo $products[ $product ]; ?></span>

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

            <?php if( !empty( $query->posts ) ) : ?>

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
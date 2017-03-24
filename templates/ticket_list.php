<?php

use SmartcatSupport\descriptor\Option;

?>

<?php if ( empty( $query->posts ) ) : ?>

    <div class="row-table first-create-ticket">

        <div class="row-table-cell">

            <div class="text-center">

                <div class="row">

                    <p><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ), \SmartcatSupport\PLUGIN_ID ); ?></p>

                </div>

            </div>

        </div>

    </div>

<?php else : ?>

    <div class="ticket-list col-sm-12">

        <div class="list-group">

            <?php foreach( $query->posts as $post ) : ?>

                <?php $status = get_post_meta( $post->ID, 'status', true ); ?>
                <?php $statuses = \SmartcatSupport\util\statuses(); ?>

                <div class="list-group-item ticket <?php echo $status; ?>">

                    <div class="media">

                        <div class="media-left">

                            <div class="status-wrapper">

                                <span class="ticket-status <?php echo $status; ?>"></span>

                                <?php if( array_key_exists( $status, $statuses ) ) : ?>

                                    <span class="status-tooltip"><?php _e( $statuses[ $status ], \SmartcatSupport\PLUGIN_ID ); ?></span>

                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="media-body">

                            <div>

                                <?php $products = \SmartcatSupport\util\products(); ?>
                                <?php $product = get_post_meta( $post->ID, 'product', true ); ?>

                                <a class="open-ticket" href="#" data-id="<?php echo $post->ID; ?>">

                                    <h4 class="ticket-title"><?php echo $post->post_title; ?></h4>

                                </a>

                                <?php if( array_key_exists( $product, $products ) ) : ?>

                                    <span class="product"><?php echo $products[ $product ]; ?></span>

                                <?php endif; ?>

                                <div class="text-muted">

                                    #<?php echo $post->ID; ?> opened by <?php echo get_the_author_meta( 'display_name', $post->post_author ); ?>

                                    <a class="ticket-email" href="#"><?php echo \SmartcatSupport\util\author_email( $post ); ?></a>

                                </div>

                            </div>

                        </div>

                        <div class="media-right">

                            <div class="indicators pull-right">

                                <?php $attachments = count( get_attached_media( 'image', $post->ID ) ); ?>

                                <?php if( $attachments > 0 ) : ?>

                                    <div class="indicator">

                                        <span class="glyphicon glyphicon-paperclip"></span>

                                    </div>

                                <?php endif; ?>

                                <div class="indicator">

                                    <span class="glyphicon glyphicon-comment comment-icon"></span>

                                    <span class="comment-count-badge" data-count="<?php echo $post->comment_count;?>"></span>

                                </div>

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
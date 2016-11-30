<?php

use function SmartcatSupport\convert_html_chars;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="support_ticket">

    <div class="ticket support_card" data-id="<?php esc_attr_e( $post->ID ); ?>">

        <div class="status_bar">

            <div class="image_wrapper">

                <?php echo get_avatar( $post, 48 ); ?>

            </div>

            <div class="meta_wrapper">

                <p class="author_name"><?php esc_html_e( get_the_author_meta( 'display_name', $post->post_author ) ); ?></p>

                <p class="date_posted">

                    <?php _e( 'Updated ', TEXT_DOMAIN ); ?>
                    <?php _e( human_time_diff( strtotime( $post->post_date ), current_time( 'timestamp' ) ) . ' ago', TEXT_DOMAIN ); ?>

                    <?php if ( current_user_can( 'edit_others_tickets' ) ) : ?>

                        by

                        <span class="author_name">

                        <?php esc_html_e( get_the_author_meta( 'display_name', get_post_meta( $post->ID, '_edit_last', true ) ) ); ?>

                    </span>

                    <?php endif; ?>

                </p>

            </div>

            <?php if( current_user_can( 'edit_others_tickets' ) ) : ?>

                <div class="actions">

                    <a href="<?php echo admin_url( 'admin-ajax.php' ) . '?action=support_edit_ticket&id=' . $post->ID; ?>"
                       rel="modal:open">

                        <i class="icon-pencil"></i>

                    </a>

                </div>

            <?php endif; ?>

        </div>

        <div class="inner">

            <h2 class="subject"><?php esc_html_e( $post->post_title ); ?></h2>

            <div class="content"><?php echo $post->post_content; ?></div>

            <div class="meta">

                <table>

                    <tr>

                        <th class="label"><?php _e( 'Product', TEXT_DOMAIN ); ?></th>

                        <td><?php echo get_products()[ get_post_meta(  $post->ID, 'product', true ) ]; ?></td>

                    </tr>

                    <tr>

                        <th class="label"><?php _e( 'Status', TEXT_DOMAIN ); ?></th>

                        <td><?php echo get_option( Option::STATUSES, Option\Defaults::STATUSES ) [ get_post_meta( $post->ID, 'status', true ) ]; ?></td>

                    </tr>

                    <?php if( current_user_can( 'edit_others_tickets' ) ) : ?>

                        <tr>

                            <th class="label"><?php _e( 'Assigned to', TEXT_DOMAIN ); ?></th>

                            <td><?php echo get_agents() [ get_post_meta( $post->ID, 'agent', true ) ]; ?></td>

                        </tr>

                        <tr>

                            <th class="label"><?php _e( 'Priority', TEXT_DOMAIN ); ?></th>

                            <td><?php echo get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES ) [ get_post_meta( $post->ID, 'priority', true ) ]; ?></td>

                        </tr>

                    <?php endif; ?>

                </table>

            </div>


        </div>

        <div class="date_opened">

            <?php _e( 'Opened ', TEXT_DOMAIN ); echo get_the_date( 'l F n, Y', $post ); ?>

        </div>

</div>

<?php

use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use const SmartcatSupport\PLUGIN_ID;
use SmartcatSupport\util\UserUtils;

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

                    <?php _e( 'Updated ', PLUGIN_ID ); ?>
                    <?php _e( human_time_diff( strtotime( $post->post_date ), current_time( 'timestamp' ) ) . ' ago', PLUGIN_ID ); ?>

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

                        <th class="label"><?php _e( 'Product', PLUGIN_ID ); ?></th>

                        <td>
                            <?php $products = apply_filters( 'support_list_products', array( '' => '' ) ); ?>
                            <?php echo $products[ get_post_meta(  $post->ID, 'product', true ) ]; ?>
                        </td>

                    </tr>

                    <tr>

                        <th class="label"><?php _e( 'Status', PLUGIN_ID ); ?></th>

                        <td>
                            <?php $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES ); ?>
                            <?php echo $statuses[ get_post_meta( $post->ID, 'status', true ) ]; ?>
                        </td>

                    </tr>

                    <?php if( current_user_can( 'edit_others_tickets' ) ) : ?>

                        <tr>

                            <th class="label"><?php _e( 'Assigned to', PLUGIN_ID ); ?></th>

                            <td>
                                <?php $agents = UserUtils::list_agents(); ?>
                                <?php echo $agents[ get_post_meta( $post->ID, 'agent', true ) ]; ?>
                            </td>

                        </tr>

                        <tr>

                            <th class="label"><?php _e( 'Priority', PLUGIN_ID ); ?></th>

                            <td>
                                <?php $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES ); ?>
                                <?php echo $priorities[ get_post_meta( $post->ID, 'priority', true ) ]; ?>
                            </td>

                        </tr>

                    <?php endif; ?>

                </table>

            </div>


        </div>

        <div class="date_opened">

            <?php _e( 'Opened ', PLUGIN_ID ); echo get_the_date( 'l F n, Y', $post ); ?>

        </div>

</div>

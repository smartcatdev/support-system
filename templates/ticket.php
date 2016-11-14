<?php

use function SmartcatSupport\api\convert_html_specialchars;
use const SmartcatSupport\TEXT_DOMAIN;

?>

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

        <div class="actions_wrapper">

            <div class="actions">

                <span class="action icon-pencil" data-action="edit_ticket"></span>

            </div>

        </div>

    </div>

    <div class="inner">

        <div class="read_only">

            <h2 class="subject"><?php esc_html_e( $post->post_title ); ?></h2>

            <div class="content"><?php echo convert_html_specialchars( $post->post_content ); ?></div>

            <div class="meta">

                <table class="collapsible">

                    <?php foreach( $meta as $name => $value ) : ?>

                        <tr>

                            <th class="label">

                                <?php esc_html_e( $name ); ?>

                            </th>

                            <td>

                                <?php esc_html_e( $value ); ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>

            </div>

        </div>

    </div>

    <div class="date_opened">

        <?php _e( 'Opened ', TEXT_DOMAIN ); echo get_the_date( 'l F n, Y', $post->ID ) ; ?>

    </div>

</div>

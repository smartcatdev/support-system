<?php

use function SmartcatSupport\api\convert_html_specialchars;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="ticket root" data-id="<?php esc_attr_e( $post->ID ); ?>">

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

    <div class="details">

        <h2><?php esc_html_e( $post->post_title ); ?></h2>

        <p><?php echo convert_html_specialchars( $post->post_content ); ?></p>

        <table>

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

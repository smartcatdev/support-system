<?php use function SmartcatSupport\api\convert_html_specialchars;
use const SmartcatSupport\TEXT_DOMAIN; ?>

<div class="comment root" data-id="<?php esc_attr_e( $comment->comment_ID ); ?>">

    <div class="status_bar">

        <div class="image_wrapper">

            <?php echo get_avatar( $comment, 36 ); ?>

        </div>

        <div class="meta_wrapper">

            <p class="author_name"><?php esc_html_e( $comment->comment_author ); ?></p>

            <p class="date_posted">

                <?php _e( human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) . ' ago', TEXT_DOMAIN ); ?>

            </p>

        </div>

        <div class="actions_wrapper">

            <div class="actions">

                <?php if( $comment->user_id == wp_get_current_user()->ID ) : ?>

                    <span class="action icon-pencil" data-action="edit_comment"></span>

                <?php endif; ?>

            </div>

        </div>

    </div>

    <div class="content"><?php echo $comment->comment_content; ?></div>

</div>

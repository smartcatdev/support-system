<?php use function SmartcatSupport\api\convert_html_specialchars;
use const SmartcatSupport\TEXT_DOMAIN; ?>

<div class="comment">

    <div class="details">

        <div class="image_wrapper">

            <?php echo get_avatar( $comment, 36 ); ?>

        </div>

        <div class="meta_wrapper">

            <p class="author_name"><?php esc_html_e( $comment->comment_author ); ?></p>

            <p class="date_posted">

                <?php _e( human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) . ' ago', TEXT_DOMAIN ); ?>

            </p>

        </div>

    </div>

    <div class="content"><?php echo convert_html_specialchars( $comment->comment_content ); ?></div>

</div>

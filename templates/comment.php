<?php use function SmartcatSupport\api\filter_code_from_text; ?>

<div class="comment">

    <div class="details">

        <div class="image-wrapper">

            <?php echo get_avatar( $comment, 48 ); ?>

        </div>

        <div class="meta-wrapper">

            <p class="author_name"><?php esc_html_e( $comment->comment_author ); ?></p>

            <p class="date_posted"><?php esc_html_e( $comment->comment_date ); ?></p>

        </div>

    </div>

    <div class="clear"></div>

    <div class="content"><?php echo filter_code_from_text( $comment->comment_content ); ?></div>

</div>

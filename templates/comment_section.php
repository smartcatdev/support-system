<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="comment_section">

    <div class="comments">

        <?php if ( !empty( $comments ) ) : ?>

            <?php foreach ( $comments as $comment ) : ?>

                <?php include 'comment.php'; ?>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <div class="comment_reply support_card">

        <div class="status_bar">

            <div class="wrapper">

                <div class="image_wrapper">

                    <?php echo get_avatar( wp_get_current_user()->ID, 36 ); ?>

                </div>

                <div class="meta_wrapper">

                    <p class="author_name"><?php esc_html_e( wp_get_current_user()->display_name ); ?></p>

                </div>

            </div>

        </div>

        <div class="inner">

            <?php include 'comment_form.php' ?>

        </div>

    </div>

</div>

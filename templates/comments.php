<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TicketUtils;

$comments_enabled = TicketUtils::comments_enabled( $post->ID );

$ticket_status = get_post_meta( $post->ID, 'status', true );
$comments_closed = $ticket_status === 'resolved' || $ticket_status === 'closed';

?>

    <?php if( !empty( $comments ) ) : ?>

        <?php foreach ( $comments as $comment ) : ?>

            <?php include Plugin::plugin_dir( Plugin::ID ) . '/templates/comment.php'; ?>

        <?php endforeach; ?>

    <?php endif; ?>





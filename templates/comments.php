<?php

use ucare\Plugin;

?>

<?php foreach( $comments as $comment ) : ?>

    <?php include Plugin::plugin_dir( \ucare\PLUGIN_ID ) . '/templates/comment.php'; ?>

<?php endforeach; ?>

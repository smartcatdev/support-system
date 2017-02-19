<?php

use SmartcatSupport\Plugin;

?>

<?php foreach( $comments as $comment ) : ?>

    <?php include Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '\templates\comment.php'; ?>

<?php endforeach; ?>

<?php include_once 'header.php'; ?>

<?php if( is_user_logged_in() && current_user_can( 'view_support_tickets' ) ) : ?>

    <?php include_once 'navbar.php'; ?>
    <?php include_once 'dash.php'; ?>

<?php else : ?>

    <?php include_once 'login.php'; ?>

<?php endif; ?>

<?php include_once 'footer.php'; ?>
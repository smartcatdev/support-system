<?php include_once 'header.php'; ?>

<?php if( is_user_logged_in() && current_user_can( 'use_support' ) ) : ?>

    <?php if( empty( get_user_meta( wp_get_current_user()->ID, 'first_login', true ) ) ) : ?>
        
        <?php include_once 'first_login.php'; ?>

        <?php do_action( 'support_first_login' ); ?>

        <?php //update_user_meta( wp_get_current_user()->ID, 'first_login', true ); ?>

    <?php endif; ?>

    <div id="page-container">

        <?php include_once 'navbar.php'; ?>
        <?php include_once 'dash.php'; ?>

<?php else : ?>

    <?php include_once 'login.php'; ?>

<?php endif; ?>

    <?php include_once 'footer.php'; ?>

</div>

<?php include_once 'header.php'; ?>

<?php if( is_user_logged_in() && current_user_can( 'use_support' ) ) : ?>

    <?php if( empty( get_user_meta( wp_get_current_user()->ID, 'first_login', true ) ) ) : ?>
        
        <?php include_once 'first_login.php'; ?>

        <?php do_action( 'ucare_first_login' ); ?>

        <?php update_user_meta( wp_get_current_user()->ID, 'first_login', true ); ?>

    <?php endif; ?>

    <div id="page-container">
        
        <?php do_action( 'ucare_before_navbar' ); ?>
        <?php include_once 'navbar.php'; ?>
        <?php do_action( 'ucare_after_navbar' ); ?>
        
        <?php include_once 'dash.php'; ?>

<?php else : ?>

    <?php include_once 'login.php'; ?>

<?php endif; ?>

    <?php include_once 'footer.php'; ?>

</div>

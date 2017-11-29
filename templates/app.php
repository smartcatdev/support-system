<?php

namespace ucare;

?>

<?php get_template( 'header' ); ?>

<?php if( is_user_logged_in() && current_user_can( 'use_support' ) ) : ?>

    <?php if( empty( get_user_meta( wp_get_current_user()->ID, 'first_login', true ) ) ) : ?>

        <?php get_template( 'first_login' ); ?>

        <?php do_action( 'ucare_first_login' ); ?>

        <?php update_user_meta( wp_get_current_user()->ID, 'first_login', true ); ?>

    <?php endif; ?>

        <?php do_action( 'ucare_before_navbar' ); ?>

        <?php get_template( 'navbar' ); ?>

        <?php do_action( 'ucare_after_navbar' ); ?>

        <?php get_template( 'dash.php' ); ?>

<?php else : ?>

    <?php get_template( 'login.php' ); ?>

<?php endif; ?>

<?php get_template( 'footer.php' ); ?>

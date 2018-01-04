<?php

namespace ucare;

?>

<?php ucare_get_header(); ?>

<?php if( is_user_logged_in() && current_user_can( 'use_support' ) ) : ?>

    <?php if( empty( get_user_meta( wp_get_current_user()->ID, 'first_login', true ) ) ) : ?>

        <?php get_template( 'first_login' ); ?>

        <?php do_action( 'ucare_first_login' ); ?>

        <?php update_user_meta( wp_get_current_user()->ID, 'first_login', true ); ?>

    <?php endif; ?>

        <?php get_template( 'dash.php' ); ?>

<?php endif; ?>

<?php ucare_get_footer(); ?>

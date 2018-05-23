<?php
/**
 * Application header template.
 *
 * @since 1.0.0
 * @package ucare
 */
namespace ucare;

?>

<html><!-- Start html -->

    <head>

        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="<?php _e( 'uCare Support System', 'ucare' ); ?>" />

        <link href="<?php echo get_option( Options::FAVICON ); ?>" rel="icon">

        <?php ucare_head(); ?>

    </head>

    <body><!-- Start body -->

        <div id="page-container"><!-- Start Page Container -->

            <?php if ( !is_page_public() ) : ?>
                <?php get_navbar(); ?>
            <?php endif; ?>

            <?php do_action( 'ucare_body' ); ?>

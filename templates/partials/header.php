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

        <?php print_styles(); ?>
        <?php print_header_scripts(); ?>

        <link href="<?php echo get_option( Options::FAVICON ); ?>" rel="icon">

        <?php get_template( 'dynamic-styles' ); ?>

        <?php do_action( 'ucare_head' ); ?>

    </head>

    <body><!-- Start body -->

        <div id="page-container"><!-- Start Page Container -->

        <?php do_action( 'ucare_body' ); ?>

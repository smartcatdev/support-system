<?php

use ucare\Options;
use ucare\Plugin;

$url = Plugin::plugin_url( \ucare\PLUGIN_ID );
$ver = get_option( Options::PLUGIN_VERSION );
$fonts = \ucare\fonts();

$primary_font   = get_option( Options::PRIMARY_FONT, \ucare\Defaults::PRIMARY_FONT );
$secondary_font = get_option( Options::SECONDARY_FONT, \ucare\Defaults::SECONDARY_FONT );

?>

<html>

    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="<?php _e( 'uCare Support System', 'ucare' ); ?>"/>
        <link href="<?php echo $url . 'assets/lib/bootstrap/css/bootstrap.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/scrollingTabs/scrollingTabs.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/dropzone/css/dropzone.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/lightGallery/css/lightgallery.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">

        <?php if( array_key_exists ( $primary_font, $fonts ) && array_key_exists ( $secondary_font, $fonts ) ) : ?>
            
            <?php if ( $primary_font == $secondary_font ) : ?>
        
                <link href="https://fonts.googleapis.com/css?family=<?php esc_attr_e( $fonts[ $primary_font ] ); ?>" rel="stylesheet">        
            
            <?php else : ?>
                
                <link href="https://fonts.googleapis.com/css?family=<?php esc_attr_e( $fonts[ $primary_font ] . '|' . $fonts[ $secondary_font ] ); ?>" rel="stylesheet">
                
            <?php endif; ?>
        
        <?php endif; ?>
             
        <link href="<?php echo $url . 'assets/css/style.css' . '?ver=' . $ver; ?>" rel="stylesheet">
        <script src="<?php echo home_url( 'wp-includes/js/jquery/jquery.js' ) . '?ver=' . $ver; ?>"></script>
        <link href="<?php echo get_option( Options::FAVICON ); ?>" rel="icon">

        <?php include_once Plugin::plugin_dir( \ucare\PLUGIN_ID ) . '/assets/css/dynamic.php'; ?>
    
        <!-- Please keep jQuery in the header -->
        <script src="<?php echo home_url( 'wp-includes/js/jquery/jquery.js' ) . '?ver=' . $ver; ?>"></script>
    </head>
    <body>
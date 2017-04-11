<?php

use SmartcatSupport\Plugin;

$url = Plugin::plugin_url( \SmartcatSupport\PLUGIN_ID );

?>

<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="<?php _e( 'uCare Support System', \SmartcatSupport\PLUGIN_ID ) ?>"/>
        <link href="<?php echo $url . 'assets/lib/bootstrap/css/bootstrap.min.css'; ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/scrollingTabs/scrollingTabs.min.css'; ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/dropzone/css/dropzone.min.css'; ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/lightGallery/css/lightgallery.min.css'; ?>" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/style.css' ?>" rel="stylesheet">
        <script src="<?php echo home_url( 'wp-includes/js/jquery/jquery.js' ); ?>"></script>
        <?php include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/assets/css/dynamic.php'; ?>

    </head>
    <body>

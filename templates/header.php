<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

$url = Plugin::plugin_url( \SmartcatSupport\PLUGIN_ID );
$ver = get_option( Option::PLUGIN_VERSION );

?>

<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="<?php _e( 'uCare Support System', \SmartcatSupport\PLUGIN_ID ); ?>"/>
    <link href="<?php echo $url . 'assets/lib/bootstrap/css/bootstrap.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">
    <link href="<?php echo $url . 'assets/lib/scrollingTabs/scrollingTabs.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">
    <link href="<?php echo $url . 'assets/lib/dropzone/css/dropzone.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">
    <link href="<?php echo $url . 'assets/lib/lightGallery/css/lightgallery.min.css' . '?ver=' . $ver; ?>" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <link href="<?php echo $url . 'assets/css/style.css' . '?ver=' . $ver; ?>" rel="stylesheet">
    <script src="<?php echo home_url( 'wp-includes/js/jquery/jquery.js' ) . '?ver=' . $ver; ?>"></script>

    <?php include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/assets/css/dynamic.php'; ?>

</head>
<body>
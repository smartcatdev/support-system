<?php

use SmartcatSupport\Plugin;

$url = Plugin::plugin_url( \SmartcatSupport\PLUGIN_ID );

?>

<html>
    <head>
        <link href="<?php echo $url . '/assets/lib/bootstrap/css/bootstrap.min.css'; ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/scrollingTabs/scrollingTabs.min.css'; ?>" rel="stylesheet">
        
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/style.css' ?>" rel="stylesheet">

        <?php include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/assets/css/dynamic.php'; ?>

    </head>
    <body>

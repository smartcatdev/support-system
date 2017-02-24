<?php

use SmartcatSupport\Plugin;

$url = Plugin::plugin_url( \SmartcatSupport\PLUGIN_ID );

?>

<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/common.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/style.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/icons.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/datatables/datatables.min.css' ?>" rel="stylesheet"/>

        <?php include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/assets/css/dynamic_styles.php'; ?>
    </head>
    <body>

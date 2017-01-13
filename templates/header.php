<?php

use SmartcatSupport\Plugin;
use const SmartcatSupport\PLUGIN_ID;
use SmartcatSupport\descriptor\Option;

$url = Plugin::plugin_url( PLUGIN_ID );
$primary_color = esc_attr( get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ) );
$hover_color = esc_attr( get_option( Option::HOVER_COLOR, Option\Defaults::HOVER_COLOR ) );
$secondary_color = esc_attr( get_option( Option::SECONDARY_COLOR, Option\Defaults::SECONDARY_COLOR ) );
?>

<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/common.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/style.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/icons.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/datatables/datatables.min.css' ?>" rel="stylesheet"/>
        <link href="<?php echo $url . 'assets/lib/modal/jquery.modal.min.css' ?>" rel="stylesheet"/>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
        <script src="<?php echo $url . 'assets/lib/datatables/datatables.min.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/lib/modal/jquery.modal.min.js' ?>" ></script>
        <script src="<?php echo includes_url( 'js/tinymce/' ) . 'wp-tinymce.php' ?>" ></script>
        <script>SupportSystem = { ajaxUrl : "<?php echo admin_url( 'admin-ajax.php' ); ?>" };</script>
        <script src="<?php echo $url . 'assets/js/app.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/script.js' ?>" ></script>
        
        <style type="text/css">
            
            /* Primary color */
            
            .button-primary,
            .trigger,
            input[type="submit"]{
                background: #<?php echo $primary_color; ?>;
            }
            
            table th {
                color: #<?php echo $primary_color; ?>;
            }
            
            #support-login-wrapper input[type="text"]:focus,
            #support-login-wrapper input[type="email"]:focus,
            #support-login-wrapper input[type="password"]:focus{
                border: 1px solid #<?php echo $primary_color; ?>;
            }
            
            /* Secondary color */
            #navbar,
            .ui-tabs .ui-tabs-nav{
                background: #<?php echo $secondary_color; ?>;
            }
            
        </style>
        
    </head>
    <body>
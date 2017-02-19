<?php

use SmartcatSupport\Plugin;
use SmartcatSupport\descriptor\Option;

$url = Plugin::plugin_url( \SmartcatSupport\PLUGIN_ID );
$primary_color = esc_attr( get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ) );
$hover_color = esc_attr( get_option( Option::HOVER_COLOR, Option\Defaults::HOVER_COLOR ) );
$secondary_color = esc_attr( get_option( Option::SECONDARY_COLOR, Option\Defaults::SECONDARY_COLOR ) );
$tertiary_color = esc_attr( get_option( Option::TERTIARY_COLOR, Option\Defaults::TERTIARY_COLOR ) );
?>

<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/common.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/css/style.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/icons.css' ?>" rel="stylesheet">
        <link href="<?php echo $url . 'assets/lib/datatables/datatables.min.css' ?>" rel="stylesheet"/>
        <link href="<?php echo $url . 'assets/lib/modal/jquery.modal.min.css' ?>" rel="stylesheet"/>

        <script>
            var Globals = {
                ajaxUrl: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                strings: <?php echo json_encode( array(
                            'register_form_toggle' => __( get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ), \SmartcatSupport\PLUGIN_ID ),
                        ) ); ?>
            };
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
        <script src="<?php echo $url . 'assets/lib/datatables/datatables.min.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/lib/modal/jquery.modal.min.js' ?>" ></script>
        <script src="<?php echo includes_url( 'js/tinymce/' ) . 'wp-tinymce.php' ?>" ></script>

        <script src="<?php echo $url . 'assets/js/app.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/comments.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/tickets.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/settings.js' ?>" ></script>



        <style type="text/css">
            
            /* Primary color */
            
            .button-primary,
            .trigger,
            input[type="submit"],
            .button{
                background: <?php echo $primary_color; ?>;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button.current{
                background: <?php echo $primary_color; ?> !important;                
            }
            
            table th,
            a{
                color: <?php echo $primary_color; ?>;
            }
            
            #support-login-wrapper input[type="text"]:focus,
            #support-login-wrapper input[type="email"]:focus,
            #support-login-wrapper input[type="password"]:focus{
                border: 2px solid <?php echo $primary_color; ?>;
            }
            
            .support_form .form_field:focus,
            .support_settings .form_field:focus{
                border-color: <?php echo $primary_color; ?>;
            }
            
            /* Secondary color */
            #navbar,
            .ui-tabs .ui-tabs-nav {
                background: <?php echo $secondary_color; ?>;
            }
            
            /* Hover color */
            
            a:focus,a:hover{
                color: <?php echo $hover_color; ?>;
            }
            
            .button-primary:hover,
            .button-primary:focus,
            input[type="submit"]:hover,
            input[type="submit"]:focus {
                background: <?php echo $hover_color; ?>;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button:hover{
                background: <?php echo $hover_color; ?> !important;                
            }
            
            /* Tertiary color */
            #support_system table .action-icon,
            .ui-tabs .ui-tabs-nav li .ui-icon-close:hover{
                background: <?php echo $tertiary_color; ?>;
            }
        </style>
        
    </head>
    <body>

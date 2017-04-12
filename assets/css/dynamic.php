<?php

use SmartcatSupport\descriptor\Option;

$primary_color   = esc_attr( get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ) );
$hover_color     = esc_attr( get_option( Option::HOVER_COLOR, Option\Defaults::HOVER_COLOR ) );
$secondary_color = esc_attr( get_option( Option::SECONDARY_COLOR, Option\Defaults::SECONDARY_COLOR ) );
$tertiary_color  = esc_attr( get_option( Option::TERTIARY_COLOR, Option\Defaults::TERTIARY_COLOR ) );

$primary_color_rgb = \SmartcatSupport\proc\hex2rgb( $primary_color );
$secondary_color_rgb = \SmartcatSupport\proc\hex2rgb( $secondary_color );

?>

<style type="text/css">

    /* Primary color */
    .button, .button-primary,
    input[type="submit"],
    .pagination .active a {
        background-color: <?php echo $primary_color; ?>;
        border-color: <?php echo $primary_color; ?>;
    }

    #filter-toggle.active {
        background: <?php echo $tertiary_color; ?>;
    }
    
    /*
    .panel-default > .panel-heading {
        background: rgba( <?php echo $secondary_color_rgb[0]; ?>,<?php echo $secondary_color_rgb[1]; ?>,<?php echo $secondary_color_rgb[2]; ?>, 0.3 );
    }
    
    .discussion-area .wrapper .current-user .panel-heading{
        background: rgba( <?php echo $primary_color_rgb[0]; ?>,<?php echo $primary_color_rgb[1]; ?>,<?php echo $primary_color_rgb[2]; ?>, 0.3 );
    }
    */  

    #filter-toggle .toggle-label:after {
        content: "<?php _e( 'Apply Filters', \SmartcatSupport\PLUGIN_ID ); ?>";
    }

    #filter-toggle.active .toggle-label:after {
        content: "<?php _e( 'Applied', \SmartcatSupport\PLUGIN_ID ); ?>";
    }

    #support-login-wrapper input[type="text"]:focus,
    #support-login-wrapper input[type="email"]:focus,
    #support-login-wrapper input[type="password"]:focus{
        border: 2px solid <?php echo $primary_color; ?>;
    }

    .form-control:focus {
        border-color: <?php echo $primary_color; ?>;
    }

    /* Secondary color */
    #navbar {
        background: <?php echo $secondary_color; ?>;
    }

    /* Hover color */
    a:focus, a:hover{
        color: <?php echo $hover_color; ?>;
    }

    .button-primary:hover,
    .button-primary:focus,
    input[type="submit"]:hover,
    input[type="submit"]:focus {
        background: <?php echo $hover_color; ?>;
    }

    .pagination .active a:hover {
        background: <?php echo $hover_color; ?> !important;
        border-color: <?php echo $hover_color; ?> !important;
    }

    /* Tertiary color */

    .carousel-caption {
        color: <?php echo $tertiary_color; ?>
    }

    #support-login-page {
        background-image: url(<?php echo get_option( Option::LOGIN_BACKGROUND, Option\Defaults::LOGIN_BACKGROUND ); ?> )
    }


</style>

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

    #filter-toggle.active,
    .widget-wrapper > div{
        background: <?php echo $tertiary_color; ?>;
    }
    
    #tickets-container .ticket-title,
    .nav-tabs .tab .title{
        color: <?php echo $secondary_color; ?>;
    }
    
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
    #navbar,
    #footer{
        background: <?php echo $secondary_color; ?>;
    }

    /* Hover color */
    a {
        color: <?php echo $primary_color; ?>;
    }
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
        color: <?php echo $tertiary_color; ?>;
    }

    #support-login-page {
        background-image: url(<?php echo get_option( Option::LOGIN_BACKGROUND, Option\Defaults::LOGIN_BACKGROUND ); ?> )
    }
    
    /* - Statistics - */
    #statistics-container {
        width: 100%;
        display: table;
    }

    #statistics-container .stat-tab {
        width: 20%;
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        padding: 5px;
    }
    
    #statistics-container .stat-tab .grad-bubble {
        margin-top: 15px;
        display: inline-block;
        height: 80px;
        width: 80px;
        line-height: 50px;
    }
    
    #statistics-container .stat-tab .stat-label {
        text-transform: uppercase;
        letter-spacing: .125em;
        min-height: 35px;
        z-index: 99;
        position: relative;
    }
    
    #statistics-container .stat-tab h4 {
        font-size: 26px;
        color: #fff;
        display: inline-block;
        height: 50px;
        border-radius: 3px;
        line-height: 50px;
        text-align: center;
        z-index: 99;
        position: relative;
    }
 
    #statistics-container .stat-tab .inner {
        padding: 10px 30px;
        min-height: 105px;
        background: <?php echo $primary_color; ?>;
/*        background: -webkit-linear-gradient( top, <?php echo $primary_color; ?>, <?php echo $secondary_color; ?> );
        background: linear-gradient(to bottom, <?php echo $primary_color; ?>, <?php echo $secondary_color; ?> );*/
        text-align: left;
        color: #fff;
        position: relative;
        overflow: hidden;
        border-radius: 3px;
    }
    
    #statistics-container .stat-tab span.glyphicon {
        position: absolute;
        right: -15px;
        top: 40px;
        font-size: 95px;
        opacity: .25;
        color: white;
    }

    @media (max-width: 767px) {
        
        #statistics-container {
            width: 100%;
            display: block;
        }
        
        #statistics-container .stat-tab {
            width: 100%;
            display: block;
        }
        
        #statistics-container .stat-tab .inner {
            display: table;
            vertical-align: middle;
            width: 100%;
            min-height: 0;
            padding: 0 30px 0 15px;
        }
        
        #statistics-container .stat-tab .inner h4 {
            margin: 0 30px 0 0;
            display: table-cell;
            vertical-align: middle;
            width: 85px;
            text-align: left;
            font-size: 20px;
        }
        
        #statistics-container .stat-tab .inner .stat-label {
            font-size: 10px;
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            padding-right: 10px;
        }
        
        #statistics-container .stat-tab span.glyphicon {
            font-size: 20px;
            top: 15px;
            right: 10px;
        }
        
    }
    

</style>

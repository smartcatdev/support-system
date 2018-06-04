<?php

use ucare\Options;

$primary_color   = esc_attr( get_option( Options::PRIMARY_COLOR, \ucare\Defaults::PRIMARY_COLOR ) );
$hover_color     = esc_attr( get_option( Options::HOVER_COLOR, \ucare\Defaults::HOVER_COLOR ) );
$secondary_color = esc_attr( get_option( Options::SECONDARY_COLOR, \ucare\Defaults::SECONDARY_COLOR ) );
$tertiary_color  = esc_attr( get_option( Options::TERTIARY_COLOR, \ucare\Defaults::TERTIARY_COLOR ) );

$primary_color_rgb      = \ucare\proc\hex2rgb( $primary_color );
$secondary_color_rgb    = \ucare\proc\hex2rgb( $secondary_color );

$primary_font   = esc_attr( get_option( Options::PRIMARY_FONT, \ucare\Defaults::PRIMARY_FONT ) );
$secondary_font = esc_attr( get_option( Options::SECONDARY_FONT, \ucare\Defaults::SECONDARY_FONT ) );

?>

<style type="text/css">

    /* Primary color */
    .button, .button-primary,
    input[type="submit"],
    .pagination .active a {
        background-color: <?php echo $primary_color; ?>;
        border-color: <?php echo $primary_color; ?>;
    }

    .ajax-loader .dot {
        background-color: <?php echo $primary_color; ?>;
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
        content: "<?php _e( 'Apply Filters', 'ucare' ); ?>";
    }

    #filter-toggle.active .toggle-label:after {
        content: "<?php _e( 'Filters Applied', 'ucare' ); ?>";
    }

    #support-login-wrapper input[type="text"]:focus,
    #support-login-wrapper input[type="email"]:focus,
    #support-login-wrapper input[type="password"]:focus{
        border: 1px solid <?php echo $primary_color; ?>;
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
    a,.carousel-caption,
    .carousel-control.left .glyphicon,
    .carousel-control.right .glyphicon {
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

    #support-login-page {
        background-image: url(<?php echo get_option( Options::LOGIN_BACKGROUND, \ucare\Defaults::LOGIN_BACKGROUND ); ?> )
    }
    
    /* Primary Heading Font */
    
    body{
        font-family: <?php echo $primary_font; ?> !important;
    }
    
    .sidebar .ticket-details .panel-body .lead,
    .customer-details .panel-body strong {
        font-family: <?php echo $primary_font; ?>;
    }
    
    /* Secondary Body Font */
    
    form select,
    input#search,
    form input, form textarea,
    .discussion-area > .ticket .panel-body,
    .ticket-list .ticket .media-body .text-muted,
    #navbar .clock,
    form .form-check label,
    .dz-message,
    .discussion-area .comments .comment .comment-content,
    .comments .comment .meta .text-muted,
    .purchase-info,
    .customer-details .panel-body,
    .sidebar .ticket-details .panel-body,
    .discussion-area .editor .preview,
    .modal-body {
        font-family: <?php echo $secondary_font; ?> !important;
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

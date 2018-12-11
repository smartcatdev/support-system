<?php

namespace ucare;

?>


<style>
    #ucare-tutorial .wrap {
        padding: 15px;
    }

    #ucare-tutorial .postbox {
        padding: 15px;
    }

    #ucare-tutorial h1 {
        font-size: 40px;
        line-height: 44px;
    }

    #ucare-tutorial h3 {
        font-size: 24px;
        line-height: 28px;
    }
    
    #ucare-tutorial p,
    #ucare-tutorial li{
        font-size: 16px;
        line-height: 18px;
    }

</style>

<div id="ucare-tutorial">

    <div class="wrap">
        
        <h2></h2>
        
        <div class="postbox">

            <h1><?php echo __( 'Welcome to uCare Support System ', 'ucare' ) . PLUGIN_VERSION ?></h1>

            <p><?php _e( 'uCare is the most intelligent, automated and user friendly WordPress support plugin.', 'ucare' ); ?></p>         

            <hr>

            <h3><?php _e( 'Say Hello to Custom Fields & Automation!', 'ucare' ); ?></h3>
            <p><?php _e( 'We have added several new features to uCare, including Custom Fields and Auto Agent! These tools will '
                    . 'empower you to customize the ticket form the way you see fit, as well as provide instant, automated & pre-defined'
                    . 'responses to your users tickets based on the ticket criteria', 'ucare' ); ?></p>
            
            <img src="<?php esc_url_e( resolve_url( 'assets/images/ucare-auto-agent.png' ) ); ?>"/>
            
            <p>
                <a class="button-primary"
                   href="https://ucaresupport.com/add-ons?utm_source=plugin-tutorial-page&utm_medium=plugin&utm_campaign=ucareAddonsPage&utm_content=ucare+pro"
                   target="_BLANK">
                    <?php _e( 'View uCare Pro Add-ons', 'ucare' ); ?>
                </a>
            </p>

            <hr>
            
            <h3><?php _e( 'New Features in 1.7', 'ucare' ); ?></h3>
            <ol>
                <li><?php _e( 'Unique pages for Registration/Login, Create Ticket, and Edit Profile', 'ucare' ); ?></li>
                <li><?php _e( 'Improved flow for registration and login', 'ucare' ); ?></li>
                <li><?php _e( 'GDPR tools', 'ucare' ); ?></li>
                <li><?php _e( 'Improved login & registration through shortcode', 'ucare' ); ?></li>
                <li><?php _e( 'Improved UI & UX for ticket creation', 'ucare' ); ?></li>
                <li><?php _e( 'Added support for PHP v7.2', 'ucare' ); ?></li>
                <li><?php _e( 'New add-ons', 'ucare' ); ?></li>
            </ol>

            <h3><?php _e( 'New uCare Pro features', 'ucare' ); ?></h3>
            <ol>
                <li><b><?php _e( 'Custom Fields', 'ucare' ); ?></b>: <?php _e( 'Add textboxes, dropdowns, radio optiosn or text areas and customize your ticket form as you want with custom fields.', 'ucare' ); ?></li>
                <li><b><?php _e( 'Auto Agent automated responder', 'ucare' ); ?></b>: <?php _e( 'Create pre-defined responses, and set rules on when they get used. Then let the Auto Agent automatically answer tickets for you!', 'ucare' ) ?></li>
                <li><b><?php _e( 'Social Login', 'ucare' ); ?></b></li>
                <li><b><?php _e( 'Advanced WooCommerce Integration', 'ucare' ); ?></b></li>
                <li><b><?php _e( 'Software Licensing Integration for EDD', 'ucare' ); ?></b></li>
            </ol>
            
            <h3><?php _e( 'Custom Fields Preview', 'ucare' ); ?></h3>
            <iframe width="500" height="315" src="https://www.youtube.com/embed/CTHwOB-K0i4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            
            <h3><?php _e( 'Auto Agent Preview', 'ucare' ); ?></h3>
            <iframe width="500" height="315" src="https://www.youtube.com/embed/c27p7PfbGgk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>

    </div>
    
</div>
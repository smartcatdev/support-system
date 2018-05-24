<?php

namespace ucare;

?>

<div class="sidebar ucare-admin-sidebar">

    <div class="widget">
        <?php sc_marketing_message( Marketing::SETTINGS_SIDEBAR_UCARE_PRO ); ?>
    </div>
    
    <div class="widget">
        
        <div class="ucare-sidebar-widget">

                <div class="widget-header">
                    
                    <a class="button button-primary button-cta" href="<?php echo esc_url( admin_url( 'theme-install.php?search=buildr') ) ?>"><?php _e( 'Get it now', 'ucare' ); ?></a>
                    
                    <h3>
                        <span class="dashicons dashicons-admin-appearance"></span> <?php _e( 'Try Buildr Theme', 'buildr' ); ?>
                    </h3>

                </div>

                <div class="widget-content">
                    
                    <p><?php _e( 'We also build themes! Check out our theme Buildr. The most versatile and customizable theme on WordPress.org, completely free!', 'ucare' ); ?></p>
                    <p>
                        <a href="<?php echo esc_url( admin_url( 'theme-install.php?search=buildr') ) ?>"><img src="https://i.imgur.com/nJHSD5W.jpg" title="source: imgur.com" /></a>
                    </p>
                    
                </div>

        </div>
    </div>

    <div class="widget">
        <?php sc_marketing_message( Marketing::SETTINGS_SIDEBAR_FEEDBACK ); ?>
    </div>

</div>

<div class="clear"></div>
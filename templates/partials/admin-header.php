<?php

namespace ucare;

?>

<div id="ucare-settings-header">

    <div class="inner">

        <div class="ucare-logo">
            <img src="<?php echo esc_url( plugin_url( 'assets/images/admin-icon-grey.png' ) ); ?>">
        </div>

        <p class="page-title">
            <span class="title-text"><?php _e( 'uCare Customer Support', 'ucare' ); ?></span>
            <span class="small version-number">v<?php esc_html_e( PLUGIN_VERSION ); ?></span>
        </p>

    </div>

    <p class="links">
        <a href="<?php echo esc_url( support_page_url() ); ?>" target="_blank">
            <?php _e( 'Go to the Helpdesk', 'ucare' ); ?>
        </a>
        |
        <a href="https://ucaresupport.com/documentation/?utm_source=plugin-settings-page&utm_medium=plugin&utm_campaign=uCareSettingsPage&utm_content=Plugin+Documentation" target="_blank">
            <?php _e( 'Documentation', 'ucare' ); ?>
        </a>
        |
        <a href="https://ucaresupport.com/add-ons/?utm_source=plugin-settings-page&utm_medium=plugin&utm_campaign=uCareSettingsPage&utm_content=Plugin+Documentation" target="_blank">
            <?php _e( 'Add-ons', 'ucare' ); ?>
        </a>
    </p>
    
</div>

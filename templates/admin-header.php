<div id="ucare-settings-header">
    
    <div class="alignleft header-component">
        <img src="<?php echo \ucare\plugin_url() ?>assets/images/admin-icon.png" />
    </div>
    
    <div class="alignleft header-component">
        <h1><?php _e( 'uCare Support Help Desk', 'ucare' ); ?></h1>
        <p><?php _e( 'Plugin Settings', 'ucare' ); ?></p>
    </div>
    
    <div class="alignright header-component">
        <a href="https://ucaresupport.com/documentation/?utm_source=plugin-settings-page&utm_medium=plugin&utm_campaign=uCareSettingsPage&utm_content=Plugin+Documentation" target="_blank" class="ucare-button secondary"><?php _e( 'Plugin Documentations', 'ucare' ); ?></a>
        <a href="https://ucaresupport.com/add-ons/?utm_source=plugin-settings-page&utm_medium=plugin&utm_campaign=uCareSettingsPage&utm_content=Plugin+Documentation" target="_blank" class="ucare-button secondary"><?php _e( 'Add-ons', 'ucare' ); ?></a>
    </div>
    
    <div class="clear"></div>
    
</div>

<div class="ucare-url">
    <p>
        <?php echo __( 'Your help desk URL is: ', 'ucare' ); ?>
        <a href="<?php echo esc_url( \ucare\support_page_url() ); ?>"><?php echo esc_url( \ucare\support_page_url() ); ?></a>
    </p>
</div>
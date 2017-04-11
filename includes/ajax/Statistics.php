<?php

namespace SmartcatSupport\ajax;

class Statistics extends AjaxComponent {
    
    /**
     * Ajax action for loading statistics into the dash
     * 
     * @since 1.1.0
     */
    public function load_statistics() {
        
        $html = $this->render( $this->plugin->template_dir . '/statistics.php', array() );
        
        wp_send_json(
            array(
                'success' => true,
                'content' => $html
            )
        );
        
    }
    
    
    public function subscribed_hooks() {
        return parent::subscribed_hooks( array(
            'wp_ajax_support_display_statistics' => array( 'load_statistics' ),
        ) );
    }
    
    

    
}

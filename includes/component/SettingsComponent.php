<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use SmartcatSupport\util\TemplateUtils;

class SettingsComponent extends AbstractComponent {

    public function settings() {
        if( current_user_can( 'view_support_tickets' ) ) {
            wp_send_json( TemplateUtils::render_template( $this->plugin->template_dir . '/settings_modal.php' ) );
        }
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_settings' => array( 'settings' ),
        );
    }
}

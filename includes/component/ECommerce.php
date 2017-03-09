<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use SmartcatSupport\descriptor\Option;

class ECommerce extends AbstractComponent {

    public function start() {
        if( $this->plugin->woo_active ) {
            \SmartcatSupport\util\add_caps( 'customer' );
        }
    }

    /**
     * Configure capabilities for subscriber role when EDD is enabled.
     *
     * @param $val
     * @return mixed
     * @since 1.0.0
     */
    public function configure_user_caps( $val ) {
        if ( $val == 'on' ) {

            \SmartcatSupport\util\add_caps( 'subscriber' );
            \SmartcatSupport\util\add_caps( 'customer' );

        } else {

            \SmartcatSupport\util\remove_caps( 'customer' );
            \SmartcatSupport\util\remove_caps( 'subscriber' );

        }

        return $val;
    }

    /**
     * Hooks that the Component is subscribed to.
     *
     * @see \smartcat\core\AbstractComponent
     * @see \smartcat\core\HookSubscriber
     * @return array $hooks
     * @since 1.0.0
     */
    public function subscribed_hooks() {
        return array(
            'pre_update_option_' . Option::ECOMMERCE => array( 'configure_user_caps' )
        );
    }
}

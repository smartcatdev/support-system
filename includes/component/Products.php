<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use SmartcatSupport\descriptor\Option;

class Products extends AbstractComponent {

    /**
     * Configure capabilities for customer role when WooCommerce is enabled.
     *
     * @param $val
     * @return mixed
     * @since 1.0.0
     */
    public function configure_customer_caps( $val ) {
        if( $this->plugin->woo_active ) {
            if( $val == 'on' ) {
                \SmartcatSupport\util\user\append_role_caps( get_role( 'customer' ) );
            } else {
                \SmartcatSupport\util\user\remove_role_caps( get_role( 'customer' ) );
            }
        }

        return $val;
    }

    /**
     * Configure capabilities for subscriber role when EDD is enabled.
     *
     * @param $val
     * @return mixed
     * @since 1.0.0
     */
    public function configure_subscriber_caps( $val ) {
        if( $this->plugin->edd_active ) {
            if ( $val == 'on' ) {
                \SmartcatSupport\util\user\append_role_caps( get_role( 'subscriber' ) );
            } else {
                \SmartcatSupport\util\user\remove_role_caps( get_role( 'subscriber' ) );
            }
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
            'pre_update_option_' . Option::ECOMMERCE_INTEGRATION => array( 'configure_subscriber_caps' ),
            'pre_update_option_' . Option::ECOMMERCE_INTEGRATION => array( 'configure_customer_caps' ),
        );
    }
}

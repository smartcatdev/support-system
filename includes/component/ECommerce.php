<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use SmartcatSupport\descriptor\Option;

class ECommerce extends AbstractComponent {

    public function start()  {error_log("asdf");
        if( $this->plugin->woo_active && get_option( Option::ECOMMERCE_INTEGRATION, Option\Defaults::ECOMMERCE_INTEGRATION ) == 'on' ) {
            $customer = get_role( 'customer' );

            if( !empty( $customer ) ) {
                \SmartcatSupport\util\add_role_caps( $customer );
            }
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
        $customer = get_role( 'customer' );
        $subscriber = get_role( 'subscriber' );

        if ( $val == 'on' ) {

            if( !empty( $customer ) ) {
                \SmartcatSupport\util\add_role_caps( $customer );
            }

            \SmartcatSupport\util\add_role_caps( $subscriber );
        } else {

            if( !empty( $customer ) ) {
                \SmartcatSupport\util\remove_role_caps( $customer );
            }

            \SmartcatSupport\util\remove_role_caps( $subscriber );
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
            'pre_update_option_' . Option::ECOMMERCE_INTEGRATION => array( 'configure_user_caps' )
        );
    }
}

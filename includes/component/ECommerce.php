<?php

namespace ucare\component;

use smartcat\core\AbstractComponent;
use ucare\Options;

class ECommerce extends AbstractComponent {

    public function start() {
        if( $this->plugin->woo_active ) {
            \ucare\util\add_caps( 'customer' );
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

            \ucare\util\add_caps( 'subscriber' );
            \ucare\util\add_caps( 'customer' );

        } else {

            \ucare\util\remove_caps( 'customer' );
            \ucare\util\remove_caps( 'subscriber' );

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
            'pre_update_option_' . Options::ECOMMERCE => array( 'configure_user_caps' )
        );
    }
}

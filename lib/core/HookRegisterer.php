<?php

namespace smartcat\core;

if( !interface_exists( '\smartcat\core\HookRegister' ) ) :

/**
 * Interface that a HookSubscriber registers with.
 *
 * @package smartcat\core
 * @author Eric Green <eric@smartcat.ca>
 */
interface HookRegisterer {

    /**
     * Register a listener with the Plugin API.
     *
     * @param HookSubscriber $listener
     */
    public function add_api_subscriber( HookSubscriber $listener );

    /**
     * Unregister a subscriber from the Plugin API.
     *
     * @param HookSubscriber $listener
     */
    public function remove_api_subscriber( HookSubscriber $listener );
}

endif;
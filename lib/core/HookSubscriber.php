<?php

namespace smartcat\core;

if( !interface_exists( '\smartcat\core\HookSubscriber' ) ) :

/**
 * Interface for objects that subscribe to hooks in the Plugin API.
 *
 * @package smartcat\core
 * @author Eric Green <eric@smartcat.ca>
 */
interface HookSubscriber {

    /**
     * The array of hooks the object is subscribed to.
     *
     * @return array Array of hooks.
     *
     *    Example: array( 'wp_loaded' => 'do_stuff' )
     *    Example: array( 'wp_loaded' => array( 'do_stuff', 10, 2 ) )
     */
    public function subscribed_hooks();
}

endif;